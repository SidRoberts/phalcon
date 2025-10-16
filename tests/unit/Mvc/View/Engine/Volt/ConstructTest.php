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

namespace Phalcon\Tests\Unit\Mvc\View\Engine\Volt;

use Phalcon\Mvc\View;
use Phalcon\Mvc\View\Engine\EngineInterface;
use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Tests\AbstractUnitTestCase;

class ConstructTest extends AbstractUnitTestCase
{
    /**
     * Tests Phalcon\Mvc\View\Engine\Volt :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-13
     */
    public function testMvcViewEngineVoltInstanceOfEngineInterface(): void
    {
        $view   = new View();
        $engine = new Volt($view);

        $this->assertInstanceOf(EngineInterface::class, $engine);
    }
}
