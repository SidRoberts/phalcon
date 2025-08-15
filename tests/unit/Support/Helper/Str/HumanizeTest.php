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

use Phalcon\Support\Helper\Str\Humanize;
use Phalcon\Tests\AbstractUnitTestCase;

final class HumanizeTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrHumanize(): void
    {
        $object = new Humanize();

        $this->assertSame(
            'start a horse',
            $object('start_a_horse')
        );

        $this->assertSame(
            'five cats',
            $object('five-cats')
        );

        $this->assertSame(
            'kittens are cats',
            $object('kittens-are_cats')
        );

        $this->assertSame(
            'Awesome Phalcon',
            $object(" \t Awesome-Phalcon \t ")
        );
    }
}
