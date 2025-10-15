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

use function uniqid;

final class GetSetTest extends AbstractUnitTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionManagerGetSet(): void
    {
        $manager = new Manager();
        $files   = $this->newService('sessionStream');

        $manager->setAdapter($files);

        $this->assertNull(
            $manager->get('test')
        );

        $this->assertTrue(
            $manager->start()
        );

        $expected = 'myval';
        $manager->set('test', $expected);

        $this->assertEquals(
            $expected,
            $manager->get('test')
        );

        $this->assertTrue(
            $manager->has('test')
        );

        $this->assertEquals(
            $expected,
            $manager->get('test', null, true)
        );

        $this->assertFalse(
            $manager->has('test')
        );

        $name = uniqid();

        $this->assertEquals(
            $name,
            $manager->get('test', $name)
        );

        $manager->destroy();

        $this->assertFalse(
            $manager->exists()
        );
    }
}
