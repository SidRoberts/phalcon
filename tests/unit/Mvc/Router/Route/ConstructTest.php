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
use Phalcon\Mvc\Router\RouteInterface;
use Phalcon\Tests\AbstractUnitTestCase;

use function uniqid;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-12
     */
    public function testMvcRouterRouteInstanceOfRouteInterface(): void
    {
        $route = new Route('');

        $this->assertInstanceOf(RouteInterface::class, $route);
    }

    /**
     * Tests Phalcon\Mvc\Router\Route :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2022-01-27
     */
    public function testMvcRouterRouteConstruct(): void
    {
        $pattern = uniqid();
        $route   = new Route($pattern);

        $this->assertSame(
            $pattern,
            $route->getPattern()
        );
    }
}
