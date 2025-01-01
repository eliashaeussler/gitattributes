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

namespace EliasHaeussler\Gitattributes\Tests;

use EliasHaeussler\Gitattributes as Src;
use PHPUnit\Framework;

/**
 * GitattributesParserTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\GitattributesParser::class)]
final class GitattributesParserTest extends Framework\TestCase
{
    private string $testRootPath;
    private Src\GitattributesParser $subject;

    public function setUp(): void
    {
        $this->testRootPath = __DIR__.'/Fixtures';
        $this->subject = new Src\GitattributesParser($this->testRootPath);
    }

    #[Framework\Attributes\Test]
    public function dumpThrowsExceptionIfFileDoesNotExist(): void
    {
        $this->expectExceptionObject(
            new Src\Exception\FileDoesNotExist($this->testRootPath.'/foo'),
        );

        $this->subject->parse('foo');
    }

    #[Framework\Attributes\Test]
    public function dumpReturnsParsedRuleset(): void
    {
        $expected = new Src\Rule\Ruleset(
            Src\Helper\FilesystemHelper::createFileObject('.gitattributes.txt', $this->testRootPath),
            [
                new Src\Rule\Rule(
                    new Src\Rule\Pattern\FilePattern('*'),
                    [
                        Src\Rule\Attribute\Attribute::setToValue(Src\Rule\Attribute\AttributeName::Text, 'auto'),
                        Src\Rule\Attribute\Attribute::setToValue(Src\Rule\Attribute\AttributeName::Eol, 'crlf'),
                    ],
                ),
                new Src\Rule\Rule(
                    new Src\Rule\Pattern\FilePattern('/tests'),
                    [
                        Src\Rule\Attribute\Attribute::set(Src\Rule\Attribute\AttributeName::ExportIgnore),
                    ],
                ),
            ],
        );

        self::assertEquals($expected, $this->subject->parse('.gitattributes.txt'));
    }
}
