<?php

namespace App\Services\Wealthsimple\Sessions;

abstract class OAuthSession
{
    public ?string $client_id = NULL;     // OAuth Client ID; sent in OAuth requests
    public ?string $access_token = NULL;  // OAuth Access Token; used to authenticate API requests
    public ?string $refresh_token = NULL; // OAuth Refresh Token; used to obtain new access tokens when they expire
}
