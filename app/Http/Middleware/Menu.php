<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Menu
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (!$user)
            return $next($request);

        $verticalMenuJson = file_get_contents(base_path('resources/data/menu-data/verticalMenu.json'));
        $verticalMenuData = json_decode($verticalMenuJson);
        $horizontalMenuJson = file_get_contents(base_path('resources/data/menu-data/horizontalMenu.json'));
        $horizontalMenuData = json_decode($horizontalMenuJson);


        foreach ($horizontalMenuData as $keyUno => $menuData) {
            foreach ($menuData as $keyDue => $menu) {
                if (!is_array($menu->permission))
                    $menu->permission = [$menu->permission];
                if (!is_array($menu->role))
                    $menu->role = [$menu->role];

                if (($menu->permission[0] == '*' && $user->hasRole($menu->role)) ||
                    ($user->hasAnyPermission($menu->permission) && $user->hasRole($menu->role)) ||
                    $user->hasRole('super-admin')) {
                    if (!empty($menu->submenu)) {
                        foreach ($menu->submenu as $keyS => $smenu) {
                            if (!is_array($smenu->permission))
                                $smenu->permission = [$smenu->permission];
                            if (!is_array($smenu->role))
                                $smenu->role = [$smenu->role];

                            if (($smenu->permission[0] == '*' && $user->hasRole($smenu->role)) ||
                                ($user->hasAnyPermission($smenu->permission) && $user->hasRole($smenu->role)) ||
                                $user->hasRole('super-admin')) {

                                if (!empty($smenu->submenu)) {
                                    foreach ($smenu->submenu as $keySS => $ssmenu) {
                                        if (!is_array($ssmenu->permission))
                                            $ssmenu->permission = [$ssmenu->permission];
                                        if (!is_array($ssmenu->role))
                                            $ssmenu->role = [$ssmenu->role];
                                        if (($ssmenu->permission[0] == '*' && $user->hasRole($ssmenu->role)) ||
                                            ($user->hasAnyPermission($ssmenu->permission) && $user->hasRole($ssmenu->role)) ||
                                            $user->hasRole('super-admin')) {
                                            if (!empty($ssmenu->submenu)) {

                                            }
                                        } else {
                                            unset($horizontalMenuData->$keyUno[$keyDue]->submenu[$keyS]->submenu[$keySS]);
                                        }

                                    }
                                }
                            } else {
                                unset($horizontalMenuData->$keyUno[$keyDue]->submenu[$keyS]);
                            }

                        }
                    }
                } else {
                    //dd($menu);
                    unset($horizontalMenuData->$keyUno[$keyDue]);
                }

                //dd($menu->permission);
            }

        }
        // Share all menuData to all the views
        \View::share('menuData', [$verticalMenuData, $horizontalMenuData]);

        return $next($request);
    }


}
