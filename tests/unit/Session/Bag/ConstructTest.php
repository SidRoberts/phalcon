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
use Phalcon\Session\ManagerInterface;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Traits\DiTrait;

final class ConstructTest extends AbstractUnitTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSessionBagConstruct(): void
    {
        $this->setNewFactoryDefault();
        $this->setDiService('sessionStream');

        /** @var ManagerInterface */
        $session = $this->container->get("session");

        $collection = new Bag($session, 'BagTest');

        $this->assertInstanceOf(Bag::class, $collection);
    }
}
