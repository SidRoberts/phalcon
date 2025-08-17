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

final class GetTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-05-08
     */
    public function testHttpResponseHeadersGet(): void
    {
        $headers = new Headers();

        $headers->set(
            Http::CONTENT_TYPE,
            Http::CONTENT_TYPE_HTML
        );

        $this->assertSame(
            Http::CONTENT_TYPE_HTML,
            $headers->get(Http::CONTENT_TYPE)
        );

        $headers->set(
            Http::CONTENT_TYPE,
            Http::CONTENT_TYPE_PLAIN
        );

        $this->assertSame(
            Http::CONTENT_TYPE_PLAIN,
            $headers->get(Http::CONTENT_TYPE)
        );
    }
}
