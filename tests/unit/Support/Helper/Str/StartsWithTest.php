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

use Phalcon\Support\Helper\Str\StartsWith;
use Phalcon\Tests\AbstractUnitTestCase;

final class StartsWithTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrStartsWith(): void
    {
        $object = new StartsWith();

        $this->assertTrue(
            $object('Hello', 'H')
        );

        $this->assertTrue(
            $object('Hello', 'He')
        );

        $this->assertTrue(
            $object('Hello', 'Hello')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrStartsWithCaseInsensitive(): void
    {
        $object = new StartsWith();

        $this->assertTrue(
            $object('Hello', 'h')
        );

        $this->assertTrue(
            $object('Hello', 'he')
        );

        $this->assertTrue(
            $object('Hello', 'hello')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrStartsWithCaseSensitive(): void
    {
        $object = new StartsWith();

        $this->assertTrue(
            $object('Hello', 'hello', true)
        );

        $this->assertFalse(
            $object('Hello', 'hello', false)
        );

        $this->assertFalse(
            $object('Hello', 'h', false)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrStartsWithEmpty(): void
    {
        $object = new StartsWith();

        $this->assertFalse(
            $object('', '')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrStartsWithEmptySearchString(): void
    {
        $object = new StartsWith();

        $this->assertFalse(
            $object('', 'hello')
        );
    }
}
