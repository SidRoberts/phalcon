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

namespace Phalcon\Tests\Unit\Mvc\View\Engine\Volt\Extensions;

use Phalcon\Mvc\View\Engine\Volt\Compiler;
use Phalcon\Tests\AbstractUnitTestCase;

class PhpTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-12-28
     */
    public function testMvcViewEngineVoltExtensionsDoesNotExist(): void
    {
        $compiler = new Compiler();

        $source = '{{ myfunction("a") }}';

        $this->assertSame(
            "<?= \$this->callMacro('myfunction', ['a']) ?>",
            $compiler->compileString($source)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-12-28
     */
    public function testMvcViewEngineVoltExtensionsPhp(): void
    {
        $compiler = new Compiler();

        $source = '{{ str_replace("a", "b", "aabb") }}';

        $this->assertSame(
            "<?= \$this->callMacro('str_replace', ['a', 'b', 'aabb']) ?>",
            $compiler->compileString($source)
        );
    }
}
