<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SendFeedbackController extends Controller
{
    public function __construct()
    {
        $this->middleware('client');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendFeedback(Request $request)
    {
        if (!$request->filled([
            'lang',
            'message',
            'id'
        ])){
            $returnErrorData = array(
                'error' => 2
            );

            return response()->json($returnErrorData, 400);
        }

        $transport = (new \Swift_SmtpTransport(getenv('MAIL_SERVER'), getenv('MAIL_PORT'), getenv('MAIL_TRANSPORT')))
            ->setUsername(getenv('MAIL_USER'))
            ->setPassword(getenv('MAIL_PASSWORD'));

        $mailer = new \Swift_Mailer($transport);

        $subject = $this->createSubject($request->input('id'));

        $client = CourseApiController::getClientFromToken($request);

        if ($client->id == getenv('PASSPORT_ANDROID_ACCESS_CLIENT_ID')
            || $client->id == getenv('PASSPORT_DEV_ANDROID_ACCESS_CLIENT_ID'))
        {
            $body = $this->createBody(
                $request->input('lang'),
                $client->id,
                'Android',
                $request->input('id'),
                $request->input('message'),
                $request->input('contact'),
                $request->input('meta')
            );
        } elseif ($client->id == getenv('PASSPORT_IOS_ACCESS_CLIENT_ID')
            || $client->id == getenv('PASSPORT__DEV_IOS_ACCESS_CLIENT_ID'))
        {
            $body = $this->createBody(
                $request->input('lang'),
                $client->id,
                'iOS',
                $request->input('id'),
                $request->input('message'),
                $request->input('contact'),
                $request->input('meta')
            );
        }

        $mail = (new \Swift_Message($subject))
            ->setFrom(getenv('MAIL_SEND_ADDRESS'))
            ->setTo(getenv('MAIL_RECEIVER'))
            ->setBody($body, 'text/html');

        $numberSent = $mailer->send($mail);

        if ($numberSent > 0){
            $successResponse = array(
                'feedback_sent' => true
            );

            return response()->json($successResponse, 200);
        } else{
            $returnErrorData = array(
                'error' => 0
            );
            return response()->json($returnErrorData, 500);
        }
    }

    /**
     * @param string $language
     * @param string $deviceId
     * @param string $platform
     * @param string $appId
     * @param string $message
     * @param string|null $userContact
     * @param string|null $meta
     * @return string
     */
    protected function createBody(
        string $language,
        string $deviceId,
        string $platform,
        string $appId,
        string $message,
        ?string $userContact = null,
        ?string $meta = null)
    {
        $body = $message . '<br>';
        if ($userContact != null)
        {
            $body = $body . '<br>Kontaktdaten: ' . $userContact . '<br>';
        }

        $body = $body . '<br>App Id: ' . $appId;
        $body = $body . '<br>Device Id: ' . $deviceId;
        $body = $body . '<br>Platform: ' . $platform;
        $body = $body . '<br>Language: ' . $language;

        if ($meta != null)
        {
            $body = $body . '<br>Meta Info: ' . $meta;
        }

        return $body;
    }

    /**
     * @param string $appId
     * @return string
     */
    protected function createSubject(string $appId)
    {
        switch ($appId)
        {
            case 'flyanx':
                $appName = 'Flugangst';
                break;

            case 'testanx':
                $appName = 'Prüfungsangst';
                break;

            default:
                $appName = $appId;
        }

        return 'Feedback Therapy App (' . $appName . ')';
    }
}
