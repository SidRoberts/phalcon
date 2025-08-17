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

namespace Phalcon\Tests\Unit\Di\Service;

use Phalcon\Di\Di;
use Phalcon\Html\Escaper;
use Phalcon\Tests\AbstractUnitTestCase;

final class IsResolvedResolveTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceIsResolvedResolve(): void
    {
        $container = new Di();

        $container->set(
            'resolved',
            function () {
                return new Escaper();
            }
        );

        $container->set(
            'notResolved',
            function () {
                return new Escaper();
            }
        );

        $this->assertFalse(
            $container->getService('resolved')->isResolved()
        );

        $this->assertFalse(
            $container->getService('notResolved')->isResolved()
        );

        $container->get('resolved');

        $this->assertTrue(
            $container->getService('resolved')->isResolved()
        );

        $this->assertFalse(
            $container->getService('notResolved')->isResolved()
        );

        $container->getService('notResolved')->resolve();

        $this->assertTrue(
            $container->getService('notResolved')->isResolved()
        );
    }
}
