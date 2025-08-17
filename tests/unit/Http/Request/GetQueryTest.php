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

use function strtolower;
use function uniqid;

final class GetQueryTest extends AbstractHttpBase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-12-01
     */
    public function testHttpRequestGetQuery(): void
    {
        $key        = uniqid('key-');
        $value      = uniqid('val-');
        $unknown    = uniqid('unk-');
        $_GET[$key] = $value;

        $request = $this->getRequestObject();

        $this->assertTrue(
            $request->hasQuery($key)
        );

        $this->assertFalse(
            $request->hasQuery($unknown)
        );

        $this->assertSame(
            $value,
            $request->getQuery($key)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-12-01
     */
    public function testHttpRequestGetQueryAllowNoEmpty(): void
    {
        $key = uniqid('key-');

        $_GET[$key] = ' 0 ';

        $request = $this->getRequestObject();

        $this->assertSame(
            '0',
            $request->getQuery($key, 'trim', 'zero value', true)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-12-01
     */
    public function testHttpRequestGetQueryDefault(): void
    {
        $key     = uniqid('key-');
        $request = $this->getRequestObject();

        $this->assertSame(
            'default',
            $request->getQuery($key, null, 'default')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-12-01
     */
    public function testHttpRequestGetQueryFilter(): void
    {
        $key        = uniqid('key-');
        $value      = uniqid('VAL-');
        $_GET[$key] = '  ' . $value . '  ';

        $request = $this->getRequestObject();

        $this->assertSame(
            $value,
            $request->getQuery($key, 'trim')
        );

        $this->assertSame(
            strtolower($value),
            $request->getQuery($key, ['trim', 'lower'])
        );
    }
}
