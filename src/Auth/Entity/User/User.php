<?php

declare(strict_types=1);

namespace App\Auth\Entity\User;

use App\Service\Id;
use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id as ORMId;
use Doctrine\ORM\Mapping\PostLoad;
use Doctrine\ORM\Mapping\Table;
use DomainException;

#[Entity]
#[Table(name: 'users')]
#[HasLifecycleCallbacks]
final class User
{
    #[ORMId]
    #[Column(type: 'bb_id')]
    private Id $id;
    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $date;
    #[Column(type: 'user_email', unique: true)]
    private Email $email;
    #[Column(type: 'string', length: 100)]
    private string $name;
    #[Column(type: 'string', length: 255, nullable: true)]
    private ?string $passwordHash = null;
    #[Column(type: 'user_status', length: 16)]
    private Status $status;
    #[Embedded(class: 'Token')]
    private ?Token $signupToken = null;
    #[Embedded(class: 'Token')]
    private ?Token $passwordResetToken = null;
    #[Column(type: 'user_role', length: 16)]
    private Role $role;

    private function __construct(Id $id, DateTimeImmutable $date, Email $email, string $name, Status $status)
    {
        $this->id = $id;
        $this->date = $date;
        $this->email = $email;
        $this->status = $status;
        $this->name = $name;
        $this->role = Role::user();
    }

    public static function signupRequest(
        Id $id,
        DateTimeImmutable $now,
        Email $email,
        string $name,
        string $passwordHash,
        Token $token
    ): self {
        $user = new self($id, $now, $email, $name, Status::wait());
        $user->passwordHash = $passwordHash;
        $user->signupToken = $token;
        return $user;
    }

    public function signupConfirm(string $token, DateTimeImmutable $now): void
    {
        if ($this->signupToken === null) {
            throw new DomainException('Confirmation is not required.');
        }

        $this->signupToken->validate($token, $now);
        $this->status = Status::active();
        $this->signupToken = null;
    }

    public function resetPasswordRequest(Token $token, DateTimeImmutable $now): void
    {
        if (!$this->isActive()) {
            throw new DomainException('User is not active.');
        }
        if ($this->passwordResetToken !== null && !$this->passwordResetToken->isExpiredTo($now)) {
            throw new DomainException('Resetting is already requested.');
        }
        $this->passwordResetToken = $token;
    }

    public function resetPassword(string $token, DateTimeImmutable $now, string $hash): void
    {
        if ($this->passwordResetToken === null) {
            throw new DomainException('Resetting is not requested.');
        }
        $this->passwordResetToken->validate($token, $now);
        $this->passwordResetToken = null;
        $this->passwordHash = $hash;
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function changePassword(string $newPasswordHash): void
    {
        $this->passwordHash = $newPasswordHash;
    }

    public function isWait(): bool
    {
        return $this->status->isWait();
    }

    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getDate(): DateTimeImmutable
    {
        return $this->date;
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getRole(): Role
    {
        return $this->role;
    }

    public function getPasswordHash(): ?string
    {
        return $this->passwordHash;
    }

    public function getSignupToken(): ?Token
    {
        return $this->signupToken;
    }

    public function getPasswordResetToken(): ?Token
    {
        return $this->passwordResetToken;
    }

    #[PostLoad]
    public function checkEmbeds(): void
    {
        if ($this->signupToken && $this->signupToken->isEmpty()) {
            $this->signupToken = null;
        }
        if ($this->passwordResetToken && $this->passwordResetToken->isEmpty()) {
            $this->passwordResetToken = null;
        }
    }
}
