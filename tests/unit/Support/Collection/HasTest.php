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
use Phalcon\Support\Collection\ReadOnlyCollection;
use Phalcon\Support\Collection\CollectionInterface;
use PHPUnit\Framework\Attributes\DataProvider;

final class HasTest extends AbstractCollectionTestCase
{
    /**
     * @param class-string<CollectionInterface> $class
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getClasses')]
    public function testSupportCollectionHas(
        string $class,
    ): void {
        $data = $this->getData();
        $collection = new $class($data);

        $this->assertTrue(
            $collection->has('three')
        );

        $this->assertTrue(
            $collection->has('THREE')
        );

        $this->assertFalse(
            $collection->has(uniqid())
        );

        $this->assertTrue(
            $collection->__isset('three')
        );

        $this->assertTrue(
            isset($collection['three'])
        );

        $this->assertFalse(
            isset($collection[uniqid()])
        );

        $this->assertTrue(
            $collection->offsetExists('three')
        );

        $this->assertFalse(
            $collection->offsetExists(uniqid())
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportCollectionHasSensitive(): void
    {
        $data = $this->getData();
        $collection = new Collection($data, false);

        $this->assertTrue(
            $collection->has('three')
        );

        $this->assertFalse(
            $collection->has('THREE')
        );

        $this->assertFalse(
            $collection->has(uniqid())
        );

        $this->assertTrue(
            $collection->__isset('three')
        );

        $this->assertTrue(
            isset($collection['three'])
        );

        $this->assertFalse(
            isset($collection[uniqid()])
        );

        $this->assertTrue(
            $collection->offsetExists('three')
        );

        $this->assertFalse(
            $collection->offsetExists(uniqid())
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportCollectionReadOnlyHasSensitive(): void
    {
        $data = $this->getData();

        $collection = new ReadOnlyCollection($data, false);

        $this->assertTrue(
            $collection->has('three')
        );

        $this->assertFalse(
            $collection->has('THREE')
        );

        $this->assertFalse(
            $collection->has(uniqid())
        );

        $this->assertTrue(
            $collection->__isset('three')
        );

        $this->assertTrue(
            isset($collection['three'])
        );

        $this->assertFalse(
            isset($collection[uniqid()])
        );

        $this->assertTrue(
            $collection->offsetExists('three')
        );

        $this->assertFalse(
            $collection->offsetExists(uniqid())
        );
    }
}
