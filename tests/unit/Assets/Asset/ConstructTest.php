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

namespace Phalcon\Tests\Unit\Assets\Asset;

use Phalcon\Assets\Asset;
use Phalcon\Assets\AssetInterface;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Assets\Fake\AssetsTrait;
use PHPUnit\Framework\Attributes\DataProvider;

final class ConstructTest extends AbstractUnitTestCase
{
    use AssetsTrait;

    /**
     * Tests Phalcon\Assets\Asset :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-13
     */
    public function testAssetsAssetInstanceOfAssetInterface(): void
    {
        $asset = new Asset('css', 'css/docs.css');

        $this->assertInstanceOf(AssetInterface::class, $asset);
    }

    /**
     * Tests Phalcon\Assets\Asset :: __construct() - attributes
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('providerAssets')]
    public function testAssetsAssetConstructAttributes(
        string $type,
        string $path
    ): void {
        $asset = new Asset($type, $path);

        $this->assertSame(
            [],
            $asset->getAttributes()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('providerAssets')]
    public function testAssetsAssetConstructAttributesSet(
        string $type,
        string $path
    ): void {
        $attributes = [
            'data' => 'phalcon',
        ];

        $asset = new Asset(
            $type,
            $path,
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
    #[DataProvider('providerAssets')]
    public function testAssetsAssetConstructFilter(
        string $type,
        string $path
    ): void {
        $asset = new Asset($type, $path);

        $this->assertTrue(
            $asset->getFilter()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('providerAssets')]
    public function testAssetsAssetConstructFilterSet(
        string $type,
        string $path
    ): void {
        $asset = new Asset(
            $type,
            $path,
            true,
            false
        );

        $this->assertFalse(
            $asset->getFilter()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('providerAssets')]
    public function testAssetsAssetConstructLocal(
        string $type,
        string $path
    ): void {
        $asset = new Asset($type, $path);

        $this->assertTrue(
            $asset->isLocal()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('providerAssets')]
    public function testAssetsAssetConstructRemote(
        string $type,
        string $path
    ): void {
        $asset = new Asset($type, $path, false);

        $this->assertFalse(
            $asset->isLocal()
        );
    }
}
