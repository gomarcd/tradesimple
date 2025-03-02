<?php

namespace App\Services\Wealthsimple\Sessions;

class WSAPISession extends OAuthSession
{
    public ?string $session_id = NULL; // Session ID; sent in headers for OAuth requests
    public ?string $wssdi = NULL;      // Device ID; sent in headers of API requests
    public ?object $token_info = NULL; // Cache result of getTokenInfo()
}
