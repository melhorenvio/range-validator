<?php

namespace Melhorenvio\RangeValidator;

use Illuminate\Support\Collection;
use Melhorenvio\RangeValidator\MessageConstants;

class RangeValidator
{
    protected $ranges = [];

    protected $response = [];

    public function getResponse()
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, $this->response);
    }

    public function getRanges()
    {
        return array_map(function ($value) {
            return $value instanceof Arrayable ? $value->toArray() : $value;
        }, $this->ranges);
    }

    public function setRanges(Array $ranges)
    {
        $this->ranges = $ranges;
        return $this;
    }

    public function addRanges(Array $ranges)
    {
        $this->ranges = array_merge($this->ranges, $ranges);
        return $this;
    }

    public function checkEmpty()
    {
        return $this->validate(MessageConstants::EMPTY_CODE_EXCEPTION);
    }

    public function checkOverlapping()
    {
        return $this->validate(MessageConstants::OVERLAPPING_CODE_EXCEPTION);
    }

    public function checkBeginBiggerThanEnd()
    {
        return $this->validate(MessageConstants::BEGIN_BIGGER_THAN_END_CODE_EXCEPTION);
    }

    public function validate($type = null)
    {
        if (!count($this->ranges)) {
            $this->response[] = [
                'message' => MessageConstants::EMPTY_PARAMETER_MESSAGE_EXCEPTION,
                'code' => MessageConstants::EMPTY_PARAMETER_CODE_EXCEPTION
            ];
            return $this;
        }

        $emptyRanges = [];
        $beginBiggerRanges = [];
        $overlappedRanges = [];
        $invalidParameters = [];
        $hasInvalidRanges = false;

        foreach ($this->ranges as $range) {
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

            $invalidRange = $this->overlapping($range);

            if (!empty($invalidRange)) {
                $overlappedRanges[] = $invalidRange;
            }
        }

        if (count($invalidParameters)) {
            $this->response[] = [
                'message' => MessageConstants::INVALID_PARAMETER_MESSAGE_EXCEPTION,
                'code' => MessageConstants::INVALID_PARAMETER_CODE_EXCEPTION,
                'data' => $invalidParameters
            ];
            return $this;
        }

        if (count($emptyRanges) && (empty($type) || $type === MessageConstants::EMPTY_CODE_EXCEPTION)) {
            $this->response[] = [
                'message' => MessageConstants::EMPTY_MESSAGE_EXCEPTION,
                'code' => MessageConstants::EMPTY_CODE_EXCEPTION,
                'data' => $emptyRanges
            ];
            $hasInvalidRanges = true;
        }

        if (count($beginBiggerRanges) && (empty($type) || $type === MessageConstants::BEGIN_BIGGER_THAN_END_CODE_EXCEPTION)) {
            $this->response[] = [
                'message' => MessageConstants::BEGIN_BIGGER_THAN_END_MESSAGE_EXCEPTION,
                'code' => MessageConstants::BEGIN_BIGGER_THAN_END_CODE_EXCEPTION,
                'data' => $beginBiggerRanges
            ];
            $hasInvalidRanges = true;
        }

        if (count($overlappedRanges) && (empty($type) || $type === MessageConstants::OVERLAPPING_CODE_EXCEPTION)) {
            $this->response[] = [
                'message' => MessageConstants::OVERLAPPING_MESSAGE_EXCEPTION,
                'code' => MessageConstants::OVERLAPPING_CODE_EXCEPTION,
                'data' => $overlappedRanges
            ];
            $hasInvalidRanges = true;
        }

        if (!$hasInvalidRanges) {
            $this->response[] = [
                'message' => MessageConstants::SUCCESS_MESSAGE,
                'code' => MessageConstants::SUCCESS_CODE
            ];
        }

        return $this;
    }

    private function emptyValue(Array $range)
    {
        if(!empty($range['begin']) && !empty($range['end'])) {
            return null;
        }

        return $range;
    }

    private function overlapping(Array $range)
    {
        if (count($this->getOverlappedRangesCollection($range)) <= 1) {
            return null;
        }

        return $range;
    }

    private function beginBiggerThanEnd(Array $range)
    {
        if($range['begin'] <= $range['end']) {
            return null;
        }

        return $range;
    }

    private function getOverlappedRangesCollection(Array $range)
    {
        $ranges = collect($this->ranges);

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
