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

use InvalidArgumentException;
use Phalcon\Support\Collection;
use Phalcon\Support\Collection\CollectionInterface;
use Phalcon\Tests\Unit\Support\Fake\FakeCollectionPhpJsonEncode;
use PHPUnit\Framework\Attributes\DataProvider;

final class ToJsonTest extends AbstractCollectionTestCase
{
    /**
     * @param class-string<CollectionInterface> $class
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    #[DataProvider('getClasses')]
    public function testSupportCollectionToJson(
        string $class,
    ): void {
        $data = $this->getData();

        $collection = new $class($data);

        $this->assertSame(
            json_encode($data),
            $collection->toJson()
        );

        $this->assertSame(
            json_encode($data, JSON_PRETTY_PRINT),
            $collection->toJson(JSON_PRETTY_PRINT)
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-09-09
     */
    public function testSupportCollectionToJsonEncodeFail(): void
    {
        $collection = new Collection();

        $collection->set('handle', fopen('php://memory', 'r'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('json_encode error: Type is not supported');

        $collection->toJson();
    }
}
