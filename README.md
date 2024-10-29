<div align="center">

# Object-oriented `.gitattributes` file handling

[![Coverage](https://img.shields.io/coverallsCoverage/github/eliashaeussler/gitattributes?logo=coveralls)](https://coveralls.io/github/eliashaeussler/gitattributes)
[![Maintainability](https://img.shields.io/codeclimate/maintainability/eliashaeussler/gitattributes?logo=codeclimate)](https://codeclimate.com/github/eliashaeussler/gitattributes/maintainability)
[![CGL](https://img.shields.io/github/actions/workflow/status/eliashaeussler/gitattributes/cgl.yaml?label=cgl&logo=github)](https://github.com/eliashaeussler/gitattributes/actions/workflows/cgl.yaml)
[![Tests](https://img.shields.io/github/actions/workflow/status/eliashaeussler/gitattributes/tests.yaml?label=tests&logo=github)](https://github.com/eliashaeussler/gitattributes/actions/workflows/tests.yaml)
[![Supported PHP Versions](https://img.shields.io/packagist/dependency-v/eliashaeussler/gitattributes/php?logo=php)](https://packagist.org/packages/eliashaeussler/gitattributes)

</div>

A PHP library to parse and dump `.gitattributes` file in an object-oriented way. The library
provides a `GitattributesDumper` and `GitattributesParser` to either read or write contents
to or from `.gitattributes` files. All attributes as of Git v2.46.0 according to the
[official documentation](https://git-scm.com/docs/gitattributes) are supported.

## 🔥 Installation

[![Packagist](https://img.shields.io/packagist/v/eliashaeussler/gitattributes?label=version&logo=packagist)](https://packagist.org/packages/eliashaeussler/gitattributes)
[![Packagist Downloads](https://img.shields.io/packagist/dt/eliashaeussler/gitattributes?color=brightgreen)](https://packagist.org/packages/eliashaeussler/gitattributes)

```bash
composer require eliashaeussler/gitattributes
```

## ⚡ Usage

### Parse rules from `.gitattributes` file

The main parsing functionality is provided by the [`GitattributesParser`](src/GitattributesParser.php).
It can be used as follows:

```php
use EliasHaeussler\Gitattributes;

$parser = new Gitattributes\GitattributesParser(__DIR__);
$ruleset = $parser->parse('.gitattributes');
```

The returned ruleset contains the original filename as well as all parsed rules as instances of
[`Rule\Rule`](src/Rule/Rule.php). A rule contains the file pattern and a list of attributes:

```php
foreach ($ruleset->rules() as $rule) {
    echo $rule->pattern()->toString().' ';

    foreach ($rule->attributes() as $attribute) {
        echo $attribute->toString().' ';
    }

    echo PHP_EOL;
}
```

> [!IMPORTANT]
> Only attribute names listed in the [official documentation](https://git-scm.com/docs/gitattributes)
> are supported by the library. Using other than the supported attributes will raise an exception.
> See [`Rule\Attribute\AttributeName`](src/Rule/Attribute/AttributeName.php) for an overview.

### Dump `.gitattributes` file from rules

It is also possible to create a new `.gitattributes` file by dumping a list of prepared rules.
This functionality is provided by the [`GitattributesDumper`](src/GitattributesDumper.php):

```php
use EliasHaeussler\Gitattributes;

$rules = [
    // You can create rules in an object-oriented way
    new Gitattributes\Rule\Rule(
        new Gitattributes\Rule\Pattern\FilePattern('/tests'),
        [
            Gitattributes\Rule\Attribute\Attribute::set(Gitattributes\Rule\Attribute\AttributeName::ExportIgnore),
        ],
    ),
    // ... or using a string input
    Gitattributes\Rule\Rule::fromString('/phpunit.xml export-ignore'),
];

$dumper = new Gitattributes\GitattributesDumper(__DIR__);
$result = $dumper->dump('.gitattributes', $rules);
```

> [!NOTE]
> A file must not exist when dumping file contents. Otherwise, an exception is thrown.

## 🧑‍💻 Contributing

Please have a look at [`CONTRIBUTING.md`](CONTRIBUTING.md).

## ⭐ License

This project is licensed under [GNU General Public License 3.0 (or later)](LICENSE).
