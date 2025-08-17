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

namespace Phalcon\Tests\Unit\Cli\Dispatcher;

use Phalcon\Cli\Dispatcher;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Traits\DiTrait;

final class GetSetParametersTest extends AbstractUnitTestCase
{
    use DiTrait;

    public function testCliDispatcherGetSetParameters(): void
    {
        $this->setNewCliFactoryDefault();
        $dispatcher = new Dispatcher();

        $this->container->setShared('dispatcher', $dispatcher);
        $dispatcher->setDI($this->container);

        // Test $this->dispatcher->getParams()
        $dispatcher->setNamespaceName('Phalcon\Tests\Support\Tasks');
        $dispatcher->setTaskName('params');
        $dispatcher->setActionName('params');

        $dispatcher->setParameters(
            [
                'a' => 'This',
                'b' => 'Is',
                'c' => 'An',
                'd' => 'Example',
            ]
        );

        $dispatcher->dispatch();

        $this->assertSame(
            'This',
            $dispatcher->getParam('a')
        );

        $this->assertSame(
            'Is',
            $dispatcher->getParam('b')
        );

        $this->assertSame(
            'An',
            $dispatcher->getParam('c')
        );

        $this->assertSame(
            'Example',
            $dispatcher->getParam('d')
        );

        $this->assertSame(
            'This',
            $dispatcher->getParameter('a')
        );

        $this->assertSame(
            'Is',
            $dispatcher->getParameter('b')
        );

        $this->assertSame(
            'An',
            $dispatcher->getParameter('c')
        );

        $this->assertSame(
            'Example',
            $dispatcher->getParameter('d')
        );

        $dispatcher->setParam('e', 'one');

        $this->assertSame(
            'one',
            $dispatcher->getParam('e')
        );

        $dispatcher->setParameter('f', 'two');

        $this->assertSame(
            'two',
            $dispatcher->getParameter('f')
        );
    }
}
