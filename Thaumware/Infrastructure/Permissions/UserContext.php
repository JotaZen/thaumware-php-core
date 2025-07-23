<?php
namespace Thaumware\Core\Permissions;

use Illuminate\Support\Facades\Auth;
use Thaumware\Core\Permissions\ScopedUser;

class UserContext
{
    private ?ScopedUser $user;
    private ?string $clientId;


    public function loadScopedUser(ScopedUser $user): void
    {
        $this->user = $user;
        $this->clientId = $user->getClientId();
    }


    public function getUser(): ?ScopedUser
    {
        return $this->user;
    }

    public function getUserId(): ?string
    {
        return $this->user?->getId();
    }

    public function getClientId(): ?string
    {
        return $this->clientId;
    }


    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->user?->getPermissions() ?? []);
    }

    public function canAccessClient(string $clientId): bool
    {
        return strtolower($clientId) === strtolower($this->clientId);
    }
}