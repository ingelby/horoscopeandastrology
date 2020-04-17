<?php


namespace Ingelby\HoroscopeAstrology\Helpers;


use Ingelby\HoroscopeAstrology\Models\SearchMatch;
use Ingelby\HoroscopeAstrology\Models\TimeSeries;

class Select2Helper
{

    /**
     * @param SearchMatch[] $searchResults
     * @return
     */
    public static function mapSimple(array $searchResults)
    {

        $mappedValues = [];
        foreach ($searchResults as $searchResult) {
            $mappedValues[] = [
                'id'   => $searchResult->symbol,
                'text' => $searchResult->getFriendlyName(),
            ];
        }
        return $mappedValues;
    }
}
