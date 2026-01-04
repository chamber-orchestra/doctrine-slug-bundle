<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChamberOrchestra\DoctrineSlugBundle\Exception;

class MappingException extends \ChamberOrchestra\MetadataBundle\Exception\MappingException
{
    public static function notNullableWithSourceNullable(string $className, string $sourceProperty, string $property): self
    {
        return new self(\sprintf('Source property "%s" of class "%s" is nullable while sluggable property "%s" not.',
            $sourceProperty,
            $className,
            $property
        ));
    }

    public static function notUnique(string $className, string $property): self
    {
        return new self(\sprintf('Property "%s" of class "%s" must be unique.',
            $property,
            $className
        ));
    }
}