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

namespace Phalcon\Tests\Unit\Support\Collection;

use Phalcon\Support\Collection;
use Phalcon\Support\Collection\CollectionInterface;
use PHPUnit\Framework\Attributes\DataProvider;

use function uniqid;

final class GetTest extends AbstractCollectionTestCase
{
    /**
     * @param class-string<CollectionInterface> $class
     *
     * @issue https://github.com/phalcon/cphalcon/issues/15370
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2021-12-01
     */
    #[DataProvider('getClasses')]
    public function testSupportCollectionGet(
        string $class,
    ): void {
        $data = $this->getDataForGet();
        $collection = new $class($data);
        $expected = 'four';

        $this->assertSame(
            $expected,
            $collection->get('three')
        );

        $this->assertSame(
            $expected,
            $collection->get('THREE')
        );

        $this->assertSame(
            $expected,
            $collection->get(uniqid(), 'four')
        );

        $this->assertSame(
            $expected,
            $collection['three']
        );

        $this->assertSame($expected, $collection->three);

        $this->assertSame(
            $expected,
            $collection->offsetGet('three')
        );

        $this->assertSame(
            'two',
            $collection->get('one', 'fallback')
        );

        $this->assertSame(
            '',
            $collection->get('seven', 'fallback')
        );

        $this->assertSame(
            'fallback',
            $collection->get('eight', 'fallback')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getExamples')]
    public function testSupportCollectionGetCast(
        string $class,
        string $cast,
        mixed $value,
        mixed $expected,
    ): void {
        $collection = new $class(
            [
                'value' => $value,
            ],
        );

        $this->assertEquals(
            $expected,
            $collection->get('value', null, $cast)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-05-11
     */
    public function testSupportCollectionGetReturnsDefaultForNullByDefaultBC(): void
    {
        $collection = new Collection(['key' => null]);

        $this->assertSame('fallback', $collection->get('key', 'fallback'));
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-05-11
     */
    public function testSupportCollectionGetReturnsNullWhenStrictNullEnabled(): void
    {
        $collection = new Collection(['key' => null], true, true);

        $this->assertNull($collection->get('key', 'fallback'));
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-05-11
     */
    public function testSupportCollectionGetReturnsDefaultForMissingKeyEvenWhenStrict(): void
    {
        $collection = new Collection([], true, true);

        $this->assertSame('fallback', $collection->get('missing', 'fallback'));
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2026-05-13
     * @issue  https://github.com/phalcon/cphalcon/issues/17005
     */
    public function testSupportCollectionGetCastArrayUnwrapsToArrayObjects(): void
    {
        $nested = new Collection(['inKey' => 'inValue']);
        $collection = new Collection(['outKey' => $nested]);

        $extractedArray = $collection->get('outKey', [], 'array');

        $this->assertIsArray($extractedArray);
        $this->assertArrayHasKey('inKey', $extractedArray);
        $this->assertSame('inValue', $extractedArray['inKey']);
    }
}
