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
use Phalcon\Tests\AbstractUnitTestCase;

final class SetDefaultActionTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCliRouterSetDefaultAction(): void
    {
        $router = new Router(false);

        $this->assertSame(
            "",
            $router->getActionName()
        );

        $router->handle("");

        $this->assertSame(
            "",
            $router->getActionName()
        );

        $router->setDefaultAction("test");

        $router->handle("");

        $this->assertSame(
            "test",
            $router->getActionName()
        );
    }
}
