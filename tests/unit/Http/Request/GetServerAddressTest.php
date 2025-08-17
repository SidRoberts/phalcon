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

use function gethostbyname;

final class GetServerAddressTest extends AbstractHttpBase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-04
     */
    public function testHttpRequestGetServerAddress(): void
    {
        $request = $this->getRequestObject();

        $_SERVER['SERVER_ADDR'] = Http::TEST_IP_ONE;

        $this->assertSame(
            Http::TEST_IP_ONE,
            $request->getServerAddress()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-04
     */
    public function testHttpRequestGetServerAddressDefault(): void
    {
        $request = $this->getRequestObject();

        $this->assertSame(
            gethostbyname('localhost'),
            $request->getServerAddress()
        );
    }
}
