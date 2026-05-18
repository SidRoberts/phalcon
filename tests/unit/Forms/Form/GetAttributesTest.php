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

use Phalcon\Forms\Form;
use Phalcon\Html\Attributes\AttributesInterface;
use Phalcon\Tests\AbstractUnitTestCase;

use function method_exists;

final class GetAttributesTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-05-11
     */
    public function testFormsFormGetAttributes(): void
    {
        $form = new Form();

        $this->assertTrue(
            method_exists($form, 'getAttributes')
        );

        // Form implements AttributeInterface
        $this->assertInstanceOf(AttributesInterface::class, $form);


        // Empty attributes
        $this->assertCount(
            0,
            $form->getAttributes()
        );

        // Set an attribute
        $form->getAttributes()->set('attr', 'value');

        $this->assertCount(
            1,
            $form->getAttributes()
        );


        // Check has
        $this->assertTrue(
            $form->getAttributes()->has('attr')
        );

        $this->assertFalse(
            $form->getAttributes()->has('fake-attr')
        );

        $this->assertFalse(
            $form->getAttributes()->has('non exists attr')
        );

        // Render an attribute
        $expected = 'attr="value" ';
        $actual   = $form->getAttributes()->render();
        $this->assertEquals($expected, $actual);

        // Reset attributes
        $form->getAttributes()->clear();

        $this->assertCount(
            0,
            $form->getAttributes()
        );

        // Set multi attributes
        $form->getAttributes()->init(
            [
                'attr1' => 'value1',
                'attr2' => 'value2',
                'attr3' => 'value3',
            ]
        );

        $this->assertCount(
            3,
            $form->getAttributes()
        );

        // Render multi attributes
        $expected = 'attr1="value1" attr2="value2" attr3="value3" ';
        $actual   = $form->getAttributes()->render();
        $this->assertEquals($expected, $actual);

        // Get an attribute
        $this->assertEquals(
            'value2',
            $form->getAttributes()->get('attr2')
        );

        // Test action attribute
        $url = '/some-url';
        $form->setAction($url);

        $actual = $form->getAction();
        $this->assertEquals($url, $actual);

        $actual = $form->getAttributes()->get('action');
        $this->assertEquals($url, $actual);

        $expected = 'action="/some-url" attr1="value1" attr2="value2" attr3="value3" ';
        $actual   = $form->getAttributes()->render();
        $this->assertEquals($expected, $actual);

        // Remove an attribute
        $form->getAttributes()->remove('attr2');

        $this->assertFalse(
            $form->getAttributes()->has('attr2')
        );

        $this->assertCount(
            3,
            $form->getAttributes()
        );

        // Delete a nonexistent attribute
        $form->getAttributes()->remove('attr2');

        $this->assertFalse(
            $form->getAttributes()->has('attr2')
        );

        // Render multi attributes again
        $expected = 'action="/some-url" attr1="value1" attr3="value3" ';
        $actual   = $form->getAttributes()->render();
        $this->assertEquals($expected, $actual);

        // Reset attributes
        $form->getAttributes()->clear();

        $this->assertCount(
            0,
            $form->getAttributes()
        );

        // Exception on non exists attribute
        $this->assertNull(
            $form->getAttributes()->get('non exists')
        );
    }
}
