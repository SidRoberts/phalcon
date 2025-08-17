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

namespace Phalcon\Tests\Unit\Flash\Direct;

use Phalcon\Flash\Direct;
use Phalcon\Tests\AbstractUnitTestCase;

final class GetSetAutoescapeTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testFlashDirectGetSetAutoescape(): void
    {
        $flash = new Direct();

        $this->assertTrue(
            $flash->getAutoescape()
        );

        $actual = $flash->setAutoescape(false);

        $this->assertInstanceOf(Direct::class, $actual);

        $this->assertFalse(
            $flash->getAutoescape()
        );
    }
}
