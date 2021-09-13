<?php

namespace Increment\Security\Http;

use Illuminate\Http\Request;
use App\Http\Controllers\APIController;
use Increment\Security\Models\DeviceInfo;
use Carbon\Carbon;

class DeviceInfoController extends APIController
{
	public $notificationClass = 'Increment\Common\Notification\Http\NotificationController';
	public $firebaseController = '\App\Http\Controllers\FirebaseController';
	function __construct(){
			$this->model= new DeviceInfo();
	}

	public function getByParams($column, $value){
		$result = DeviceInfo::where($column, '=', $value)->orderBy('created_at', 'asc')->limit(1)->get();
		return sizeof($result) > 0 ? $result[0] : null;
	}

	public function createDevice(Request $request){
		$data = $request->all();
		$account_id = $data['account_id'];
		$model = $data['model'];
		$unique_code = $data['unique_code'];
		$details = $data['details'];
		$status = $data['status'];
		
		if($status == 'secondary'){
			$result = $this->newEntryDevice(array(
				'account_id' => $account_id,
				'model' => $model,
				'unique_code' => $unique_code,
				'details' => $details,
				'status' => $status
			),
			$flag = true
			);
			if($result['error'] != null){
				$this->response['data'] = $result['data'];
				return $this->response();
			}
			
		}else{
			$this->model = new DeviceInfo();
			$params = array(
				'account_id' => $account_id,
				'model' => $model,
				'unique_code' => $unique_code,
				'details' => $details,
				'status' => $status
			);
			$this->insertDB($params);
		}
		return $this->response();
	}

	public function newEntryDevice($data, $flag){
		$entry = array();
		$entry["account_id"] = $data["account_id"];
		$entry["model"] = $data["model"];
		$entry["unique_code"] = $data["unique_code"];
		$entry["details"] = $data["details"];
		$entry["status"] = $data["status"];
		$this->model = new DeviceInfo();
		$this->insertDB($entry);
		$primary = $this->retrievePrimary($data["account_id"]);
		if($this->response['data'] > 0){
			if($flag == true){
				// send email function here
			}
			// run jobs here
			$parameter = array(
				'from'    => $data['account_id'],
				'to'      => $data['account_id'],
				'payload' => 'device',
				'payload_value' => $entry["unique_code"],
				'route'   => 'device_info/'.$entry["details"],
				'created_at'  => Carbon::now()
			);
			app($this->notificationClass)->createByParamsByDevice($parameter, $primary['unique_code']);
		}
		return array(
			'data' => $this->response['data'],
			'error' => null
		);
	}

	public function retrieve(Request $request){
		$data = $request->all();
		$this->model = new DeviceInfo();
		$this->retrieveDB($data);
		$result = $this->response['data'];
		if(sizeof($result) > 0){
			$i = 0;
			foreach ($result as $key) {
			  $result[$i]['details'] = json_decode($result[$i]['details']);
			  $i++;
			}
			$this->response['data'] = $result;
		  }
		return $this->response();
	}

	public function retrievePrimary($account_id){
		$var = DeviceInfo::where('account_id', '=', $account_id)->where('status', '=', 'primary')->get();
		return sizeof($var) > 0 ? $var[0] : null;
	}

	public function update(Request $request){
		$data = $request->all();
		$on = DeviceInfo::where('id', '=', $data['id'])->update(array(
			'status' => $data['status'],
			'updated_at' => Carbon::now()
		));
		$off = DeviceInfo::where('id', '!=', $data['id'])->update(array(
			'status' => 'secondary',
			'updated_at' => Carbon::now()
		));

		$this->response['data'] = $on;

		return $this->response();
	}
}
