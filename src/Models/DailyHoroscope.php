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

    /**
     * @return string
     */
    public function getHtmlStrippedHoroscope(): string
    {
        $todaysHoroscope = $this->horoscope;
        $doc = new \DOMDocument();
        $doc->loadHTML($todaysHoroscope);
        $xpath = new \DOMXPath($doc);
        foreach ($xpath->query('//a') as $node) {
            $node->parentNode->removeChild($node);
        }
        $todaysHoroscope = $doc->saveHTML();

        return $todaysHoroscope;
    }
}
