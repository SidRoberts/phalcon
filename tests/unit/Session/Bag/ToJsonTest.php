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
 * Class ToJsonTest extends AbstractUnitTestCase
 */
final class ToJsonTest extends AbstractUnitTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionBagToJson(): void
    {
        $this->setNewFactoryDefault();
        $this->setDiService('sessionStream');

        $data = [
            'one'   => 'two',
            'three' => 'four',
            'five'  => 'six',
        ];

        $session = $this->container->get("session");

        $collection = new Bag($session, 'BagTest');

        $collection->init($data);

        $this->assertEquals(
            json_encode($data),
            $collection->toJson()
        );

        $this->assertEquals(
            json_encode($data, JSON_PRETTY_PRINT),
            $collection->toJson(JSON_PRETTY_PRINT)
        );
    }
}
