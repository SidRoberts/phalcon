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

namespace Phalcon\Tests\Unit\Http\Response;

use Phalcon\Tests\Support\Page\Http;
use Phalcon\Tests\Unit\Http\Helper\AbstractHttpBase;

final class SetNotModifiedTest extends AbstractHttpBase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-04-17
     */
    public function testHttpResponseSetNotModified(): void
    {
        $response = $this->getResponseObject();

        $response->setNotModified();

        $this->assertSame(
            Http::CODE_304,
            $response->getStatusCode()
        );

        $this->assertSame(
            Http::NOT_MODIFIED,
            $response->getReasonPhrase()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-08
     */
    public function testHttpResponseSetNotModifiedHeaders(): void
    {
        $response = $this->getResponseObject();
        $response->resetHeaders();
        $response->setNotModified();

        $headers = $response->getHeaders();

        $this->assertNull(
            $headers->get(Http::HTTP_304_NOT_MODIFIED)
        );

        $this->assertSame(
            Http::MESSAGE_304_NOT_MODIFIED,
            $headers->get(Http::STATUS)
        );
    }
}
