<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "eliashaeussler/gitattributes".
 *
 * Copyright (C) 2024 Elias Häußler <elias@haeussler.dev>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

namespace EliasHaeussler\Gitattributes\Entity;

use Stringable;
use Symfony\Component\Filesystem;
use Symfony\Component\Finder;

use function fnmatch;
use function str_starts_with;
use function strlen;
use function strpos;
use function trim;

/**
 * FilePattern.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final class FilePattern implements Stringable
{
    public function __construct(
        private readonly string $pattern,
    ) {}

    public function pattern(): string
    {
        return $this->pattern;
    }

    /**
     * @see https://git-scm.com/docs/gitattributes#_description
     * @see https://git-scm.com/docs/gitignore#_pattern_format
     */
    public function matches(Finder\SplFileInfo $file): bool
    {
        $patterns = [];
        $relativePathname = $file->getRelativePathname();

        if (str_starts_with($this->pattern, '/') || (strpos($this->pattern, '/') < strlen($this->pattern) - 1)) {
            // Pattern is relative to .gitattributes file
            $normalizedPattern = trim($this->pattern, DIRECTORY_SEPARATOR);

            $patterns[] = $normalizedPattern;
            $patterns[] = Filesystem\Path::join($normalizedPattern, '**');
        } else {
            // Pattern is global (may match at any level)
            $patterns[] = $this->pattern;
            $patterns[] = Filesystem\Path::join('**', $this->pattern);
            $patterns[] = Filesystem\Path::join($this->pattern, '**');
            $patterns[] = Filesystem\Path::join('**', $this->pattern, '**');
        }

        foreach ($patterns as $pattern) {
            if (fnmatch($pattern, $relativePathname)) {
                return true;
            }
        }

        return false;
    }

    public function toString(): string
    {
        return $this->pattern;
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
