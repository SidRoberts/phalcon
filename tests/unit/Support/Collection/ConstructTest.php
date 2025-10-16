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

final class ConstructTest extends AbstractCollectionTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-10-13
     */
    public function testSupportCollectionInstanceOfCollection(): void
    {
        $collection = new Collection();

        $this->assertInstanceOf(CollectionInterface::class, $collection);
    }

    /**
     * Tests Phalcon\Support\Collection :: __construct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getClasses')]
    public function testSupportCollectionConstruct(
        string $class,
    ): void {
        $collection = new $class();

        $this->assertInstanceOf(Collection::class, $collection);
    }
}
