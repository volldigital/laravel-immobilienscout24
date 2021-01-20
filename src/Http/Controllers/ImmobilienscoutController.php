<?php

namespace VOLLdigital\LaravelImmobilienscout24\Http\Controllers;

use Illuminate\Routing\Controller;
use VOLLdigital\LaravelImmobilienscout24\Server\ImmobilienscoutServer;

class ImmobilienscoutController extends Controller
{

    public function authorize()
    {
        $server = app(ImmobilienscoutServer::class);

        $temporaryCredentials = $server->getTemporaryCredentials();

        session()->put('temporary_credentials', serialize($temporaryCredentials));

        $server->authorize($temporaryCredentials);
    }

    public function callback()
    {
        $server = app(ImmobilienscoutServer::class);

        if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
            $temporaryCredentials = unserialize(session('temporary_credentials'));
            $tokenCredentials = $server->getTokenCredentials($temporaryCredentials, $_GET['oauth_token'], $_GET['oauth_verifier']);

            session()->put('immo_cred', serialize($tokenCredentials));
        }

        return redirect('/');
    }

}