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

namespace EliasHaeussler\Gitattributes\Tests\Rule;

use EliasHaeussler\Gitattributes as Src;
use Generator;
use PHPUnit\Framework;

/**
 * RuleTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\Rule\Rule::class)]
final class RuleTest extends Framework\TestCase
{
    #[Framework\Attributes\Test]
    public function fromStringReturnsNullOnComment(): void
    {
        self::assertNull(Src\Rule\Rule::fromString('# this is a comment'));
    }

    #[Framework\Attributes\Test]
    #[Framework\Attributes\DataProvider('fromStringReturnsParsedRuleDataProvider')]
    public function fromStringReturnsParsedRule(string $rule, Src\Rule\Rule $expected): void
    {
        self::assertEquals($expected, Src\Rule\Rule::fromString($rule));
    }

    #[Framework\Attributes\Test]
    public function toStringReturnsConstructedRule(): void
    {
        $subject = new Src\Rule\Rule(
            new Src\Rule\Pattern\FilePattern('*'),
            [
                Src\Rule\Attribute\Attribute::setToValue(
                    Src\Rule\Attribute\AttributeName::Text,
                    'auto',
                ),
                Src\Rule\Attribute\Attribute::setToValue(
                    Src\Rule\Attribute\AttributeName::Eol,
                    'lf',
                ),
            ],
        );

        self::assertSame('* text=auto eol=lf', $subject->toString());
    }

    /**
     * @return Generator<string, array{string, Src\Rule\Rule}>
     */
    public static function fromStringReturnsParsedRuleDataProvider(): Generator
    {
        yield 'no attributes' => [
            '*',
            new Src\Rule\Rule(
                new Src\Rule\Pattern\FilePattern('*'),
                [],
            ),
        ];
        yield 'single attribute' => [
            '* text=auto',
            new Src\Rule\Rule(
                new Src\Rule\Pattern\FilePattern('*'),
                [
                    Src\Rule\Attribute\Attribute::setToValue(
                        Src\Rule\Attribute\AttributeName::Text,
                        'auto',
                    ),
                ],
            ),
        ];
        yield 'multiple attributes' => [
            '* text=auto eol=lf',
            new Src\Rule\Rule(
                new Src\Rule\Pattern\FilePattern('*'),
                [
                    Src\Rule\Attribute\Attribute::setToValue(
                        Src\Rule\Attribute\AttributeName::Text,
                        'auto',
                    ),
                    Src\Rule\Attribute\Attribute::setToValue(
                        Src\Rule\Attribute\AttributeName::Eol,
                        'lf',
                    ),
                ],
            ),
        ];
        yield 'whitespaces between pattern and attributes' => [
            '*                 text=auto',
            new Src\Rule\Rule(
                new Src\Rule\Pattern\FilePattern('*'),
                [
                    Src\Rule\Attribute\Attribute::setToValue(
                        Src\Rule\Attribute\AttributeName::Text,
                        'auto',
                    ),
                ],
            ),
        ];
        yield 'whitespaces around pattern and attributes' => [
            '      * text=auto       ',
            new Src\Rule\Rule(
                new Src\Rule\Pattern\FilePattern('*'),
                [
                    Src\Rule\Attribute\Attribute::setToValue(
                        Src\Rule\Attribute\AttributeName::Text,
                        'auto',
                    ),
                ],
            ),
        ];
    }
}
