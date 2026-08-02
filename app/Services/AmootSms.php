<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AmootSms
{
    protected string $token;
    protected string $baseUrl = 'https://portal.amootsms.com/rest';

    public function __construct()
    {
        $this->token = config('services.amoot.token');
    }

    public function send(string $phone, string $code): bool
    {
        $phone = ltrim($phone, '0');

        $response = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->post("{$this->baseUrl}/SendSimple", [
            'Token' => $this->token,
            'SendDateTime' => '',
            'SMSMessageText' => "کد ورود شما در تسکفلو:{$code}",
            'LineNumber' => 'service',
            'Mobiles' => (int) $phone,
        ]);

        $result = $response->json();

        if (isset($result['Status']) && ($result['Status'] === 1 || $result['Status'] === 'Success')) {
            return true;
        }

        logger('AmootSMS SendSimple failed', ['response' => $result]);

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
