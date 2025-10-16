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

namespace Phalcon\Tests\Unit\Translate\Interpolator\AssociativeArray;

use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Translate\Interpolator\AssociativeArray;
use Phalcon\Translate\Interpolator\InterpolatorInterface;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Translate\Interpolator\AssociativeArray :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-14
     */
    public function testTranslateInterpolatorAssociativeArrayInstanceOfInterpolatorInterface(): void
    {
        $interpolator = new AssociativeArray();

        $this->assertInstanceOf(InterpolatorInterface::class, $interpolator);
    }
}
