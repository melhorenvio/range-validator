<?php

namespace Melhorenvio\RangeValidator\Tests;

use Melhorenvio\RangeValidator\RangeValidator;
use PHPUnit\Framework\TestCase;

class RangeValidatorTest extends TestCase
{
    /** @test */
    public function it_validates_all_ranges()
    {
        $rangeValidator = new RangeValidator();

        $ranges = [
            [
                'begin' => '1',
                'end' => '2'
            ],
            [
                'begin' => '3',
                'end' => '8'
            ],
            [
                'begin' => '4',
                'end' => '5'
            ],
            [
                'begin' => '5',
                'end' => '4'
            ],
            [
                'begin' => '2',
                'end' => ''
            ]
        ];

        $invalidRanges = $rangeValidator->getAllInvalidRanges($ranges);

        $this->assertEquals($invalidRanges,[
            [
                "message" => "These ranges have empty values",
                "code" => 1,
                "data" => [
                    [
                        "begin" => "2",
                        "end" => ''
                    ]
                ]
            ],
            [
                "message" => "These ranges have the begin bigger than the end",
                "code" => 3,
                "data" => [
                    [
                        "begin" => "5",
                        "end" => "4"
                    ]
                ]
            ],
            [
                "message" => "These ranges are repeated or overlapped with another range",
                "code" => 2,
                "data" => [
                    [
                        "begin" => "3",
                        "end" => "8"
                    ],
                    [
                        "begin" => "4",
                        "end" => "5"
                    ],
                    [
                        "begin" => "5",
                        "end" => "4"
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_validates_empty_ranges()
    {
        $rangeValidator = new RangeValidator();

        $ranges = [
            [
                'begin' => '1',
                'end' => '2'
            ],
            [
                'begin' => '3',
                'end' => '8'
            ],
            [
                'begin' => '4',
                'end' => '5'
            ],
            [
                'begin' => '5',
                'end' => '4'
            ],
            [
                'begin' => '2',
                'end' => ''
            ]
        ];

        $invalidRanges = $rangeValidator->getemptyRanges($ranges);

        $this->assertEquals($invalidRanges,[
            [
                "message" => "These ranges have empty values",
                "code" => 1,
                "data" => [
                    [
                        "begin" => "2",
                        "end" => ''
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_validates_overlapped_ranges()
    {
        $rangeValidator = new RangeValidator();

        $ranges = [
            [
                'begin' => '1',
                'end' => '2'
            ],
            [
                'begin' => '3',
                'end' => '8'
            ],
            [
                'begin' => '4',
                'end' => '5'
            ],
            [
                'begin' => '5',
                'end' => '4'
            ],
            [
                'begin' => '2',
                'end' => ''
            ]
        ];

        $invalidRanges = $rangeValidator->getOverlappedRanges($ranges);

        $this->assertEquals($invalidRanges,[
            [
                "message" => "These ranges are repeated or overlapped with another range",
                "code" => 2,
                "data" => [
                    [
                        "begin" => "3",
                        "end" => "8"
                    ],
                    [
                        "begin" => "4",
                        "end" => "5"
                    ],
                    [
                        "begin" => "5",
                        "end" => "4"
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_validates_begin_bigger_ranges()
    {
        $rangeValidator = new RangeValidator();

        $ranges = [
            [
                'begin' => '1',
                'end' => '2'
            ],
            [
                'begin' => '3',
                'end' => '8'
            ],
            [
                'begin' => '4',
                'end' => '5'
            ],
            [
                'begin' => '5',
                'end' => '4'
            ],
            [
                'begin' => '2',
                'end' => ''
            ]
        ];

        $invalidRanges = $rangeValidator->getBeginBiggerRanges($ranges);

        $this->assertEquals($invalidRanges,[
            [
                "message" => "These ranges have the begin bigger than the end",
                "code" => 3,
                "data" => [
                    [
                        "begin" => "5",
                        "end" => "4"
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_validates_empty_value()
    {
        $rangeValidator = new RangeValidator();

        $ranges = [
                'begin' => '',
                'end' => '23456789'
            ];

        $invalidRange = $rangeValidator->emptyValue($ranges);

        $this->assertEquals($invalidRange,
        [
            'begin' => '',
            'end' => '23456789'
        ]);
    }

    /** @test */
    public function it_validates_overlapping()
    {
        $rangeValidator = new RangeValidator();

        $ranges = [
            [
                'begin' => '1',
                'end' => '2'
            ],
            [
                'begin' => '3',
                'end' => '8'
            ],
            [
                'begin' => '4',
                'end' => '5'
            ],
            [
                'begin' => '9',
                'end' => '10'
            ]
        ];

        $range = [
            'begin' => '3',
            'end' => '8'
        ];

        $invalidRange = $rangeValidator->overlapping($range, $ranges);

        $this->assertEquals($invalidRange,
        [
            'begin' => '3',
            'end' => '8'
        ]);
    }

    /** @test */
    public function it_validates_begin_bigger_than_end()
    {
        $rangeValidator = new RangeValidator();

        $ranges = [
            'begin' => '34567890',
            'end' => '23456789'
        ];

        $invalidRange = $rangeValidator->beginBiggerThanEnd($ranges);

        $this->assertEquals($invalidRange,
        [
            'begin' => '34567890',
            'end' => '23456789'
        ]);
    }
}
