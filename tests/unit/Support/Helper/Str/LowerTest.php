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

namespace Phalcon\Tests\Unit\Support\Helper\Str;

use Phalcon\Support\Helper\Str\Lower;
use Phalcon\Tests\AbstractUnitTestCase;

final class LowerTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrLower(): void
    {
        $object = new Lower();

        $this->assertSame(
            'hello',
            $object('hello')
        );

        $this->assertSame(
            'hello',
            $object('HELLO')
        );

        $this->assertSame(
            '1234',
            $object('1234')
        );
    }

    /**
     * @author Stanislav Kiryukhin <korsar.zn@gmail.com>
     * @since  2015-05-06
     */
    public function testSupportHelperStrLowerMultiBytesEncoding(): void
    {
        $object = new Lower();

        $this->assertSame(
            'привет мир!',
            $object('привет мир!')
        );

        $this->assertSame(
            'привет мир!',
            $object('ПриВЕт Мир!')
        );

        $this->assertSame(
            'привет мир!',
            $object('ПРИВЕТ МИР!')
        );

        $this->assertSame(
            'männer',
            $object('männer')
        );

        $this->assertSame(
            'männer',
            $object('mÄnnER')
        );

        $this->assertSame(
            'männer',
            $object('MÄNNER')
        );
    }
}
