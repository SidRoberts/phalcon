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

namespace Phalcon\Tests\Unit\Filter\Validation\Validator\Alnum;

use Phalcon\Filter\Validation;
use Phalcon\Filter\Validation\Exception;
use Phalcon\Filter\Validation\Validator\Alnum;
use Phalcon\Tests\AbstractUnitTestCase;
use stdClass;

final class ValidateTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2023-08-03
     */
    public function testFilterValidationValidatorAlnumValidateEmpty(): void
    {
        $validation = new Validation();
        $validator  = new Alnum(['allowEmpty' => true,]);
        $validation->add('name', $validator);

        $entity       = new stdClass();
        $entity->name = '';

        $validation->bind($entity, []);

        $this->assertTrue(
            $validator->validate($validation, 'name')
        );
    }

    /**
     * @author Wojciech Ślawski <jurigag@gmail.com>
     * @since  2016-06-05
     */
    public function testFilterValidationValidatorAlnumValidateMultipleField(): void
    {
        $validation = new Validation();

        $validationMessages = [
            'name' => 'Name must be alnum',
            'type' => 'Type must be alnum',
        ];

        $al = new Alnum(
            [
                'message' => $validationMessages,
            ]
        );

        $validation->add(
            [
                'name',
                'type',
            ],
            $al
        );

        $messages = $validation->validate(
            [
                'name' => 'SomeValue123',
                'type' => 'SomeValue123',
            ]
        );

        $this->assertSame(
            0,
            $messages->count()
        );

        $messages = $validation->validate(
            [
                'name' => 'SomeValue123!@#',
                'type' => 'SomeValue123',
            ]
        );

        $this->assertSame(
            1,
            $messages->count()
        );

        $this->assertSame(
            $validationMessages['name'],
            $messages->offsetGet(0)->getMessage()
        );

        $messages = $validation->validate(
            [
                'name' => 'SomeValue123!@#',
                'type' => 'SomeValue123!@#',
            ]
        );

        $this->assertSame(
            2,
            $messages->count()
        );

        $this->assertSame(
            $validationMessages['name'],
            $messages->offsetGet(0)->getMessage()
        );

        $this->assertSame(
            $validationMessages['type'],
            $messages->offsetGet(1)->getMessage()
        );
    }

    /**
     * @author Wojciech Ślawski <jurigag@gmail.com>
     * @since  2016-06-05
     */
    public function testFilterValidationValidatorAlnumValidateSingleField(): void
    {
        $validation = new Validation();
        $validation->add('name', new Alnum());

        $messages = $validation->validate(
            [
                'name' => 'SomeValue123',
            ]
        );

        $this->assertSame(
            0,
            $messages->count()
        );

        $messages = $validation->validate(
            [
                'name' => 'SomeValue123!@#',
            ]
        );

        $this->assertSame(
            1,
            $messages->count()
        );
    }
}
