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

namespace EliasHaeussler\Gitattributes\Rule\Attribute;

/**
 * AttributeName.
 *
 * @author Elias Häußler <elias@haeussler.dev>
 * @license GPL-3.0-or-later
 *
 * @see https://git-scm.com/docs/gitattributes#_effects
 */
enum AttributeName: string
{
    case ConflictMarkerSize = 'conflict-marker-size';
    case Crlf = 'crlf';
    case Delta = 'delta';
    case Diff = 'diff';
    case Encoding = 'encoding';
    case Eol = 'eol';
    case ExportIgnore = 'export-ignore';
    case ExportSubst = 'export-subst';
    case Filter = 'filter';
    case Ident = 'ident';
    case Merge = 'merge';
    case Text = 'text';
    case Whitespace = 'whitespace';
    case WorkingTreeEncoding = 'working-tree-encoding';
}
