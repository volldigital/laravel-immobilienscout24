<?php

namespace VOLLdigital\LaravelImmobilienscout24\Server;

use GuzzleHttp\Exception\BadResponseException;
use League\OAuth1\Client\Credentials\CredentialsException;
use League\OAuth1\Client\Credentials\TemporaryCredentials;
use League\OAuth1\Client\Server\Server as ServerServer;
use League\OAuth1\Client\Signature\SignatureInterface;

abstract class Server  extends ServerServer
{

    protected bool $sandbox = true;

    public function __construct($clientCredentials, SignatureInterface $signature = null)
    {
        $this->sandbox = $clientCredentials['sandbox'];

        unset($clientCredentials['sandbox']);

        parent::__construct($clientCredentials, $signature);
    }

    /**
     * @inheritDoc
     */
    public function getTokenCredentials(TemporaryCredentials $temporaryCredentials, $temporaryIdentifier, $verifier)
    {
        if ($temporaryIdentifier !== $temporaryCredentials->getIdentifier()) {
            throw new \InvalidArgumentException(
                'Temporary identifier passed back by server does not match that of stored temporary credentials.
                Potential man-in-the-middle.'
            );
        }

        $uri = $this->urlTokenCredentials();
        $bodyParameters = ['oauth_verifier' => $verifier];

        $client = $this->createHttpClient();

        $headers = $this->getHeaders($temporaryCredentials, 'POST', $uri, $bodyParameters);

        $auth = $headers['Authorization'];
        $auth .= ', oauth_verifier="'.$verifier.'"';

        $headers['Authorization'] = $auth;

        try {
            $response = $client->post($uri, [
                'headers' => $headers,
                'form_params' => $bodyParameters,
            ]);

            return $this->createTokenCredentials((string) $response->getBody());
        } catch (BadResponseException $e) {
            $this->handleTokenCredentialsBadResponse($e);
        }

        throw new CredentialsException('Failed to get token credentials.');
    }

}