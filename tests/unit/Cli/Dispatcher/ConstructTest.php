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
use Phalcon\Cli\DispatcherInterface;
use Phalcon\Tests\AbstractUnitTestCase;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Cli\Dispatcher :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-13
     */
    public function testCliDispatcherInstanceOfDispatcherInterface(): void
    {
        $dispatcher = new Dispatcher();

        $this->assertInstanceOf(DispatcherInterface::class, $dispatcher);
    }
}
