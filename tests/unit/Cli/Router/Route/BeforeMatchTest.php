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

use Phalcon\Cli\Router;
use Phalcon\Cli\Router\Route;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Traits\DiTrait;

final class BeforeMatchTest extends AbstractUnitTestCase
{
    use DiTrait;

    public function testCliRouterRouteBeforeMatch(): void
    {
        $this->setNewCliFactoryDefault();

        Route::reset();

        $trace = 0;

        $router   = new Router(false);
        $callback = function () use (&$trace): bool {
            $trace++;

            return false;
        };

        /** @var Route $route1 */
        $route1 = $router->add('static route');
        $route1->beforeMatch($callback);

        $router
            ->add('static route2')
            ->beforeMatch(
                function () use (&$trace): bool {
                    $trace++;

                    return true;
                }
            )
        ;


        $router->handle();

        $this->assertFalse(
            $router->wasMatched()
        );


        $router->handle('static route');

        $this->assertFalse(
            $router->wasMatched()
        );


        $router->handle('static route2');

        $this->assertTrue(
            $router->wasMatched()
        );

        $this->assertSame(2, $trace);

        $this->assertSame(
            $callback,
            $route1->getBeforeMatch()
        );
    }
}
