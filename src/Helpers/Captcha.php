<?php

declare(strict_types=1);

namespace Src\Helpers;

use GuzzleHttp\Client;

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

        $reqHeaders = [
            "Content-type" => "application/x-www-form-urlencoded"
        ];

        $guzzleClient = new Client();

        $res = $guzzleClient->post($this->verificationUrl, [
            "headers" => $reqHeaders,
            "body" => http_build_query($reqBody)
        ]);

        $resBody = $res->getBody();
        $resConents = $resBody->getContents();
        $resObj = json_decode($resConents);

        if (!is_object($resObj) || !property_exists($resObj, 'success') || !$resObj->success) {
            return false;
        }

        return true;
    }
}
