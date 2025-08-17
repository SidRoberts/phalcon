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

namespace Phalcon\Tests\Unit\Http\Request;

use Phalcon\Tests\Support\Page\Http;
use Phalcon\Tests\Unit\Http\Helper\AbstractHttpBase;

final class IsStrictHostCheckTest extends AbstractHttpBase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-06-26
     */
    public function testHttpStrictHostCheck(): void
    {
        $host                   = 'LOCALHOST:80';
        $_SERVER['SERVER_NAME'] = $host;

        $request = $this->getRequestObject();
        $request->setStrictHostCheck();

        $this->assertSame(
            Http::HOST_LOCALHOST,
            $request->getHttpHost()
        );

        $this->assertTrue(
            $request->isStrictHostCheck()
        );

        $request->setStrictHostCheck(false);

        $this->assertSame(
            $host,
            $request->getHttpHost()
        );

        $this->assertFalse(
            $request->isStrictHostCheck()
        );
    }
}
