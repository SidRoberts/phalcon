<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Phalcon\Tests\Unit\Html\Helper\Ul;

use Phalcon\Html\Escaper;
use Phalcon\Html\Exception;
use Phalcon\Html\Helper\Ul;
use Phalcon\Html\TagFactory;
use Phalcon\Tests\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

use const PHP_EOL;

final class UnderscoreInvokeTest extends AbstractUnitTestCase
{
    /**
     * @return array<array{0: string, 1: string, 2: array, 3: array, 4: string}>
     */
    public static function getExamples(): array
    {
        return [
            [
                '    ',
                PHP_EOL,
                ['id' => 'carsList'],
                [
                    [
                        "> Ferrari",
                        ["class" => "active"],
                        false,
                    ],
                    [
                        "> Ford",
                        [],
                        false,
                    ],
                    [
                        "> Dodge",
                        [],
                        false,
                    ],
                    [
                        "> Toyota",
                        [],
                        false,
                    ],
                ],
                "<ul id=\"carsList\">
    <li class=\"active\">&gt; Ferrari</li>
    <li>&gt; Ford</li>
    <li>&gt; Dodge</li>
    <li>&gt; Toyota</li>
</ul>",
            ],
            [
                '    ',
                PHP_EOL,
                ['id' => 'carsList'],
                [
                    [
                        "> Ferrari",
                        ["class" => "active"],
                        false,
                    ],
                    [
                        "> Ford",
                        [],
                        false,
                    ],
                    [
                        "> Dodge",
                        [],
                        false,
                    ],
                    [
                        "> Toyota",
                        [],
                        false,
                    ],
                ],
                "<ul id=\"carsList\">
    <li class=\"active\">&gt; Ferrari</li>
    <li>&gt; Ford</li>
    <li>&gt; Dodge</li>
    <li>&gt; Toyota</li>
</ul>",
            ],
            [
                '--',
                '+',
                ['id' => 'carsList'],
                [
                    [
                        "> Ferrari",
                        ["class" => "active"],
                        false,
                    ],
                    [
                        "> Ford",
                        [],
                        false,
                    ],
                    [
                        "> Dodge",
                        [],
                        false,
                    ],
                    [
                        "> Toyota",
                        [],
                        false,
                    ],
                ],
                "<ul id=\"carsList\">+--<li class=\"active\">&gt; Ferrari</li>+"
                . "--<li>&gt; Ford</li>+--<li>&gt; Dodge</li>+--<li>&gt; Toyota</li>+</ul>",
            ],
            [
                '    ',
                PHP_EOL,
                ['id' => 'carsList'],
                [
                    [
                        "> Ferrari",
                        ["class" => "active"],
                        true,
                    ],
                    [
                        "> Ford",
                        [],
                        true,
                    ],
                    [
                        "> Dodge",
                        [],
                        true,
                    ],
                    [
                        "> Toyota",
                        [],
                        true,
                    ],
                ],
                "<ul id=\"carsList\">
    <li class=\"active\">> Ferrari</li>
    <li>> Ford</li>
    <li>> Dodge</li>
    <li>> Toyota</li>
</ul>",
            ],
            [
                '    ',
                PHP_EOL,
                ['id' => 'carsList'],
                [
                    [
                        "> Ferrari",
                        ["class" => "active"],
                        true,
                    ],
                    [
                        "> Ford",
                        [],
                        true,
                    ],
                    [
                        "> Dodge",
                        [],
                        true,
                    ],
                    [
                        "> Toyota",
                        [],
                        true,
                    ],
                ],
                "<ul id=\"carsList\">
    <li class=\"active\">> Ferrari</li>
    <li>> Ford</li>
    <li>> Dodge</li>
    <li>> Toyota</li>
</ul>",
            ],
            [
                '--',
                '+',
                ['id' => 'carsList'],
                [
                    [
                        "> Ferrari",
                        ["class" => "active"],
                        true,
                    ],
                    [
                        "> Ford",
                        [],
                        true,
                    ],
                    [
                        "> Dodge",
                        [],
                        true,
                    ],
                    [
                        "> Toyota",
                        [],
                        true,
                    ],
                ],
                "<ul id=\"carsList\">+--<li class=\"active\">> Ferrari</li>+"
                . "--<li>> Ford</li>+--<li>> Dodge</li>+--<li>> Toyota</li>+</ul>",
            ],
        ];
    }

    /**
     * @throws Exception
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getExamples')]
    public function testHtmlHelperUlUnderscoreInvoke(
        string $indent,
        string $delimiter,
        array $attributes,
        array $add,
        string $expected
    ): void {
        $escaper = new Escaper();
        $helper  = new Ul($escaper);

        $result = $helper($indent, $delimiter, $attributes);

        foreach ($add as $addItem) {
            $result->add($addItem[0], $addItem[1], $addItem[2]);
        }

        $this->assertSame(
            $expected,
            (string)$result
        );

        $factory = new TagFactory($escaper);
        $locator = $factory->newInstance('ul');

        $result = $locator($indent, $delimiter, $attributes);

        foreach ($add as $addItem) {
            $result->add($addItem[0], $addItem[1], $addItem[2]);
        }

        $this->assertSame(
            $expected,
            (string)$result
        );
    }
}
