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

use Phalcon\DataMapper\Statement\Select;
use Phalcon\Tests\AbstractStatementTestCase;
use PHPUnit\Framework\Attributes\Group;

use function env;

final class AsAliasTest extends AbstractStatementTestCase
{
    /**
     * Database Tests Phalcon\DataMapper\Statement\Select :: asAlias()
     *
     * @since 2020-01-20
     */
    #[Group('mysql')]
    public function testDmStatementSelectAsAlias(): void
    {
        $driver = env('driver');
        $select = Select::new($driver);

        $select
            ->from('co_invoices')
            ->asAlias('inv')
        ;

        $this->assertSame(
            '(SELECT * FROM co_invoices) AS inv',
            $select->getStatement()
        );

        $select->resetAs();

        $this->assertSame(
            'SELECT * FROM co_invoices',
            $select->getStatement()
        );
    }
}
