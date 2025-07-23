<?php
namespace Thaumware\Core\Permissions;

use Illuminate\Support\Facades\Auth;

class RequestContext
{
    private ?ScopedUser $user;
    private ?string $token;

    public function __construct()
    {
        $user = Auth::user();
        $this->user = $user instanceof ScopedUser ? $user : null;
        $this->token = request()->header('AETHER-TOKEN');
    }

    public function getUser(): ?ScopedUser
    {
        return $this->user;
    }
    public function getToken(): ?string
    {
        return $this->token;
    }
}