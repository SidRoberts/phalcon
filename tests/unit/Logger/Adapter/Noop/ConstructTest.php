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

namespace Phalcon\Tests\Unit\Logger\Adapter\Noop;

use Phalcon\Logger\Adapter\AdapterInterface;
use Phalcon\Logger\Adapter\Noop;
use Phalcon\Tests\AbstractUnitTestCase;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Logger\Adapter\Noop :: __construct()
     *
     * @since 2025-10-13
     */
    public function testLoggerAdapterNoopInstanceOfAdapterInterface(): void
    {
        $adapter = new Noop();

        $this->assertInstanceOf(AdapterInterface::class, $adapter);
    }
}
