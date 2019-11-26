<?php

namespace Melhorenvio\RangeValidator;

class ExceptionConstants
{
    const NULL_CODE_EXCEPTION = '1';
    const OVERLAPPING_CODE_EXCEPTION = '2';
    const BEGIN_BIGGER_THAN_END_CODE_EXCEPTION = '3';

    const NULL_MESSAGE_EXCEPTION = 'Este trecho possui valores vazios ou nulos';
    const OVERLAPPING_MESSAGE_EXCEPTION = 'Este trecho está repetido ou sobreposto a outro trecho';
    const BEGIN_BIGGER_THAN_END_MESSAGE_EXCEPTION = 'Este trecho possui o inicio maior que o fim';
}
