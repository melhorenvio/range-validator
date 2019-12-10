<?php

namespace Melhorenvio\RangeValidator;


use Illuminate\Support\Facades\Cache;
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

    public function checkRepeated()
    {
        return $this->validate(MessageConstants::REPEATED_CODE_EXCEPTION);
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

        $validateds = collect();

        foreach ($this->ranges as $range) {
            $invalidRange = null;
            $key = $range['begin'] . $range['end'];

            if ($cachedRange = Cache::get($key, false)) {
                continue;
            }

            if (!$this->validateParameter($range)) {
                $validateds->push([
                    'code' => MessageConstants::INVALID_PARAMETER_CODE_EXCEPTION,
                    'range' => $range
                ]);
                continue;
            }

            if ($invalidRange = $this->repeated($range)) {
                $validateds->push([
                    'code' => MessageConstants::REPEATED_CODE_EXCEPTION,
                    'range' => $invalidRange
                ]);
                Cache::forever($key, $validateds->last());
                continue;
            }

            if ($invalidRange = $this->emptyValue($range)) {
                $validateds->push([
                    'code' => MessageConstants::EMPTY_CODE_EXCEPTION,
                    'range' => $invalidRange
                ]);
                Cache::forever($key, $validateds->last());
                continue;
            }

            if ($invalidRange = $this->beginBiggerThanEnd($range)) {
                $validateds->push([
                    'code' => MessageConstants::BEGIN_BIGGER_THAN_END_CODE_EXCEPTION,
                    'range' => $invalidRange
                ]);
                Cache::forever($key, $validateds->last());
                continue;
            }

            if ($invalidRange = $this->overlapping($range)) {
                $validateds->push([
                    'code' => MessageConstants::OVERLAPPING_CODE_EXCEPTION,
                    'range' => $invalidRange
                ]);
                Cache::forever($key, $validateds->last());
                continue;
            }

            if (empty($invalidRange)) {
                $validateds->push([
                    'code' => MessageConstants::SUCCESS_CODE,
                    'range' => $range
                ]);
                Cache::forever($key, $validateds->last());
                continue;
            }
        }
        Cache::flush();
        $repeated = $validateds->where('code', MessageConstants::REPEATED_CODE_EXCEPTION)->pluck('range')->toArray();
        $empty = $validateds->where('code', MessageConstants::EMPTY_CODE_EXCEPTION)->pluck('range')->toArray();
        $beginBigger = $validateds->where('code', MessageConstants::BEGIN_BIGGER_THAN_END_CODE_EXCEPTION)->pluck('range')->toArray();
        $overlapping = $validateds->where('code', MessageConstants::OVERLAPPING_CODE_EXCEPTION)->pluck('range')->toArray();
        $wrongParameter = $validateds->where('code', MessageConstants::INVALID_PARAMETER_CODE_EXCEPTION)->pluck('range')->toArray();

        if (count($wrongParameter)) {
            $this->response[] = [
                'message' => MessageConstants::INVALID_PARAMETER_MESSAGE_EXCEPTION,
                'code' => MessageConstants::INVALID_PARAMETER_CODE_EXCEPTION,
                'data' => $wrongParameter
            ];
            return $this;
        }

        if (count($repeated) && (empty($type) || $type === MessageConstants::REPEATED_CODE_EXCEPTION)) {
            $this->response[] = [
                'message' => MessageConstants::REPEATED_MESSAGE_EXCEPTION,
                'code' => MessageConstants::REPEATED_CODE_EXCEPTION,
                'data' => $repeated
            ];
        }

        if (count($empty) && (empty($type) || $type === MessageConstants::EMPTY_CODE_EXCEPTION)) {
            $this->response[] = [
                'message' => MessageConstants::EMPTY_MESSAGE_EXCEPTION,
                'code' => MessageConstants::EMPTY_CODE_EXCEPTION,
                'data' => $empty
            ];
        }

        if (count($beginBigger) && (empty($type) || $type === MessageConstants::BEGIN_BIGGER_THAN_END_CODE_EXCEPTION)) {
            $this->response[] = [
                'message' => MessageConstants::BEGIN_BIGGER_THAN_END_MESSAGE_EXCEPTION,
                'code' => MessageConstants::BEGIN_BIGGER_THAN_END_CODE_EXCEPTION,
                'data' => $beginBigger
            ];
        }

        if (count($overlapping) && (empty($type) || $type === MessageConstants::OVERLAPPING_CODE_EXCEPTION)) {
            $this->response[] = [
                'message' => MessageConstants::OVERLAPPING_MESSAGE_EXCEPTION,
                'code' => MessageConstants::OVERLAPPING_CODE_EXCEPTION,
                'data' => $overlapping
            ];
        }

        if (!count($validateds->where('code','!=',MessageConstants::SUCCESS_CODE))) {
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
            return false;
        }

        return $range;
    }

    private function overlapping(Array $range)
    {
        if (count($this->getOverlappedRangesCollection($range)) <= 1) {
            return false;
        }

        return $range;
    }

    private function beginBiggerThanEnd(Array $range)
    {
        if($range['begin'] <= $range['end']) {
            return false;
        }

        return $range;
    }

    private function repeated(Array $range)
    {
        $ranges = collect($this->ranges);

        if (count($ranges->where('begin', $range['begin'])->where('end', $range['end'])) <= 1) {
            return false;
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
