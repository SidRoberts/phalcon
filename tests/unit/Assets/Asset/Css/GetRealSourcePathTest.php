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

namespace Phalcon\Tests\Unit\Assets\Asset\Css;

use Phalcon\Assets\Asset\Css;
use Phalcon\Tests\AbstractUnitTestCase;

final class GetRealSourcePathTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetCssGetRealSourcePathLocal(): void
    {
        $asset = new Css('css/docs.css');

        $this->assertEmpty(
            $asset->getRealSourcePath()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetCssGetRealSourcePathRemote(): void
    {
        $path  = 'https://phalcon.ld/css/docs.css';
        $asset = new Css($path, false);

        $this->assertSame(
            $path,
            $asset->getRealSourcePath()
        );
    }
}
