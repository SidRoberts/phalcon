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
use Phalcon\Tests\AbstractUnitTestCase;

final class RemoveTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2024-01-01
     */
    public function testFormsFormRemoveFound(): void
    {
        $form = new Form();

        $form->add(new Text('name'));
        $form->add(new Text('email'));

        $this->assertTrue(
            $form->remove('name')
        );

        $this->assertFalse(
            $form->has('name')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2024-01-01
     */
    public function testFormsFormRemoveNotFound(): void
    {
        $form = new Form();

        $form->add(new Text('name'));

        $this->assertFalse(
            $form->remove('nonexistent')
        );
    }
}
