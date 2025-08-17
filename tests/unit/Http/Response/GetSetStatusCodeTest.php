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

use Phalcon\Mvc\Micro;
use Phalcon\Tests\Support\Page\Http;
use Phalcon\Tests\Unit\Http\Helper\AbstractHttpBase;
use Phalcon\Tests\Unit\Http\Response\Fake\FakeHttpResponseContentMiddleware;

use function ob_get_clean;
use function ob_start;
use function uniqid;
use function xdebug_get_headers;

final class GetSetStatusCodeTest extends AbstractHttpBase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-12-24
     */
    public function testHttpResponseGetSetStatusCode(): void
    {
        $code = Http::CODE_200;

        $response = $this->getResponseObject();

        $response->setStatusCode($code);

        $this->assertSame(
            $code,
            $response->getStatusCode()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-08
     */
    public function testHttpResponseSetStatusCode(): void
    {
        $response = $this->getResponseObject();

        $response->resetHeaders();

        $response->setStatusCode(
            Http::CODE_404,
            Http::NOT_FOUND
        );

        $headers = $response->getHeaders();

        $this->assertTrue(
            $headers->has(Http::HTTP_404_NOT_FOUND)
        );

        $this->assertNull(
            $headers->get(Http::HTTP_404_NOT_FOUND)
        );

        $this->assertSame(
            Http::MESSAGE_404_NOT_FOUND,
            $headers->get(Http::STATUS)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-08
     */
    public function testHttpResponseSetStatusCodeSendMicro(): void
    {
        $this->checkExtensionIsLoaded('xdebug');

        $application = new Micro($this->container);

        $application->before(
            new FakeHttpResponseContentMiddleware()
        );

        $application->notFound(
            function (): string {
                return '404 - handler';
            }
        );

        $application->get(
            "/",
            function (): string {
                return '200 - "/"';
            }
        );

        ob_start();
        $application->handle("/");
        $contents = ob_get_clean();

        $expected = [
            "Status: 404 Not Found",
            "Content-Type: application/json",
        ];

        $this->assertSame(
            $expected,
            xdebug_get_headers()
        );

        $this->assertSame('{"test":123}', $contents);
    }

    /**
     * @issue  https://github.com/phalcon/cphalcon/issues/1892
     * @author Kamil Skowron <git@hedonsoftware.com>
     * @since  2014-05-28
     */
    public function testMultipleHttpHeaders(): void
    {
        $response = $this->getResponseObject();

        $response->resetHeaders();
        $response->setStatusCode(Http::CODE_200, Http::OK);
        $response->setStatusCode(Http::CODE_404, Http::NOT_FOUND);
        $response->setStatusCode(Http::CODE_409, Http::CONFLICT);

        $headers = $response->getHeaders();

        $this->assertNull(
            $headers->get(Http::HTTP_409_CONFLICT)
        );

        $this->assertSame(
            Http::MESSAGE_409_CONFLICT,
            $headers->get(Http::STATUS)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2024-01-01
     */
    public function testHttpResponseSetStatusCodeUnknownCodeThrows(): void
    {
        $response = $this->getResponseObject();

        $this->expectException(\Phalcon\Http\Response\Exception::class);
        $this->expectExceptionMessage('Non-standard status-code given without a message');
        $response->setStatusCode(999);
    }

    public function testSetStatusCodeDefaultMessage(): void
    {
        $response = $this->getResponseObject();

        $response->resetHeaders();
        $response->setStatusCode(Http::CODE_103);

        $headers = $response->getHeaders();

        $this->assertNull(
            $headers->get(Http::HTTP_103_EARLY_HINTS)
        );

        $this->assertSame(
            Http::MESSAGE_103_EARLY_HINTS,
            $headers->get(Http::STATUS)
        );

        /**
         * 200
         */
        $response->setStatusCode(Http::CODE_200);

        $headers = $response->getHeaders();

        $this->assertNull(
            $headers->get(Http::HTTP_200_OK)
        );

        $this->assertSame(
            Http::MESSAGE_200_OK,
            $headers->get(Http::STATUS)
        );

        /**
         * 418
         */
        $response->setStatusCode(Http::CODE_418);

        $headers = $response->getHeaders();

        $this->assertNull(
            $headers->get(Http::HTTP_418_IM_A_TEAPOT)
        );

        $this->assertSame(
            Http::MESSAGE_418_IM_A_TEAPOT,
            $headers->get(Http::STATUS)
        );

        /**
         * 418 Custom
         */
        $message = uniqid('mess-');
        $status  = '418 ' . $message;
        $name    = 'HTTP/1.1 ' . $status;
        $response->setStatusCode(Http::CODE_418, $message);

        $headers = $response->getHeaders();

        $this->assertNull(
            $headers->get($name)
        );

        $this->assertSame(
            $status,
            $headers->get(Http::STATUS)
        );
    }
}
