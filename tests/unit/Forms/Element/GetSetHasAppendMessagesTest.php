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

namespace Phalcon\Tests\Unit\Forms\Element;

use Phalcon\Forms\Element\ElementInterface;
use Phalcon\Messages\Message;
use Phalcon\Messages\Messages;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Unit\Forms\Fake\FormsTrait;
use PHPUnit\Framework\Attributes\DataProvider;

use function uniqid;

final class GetSetHasAppendMessagesTest extends AbstractUnitTestCase
{
    use FormsTrait;

    /**
     * Tests Phalcon\Forms\Element\* :: getMessages()/setMessages()/hasMessages()/appendMessage()
     *
     * @param class-string<ElementInterface> $class
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-12-05
     */
    #[DataProvider('getExamples')]
    public function testFormsElementGetSetAppendMessages(
        string $class
    ): void {
        $name     = uniqid();
        $one      = new Message('one', 'two');
        $two      = new Message('three', 'four');
        $three    = new Message('five', 'six');
        $messages = new Messages(
            [
                $one,
                $two,
            ]
        );

        $object = new $class($name);

        $this->assertInstanceOf(
            Messages::class,
            $object->getMessages()
        );

        $this->assertFalse(
            $object->hasMessages()
        );

        $object->setMessages($messages);

        $this->assertSame(
            $messages,
            $object->getMessages()
        );

        $object->appendMessage($three);

        $messages[] = $three;

        $this->assertSame(
            $messages,
            $object->getMessages()
        );

        $this->assertTrue(
            $object->hasMessages()
        );
    }
}
