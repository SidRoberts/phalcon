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

namespace Phalcon\Tests\Unit\Session\Bag;

use Phalcon\Session\Bag;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Traits\DiTrait;
use Phalcon\Tests\Unit\Session\Fake\FakeInjectableBag;

final class InjectableTest extends AbstractUnitTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testSessionBagClear(): void
    {
        $this->setNewFactoryDefault();
        $this->setDiService('sessionStream');

        /**
         * Set a session bag
         */
        $data       = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $session = $this->container->get("session");

        $collection = new Bag($session, 'BagTest');

        $collection->init($data);

        /**
         * Store it in the container
         */
        $this->container->set('sessionBag', $collection);

        /**
         * Create the injectable component - this can be a controller for
         * instance, and set the container
         */
        $injectable = new FakeInjectableBag();

        $injectable->setDI($this->container);

        /**
         * Get the `persistent` property
         */
        $sessionBag = $injectable->persistent;

        $this->assertInstanceOf(Bag::class, $sessionBag);

        $this->assertEquals(
            $data,
            $sessionBag->toArray()
        );
    }
}
