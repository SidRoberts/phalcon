<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Http\Response;

use Phalcon\Di\InjectionAwareInterface;
use Phalcon\Events\EventsAwareInterface;
use Phalcon\Http\Message\ResponseStatusCodeInterface;
use Phalcon\Http\Response;
use Phalcon\Http\ResponseInterface;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Page\Http;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-12-08
     */
    public function testHttpResponseInstanceOfResponseInterface(): void
    {
        $response = new Response();

        $this->assertInstanceOf(ResponseInterface::class, $response);

        $this->assertInstanceOf(InjectionAwareInterface::class, $response);

        $this->assertInstanceOf(EventsAwareInterface::class, $response);

        $this->assertInstanceOf(ResponseStatusCodeInterface::class, $response);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-12-08
     */
    public function testHttpResponseConstructWithContent(): void
    {
        $content  = Http::TEST_CONTENT;
        $response = new Response($content);

        $this->assertSame(
            $content,
            $response->getContent()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-12-08
     */
    public function testHttpResponseConstructWithContentCode(): void
    {
        $content = Http::TEST_CONTENT;
        $code    = Http::CODE_200;

        $response = new Response($content, $code);

        $this->assertSame(
            $content,
            $response->getContent()
        );

        $this->assertSame(
            $code,
            $response->getStatusCode()
        );

        // Check Status message
        $this->assertSame(
            Http::MESSAGE_200_OK,
            $response->getHeaders()->get(Http::STATUS)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-12-08
     */
    public function testHttpResponseConstructWithContentCodeStatus(): void
    {
        $content = Http::TEST_CONTENT;
        $code    = Http::CODE_200;

        $response = new Response($content, $code, 'Success');

        $this->assertSame(
            $content,
            $response->getContent()
        );

        $this->assertSame(
            $code,
            $response->getStatusCode()
        );

        // Check Status message
        $this->assertSame(
            Http::MESSAGE_200_SUCCESS,
            $response->getHeaders()->get(Http::STATUS)
        );
    }
}
