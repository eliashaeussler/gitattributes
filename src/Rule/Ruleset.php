<?php

declare(strict_types=1);

/*
 * This file is part of the Composer package "eliashaeussler/gitattributes".
 *
 * Copyright (C) 2024-2026 Elias Häußler <elias@haeussler.dev>
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
use Symfony\Component\Finder;

use function implode;

/**
 * Ruleset.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final readonly class Ruleset implements Stringable
{
    /**
     * @param list<Rule> $rules
     */
    public function __construct(
        private Finder\SplFileInfo $file,
        private array $rules,
    ) {}

    public function file(): Finder\SplFileInfo
    {
        return $this->file;
    }

    /**
     * @return list<Rule>
     */
    public function rules(): array
    {
        return $this->rules;
    }

    public function toString(): string
    {
        $lines = [];

        foreach ($this->rules as $rule) {
            $lines[] = $rule->toString();
        }

        return implode(PHP_EOL, $lines);
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
