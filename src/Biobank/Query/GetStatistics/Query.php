<?php

declare(strict_types=1);

namespace App\Biobank\Query\GetStatistics;

use Symfony\Component\Validator\Constraints as Assert;

final class Query
{
    #[Assert\NotBlank, Assert\Length(max: 20)]
    public string $type = '';
}
