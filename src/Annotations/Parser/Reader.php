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

namespace Phalcon\Annotations\Parser;

use ReflectionClass;
use ReflectionException;

/**
 * Parses classes returning an array with the found annotations
 */
class Reader implements ReaderInterface
{
    /**
     * Reads annotations from the class, its methods and/or properties
     *
     * @param class-string $className
     *
     * @return array{
     *     class?: Collection,
     *     constants?: array<string, Collection>,
     *     properties?: array<string, Collection>,
     *     methods?: array<string, Collection>
     * }
     *
     * @throws ReflectionException
     */
    public function parse(string $className): array
    {
        $annotations = [];

        /**
         * A ReflectionClass is used to obtain the annotations.
         */
        $reflection = new ReflectionClass($className);

        $classAnnotations = $reflection->getAttributes();

        /**
         * Append the class annotations to the annotations var
         */
        if (!empty($classAnnotations)) {
            $annotations["class"] = new Collection($classAnnotations);
        }

        /**
         * Get class constants
         */
        $constants            = $reflection->getReflectionConstants();
        $annotationsConstants = [];

        foreach ($constants as $constant) {
            $constantAnnotations = $constant->getAttributes();

            if (!empty($constantAnnotations)) {
                $annotationsConstants[$constant->getName()] = new Collection($constantAnnotations);
            }
        }

        if (!empty($annotationsConstants)) {
            $annotations["constants"] = $annotationsConstants;
        }

        /**
         * Get the class properties
         */
        $properties            = $reflection->getProperties();
        $annotationsProperties = [];

        foreach ($properties as $property) {
            $propertyAnnotations = $property->getAttributes();

            if (!empty($propertyAnnotations)) {
                $annotationsProperties[$property->getName()] = new Collection($propertyAnnotations);
            }
        }

        if (!empty($annotationsProperties)) {
            $annotations["properties"] = $annotationsProperties;
        }

        /**
         * Get the class methods
         */
        $methods            = $reflection->getMethods();
        $annotationsMethods = [];

        foreach ($methods as $method) {
            $methodAnnotations = $method->getAttributes();

            if (!empty($methodAnnotations)) {
                $annotationsMethods[$method->getName()] = new Collection($methodAnnotations);
            }
        }

        if (!empty($annotationsMethods)) {
            $annotations["methods"] = $annotationsMethods;
        }

        return $annotations;
    }
}
