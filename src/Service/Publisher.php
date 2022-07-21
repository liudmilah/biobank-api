<?php

declare(strict_types=1);

namespace App\Service;

use phpcent\Client;

final class Publisher
{
    public function __construct(private Client $client)
    {
    }

    public function publish(string $channel, array $payload): void
    {
        $this->client->publish($channel, $payload);
    }

    public function generateToken(string $userId): string
    {
        return $this->client->generateConnectionToken($userId);
    }
}
