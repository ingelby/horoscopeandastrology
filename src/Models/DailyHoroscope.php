<?php


namespace Ingelby\HoroscopeAstrology\Models;

use Carbon\Carbon;
use yii\base\Model;

class DailyHoroscope extends Model
{

	/**
	 * @var string
	 */
	public $language;

	/**
	 * @var array
	 */
	public $dailyhoroscope;

	/**
	 * @var array
	 */
	public $dates;

	/**
	 * @var array
	 */
	public $titles;

	/**
	 * @var string
	 */
	public $credit;


	/**
	 * @return array
	 */
	public function rules()
	{
		return [
			[
				[
					'language',
					'dailyhoroscope',
					'dates',
					'titles',
					'credit',
				],
				'safe',
			],
		];
	}

}
