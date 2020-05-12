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
    public function getDailyHoroscope(string $sign=null): DailyHoroscope
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

		$reading = mb_convert_encoding(response['dates'][$sign],"HTML-ENTITIES","UTF-8")?? null;
        $model = new DailyHoroscope(
            [
                'sign'          => $sign,
                'horoscope'     => $response['dailyhoroscope'][$sign],
                'signDateRange' => $reading,
            ]
        );

        return $model;
    }

    /**
     * @return DailyHoroscope[]
     * @throws HoroscopeAstrologyResponseException
     */
    public function getDailyHoroscopes(): array
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

			$reading = mb_convert_encoding(response['dates'][$sign],"HTML-ENTITIES","UTF-8")?? null;

            $horoscopes[] = new DailyHoroscope(
                [
                    'sign'          => $sign,
                    'horoscope'     => $todaysHoroscope,
                    'signDateRange' => $reading,
                ]
            );
        }

        return $horoscopes;
    }
}

