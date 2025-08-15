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

namespace Phalcon\Tests\Unit\Support\Helper\Str;

use Phalcon\Support\Helper\Str\DirFromFile;
use Phalcon\Tests\AbstractUnitTestCase;

final class DirFromFileTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrFolderFromFile(): void
    {
        $object = new DirFromFile();

        $this->assertSame(
            'ab/cd/ef/12/3/',
            $object('abcdef12345.jpg')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperStrFolderFromFileEmptyString(): void
    {
        $object = new DirFromFile();

        $this->assertSame(
            '/',
            $object('')
        );
    }
}
