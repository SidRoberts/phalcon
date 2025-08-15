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

namespace Phalcon\Tests\Unit\Events\Event;

use Phalcon\Events\Event;
use Phalcon\Tests\AbstractUnitTestCase;

final class GetSetDataTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-10-06
     */
    public function testEventsEventGetSetData(): void
    {
        $event = new Event('some-type:beforeSome', $this);

        $this->assertNull(
            $event->getData()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-10-06
     */
    public function testEventsEventGetSetDataConstructor(): void
    {
        $data     = [1, 2, 3];
        $event    = new Event('some-type:beforeSome', $this, $data);

        $this->assertSame(
            $data,
            $event->getData()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-10-06
     */
    public function testEventsEventGetSetDataEmpty(): void
    {
        $data  = [1, 2, 3];
        $event = new Event('some-type:beforeSome', $this, $data);

        $this->assertSame(
            $data,
            $event->getData()
        );

        $event->setData();

        $this->assertNull(
            $event->getData()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-10-06
     */
    public function testEventsEventGetSetDataOverwrite(): void
    {
        $data  = [1, 2, 3];
        $event = new Event('some-type:beforeSome', $this, $data);

        $this->assertSame(
            $data,
            $event->getData()
        );

        $newData = [4, 5, 6];
        $event->setData($newData);

        $this->assertSame(
            $newData,
            $event->getData()
        );
    }
}
