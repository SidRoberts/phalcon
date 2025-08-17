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

namespace Phalcon\Tests\Unit\Mvc\View\Simple;

use Phalcon\Mvc\View\Engine\Volt;
use Phalcon\Mvc\View\Exception;
use Phalcon\Mvc\View\Simple;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Objects\ChildObject;
use Phalcon\Tests\Support\Objects\ParentObject;
use Phalcon\Tests\Support\Traits\DiTrait;
use Phalcon\Tests\Unit\Mvc\Fake\ViewTrait;

use function dataDir;
use function ob_end_clean;
use function ob_get_level;
use function ob_start;
use function sprintf;
use function supportDir;

class RenderTest extends AbstractUnitTestCase
{
    use DiTrait;
    use ViewTrait;

    public function setUp(): void
    {
        $this->newDi();
        $this->setDiService('viewSimple');

        ob_start();
    }

    public function tearDown(): void
    {
        if (ob_get_level()) {
            ob_end_clean();
        }
    }

    public function testMvcViewRenderChildobject(): void
    {
        $this->safeDeleteFile(
            supportDir('assets/views/currentrender/subobject.volt.php')
        );

        /** @var Simple */
        $view = $this->container->get('viewSimple');

        $view->registerEngines(
            [
                '.volt' => Volt::class,
            ]
        );

        $child  = new ChildObject();
        $parent = new ParentObject($child);

        $view->setVar('parentObject', $parent);

        $this->assertEquals(
            'Value',
            $view->render('currentrender/subobject')
        );

        $this->safeDeleteFile(
            supportDir('assets/views/currentrender/subobject.volt.php')
        );
    }

    /**
     * @author Kamil Skowron <git@hedonsoftware.com>
     * @since  2014-05-28
     */
    public function testMvcViewSimpleRender(): void
    {
        /** @var Simple */
        $view = $this->container->get('viewSimple');

        $this->assertEquals(
            'here',
            $view->render('currentrender/other')
        );
    }

    /**
     * @author Kamil Skowron <git@hedonsoftware.com>
     * @since  2014-05-28
     */
    public function testRenderFilenameWithoutEngine(): void
    {
        $startLevel = ob_get_level();

        try {
            /** @var Simple */
            $view = $this->container->get('viewSimple');

            $view->setParamToView('name', 'FooBar');
            $view->render('unknown/view');
        } catch (Exception $ex) {
            // Drain any buffers opened by render() before it threw.
            while (ob_get_level() > $startLevel) {
                ob_end_clean();
            }

            $actual   = $ex->getMessage();
            $expected = sprintf(
                "View '%sunknown/view' was not found in the views directory",
                supportDir('assets/views/')
            );
            $this->assertSame(
                $expected,
                $ex->getMessage()
            );
        }
    }

    /**
     * @author Kamil Skowron <git@hedonsoftware.com>
     * @since  2014-05-28
     */
    public function testRenderMissingView(): void
    {
        $startLevel = ob_get_level();
        $view       = $this->container->get('viewSimple');

        try {
            $view->render('unknown/view');
            $this->fail('Expected View Exception was not thrown.');
        } catch (Exception $ex) {
            // Drain any buffers opened by render() before it threw.
            while (ob_get_level() > $startLevel) {
                ob_end_clean();
            }

            $expected = sprintf(
                "View '%sunknown/view' was not found in the views directory",
                supportDir('assets/views/')
            );
            $this->assertSame($expected, $ex->getMessage());
        }
    }

    /**
     * @author Kamil Skowron <git@hedonsoftware.com>
     * @since  2014-05-28
     */
    public function testRenderStandard(): void
    {
        /** @var Simple */
        $view = $this->container->get('viewSimple');

        $this->assertEquals(
            'We are here',
            $view->render('simple/index')
        );

        $this->assertEquals(
            'We are here',
            $view->getContent()
        );
    }

    /**
     * @author Kamil Skowron <git@hedonsoftware.com>
     * @since  2014-05-28
     */
    public function testRenderWithPartials(): void
    {
        /** @var Simple */
        $view = $this->container->get('viewSimple');

        $expectedParams = [
            'cool_var' => 'FooBar',
        ];

        $view->partial('partials/partial', $expectedParams);

        $this->assertEquals(
            'Hey, this is a partial, also FooBar',
            $view->getContent()
        );

        $view->setVars($expectedParams);
    }
}
