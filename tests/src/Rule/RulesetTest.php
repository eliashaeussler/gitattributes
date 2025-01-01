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

namespace EliasHaeussler\Gitattributes\Tests\Rule;

use EliasHaeussler\Gitattributes as Src;
use PHPUnit\Framework;
use Symfony\Component\Finder;

/**
 * RulesetTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\Rule\Ruleset::class)]
final class RulesetTest extends Framework\TestCase
{
    #[Framework\Attributes\Test]
    public function toStringReturnsDumpedRules(): void
    {
        $subject = new Src\Rule\Ruleset(
            new Finder\SplFileInfo(
                __FILE__,
                'tests/src/Rule',
                'tests/src/Rule/RulesetTest.php',
            ),
            [
                new Src\Rule\Rule(
                    new Src\Rule\Pattern\FilePattern('*'),
                    [
                        Src\Rule\Attribute\Attribute::setToValue(Src\Rule\Attribute\AttributeName::Text, 'auto'),
                        Src\Rule\Attribute\Attribute::setToValue(Src\Rule\Attribute\AttributeName::Eol, 'crlf'),
                    ],
                ),
                new Src\Rule\Rule(
                    new Src\Rule\Pattern\FilePattern('tests/*'),
                    [
                        Src\Rule\Attribute\Attribute::set(Src\Rule\Attribute\AttributeName::ExportIgnore),
                    ],
                ),
            ],
        );

        $expected = <<<EOF
* text=auto eol=crlf
tests/* export-ignore
EOF;

        self::assertSame($expected, $subject->toString());
    }
}
