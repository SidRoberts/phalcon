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

namespace Phalcon\Tests\Unit\Flash\Direct;

use Phalcon\Flash\Direct;
use Phalcon\Html\Escaper;
use Phalcon\Tests\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use const PHP_EOL;

final class OutputMessageTest extends AbstractUnitTestCase
{
    /**
     * @return array<array{0: string}>
     */
    public static function getExamples(): array
    {
        return [
            [
                'error',
            ],
            [
                'notice',
            ],
            [
                'success',
            ],
            [
                'warning',
            ],
        ];
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getExamples')]
    public function testFlashDirectOutputMessage(string $type): void
    {
        $flash = new Direct(new Escaper());

        $flash->setImplicitFlush(false);

        $source = 'sample <phalcon> message';

        $expected = '<div class="' . $type . 'Message">'
            . 'sample &lt;phalcon&gt; message</div>' . PHP_EOL;

        $this->assertSame(
            $expected,
            $flash->outputMessage($type, $source)
        );

        $this->assertSame(
            $expected,
            $flash->message($type, $source)
        );

        $this->assertSame(
            $expected,
            $flash->$type($source)
        );
    }
}
