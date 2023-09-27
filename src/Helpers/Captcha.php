<?php

declare(strict_types=1);

namespace Src\Helpers;

class Captcha
{
    private $siteKey;
    private $secretKey;
    private $verificationUrl = "https://www.google.com/recaptcha/api/siteverify";

    public function __construct(string $siteKey, string $secretKey)
    {
        $this->siteKey = $siteKey;
        $this->secretKey = $secretKey;
    }

    public function renderCaptcha(): string
    {
        return "<div class='g-recaptcha' data-sitekey='{$this->siteKey}'></div>";
    }

    // TODO rewrite
    public function validateCaptcha(string $responseKey)
    {
        $reqBody = [
            'secret' => $this->secretKey,
            'response' => $responseKey
        ];

        // $requestOptions = [
        //     'http' => [
        //         'header' => "Content-type: application/x-www-form-urlencoded",
        //         'method' => 'POST',
        //         'content' => http_build_query($reqBody)
        //     ]
        // ];

        // $context = stream_context_create($requestOptions);
        // $res = file_get_contents($this->verificationUrl, false, $context);
        // $resObj = json_decode($res);

        // dd($resObj);
        // dd($http_response_header);
        // die();

        $reqHeaders = [
            "Content-type: application/x-www-form-urlencoded"
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->verificationUrl,
            CURLOPT_RETURNTRANSFER => true,
            // CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($reqBody),
            CURLOPT_HTTPHEADER => $reqHeaders
        ]);

        $res = curl_exec($curl);
        // dd(curl_getinfo($curl));
        curl_close($curl);
        // die();

        $resObj = json_decode($res);

        if (!is_object($resObj) || !property_exists($resObj, 'success') || !$resObj->success) {
            return false;
        }

        return true;
    }
}
