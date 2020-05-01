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
    public function getDailyHoroscope(string $sign=null)
    {
        $sign = ucfirst($sign);
		$horoscopes = [];

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

        if(!$sign){
			foreach ($response['dailyhoroscope'] as $sign => $todaysHoroscope) {

				$doc = new \DOMDocument();
				$doc->loadHTML($todaysHoroscope);
				$xpath = new \DOMXPath($doc);
				foreach ($xpath->query('//a') as $node) {
					$node->parentNode->removeChild($node);
				}
				$todaysHoroscope = $doc->saveHTML();

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
        $model = new DailyHoroscope(
            [
                'sign'          => $sign,
                'horoscope'     => $response['dailyhoroscope'][$sign],
                'signDateRange' => $response['dates'][$sign] ?? null,
            ]
        );

        return $sign?$model:$horoscopes;
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

			$doc = new \DOMDocument();
			$doc->loadHTML($todaysHoroscope);
			$xpath = new \DOMXPath($doc);
			foreach ($xpath->query('//a') as $node) {
				$node->parentNode->removeChild($node);
			}
			$todaysHoroscope = $doc->saveHTML();

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

