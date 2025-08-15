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

final class SetDefaultsTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCliRouterSetDefaults(): void
    {
        $router = new Router(false);

        $router->handle();

        $this->assertSame(
            "",
            $router->getModuleName()
        );

        $this->assertSame(
            "",
            $router->getTaskName()
        );

        $this->assertSame(
            "",
            $router->getActionName()
        );

        $defaults = [
            'module' => "testModule",
            'task'   => "testTask",
            'action' => "testAction",
        ];

        $router->setDefaults($defaults);

        $router->handle();

        $this->assertSame(
            $defaults["module"],
            $router->getModuleName()
        );

        $this->assertSame(
            $defaults["task"],
            $router->getTaskName()
        );

        $this->assertSame(
            $defaults["action"],
            $router->getActionName()
        );
    }
}
