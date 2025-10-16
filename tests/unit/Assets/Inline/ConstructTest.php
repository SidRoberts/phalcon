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

namespace Phalcon\Tests\Unit\Assets\Inline;

use Phalcon\Assets\AssetInterface;
use Phalcon\Assets\Inline;
use Phalcon\Tests\AbstractUnitTestCase;

final class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Assets\Inline :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-13
     */
    public function testAssetsInlineCssInstanceOfAssetInterface(): void
    {
        $asset = new Inline('css', 'p {color: #000099}');

        $this->assertInstanceOf(AssetInterface::class, $asset);
    }

    /**
     * Tests Phalcon\Assets\Asset :: __construct() - css
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineConstructCss(): void
    {
        $content = 'p {color: #000099}';
        $asset   = new Inline('css', $content);

        $this->assertSame(
            'css',
            $asset->getType()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineConstructCssAttributes(): void
    {
        $content = 'p {color: #000099}';
        $asset   = new Inline('css', $content);

        $this->assertSame(
            [],
            $asset->getAttributes()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineConstructCssAttributesSet(): void
    {
        $content    = 'p {color: #000099}';
        $attributes = [
            'data' => 'phalcon',
        ];

        $asset = new Inline(
            'css',
            $content,
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
    public function testAssetsInlineConstructCssFilter(): void
    {
        $content = 'p {color: #000099}';
        $asset   = new Inline('css', $content);

        $this->assertTrue(
            $asset->getFilter()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineConstructCssFilterSet(): void
    {
        $content = 'p {color: #000099}';
        $asset   = new Inline('css', $content, false);

        $this->assertFalse(
            $asset->getFilter()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineConstructJs(): void
    {
        $content = '<script>alert("Hello");</script>';
        $asset   = new Inline('js', $content);

        $this->assertSame(
            'js',
            $asset->getType()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineConstructJsAttributes(): void
    {
        $content = '<script>alert("Hello");</script>';
        $asset   = new Inline('js', $content);

        $this->assertSame(
            [],
            $asset->getAttributes()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineConstructJsAttributesSet(): void
    {
        $content    = '<script>alert("Hello");</script>';
        $attributes = [
            'data' => 'phalcon',
        ];
        $asset      = new Inline('js', $content, true, $attributes);

        $this->assertSame(
            $attributes,
            $asset->getAttributes()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineConstructJsFilter(): void
    {
        $content = '<script>alert("Hello");</script>';
        $asset   = new Inline('js', $content);

        $this->assertTrue(
            $asset->getFilter()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsInlineConstructJsFilterSet(): void
    {
        $content = '<script>alert("Hello");</script>';
        $asset   = new Inline('js', $content, false);

        $this->assertFalse(
            $asset->getFilter()
        );
    }
}
