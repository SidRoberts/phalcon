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

use Phalcon\Support\Helper\Arr\SliceLeft;
use Phalcon\Tests\AbstractUnitTestCase;

final class SliceLeftTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperArrSliceLeft(): void
    {
        $object = new SliceLeft();

        $collection = [
            'Phalcon',
            'Framework',
            'for',
            'PHP',
        ];

        $expected = [
            'Phalcon',
        ];

        $this->assertSame(
            $expected,
            $object($collection, 1)
        );

        $expected = [
            'Phalcon',
            'Framework',
            'for',
        ];

        $this->assertSame(
            $expected,
            $object($collection, 3)
        );
    }
}
