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

namespace Phalcon\Tests\Unit\Di\Service\Builder;

use Phalcon\Di\Di;
use Phalcon\Di\Exception;
use Phalcon\Di\Service\Builder;
use Phalcon\Html\Escaper;
use Phalcon\Tests\AbstractUnitTestCase;
use Phalcon\Tests\Support\Di\PropertiesComponent;
use Phalcon\Tests\Support\Di\ServiceComponent;

final class BuildTest extends AbstractUnitTestCase
{
    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildExceptionArgumentType(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Argument at position 0 must have a type'
        );

        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className' => Escaper::class,
            'arguments' => [
                0 => ['one'],
            ],
        ];

        $builder->build($container, $definition);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildExceptionClassName(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            "Invalid service definition. Missing 'className' parameter"
        );

        $container = new Di();
        $builder   = new Builder();

        $builder->build($container, []);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildExceptionUknownServiceType(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Unknown service type in parameter on position 0'
        );

        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className' => Escaper::class,
            'arguments' => [
                0 => [
                    'type'  => 'unknown',
                    'value' => 'one',
                ],
            ],
        ];

        $builder->build($container, $definition);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildInstanceWithCallsException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Setter injection parameters must be an array'
        );

        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className' => PropertiesComponent::class,
            'arguments' => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
            ],
            'calls'     => 1234,
        ];

        $builder->build($container, $definition);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildInstanceWithCallsExceptionMethodArguments(): void
    {
        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className' => PropertiesComponent::class,
            'arguments' => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
            ],
            'calls'     => [
                0 => [
                    'method'    => 'transform',
                    'arguments' => [
                        [
                            'type'  => 'parameter',
                            'value' => 444,
                        ],
                    ],
                ],
            ],
        ];

        $instance = $builder->build($container, $definition);

        $this->assertInstanceOf(PropertiesComponent::class, $instance);

        $this->assertSame('one', $instance->getName());

        $this->assertSame(444, $instance->getType());
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildInstanceWithCallsExceptionMethodArgumentsIsArray(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Call arguments must be an array on position 0'
        );

        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className' => PropertiesComponent::class,
            'arguments' => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
            ],
            'calls'     => [
                0 => [
                    'method'    => 'transform',
                    'arguments' => 444,
                ],
            ],
        ];

        $builder->build($container, $definition);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildInstanceWithCallsExceptionMethodExists(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'The method name is required on position 0'
        );

        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className' => PropertiesComponent::class,
            'arguments' => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
            ],
            'calls'     => [
                0 => [
                    'methodName',
                ],
            ],
        ];

        $builder->build($container, $definition);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildInstanceWithCallsExceptionMethodNoArguments(): void
    {
        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className' => PropertiesComponent::class,
            'arguments' => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
            ],
            'calls'     => [
                0 => [
                    'method' => 'calculate',
                ],
            ],
        ];

        $instance = $builder->build($container, $definition);

        $this->assertInstanceOf(PropertiesComponent::class, $instance);

        $this->assertSame(
            'one',
            $instance->getName()
        );

        $this->assertSame(
            555,
            $instance->getType()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildInstanceWithCallsExceptionMethodPosition(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Method call must be an array on position 0'
        );

        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className' => PropertiesComponent::class,
            'arguments' => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
            ],
            'calls'     => [
                0 => 'methodName',
            ],
        ];

        $builder->build($container, $definition);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildInstanceWithProperties(): void
    {
        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className'  => PropertiesComponent::class,
            'arguments'  => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
            ],
            'properties' => [
                0 => [
                    'name'  => 'propertyName',
                    'value' => [
                        'type'  => 'parameter',
                        'value' => 'set-one',
                    ],
                ],
                1 => [
                    'name'  => 'propertyType',
                    'value' => [
                        'type'  => 'parameter',
                        'value' => 100,
                    ],
                ],
            ],
        ];

        $instance = $builder->build($container, $definition);

        $this->assertInstanceOf(PropertiesComponent::class, $instance);

        $this->assertSame(
            'one',
            $instance->getName()
        );

        $this->assertSame(
            2,
            $instance->getType()
        );

        $this->assertSame(
            'set-one',
            $instance->propertyName
        );

        $this->assertSame(
            100,
            $instance->propertyType
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildInstanceWithPropertiesException(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Setter injection parameters must be an array'
        );

        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className'  => PropertiesComponent::class,
            'arguments'  => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
            ],
            'properties' => 1234,
        ];

        $builder->build($container, $definition);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildInstanceWithPropertiesExceptionPropertyIsArray(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'Property must be an array on position 0'
        );

        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className'  => PropertiesComponent::class,
            'arguments'  => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
            ],
            'properties' => [
                0 => 1,
            ],
        ];

        $builder->build($container, $definition);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildInstanceWithPropertiesExceptionPropertyNameExists(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'The property name is required on position 0'
        );

        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className'  => PropertiesComponent::class,
            'arguments'  => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
            ],
            'properties' => [
                0 => [
                    'one' => 1,
                ],
            ],
        ];

        $builder->build($container, $definition);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildInstanceWithPropertiesExceptionPropertyValueExists(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            'The property value is required on position 0'
        );

        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className'  => PropertiesComponent::class,
            'arguments'  => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
            ],
            'properties' => [
                0 => [
                    'name' => 'propertyName',
                ],
            ],
        ];

        $builder->build($container, $definition);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildPassedParameters(): void
    {
        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className' => PropertiesComponent::class,
            'arguments' => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
            ],
        ];

        $component = $builder->build($container, $definition);

        $this->assertInstanceOf(PropertiesComponent::class, $component);

        $this->assertSame(
            'one',
            $component->getName()
        );

        $this->assertSame(
            2,
            $component->getType()
        );

        $this->assertNull(
            $component->getEscaper()
        );

        $this->assertNull(
            $component->getService()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildPassedParametersWithInstance(): void
    {
        $container = new Di();
        $builder   = new Builder();

        $definition = [
            'className' => PropertiesComponent::class,
            'arguments' => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
                [
                    'type'      => 'instance',
                    'className' => Escaper::class,
                ],
            ],
        ];

        $component = $builder->build($container, $definition);

        $this->assertInstanceOf(PropertiesComponent::class, $component);

        $this->assertSame(
            'one',
            $component->getName()
        );

        $this->assertSame(
            2,
            $component->getType()
        );

        $this->assertInstanceOf(
            Escaper::class,
            $component->getEscaper()
        );

        $this->assertNull(
            $component->getService()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2019-09-09
     */
    public function testDiServiceBuilderBuildPassedParametersWithInstanceAndService(): void
    {
        $container = new Di();
        $builder   = new Builder();
        $service   = new ServiceComponent('two', 3);

        $container->set('newService', $service, true);

        $definition = [
            'className' => PropertiesComponent::class,
            'arguments' => [
                [
                    'type'  => 'parameter',
                    'value' => 'one',
                ],
                [
                    'type'  => 'parameter',
                    'value' => 2,
                ],
                [
                    'type'      => 'instance',
                    'className' => Escaper::class,
                ],
                [
                    'type' => 'service',
                    'name' => 'newService',
                ],
            ],
        ];

        $component = $builder->build($container, $definition);

        $this->assertInstanceOf(PropertiesComponent::class, $component);

        $this->assertSame(
            'one',
            $component->getName()
        );

        $this->assertSame(
            2,
            $component->getType()
        );

        $this->assertInstanceOf(
            Escaper::class,
            $component->getEscaper()
        );

        $this->assertInstanceOf(
            ServiceComponent::class,
            $component->getService()
        );

        $this->assertSame(
            'two',
            $service->getName()
        );

        $this->assertSame(
            3,
            $service->getType()
        );
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2024-01-01
     */
    public function testDiServiceBuilderBuildMissingServiceParameterKey(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage(
            "Service 'value' is required in parameter on position 0"
        );

        $container  = new Di();
        $builder    = new Builder();
        $definition = [
            'className' => Escaper::class,
            'arguments' => [
                0 => [
                    'type' => 'parameter',
                    // 'value' key intentionally missing
                ],
            ],
        ];

        $builder->build($container, $definition);
    }

    /**
     * @author Phalcon Team <team@phalcon.io>
     * @since  2024-01-01
     */
    public function testDiServiceBuilderBuildWithPassedParameters(): void
    {
        $container  = new Di();
        $builder    = new Builder();
        $definition = [
            'className' => PropertiesComponent::class,
        ];

        $instance = $builder->build($container, $definition, ['one', 2]);

        $this->assertInstanceOf(PropertiesComponent::class, $instance);

        $this->assertSame(
            'one',
            $instance->getName()
        );

        $this->assertSame(
            2,
            $instance->getType()
        );
    }
}
