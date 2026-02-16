<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChamberOrchestra\DoctrineSlugBundle\Mapping\Attribute;

use Attribute;
use Doctrine\ORM\Mapping\MappingAttribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class Slug implements MappingAttribute
{
    public function __construct(
        public string $source,
        public bool $update = false,
        public string $separator = '-',
    ) {
        if ('' === $this->source) {
            throw new \InvalidArgumentException('The "source" option of #[Slug] must be a non-empty string.');
        }

        if ('' === $this->separator || \mb_strlen($this->separator) > 1) {
            throw new \InvalidArgumentException(\sprintf(
                'The "separator" option of #[Slug] must be exactly one character, got "%s".',
                $this->separator,
            ));
        }
    }
}
