<?php
namespace OCMS\Socialite\Two;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use OCMS\Socialite\Two\AbstractProvider;
use OCMS\Socialite\Two\ProviderInterface;
use OCMS\Socialite\Two\User;

class NaverProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://nid.naver.com/oauth2.0/authorize', $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return 'https://nid.naver.com/oauth2.0/token';
    }

    /**
     * Get the access token for the given code.
     *
     * @param  string  $code
     * @return string
     */
    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            RequestOptions::FORM_PARAMS => $this->getTokenFields($code),
        ]);

        $data = json_decode($response->getBody(), true);
        return $data;
    }

    /**
     * Get the raw user for the given access token.
     *
     * @param  string  $token
     * @return array
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->post('https://openapi.naver.com/v1/nid/me', [
            RequestOptions::HEADERS => ['Authorization' => 'Bearer '.$token],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Map the raw user array to a Socialite User instance.
     *
     * @param  array  $user
     * @return \OCMS\Socialite\Two\User
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'        => Arr::get($user, 'response.id'),
            'nickname'  => Arr::get($user, 'response.nickname'),
            'name'      => Arr::get($user, 'response.name'),
            'email'     => Arr::get($user, 'response.email'),
            'avatar'    => Arr::get($user, 'response.profile_image'),
        ]);
    }
}
