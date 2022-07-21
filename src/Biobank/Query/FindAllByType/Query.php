<?php

declare(strict_types=1);

namespace App\Biobank\Query\FindAllByType;

use Symfony\Component\Validator\Constraints as Assert;

final class Query
{
    #[Assert\NotBlank]
    public string $type = '';
    #[Assert\Range(min: 0, max: 300)]
    public int $limit = 0;
    public int $offset = 0;
    public string $search = '';
    public string $sort = '';
}
