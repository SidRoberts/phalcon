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

namespace Phalcon\Tests\Unit\Filter\Validation\Validator\Email;

use Phalcon\Filter\Validation;
use Phalcon\Filter\Validation\Exception;
use Phalcon\Filter\Validation\Validator\Email;
use Phalcon\Messages\Message;
use Phalcon\Messages\Messages;
use Phalcon\Tests\AbstractUnitTestCase;
use stdClass;

final class ValidateTest extends AbstractUnitTestCase
{
    /**
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-08-19
     */
    public function testFilterValidationValidatorEmailValidEmail()
    {
        $validation = new Validation();

        $validation->add('email', new Validation\Validator\Email());

        $this->assertEmpty(
            $validation->validate(['email' => 'test@example.com'])
        );
    }

    /**
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-08-19
     */
    public function testFilterValidationValidatorEmailInvalidEmail()
    {
        $validation = new Validation();

        $validation->add('email', new Validation\Validator\Email());

        $this->assertNotEmpty(
            $validation->validate(['email' => 'test@-example.com'])
        );
    }

    /**
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-08-19
     */
    public function testFilterValidationValidatorEmailWithoutUTF8Success()
    {
        $validation = new Validation();

        $validation->add('email', new Validation\Validator\Email());

        $this->assertEmpty(
            $validation->validate(['email' => 'test@example.com'])
        );
    }

    /**
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-08-19
     */
    public function testFilterValidationValidatorEmailAllowEmptyFails()
    {
        $validation = new Validation();

        $validation->add('email', new Validation\Validator\Email());

        $this->assertNotEmpty(
            $validation->validate(['email' => ''])
        );
    }

    /**
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-08-19
     */
    public function testFilterValidationValidatorEmailAllowEmptySuccess()
    {
        $validation = new Validation();

        $validation->add('email', new Validation\Validator\Email(['allowEmpty' => true]));

        $this->assertEmpty(
            $validation->validate(['email' => ''])
        );
    }

    /**
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-08-19
     */
    public function testFilterValidationValidatorEmailWithUTF8Fail()
    {
        $validation = new Validation();

        $validation->add('email', new Validation\Validator\Email());

        $this->assertNotEmpty(
            $validation->validate(['email' => 'täst@example.com'])
        );
    }

    /**
     * @author n[oO]ne <lominum@protonmail.com>
     * @since  2024-08-19
     */
    public function testFilterValidationValidatorEmailWithUTF8Success()
    {
        $validation = new Validation();

        $validation->add('email', new Validation\Validator\Email(['allowUTF8' => true]));

        $this->assertEmpty(
            $validation->validate(['email' => 'täst@example.com'])
        );
    }

    public function testFilterValidationValidatorEmailCustomMessage(): void
    {
        $validation = new Validation();

        $validation->add(
            'email',
            new Email(
                [
                    'message' => 'The email is not valid',
                ]
            )
        );

        $expected = new Messages(
            [
                new Message(
                    'The email is not valid',
                    'email',
                    Email::class,
                    0
                ),
            ]
        );
        $this->assertEquals(
            $expected,
            $validation->validate([])
        );

        $this->assertEquals(
            $expected,
            $validation->validate(['email' => 'x=1'])
        );

        $messages = $validation->validate(['email' => 'x.x@hotmail.com']);

        $this->assertSame(
            0,
            $messages->count()
        );
    }

