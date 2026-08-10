<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AmootSms
{
    protected string $token;
    protected int $patternCode;
    protected string $baseUrl = 'https://portal.amootsms.com/rest';

    public function __construct()
    {
        $this->token = config('services.amoot.token');
        $this->patternCode = (int) config('services.amoot.pattern_code');
    }

    public function send(string $phone, string $code): bool
    {
        $phone = ltrim($phone, '0');

        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->post("{$this->baseUrl}/SendWithPattern", [
            'Token' => $this->token,
            'Mobile' => $phone,
            'PatternCodeID' => $this->patternCode,
            'PatternValues' => $code,
        ]);

        $result = $response->json();

        if (isset($result['Status']) && ($result['Status'] === 1 || $result['Status'] === 'Success')) {
            return true;
        }

        if (isset($result['Data'][0]['Status']) && ($result['Data'][0]['Status'] === 1 || $result['Data'][0]['Status'] === 'Success')) {
            return true;
        }

        logger('AmootSMS SendWithPattern failed', ['response' => $result]);

        return false;
    }

    public function sendOtp(string $phone): ?string
    {
        $code = rand(100000, 999999);

        $success = $this->send($phone, (string) $code);

        return $success ? (string) $code : null;
    }

    public function accountStatus(): ?array
    {
        $response = Http::withoutVerifying()->asForm()->post("{$this->baseUrl}/AccountStatus", [
            'Token' => $this->token,
        ]);

        return $response->json();
    }
}
