<?php

declare(strict_types=1);

namespace App\Auth\Command\Signup\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\User;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordHasher;
use App\Auth\Service\SignupConfirmationSender;
use App\Auth\Service\Tokenizer;
use App\Service\Flusher;
use App\Service\Id;
use DateTimeImmutable;
use DomainException;

final class Handler
{
    public function __construct(
        private UserRepository $users,
        private PasswordHasher $hasher,
        private Tokenizer $tokenizer,
        private Flusher $flusher,
        private SignupConfirmationSender $sender
    ) {
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        if ($this->users->hasByEmail($email)) {
            throw new DomainException('User already exists.');
        }

        $date = new DateTimeImmutable();

        $user = User::signupRequest(
            Id::generate(),
            $date,
            $email,
            $command->name,
            $this->hasher->hash($command->password),
            $token = $this->tokenizer->generate($date)
        );

        $this->users->add($user);

        $this->flusher->flush();

        $this->sender->send($email, $token, $command->name);
    }
}
