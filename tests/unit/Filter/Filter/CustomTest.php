<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalcon.io>
 *
 * For the full copyright and license information, please view the
 * LICENSE.txt file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Filter\Filter;

use Phalcon\Filter\Filter;
use Phalcon\Tests\Support\Filter\Sanitize\IPv4;
use Phalcon\Tests\AbstractUnitTestCase;

final class CustomTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testFilterFilterCustomHas(): void
    {
        $filter = new Filter(
            [
                'ipv4' => IPv4::class,
            ]
        );

        $this->assertTrue(
            $filter->has('ipv4')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testFilterFilterCustomSanitizer(): void
    {
        $filter = new Filter(
            [
                'ipv4' => IPv4::class,
            ]
        );

        /** @var IPv4 $sanitizer */
        $sanitizer = $filter->get('ipv4');

        $this->assertInstanceOf(IPv4::class, $sanitizer);
        $expected = '127.0.0.1';
        $actual   = $sanitizer('127.0.0.1');
        $this->assertSame($expected, $actual);
        $this->assertFalse($sanitizer('127.0.0'));
    }
}
