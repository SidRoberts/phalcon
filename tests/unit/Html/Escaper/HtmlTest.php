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

namespace Phalcon\Tests\Unit\Html\Escaper;

use Phalcon\Html\Escaper;
use Phalcon\Tests\AbstractUnitTestCase;

final class HtmlTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testEscaperHtml(): void
    {
        $escaper = new Escaper();

        $this->assertSame(
            '&lt;h1&gt;&lt;/h1&gt;',
            $escaper->html('<h1></h1>')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testEscaperHtmlNull(): void
    {
        $escaper = new Escaper();

        $this->assertSame(
            '',
            $escaper->html(null)
        );

        $escaper = new Escaper();

        $this->assertSame(
            '0',
            $escaper->html('0')
        );
    }
}
