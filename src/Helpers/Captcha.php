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
        $captchaData = [
            'secret' => $this->secretKey,
            'response' => $responseKey
        ];

        // $requestOptions = [
        //     'http' => [
        //         'header' => "Content-type: application/x-www-form-urlencoded",
        //         'method' => 'POST',
        //         'content' => http_build_query($captchaData)
        //     ]
        // ];

        // $context = stream_context_create($requestOptions);
        // $captchaResult = file_get_contents($this->verificationUrl, false, $context);
        // $jsonCaptchaResult = json_decode($captchaResult);

        // dd($jsonCaptchaResult);
        // dd($http_response_header);
        // die();

        $headers = [
            "Content-type: application/x-www-form-urlencoded"
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->verificationUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($captchaData),
            CURLOPT_HTTPHEADER => $headers
        ]);

        $captchaResult = curl_exec($curl);
        curl_close($curl);

        $jsonCaptchaResult = json_decode($captchaResult);

        if (!is_object($jsonCaptchaResult) || !property_exists($jsonCaptchaResult, 'success') || !$jsonCaptchaResult->success) {
            return false;
        }

        return true;
    }
}
