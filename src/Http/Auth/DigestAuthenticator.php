<?php declare(strict_types=1);

namespace Saloon\Http\Auth;

use Saloon\Http\PendingRequest;
use Saloon\Contracts\Authenticator;

class DigestAuthenticator implements Authenticator
{
    /**
     * @param string $username
     * @param string $password
     * @param string $digest
     */
    public function __construct(
        public string $username,
        public string $password,
        public string $digest,
    ) {
        //
    }

    /**
     * Apply the authentication to the request.
     *
     * @param PendingRequest $pendingRequest
     * @return void
     */
    public function set(PendingRequest $pendingRequest): void
    {
        $pendingRequest->config()->add('auth', [$this->username, $this->password, $this->digest]);
    }
}
