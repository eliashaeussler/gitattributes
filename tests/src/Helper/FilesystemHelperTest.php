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

namespace EliasHaeussler\Gitattributes\Tests\Helper;

use EliasHaeussler\Gitattributes as Src;
use PHPUnit\Framework;
use Symfony\Component\Finder;

/**
 * FilesystemHelperTest.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
#[Framework\Attributes\CoversClass(Src\Helper\FilesystemHelper::class)]
final class FilesystemHelperTest extends Framework\TestCase
{
    #[Framework\Attributes\Test]
    public function createFileObjectReturnsFileObjectForGivenFileAndRootPath(): void
    {
        $filename = 'baz/baz.txt';
        $rootPath = '/foo/bar';

        $expected = new Finder\SplFileInfo(
            '/foo/bar/baz/baz.txt',
            'baz',
            'baz/baz.txt',
        );

        self::assertEquals($expected, Src\Helper\FilesystemHelper::createFileObject($filename, $rootPath));
    }

    #[Framework\Attributes\Test]
    public function createFileObjectAcceptsAbsolutePaths(): void
    {
        $filename = '/foo/bar/baz/baz.txt';
        $rootPath = '/foo/bar';

        $expected = new Finder\SplFileInfo(
            '/foo/bar/baz/baz.txt',
            'baz',
            'baz/baz.txt',
        );

        self::assertEquals($expected, Src\Helper\FilesystemHelper::createFileObject($filename, $rootPath));
    }
}
