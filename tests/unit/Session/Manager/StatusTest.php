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

final class StatusTest extends AbstractUnitTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionManagerStatus(): void
    {
        $manager = new Manager();
        $files   = $this->newService('sessionStream');

        $manager->setAdapter($files);

        $this->assertEquals(
            $manager::SESSION_NONE,
            $manager->status()
        );

        $this->assertTrue(
            $manager->start()
        );

        $this->assertEquals(
            $manager::SESSION_ACTIVE,
            $manager->status()
        );

        $manager->destroy();

        $this->assertEquals(
            $manager::SESSION_NONE,
            $manager->status()
        );
    }
}
