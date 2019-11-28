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

        $invalidRanges = $rangeValidator->validate($ranges);

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

        $invalidRanges = $rangeValidator->checkEmpty($ranges);

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

        $invalidRanges = $rangeValidator->checkOverlapping($ranges);

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

        $invalidRanges = $rangeValidator->checkBeginBiggerThanEnd($ranges);

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
}
