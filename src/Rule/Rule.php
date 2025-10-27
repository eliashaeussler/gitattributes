<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "eliashaeussler/gitattributes".
 *
 * Copyright (C) 2024-2025 Elias Häußler <elias@haeussler.dev>
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

namespace EliasHaeussler\Gitattributes\Rule;

use Stringable;

use function array_map;
use function array_shift;
use function explode;
use function implode;
use function preg_replace;
use function str_starts_with;
use function trim;

/**
 * Rule.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final class Rule implements Stringable
{
    /**
     * @param list<Attribute\Attribute> $attributes
     */
    public function __construct(
        private readonly Pattern\FilePattern $pattern,
        private readonly array $attributes,
    ) {}

    public static function fromString(string $rule): ?self
    {
        $rule = trim($rule);

        // Skip comments
        if (str_starts_with($rule, '#')) {
            return null;
        }

        $parts = array_map(
            trim(...),
            explode(' ', (string) preg_replace('/\s{2,}/', ' ', $rule)),
        );
        $pattern = array_shift($parts);

        return new self(
            new Pattern\FilePattern($pattern),
            array_map(Attribute\Attribute::fromString(...), $parts),
        );
    }

    public function pattern(): Pattern\FilePattern
    {
        return $this->pattern;
    }

    /**
     * @return list<Attribute\Attribute>
     */
    public function attributes(): array
    {
        return $this->attributes;
    }

    public function toString(): string
    {
        return implode(' ', [
            $this->pattern->pattern(),
            ...array_map(strval(...), $this->attributes),
        ]);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
