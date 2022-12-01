<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Array_;

class SmartBikeController extends Controller
{
    //
    public function save_HeartRateBloodOxygen(Request $request){
        $validator=Validator::make($request->toArray(),['id'=>['required'],'Calories'=>['required'],'HeartRate'=>['required'],'BloodOxygen'=>['required'],'DataTime'=>['required','date_format:Y-m-d H:i:s']]);
        if ($validator->passes()) {
            $data=DB::table('user_HeartRateBloodOxygen'.$request->id)->where('DataTime','=',$request->DataTime)->first();
            if ($data==null){
                $result=DB::insert('insert into user_HeartRateBloodOxygen' . $request->id . '(DataTime,Calories,HeartRate,BloodOxygen) values (?,?,?,?)'
                    , [ $request->DataTime, $request->Calories,$request->HeartRate,$request->BloodOxygen]);
            }else{
                $s_Calories=$data->Calories.'、'.$request->Calories;
                $s_HeartRate=$data->HeartRate.'、'.$request->HeartRate;
                $s_BloodOxygen=$data->BloodOxygen.'、'.$request->BloodOxygen;
                $result=DB::table('user_HeartRateBloodOxygen' . $request->id)->update(['Calories'=>$s_Calories,'HeartRate'=>$s_HeartRate,'BloodOxygen'=>$s_BloodOxygen]);
            }
            return $result==true?response()->json(['status' => 0, 'message' => "succeed"]):response()->json(['status' => 1, 'message' => "update data error"]);
        }else{
            return response()->json(['status' => 1, 'message' => "Missing or incorrect data"]);
        }
    }

    public function get_HeartRateBloodOxygen(Request $request){
        if ($request->Way=='order'){
            $data=DB::table('user_HeartRateBloodOxygen'.$request->id)->orderBy('DataTime','desc')->offset($request->InitialPosition)->limit($request->Quantity)->get();
            return response()->json(['status'=>0,'data'=>$data]);

        }elseif ($request->Way=='datatime'){
            $data=DB::table('user_HeartRateBloodOxygen'.$request->id)->where('DataTime','=',$request->DataTime)->get();
            return response()->json(['status'=>0,'data'=>$data]);

        }
        else{
            $data=DB::table('user_HeartRateBloodOxygen'.$request->id)->whereDate('DataTime','>=',$request->DataTime)->get();
            return response()->json(['status'=>0,'data'=>$data]);

        }
    }
    public function save_Mapchange(Request $request){
        $validator=Validator::make($request->toArray(),['id'=>['required'],'Location'=>['required'],'BikeLocation'=>['required'],'DataTime'=>['required','date_format:Y-m-d H:i:s']]);
        if ($validator->passes()) {
            $data=DB::table('user_Maphistore'.$request->id)->where('DataTime','=',$request->DataTime);
            if ($data->get()->toArray()==null) {
                $result=DB::table('user_Maphistore'.$request->id)->insert(['DataTime'=>$request->DataTime,'Location'=>$request->Location,'BikeLocation'=>$request->BikeLocation]);
            }else{
                $s_MapLocation=$data->first()->Location.'、'.$request->Location;
                $s_MapBikeLocation=$data->first()->BikeLocation.'、'.$request->BikeLocation;
                $result=$data->update(['Location'=>$s_MapLocation,'BikeLocation'=>$s_MapBikeLocation]);
            }
            return $result==true?response()->json(['status' => 0, 'message' => "succeed"]):response()->json(['status' => 1, 'message' => "update data error"]);
        }else{
            return response()->json(['status' => 1,  'message' => "Missing or incorrect data"]);
        }
    }
    public function get_Maphistore(Request $request){
        if ($request->Way=='order'){
            $data=DB::table('user_Maphistore'.$request->id)->orderBy('DataTime','desc')->offset($request->InitialPosition)->limit($request->Quantity)->get();
            return response()->json(['status'=>0,'data'=>$data]);

        }elseif ($request->Way=='datatime'){
            $data=DB::table('user_Maphistore'.$request->id)->where('DataTime','=',$request->DataTime)->get();
            return response()->json(['status'=>0,'data'=>$data]);
        }
        else{
            $data=DB::table('user_Maphistore'.$request->id)->whereDate('DataTime','>=',$request->DataTime)->get();
            return response()->json(['status'=>0,'data'=>$data]);

        }
    }
    public function save_PersonDataChange(Request $request){
        return 0;
        $validator=Validator::make($request->toArray(),[
            'id'=>['required']
        ]);
        if ($validator->passes()) {
            $data=DB::table('user_SmartBike_Personal' . $request->id)->first();
            if ($data==null){
                $result=DB::insert('insert into user_SmartBike_Personal' . $request->id . '(height,weight,birthday,gender,nowLocationLat,nowLocationLng) values (?,?,?,?,?,?)'
                    , [ $request->height, $request->weight,$request->birthday,$request->gender,$request->nowLocationLat,$request->nowLocationLng]);
            }else{
                $update_data=$request->toArray();
                unset($update_data['id']);
                $new_data=array();
                foreach ($update_data as $item){
                    array_push($new_data,[key($item)=>$item]);
                }
                $result=DB::table('user_SmartBike_Personal' . $request->id)->first()->update($new_data);
            }
            return $result==true?response()->json(['status' => 0, 'message' => "succeed"]):response()->json(['status' => 1, 'message' => "error"]);

        }else{
            return response()->json(['status' => 1, 'message' => "Missing or incorrect data"]);

        }
    }
    public function get_PersonData(Request $request){
        $validator=Validator::make($request->toArray(),['id'=>['required']]);
        if ($validator->passes()){
            $data=DB::table('user_SmartBike_Personal' . $request->id)->first();
            return $data==null?response()->json(['status' => 1, 'message' => "data is null"]):response()->json(['status' => 0, 'data'=>$data]);
        }else{
            return response()->json(['status' => 1, 'message' => "Missing or incorrect data"]);
        }
    }
    public function getData_quantity(Request $request){
        $validator=Validator::make($request->toArray(),['id'=>['required'],'tableName'=>['required']]);
        if ($validator->passes()){
            $result=DB::table($request->tableName.$request->id)->count();
            return response()->json(['status' => 0, 'message' => "success",'data'=>['quantity'=>$result]]);
        }else{
            return response()->json(['status' => 1, 'message' => "Missing or incorrect data"]);
        }
    }
}
