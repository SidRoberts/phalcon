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

use Phalcon\Mvc\Router\Exception;
use Phalcon\Mvc\Router\Route;
use Phalcon\Tests\AbstractUnitTestCase;

final class CompilePatternTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-10-05
     */
    public function testMvcRouterRouteCompilePattern(): void
    {
        $route       = '/my-simple-route';
        $simpleRoute = new Route($route);

        $this->assertSame(
            $route,
            $simpleRoute->getCompiledPattern()
        );

        /**
         * Placeholder
         */
        $placeholderRoute = new Route(
            '/:module/:namespace/:controller/:action/:params/:int'
        );

        $this->assertSame(
            '#^/([\\w0-9\\_\\-]+)/([\\w0-9\\_\\-]+)/([\\w0-9\\_\\-]+)/([\\w0-9\\_\\-]+)(/.*)*/([0-9]+)$#u',
            $placeholderRoute->getCompiledPattern()
        );

        /**
         * Custom regex
         */
        $regexRoute = new Route(
            '/([\\w0-9\\_\\-]+)/([\\w0-9\\_\\-]+)/([\\w0-9\\_\\-]+)/([\\w0-9\\_\\-]+)(/.*)*/([0-9]+)'
        );

        $this->assertSame(
            '#^/([\\w0-9\\_\\-]+)/([\\w0-9\\_\\-]+)/([\\w0-9\\_\\-]+)/([\\w0-9\\_\\-]+)(/.*)*/([0-9]+)$#u',
            $regexRoute->getCompiledPattern()
        );
    }
}
