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
            // [
            //     'begin' => '5',
            //     'end' => '4'
            // ],
            [
                'begin' => '2',
                'end' => null
            ]
        ];

        $validatedRanges = $rangeValidator->checkAll($ranges);

        $this->assertEquals($validatedRanges,[
            [
                'begin' => '2',
                'end' => null,
                'message' => 'Este trecho possui valores vazios ou nulos',
                'code' => '1'
            ],
            // [
            //     'begin' => '5',
            //     'end' => '4',
            //     'message' => 'Este trecho possui o inicio maior que o fim',
            //     'code' => '3'
            // ]
        ]);
    }

    // /** @test */
    // public function it_validates_null_ranges()
    // {
    //     $rangeValidator = new RangeValidator();

    //     $ranges = [
    //         [
    //             'begin' => '12345678',
    //             'end' => '23456789'
    //         ],
    //         [
    //             'begin' => null,
    //             'end' => '23456789'
    //         ],
    //         [
    //             'begin' => '12345678',
    //             'end' => ''
    //         ],
    //         [
    //             'begin' => '34567890',
    //             'end' => '45678901'
    //         ]
    //     ];

    //     $validatedRanges = $rangeValidator->checkNullRanges($ranges);

    //     $this->assertEquals($validatedRanges,
    //     [
    //         [
    //             'begin' => null,
    //             'end' => '23456789',
    //             'message' => 'Este trecho possui valores vazios ou nulos',
    //             'code' => '1'
    //         ],
    //         [
    //             'begin' => '12345678',
    //             'end' => '',
    //             'message' => 'Este trecho possui valores vazios ou nulos',
    //             'code' => '1'
    //         ]
    //     ]);
    // }

    // /** @test */
    // public function it_validates_repeated_ranges()
    // {
    //     $rangeValidator = new RangeValidator();

    //     $ranges = [
    //         [
    //             'begin' => '1',
    //             'end' => '2'
    //         ],
    //         [
    //             'begin' => '3',
    //             'end' => '8'
    //         ],
    //         [
    //             'begin' => '4',
    //             'end' => '5'
    //         ],
    //         [
    //             'begin' => '9',
    //             'end' => '10'
    //         ]
    //     ];

    //     $validatedRanges = $rangeValidator->checkOverlapping($ranges);

    //     $this->assertEquals($validatedRanges,
    //     [
    //         [
    //             'begin' => '3',
    //             'end' => '8',
    //             'message' => 'Este trecho está repetido ou sobreposto a outro trecho',
    //             'code' => '2'
    //         ],
    //         [
    //             'begin' => '4',
    //             'end' => '5',
    //             'message' => 'Este trecho está repetido ou sobreposto a outro trecho',
    //             'code' => '2'
    //         ]
    //     ]);
    // }

    // /** @test */
    // public function it_validates_begin_bigger_than_end()
    // {
    //     $rangeValidator = new RangeValidator();

    //     $ranges = [
    //         [
    //             'begin' => '12345678',
    //             'end' => '23456789'
    //         ],
    //         [
    //             'begin' => '34567890',
    //             'end' => '23456789'
    //         ],
    //         [
    //             'begin' => '12345678',
    //             'end' => '01234567'
    //         ],
    //         [
    //             'begin' => '34567890',
    //             'end' => '45678901'
    //         ]
    //     ];

    //     $validatedRanges = $rangeValidator->checkBeginBiggerThanEnd($ranges);

    //     $this->assertEquals($validatedRanges,
    //     [
    //         [
    //             'begin' => '34567890',
    //             'end' => '23456789',
    //             'message' => 'Este trecho possui o inicio maior que o fim',
    //             'code' => '3'
    //         ],
    //         [
    //             'begin' => '12345678',
    //             'end' => '01234567',
    //             'message' => 'Este trecho possui o inicio maior que o fim',
    //             'code' => '3'
    //         ]
    //     ]);
    // }
}
