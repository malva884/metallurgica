<?php

namespace App\Http\Middleware;

use Closure;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      // available language in template array
      $availLocale=['it'=>'it','en'=>'en', 'fr'=>'fr','de'=>'de','pt'=>'pt'];
      
      // Locale is enabled and allowed to be change
      if(session()->has('locale') && array_key_exists(session()->get('locale'),$availLocale)){
        // Set the Laravel locale
        app()->setLocale(session()->get('locale'));
      }
        $verticalMenuJson = file_get_contents(base_path('resources/data/menu-data/verticalMenu.json'));
        $verticalMenuData = json_decode($verticalMenuJson);
        $horizontalMenuJson = file_get_contents(base_path('resources/data/menu-data/horizontalMenu.json'));
        $horizontalMenuData = json_decode($horizontalMenuJson);

        // Share all menuData to all the views
        \View::share('menuData', [$verticalMenuData, $horizontalMenuData]);
        return $next($request);
    }
}