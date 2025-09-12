<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Mvc\Router\Group;

use Phalcon\Mvc\Router\Group;
use Phalcon\Mvc\Router\Route;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Mvc\Fake\RouterTrait;
use PHPUnit\Framework\Attributes\DataProvider;

final class GetSetHostnameTest extends AbstractUnitTestCase
{
    use RouterTrait;

    /**
     * @return array<array{0: ?string, 1: ?string, 2: string}>
     */
    public static function getHostnameRoutes(): array
    {
        return [
            [
                'localhost',
                null,
                'posts3',
            ],
            [
                'my.phalcon.io',
                'my.phalcon.io',
                'posts',
            ],
            [
                null,
                null,
                'posts3',
            ],
        ];
    }

    /**
     * @return array<array{0: ?string, 1: ?string, 2: string}>
     */
    public static function getHostnameRoutesRegex(): array
    {
        return [
            [
                'localhost',
                null,
                'posts3',
            ],
            [
                'my.phalcon.io',
                '([a-z]+).phalcon.io',
                'posts',
            ],
            [
                null,
                null,
                'posts3',
            ],
        ];
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testMvcRouterGroupGetHostname(): void
    {
        $group = new Group();

        $this->assertNull(
            $group->getHostname()
        );

        $hostname = 'https://phalcon.io';

        $group->setHostname($hostname);

        $this->assertSame(
            $hostname,
            $group->getHostname()
        );
    }

    #[DataProvider('getHostnameRoutesRegex')]
    public function testMvcRouterGroupGetHostnameRegexRouteGroup(
        ?string $actualHost,
        ?string $expectedHost,
        string $controller
    ): void {
        Route::reset();

        $router = $this->getRouter(false);

        $router->add(
            '/edit',
            [
                'controller' => 'posts3',
                'action'     => 'edit3',
            ]
        );

        $group = new Group();

        $group->setHostname('([a-z]+).phalcon.io');

        $group->add(
            '/edit',
            [
                'controller' => 'posts',
                'action'     => 'edit',
            ]
        );

        $router->mount($group);

        $_SERVER['HTTP_HOST'] = $actualHost;

        $router->handle('/edit');

        $this->assertSame(
            $controller,
            $router->getControllerName()
        );

        $matchedRoute = $router->getMatchedRoute();

        $this->assertNotNull($matchedRoute);

        $this->assertSame(
            $expectedHost,
            $matchedRoute->getHostname()
        );
    }

    #[DataProvider('getHostnameRoutes')]
    public function testMvcRouterGroupGetHostnameRouteGroup(
        ?string $actualHost,
        ?string $expectedHost,
        string $controller
    ): void {
        Route::reset();

        $router = $this->getRouter(false);

        $router->add(
            '/edit',
            [
                'controller' => 'posts3',
                'action'     => 'edit3',
            ]
        );

        $group = new Group();

        $group->setHostname('my.phalcon.io');

        $group->add(
            '/edit',
            [
                'controller' => 'posts',
                'action'     => 'edit',
            ]
        );

        $router->mount($group);

        $_SERVER['HTTP_HOST'] = $actualHost;

        $router->handle('/edit');

        $this->assertSame(
            $controller,
            $router->getControllerName()
        );

        $matchedRoute = $router->getMatchedRoute();

        $this->assertNotNull($matchedRoute);

        $this->assertSame(
            $expectedHost,
            $matchedRoute->getHostname()
        );
    }
}
