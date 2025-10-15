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

/**
 * Class SetTest extends AbstractUnitTestCase
 */
final class SetTest extends AbstractUnitTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionBagSet(): void
    {
        $this->setNewFactoryDefault();
        $this->setDiService('sessionStream');

        $session = $this->container->get("session");

        $collection = new Bag($session, 'BagTest');

        $collection->set('three', 'two');

        $this->assertEquals(
            'two',
            $collection->get('three')
        );

        $collection->three = 'Phalcon';

        $this->assertEquals(
            'Phalcon',
            $collection->get('three')
        );

        $collection->offsetSet('three', 123);

        $this->assertEquals(
            123,
            $collection->get('three')
        );

        $collection['three'] = true;

        $this->assertTrue(
            $collection->get('three')
        );
    }
}
