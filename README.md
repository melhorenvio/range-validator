# Very short description of the package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/melhorenvio/range-validator.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/range-validator)
[![Build Status](https://img.shields.io/travis/melhorenvio/range-validator/master.svg?style=flat-square)](https://travis-ci.org/melhorenvio/range-validator)
[![Quality Score](https://img.shields.io/scrutinizer/g/melhorenvio/range-validator.svg?style=flat-square)](https://scrutinizer-ci.com/g/melhorenvio/range-validator)
[![Total Downloads](https://img.shields.io/packagist/dt/melhorenvio/range-validator.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/range-validator)

This is where your description should go. Try and limit it to a paragraph or two, and maybe throw in a mention of what PSRs you support to avoid any confusion with users and contributors.

## Installation

You can install the package via composer:

```bash
composer require melhorenvio/range-validator
```

## Usage

First you need to instantiate a variable of the RangeValidator type.
``` php
$rangeValidator = new RangeValidator();
```

You will need to set the RangeValidator dependencie
``` php
use Melhorenvio\RangeValidator\RangeValidator;
```

The checkEmpty() function returns the ranges with empty values.
``` php
$rangeValidator->checkEmpty($ranges);
```

The checkBegginBiggerThanEnd() function returns the ranges with the begin value bigger than the end value.
``` php
$rangeValidator->checkBegginBiggerThanEnd($ranges);
```

The checkOverlapping() function returns the ranges that are overlapping others ranges.
``` php
$rangeValidator->checkOverlapping($ranges);
```

The validate() function returns what the 3 others functions return.
``` php
$rangeValidator->validate($ranges);
```

### Testing

``` bash
composer test
```

### Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

### Security

If you discover any security related issues, please email tecnologia@melhorenvio.com instead of using the issue tracker.

## Credits

- [Melhor Envio](https://github.com/melhorenvio)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
