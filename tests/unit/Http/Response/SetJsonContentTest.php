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

use InvalidArgumentException;
use Phalcon\Http\Response;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Page\Http;

use function json_encode;

use const JSON_HEX_TAG;

final class SetJsonContentTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2024-01-01
     */
    public function testHttpResponseSetJsonContentEncodeErrorThrows(): void
    {
        $response = new Response();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('json_encode error');
        $response->setJsonContent(NAN);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-12-07
     */
    public function testHttpResponseSetJsonContent(): void
    {
        $content = [
            'sentence' => 'it\'s a "city"',
            'word'     => '<h1>city</h1>',
        ];

        $response = new Response();
        $response->setJsonContent($content);

        // Check content
        $this->assertSame(
            json_encode($content),
            $response->getContent()
        );

        // Check Header
        $this->assertSame(
            Http::CONTENT_TYPE_JSON,
            $response->getHeaders()->get(Http::CONTENT_TYPE)
        );

        // With option
        $response = new Response();
        $response->setJsonContent($content, JSON_HEX_TAG);

        $this->assertSame(
            json_encode($content, JSON_HEX_TAG),
            $response->getContent()
        );
    }
}
