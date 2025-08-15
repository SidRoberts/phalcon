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

namespace Phalcon\Tests\Unit\Support\Registry;

use Phalcon\Support\Registry;
use PHPUnit\Framework\Attributes\DataProvider;
use stdClass;

final class GetSetHasTest extends AbstractRegistryTestCase
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
     * @since  2018-11-13
     */
    public function testSupportRegistryGet(): void
    {
        $registry = new Registry();

        /**
         * Has
         */
        $this->assertFalse(
            $registry->has('three')
        );

        /**
         * Set
         */
        $registry->set('three', 'two');

        $this->assertTrue(
            $registry->has('three')
        );

        $this->assertSame(
            'two',
            $registry->get('three')
        );

        /**
         * Remove
         */
        $registry->remove('three');

        $this->assertFalse(
            $registry->has('three')
        );

        /**
         * Has property
         */
        $this->assertFalse(
            isset($registry->six)
        );

        /**
         * Set
         */
        $registry->six = 789;

        $this->assertTrue(
            isset($registry->six)
        );

        $this->assertSame(789, $registry->six);

        /**
         * Unset
         */
        unset($registry->six);

        $this->assertFalse(
            isset($registry->six)
        );

        /**
         * offsetExists
         */
        $this->assertFalse(
            $registry->offsetExists('four')
        );

        /**
         * offsetSet
         */
        $registry->offsetSet('four', 123);

        /**
         * offsetExists
         */
        $this->assertTrue(
            $registry->offsetExists('four')
        );

        /**
         * offsetGet
         */
        $this->assertSame(
            123,
            $registry->offsetGet('four')
        );

        /**
         * offsetUnset
         */
        $registry->offsetUnset('four');

        $this->assertFalse(
            $registry->offsetExists('four')
        );

        /**
         * isset
         */
        $this->assertFalse(
            isset($registry['five'])
        );

        /**
         * set
         */
        $registry['five'] = 456;

        $this->assertTrue(
            isset($registry['five'])
        );

        /**
         * Get
         */
        $this->assertSame(456, $registry['five']);

        /**
         * Unset
         */
        unset($registry['five']);

        $this->assertFalse(
            isset($registry['five'])
        );
    }

    /**
     * @since 2019-10-12
     */
    #[DataProvider('getExamples')]
    public function testSupportRegistryGetCast(
        string $cast,
        mixed $value,
        mixed $expected,
    ): void {
        $registry = new Registry(
            [
                'value' => $value,
            ],
        );

        /**
         * Get
         */
        $this->assertEquals(
            $expected,
            $registry->get('value', null, $cast)
        );
    }
}
