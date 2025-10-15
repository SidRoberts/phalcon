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

final class HasTest extends AbstractUnitTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionManagerHas(): void
    {
        $manager = new Manager();

        $files = $this->newService('sessionStream');

        $manager->setAdapter($files);

        $this->assertFalse(
            $manager->has('test')
        );

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

        $manager->destroy();

        $this->assertFalse(
            $manager->exists()
        );
    }
}
