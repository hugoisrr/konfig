<?php

namespace App\Http\Controllers;

use App\Model\Course;
use App\Model\Language;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ZipController extends Controller
{
    public function __construct()
    {
        $this->middleware('client');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadBaseCourse(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $client = CourseApiController::getClientFromToken($request);
        $language = Language::where('language_code', $request->input('lang'))->first();
        (is_null($language)) ? $languageBaseCode = 'en'
            : $languageBaseCode = $request->input('lang');

        switch ($client->id){
            // Download BaseCourse for Google
            case getenv('PASSPORT_ANDROID_ACCESS_CLIENT_ID'):
            case getenv('PASSPORT_DEV_ANDROID_ACCESS_CLIENT_ID'):
                return $this->getLanguageBaseCourse($languageBaseCode, true);

            // Download BaseCourse for iOS
            case getenv('PASSPORT_IOS_ACCESS_CLIENT_ID'):
            case getenv('PASSPORT__DEV_IOS_ACCESS_CLIENT_ID'):
                return $this->getLanguageBaseCourse($languageBaseCode);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|mixed|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Google\Exception
     */
    public function downloadCourse(Request $request)
    {
        $client = CourseApiController::getClientFromToken($request);
        $checkPermission = new CheckPermissionController();
        $course = Course::find($request->input('id'));

        switch ($client->id){
            case getenv('PASSPORT_ANDROID_ACCESS_CLIENT_ID'):
            case getenv('PASSPORT_DEV_ANDROID_ACCESS_CLIENT_ID'):
                return $this->getValidLanguageCourseGoogle($request);
            case getenv('PASSPORT_IOS_ACCESS_CLIENT_ID'):
            case getenv('PASSPORT__DEV_IOS_ACCESS_CLIENT_ID'):
                if ($course->type == 'Course') {
                    // Get Course zip file with default method of compression
                    if ($checkPermission->appleReceiptValidation(
                            $request,CheckPermissionController::VERIFY_RECEIPT_END_POINT) instanceof JsonResponse) {
                        return $checkPermission->appleReceiptValidation(
                            $request,
                            CheckPermissionController::VERIFY_RECEIPT_END_POINT
                        );
                    } elseif ($checkPermission->appleReceiptValidation(
                            $request,CheckPermissionController::VERIFY_RECEIPT_END_POINT)['status'] == CheckPermissionController::STATUS_SUCCESS) {
                        $arrayResponse = $checkPermission->appleReceiptValidation($request,CheckPermissionController::VERIFY_RECEIPT_END_POINT);
                        return $this->getValidLanguageCourseApple($request, $arrayResponse, $course);
                    } elseif ($checkPermission->appleReceiptValidation($request,CheckPermissionController::VERIFY_RECEIPT_END_POINT)['status'] == CheckPermissionController::STATUS_FOR_SANDBOX) {
                        $arrayResponse = $checkPermission->appleReceiptValidation($request,CheckPermissionController::SANDBOX_TESTING_END_POINT);
                        if ($arrayResponse['status'] == CheckPermissionController::STATUS_SUCCESS) {
                            return $this->getValidLanguageCourseApple($request, $arrayResponse, $course);
                        }
                    }
                } else{
                    $languageCourse = self::getLanguage($request);
                    return $this->getLanguageCourse($course->id, $languageCourse);
                }
                break;
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Google\Exception
     */
    private function getValidLanguageCourseGoogle(Request $request)
    {
        $languageCourse = $this->getLanguage($request);
        $course = Course::find($request->input('id'));
        if (is_null($course)) {
            Log::error('No course found with the id: ' . $request->input('id'));
            $returnErrorData = array(
                'error' => 2
            );
            return response()->json($returnErrorData, 400);
        }

        // Validate if course is type Course or Test
        if ($course->type == 'Course') {
            if (!isset($request['subscriptions'])) {
                $returnErrorData = array(
                    'error' => 2
                );
                return response()->json($returnErrorData, 400);
            }
            $subscriptions = $request->input('subscriptions');
            if (gettype($subscriptions) == 'string') {
                $subscriptions = json_decode($subscriptions, true);
            }

            if (empty($subscriptions)) {
                Log::info('Google user does not have subscription for Course id: ' . $course->id);
                $returnErrorData = array(
                    'error' => 6
                );
                return response()->json($returnErrorData, 403);
            }

            $googleClient = new \Google_Client;
            $googleClient->setAuthConfig(getenv('GOOGLE_SERVICE_CREDENTIALS'));
            $googleClient->setScopes(CheckPermissionController::GOOGLE_AUTHORIZATION_SCOPES);

            $googleService = new \Google_Service_AndroidPublisher($googleClient);

            // loop and query for the course that the user has subscription
            foreach ($subscriptions as $subscription) {
                $purchase = $googleService->purchases_subscriptions->get(
                    getenv('GOOGLE_PACKAGE_NAME'),
                    $subscription['id'],
                    $subscription['token']
                );
                $currentTimeMillis = round(microtime(true) * 1000);

                if (($purchase->paymentState == CheckPermissionController::GOOGLE_SUBSCRIPTION_PAYMENT_RECEIVED
                        || $purchase->paymentState == CheckPermissionController::GOOGLE_SUBSCRIPTION_FREE_TRIAL
                        || $purchase->paymentState == CheckPermissionController::GOOGLE_SUBSCRIPTION_PENDING_DEFERRED)
                    && $purchase->expiryTimeMillis > $currentTimeMillis
                ) {
                    $subscriptionCourse = Course::where([
                        ['iap_id_google', '=', $subscription['id']],
                        ['live', true],
                        ['id', '=', $request->input('id')]
                    ])->first();
                    return $this->getLanguageCourse($subscriptionCourse->id, $languageCourse, true);
                }
            }

            Log::info('Google user does not have subscription for Course id: ' . $course->id);
            $returnErrorData = array(
                'error' => 6
            );
            return response()->json($returnErrorData, 403);
        } elseif ($course->type == 'Test') {
            return $this->getLanguageCourse($course->id, $languageCourse, true);
        }
    }

    /**
     * @param Request $request
     * @param $arrayResponse
     * @param Course $course
     * @return JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    private function getValidLanguageCourseApple(Request $request, $arrayResponse, Course $course)
    {
        $languageCourse = $this->getLanguage($request);
        if (is_null($course)) {
            Log::error('No course found with the id: ' . $request->input('id'));
            $returnErrorData = array(
                'error' => 2
            );
            return response()->json($returnErrorData, 400);
        }

        foreach ($arrayResponse['pending_renewal_info'] as $renewal_info) {
            if (isset($renewal_info['expiration_intent'])) {
                switch ($renewal_info['expiration_intent']) {
                    case CheckPermissionController::EXPIRATION_VOLUNTARY_CANCELED_SUBSCRIPTION:
                    case CheckPermissionController::EXPIRATION_BILLING_ERROR_ON_PAYMENT:
                    case CheckPermissionController::EXPIRATION_NOT_AGREE_TO_PRICE_INCREASE:
                    case CheckPermissionController::EXPIRATION_PRODUCT_NOT_AVAILABLE_ON_RENEWAL:
                    case CheckPermissionController::EXPIRATION_UNKNOWN_ERROR:
                        Log::info("Apple's value expiration code: " . $renewal_info['expiration_intent'] .
                            ". For product: " . $renewal_info['product_id']);
                        break;
                }
            }
        }

        // Verify if current user has subscription or not
        foreach ($arrayResponse['pending_renewal_info'] as $renewal_info) {
            if (($renewal_info['auto_renew_status'] == CheckPermissionController::SUBSCRIPTION_WILL_RENEW_AT_END_OF_PERIOD)
                && !isset($renewal_info['expiration_intent'])
            ) {
                // Download zip file with the course requested
                return $this->getLanguageCourse($course->id, $languageCourse);
            }
        }

        Log::info('Apple user does not have subscription for Course id: ' . $course->id);
        $returnErrorData = array(
            'error' => 6
        );
        return response()->json($returnErrorData, 403);
    }

    /**
     * @param int $courseId
     * @param string $languageCourse
     * @param bool $withStoreMethod
     * @return JsonResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    private function getLanguageCourse(int $courseId, string $languageCourse, bool $withStoreMethod = false)
    {
        $zipFile = 'course.zip';
        $zip = new \ZipArchive();
        $zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $path = storage_path('app/public/courses/' . $courseId . '/' . $languageCourse); // Absolute Path of the folder
        if (!is_dir($path)) {
            Log::error("No course's files found for Course: " . $courseId . " with Language: " . $languageCourse);
            $returnErrorData = array(
                'error' => 4
            );
            return response()->json($returnErrorData, 404);
        }

        // Validate if current Course has XML file or not
        if (!file_exists($path . '/tree.xml')) {
            return $this->errorResponseNoXMLFound($courseId, $languageCourse);
        }

        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath(); // Absolute Path of the file
                $relativePath = $courseId . '/' . substr($filePath, strlen($path) + 1); // Creates new path within the Zip file

                $zip->addFile($filePath, $relativePath);
                if ($withStoreMethod) {
                    $zip->setCompressionName($relativePath, \ZipArchive::CM_STORE);
                }
            }
        }
        $zip->close();
        return response()->download($zipFile);
    }

    /**
     * @param string $languageBaseCode
     * @param bool $withStoreMethod
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    private function getLanguageBaseCourse(string $languageBaseCode, bool $withStoreMethod = false): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $zipFile = 'baseCourse.zip';
        $zip = new \ZipArchive();
        $zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        $path = storage_path('app/public/courses/1/' . $languageBaseCode); // Absolute Path of the folder
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($path));

        // Get BaseCourse
        $baseCourse = Course::where('type', '=', 'Base')->first();
        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath(); // Absolute Path of the file
                $relativePath = $baseCourse->id . '/' . substr($filePath, strlen($path) + 1); // Creates new path within the Zip file

                $zip->addFile($filePath, $relativePath);
                if ($withStoreMethod) {
                    $zip->setCompressionName($relativePath, \ZipArchive::CM_STORE);
                }
            }
        }
        $zip->close();
        return response()->download($zipFile);
    }

    /**
     * @param Request $request
     * @return string
     */
    public static  function getLanguage(Request $request): string
    {
        $language = Language::where('language_code', $request->input('lang'))->first();
        (is_null($language)) ? $languageCourse = 'en' : $languageCourse = $language->language_code;

        return $languageCourse;
    }

    /**
     * @param $courseId
     * @param $languageCourse
     * @return JsonResponse
     */
    private function errorResponseNoXMLFound($courseId, $languageCourse): JsonResponse
    {
        Log::error("No XML file found for Course: " . $courseId . " with language: " . $languageCourse);
        $returnErrorData['error'] = -1;

        switch ($languageCourse) {
            case 'de':
                $returnErrorData['message'] = 'Für diesen Kurs sind keine Dateien eingetragen.';
                break;
            case 'fr':
                $returnErrorData['message'] = "Aucun fichier n'est enregistré pour ce cours.";
                break;
            case 'ru':
                $returnErrorData['message'] = "Для этого курса нет файлов.";
                break;
            case 'es':
                $returnErrorData['message'] = "No hay archivos registrados para este curso.";
                break;
            case 'pt':
                $returnErrorData['message'] = "Nenhum arquivo está registrado para este curso.";
                break;
            default:
                $returnErrorData['message'] = 'No files are registered for this course.';
                break;
        }

        return response()->json($returnErrorData, 400);
    }
}
