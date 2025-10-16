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

namespace Phalcon\Tests\Unit\Mvc\Dispatcher;

use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\DispatcherInterface;
use Phalcon\Tests\Unit\Mvc\Dispatcher\Helper\BaseDispatcher;

final class ConstructTest extends BaseDispatcher
{
    /**
     * Tests Phalcon\Mvc\Dispatcher :: __construct()
     *
     * @since 2025-10-13
     */
    public function testMvcDispatcherInstanceOfDispatcherInterface(): void
    {
        $dispatcher = new Dispatcher();

        $this->assertInstanceOf(DispatcherInterface::class, $dispatcher);
    }
}
