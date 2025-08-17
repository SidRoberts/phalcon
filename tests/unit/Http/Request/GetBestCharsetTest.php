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

use Phalcon\Tests\Unit\Http\Helper\AbstractHttpBase;

final class GetBestCharsetTest extends AbstractHttpBase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-03-17
     */
    public function testHttpRequestGetBestCharset(): void
    {
        $request = $this->getRequestObject();

        $_SERVER['HTTP_ACCEPT_CHARSET'] = 'iso-8859-5,unicode-1-1;q=0.8';

        $clientCharsets = $request->getClientCharsets();

        $this->assertCount(2, $clientCharsets);


        $this->assertSame(
            'iso-8859-5',
            $clientCharsets[0]['charset']
        );

        $this->assertSame(
            1.0,
            $clientCharsets[0]['quality']
        );

        $this->assertSame(
            'unicode-1-1',
            $clientCharsets[1]['charset']
        );

        $this->assertSame(
            0.8,
            $clientCharsets[1]['quality']
        );

        $this->assertSame(
            'iso-8859-5',
            $request->getBestCharset()
        );
    }
}
