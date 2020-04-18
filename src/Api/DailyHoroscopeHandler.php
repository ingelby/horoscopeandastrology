<?php

namespace Ingelby\HoroscopeAstrology\Api;

use Ingelby\HoroscopeAstrology\Exceptions\HoroscopeAstrologyResponseException;
use Ingelby\HoroscopeAstrology\Models\DailyHoroscope;
use ingelby\toolbox\constants\HttpStatus;
use ingelby\toolbox\services\InguzzleHandler;

class DailyHoroscopeHandler extends AbstractHandler
{
    /**
     * @param string $sign
     * @return DailyHoroscope
     * @throws HoroscopeAstrologyResponseException
     */
    public function getDailyHoroscope(string $sign)
    {
        $sign = ucfirst($sign);

        $response = $this->fetch(
            'json'
        );

        if (!isset($response['dailyhoroscope'], $response['dates'])) {
            throw new HoroscopeAstrologyResponseException(HttpStatus::BAD_REQUEST, 'No horoscope or dates in response');
        }

        if (!is_array($response['dailyhoroscope'])) {
            throw new HoroscopeAstrologyResponseException(HttpStatus::NOT_FOUND, 'Dailyhoroscope is not an array');
        }

        if (!array_key_exists($sign, $response['dailyhoroscope'])) {
            throw new HoroscopeAstrologyResponseException(HttpStatus::NOT_FOUND, 'No horoscope for sign: ' . $sign);
        }

        $model = new DailyHoroscope(
            [
                'sign'          => $sign,
                'horoscope'     => $response['dailyhoroscope'][$sign],
                'signDateRange' => $response['dates'][$sign] ?? null,
            ]
        );

        return $model;
    }

    /**
     * @return DailyHoroscope[]
     * @throws HoroscopeAstrologyResponseException
     */
    public function getDailyHoroscopes()
    {
        $response = $this->fetch(
            'json'
        );

        if (!isset($response['dailyhoroscope'], $response['dates'])) {
            throw new HoroscopeAstrologyResponseException(HttpStatus::BAD_REQUEST, 'No horoscope or dates in response');
        }

        if (!is_array($response['dailyhoroscope'])) {
            throw new HoroscopeAstrologyResponseException(HttpStatus::NOT_FOUND, 'Dailyhoroscope is not an array');
        }

        $horoscopes = [];

        foreach ($response['dailyhoroscope'] as $sign => $todaysHoroscope) {
            $horoscopes[] = new DailyHoroscope(
                [
                    'sign'          => $sign,
                    'horoscope'     => $todaysHoroscope,
                    'signDateRange' => $response['dates'][$sign] ?? null,
                ]
            );
        }
        
        return $horoscopes;
    }
}

