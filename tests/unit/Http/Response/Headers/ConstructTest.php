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

namespace Phalcon\Tests\Unit\Http\Response\Headers;

use Phalcon\Http\Response\Headers;
use Phalcon\Http\Response\HeadersInterface;
use Phalcon\Tests\AbstractUnitTestCase;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Http\Response\Headers :: get()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-13
     */
    public function testHttpResponseHeadersInstanceOfHeadersInterface(): void
    {
        $headers = new Headers();

        $this->assertInstanceOf(HeadersInterface::class, $headers);
    }
}
