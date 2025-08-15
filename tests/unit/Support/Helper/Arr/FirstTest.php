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

use Phalcon\Support\Helper\Arr\First;
use Phalcon\Tests\AbstractUnitTestCase;

use function strlen;

final class FirstTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperArrFirst(): void
    {
        $object = new First();

        $collection = [
            'Phalcon',
            'Framework',
        ];

        $this->assertSame(
            'Phalcon',
            $object($collection)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperArrFirstFunction(): void
    {
        $object = new First();

        $collection = [
            'Phalcon',
            'Framework',
        ];

        $actual = $object(
            $collection,
            function (string $element): bool {
                return strlen($element) > 8;
            },
        );

        $this->assertSame('Framework', $actual);
    }
}
