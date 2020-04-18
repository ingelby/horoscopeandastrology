<?php

namespace Ingelby\HoroscopeAstrology\Api;

use common\helpers\LoggingHelper;
use Ingelby\HoroscopeAstrology\Exceptions\HoroscopeAstrologyRateLimitException;
use Ingelby\HoroscopeAstrology\Exceptions\HoroscopeAstrologyResponseException;
use ingelby\toolbox\constants\HttpStatus;
use ingelby\toolbox\services\inguzzle\exceptions\InguzzleClientException;
use ingelby\toolbox\services\inguzzle\exceptions\InguzzleInternalServerException;
use ingelby\toolbox\services\inguzzle\exceptions\InguzzleServerException;
use ingelby\toolbox\services\inguzzle\InguzzleHandler;
use yii\caching\TagDependency;
use yii\helpers\Json;

class AbstractHandler extends InguzzleHandler
{
    protected const DEFAULT_URL = 'https://horoscopes-and-astrology.com/';
    protected const CACHE_KEY = 'HOROSCOPEASTROLOGY_';
    public const CACHE_TAG_DEPENDANCY = 'HOROSCOPEASTROLOGY';

    /**
     * @var string
     */
    protected $baseUrl;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var int
     */
    protected $cacheTimeout = 3600; # cached for 60 minutes

    /**
     * AbstractHandler constructor.
     *
     * @param string      $apiKey
     * @param string|null $baseUrl
     */
    public function __construct($baseUrl = null)
    {
        $this->baseUrl = $baseUrl;

        if (null === $this->baseUrl) {
            $this->baseUrl = static::DEFAULT_URL;
        }

        parent::__construct($this->baseUrl);
    }

    /**
	 * @param string $call
     * @param array  $headers
     * @throws HoroscopeAstrologyResponseException
     */
    public function fetch(string $call)
    {
        $cacheKey = static::CACHE_KEY . $call;

        return \Yii::$app->cache->getOrSet(
            $cacheKey,
            function () use ($call) {
                try {
                    return $this->get("$call", []);
				} catch (InguzzleClientException $e) {
					LoggingHelper::logError($e);
                } catch (InguzzleInternalServerException | InguzzleServerException $e) {
					throw new HoroscopeAstrologyResponseException(
					    $e->statusCode,
                        'Error contacting Horoscope and Astrology',
                        0,
                        $e
                    );
                }
            },
            $this->cacheTimeout,
            new TagDependency(['tags' => static::CACHE_TAG_DEPENDANCY])
        );
    }

    /**
     * @param int $cahceTimeout
     */
    public function setCacheTimeout(int $cacheTimeout)
    {
        $this->cacheTimeout = $cacheTimeout;
    }
}
