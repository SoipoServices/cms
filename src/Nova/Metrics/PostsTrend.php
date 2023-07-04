<?php

namespace SoipoServices\Cms\Nova\Metrics;

use Laravel\Nova\Metrics\TrendResult;
use SoipoServices\Cms\Models\Post;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Trend;

class PostsTrend extends Trend
{
    /**
     * Calculate the value of the metric.
     *
     * @param NovaRequest $request
     * @return TrendResult
     */
    public function calculate(NovaRequest $request): TrendResult
    {
        return $this->countByDays($request, Post::class)->showLatestValue();
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges(): array
    {
        return [
            7 => __('Last 7 Days'),
            1 => __('Today'),
            2 => __('Yesterday'),
            14 => __('Last 14 Days'),
            28 => __('Last 28 Days'),
            30 => __('Last 30 Days'),
            90 => __('Last 90 Days'),
            180 => __('Last 180 Days'),
            365 => __('Last Year'),
        ];
    }

    /**
     * Determine for how many minutes the metric should be cached.
     *
     * @return  \DateTimeInterface|\DateInterval|float|int
     */
    public function cacheFor(): \DateInterval|float|\DateTimeInterface|int
    {
        return now()->addMinutes(10);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey(): string
    {
        return 'posts-trend';
    }
}
