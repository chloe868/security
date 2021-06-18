<?php

$route = env('PACKAGE_ROUTE', '').'/device_info/';
$controller = 'Increment\Payment\Http\DeviceInfoController@';
Route::post($route.'create', $controller."create");
Route::post($route.'retrieve', $controller."retrieve");
Route::post($route.'update', $controller."update");
Route::post($route.'delete', $controller."delete");
