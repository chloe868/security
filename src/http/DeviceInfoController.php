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

    public function getByParams($column, $value){
			$result = DeviceInfo::where($column, '=', $value)->orderBy('created_at', 'asc')->limit(1)->get();
			return sizeof($result) > 0 ? $result[0] : null;
		}

    
}