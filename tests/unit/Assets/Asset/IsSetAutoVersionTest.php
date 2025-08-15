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
use Phalcon\Tests\AbstractUnitTestCase;

final class IsSetAutoVersionTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAssetsAssetIsSetAutoVersion(): void
    {
        $asset = new Asset('css', 'css/docs.css');

        $this->assertFalse(
            $asset->isAutoVersion()
        );

        $asset->setAutoVersion(true);

        $this->assertTrue(
            $asset->isAutoVersion()
        );
    }
}
