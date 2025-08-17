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

namespace Phalcon\Tests\Unit\Cli\Dispatcher;

use Phalcon\Cli\Dispatcher;
use Phalcon\Di\FactoryDefault\Cli as DiFactoryDefault;
use Phalcon\Tests\AbstractUnitTestCase;

final class GetOptionTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2018-11-13
     */
    public function testCliDispatcherGetOption(): void
    {
        $container  = new DiFactoryDefault();
        $dispatcher = new Dispatcher();

        $dispatcher->setDi($container);

        $options = [
            "phalcon" => "value123!",
        ];

        $dispatcher->setOptions($options);

        $optionName   = "phalcon";
        $defaultValue = "Phalcon Rocks!";

        $this->assertSame(
            $options[$optionName],
            $dispatcher->getOption($optionName)
        );

        $this->assertSame(
            $options[$optionName],
            $dispatcher->getOption($optionName, '', $defaultValue)
        );

        $this->assertSame(
            $defaultValue,
            $dispatcher->getOption('nonExisting', '', $defaultValue)
        );

        $this->assertSame(
            'value123',
            $dispatcher->getOption($optionName, 'alnum')
        );

        $this->assertSame(
            123,
            $dispatcher->getOption($optionName, ['int'])
        );
    }
}
