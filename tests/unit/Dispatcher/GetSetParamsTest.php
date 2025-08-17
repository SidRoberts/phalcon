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

namespace Phalcon\Tests\Unit\Dispatcher;

use Phalcon\Mvc\Dispatcher;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Controllers\DispatcherParametersController;
use Phalcon\Tests\Support\Traits\DiTrait;

final class GetSetParamsTest extends AbstractUnitTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-11-17
     */
    public function testDispatcherGetParams(): void
    {
        $dispatcher = new Dispatcher();
        $params     = [
            'one'   => 'two',
            'three' => 'four',
        ];

        $this->assertEmpty(
            $dispatcher->getParams()
        );

        $dispatcher->setParams($params);

        $this->assertSame(
            $params,
            $dispatcher->getParams()
        );

        $dispatcher = new Dispatcher();
        $params     = [
            'one'   => 'two',
            'three' => 'four',
        ];

        $this->assertEmpty(
            $dispatcher->getParameters()
        );

        $dispatcher->setParameters($params);

        $this->assertSame(
            $params,
            $dispatcher->getParameters()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-11-17
     */
    public function testDispatcherGetParamsKeptBetweenCalls(): void
    {
        $this->setNewFactoryDefault();

        $dispatcher = new Dispatcher();
        $dispatcher->setDI($this->container);

        $dispatcher->setControllerSuffix("");
        $dispatcher->setControllerName(DispatcherParametersController::class);

        $this->assertEmpty(
            $dispatcher->getParams()
        );

        $params = [
            "trace" => [],
        ];
        $dispatcher->setParams($params);
        $dispatcher->dispatch();

        $expected = [
            "trace" => [
                "beforeExecuteRoute",
                "afterExecuteRoute",
            ],
        ];

        $this->assertSame(
            $expected,
            $dispatcher->getParams()
        );

        $dispatcher = new Dispatcher();
        $dispatcher->setDI($this->container);

        $dispatcher->setControllerSuffix("");
        $dispatcher->setControllerName(DispatcherParametersController::class);

        $this->assertEmpty(
            $dispatcher->getParameters()
        );

        $params = [
            "trace" => [],
        ];
        $dispatcher->setParameters($params);
        $dispatcher->dispatch();

        $expected = [
            "trace" => [
                "beforeExecuteRoute",
                "afterExecuteRoute",
            ],
        ];

        $this->assertSame(
            $expected,
            $dispatcher->getParameters()
        );
    }
}
