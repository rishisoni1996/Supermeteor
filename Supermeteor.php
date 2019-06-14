<?php

namespace Supermeteor;

class Supermeteor
{
    public $secretKey, $statusCode, $message;

    /**
     * Supermeteor constructor.
     */
    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param mixed $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function response()
    {
        http_response_code($this->statusCode);
        header('Content-Type: application/json');
        $message = ["message" => $this->message];
        $response = json_encode($message);

        return $response;
    }

    public function ValidateSendMessageRequest($type, $phone, $message)
    {
        if (strtolower($type) == 'sms' || strtolower($type) == 'whatsapp') {
            if ($phone == '') {
                $this->statusCode = 400;
                $this->message = 'phone must not be blank';
                return false;
            } else if ($message == '') {
                $this->statusCode = 400;
                $this->message = 'message must not be blank';
                return false;
            }
        } else {
            $this->statusCode = 400;
            $this->message = 'type must be sms or whatsapp';
            return false;
        }

        return true;
    }

    public function SendMessage($type, $phone, $message)
    {
        // validate if type, phone or message must not blank.
        $valid = $this->ValidateSendMessageRequest($type, $phone, $message);
        if (!$valid) {
            $response = $this->response();
            return $response;
        }

        // check which type of message to send.
        switch (strtolower($type)) {
            // for type sms
            case strtolower($type) == 'sms':
                $url = 'https://email-uat.lncknight.com/sms/send';
                break;
            // for type whatsaap
            case strtolower($type) == 'whatsapp':
                $url = 'https://email-uat.lncknight.com/whatsapp/send';
                break;
            default:
                $this->statusCode = 400;
                $this->message = 'Type must be sms or whatsapp only.';
                $response = $this->response();
                return $response;
        }

        $payload = [
            "secret" => $this->secretKey,
            "phone" => $phone,
            "message" => $message
        ];

        $data_string = json_encode($payload );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'));
        $result = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        http_response_code($statusCode);
        header('Content-Type: application/json');
        return $result;
    }

    public function ValidateSendEmailRequest($email, $subject, $message)
    {
        if ($email == '') {
            $this->statusCode = 400;
            $this->message = 'email must not be blank';
            return false;
        } else if ($subject == '') {
            $this->statusCode = 400;
            $this->message = 'subject must not be blank';
            return false;
        } else if ($message == '') {
            $this->statusCode = 400;
            $this->message = 'message must not be blank';
            return false;
        }

        return true;
    }

    public function SendEmail($email, $subject, $message)
    {
        // validate if email, message, subject must not blank.
        $valid = $this->ValidateSendEmailRequest($email, $subject, $message);
        if (!$valid) {
            $response = $this->response();
            return $response;
        }

        $url = 'https://email-uat.lncknight.com/email/send';
        $payload = [
            "secret" => $this->secretKey,
            "email" => $email,
            "subject" => $subject,
            "message" => $message
        ];

        $data_string = json_encode($payload );

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'));
        $result = curl_exec($ch);

        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        http_response_code($statusCode);
        header('Content-Type: application/json');
        return $result;
    }
}
