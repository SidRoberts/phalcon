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
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Page\Http;

final class SendTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-05-08
     */
    public function testHttpResponseHeadersSend(): void
    {
        $headers = new Headers();

        $headers->set(
            Http::CONTENT_TYPE,
            Http::CONTENT_TYPE_HTML_CHARSET
        );
        $headers->set(
            Http::CONTENT_ENCODING,
            Http::CONTENT_ENCODING_GZIP
        );

        $this->assertTrue(
            $headers->send()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2024-01-01
     */
    public function testHttpResponseHeadersSendRawNoColon(): void
    {
        $headers = new Headers();

        $headers->setRaw('X-Custom-Header');

        $this->assertTrue(
            $headers->send()
        );
    }

    /**
     * @issue  https://github.com/phalcon/cphalcon/issues/15334
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-04-22
     */
    public function testHttpResponseHeadersSendTwice(): void
    {
        $headers = new Headers();

        $headers->set(
            Http::CONTENT_TYPE,
            Http::CONTENT_TYPE_HTML_CHARSET
        );
        $headers->set(
            Http::CONTENT_ENCODING,
            Http::CONTENT_ENCODING_GZIP
        );

        $this->assertFalse(
            $headers->isSent()
        );

        $this->assertTrue(
            $headers->send()
        );

        $this->assertTrue(
            $headers->isSent()
        );

        $this->assertFalse(
            $headers->send()
        );
    }
}
