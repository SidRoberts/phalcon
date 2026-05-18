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

final class GetSetExpirationTest extends AbstractHttpBase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testHttpCookieGetSetExpiration(): void
    {
        $this->setDiService('sessionStream');

        $expire = time() - 100;
        $cookie = $this->getCookieObject();

        $this->assertSame(
            $expire,
            $cookie->getExpiration()
        );

        $expire = time() - 200;
        $cookie->setExpiration($expire);

        $this->assertSame(
            $expire,
            $cookie->getExpiration()
        );
    }
}
