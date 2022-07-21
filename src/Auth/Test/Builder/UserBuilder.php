<?php

declare(strict_types=1);

namespace App\Auth\Test\Builder;

use App\Auth\Entity\User\Email;
use App\Auth\Entity\User\Token;
use App\Auth\Entity\User\User;
use App\Service\Id;
use DateTimeImmutable;
use Ramsey\Uuid\Uuid;

final class UserBuilder
{
    private Id $id;
    private Email $email;
    private string $passwordHash;
    private string $name;
    private DateTimeImmutable $date;
    private Token $signupToken;
    private bool $active = false;

    public function __construct()
    {
        $this->id = Id::generate();
        $this->email = new Email('mail@example.com');
        $this->passwordHash = '$2y$12$qwnND33o8DGWvFoepotSju7eTAQ6gzLD/zy6W8NCVtiHPbkybz.w6'; // password
        $this->name = 'Test User';
        $this->date = new DateTimeImmutable();
        $this->signupToken = new Token(Uuid::uuid4()->toString(), $this->date->modify('+1 day'));
    }

    public function withId(Id $id): self
    {
        $clone = clone $this;
        $clone->id = $id;
        return $clone;
    }

    public function withEmail(Email $email): self
    {
        $clone = clone $this;
        $clone->email = $email;
        return $clone;
    }

    public function withSignupToken(Token $token): self
    {
        $clone = clone $this;
        $clone->signupToken = $token;
        return $clone;
    }

    public function active(): self
    {
        $clone = clone $this;
        $clone->active = true;
        return $clone;
    }

    public function build(): User
    {
        $user = User::signupRequest(
            $this->id,
            $this->date,
            $this->email,
            $this->name,
            $this->passwordHash,
            $this->signupToken
        );

        if ($this->active) {
            $user->signupConfirm(
                $this->signupToken->getValue(),
                $this->signupToken->getExpires()->modify('-1 day')
            );
        }

        return $user;
    }
}
