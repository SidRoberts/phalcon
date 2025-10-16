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

namespace Phalcon\Tests\Unit\Assets\Inline\Css;

use Phalcon\Assets\AssetInterface;
use Phalcon\Assets\Inline\Css;
use Phalcon\Tests\AbstractUnitTestCase;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-13
     */
    public function testAssetsInlineCssInstanceOfAssetInterface(): void
    {
        $asset = new Css('p {color: #000099}');

        $this->assertInstanceOf(AssetInterface::class, $asset);
    }

    /**
     * Tests Phalcon\Assets\Inline\Css :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineCssConstruct(): void
    {
        $asset = new Css('p {color: #000099}');

        $this->assertSame(
            'css',
            $asset->getType()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineCssConstructAttributes(): void
    {
        $asset = new Css('p {color: #000099}');

        $expected = [
            'type' => 'text/css',
        ];

        $this->assertSame(
            $expected,
            $asset->getAttributes()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineCssConstructAttributesSet(): void
    {
        $attributes = [
            'data' => 'phalcon',
        ];

        $asset = new Css(
            'p {color: #000099}',
            true,
            $attributes
        );

        $this->assertSame(
            $attributes,
            $asset->getAttributes()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineCssConstructFilter(): void
    {
        $asset = new Css('p {color: #000099}');

        $this->assertTrue(
            $asset->getFilter()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineCssConstructFilterSet(): void
    {
        $asset = new Css('p {color: #000099}', false);

        $this->assertFalse(
            $asset->getFilter()
        );
    }
}
