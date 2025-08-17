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
use PHPUnit\Framework\Attributes\DataProvider;

use const ENT_HTML401;
use const ENT_HTML5;
use const ENT_QUOTES;
use const ENT_SUBSTITUTE;
use const ENT_XHTML;
use const ENT_XML1;

final class AttributesTest extends AbstractUnitTestCase
{
    /**
     * @return array<array{0: int, 1: string, 2: mixed}>
     */
    public static function escaperEscapeHtmlAttrProvider(): array
    {
        return [
            [
                ENT_HTML401,
                "That's right",
                "That's right",
            ],
            [
                ENT_XML1,
                "That's right",
                "That's right",
            ],
            [
                ENT_XHTML,
                "That's right",
                "That's right",
            ],
            [
                ENT_HTML5,
                "That's right",
                "That's right",
            ],
            [
                ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401,
                "That&#039;s right",
                "That's right",
            ],
            [
                ENT_HTML5,
                '10',
                10,
            ],
            [
                ENT_HTML5,
                'maxlength="10" cols="5" rows="3" min="1" max="100"',
                [
                    'maxlength'   => 10,
                    'cols'        => 5,
                    'rows'        => 3,
                    'min'         => 1,
                    'max'         => 100,
                    'notPrinted'  => false,
                    'notPrinted2' => null,
                ],
            ],
            [
                ENT_HTML5,
                '10',
                10,
            ],
            [
                ENT_HTML5,
                'maxlength="10" cols="5" rows="3" min="1" max="100"',
                [
                    'maxlength'   => 10,
                    'cols'        => 5,
                    'rows'        => 3,
                    'min'         => 1,
                    'max'         => 100,
                    'notPrinted'  => false,
                    'notPrinted2' => null,
                ],
            ],
        ];
    }

    /**
     * @param int    $flags
     * @param string $expected
     * @param mixed  $text
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('escaperEscapeHtmlAttrProvider')]
    public function testEscaperAttributes(
        int $flags,
        string $expected,
        mixed $text
    ): void {
        $escaper = new Escaper();

        $escaper->setFlags($flags);

        $this->assertSame(
            $expected,
            $escaper->attributes($text)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testEscaperAttributesWithArrayValue(): void
    {
        $escaper = new Escaper();

        $escaper->setFlags(ENT_HTML5);

        $input = [
            'text' => ['Ferrari', 'Ford', 'Dodge'],
        ];

        $this->assertSame(
            'text="Ferrari Ford Dodge"',
            $escaper->attributes($input)
        );
    }
}
