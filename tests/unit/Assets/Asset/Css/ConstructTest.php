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

namespace Phalcon\Tests\Unit\Assets\Asset\Css;

use Phalcon\Assets\Asset\Css;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Assets\Fake\AssetsTrait;
use PHPUnit\Framework\Attributes\DataProvider;

final class ConstructTest extends AbstractUnitTestCase
{
    use AssetsTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetCssConstructAttributes(): void
    {
        $asset = new Css('css/docs.css');

        $this->assertSame(
            [],
            $asset->getAttributes()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetCssConstructAttributesSet(): void
    {
        $attributes = [
            'data' => 'phalcon',
        ];

        $asset = new Css(
            'css/docs.css',
            true,
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
    public function testAssetsAssetCssConstructFilter(): void
    {
        $asset = new Css('css/docs.css');

        $this->assertTrue(
            $asset->getFilter()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetCssConstructFilterSet(): void
    {
        $asset = new Css('css/docs.css', true, false);

        $this->assertFalse(
            $asset->getFilter()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('providerCss')]
    public function testAssetsAssetCssConstructLocal(
        string $path,
        bool $local
    ): void {
        $asset = new Css($path, $local);

        $this->assertSame(
            $local,
            $asset->isLocal()
        );
    }
}
