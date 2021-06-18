<?php

namespace Increment\Security\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use Increment\Security\Models\DeviceInfo;
use Carbon\Carbon;

class DeviceInfoController extends APIController
{
    function __construct(){
        $this->model= new DeviceInfo();
    }

    
}