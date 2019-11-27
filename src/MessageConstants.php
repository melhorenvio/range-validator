<?php

namespace Melhorenvio\RangeValidator;

class MessageConstants
{
    const EMPTY_CODE_EXCEPTION = 1;
    const OVERLAPPING_CODE_EXCEPTION = 2;
    const BEGIN_BIGGER_THAN_END_CODE_EXCEPTION = 3;
    const INVALID_PARAMETER_CODE_EXCEPTION = 4;
    const SUCCESS_CODE = 5;
    const EMPTY_PARAMETER_CODE_EXCEPTION = 6;

    const EMPTY_MESSAGE_EXCEPTION = 'These ranges have empty values';
    const OVERLAPPING_MESSAGE_EXCEPTION = 'These ranges are repeated or overlapped with another range';
    const BEGIN_BIGGER_THAN_END_MESSAGE_EXCEPTION = 'These ranges have the begin bigger than the end';
    const INVALID_PARAMETER_MESSAGE_EXCEPTION = 'These informed parameters are invalid';
    const SUCCESS_MESSAGE = 'No invalid range found';
    const EMPTY_PARAMETER_MESSAGE_EXCEPTION = 'No parameter found';
}
