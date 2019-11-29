<?php

namespace Melhorenvio\RangeValidator;

use Illuminate\Support\Collection;
use Melhorenvio\RangeValidator\MessageConstants;

class RangeValidator
{
    public function checkEmpty(Array $ranges) {
        return $this->validate($ranges, MessageConstants::EMPTY_CODE_EXCEPTION);
    }

    public function checkOverlapping(Array $ranges) {
        return $this->validate($ranges, MessageConstants::OVERLAPPING_CODE_EXCEPTION);
    }

    public function checkBeginBiggerThanEnd(Array $ranges) {
        return $this->validate($ranges, MessageConstants::BEGIN_BIGGER_THAN_END_CODE_EXCEPTION);
    }

    public function validate(Array $ranges, $type = null) {
        if (!count($ranges)) {
            return [
                'message' => MessageConstants::EMPTY_PARAMETER_MESSAGE_EXCEPTION,
                'code' => MessageConstants::EMPTY_PARAMETER_CODE_EXCEPTION
            ];
        }

        $emptyRanges = [];
        $beginBiggerRanges = [];
        $overlappedRanges = [];
        $invalidParameters = [];
        $invalidRanges = [];

        foreach ($ranges as $range) {
            if (!$this->validateParameter($range)) {
                $invalidParameters[] = $range;
                continue;
            }

            $invalidRange = $this->emptyValue($range);

            if (!empty($invalidRange)) {
                $emptyRanges[] = $invalidRange;
                continue;
            }

            $invalidRange = $this->beginBiggerThanEnd($range);

            if (!empty($invalidRange)) {
                $beginBiggerRanges[] = $invalidRange;
            }

            $invalidRange = $this->overlapping($range, $ranges);

            if (!empty($invalidRange)) {
                $overlappedRanges[] = $invalidRange;
            }
        }

        if (count($invalidParameters)) {
            return [
                'message' => MessageConstants::INVALID_PARAMETER_MESSAGE_EXCEPTION,
                'code' => MessageConstants::INVALID_PARAMETER_CODE_EXCEPTION,
                'data' => $invalidParameters
            ];
        }

        if (count($emptyRanges) && (empty($type) || $type === MessageConstants::EMPTY_CODE_EXCEPTION)) {
            $invalidRanges[] = [
                'message' => MessageConstants::EMPTY_MESSAGE_EXCEPTION,
                'code' => MessageConstants::EMPTY_CODE_EXCEPTION,
                'data' => $emptyRanges
            ];
        }

        if (count($beginBiggerRanges) && (empty($type) || $type === MessageConstants::BEGIN_BIGGER_THAN_END_CODE_EXCEPTION)) {
            $invalidRanges[] = [
                'message' => MessageConstants::BEGIN_BIGGER_THAN_END_MESSAGE_EXCEPTION,
                'code' => MessageConstants::BEGIN_BIGGER_THAN_END_CODE_EXCEPTION,
                'data' => $beginBiggerRanges
            ];
        }

        if (count($overlappedRanges) && (empty($type) || $type === MessageConstants::OVERLAPPING_CODE_EXCEPTION)) {
            $invalidRanges[] = [
                'message' => MessageConstants::OVERLAPPING_MESSAGE_EXCEPTION,
                'code' => MessageConstants::OVERLAPPING_CODE_EXCEPTION,
                'data' => $overlappedRanges
            ];
        }

        if (count($invalidRanges)) {
            return $invalidRanges;
        }

        return [
            'message' => MessageConstants::SUCCESS_MESSAGE,
            'code' => MessageConstants::SUCCESS_CODE
        ];
    }

    private function emptyValue(Array $range) {
        if(!empty($range['begin']) && !empty($range['end'])) {
            return null;
        }

        return $range;
    }

    private function overlapping(Array $range, Array $ranges)
    {
        $ranges = collect($ranges);

        if (count($this->getOverlappedRangesCollection($ranges, $range)) <= 1) {
            return null;
        }

        return $range;
    }

    private function beginBiggerThanEnd(Array $range) {
        if($range['begin'] <= $range['end']) {
            return null;
        }

        return $range;
    }

    private function getOverlappedRangesCollection(Collection $ranges, Array $range) {
        $overlappedRanges = $ranges->where('begin','>=', $range['begin'])->where('begin','<=', $range['end']);

        $overlappedRanges = $ranges->where('begin', '<=', $range['begin'])->where('end', '>=', $range['end']);

        return $overlappedRanges->merge($ranges->where('end','>=', $range['begin'])->where('end','<=', $range['end']))->unique();
    }

    private function validateParameter($range)
    {
        if (gettype($range) != 'array') {
            return false;
        }

        if (!isset($range['begin'])) {
            return false;
        }

        if (gettype($range['begin']) != 'string') {
            return false;
        }

        if (!isset($range['end'])) {
            return false;
        }

        if (gettype($range['end']) != 'string') {
            return false;
        }

        return true;
    }
}
