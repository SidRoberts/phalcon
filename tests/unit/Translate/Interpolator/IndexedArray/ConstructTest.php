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

namespace Phalcon\Tests\Unit\Translate\Interpolator\IndexedArray;

use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Translate\Interpolator\IndexedArray;
use Phalcon\Translate\Interpolator\InterpolatorInterface;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Translate\Interpolator\IndexedArray :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-14
     */
    public function testTranslateInterpolatorIndexedArrayInstanceOfInterpolatorInterface(): void
    {
        $interpolator = new IndexedArray();

        $this->assertInstanceOf(InterpolatorInterface::class, $interpolator);
    }
}
