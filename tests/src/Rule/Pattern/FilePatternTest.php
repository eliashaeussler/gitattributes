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

namespace EliasHaeussler\Gitattributes\Tests\Rule\Pattern;

use EliasHaeussler\Gitattributes as Src;
use Generator;
use PHPUnit\Framework;
use Symfony\Component\Finder;

/**
 * FilePatternTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\Rule\Pattern\FilePattern::class)]
final class FilePatternTest extends Framework\TestCase
{
    #[Framework\Attributes\Test]
    #[Framework\Attributes\DataProvider('matchesReturnsTrueIfPatternMatchesGivenFileDataProvider')]
    public function matchesReturnsTrueIfPatternMatchesGivenFile(string $pattern, bool $expected): void
    {
        $file = new Finder\SplFileInfo(
            __FILE__,
            'tests/src/Rule/Pattern',
            'tests/src/Rule/Pattern/FilePatternTest.php',
        );

        $subject = new Src\Rule\Pattern\FilePattern($pattern);

        self::assertSame($expected, $subject->matches($file));
    }

    #[Framework\Attributes\Test]
    public function objectIsStringable(): void
    {
        $subject = new Src\Rule\Pattern\FilePattern('tests/*');

        self::assertSame('tests/*', (string) $subject);
    }

    /**
     * @return Generator<string, array{string, bool}>
     */
    public static function matchesReturnsTrueIfPatternMatchesGivenFileDataProvider(): Generator
    {
        yield 'relative path with leading slash (matching)' => ['/tests', true];
        yield 'relative path with leading slash (non-matching)' => ['/src', false];

        yield 'relative path with containing slash (matching)' => ['tests/src', true];
        yield 'relative path with containing slash (non-matching)' => ['src/Rule', false];

        yield 'relative path with leading and containing slashes (matching)' => ['/tests/src', true];
        yield 'relative path with leading and containing slashes (non-matching)' => ['/src/Rule', false];

        yield 'relative path with leading wildcard (matching)' => ['**/Rule', true];
        yield 'relative path with leading wildcard (non-matching)' => ['**/tests', false];
        yield 'relative path with leading and trailing wildcard (matching)' => ['**/Rule/**', true];
        yield 'relative path with leading and trailing wildcard (non-matching)' => ['**/tests/**', false];
        yield 'relative path with containing wildcard (matching)' => ['tests/**/FilePatternTest.php', true];

        yield 'relative path with placeholder (matching)' => ['tests/*', true];
        yield 'relative path with placeholder (non-matching)' => ['*/tests', false];

        yield 'relative path with containing group (matching)' => ['tests/[a-z]*', true];
        yield 'relative path with containing group (non-matching)' => ['tests/[A-Z]*', false];

        yield 'path with trailing slash (non-matching)' => ['tests/', false];
        yield 'path with trailing slash and wildcard (matching)' => ['tests/**', true];
        yield 'path with trailing slash, containing group and wildcard (matching)' => ['test[a-z]/**', true];

        yield 'path without slashes (matching)' => ['src', true];
        yield 'path without slashes (non-matching)' => ['FilePatternTest', false];
        yield 'path without slashes and with wildcard (matching)' => ['test**', true];
        yield 'path without slashes and with placeholder (matching)' => ['test*', true];
    }
}
