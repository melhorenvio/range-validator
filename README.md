# RangeValidator

[![Latest Version on Packagist](https://img.shields.io/packagist/v/melhorenvio/range-validator.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/range-validator)
[![Build Status](https://img.shields.io/travis/melhorenvio/range-validator/master.svg?style=flat-square)](https://travis-ci.org/melhorenvio/range-validator)
[![Quality Score](https://img.shields.io/scrutinizer/g/melhorenvio/range-validator.svg?style=flat-square)](https://scrutinizer-ci.com/g/melhorenvio/range-validator)
[![Total Downloads](https://img.shields.io/packagist/dt/melhorenvio/range-validator.svg?style=flat-square)](https://packagist.org/packages/melhorenvio/range-validator)

This package has the objective of validate an array of ranges passed and define the invalid ranges with a message and code of the respective error. However, if there isn't problems with the ranges passed, the method will define a success message.

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

The setRanges() and addRanges() methods accept a parameter, this paramater must be an array in the format showed below.
``` php
$ranges = [
    'begin' => '12345678',
    'end' => '87654321'
];
```
The "begin" and "end" values must be of the String type, otherwise will be showed an error message instead the invalid ranges.

The setRanges() method will define the ranges with the value passed as parameter, it will overwrite others already setted ranges.
``` php
$rangeValidator->setRanges($range);
```

The addRanges() method do the same as the setRanges() function, but it wont overwrite others already setted ranges.
``` php
$rangeValidator->addRanges($range);
```

The checkEmpty() method set the response with the ranges with empty values.
``` php
$rangeValidator->checkEmpty();
```

The checkBeginBiggerThanEnd() method set the response with the ranges with begin value bigger than the end value.
``` php
$rangeValidator->checkBeginBiggerThanEnd();
```

The checkRepeated() method set the response with the ranges that are fond more than just one time.
``` php
$rangeValidator->checkRepeated();
```

The checkOverlapping() method set the response with the ranges that are overlapping others ranges.
``` php
$rangeValidator->checkOverlapping();
```

The checkAirport() method will make the validator consider whether the ranges are of the same airport when looking for overlapping. If the ranges are of the same airport, the validator will disconsider the overlapping.
``` php
$rangeValidator->checkAirport();
```
In this case, if the checkAirport method is enable, the setRanges() and addRanges()'s parameters will change, because the string parameter 'airport' will be added. So the array's format will be like showed below.
``` php
$ranges = [
    'begin' => '12345678',
    'end' => '87654321',
    'airport' => 'AirportOne'
];
```

The validate() method will really validate the ranges and return the response, without this method the validation won't work.
``` php
$rangeValidator->validate();
```

The getResponse() method will return the response attribute in array form.
``` php
$rangeValidator->getResponse();
```

The getRanges() method will return the ranges attribute in array form.
``` php
$rangeValidator->getRanges();
```

The response attribute will be an array of arrays with a "message", "code" and, if it has, a "data" attribute as showed below.
``` php
[
    'message' => String,
    'code' => Int,
    'data' => Array
]
```

If the getResponse() or getResponse() methods not be used, so the methods will return an object instance of the RangeValidator.
