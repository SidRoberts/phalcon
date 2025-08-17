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
use PHPUnit\Framework\Attributes\BackupGlobals;

use function uniqid;

final class GetTest extends AbstractHttpBase
{
    /**
     * @issue https://github.com/phalcon/cphalcon/issues/1265
     * @author Phalcon Team <team@phalcon.io>
     * @since  2014-10-04
     */
    #[BackupGlobals(true)]
    public function testHttpRequestGet(): void
    {
        $key   = uniqid('key-');
        $value = uniqid('val-');

        $_REQUEST = [
            'id'         => 1,
            'num'        => 'a1a',
            'age'        => 'aa',
            'phone'      => '',
            'string-key' => 'hello',
            'array-key'  => ['string' => 'world'],
        ];

        $request = $this->getRequestObject();

        $this->assertFalse(
            $request->has($key)
        );

        $_REQUEST[$key] = $value;

        $this->assertTrue(
            $request->has($key)
        );

        $this->assertSame(
            $value,
            $request->get($key)
        );

        /**
         * Get - different methods
         */
        $this->assertSame(
            'hello',
            $request->get('string-key', 'string')
        );

        $actual = $request->get(
            'string-key',
            'string',
            null,
            true,
            true
        );
        $this->assertSame('hello', $actual);

        $expected = ['string' => 'world'];

        $this->assertSame(
            $expected,
            $request->get('array-key', 'string')
        );

        $expected = ['string' => 'world'];
        $actual   = $request->get(
            'array-key',
            'string',
            null,
            true,
            false
        );
        $this->assertSame($expected, $actual);

        $this->assertSame(
            1,
            $request->get('id', 'int', 100)
        );

        $this->assertSame(
            1,
            $request->get('num', 'int', 100)
        );

        $this->assertEmpty(
            $request->get('age', 'int', 100)
        );

        $this->assertEmpty(
            $request->get('phone', 'int', 100)
        );

        $this->assertSame(
            100,
            $request->get('phone', 'int', 100, true)
        );
    }
}
