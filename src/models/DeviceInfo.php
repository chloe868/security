<?php

namespace Increment\Security\Models;

use Illuminate\Database\Eloquent\Model;
use App\APIModel;

class DeviceInfo extends APIModel
{
    protected $table = 'device_info';
    protected $fillable = ['account_id', 'model', 'unique_code', 'details', 'status'];
    
    public function getAccountIdAttribute($value){
      return intval($value);
    }
}
