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

namespace Phalcon\Tests\Unit\Mvc\Router\Route;

use Phalcon\Mvc\Router\Route;
use Phalcon\Tests\AbstractUnitTestCase;

final class GetBeforeMatchTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-09-07
     */
    public function testMvcRouterRouteGetBeforeMatch(): void
    {
        $route = new Route('/test');

        $this->assertNull(
            $route->getBeforeMatch()
        );

        $callback = function (): bool {
            return true;
        };

        $route->beforeMatch($callback);

        $this->assertIsCallable(
            $route->getBeforeMatch()
        );

        $this->assertSame(
            $callback,
            $route->getBeforeMatch()
        );
    }
}
