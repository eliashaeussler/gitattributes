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

namespace EliasHaeussler\Gitattributes\Tests\Rule\Attribute;

use EliasHaeussler\Gitattributes as Src;
use Generator;
use PHPUnit\Framework;

/**
 * AttributeTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\Rule\Attribute\Attribute::class)]
final class AttributeTest extends Framework\TestCase
{
    #[Framework\Attributes\Test]
    public function setReturnsAttributeWithGivenName(): void
    {
        $actual = Src\Rule\Attribute\Attribute::set(Src\Rule\Attribute\AttributeName::ExportIgnore);

        self::assertSame('export-ignore', $actual->toString());
    }

    #[Framework\Attributes\Test]
    public function unsetReturnsAttributeWithGivenName(): void
    {
        $actual = Src\Rule\Attribute\Attribute::unset(Src\Rule\Attribute\AttributeName::ExportIgnore);

        self::assertSame('-export-ignore', $actual->toString());
    }

    #[Framework\Attributes\Test]
    public function setToValueReturnsAttributeWithGivenNameAndValue(): void
    {
        $actual = Src\Rule\Attribute\Attribute::setToValue(
            Src\Rule\Attribute\AttributeName::Text,
            'auto',
        );

        self::assertSame('text=auto', $actual->toString());
    }

    #[Framework\Attributes\Test]
    public function unspecifiedReturnsEmptyAttribute(): void
    {
        $actual = Src\Rule\Attribute\Attribute::unspecified();

        self::assertSame('', $actual->toString());
    }

    #[Framework\Attributes\Test]
    #[Framework\Attributes\DataProvider('fromStringReturnsParsedAttributeFromGivenStringDataProvider')]
    public function fromStringReturnsParsedAttributeFromGivenString(
        string $attribute,
        Src\Rule\Attribute\Attribute $expected,
    ): void {
        self::assertEquals($expected, Src\Rule\Attribute\Attribute::fromString($attribute));
    }

    #[Framework\Attributes\Test]
    public function objectIsStringable(): void
    {
        $actual = Src\Rule\Attribute\Attribute::set(Src\Rule\Attribute\AttributeName::ExportIgnore);

        self::assertSame('export-ignore', (string) $actual);
    }

    /**
     * @return Generator<string, array{string, Src\Rule\Attribute\Attribute}>
     */
    public static function fromStringReturnsParsedAttributeFromGivenStringDataProvider(): Generator
    {
        yield 'unspecified' => [
            '',
            Src\Rule\Attribute\Attribute::unspecified(),
        ];
        yield 'set to value' => [
            'text=auto',
            Src\Rule\Attribute\Attribute::setToValue(Src\Rule\Attribute\AttributeName::Text, 'auto'),
        ];
        yield 'unset' => [
            '-export-ignore',
            Src\Rule\Attribute\Attribute::unset(Src\Rule\Attribute\AttributeName::ExportIgnore),
        ];
        yield 'set' => [
            'export-ignore',
            Src\Rule\Attribute\Attribute::set(Src\Rule\Attribute\AttributeName::ExportIgnore),
        ];
    }
}
