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

    // public function renderCaptcha(): string
    // {
    //     return "<div class='g-recaptcha' data-sitekey='{$this->siteKey}'></div>";
    // }

    public function validateCaptcha(string $responseKey): object
    {
        $captchaData = [
            'secret' => $this->secretKey,
            'response' => $responseKey
        ];

        $requestOptions = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($captchaData)
            ]
        ];

        $context = stream_context_create($requestOptions);
        $captchaResult = file_get_contents($this->verificationUrl, false, $context);
        $jsonCaptchaResult = json_decode($captchaResult);

        return $jsonCaptchaResult;
    }
}
