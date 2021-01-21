<?php

namespace VOLLdigital\LaravelImmobilienscout24\Server;

use Exception;
use GuzzleHttp\Exception\BadResponseException;
use League\OAuth1\Client\Credentials\TokenCredentials;
use VOLLdigital\LaravelImmobilienscout24\Models\Is24Token;

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

    public function fetchData(string $uri)
    {
        $url = $this->baseUrl() . $uri;
        $client = $this->createHttpClient();

        $token = Is24Token::first();

        if (is_null($token)) {
            throw new Exception('No authentication token available');
        }

        $tokenCredentials = new TokenCredentials();
        $tokenCredentials->setIdentifier($token->is_identifier);
        $tokenCredentials->setSecret($token->is_secret);

        if (is_null($tokenCredentials)) {
            throw new \Exception('No token credentials available');
        }

        $headers = $this->getHeaders($tokenCredentials, 'GET', $url);

        try {
            $response = $client->get($url, [
                'headers' => array_merge($headers, ['Accept' => 'application/json']),
            ]);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();
            $body = $response->getBody();
            $statusCode = $response->getStatusCode();

            throw new \Exception("Received error [$body] with status code [$statusCode].");
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
