<?php

declare(strict_types=1);

namespace App\Auth\Command\ResetPassword\Request;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\UserRepository;
use App\Auth\Service\PasswordResetTokenSender;
use App\Auth\Service\Tokenizer;
use App\Service\Flusher;
use DateTimeImmutable;

final class Handler
{
    private UserRepository $users;
    private Tokenizer $tokenizer;
    private Flusher $flusher;
    private PasswordResetTokenSender $sender;

    public function __construct(
        UserRepository $users,
        Tokenizer $tokenizer,
        Flusher $flusher,
        PasswordResetTokenSender $sender
    ) {
        $this->users = $users;
        $this->tokenizer = $tokenizer;
        $this->flusher = $flusher;
        $this->sender = $sender;
    }

    public function handle(Command $command): void
    {
        $email = new Email($command->email);

        try {
            $user = $this->users->getByEmail($email);
        } catch (\Throwable) {
            return;
        }

        $date = new DateTimeImmutable();

        $user->resetPasswordRequest(
            $token = $this->tokenizer->generate($date),
            $date
        );

        $this->flusher->flush();

        $this->sender->send($email, $token);
    }
}
