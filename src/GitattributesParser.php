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

namespace EliasHaeussler\Gitattributes;

use Symfony\Component\Filesystem;
use Symfony\Component\Finder;

use function array_filter;
use function array_map;
use function array_values;
use function file;
use function is_readable;

/**
 * GitattributesParser.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final class GitattributesParser
{
    public function __construct(
        private readonly string $rootPath,
    ) {}

    /**
     * @throws Exception\FileDoesNotExist
     * @throws Exception\FileIsNotReadable
     */
    public function parse(string $filename): Entity\Ruleset
    {
        $file = $this->createFileObject($filename);
        $path = $file->getPathname();

        if (!file_exists($path)) {
            throw new Exception\FileDoesNotExist($path);
        }
        if (!is_readable($path)) {
            throw new Exception\FileIsNotReadable($path);
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        if (false === $lines) {
            throw new Exception\FileIsNotReadable($path);
        }

        $rules = array_values(
            array_filter(
                array_map(Entity\Rule::fromString(...), $lines),
            ),
        );

        return new Entity\Ruleset($file, $rules);
    }

    private function createFileObject(string $filename): Finder\SplFileInfo
    {
        $pathname = Filesystem\Path::join($this->rootPath, $filename);

        return new Finder\SplFileInfo(
            $pathname,
            Filesystem\Path::makeRelative(Filesystem\Path::getDirectory($pathname), $this->rootPath),
            $filename,
        );
    }
}
