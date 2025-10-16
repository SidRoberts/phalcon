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

namespace Phalcon\Tests\Unit\Assets\Asset\Js;

use Phalcon\Assets\Asset\Js;
use Phalcon\Assets\AssetInterface;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Assets\Fake\AssetsTrait;
use PHPUnit\Framework\Attributes\DataProvider;

final class ConstructTest extends AbstractUnitTestCase
{
    use AssetsTrait;

    /**
     * Tests Phalcon\Assets\Asset\Js :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-13
     */
    public function testAssetsAssetJsInstanceOfAssetInterface(): void
    {
        $asset = new Js('js/jquery.js');

        $this->assertInstanceOf(AssetInterface::class, $asset);
    }

    /**
     * Tests Phalcon\Assets\Asset\Js :: __construct() - attributes
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsConstructAttributes(): void
    {
        $asset = new Js('js/jquery.js');

        $this->assertSame(
            [],
            $asset->getAttributes()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsConstructAttributesSet(): void
    {
        $attributes = [
            'data' => 'phalcon',
        ];

        $asset = new Js(
            'js/jquery.js',
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
    public function testAssetsAssetJsConstructFilter(): void
    {
        $asset = new Js('js/jquery.js');

        $this->assertTrue(
            $asset->getFilter()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsConstructFilterSet(): void
    {
        $asset = new Js('js/jquery.js', true, false);

        $this->assertFalse(
            $asset->getFilter()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('providerJs')]
    public function testAssetsAssetJsConstructLocal(
        string $path,
        bool $local
    ): void {
        $asset = new Js($path, $local);

        $this->assertSame(
            $local,
            $asset->isLocal()
        );
    }
}
