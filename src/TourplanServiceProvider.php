<?php
namespace Shineklbm\Tourplan;

use Illuminate\Support\ServiceProvider;

class TourplanServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'Requests/SearchHotelRequest.php' => app_path('Http\Requests\SearchHotelRequest.php'),
            __DIR__.'Requests/BookHotelRequest.php' => app_path('Http\Requests\BookHotelRequest.php'),
            __DIR__.'Requests/CancelBookingRequest.php' => app_path('Http\Requests\CancelBookingRequest.php'),
        ]);
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}