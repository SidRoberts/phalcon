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

namespace Phalcon\Tests\Unit\Support\Helper\Arr;

use Phalcon\Support\Helper\Arr\Get;
use Phalcon\Tests\AbstractUnitTestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use stdClass;

final class GetTest extends AbstractUnitTestCase
{
    /**
     * @return array<array{0: string, 1: mixed, 2: mixed}>
     */
    public static function getExamples(): array
    {
        $sample = new stdClass();

        $sample->one = 'two';

        return [
            [
                'boolean',
                1,
                true,
            ],
            [
                'bool',
                1,
                true,
            ],
            [
                'integer',
                "123",
                123,
            ],
            [
                'int',
                "123",
                123,
            ],
            [
                'float',
                "123.45",
                123.45,
            ],
            [
                'double',
                "123.45",
                123.45,
            ],
            [
                'string',
                123,
                "123",
            ],
            [
                'array',
                $sample,
                ['one' => 'two'],
            ],
            [
                'object',
                ['one' => 'two'],
                $sample,
            ],
            [
                'null',
                1234,
                null,
            ],
        ];
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getExamples')]
    public function testSupportHelperArrGetCast(
        string $cast,
        mixed $value,
        mixed $expected,
    ): void {
        $object = new Get();

        $collection = [
            'value' => $value,
        ];

        $this->assertEquals(
            $expected,
            $object($collection, 'value', null, $cast)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperArrGetDefault(): void
    {
        $object = new Get();

        $collection = [
            1        => 'Phalcon',
            'suffix' => 'Framework',
        ];

        $this->assertSame(
            'Error',
            $object($collection, uniqid(), 'Error')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperArrGetNumeric(): void
    {
        $object = new Get();

        $collection = [
            1        => 'Phalcon',
            'suffix' => 'Framework',
        ];

        $this->assertSame(
            'Phalcon',
            $object($collection, 1, 'Error')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportHelperArrGetString(): void
    {
        $object = new Get();

        $collection = [
            1        => 'Phalcon',
            'suffix' => 'Framework',
        ];

        $this->assertSame(
            'Framework',
            $object($collection, 'suffix', 'Error')
        );
    }
}
