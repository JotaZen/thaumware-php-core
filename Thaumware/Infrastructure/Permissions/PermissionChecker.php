<?php

namespace Thaumware\Core\Permissions;

class PermissionChecker
{
    private RequestContext $context;

    public function __construct(RequestContext $context)
    {
        $this->context = $context;
    }

    public function getUser(): ?ScopedUser
    {
        return $this->context->getUser();
    }

    public function getUserId(): ?string
    {
        $user = $this->context->getUser();
        return $user ? $user->getId() : null;
    }

    public function getClientId(): ?string
    {
        $user = $this->context->getUser();
        return $user ? $user->getClientId() : null;
    }

    public function getToken(): ?string
    {
        return $this->context->getToken();
    }

    public function userCanAccessClient($clientId): bool
    {
        $user = $this->context->getUser();
        return $user && strtolower($user->getClientId()) === strtolower($clientId);
    }
}