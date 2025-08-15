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

namespace Phalcon\Tests\Unit\Events\Manager;

use Phalcon\Events\Manager;
use Phalcon\Tests\AbstractUnitTestCase;

final class EnablePrioritiesTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testEventsManagerEnablePriorities(): void
    {
        $manager = new Manager();

        $this->assertFalse(
            $manager->arePrioritiesEnabled()
        );

        $manager->enablePriorities(true);

        $this->assertTrue(
            $manager->arePrioritiesEnabled()
        );

        $manager->enablePriorities(false);

        $this->assertFalse(
            $manager->arePrioritiesEnabled()
        );
    }
}
