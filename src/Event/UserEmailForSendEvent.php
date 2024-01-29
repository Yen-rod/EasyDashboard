<?php

declare(strict_types=1);

namespace App\Event;

use App\Entity\User;

class UserEmailForSendEvent
{
    public const USER_CREATE_ACTION = 'user.create.action';
    public const USER_RESET_ACTION = 'user.reset.action';

    public function __construct(private readonly User $user)
    {}

    public function getUser(): User
    {
        return $this->user;
    }


}
