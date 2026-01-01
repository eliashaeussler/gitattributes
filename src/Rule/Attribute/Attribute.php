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

namespace EliasHaeussler\Gitattributes\Rule\Attribute;

use Stringable;

use function array_map;
use function count;
use function explode;
use function str_starts_with;
use function trim;

/**
 * Attribute.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final readonly class Attribute implements Stringable
{
    private function __construct(
        private AttributeState $state,
        private ?AttributeName $name = null,
        private ?string $value = null,
    ) {}

    public static function set(AttributeName $name): self
    {
        return new self(AttributeState::Set, $name);
    }

    public static function unset(AttributeName $name): self
    {
        return new self(AttributeState::Unset, $name);
    }

    public static function setToValue(AttributeName $name, string $value): self
    {
        return new self(AttributeState::Value, $name, $value);
    }

    public static function unspecified(): self
    {
        return new self(AttributeState::Unspecified);
    }

    public static function fromString(string $attribute): self
    {
        $attribute = trim($attribute);

        if ('' === $attribute) {
            return self::unspecified();
        }

        $parts = array_map(trim(...), explode('=', $attribute, 2));

        if (2 === count($parts)) {
            return self::setToValue(
                AttributeName::from($parts[0]),
                $parts[1],
            );
        }

        if (str_starts_with($parts[0], '-')) {
            return self::unset(
                AttributeName::from(substr($parts[0], 1)),
            );
        }

        return self::set(
            AttributeName::from($parts[0]),
        );
    }

    public function state(): AttributeState
    {
        return $this->state;
    }

    public function name(): ?AttributeName
    {
        return $this->name;
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function toString(): string
    {
        $name = $this->name->value ?? '';

        return match ($this->state) {
            AttributeState::Set => $name,
            AttributeState::Unset => '-'.$name,
            AttributeState::Unspecified => '',
            AttributeState::Value => $name.'='.$this->value,
        };
    }

    public function __toString(): string
    {
        return $this->toString();
    }
}
