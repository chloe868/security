<?php

namespace Increment\Security\Http;

use App\Http\Controllers\APIController;
use Increment\Finance\Models\DeviceInfo;

class DeviceInfoController extends APIController
{
    function __construct(){
        $this->model= new DeviceInfo();
    }

    
}