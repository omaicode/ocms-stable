<?php

namespace OCMS\LaravelGoogleAnalytics\Traits;

use OCMS\LaravelGoogleAnalytics\LaravelGoogleAnalytics;
use OCMS\LaravelGoogleAnalytics\Period;
use Google\Analytics\Data\V1beta\DateRange;

trait DateRangeTrait
{
    public array $dateRanges = [];

    /**
     * Set the date Range.
     *
     * @param  Period  $period
     * @return $this
     */
    public function dateRange(Period $period): self
    {
        $this->dateRanges[] = (new DateRange())
            ->setStartDate($period->startDate->toDateString())
            ->setEndDate($period->endDate->toDateString());

        return $this;
    }

    /**
     * Set the date Ranges.
     *
     * @param  Period  ...$items
     * @return LaravelGoogleAnalytics|DateRangeTrait
     */
    public function dateRanges(Period ...$items): self
    {
        foreach ($items as $item) {
            $this->dateRange($item);
        }

        return $this;
    }
}
