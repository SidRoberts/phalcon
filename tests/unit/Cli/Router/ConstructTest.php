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

namespace Phalcon\Tests\Unit\Cli\Router;

use Phalcon\Cli\Router;
use Phalcon\Cli\Router\Route;
use Phalcon\Cli\RouterInterface;
use Phalcon\Tests\AbstractUnitTestCase;

final class ConstructTest extends AbstractUnitTestCase
{
    public function setUp(): void
    {
        Route::reset();
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCliRouterInstanceOfRouterInterface(): void
    {
        $router = new Router();

        $this->assertInstanceOf(RouterInterface::class, $router);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCliRouterConstructNoDefaultRoutes(): void
    {
        $router = new Router(false);

        $this->assertInstanceOf(Router::class, $router);

        $this->assertSame(
            [],
            $router->getRoutes()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCliRouterConstructDefaultRoutes(): void
    {
        // Should contain 2 default routes.
        $router = new Router();

        $routes = $router->getRoutes();

        $this->assertCount(2, $routes);

        $this->assertSame(
            "#^(?: )?([a-zA-Z0-9\\_\\-]+)[ ]{0,1}$#",
            $routes[0]->getPattern()
        );

        $this->assertSame(
            "#^(?: )?([a-zA-Z0-9\\_\\-]+) ([a-zA-Z0-9\\.\\_]+)( .*)*$#",
            $routes[1]->getPattern()
        );
    }
}
