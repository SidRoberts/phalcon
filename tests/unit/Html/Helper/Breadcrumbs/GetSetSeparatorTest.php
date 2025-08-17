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

namespace Phalcon\Tests\Unit\Html\Helper\Breadcrumbs;

use Phalcon\Html\Escaper;
use Phalcon\Html\Helper\Breadcrumbs;
use Phalcon\Tests\AbstractUnitTestCase;

final class GetSetSeparatorTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testHtmlHelperBreadcrumbsGetSetSeparator(): void
    {
        $escaper     = new Escaper();
        $breadcrumbs = new Breadcrumbs($escaper);

        $this->assertSame(
            '<li>/</li>',
            $breadcrumbs->getSeparator()
        );

        $separator = ' -:- ';

        $breadcrumbs->setSeparator($separator);

        $this->assertSame(
            $separator,
            $breadcrumbs->getSeparator()
        );
    }
}
