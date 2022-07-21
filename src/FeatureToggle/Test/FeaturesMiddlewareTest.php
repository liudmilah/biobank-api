<?php

declare(strict_types=1);

namespace App\FeatureToggle\Test;

use App\FeatureToggle\FeaturesMiddleware;
use App\FeatureToggle\FeatureSwitch;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Factory\ServerRequestFactory;

/**
 * @covers \App\FeatureToggle\FeaturesMiddleware
 *
 * @internal
 */
final class FeaturesMiddlewareTest extends TestCase
{
    public function testEmptyFeatures(): void
    {
        $switch = $this->createMock(FeatureSwitch::class);
        $switch->expects(self::never())->method('enable');
        $switch->expects(self::never())->method('disable');

        $middleware = new FeaturesMiddleware($switch, 'X-Features');

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response1 = $this->createResponse());

        $response2 = $middleware->process($this->createRequest(), $handler);

        self::assertSame($response1, $response2);
    }

    public function testWithFeatures(): void
    {
        $switch = $this->createMock(FeatureSwitch::class);
        $switch->expects(self::exactly(2))->method('enable')->withConsecutive(['TWO'], ['THREE']);
        $switch->expects(self::once())->method('disable')->withConsecutive(['ONE']);

        $middleware = new FeaturesMiddleware($switch, 'X-Features');

        $handler = $this->createStub(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($response1 = $this->createResponse());

        $request = self::createRequest()->withHeader('X-Features', '!ONE, TWO, THREE');

        $response2 = $middleware->process($request, $handler);

        self::assertSame($response1, $response2);
    }

    private function createResponse(): ResponseInterface
    {
        return (new ResponseFactory())->createResponse();
    }

    private function createRequest(): ServerRequestInterface
    {
        return (new ServerRequestFactory())->createServerRequest('POST', 'http://test');
    }
}
