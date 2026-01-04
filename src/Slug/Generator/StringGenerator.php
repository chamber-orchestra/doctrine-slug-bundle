<?php

declare(strict_types=1);

/*
 * This file is part of the ChamberOrchestra package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ChamberOrchestra\DoctrineSlugBundle\Slug\Generator;

use Symfony\Component\String\Slugger\SluggerInterface;

readonly class StringGenerator implements GeneratorInterface
{
    public function __construct(
        private SluggerInterface $slugger,
    ) {
    }

    public function generate(string $base, string $delimiter = '-', ?int $length = null): string
    {
        $string = $this->slugger->slug($base, $delimiter);
        $string = $string->lower();

        if (null !== $length) {
            $string = $string->truncate($length);
        }

        return (string)$string;
    }
}
