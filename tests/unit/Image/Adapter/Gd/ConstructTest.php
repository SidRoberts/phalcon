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

namespace Phalcon\Tests\Unit\Image\Adapter\Gd;

use Phalcon\Image\Adapter\AdapterInterface;
use Phalcon\Image\Adapter\Gd;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Image\Fake\GdTrait;

use function supportDir;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Image\Adapter\Gd :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-13
     */
    public function testImageAdapterGdInstanceOfAdapterInterface(): void
    {
        $gd = new Gd(
            supportDir('assets/images/example-jpg.jpg')
        );

        $this->assertInstanceOf(AdapterInterface::class, $gd);
    }
}
