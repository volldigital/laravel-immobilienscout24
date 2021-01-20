<?php

namespace VOLLdigital\LaravelImmobilienscout24\Server;

use GuzzleHttp\Exception\BadResponseException;
use League\OAuth1\Client\Credentials\TokenCredentials;

class ImmobilienscoutServer extends Server
{
    /**
     * @inheritDoc
     */
    public function urlTemporaryCredentials()
    {
        return $this->sandbox 
        ? 'https://rest.sandbox-immobilienscout24.de/restapi/security/oauth/request_token'
        : 'https://rest.immobilienscout24.de/restapi/security/oauth/request_token';
    }

    /**
     * @inheritDoc
     */
    public function urlAuthorization()
    {
        return $this->sandbox 
        ? 'https://rest.sandbox-immobilienscout24.de/restapi/security/oauth/confirm_access'
        : 'https://rest.immobilienscout24.de/restapi/security/oauth/confirm_access';
    }

    /**
     * @inheritDoc
     */
    public function urlTokenCredentials()
    {
        return $this->sandbox
        ? 'https://rest.sandbox-immobilienscout24.de/restapi/security/oauth/access_token'
        : 'https://rest.immobilienscout24.de/restapi/security/oauth/access_token';
    }

    /**
     * @inheritDoc
     */
    public function urlUserDetails()
    {
        return $this->sandbox
        ? 'https://rest.sandbox-immobilienscout24.de/restapi/api/offer/v1.0/user/me/realestate'
        : 'https://rest.immobilienscout24.de/restapi/api/offer/v1.0/user/me/realestate';
    }

    /**
     * @inheritDoc
     */
    public function baseUrl()
    {
        return $this->sandbox
        ? 'https://rest.sandbox-immobilienscout24.de/restapi/api'
        : 'https://rest.immobilienscout24.de/restapi/api';
    }

    public function fetchData(string $uri, $tokenCredentials)
    {
        $url = $this->baseUrl() . $uri;
        $client = $this->createHttpClient();
        $headers = $this->getHeaders($tokenCredentials, 'GET', $url);

        try {
            $response = $client->get($url, [
                'headers' => array_merge($headers, ['Accept' => 'application/json']),
            ]);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            $body = $response->getBody();
            $statusCode = $response->getStatusCode();

            throw new \Exception("Received error [$body] with status code [$statusCode] when retrieving token credentials.");
        }

        return json_decode((string) $response->getBody(), true);
    }

    /**
     * @inheritDoc
     */
    public function userDetails($data, TokenCredentials $tokenCredentials)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function userUid($data, TokenCredentials $tokenCredentials)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function userEmail($data, TokenCredentials $tokenCredentials)
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function userScreenName($data, TokenCredentials $tokenCredentials)
    {
        return null;
    }
}
