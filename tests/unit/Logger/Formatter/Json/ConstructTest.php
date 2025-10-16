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

namespace Phalcon\Tests\Unit\Logger\Formatter\Json;

use Phalcon\Logger\Formatter\FormatterInterface;
use Phalcon\Logger\Formatter\Json;
use Phalcon\Tests\AbstractUnitTestCase;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Logger\Formatter\Json :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-13
     */
    public function testLoggerFormatterJsonInstanceOfFormatterInterface(): void
    {
        $formatter = new Json();

        $this->assertInstanceOf(FormatterInterface::class, $formatter);
    }
}
