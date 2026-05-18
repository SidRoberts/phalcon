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

namespace Phalcon\Tests\Unit\Forms\Form;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Form;
use Phalcon\Html\Escaper;
use Phalcon\Html\Helper\Doctype;
use Phalcon\Html\TagFactory;
use Phalcon\Tests\AbstractUnitTestCase;
use stdClass;

final class RenderTest extends AbstractUnitTestCase
{
    /**
     * @issue  https://github.com/phalcon/cphalcon/issues/1190
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-07-17
     */
    public function testFormsFormRenderEscaped(): void
    {
        $object = new stdClass();
        $object->title = 'Hello "world!"';

        $form = new Form($object);

        /**
         * Make them all XHTML
         */
        $factory = new TagFactory(new Escaper());
        $doctype = $factory->newInstance('doctype');
        $doctype(Doctype::XHTML5);

        $form->setTagFactory($factory);

        $element = new Text("title");

        $form->add($element);

        $this->assertSame(
            '<input type="text" id="title" name="title" value="Hello &quot;world!&quot;" />',
            $form->render('title')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-07-17
     */
    public function testFormsFormRenderIndirect(): void
    {
        $form = new Form();

        /**
         * Make them all XHTML
         */
        $factory = new TagFactory(new Escaper());
        $doctype = $factory->newInstance('doctype');
        $doctype(Doctype::XHTML5);

        $form->setTagFactory($factory);

        $element = new Text("name");

        $form->add($element);

        $this->assertSame(
            '<input type="text" id="name" name="name" />',
            $form->render('name')
        );


        $actual = $form->render(
            'name',
            [
                'class' => 'big-input',
            ]
        );
        $this->assertSame(
            '<input type="text" id="name" name="name" class="big-input" />',
            $actual
        );
    }

    /**
     * @issue  https://github.com/phalcon/cphalcon/issues/10398
     * @author Phalcon Team <team@phalcon.io>
     * @since  2016-07-17
     */
    public function testFormsFormRenderMethods(): void
    {
        /**
         * Make them all XHTML
         */
        $factory = new TagFactory(new Escaper());
        $doctype = $factory->newInstance('doctype');
        $doctype(Doctype::XHTML5);

        $names      = [
            'validation',
            'action',
            'useroption',
            'useroptions',
            'entity',
            'elements',
            'messages',
            'messagesfor',
            'label',
            'value',
            'di',
            'eventsmanager',
        ];

        foreach ($names as $name) {
            $form = new Form();

            $form->setTagFactory($factory);

            $element = new Text($name);

            $this->assertEquals(
                $name,
                $element->getName()
            );

            $form->add($element);

            $expected = sprintf(
                '<input type="text" id="%s" name="%s" />',
                $name,
                $name
            );
            $this->assertSame($expected, $form->render($name));

            $this->assertNull(
                $form->getValue($name)
            );
        }
    }
}
