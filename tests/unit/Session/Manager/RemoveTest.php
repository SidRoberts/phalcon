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

namespace Phalcon\Tests\Unit\Session\Manager;

use Phalcon\Session\Manager;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Traits\DiTrait;
use PHPUnit\Framework\Attributes\BackupGlobals;

final class RemoveTest extends AbstractUnitTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[BackupGlobals(true)]
    public function testSessionManagerRemove(): void
    {
        $_SESSION = [];

        $manager = new Manager();
        $files   = $this->newService('sessionStream');

        $manager->setAdapter($files);

        $this->assertTrue(
            $manager->start()
        );

        $this->assertFalse(
            $manager->has('test')
        );

        $manager->set('test', 'myval');

        $this->assertTrue(
            $manager->has('test')
        );

        $manager->remove('test');

        $this->assertFalse(
            $manager->has('test')
        );

        $manager->destroy();

        $this->assertFalse(
            $manager->exists()
        );
    }
}