    /**
     * @author Wojciech Ślawski <jurigag@gmail.com>
     * @since  2016-06-05
     */
    public function testFilterValidationValidatorEmailMultipleField(): void
    {
        $validation = new Validation();

        $validationMessages = [
            'email'        => 'Email must be correct email.',
            'anotherEmail' => 'AnotherEmail must be correct email.',
        ];

        $validation->add(
            [
                'email',
                'anotherEmail',
            ],
            new Email(
                [
                    'message' => $validationMessages,
                ]
            )
        );


        $messages = $validation->validate(
            [
                'email'        => 'test@somemail.com',
                'anotherEmail' => 'test@somemail.com',
            ]
        );

        $this->assertSame(
            0,
            $messages->count()
        );


        $messages = $validation->validate(
            [
                'email'        => 'rootlocalhost',
                'anotherEmail' => 'test@somemail.com',
            ]
        );

        $this->assertSame(
            1,
            $messages->count()
        );

        $this->assertSame(
            $validationMessages['email'],
            $messages->offsetGet(0)->getMessage()
        );


        $messages = $validation->validate(
            [
                'email'        => 'rootlocalhost',
                'anotherEmail' => 'rootlocalhost',
            ]
        );

        $this->assertSame(
            2,
            $messages->count()
        );

        $this->assertSame(
            $validationMessages['email'],
            $messages->offsetGet(0)->getMessage()
        );

        $this->assertSame(
            $validationMessages['anotherEmail'],
            $messages->offsetGet(1)->getMessage()
        );
    }

    /**
     * @author Wojciech Ślawski <jurigag@gmail.com>
     * @since  2016-06-05
     */
    public function testFilterValidationValidatorEmailSingleField(): void
    {
        $validation = new Validation();

        $validation->add(
            'email',
            new Email()
        );


        $messages = $validation->validate(
            [
                'email' => 'test@somemail.com',
            ]
        );

        $this->assertSame(
            0,
            $messages->count()
        );


        $messages = $validation->validate(
            [
                'email' => 'rootlocalhost',
            ]
        );

        $this->assertSame(
            1,
            $messages->count()
        );

        $expected = new Messages(
            [
                new Message(
                    'Field email must be an email address',
                    'email',
                    Email::class,
                    0
                ),
            ]
        );

        $this->assertEquals($expected, $messages);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2023-08-03
     */
    public function testFilterValidationValidatorEmailValidateEmpty(): void
    {
        $validation = new Validation();
        $validator  = new Email(['allowEmpty' => true,]);
        $validation->add('email_one', $validator);

        $entity            = new stdClass();
        $entity->email_one = '';

        $validation->bind($entity, []);

        $this->assertTrue(
            $validator->validate($validation, 'email_one')
        );
    }

    /**
     * @author Phalcon Team <tram@phalcon.io>
     * @since  2023-08-03
     */
    public function testFilterValidationValidatorEmailValidateMultipleField(): void
    {
        $validation = new Validation();

        $validationMessages = [
            'email_one' => 'Email One must be email.',
            'email_two' => 'Email Two must by email.',
        ];

        $validation->add(
            ['email_one', 'email_two'],
            new Email(
                [
                    'message' => $validationMessages,
                ]
            )
        );

        $messages = $validation->validate(
            [
                'email_one' => 'team@phalcon.io',
                'email_two' => 'team2@phalcon.io',
            ]
        );

        $this->assertSame(
            0,
            $messages->count()
        );


        $messages = $validation->validate(
            [
                'email_one' => 'random-text',
                'email_two' => 'team@phalcon.io',
            ]
        );

        $this->assertSame(
            1,
            $messages->count()
        );

        $this->assertSame(
            $validationMessages['email_one'],
            $messages->offsetGet(0)->getMessage()
        );

        $messages = $validation->validate(
            [
                'email_one' => 'random-text',
                'email_two' => 'random-text',
            ]
        );

        $this->assertSame(
            2,
            $messages->count()
        );

        $this->assertSame(
            $validationMessages['email_one'],
            $messages->offsetGet(0)->getMessage()
        );

        $this->assertSame(
            $validationMessages['email_two'],
            $messages->offsetGet(1)->getMessage()
        );
    }

    /**
     * @author Phalcon Team <tram@phalcon.io>
     * @since  2023-08-03
     */
    public function testFilterValidationValidatorEmailValidateSingleField(): void
    {
        $validation = new Validation();

        $validation->add('email_one', new Email());

        $messages = $validation->validate(
            [
                'email_one' => 'team@phalcon.io',
            ]
        );

        $this->assertSame(
            0,
            $messages->count()
        );

        $messages = $validation->validate(
            [
                'email_one' => 'random-text',
            ]
        );

        $this->assertSame(
            1,
            $messages->count()
        );
    }
}
