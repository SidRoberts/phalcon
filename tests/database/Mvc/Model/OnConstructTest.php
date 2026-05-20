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

use Phalcon\Tests\AbstractDatabaseTestCase;
use Phalcon\Tests\Support\Traits\DiTrait;
use Phalcon\Tests\Support\Models\OnConstructModel;
use PHPUnit\Framework\Attributes\Group;

final class OnConstructTest extends AbstractDatabaseTestCase
{
    use DiTrait;

    /**
     * Tests Phalcon\Mvc\Model :: onConstruct()
     *
     * @author Phalcon Team <team@phalcon.io>
     * @since  2025-09-12
     */
    #[Group('mysql')]
    public function testMvcModelConstruct(): void
    {
        $this->setNewFactoryDefault();

        $model = new OnConstructModel();

        $this->assertTrue($model->onConstructExecuted);
    }
}
