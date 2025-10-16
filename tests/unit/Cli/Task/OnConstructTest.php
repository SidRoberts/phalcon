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

namespace Phalcon\Tests\Unit\Cli\Task;

use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Tasks\OnConstructTask;

final class OnConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Cli\Task :: onConstruct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCliTaskOnConstruct(): void
    {
        $task = new OnConstructTask();

        $this->assertTrue($task->onConstructExecuted);
    }
}
