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

use Phalcon\Events\Event;
use Phalcon\Events\Manager;
use Phalcon\Support\Collection;
use Phalcon\Support\Settings;
use Phalcon\Tests\AbstractDatabaseTestCase;
use Phalcon\Tests\Support\Migrations\CustomersMigration;
use Phalcon\Tests\Support\Models\Customers;
use Phalcon\Tests\Support\Models\CustomersDymanicUpdate;
use Phalcon\Tests\Support\Traits\DiTrait;
use PHPUnit\Framework\Attributes\Group;

final class DynamicUpdateTest extends AbstractDatabaseTestCase
{
    use DiTrait;

    public function setUp(): void
    {
        $this->setNewFactoryDefault();
        $this->setDatabase();
    }

    public function tearDown(): void
    {
        $this->tearDownDatabase();
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2023-08-11
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelDisableDynamicUpdate(): void
    {

        $connection         = self::getConnection();
        $customersMigration = new CustomersMigration($connection);
        $customersMigration->insert(90, 1);

        $collection = new Collection();

        $connection    = $this->container->get('db');
        $manager       = new Manager();
        $modelsManager = $this->container->get('modelsManager');

        $manager->attach(
            'db:beforeQuery',
            function (Event $event) use ($connection, $collection): void {
                $key = (string)$collection->count();
                $collection->set($key, $connection->getSQLVariables());
            }
        );

        $connection->setEventsManager($manager);

        /**
         * Disable system wide dynamic update
         */
        Settings::set('orm.dynamic_update', false);

        /**
         * New model
         */
        $customer                 = Customers::findFirst(['cst_id=:id:', 'bind' => ['id' => 90]]);
        $customer->cst_name_first = 'disableDynamicUpdate';

        $this->assertTrue(
            $customer->save()
        );

        $this->assertFalse(
            $modelsManager->isUsingDynamicUpdate($customer)
        );

        $collection->clear();

        $customer->cst_name_last = 'cst_test_lastName';

        $this->assertTrue(
            $customer->save()
        );

        $this->assertCount(
            4,
            $collection->get('0')
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2023-08-11
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelDisabledCherryPickDynamicUpdate(): void
    {

        $connection         = self::getConnection();
        $customersMigration = new CustomersMigration($connection);
        $customersMigration->insert(90, 1);

        $collection    = new Collection();
        $manager       = new Manager();
        $connection    = $this->container->get('db');
        $modelsManager = $this->container->get('modelsManager');

        $manager->attach(
            'db:beforeQuery',
            function (Event $event) use ($connection, $collection): void {
                $key = (string)$collection->count();
                $collection->set($key, $connection->getSQLVariables());
            }
        );

        $connection->setEventsManager($manager);

        /**
         * Disable system wide dynamic update
         */
        Settings::set('orm.dynamic_update', false);

        /**
         * New model
         *
         * @var CustomersDymanicUpdate
         */
        $customer = CustomersDymanicUpdate::findFirst(['cst_id=:id:', 'bind' => ['id' => 90]]);

        $this->assertTrue(
            $modelsManager->isUsingDynamicUpdate($customer)
        );

        $collection->clear();

        $customer->cst_name_first = 'disabledCherryPickDynamicUpdate';

        $this->assertTrue(
            $customer->save()
        );

        $this->assertCount(
            2,
            $collection->get('0')
        );
    }

    /**
     * @issue https://github.com/phalcon/cphalcon/issues/16343
     * @author Phalcon Team <team@phalcon.io>
     * @since  2023-08-11
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelEnableDynamicUpdate(): void
    {

        $connection         = self::getConnection();
        $customersMigration = new CustomersMigration($connection);
        $customersMigration->insert(90, 1);

        /**
         * Enable system wide dynamic update
         */
        Settings::set('orm.dynamic_update', true);

        $collection    = new Collection();
        $connection    = $this->container->get('db');
        $manager       = new Manager();
        $modelsManager = $this->container->get('modelsManager');

        $manager->attach(
            'db:beforeQuery',
            function (Event $event) use ($connection, $collection): void {
                $key = (string)$collection->count();
                $collection->set($key, $connection->getSQLVariables());
            }
        );

        $connection->setEventsManager($manager);

        /**
         * New model
         */
        $customer                 = Customers::findFirst(['cst_id=:id:', 'bind' => ['id' => 90]]);
        $customer->cst_name_first = 'enableDynamicUpdate';

        $this->assertTrue(
            $customer->save()
        );

        $this->assertTrue(
            $modelsManager->isUsingDynamicUpdate($customer)
        );

        $collection->clear();

        $customer->cst_name_last = 'cst_test_lastName';

        $this->assertTrue(
            $customer->save()
        );

        $this->assertCount(
            2,
            $collection->get('0')
        );
    }
}
