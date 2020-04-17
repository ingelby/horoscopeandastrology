<?php

namespace Ingelby\HoroscopeAstrology\Api;

use Ingelby\HoroscopeAstrology\Exceptions\HoroscopeAstrologyRateLimitException;
use Ingelby\HoroscopeAstrology\Exceptions\HoroscopeAstrologyResponseException;
use Ingelby\HoroscopeAstrology\Models\DailyHoroscope;
use Ingelby\HoroscopeAstrology\Models\HoroscopeAstrologyNews;
use ingelby\toolbox\constants\HttpStatus;
use ingelby\toolbox\services\InguzzleHandler;

class DailyHoroscopeHandler extends AbstractHandler
{

    /**
     * @param string $symbol
     * @return HoroscopeAstrologyNews[]
     * @throws HoroscopeAstrologyResponseException
     * @throws HoroscopeAstrologyRateLimitException
     */
    public function getDailyHoroscope(string $sign = null)
    {
        $response = $this->fetch(
            'json'
        );


        if (empty($response) || !is_array($response)) {
            throw new HoroscopeAstrologyResponseException(HttpStatus::NOT_FOUND, 'No news for symbol: ' . $symbol);
        }


        if($sign){ //-- star sign requested so provide just the daily horoscope for that sign
			$model = new DailyHoroscope();
			$model->setAttributes($response);
			foreach($model->dailyhoroscope as $starSign=>$value):
				if($starSign==$sign){
					return $value;
				}
			endforeach;
			return false;
		}else{ //-- no sign provided so give all the data
			$model = new DailyHoroscope();
			$model->setAttributes($response);

			return $model;
		}

    }
}

