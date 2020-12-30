<?php

namespace App\Http\Controllers;

use App\Http\Resources\ValidCoursesCollection;
use App\Model\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckPermissionController extends Controller
{
    //    Apple Receipt and Subscription Status
    public const STATUS_SUCCESS                              = 0;
    public const STATUS_METHOD_POST_NOT_USED                 = 21000;
    public const STATUS_NO_LONGER_SENT                       = 21001;
    public const STATUS_DATA_MALFORMED                       = 21002;
    public const STATUS_NOT_AUTHENTICATED                    = 21003;
    public const STATUS_WRONG_SECRET                         = 21004;
    public const STATUS_SERVER_UNAVAILABLE                   = 21005;
    public const STATUS_SUBSCRIPTION_EXPIRED                 = 21006;
    public const STATUS_FOR_SANDBOX                          = 21007;
    public const STATUS_FOR_PRODUCTION                       = 21008;
    public const STATUS_INTERNAL_ERROR                       = 21009;
    public const STATUS_UNAUTHORIZED                         = 21010;

    //    Apple Web Service Endpoints
    public const VERIFY_RECEIPT_END_POINT                    = 'https://buy.itunes.apple.com/verifyReceipt';
    public const SANDBOX_TESTING_END_POINT                   = 'https://sandbox.itunes.apple.com/verifyReceipt';

    //    Apple expiration_intent values
    public const EXPIRATION_VOLUNTARY_CANCELED_SUBSCRIPTION  = 1;
    public const EXPIRATION_BILLING_ERROR_ON_PAYMENT         = 2;
    public const EXPIRATION_NOT_AGREE_TO_PRICE_INCREASE      = 3;
    public const EXPIRATION_PRODUCT_NOT_AVAILABLE_ON_RENEWAL = 4;
    public const EXPIRATION_UNKNOWN_ERROR                    = 5;

    //    Apple renewal status for the auto-renewable subscription
    public const SUBSCRIPTION_WILL_RENEW_AT_END_OF_PERIOD    = 1;
    public const SUBSCRIPTION_IS_TURN_OFF_AUTOMATIC          = 0;

    //    Google Client
    public const GOOGLE_AUTHORIZATION_SCOPES                 = 'https://www.googleapis.com/auth/androidpublisher';
    public const GOOGLE_SUBSCRIPTION_PAYMENT_RECEIVED        = 1;
    public const GOOGLE_SUBSCRIPTION_FREE_TRIAL              = 2;
    public const GOOGLE_SUBSCRIPTION_PENDING_DEFERRED        = 3;

    public function __construct()
    {
        $this->middleware('client');
    }

    /**
     * @param Request $request
     * @return ValidCoursesCollection|JsonResponse|mixed
     * @throws \Google\Exception
     */
    public function index(Request $request)
    {
        $client = CourseApiController::getClientFromToken($request);

        switch ($client->id){

            // Google CheckPermission
            case getenv('PASSPORT_ANDROID_ACCESS_CLIENT_ID'):
                return $this->googleSubscriptionValidation($request);
            case getenv('PASSPORT_DEV_ANDROID_ACCESS_CLIENT_ID'):
                return $this->googleSubscriptionValidation($request, true);

            // Apple CheckPermission
            case getenv('PASSPORT_IOS_ACCESS_CLIENT_ID'):
                if (!isset($request['receipt'])){
                    return self::getTestCourses();
                }else{
                    if (self::appleReceiptValidation($request, self::VERIFY_RECEIPT_END_POINT) instanceof JsonResponse) {
                        return self::appleReceiptValidation($request, self::VERIFY_RECEIPT_END_POINT);
                    } elseif (self::appleReceiptValidation($request, self::VERIFY_RECEIPT_END_POINT)['status'] == self::STATUS_SUCCESS) {
                        $arrayResponse = self::appleReceiptValidation($request, self::VERIFY_RECEIPT_END_POINT);
                        return  $this->getValidCoursesCollectionWithReceipt($arrayResponse);
                    } elseif (self::appleReceiptValidation($request, self::VERIFY_RECEIPT_END_POINT)['status'] == self::STATUS_FOR_SANDBOX) {
                        $arrayResponse = self::appleReceiptValidation($request, self::SANDBOX_TESTING_END_POINT);
                        if ($arrayResponse['status'] == self::STATUS_SUCCESS) {
                            return $this->getValidCoursesCollectionWithReceipt($arrayResponse);
                        }
                    }
                }
                break;
            case getenv('PASSPORT__DEV_IOS_ACCESS_CLIENT_ID'):
                if (!isset($request['receipt'])){
                    return self::getTestCourses(true);
                }else{
                    if (self::appleReceiptValidation($request, self::VERIFY_RECEIPT_END_POINT) instanceof JsonResponse) {
                        return self::appleReceiptValidation($request, self::VERIFY_RECEIPT_END_POINT);
                    } elseif (self::appleReceiptValidation($request, self::VERIFY_RECEIPT_END_POINT)['status'] == self::STATUS_SUCCESS) {
                        $arrayResponse = self::appleReceiptValidation($request, self::VERIFY_RECEIPT_END_POINT);
                        return  $this->getValidCoursesCollectionWithReceipt($arrayResponse, true);
                    } elseif (self::appleReceiptValidation($request, self::VERIFY_RECEIPT_END_POINT)['status'] == self::STATUS_FOR_SANDBOX) {
                        $arrayResponse = self::appleReceiptValidation($request, self::SANDBOX_TESTING_END_POINT);
                        if ($arrayResponse['status'] == self::STATUS_SUCCESS) {
                            return $this->getValidCoursesCollectionWithReceipt($arrayResponse, true);
                        }
                    }
                }
                break;
            default:
                return self::errorJsonResponse();
        }
    }

    /**
     * @param Request $request
     * @param bool $isDevClient
     * @return ValidCoursesCollection|JsonResponse
     * @throws \Google\Exception
     */
    private function googleSubscriptionValidation(Request $request, bool $isDevClient = false)
    {
        if (isset($request['subscriptions'])) {
            $subscriptions = $request->input('subscriptions');
            if (gettype($subscriptions) == 'string') {
                $subscriptions = json_decode($subscriptions, true);
            }
        }

        $googleClient = new \Google_Client;
        $googleClient->setAuthConfig(getenv('GOOGLE_SERVICE_CREDENTIALS'));
        $googleClient->setScopes(self::GOOGLE_AUTHORIZATION_SCOPES);

        $googleService = new \Google_Service_AndroidPublisher($googleClient);

        if (!$isDevClient) {
            //  Get all live Test Courses
            $validCourses = Course::where([['type', '=', 'Test'], ['live', true]])
                ->orderBy('created_at', 'desc')->get();

            if (empty($subscriptions)) {
                if (count($validCourses) == 0) {
                    $returnEmptyArray = array();
                    return response()->json($returnEmptyArray, 200);
                }

                return new ValidCoursesCollection($validCourses);
            }

            foreach ($subscriptions as $subscription) {
                $purchase = $googleService->purchases_subscriptions->get(
                    getenv('GOOGLE_PACKAGE_NAME'),
                    $subscription['id'],
                    $subscription['token']
                );
                $currentTimeMillis = round(microtime(true) * 1000);

                // Query for Courses with a subscription's id and with a valid payment
                if (
                    $purchase->paymentState == self::GOOGLE_SUBSCRIPTION_PAYMENT_RECEIVED
                    || $purchase->paymentState == self::GOOGLE_SUBSCRIPTION_FREE_TRIAL
                    || $purchase->paymentState == self::GOOGLE_SUBSCRIPTION_PENDING_DEFERRED
                    && $purchase->expiryTimeMillis > $currentTimeMillis
                ) {
                    $subscriptionCourses = Course::where([['iap_id_google', '=', $subscription['id']], ['live', true]])->get();
                    $validCourses = $validCourses->merge($subscriptionCourses)->sortBy('type');
                }
            }

            if (count($validCourses) == 0) {
                $returnEmptyArray = array();
                return response()->json($returnEmptyArray, 200);
            }

            return new ValidCoursesCollection($validCourses);
        } else {
            //  Get all live Test Courses
            $validCourses = Course::where('type', '=', 'Test')
                ->orderBy('created_at', 'desc')->get();

            if (empty($subscriptions)) {
                if (count($validCourses) == 0) {
                    $returnEmptyArray = array();
                    return response()->json($returnEmptyArray, 200);
                }

                return new ValidCoursesCollection($validCourses);
            }

            foreach ($subscriptions as $subscription) {
                $purchase = $googleService->purchases_subscriptions->get(
                    getenv('GOOGLE_PACKAGE_NAME'),
                    $subscription['id'],
                    $subscription['token']
                );
                $currentTimeMillis = round(microtime(true) * 1000);

                // Query for Courses with a subscription's id and with a valid payment
                if (
                    $purchase->paymentState == self::GOOGLE_SUBSCRIPTION_PAYMENT_RECEIVED
                    || $purchase->paymentState == self::GOOGLE_SUBSCRIPTION_FREE_TRIAL
                    || $purchase->paymentState == self::GOOGLE_SUBSCRIPTION_PENDING_DEFERRED
                    && $purchase->expiryTimeMillis > $currentTimeMillis
                ) {
                    $subscriptionCourses = Course::where('iap_id_google', '=', $subscription['id'])->get();
                    $validCourses = $validCourses->merge($subscriptionCourses)->sortBy('type');
                }
            }

            if (count($validCourses) == 0) {
                $returnEmptyArray = array();
                return response()->json($returnEmptyArray, 200);
            }

            return new ValidCoursesCollection($validCourses);
        }
    }

    /**
     * @param $arrayResponse
     * @param bool $isDevClient
     * @return ValidCoursesCollection|JsonResponse
     */
    private function getValidCoursesCollectionWithReceipt($arrayResponse, bool $isDevClient = false)
    {
        if (!$isDevClient) {
            //  Get all live Test Courses
            $validCourses = Course::where([['type', '=', 'Test'], ['live', true]])
                ->orderBy('created_at', 'desc')->get();

            foreach ($arrayResponse['pending_renewal_info'] as $renewal_info) {
                if (isset($renewal_info['expiration_intent'])) {
                    switch ($renewal_info['expiration_intent']) {
                        case self::EXPIRATION_VOLUNTARY_CANCELED_SUBSCRIPTION:
                        case self::EXPIRATION_BILLING_ERROR_ON_PAYMENT:
                        case self::EXPIRATION_NOT_AGREE_TO_PRICE_INCREASE:
                        case self::EXPIRATION_PRODUCT_NOT_AVAILABLE_ON_RENEWAL:
                        case self::EXPIRATION_UNKNOWN_ERROR:
                            Log::info("Apple's value expiration code: " . $renewal_info['expiration_intent'] .
                                ". For product: " . $renewal_info['product_id']);
                            break;
                    }
                }
            }

            // Query for Subscription Courses with valid subscription
            foreach ($arrayResponse['pending_renewal_info'] as $renewal_info) {
                if (($renewal_info['auto_renew_status'] == self::SUBSCRIPTION_WILL_RENEW_AT_END_OF_PERIOD)
                    && !isset($renewal_info['expiration_intent'])
                ) {
                    $subscriptionCourses = Course::where([
                        ['iap_id_apple', '=', $renewal_info['product_id']],
                        ['live', true]
                    ])->get();
                    $validCourses = $validCourses->merge($subscriptionCourses)->sortBy('type');
                }
            }

            if (count($validCourses) == 0) {
                $returnEmptyArray = array();
                return response()->json($returnEmptyArray, 200);
            }

            return new ValidCoursesCollection($validCourses);
        } else {
            //  Get all live Test Courses
            $validCourses = Course::where('type', '=', 'Test')
                ->orderBy('created_at', 'desc')->get();

            foreach ($arrayResponse['pending_renewal_info'] as $renewal_info) {
                if (isset($renewal_info['expiration_intent'])) {
                    switch ($renewal_info['expiration_intent']) {
                        case self::EXPIRATION_VOLUNTARY_CANCELED_SUBSCRIPTION:
                        case self::EXPIRATION_BILLING_ERROR_ON_PAYMENT:
                        case self::EXPIRATION_NOT_AGREE_TO_PRICE_INCREASE:
                        case self::EXPIRATION_PRODUCT_NOT_AVAILABLE_ON_RENEWAL:
                        case self::EXPIRATION_UNKNOWN_ERROR:
                            Log::info("Apple's value expiration code: " . $renewal_info['expiration_intent'] .
                                ". For product: " . $renewal_info['product_id']);
                            break;
                    }
                }
            }

            // Query for Subscription Courses with valid subscription
            foreach ($arrayResponse['pending_renewal_info'] as $renewal_info) {
                if (($renewal_info['auto_renew_status'] == self::SUBSCRIPTION_WILL_RENEW_AT_END_OF_PERIOD)
                    && !isset($renewal_info['expiration_intent'])
                ) {
                    $subscriptionCourses = Course::where('iap_id_apple', '=', $renewal_info['product_id'])->get();
                    $validCourses = $validCourses->merge($subscriptionCourses)->sortBy('type');
                }
            }

            if (count($validCourses) == 0) {
                $returnEmptyArray = array();
                return response()->json($returnEmptyArray, 200);
            }

            return new ValidCoursesCollection($validCourses);
        }
    }

    /**
     * @param Request $request
     * @param string $appleEndPoint
     * @return JsonResponse|mixed
     */
    public function appleReceiptValidation(Request $request, string $appleEndPoint)
    {
        if (!isset($request['receipt'])) {
            Log::error('No receipt found on the request, please add a receipt.');
            $returnErrorData = array(
                'error' => 2
            );
            return response()->json($returnErrorData, 400);
        }

        $params = [
            "receipt-data" => $request->input('receipt'),
            "password" => getenv('APPLE_SHARED_SECRET'),
            "exclude-old-transactions" => true
        ];
        $postFieldsRequest = json_encode($params);
        // create POST request to be sent to the Apple's End-point.
        $ch = curl_init($appleEndPoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsRequest);
        $jsonResponse = curl_exec($ch);
        curl_close($ch);
        $arrayResponse = json_decode($jsonResponse, true);

        if (!isset($arrayResponse['status'])) {
            Log::error('No status code found on the receipt, please verify the receipt.');
            return self::errorJsonResponse();
        }

        switch ($arrayResponse['status']) {
            case self::STATUS_METHOD_POST_NOT_USED:
            case self::STATUS_NOT_AUTHENTICATED:
            case self::STATUS_WRONG_SECRET:
            case self::STATUS_SERVER_UNAVAILABLE:
            case self::STATUS_FOR_PRODUCTION:
            case self::STATUS_INTERNAL_ERROR:
            case self::STATUS_UNAUTHORIZED:
            case self::STATUS_DATA_MALFORMED:
            case self::STATUS_SUBSCRIPTION_EXPIRED:
                Log::error('Error checking in-app subscription: ' .
                    self::getConstantName($arrayResponse['status']) .
                    ". Apple's value code: " . $arrayResponse['status']);
                return self::errorJsonResponse();
        }

        return $arrayResponse;
    }

    /**
     * @param bool $isDevClient
     * @return ValidCoursesCollection|JsonResponse
     */
    public static function getTestCourses(bool $isDevClient = false)
    {
        if ($isDevClient)
        {
            $testCourses = Course::where('type', '=', 'Test')
                ->orderBy('created_at', 'desc')->get();

            if (count($testCourses) == 0){
                $returnEmptyArray = array();
                return response()->json($returnEmptyArray, 200);
            }

            return new ValidCoursesCollection($testCourses);
        }
        $testCourses = Course::where([['type', '=', 'Test'], ['live', true]])
            ->orderBy('created_at', 'desc')->get();

        if (count($testCourses) == 0){
            $returnEmptyArray = array();
            return response()->json($returnEmptyArray, 200);
        }

        return new ValidCoursesCollection($testCourses);
    }

    /**
     * JSON response when verifying subscriptions or receipts,
     * with the error code.
     * @return \Illuminate\Http\JsonResponse
     */
    public static function errorJsonResponse()
    {
        $returnErrorData = array(
            'error' => 7
        );
        return response()->json($returnErrorData, 503);
    }

    /**
     * Get the const variable name.
     * @param $constName
     * @return mixed
     */
    public static function getConstantName($constName)
    {
        $class = new \ReflectionClass(__CLASS__);
        $constants = array_flip($class->getConstants());

        return $constants[$constName];
    }
}
