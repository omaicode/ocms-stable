<?php

namespace OCMS\LaravelGoogleAnalytics\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static self setPropertyId( $propertyId = NULL)
 * @method static ?int getPropertyId()
 * @method static self setCredentials( $credentials = NULL)
 * @method static getCredentials()
 * @method static \Google\Analytics\Data\V1beta\BetaAnalyticsDataClient getClient()
 * @method static \OCMS\LaravelGoogleAnalytics\LaravelGoogleAnalyticsResponse get()
 * @method static \OCMS\LaravelGoogleAnalytics\LaravelGoogleAnalyticsResponse getRealTimeReport()
 * @method static self dateRange(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static self dateRanges(\OCMS\LaravelGoogleAnalytics\Period ...$items)
 * @method static self minuteRange(int $start, int $end = 0)
 * @method static self metric(string $name)
 * @method static self metrics(string ...$items)
 * @method static self dimension(string $name)
 * @method static self dimensions(string ...$items)
 * @method static self orderByMetric(string $name, string $order = 'ASC')
 * @method static self orderByMetricDesc(string $name)
 * @method static self orderByDimension(string $name, string $order = 'ASC')
 * @method static self orderByDimensionDesc(string $name)
 * @method static self metricAggregation(int $value)
 * @method static self metricAggregations(int ...$items)
 * @method static self whereDimension(string $name, int $matchType,  $value, bool $caseSensitive = false)
 * @method static self whereDimensionIn(string $name, array $values, bool $caseSensitive = false)
 * @method static self whereAndGroupDimensions(array $dimensions)
 * @method static self whereMetric(string $name, int $operation,  $value)
 * @method static self whereMetricBetween(string $name,  $from,  $to)
 * @method static \Google\Analytics\Data\V1beta\NumericValue getNumericObject( $value)
 * @method static self keepEmptyRows(bool $keepEmptyRows = false)
 * @method static self limit(?int $limit = NULL)
 * @method static self offset(?int $offset = NULL)
 * @method static int getTotalUsers(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getTotalUsersByDate(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getTotalUsersBySessionSource(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getMostUsersByDate(\OCMS\LaravelGoogleAnalytics\Period $period, int $count = 20)
 * @method static array getMostUsersBySessionSource(\OCMS\LaravelGoogleAnalytics\Period $period, int $count = 20)
 * @method static float getAverageSessionDuration(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getAverageSessionDurationByDate(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static int getTotalViews(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getTotalViewsByDate(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getTotalViewsByPage(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getTotalViewsByPageAndUser(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getMostViewsByPage(\OCMS\LaravelGoogleAnalytics\Period $period, int $count = 20)
 * @method static array getMostViewsByUser(\OCMS\LaravelGoogleAnalytics\Period $period, int $count = 20)
 * @method static array getTotalNewAndReturningUsers(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getTotalNewAndReturningUsersByDate(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getUsersByCountry(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getUsersByCity(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getUsersByGender(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getUsersByLanguage(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getUsersByAge(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getMostUsersByCountry(\OCMS\LaravelGoogleAnalytics\Period $period, int $count = 20)
 * @method static array getMostUsersByCity(\OCMS\LaravelGoogleAnalytics\Period $period, int $count = 20)
 * @method static array getMostUsersByLanguage(\OCMS\LaravelGoogleAnalytics\Period $period, int $count = 20)
 * @method static array getMostUsersByAge(\OCMS\LaravelGoogleAnalytics\Period $period, int $count = 20)
 * @method static array getUserByPlatform(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getUserByOperatingSystem(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getUserByBrowser(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getUserByScreenResolution(\OCMS\LaravelGoogleAnalytics\Period $period)
 * @method static array getMostUserByPlatform(\OCMS\LaravelGoogleAnalytics\Period $period, int $count = 20)
 * @method static array getMostUserByOperatingSystem(\OCMS\LaravelGoogleAnalytics\Period $period, int $count = 20)
 * @method static array getMostUserByBrowser(\OCMS\LaravelGoogleAnalytics\Period $period, int $count = 20)
 * @method static array getMostUserByScreenResolution(\OCMS\LaravelGoogleAnalytics\Period $period, int $count = 20)
 * @method static \OCMS\LaravelGoogleAnalytics\LaravelGoogleAnalyticsResponse formatResponse(\Google\Analytics\Data\V1beta\RunReportResponse $response)
 * @method static array getMetricAggregationsTable(\Google\Analytics\Data\V1beta\RunReportResponse $response)
 * @method static array getTable(\Google\Analytics\Data\V1beta\RunReportResponse $response)
 * @method static setDimensionAndMetricHeaders(\Google\Analytics\Data\V1beta\RunReportResponse $response)
 */
class LaravelGoogleAnalytics extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        self::clearResolvedInstance(\OCMS\LaravelGoogleAnalytics\LaravelGoogleAnalytics::class);

        return \OCMS\LaravelGoogleAnalytics\LaravelGoogleAnalytics::class;
    }
}
