<?php

namespace Thaumware\Core\Permissions;

interface ScopedUser
{
    public function getId(): string;
    public function getClientId(): ?string;
    public function getPermissions(): array;

}