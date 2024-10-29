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

namespace EliasHaeussler\Gitattributes\Tests\Entity;

use EliasHaeussler\Gitattributes as Src;
use Generator;
use PHPUnit\Framework;

/**
 * RuleTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\Entity\Rule::class)]
final class RuleTest extends Framework\TestCase
{
    #[Framework\Attributes\Test]
    public function fromStringReturnsNullOnComment(): void
    {
        self::assertNull(Src\Entity\Rule::fromString('# this is a comment'));
    }

    #[Framework\Attributes\Test]
    #[Framework\Attributes\DataProvider('fromStringReturnsParsedRuleDataProvider')]
    public function fromStringReturnsParsedRule(string $rule, Src\Entity\Rule $expected): void
    {
        self::assertEquals($expected, Src\Entity\Rule::fromString($rule));
    }

    #[Framework\Attributes\Test]
    public function toStringReturnsConstructedRule(): void
    {
        $subject = new Src\Entity\Rule(
            new Src\Entity\FilePattern('*'),
            [
                Src\Entity\Attribute\Attribute::setToValue(
                    Src\Entity\Attribute\AttributeName::Text,
                    'auto',
                ),
                Src\Entity\Attribute\Attribute::setToValue(
                    Src\Entity\Attribute\AttributeName::Eol,
                    'lf',
                ),
            ],
        );

        self::assertSame('* text=auto eol=lf', $subject->toString());
    }

    /**
     * @return Generator<string, array{string, Src\Entity\Rule}>
     */
    public static function fromStringReturnsParsedRuleDataProvider(): Generator
    {
        yield 'no attributes' => [
            '*',
            new Src\Entity\Rule(
                new Src\Entity\FilePattern('*'),
                [],
            ),
        ];
        yield 'single attribute' => [
            '* text=auto',
            new Src\Entity\Rule(
                new Src\Entity\FilePattern('*'),
                [
                    Src\Entity\Attribute\Attribute::setToValue(
                        Src\Entity\Attribute\AttributeName::Text,
                        'auto',
                    ),
                ],
            ),
        ];
        yield 'multiple attributes' => [
            '* text=auto eol=lf',
            new Src\Entity\Rule(
                new Src\Entity\FilePattern('*'),
                [
                    Src\Entity\Attribute\Attribute::setToValue(
                        Src\Entity\Attribute\AttributeName::Text,
                        'auto',
                    ),
                    Src\Entity\Attribute\Attribute::setToValue(
                        Src\Entity\Attribute\AttributeName::Eol,
                        'lf',
                    ),
                ],
            ),
        ];
        yield 'whitespaces between pattern and attributes' => [
            '*                 text=auto',
            new Src\Entity\Rule(
                new Src\Entity\FilePattern('*'),
                [
                    Src\Entity\Attribute\Attribute::setToValue(
                        Src\Entity\Attribute\AttributeName::Text,
                        'auto',
                    ),
                ],
            ),
        ];
        yield 'whitespaces around pattern and attributes' => [
            '      * text=auto       ',
            new Src\Entity\Rule(
                new Src\Entity\FilePattern('*'),
                [
                    Src\Entity\Attribute\Attribute::setToValue(
                        Src\Entity\Attribute\AttributeName::Text,
                        'auto',
                    ),
                ],
            ),
        ];
    }
}
