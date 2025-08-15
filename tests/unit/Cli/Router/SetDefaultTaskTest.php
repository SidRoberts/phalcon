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

final class SetDefaultTaskTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCliRouterSetDefaultTask(): void
    {
        $router = new Router();

        $this->assertSame(
            "",
            $router->getTaskName()
        );

        $router->handle("");

        $this->assertSame(
            "",
            $router->getTaskName()
        );

        $router->setDefaultTask("test");
        $router->handle("");

        $this->assertSame(
            "test",
            $router->getTaskName()
        );
    }
}
