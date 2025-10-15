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

namespace Phalcon\Tests\Unit\Support\Debug\Dump;

use Phalcon\Support\Debug\Dump;
use Phalcon\Tests\AbstractUnitTestCase;

final class VariableTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function debugDumpVariableName(): void
    {
        $test = 'value';
        $dump = new Dump();

        $expected = trim(
            file_get_contents(
                supportDir('assets/Support/Dump/variable_name_output.txt'),
            ),
        );

        $this->assertSame(
            $expected,
            $dump->variable($test, 'super')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportDebugDumpVariable(): void
    {
        $test = 'value';
        $dump = new Dump();

        $expected = trim(
            file_get_contents(
                supportDir('assets/Support/Dump/variable_output.txt'),
            ),
        );

        $this->assertSame(
            $expected,
            $dump->variable($test)
        );
    }
}
