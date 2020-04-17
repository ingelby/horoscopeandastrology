<?php

namespace Ingelby\HoroscopeAstrology\Api;

use Ingelby\HoroscopeAstrology\Exceptions\HoroscopeAstrologyRateLimitException;
use Ingelby\HoroscopeAstrology\Exceptions\HoroscopeAstrologyResponseException;
use Ingelby\HoroscopeAstrology\Models\DailyHoroscope;
use Ingelby\HoroscopeAstrology\Models\HoroscopeAstrologyNews;
use ingelby\toolbox\constants\HttpStatus;
use ingelby\toolbox\services\InguzzleHandler;
use Carbon\Carbon;

class DailyHoroscopeHandler extends AbstractHandler
{

    /**
     * @param string $symbol
     * @return HoroscopeAstrologyNews[]
     * @throws HoroscopeAstrologyResponseException
     * @throws HoroscopeAstrologyRateLimitException
     */
    public function getDailyHroscope(string $symbol)
    {
        $response = $this->fetch(
            'json'
        );


        if (empty($response) || !is_array($response)) {
            throw new HoroscopeAstrologyResponseException(HttpStatus::NOT_FOUND, 'No news for symbol: ' . $symbol);
        }

		$model = new DailyHoroscope();
		$model->setAttributes($response);

        return $model;
    }
}

