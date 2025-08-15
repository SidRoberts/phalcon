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

namespace Phalcon\Tests\Unit\Assets\Asset\Js;

use Phalcon\Assets\Asset\Js;
use Phalcon\Tests\AbstractUnitTestCase;

use function supportDir;

final class GetRealSourcePathTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsGetRealSourcePathLocal(): void
    {
        if (PHP_OS_FAMILY === 'Windows') {
            $this->markTestSkipped('Need to fix Windows new lines...');
        }

        $file  = supportDir('assets/assets/jquery.js');
        $asset = new Js($file);

        $this->assertSame(
            $file,
            $asset->getRealSourcePath()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsGetRealSourcePathLocalDoesNotExist(): void
    {
        $asset = new Js('js/jquery.js');

        $this->assertEmpty(
            $asset->getRealSourcePath()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetJsGetRealSourcePathRemote(): void
    {
        $path  = 'https://phalcon.ld/js/jquery.js';
        $asset = new Js($path, false);

        $this->assertSame(
            $path,
            $asset->getRealSourcePath()
        );
    }
}
