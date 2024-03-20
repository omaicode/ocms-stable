<?php
namespace App\Supports;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PPurio
{
    protected PendingRequest $client;

    protected string $phone_from;

    protected ?string $phone_to;

    protected string $account;

    protected string $access_key;

    protected ?string $token;

    public function __construct(string $base_url, string $phone_from, ?string $phone_to, string $account, $access_key)
    {
        $this->phone_from = $phone_from;
        $this->phone_to = $phone_to;
        $this->account = $account;
        $this->access_key = $access_key;
        $this->client = Http::baseUrl($base_url);
    }

    /**
     * Get access token from PPurio
     * Reference url: https://www.ppurio.com/send-api/develop
     * @return string|null
     */
    protected function getToken()
    {
        try {
            if(Cache::has('ppurio_token')) return Cache::get('ppurio_token');
            $encoded_str = base64_encode($this->account.':'.$this->access_key);
            $response = $this->client
                ->withHeaders([
                    'Authorization' => 'Basic '.$encoded_str
                ])
                ->post('token');

            if($response->ok()) {
                return Cache::remember('ppurio_token', 43200, function() use ($response) {
                    return $response->json('token');
                });
            } else return null;
        } catch (\Throwable $ex) {
            Log::error($ex);
            return null;
        }
    }

    /**
     * Send SMS to specific targets
     * Reference url: https://www.ppurio.com/send-api/develop
     * @param array $sms_data
     * @return bool
     */
    public function sendSMS(array $sms_data) : bool
    {
        $base_data = [
            'account' => $this->account,
            'messageType' => 'SMS',
            'from' => $this->phone_from,
            'duplicateFlag' => 'N',
            'content' => null,
            'targetCount' => 0,
            'targets' => [],
            'refKey' => Str::random(32)
        ];

        // Mapping input into base data
        foreach($sms_data as $key => $value) {
            if(array_key_exists($key, $base_data)) {
                $base_data[$key] = $value;
            }
        }

        // Check the content empty or not
        if(blank($base_data['content'])) {
            Log::error('[PPURIO][sendSMS] The content can\'t not be empty');
            return false;
        }

        // Check the targets empty or not
        if(count($base_data['targets']) <= 0 && blank($this->phone_to)) {
            Log::error('[PPURIO][sendSMS] The targets can\'t not be empty');
            return false;
        }

        // If the targets is empty, use default receive phone
        if(count($base_data['targets']) <= 0 && $this->phone_to) {
            $base_data['targetCount'] = 1;
            $base_data['targets'] = [
                ['to' => $this->phone_to]
            ];
        }

        // Check the content size and change to LMS if the content size larger than 90 bytes
        if(strlen($base_data['content']) > 90) {
            $base_data['messageType'] = 'LMS';
        }

        try {
            $response = $this->client
                ->asJson()
                ->withToken($this->getToken())
                ->post('message', $base_data);
            if($response->ok()) return true;
            else {
                Log::error("[PPURIO][sendSMS] Send failed", $response->json());
                return false;
            }
        } catch(\Throwable $ex) {
            Log::error($ex);
            return false;
        }
    }
}
