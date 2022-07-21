<?php

declare(strict_types=1);

use Doctrine\Common\Annotations\AnnotationRegistry;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

return [
    ValidatorInterface::class => static function (): ValidatorInterface {
        /** @psalm-suppress DeprecatedMethod */
        AnnotationRegistry::registerLoader('class_exists');

        return Validation::createValidatorBuilder()
            ->enableAnnotationMapping()
            ->setTranslationDomain('validators')
            ->getValidator();
    },
];
