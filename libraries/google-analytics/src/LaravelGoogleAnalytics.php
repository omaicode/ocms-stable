<?php

namespace OCMS\LaravelGoogleAnalytics;

use OCMS\LaravelGoogleAnalytics\Traits\CustomAcquisitionTrait;
use OCMS\LaravelGoogleAnalytics\Traits\CustomDemographicsTrait;
use OCMS\LaravelGoogleAnalytics\Traits\CustomEngagementTrait;
use OCMS\LaravelGoogleAnalytics\Traits\CustomRetentionTrait;
use OCMS\LaravelGoogleAnalytics\Traits\CustomTechTrait;
use OCMS\LaravelGoogleAnalytics\Traits\DateRangeTrait;
use OCMS\LaravelGoogleAnalytics\Traits\DimensionTrait;
use OCMS\LaravelGoogleAnalytics\Traits\FilterByDimensionTrait;
use OCMS\LaravelGoogleAnalytics\Traits\FilterByMetricTrait;
use OCMS\LaravelGoogleAnalytics\Traits\MetricAggregationTrait;
use OCMS\LaravelGoogleAnalytics\Traits\MetricTrait;
use OCMS\LaravelGoogleAnalytics\Traits\MinuteRangeTrait;
use OCMS\LaravelGoogleAnalytics\Traits\OrderByDimensionTrait;
use OCMS\LaravelGoogleAnalytics\Traits\OrderByMetricTrait;
use OCMS\LaravelGoogleAnalytics\Traits\ResponseTrait;
use OCMS\LaravelGoogleAnalytics\Traits\RowOperationTrait;
use Google\Analytics\Data\V1beta\BetaAnalyticsDataClient;
use Google\ApiCore\ApiException;
use Google\ApiCore\ValidationException;

class LaravelGoogleAnalytics
{
    use DateRangeTrait;
    use MetricTrait;
    use DimensionTrait;
    use OrderByMetricTrait;
    use OrderByDimensionTrait;
    use MetricAggregationTrait;
    use FilterByDimensionTrait;
    use FilterByMetricTrait;
    use RowOperationTrait;
    use CustomAcquisitionTrait;
    use CustomEngagementTrait;
    use CustomRetentionTrait;
    use CustomDemographicsTrait;
    use CustomTechTrait;
    use ResponseTrait;
    use MinuteRangeTrait;
    public ?int $propertyId = null;
    public $credentials = null;
    public array $orderBys = [];

    /**
     * Set the property id.
     *
     * @param  int|null  $propertyId
     * @return $this
     */
    public function setPropertyId($propertyId = null): self
    {
        $this->propertyId = $propertyId ?? config('analytics.view_id');

        return $this;
    }

    /**
     * Get the property id.
     *
     * @return int|null
     */
    public function getPropertyId(): ?int
    {
        if (! $this->propertyId) {
            $this->setPropertyId();
        }

        return $this->propertyId;
    }

    /**
     * Set the credentials.
     *
     * @param  null  $credentials
     * @return $this
     */
    public function setCredentials($credentials = null): self
    {
        $this->credentials = $credentials ?? config('analytics.service_account_credentials_json');

        return $this;
    }

    /**
     * Get the credentials.
     *
     * @return mixed
     */
    public function getCredentials()
    {
        if (! $this->credentials) {
            $this->setCredentials();
        }

        return $this->credentials;
    }

    /**
     * Get the client.
     *
     * @return BetaAnalyticsDataClient
     *
     * @throws \Google\ApiCore\ValidationException
     */
    public function getClient(): BetaAnalyticsDataClient
    {
        return new BetaAnalyticsDataClient([
            'credentials' => $this->getCredentials(),
        ]);
    }

    /**
     * Get the result from the GA4 query explorer.
     *
     * @return LaravelGoogleAnalyticsResponse
     *
     * @throws \Google\ApiCore\ApiException|\Google\ApiCore\ValidationException
     */
    public function get(): LaravelGoogleAnalyticsResponse
    {
        $response = $this->getClient()->runReport([
            'property' => "properties/{$this->getPropertyId()}",
            'dateRanges' => $this->dateRanges,
            'metrics' => $this->metrics,
            'dimensions' => $this->dimensions,
            'orderBys' => $this->orderBys,
            'metricAggregations' => $this->metricAggregations,
            'dimensionFilter' => $this->dimensionFilter,
            'metricFilter' => $this->metricFilter,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'keepEmptyRows' => $this->keepEmptyRows,
        ]);

        return $this->formatResponse($response);
    }

    /**
     * @throws ValidationException
     * @throws ApiException
     */
    public function getRealTimeReport(): LaravelGoogleAnalyticsResponse
    {
        $response = $this->getClient()->runRealtimeReport([
            'property' => "properties/{$this->getPropertyId()}",
            'minuteRanges' => $this->minuteRanges,
            'metrics' => $this->metrics,
            'dimensions' => $this->dimensions,
            'orderBys' => $this->orderBys,
            'metricAggregations' => $this->metricAggregations,
            'dimensionFilter' => $this->dimensionFilter,
            'metricFilter' => $this->metricFilter,
            'limit' => $this->limit,
            'offset' => $this->offset,
            'keepEmptyRows' => $this->keepEmptyRows,
        ]);

        return $this->formatResponse($response);
    }
}
