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
use Symfony\Component\Filesystem;

use function dirname;
use function file_exists;
use function touch;
use function unlink;

/**
 * GitattributesDumperTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\GitattributesDumper::class)]
final class GitattributesDumperTest extends Framework\TestCase
{
    private string $testRootPath;
    private Src\GitattributesDumper $subject;
    private Filesystem\Filesystem $filesystem;

    public function setUp(): void
    {
        $this->testRootPath = dirname(__DIR__, 2).'/.build/test-dummy';
        $this->subject = new Src\GitattributesDumper($this->testRootPath);
        $this->filesystem = new Filesystem\Filesystem();

        if (!$this->filesystem->exists($this->testRootPath)) {
            $this->filesystem->mkdir($this->testRootPath);
        }
    }

    #[Framework\Attributes\Test]
    public function dumpThrowsExceptionIfTargetFileAlreadyExists(): void
    {
        $filename = '.gitattributes';
        $pathname = Filesystem\Path::join($this->testRootPath, $filename);

        touch($pathname);

        self::assertFileExists($pathname);

        $this->expectExceptionObject(
            new Src\Exception\FileAlreadyExists($pathname),
        );

        $this->subject->dump($filename, []);
    }

    #[Framework\Attributes\Test]
    public function dumpReturnsTrueIfRulesetWasSuccessfullyDumpedToFile(): void
    {
        $filename = '.gitattributes';
        $pathname = Filesystem\Path::join($this->testRootPath, $filename);

        $rules = [
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
        ];

        $expected = <<<EOF
* text=auto eol=crlf
tests/* export-ignore

EOF;

        if (file_exists($pathname)) {
            unlink($pathname);
        }

        self::assertFileDoesNotExist($pathname);
        self::assertTrue($this->subject->dump($filename, $rules));
        self::assertStringEqualsFile($pathname, $expected);
    }

    protected function tearDown(): void
    {
        $this->filesystem->remove($this->testRootPath);
    }
}
