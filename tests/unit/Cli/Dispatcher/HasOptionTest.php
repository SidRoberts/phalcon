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

namespace Phalcon\Tests\Unit\Cli\Dispatcher;

use Phalcon\Cli\Dispatcher;
use Phalcon\Tests\AbstractUnitTestCase;

final class HasOptionTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCliDispatcherHasOption(): void
    {
        $dispatcher = new Dispatcher();
        $optionName = "Phalcon";

        $this->assertFalse(
            $dispatcher->hasOption($optionName)
        );

        $dispatcher->setOptions([$optionName => "value"]);

        $this->assertTrue(
            $dispatcher->hasOption($optionName)
        );

        // Options should be case-sensitive
        $this->assertFalse(
            $dispatcher->hasOption(strtolower($optionName))
        );
    }
}
