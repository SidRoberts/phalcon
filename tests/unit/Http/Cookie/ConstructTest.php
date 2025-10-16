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

namespace Phalcon\Tests\Unit\Http\Cookie;

use Phalcon\Http\Cookie;
use Phalcon\Http\Cookie\CookieInterface;
use Phalcon\Tests\Unit\Http\Helper\AbstractHttpBase;

final class ConstructTest extends AbstractHttpBase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-12
     */
    public function testHttpCookieInstanceOfCookieInterface(): void
    {
        $cookie = new Cookie('');

        $this->assertInstanceOf(CookieInterface::class, $cookie);
    }

    /**
     * Tests Phalcon\Http\Cookie :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testHttpCookieConstruct(): void
    {
        $this->markTestSkipped('Need implementation');
    }
}
