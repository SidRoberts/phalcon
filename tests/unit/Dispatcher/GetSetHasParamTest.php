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
use Phalcon\Tests\Support\Traits\DiTrait;

final class GetSetHasParamTest extends AbstractUnitTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-11-17
     */
    public function testDispatcherGetSetHasParam(): void
    {
        $dispatcher = new Dispatcher();

        $this->assertFalse(
            $dispatcher->hasParam('one')
        );

        $dispatcher->setParam('one', 'two');

        $this->assertTrue(
            $dispatcher->hasParam('one')
        );

        $this->assertSame(
            'two',
            $dispatcher->getParam('one')
        );

        $dispatcher = new Dispatcher();

        $this->assertFalse(
            $dispatcher->hasParameter('one')
        );

        $dispatcher->setParameter('one', 'two');

        $this->assertTrue(
            $dispatcher->hasParameter('one')
        );

        $this->assertSame(
            'two',
            $dispatcher->getParameter('one')
        );

        $this->assertSame(
            'default',
            $dispatcher->getParameter('unknown', [], 'default')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-11-17
     */
    public function testDispatcherGetSetHasParameterFiltered(): void
    {
        $this->setNewFactoryDefault();

        $dispatcher = new Dispatcher();

        $dispatcher->setDI($this->container);

        $dispatcher->setParameter('one', '1234');

        $this->assertSame(
            1234,
            $dispatcher->getParameter('one', 'int')
        );
    }
}
