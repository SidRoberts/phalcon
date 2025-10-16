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

use function function_exists;

final class LoadFilesTest extends AbstractUnitTestCase
{
    use LoaderTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testAutoloaderLoaderLoadFiles(): void
    {
        $loader = new Loader();

        $this->assertFalse(
            function_exists('noClass2Foo')
        );

        $this->assertFalse(
            function_exists('noClass2Bar')
        );

        $loader
            ->addFile(
                supportDir('assets/Loader/Example/Functions/FunctionsNoClassTwo.php')
            )
            ->addFile(
                '/path/to/unknown/file'
            )
        ;

        $loader->loadFiles();

        $this->assertTrue(
            function_exists('noClass2Foo')
        );

        $this->assertTrue(
            function_exists('noClass2Bar')
        );
    }
}
