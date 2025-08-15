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

use Phalcon\Support\Helper\Str\EndsWith;
use Phalcon\Tests\AbstractUnitTestCase;

final class EndsWithTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrEndsWith(): void
    {
        $object = new EndsWith();

        $this->assertTrue(
            $object('Hello', 'o')
        );

        $this->assertTrue(
            $object('Hello', 'lo')
        );

        $this->assertTrue(
            $object('Hello', 'Hello')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrEndsWithCaseInsensitive(): void
    {
        $object = new EndsWith();

        $this->assertTrue(
            $object('Hello', 'O')
        );

        $this->assertTrue(
            $object('Hello', 'LO')
        );

        $this->assertTrue(
            $object('Hello', 'hello')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrEndsWithCaseSensitive(): void
    {
        $object = new EndsWith();

        $this->assertTrue(
            $object('Hello', 'hello', true)
        );

        $this->assertFalse(
            $object('Hello', 'hello', false)
        );

        $this->assertFalse(
            $object('Hello', 'O', false)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrEndsWithEmpty(): void
    {
        $object = new EndsWith();

        $this->assertFalse(
            $object('', '')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrEndsWithEmptySearchString(): void
    {
        $object = new EndsWith();

        $this->assertFalse(
            $object('', 'hello')
        );
    }
}
