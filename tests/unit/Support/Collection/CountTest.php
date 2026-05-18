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

use Phalcon\Support\Collection\CollectionInterface;
use PHPUnit\Framework\Attributes\DataProvider;

final class CountTest extends AbstractCollectionTestCase
{
    /**
     * @param class-string<CollectionInterface> $class
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getClasses')]
    public function testSupportCollectionCount(
        string $class,
    ): void {
        $data = $this->getData();
        $collection = new $class($data);

        $this->assertCount(
            3,
            $collection->toArray()
        );

        $this->assertSame(
            3,
            $collection->count()
        );
    }
}
