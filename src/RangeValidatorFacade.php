<?php

namespace Melhorenvio\RangeValidator;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Melhorenvio\RangeValidator\Skeleton\SkeletonClass
 */
class RangeValidatorFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'range-validator';
    }
}
