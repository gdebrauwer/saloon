<?php

declare(strict_types=1);

namespace Saloon\Http\OAuth2;

use Saloon\Http\Request;
use Saloon\Contracts\Body\WithBody;
use Saloon\Traits\Body\HasFormBody;
use Saloon\Helpers\OAuth2\OAuthConfig;
use Saloon\Traits\Plugins\AcceptsJson;

class GetUserRequest extends Request implements WithBody
{
    use HasFormBody;
    use AcceptsJson;

    /**
     * Define the method that the request will use.
     *
     * @var string
     */
    protected string $method = 'GET';

    /**
     * Define the endpoint for the request.
     *
     * @return string
     */
    public function resolveEndpoint(): string
    {
        return $this->oauthConfig->getUserEndpoint();
    }

    /**
     * Requires the authorization code and OAuth 2 config.
     *
     * @param OAuthConfig $oauthConfig
     */
    public function __construct(protected OAuthConfig $oauthConfig)
    {
        //
    }
}
