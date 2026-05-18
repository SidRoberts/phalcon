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

namespace Phalcon\Tests\Database\DataMapper\Statement\Select;

use PDO;
use Phalcon\DataMapper\Statement\Select;
use Phalcon\Tests\AbstractStatementTestCase;
use PHPUnit\Framework\Attributes\Group;

use function env;

final class GetBindValuesTest extends AbstractStatementTestCase
{
    /**
     * Database Tests Phalcon\DataMapper\Statement\Select :: getBindValues()
     *
     * @since 2020-01-20
     */
    #[Group('mysql')]
    public function testDmStatementSelectGetBindValues(): void
    {
        $driver = env('driver');
        $select = Select::new($driver);

        /**
         * BindValues - empty
         */
        $this->assertSame(
            [],
            $select->getBindValues()
        );

        /**
         * BindValues
         */
        $select->bindValues(
            [
                'one'   => 'two',
                'three' => 'four',
            ]
        );
        $expected = [
            'one'   => ['two', PDO::PARAM_STR],
            'three' => ['four', PDO::PARAM_STR],
        ];

        $this->assertSame(
            $expected,
            $select->getBindValues()
        );

        /**
         * BindValues - append
         */
        $select->bindValues(
            [
                'five' => 'six',
            ]
        );
        $expected = [
            'one'   => ['two', PDO::PARAM_STR],
            'three' => ['four', PDO::PARAM_STR],
            'five'  => ['six', PDO::PARAM_STR],
        ];

        $this->assertSame(
            $expected,
            $select->getBindValues()
        );

        /**
         * BindValue
         */
        $select->bindValue('seven', 8, PDO::PARAM_INT);
        $expected = [
            'one'   => ['two', PDO::PARAM_STR],
            'three' => ['four', PDO::PARAM_STR],
            'five'  => ['six', PDO::PARAM_STR],
            'seven' => [8, PDO::PARAM_INT],
        ];

        $this->assertSame(
            $expected,
            $select->getBindValues()
        );

        /**
         * BindInline
         */
        $select->bindInline(false, PDO::PARAM_BOOL);
        $expected = [
            'one'   => ['two', PDO::PARAM_STR],
            'three' => ['four', PDO::PARAM_STR],
            'five'  => ['six', PDO::PARAM_STR],
            'seven' => [8, PDO::PARAM_INT],
            '_1_1_' => [false, PDO::PARAM_BOOL],
        ];

        $this->assertSame(
            $expected,
            $select->getBindValues()
        );
    }
}
