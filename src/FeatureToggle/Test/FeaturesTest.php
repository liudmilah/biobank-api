<?php

declare(strict_types=1);

namespace App\FeatureToggle\Test;

use App\FeatureToggle\Features;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\FeatureToggle\Features
 *
 * @internal
 */
final class FeaturesTest extends TestCase
{
    public function testIsEnabled(): void
    {
        $features = new Features([
            'ONE' => true,
            'TWO' => true,
            'THREE' => false,
        ]);

        self::assertTrue($features->isEnabled('ONE'));
        self::assertTrue($features->isEnabled('TWO'));
        self::assertFalse($features->isEnabled('THREE'));
        self::assertFalse($features->isEnabled('FOUR'));
    }

    public function testEnable(): void
    {
        $features = new Features([
            'ONE' => true,
            'TWO' => true,
            'THREE' => false,
        ]);

        $features->enable('ONE');
        $features->enable('THREE');
        $features->enable('FOUR');

        self::assertTrue($features->isEnabled('ONE'));
        self::assertTrue($features->isEnabled('TWO'));
        self::assertTrue($features->isEnabled('THREE'));
        self::assertTrue($features->isEnabled('FOUR'));
    }

    public function testDisable(): void
    {
        $features = new Features([
            'ONE' => true,
            'TWO' => true,
            'THREE' => false,
        ]);

        $features->disable('ONE');
        $features->disable('FOUR');

        self::assertFalse($features->isEnabled('ONE'));
        self::assertTrue($features->isEnabled('TWO'));
        self::assertFalse($features->isEnabled('THREE'));
        self::assertFalse($features->isEnabled('FOUR'));
    }

    public function testGetEnabled(): void
    {
        $features = new Features([
            'ONE' => true,
            'TWO' => true,
            'THREE' => false,
        ]);

        self::assertEquals(['ONE', 'TWO'], $features->getAllEnabled());
    }
}
