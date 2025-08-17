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

final class RedirectTest extends AbstractHttpBase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-08
     */
    public function testHttpResponseRedirectLocalUrl(): void
    {
        $response = $this->getResponseObject();

        $response->resetHeaders();
        $response->redirect(Http::REDIRECT_URI);

        $headers = $response->getHeaders();

        $this->assertSame(
            Http::MESSAGE_302_FOUND,
            $headers->get(Http::STATUS)
        );

        $this->assertSame(
            '/' . Http::REDIRECT_URI,
            $headers->get(Http::LOCATION)
        );

        $this->assertNull(
            $headers->get(Http::HTTP_302_FOUND)
        );
    }

    /**
     * @issue  https://github.com/phalcon/cphalcon/issues/11324
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-01-19
     */
    public function testHttpResponseRedirectLocalUrlWithNonStandardCode(): void
    {
        $response = $this->getResponseObject();

        $response->resetHeaders();
        $response->redirect(Http::REDIRECT_URI, false, 309);

        $headers = $response->getHeaders();

        $this->assertSame(
            Http::MESSAGE_302_FOUND,
            $headers->get(Http::STATUS)
        );

        $this->assertSame(
            '/' . Http::REDIRECT_URI,
            $headers->get(Http::LOCATION)
        );

        $this->assertNull(
            $headers->get(Http::HTTP_302_FOUND)
        );
    }

    /**
     * @issue  https://github.com/phalcon/cphalcon/issues/1182
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-08
     */
    public function testHttpResponseRedirectRemoteUrl301(): void
    {
        $response = $this->getResponseObject();

        $response->resetHeaders();
        $response->redirect(
            Http::TEST_URI,
            true,
            Http::CODE_301
        );

        $headers = $response->getHeaders();

        $this->assertSame(
            Http::MESSAGE_301_MOVED_PERMANENTLY,
            $headers->get(Http::STATUS)
        );

        $this->assertSame(
            Http::TEST_URI,
            $headers->get(Http::LOCATION)
        );

        $this->assertFalse(
            $headers->get(Http::HTTP_302_FOUND)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-08
     */
    public function testHttpResponseRedirectRemoteUrl302(): void
    {
        $response = $this->getResponseObject();

        $response->resetHeaders();
        $response->redirect(Http::TEST_URI, true);

        $headers = $response->getHeaders();

        $this->assertSame(
            Http::MESSAGE_302_FOUND,
            $headers->get(Http::STATUS)
        );

        $this->assertSame(
            Http::TEST_URI,
            $headers->get(Http::LOCATION)
        );

        $this->assertNull(
            $headers->get(Http::HTTP_302_FOUND)
        );
    }
}
