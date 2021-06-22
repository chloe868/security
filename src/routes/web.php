<?php

$route = env('PACKAGE_ROUTE', '').'/device_info/';
$controller = 'Increment\Security\Http\DeviceInfoController@';
Route::post($route.'create', $controller."createDevice");
Route::post($route.'retrieve', $controller."retrieve");
Route::post($route.'update', $controller."update");
Route::post($route.'delete', $controller."delete");
