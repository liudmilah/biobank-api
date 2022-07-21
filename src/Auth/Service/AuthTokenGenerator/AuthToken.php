<?php
declare(strict_types=1);

namespace App\Auth\Service\AuthTokenGenerator;

use App\Auth\Service\JwtTokenizer;

abstract class AuthToken
{
    private ?string $userRole = null;
    private ?string $userId = null;
    private ?string $userEmail = null;
    private ?int $expiredAt = null;

    public function __construct(private JwtTokenizer $jwtTokenizer)
    {}

    /**
     * @return string|null
     */
    public function getUserRole(): ?string
    {
        return $this->userRole;
    }

    /**
     * @param string|null $userRole
     */
    public function setUserRole(?string $userRole): void
    {
        $this->userRole = $userRole;
    }

    /**
     * @return string|null
     */
    public function getUserId(): ?string
    {
        return $this->userId;
    }

    /**
     * @param string|null $userId
     */
    public function setUserId(?string $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * @return string|null
     */
    public function getUserEmail(): ?string
    {
        return $this->userEmail;
    }

    /**
     * @param string|null $userEmail
     */
    public function setUserEmail(?string $userEmail): void
    {
        $this->userEmail = $userEmail;
    }

    /**
     * @return int|null
     */
    public function getExpiredAt(): ?int
    {
        return $this->expiredAt;
    }

    /**
     * @param int|null $expiredAt
     */
    public function setExpiredAt(?int $expiredAt): void
    {
        $this->expiredAt = $expiredAt;
    }

    public function toJWT(): string
    {
        return $this->jwtTokenizer->encode([
            'user_id' => $this->userId,
            'user_role' => $this->userRole,
            'user_email' => $this->userEmail,
            'exp' => $this->expiredAt,
        ]);
    }

    public function validate(\DateTimeImmutable $now): void
    {
        if (!$this->userId || !$this->userEmail || !$this->userRole || !$this->expiredAt) {
            throw new \DomainException('Missing required fields.');
        }

        $expiredAt = (new \DateTimeImmutable())->setTimestamp($this->expiredAt);

        if ($expiredAt <= $now) {
            throw new \DomainException('Token is expired.');
        }
    }
}