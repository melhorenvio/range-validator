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
                'end' => null
            ]
        ];

        $invalidRanges = $rangeValidator->getAllInvalidRanges($ranges);

        $this->assertEquals($invalidRanges,[
            [
                "message" => "Estes trechos possuem valores vazios ou nulos",
                "code" => "1",
                "ranges" => [
                    [
                        "begin" => "2",
                        "end" => null
                    ]
                ]
            ],
            [
                "message" => "Estes trechos estão repetidos ou sobrepostos a outro trecho",
                "code" => "2",
                "ranges" => [
                    [
                        "begin" => "1",
                        "end" => "2"
                    ],
                    [
                        "begin" => "3",
                        "end" => "8"
                    ],
                    [
                        "begin" => "4",
                        "end" => "5"
                    ]
                ]
            ],
            [
                "message" => "Estes trechos possuem o inicio maior que o fim",
                "code" => "3",
                "ranges" => [
                    [
                        "begin" => "5",
                        "end" => "4"
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_validates_null_ranges()
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
                'end' => null
            ]
        ];

        $invalidRanges = $rangeValidator->getNullRanges($ranges);

        $this->assertEquals($invalidRanges,[
            [
                "message" => "Estes trechos possuem valores vazios ou nulos",
                "code" => "1",
                "ranges" => [
                    [
                        "begin" => "2",
                        "end" => null
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
                'end' => null
            ]
        ];

        $invalidRanges = $rangeValidator->getOverlappedRanges($ranges);

        $this->assertEquals($invalidRanges,[
            [
                "message" => "Estes trechos estão repetidos ou sobrepostos a outro trecho",
                "code" => "2",
                "ranges" => [
                    [
                        "begin" => "1",
                        "end" => "2"
                    ],
                    [
                        "begin" => "3",
                        "end" => "8"
                    ],
                    [
                        "begin" => "4",
                        "end" => "5"
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
                'end' => null
            ]
        ];

        $invalidRanges = $rangeValidator->getBeginBiggerRanges($ranges);

        $this->assertEquals($invalidRanges,[
            [
                "message" => "Estes trechos possuem o inicio maior que o fim",
                "code" => "3",
                "ranges" => [
                    [
                        "begin" => "5",
                        "end" => "4"
                    ]
                ]
            ]
        ]);
    }

    /** @test */
    public function it_validates_null_value()
    {
        $rangeValidator = new RangeValidator();

        $ranges = [
                'begin' => null,
                'end' => '23456789'
            ];

        $invalidRange = $rangeValidator->nullValue($ranges);

        $this->assertEquals($invalidRange,
        [
            'begin' => null,
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
