<?php

declare(strict_types=1);

namespace App\Auth\Command\Signup\Confirm;

use App\Auth\Entity\User\UserRepository;
use App\Service\Flusher;
use DateTimeImmutable;
use DomainException;

final class Handler
{
    public function __construct(private UserRepository $users, private Flusher $flusher)
    {
    }

    public function handle(Command $command): void
    {
        if (!$user = $this->users->findBySignupConfirmToken($command->token)) {
            throw new DomainException('Incorrect token.');
        }

        try {
            $user->signupConfirm($command->token, new DateTimeImmutable());
        } catch (DomainException $e) {
            if ($e->getMessage() === 'Token is expired.') {
                $this->users->remove($user);
            }
            throw $e;
        } finally {
            $this->flusher->flush();
        }
    }
}
