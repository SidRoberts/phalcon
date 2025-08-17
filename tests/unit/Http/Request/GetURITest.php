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

final class GetURITest extends AbstractHttpBase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function testHttpRequestGetURI(): void
    {
        $uri                    = Http::TEST_URI . '?a=b';
        $_SERVER['REQUEST_URI'] = $uri;

        $request = $this->getRequestObject();

        $this->assertSame(
            $uri,
            $request->getURI()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function testHttpRequestGetURIDefault(): void
    {
        $request = $this->getRequestObject();

        $this->assertEmpty(
            $request->getURI()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function testHttpRequestGetURIOnlyPath(): void
    {
        $uri                    = Http::TEST_URI . '?a=b';
        $_SERVER['REQUEST_URI'] = $uri;

        $request = $this->getRequestObject();

        $this->assertSame(
            Http::TEST_URI,
            $request->getURI(true)
        );
    }
}
