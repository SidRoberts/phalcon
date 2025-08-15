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
use Phalcon\Tests\AbstractUnitTestCase;

final class AddTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCliRouterAdd(): void
    {
        Route::reset();

        $router = new Router(false);

        $this->assertSame(
            [],
            $router->getRoutes()
        );

        $router->add(
            'route',
            [
                'module' => 'devtools',
                'task'   => 'main',
                'action' => 'hello',
            ]
        );

        $router->handle('route');

        $routes = $router->getRoutes();

        $this->assertInstanceOf(
            Route::class,
            $routes[0]
        );
    }
}
