<?php
namespace OCMS\Socialite\Two;

use GuzzleHttp\RequestOptions;
use Illuminate\Support\Arr;
use OCMS\Socialite\Two\AbstractProvider;
use OCMS\Socialite\Two\ProviderInterface;
use OCMS\Socialite\Two\User;

class KakaoProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * Get the authentication URL for the provider.
     *
     * @param  string  $state
     * @return string
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://kauth.kakao.com/oauth/authorize', $state);
    }

    /**
     * Get the token URL for the provider.
     *
     * @return string
     */
    protected function getTokenUrl()
    {
        return 'https://kauth.kakao.com/oauth/token';
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
        $response = $this->getHttpClient()->post('https://kapi.kakao.com/v2/user/me', [
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
        $validEmail = Arr::get($user, 'kakao_account.is_email_valid');
        $verifiedEmail = Arr::get($user, 'kakao_account.is_email_verified');

        return (new User())->setRaw($user)->map([
            'id'        => $user['id'],
            'nickname'  => Arr::get($user, 'properties.nickname'),
            'name'      => Arr::get($user, 'properties.nickname'),
            'email'     => $validEmail && $verifiedEmail ? Arr::get($user, 'kakao_account.email') : null,
            'avatar'    => Arr::get($user, 'properties.profile_image'),
        ]);
    }
}
