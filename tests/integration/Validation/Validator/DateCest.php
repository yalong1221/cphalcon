<?php

/**
 * This file is part of the Phalcon Framework.
 *
 * (c) Phalcon Team <team@phalconphp.com>
 *
 * For the full copyright and license information, please view the LICENSE.txt
 * file that was distributed with this source code.
 */

namespace Phalcon\Test\Integration\Validation\Validator;

use Phalcon\Messages\Message;
use Phalcon\Messages\Messages;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Date;
use IntegrationTester;

class DateCest
{
    /**
     * Tests date validator with single field
     *
     * @author Wojciech Ślawski <jurigag@gmail.com>
     * @since  2016-06-05
     */
    public function validationValidatorSingleField(IntegrationTester $I)
    {
        $validation = new Validation();
        $validation->add('date', new Date());

        $messages = $validation->validate(['date' => '2016-06-05']);
        $expected = 0;
        $actual   = $messages->count();
        $I->assertEquals($expected, $actual);

        $messages = $validation->validate(['date' => '2016-06-32']);
        $expected = 1;
        $actual   = $messages->count();
        $I->assertEquals($expected, $actual);
    }

    /**
     * Tests date validator with multiple field
     *
     * @author Wojciech Ślawski <jurigag@gmail.com>
     * @since  2016-06-05
     */
    public function validationValidatorMultipleField(IntegrationTester $I)
    {
        $validation         = new Validation();
        $validationMessages = [
            'date'        => 'Date must be correct date format Y-m-d.',
            'anotherDate' => 'AnotherDate must be correct date format d-m-Y.',
        ];

        $validation->add(
            ['date', 'anotherDate'],
            new Date(
                [
                    'format'  => [
                        'date'        => 'Y-m-d',
                        'anotherDate' => 'd-m-Y',
                    ],
                    'message' => $validationMessages,
                ]
            )
        );

        $messages = $validation->validate(['date' => '2016-06-05', 'anotherDate' => '05-06-2017']);
        $expected = 0;
        $actual   = $messages->count();
        $I->assertEquals($expected, $actual);

        $messages = $validation->validate(['date' => '2016-06-32', 'anotherDate' => '05-06-2017']);
        $expected = 1;
        $actual   = $messages->count();
        $I->assertEquals($expected, $actual);

        $expected = $validationMessages['date'];
        $actual   = $messages->offsetGet(0)->getMessage();
        $I->assertEquals($expected, $actual);

        $messages = $validation->validate(['date' => '2016-06-32', 'anotherDate' => '32-06-2017']);
        $expected = 2;
        $actual   = $messages->count();
        $I->assertEquals($expected, $actual);

        $expected = $validationMessages['date'];
        $actual   = $messages->offsetGet(0)->getMessage();
        $I->assertEquals($expected, $actual);

        $expected = $validationMessages['anotherDate'];
        $actual   = $messages->offsetGet(1)->getMessage();
        $I->assertEquals($expected, $actual);
    }

    /**
     * Tests detect valid dates
     *
     * @author Gustavo Verzola <verzola@gmail.com>
     * @since  2015-03-09
     */
    public function shouldDetectValidDates(IntegrationTester $I)
    {
        $dates = [
            ['2012-01-01', 'Y-m-d'],
            ['2013-31-12', 'Y-d-m'],
            ['01/01/2014', 'd/m/Y'],
            ['12@12@2015', 'd@m@Y'],
        ];

        foreach ($dates as $item) {
            $date       = $item[0];
            $format     = $item[1];
            $validation = new Validation();
            $validation->add('date', new Date(['format' => $format]));

            $messages = $validation->validate(['date' => $date]);
            $expected = 0;
            $actual   = $messages->count();
            $I->assertEquals($expected, $actual);
        }
    }

    /**
     * Tests detect invalid dates
     *
     * @author Gustavo Verzola <verzola@gmail.com>
     * @since  2015-03-09
     */
    public function shouldDetectInvalidDates(IntegrationTester $I)
    {
        $dates = [
            ['', 'Y-m-d'],
            [false, 'Y-m-d'],
            [null, 'Y-m-d'],
            [new \stdClass, 'Y-m-d'],
            ['2015-13-01', 'Y-m-d'],
            ['2015-01-32', 'Y-m-d'],
            ['2015-01', 'Y-m-d'],
            ['2015-01-01', 'd-m-Y'],
        ];

        foreach ($dates as $item) {
            $date       = $item[0];
            $format     = $item[1];
            $validation = new Validation();
            $validation->add('date', new Date(['format' => $format]));

            $expected = Messages::__set_state(
                [
                    '_messages' => [
                        Message::__set_state(
                            [
                                '_type'    => 'Date',
                                '_message' => 'Field date is not a valid date',
                                '_field'   => 'date',
                                '_code'    => '0',
                            ]
                        ),
                    ],
                ]
            );

            $actual = $validation->validate(['date' => $date]);
            $I->assertEquals($expected, $actual);
        }
    }
}
