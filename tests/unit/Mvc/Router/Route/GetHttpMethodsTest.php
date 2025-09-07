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

final class GetHttpMethodsTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-09-07
     */
    public function testMvcRouterRouteGetHttpMethodsDefault(): void
    {
        $route = new Route("/");

        $this->assertNull(
            $route->getHttpMethods()
        );

        $route->setHttpMethods('GET');

        $this->assertSame(
            'GET',
            $route->getHttpMethods()
        );
    }

    /**
     * Tests Phalcon\Mvc\Router\Route :: getHttpMethods() - array
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-09-07
     */
    public function testMvcRouterRouteGetHttpMethodsArray(): void
    {
        $methods = ["GET", "POST"];

        $route = new Route("/", null, $methods);

        $this->assertSame(
            $methods,
            $route->getHttpMethods()
        );
    }

    /**
     * Tests Phalcon\Mvc\Router\Route :: getHttpMethods() - string
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-09-07
     */
    public function testMvcRouterRouteGetHttpMethodsString(): void
    {
        $methods = "GET";

        $route = new Route("/", null, $methods);

        $this->assertSame(
            $methods,
            $route->getHttpMethods()
        );
    }
}
