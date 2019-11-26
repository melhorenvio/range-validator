<?php

namespace Melhorenvio\RangeValidator;

use Illuminate\Support\Collection;
use Melhorenvio\RangeValidator\ExceptionConstants;

class RangeValidator
{

    public function checkAll(Array $ranges) {
        $invalidRanges = collect();

        foreach ($ranges as $range) {
            $invalidRange = $this->checkNullRanges($range);

            if (!empty($invalidRange)) {
                $nullRanges[] = $invalidRange;
                continue;
            }

            $invalidRange = $this->checkBeginBiggerThanEnd($range);

            if (!empty($invalidRange)) {
                $beginBiggerRanges[] = $invalidRange;
                continue;
            }

            $invalidRange = $this->checkOverlapping($range, $ranges);

            if (!empty($invalidRange)) {
                $overlappedRanges[] = $invalidRange;
            }
        }

        if (isset($nullRanges) && count($nullRanges)) {
            $invalidRanges->push([
                'message' => ExceptionConstants::NULL_MESSAGE_EXCEPTION,
                'code' => ExceptionConstants::NULL_CODE_EXCEPTION,
                'data' => $nullRanges
            ]);
        }

        if (isset($overlappedRanges) && count($overlappedRanges)) {
            $invalidRanges->push([
                'message' => ExceptionConstants::OVERLAPPING_MESSAGE_EXCEPTION,
                'code' => ExceptionConstants::OVERLAPPING_CODE_EXCEPTION,
                'data' => $overlappedRanges
            ]);
        }

        if (isset($beginBiggerRanges) && count($beginBiggerRanges)) {
            $invalidRanges->push([
                'message' => ExceptionConstants::BEGIN_BIGGER_THAN_END_MESSAGE_EXCEPTION,
                'code' => ExceptionConstants::BEGIN_BIGGER_THAN_END_CODE_EXCEPTION,
                'data' => $beginBiggerRanges
            ]);
        }

        return $invalidRanges;
    }

    public function checkNullRanges(Array $range) {

        if(!empty($range['begin']) && !empty($range['end'])) {
            return null;
        }

        return $range;
    }

    public function checkOverlapping(Array $range, Array $ranges)
    {
        $ranges = collect($ranges);

        if (count($this->getOverlappedRanges($ranges, $range)) <= 1) {
            return null;
        }

        return $range;
    }

    public function checkBeginBiggerThanEnd(Array $range) {

        if($range['begin'] <= $range['end']) {
            return null;
        }

        return $range;
    }

    private function getOverlappedRanges(Collection $ranges, Array $range) {
        $overlappedRanges = $ranges->whereBetween('begin', $range);
        return $overlappedRanges->merge($ranges->whereBetween('end', $range))->unique();
    }
}
