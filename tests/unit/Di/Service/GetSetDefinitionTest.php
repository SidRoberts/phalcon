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

namespace Phalcon\Tests\Unit\Di\Service;

use Phalcon\Di\Service;
use Phalcon\Html\Escaper;
use Phalcon\Support\Collection;
use Phalcon\Tests\AbstractUnitTestCase;

final class GetSetDefinitionTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceGetSetDefinition(): void
    {
        $service = new Service(Escaper::class, false);

        $actual = $service->getDefinition();
        $this->assertSame(Escaper::class, $actual);

        $service->setDefinition(Collection::class);

        $actual = $service->getDefinition();
        $this->assertSame(Collection::class, $actual);
    }
}
