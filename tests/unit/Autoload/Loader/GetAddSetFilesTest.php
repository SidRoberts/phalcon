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

namespace Phalcon\Tests\Unit\Autoload\Loader;

use Phalcon\Autoload\Loader;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Autoload\Fake\LoaderTrait;

use function hash;

final class GetAddSetFilesTest extends AbstractUnitTestCase
{
    use LoaderTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAutoloaderLoaderGetAddSetFiles(): void
    {
        $loader = new Loader();

        $this->assertSame(
            [],
            $loader->getFiles()
        );

        $loader->setFiles(
            [
                'classOne.php',
                'classOne.php',
                'classOne.php',
            ]
        );

        $expected = ['classOne.php' => 'classOne.php'];

        $this->assertSame(
            $expected,
            $loader->getFiles()
        );

        /**
         * Clear
         */
        $loader->setFiles([]);

        $this->assertSame(
            [],
            $loader->getFiles()
        );

        $loader
            ->addFile('classOne.php')
            ->addFile('classTwo.php')
            ->addFile('classOne.php')
        ;

        $expected = [
            'classOne.php' => 'classOne.php',
            'classTwo.php' => 'classTwo.php',
        ];

        $this->assertSame(
            $expected,
            $loader->getFiles()
        );
    }
}
