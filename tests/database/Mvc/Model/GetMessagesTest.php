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

namespace Phalcon\Tests\Database\Mvc\Model;

use PDO;
use Phalcon\Tests\AbstractDatabaseTestCase;
use Phalcon\Tests\Support\Migrations\ObjectsMigration;
use Phalcon\Tests\Support\Models\Objects;
use Phalcon\Tests\Support\Traits\DiTrait;
use PHPUnit\Framework\Attributes\Group;

final class GetMessagesTest extends AbstractDatabaseTestCase
{
    use DiTrait;

    public function setUp(): void
    {
        $this->setNewFactoryDefault();
        $this->setDatabase();

        /** @var PDO $connection */
        $connection = self::getConnection();
        $migration  = new ObjectsMigration($connection);
        $migration->clear();
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-02-01
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelGetMessages(): void
    {
        $record         = new Objects();
        $record->obj_id = 1;

        $this->assertFalse(
            $record->save()
        );

        $messages = $record->getMessages();

        $this->assertCount(2, $messages);

        $this->assertSame(
            'obj_name is required',
            $messages[0]->getMessage()
        );

        $this->assertSame(
            'obj_type is required',
            $messages[1]->getMessage()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2023-09-30
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelGetMessagesFiltered(): void
    {
        $record         = new Objects();
        $record->obj_id = 1;

        $this->assertFalse(
            $record->save()
        );

        $messages = $record->getMessages();

        $this->assertCount(2, $messages);

        /**
         * Filter by field obj_name
         */
        $messages = $record->getMessages('obj_name');

        $this->assertCount(1, $messages);

        $this->assertSame(
            'obj_name is required',
            $messages[0]->getMessage()
        );

        /**
         * Filter by field obj_type
         */
        $messages = $record->getMessages('obj_type');

        $this->assertCount(1, $messages);

        $this->assertSame(
            'obj_type is required',
            $messages[0]->getMessage()
        );

        /**
         * Filter by both fields
         */
        $messages = $record->getMessages(['obj_name', 'obj_type']);

        $this->assertCount(2, $messages);

        $this->assertSame(
            'obj_name is required',
            $messages[0]->getMessage()
        );

        $this->assertSame(
            'obj_type is required',
            $messages[1]->getMessage()
        );
    }
}
