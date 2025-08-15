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

use Phalcon\Support\Helper\Arr\IsUnique;
use Phalcon\Tests\AbstractUnitTestCase;

final class IsUniqueTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperArrIsUnique(): void
    {
        $object = new IsUnique();

        $collection = [
            'Phalcon',
            'Framework',
        ];

        $this->assertTrue(
            $object($collection)
        );

        $collection = [
            'Phalcon',
            'Framework',
            'Phalcon',
        ];

        $this->assertFalse(
            $object($collection)
        );
    }
}
