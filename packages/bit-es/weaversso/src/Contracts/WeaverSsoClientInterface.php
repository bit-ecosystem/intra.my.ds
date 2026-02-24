<?php

declare(strict_types=1);

namespace Bites\WeaverSSO\Contracts;

interface WeaverSsoClientInterface
{
    /**
     * Issue a Weaver session for the given login.
     * Returns [cookieName, cookieValue].
     */
    public function issueSession(string $weaverLogin): array;
}
