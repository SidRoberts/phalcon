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

use Phalcon\Tests\Unit\Http\Helper\AbstractHttpBase;

final class RestoreTest extends AbstractHttpBase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testHttpCookieRestore(): void
    {
        $this->setDiService('sessionStream');

        $name     = 'test';
        $value    = "phalcon";
        $expire   = time() - 100;
        $path     = "/";
        $secure   = true;
        $domain   = "phalcon.ld";
        $httpOnly = true;

        $cookie = $this->getCookieObject();

        $this->assertSame(
            $name,
            $cookie->getName()
        );

        $this->assertSame(
            $value,
            $cookie->getValue()
        );

        $this->assertSame(
            $expire,
            $cookie->getExpiration()
        );

        $this->assertSame(
            $path,
            $cookie->getPath()
        );

        $this->assertSame(
            $secure,
            $cookie->getSecure()
        );

        $this->assertSame(
            $domain,
            $cookie->getDomain()
        );

        $this->assertSame(
            $httpOnly,
            $cookie->getHttpOnly()
        );

        $cookie->restore();

        $this->assertSame(
            $name,
            $cookie->getName()
        );

        $this->assertSame(
            $value,
            $cookie->getValue()
        );

        $this->assertSame(
            $expire,
            $cookie->getExpiration()
        );

        $this->assertSame(
            $path,
            $cookie->getPath()
        );

        $this->assertSame(
            $secure,
            $cookie->getSecure()
        );

        $this->assertSame(
            $domain,
            $cookie->getDomain()
        );

        $this->assertSame(
            $httpOnly,
            $cookie->getHttpOnly()
        );
    }
}
