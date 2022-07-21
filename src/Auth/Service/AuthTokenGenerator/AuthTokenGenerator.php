<?php
declare(strict_types=1);

namespace App\Auth\Service\AuthTokenGenerator;

use App\Auth\Service\JwtTokenizer;

final class AuthTokenGenerator
{
    public function __construct(
        private JwtTokenizer $jwtTokenizer,
        private \DateInterval $accessTokenTtl,
        private \DateInterval $refreshTokenTtl
    )
    {}

    public function generateAccessToken(Params $params): AccessToken
    {
        $token = new AccessToken($this->jwtTokenizer);

        $token->setUserId($params->userId);
        $token->setUserRole($params->role);
        $token->setUserEmail($params->email);
        $token->setExpiredAt($params->date->add($this->accessTokenTtl)->getTimestamp());

        return $token;
    }

    public function generateRefreshToken(Params $params): RefreshToken
    {
        $token = new RefreshToken($this->jwtTokenizer);

        $token->setUserId($params->userId);
        $token->setUserRole($params->role);
        $token->setUserEmail($params->email);
        $token->setExpiredAt($params->date->add($this->refreshTokenTtl)->getTimestamp());

        return $token;
    }

    public function getAccessTokenFromJwt(string $jwt, \DateTimeImmutable $now): AccessToken
    {
        /** @var object{user_id: string, user_role: string, user_email: string, exp: int} $decoded */
        $decoded = $this->jwtTokenizer->decode($jwt);

        $token = new AccessToken($this->jwtTokenizer);

        $token->setUserId($decoded->user_id);
        $token->setUserRole($decoded->user_role);
        $token->setUserEmail($decoded->user_email);
        $token->setExpiredAt($decoded->exp);

        $token->validate($now);

        return $token;
    }

    public function getRefreshTokenFromJwt(string $jwt, \DateTimeImmutable $now): RefreshToken
    {
        /** @var object{user_id: string, user_role: string, user_email: string, exp: int} $decoded */
        $decoded = $this->jwtTokenizer->decode($jwt);

        $token = new RefreshToken($this->jwtTokenizer);

        $token->setUserId($decoded->user_id);
        $token->setUserRole($decoded->user_role);
        $token->setUserEmail($decoded->user_email);
        $token->setExpiredAt($decoded->exp);

        $token->validate($now);

        return $token;
    }
}