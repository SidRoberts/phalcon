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

use Phalcon\Support\Helper\Arr\FirstKey;
use Phalcon\Tests\AbstractUnitTestCase;

final class FirstKeyTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperArrFirstKey(): void
    {
        $object = new FirstKey();

        $collection = [
            1 => 'Phalcon',
            3 => 'Framework',
        ];

        $this->assertSame(
            1,
            $object($collection)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperArrFirstKeyFunction(): void
    {
        $object = new FirstKey();

        $collection = [
            1 => 'Phalcon',
            3 => 'Framework',
        ];

        $actual = $object(
            $collection,
            function ($element): bool {
                return strlen($element) > 8;
            },
        );

        $this->assertSame(3, $actual);
    }
}
