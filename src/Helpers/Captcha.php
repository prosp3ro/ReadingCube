<?php

declare(strict_types=1);

namespace Src\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use InvalidArgumentException;
use Throwable;

class Captcha
{
    private $siteKey;
    private $secretKey;
    private $verificationUrl = "https://www.google.com/recaaptcha/api/siteverify";

    public function __construct(string $siteKey, string $secretKey)
    {
        if (empty($siteKey) || empty($secretKey)) {
            throw new InvalidArgumentException("Site key and secret key must not be empty.");
        }

        $this->siteKey = $siteKey;
        $this->secretKey = $secretKey;
    }

    public function renderCaptcha(): string
    {
        return "<div class='g-recaptcha' data-sitekey='{$this->siteKey}'></div>";
    }

    public function validateCaptcha(string $responseKey): bool
    {
        $guzzleClient = new Client();

        try {
            $response = $guzzleClient->post($this->verificationUrl, [
                'form_params' => [
                    'secret' => $this->secretKey,
                    'response' => $responseKey
                ]
            ]);

            $responseData = json_decode($response->getBody()->getContents());

            return isset($responseData->success) && $responseData->success;
        // } catch (RequestException $exception) {
        } catch (Throwable $exception) {
            //
        }
    }
}
