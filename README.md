# RangeValidator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/melhorenvio/range-validator.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/range-validator)
[![Build Status](https://img.shields.io/travis/melhorenvio/range-validator/master.svg?style=flat-square)](https://travis-ci.org/melhorenvio/range-validator)
[![Quality Score](https://img.shields.io/scrutinizer/g/melhorenvio/range-validator.svg?style=flat-square)](https://scrutinizer-ci.com/g/melhorenvio/range-validator)
[![Total Downloads](https://img.shields.io/packagist/dt/melhorenvio/range-validator.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/range-validator)

This package has the objective of validate an array of ranges passed as function's parameter and return the invalid ranges with a message and code of the respective error. However, if there isn't problems with the ranges passed, the function will return a success message.

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

You will need to set the RangeValidator dependencie.
``` php
use Melhorenvio\RangeValidator\RangeValidator;
```

The RangeValidator functions accept a parameter, this paramater must be an array in the format showed below.
``` php
$ranges = [
    'begin' => '12345678',
    'end' => '87654321'
];
```
The "begin" and "end" values must be of the String type, otherwise will be showed an error message instead the invalid ranges.

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

The return of the functions will be an array white a "message", "code" and, if it has, a "data" attribute as showed below.
``` php
[
    'message' => String,
    'code' => Int,
    'data' => Array
]
```

<!-- ### Testing

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

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com). -->
