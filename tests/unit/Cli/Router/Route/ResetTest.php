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

namespace Phalcon\Tests\Unit\Cli\Router\Route;

use Phalcon\Cli\Router\Route;
use Phalcon\Tests\AbstractUnitTestCase;

final class ResetTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCliRouterRouteReset(): void
    {
        Route::reset();
        Route::delimiter('/');

        $route = new Route('test');

        $this->assertSame(
            '0',
            $route->getRouteId()
        );

        $route = new Route('test');
        $route = new Route('test');
        $route = new Route('test');

        $this->assertSame(
            '3',
            $route->getRouteId()
        );

        Route::reset();

        $route = new Route('test');

        $this->assertSame(
            '0',
            $route->getRouteId()
        );
    }
}
