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

use Phalcon\Support\Helper\Str\ReduceSlashes;
use Phalcon\Tests\AbstractUnitTestCase;

final class ReduceSlashesTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrReduceSlashes(): void
    {
        $object = new ReduceSlashes();

        $this->assertSame(
            'app/controllers/IndexController',
            $object('app/controllers//IndexController')
        );

        $this->assertSame(
            'https://foo/bar/baz/buz',
            $object('https://foo//bar/baz/buz')
        );

        $this->assertSame(
            'php://memory',
            $object('php://memory')
        );

        $this->assertSame(
            'http/https',
            $object('http//https')
        );
    }
}
