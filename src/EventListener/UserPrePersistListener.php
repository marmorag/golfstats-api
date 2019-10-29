<?php

declare(strict_types=1);


namespace App\EventListener;


use App\Entity\User;
use App\Service\ApiTokenGenerator;

class UserPrePersistListener
{
    private $tokenGenerator;

    public function __construct(ApiTokenGenerator $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    public function prePersist(User $user): void
    {
        if ($user->getApiToken() === null) {
            $user->setApiToken($this->tokenGenerator->generate());
        }
    }
}