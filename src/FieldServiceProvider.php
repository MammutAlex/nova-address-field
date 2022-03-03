<?php

namespace DigitalCloud\AddressField;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\ServiceProvider;

class FieldServiceProvider extends ServiceProvider
{
  /**
   * Bootstrap any application services.
   *
   * @return void
   */
  public function boot()
  {
    Nova::serving(function (ServingNova $event) {
      $key = Config::get('services.google_maps.key');
      $locale = App::getLocale();
      Nova::script('google-maps', "https://maps.googleapis.com/maps/api/js?key={$key}&libraries=places&language={$locale}");
      Nova::script('address-field', __DIR__ . '/../dist/js/field.js');
      Nova::style('address-field', __DIR__ . '/../dist/css/field.css');
    });
  }

  /**
   * Register any application services.
   *
   * @return void
   */
  public function register()
  {
    //
  }
}
