<?php

namespace Ingelby\HoroscopeAstrology\Models;

use yii\base\Model;

class DailyHoroscope extends Model
{
    /**
     * @var string
     */
    public $sign;

    /**
     * @var string
     */
    public $horoscope;
    /**
     * @var string
     */
    public $signDateRange;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [
                [
                    'sign',
                    'horoscope',
                    'signDateRange',
                ],
                'safe',
            ],
        ];
    }
}
