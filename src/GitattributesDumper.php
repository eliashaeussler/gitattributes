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

use function file_exists;
use function file_put_contents;

/**
 * GitattributesDumper.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 */
final class GitattributesDumper
{
    public function __construct(
        private readonly string $rootPath,
    ) {}

    /**
     * @param list<Rule\Rule> $rules
     *
     * @throws Exception\FileAlreadyExists
     */
    public function dump(string $filename, array $rules): bool
    {
        $file = Helper\FilesystemHelper::createFileObject($filename, $this->rootPath);

        if (file_exists($file->getPathname())) {
            throw new Exception\FileAlreadyExists($file->getPathname());
        }

        $ruleset = new Rule\Ruleset($file, $rules);
        $lines = $ruleset->toString().PHP_EOL;

        if (false !== file_put_contents($file->getPathname(), $lines)) {
            return true;
        }

        return false;
    }
}
