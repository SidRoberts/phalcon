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

use Phalcon\Di\Di;
use Phalcon\Di\Exception as DiException;
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Exception;
use Phalcon\Mvc\ModelInterface;
use Phalcon\Tests\AbstractDatabaseTestCase;
use Phalcon\Tests\Support\Models\Invoices;
use Phalcon\Tests\Support\Traits\DiTrait;
use PHPUnit\Framework\Attributes\Group;

final class ConstructTest extends AbstractDatabaseTestCase
{
    use DiTrait;

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2020-02-01
     */
    #[Group('mysql')]
    #[Group('pgsql')]
    #[Group('sqlite')]
    public function testMvcModelConstruct(): void
    {
        $this->setNewFactoryDefault();
        $this->setDatabase();

        $invoice = new Invoices();

        $this->assertInstanceOf(
            Model::class,
            $invoice
        );
        $this->assertInstanceOf(
            ModelInterface::class,
            $invoice
        );
    }

    /**
     * Tests Phalcon\Mvc\Model :: __construct() - without a DI
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-09-12
     */
    #[Group('mysql')]
    public function testMvcModelConstructWithoutADI(): void
    {
        $this->expectException(Exception::class);

        $this->expectExceptionMessage(
            "A dependency injection container is required to access the services related to the ODM in '"
                . Invoices::class . "'"
        );

        Di::reset();

        $invoice = new Invoices();
    }

    /**
     * Tests Phalcon\Mvc\Model :: __construct() - without a Models Manager
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-09-12
     */
    #[Group('mysql')]
    public function testMvcModelConstructWithoutAModelsManager(): void
    {
        $this->expectException(DiException::class);
        $this->expectExceptionMessage("Service 'modelsManager' is not registered in the container");

        Di::reset();

        $di = new Di();

        $invoice = new Invoices();
    }
}
