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

namespace Phalcon\Tests\Unit\Support\Helper\Arr;

use Phalcon\Support\Helper\Arr\Set;
use Phalcon\Tests\AbstractUnitTestCase;

final class SetTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperArrSetNoIndex(): void
    {
        $object = new Set();
        $collection = [];

        $expected = [
            0 => 'Phalcon',
        ];

        $this->assertSame(
            $expected,
            $object($collection, 'Phalcon')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperArrSetNumeric(): void
    {
        $object = new Set();
        $collection = [];

        $expected = [
            1 => 'Phalcon',
        ];

        $this->assertSame(
            $expected,
            $object($collection, 'Phalcon', 1)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperArrSetOverwride(): void
    {
        $object = new Set();
        $collection = [
            1 => 'Phalcon',
        ];

        $expected = [
            1 => 'Framework',
        ];

        $this->assertSame(
            $expected,
            $object($collection, 'Framework', 1)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperArrSetString(): void
    {
        $object = new Set();
        $collection = [];

        $expected = [
            'suffix' => 'Framework',
        ];

        $this->assertSame(
            $expected,
            $object($collection, 'Framework', 'suffix')
        );
    }
}
