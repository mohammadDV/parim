<?php
namespace App\Http\Controllers\Api;

use App\Department;
use App\Event;
use App\Http\Requests\Api\SaveRequest;
use App\Http\Requests\Api\SearchRequest;
use App\Shift;
use App\Shift_Department;
use System\Database\DBBuilder\DBBuilder;
use App\Http\Controllers\Controller;
use System\Database\DBConnection\Connection;
use System\Lib\Exp;
use System\Lib\JalaliDate;
use System\Config\Config;

class ApiController extends Controller {

    public function __construct()
    {
        header('Content-Type: application/json');

//        if(clearStr($_REQUEST["code"]) != md5(clearStr($_REQUEST["deviceID"]). Config::get("app.APP_KEY"))){
//            http_response_code(404);
//            exit("Invalid Request");
//        }
    }

    public function save()
    {
        Connection::beginTransaction();
        try {
            //! Input validation
            $validation     = new SaveRequest(true);
            $request        = $validation->all();

            //! insert event to DB
            if (!empty($request["event_name"]) && !empty($request["event_start"]) && !empty($request["event_end"])){

                $event_row     = Event::where("name",trim($request["event_name"]))->where("start",strtotime($request["event_start"]))->where("end",strtotime($request["event_end"]))->first();
                if (!empty($event_row->name)){
                    $dataEvent = $event_row;
                }else{
                    $dataEvent = Event::create([
                        "name"          => $request["event_name"],
                        "start"         => strtotime($request["event_start"]),
                        "end"           => strtotime($request["event_end"]),
                    ]);
                }

                if (!$dataEvent){
                    jsonResponse(2,__tr("The system has a problem. Please try again"));
                }
            }

            //! insert shift to DB
            $dataShift = Shift::create([
                "user_name"     => $request["username"],
                "user_email"    => $request["email"],
                "location"      => $request["location"],
                "type"          => $request["type"],
                "rate"          => $request["rate"],
                "charge"        => $request["charge"],
                "area"          => $request["area"],
                "event_id"      => !empty($dataEvent->id) ? $dataEvent->id : null,
                "start"         => strtotime($request["start"]),
                "end"           => strtotime($request["end"]),
            ]);

            if (!empty($request["department"])){
                foreach ($request["department"] as $department) {
                    // checking for exist department or not
                    $department_row     = Department::where("title",trim($department))->first();
                    if (!empty($department_row->title)){
                        $dataDepartment = $department_row;
                    }else{
                        // insert departments
                        $dataDepartment = Department::create([
                            "title"         => $department,
                        ]);
                    }

                    if ($dataDepartment) {
                        // insert pivot table
                        $dataPivot = Shift_Department::create([
                            "shift_id"         => $dataShift->id,
                            "department_id"    => $dataDepartment->id
                        ]);
                        if (!$dataPivot){
                            jsonResponse(2,__tr("The system has a problem. Please try again"));
                        }
                    }else{
                        jsonResponse(2,__tr("The system has a problem. Please try again"));
                    }
                }
            }
            //! insert shift to DB

            if ($dataShift){
                Connection::commit();
                jsonResponse(1,__tr("Your registration was successful"));
            }else{
                jsonResponse(2,__tr("The system has a problem. Please try again"));
            }
        }catch (\Exception $e){
            Connection::rollback();
            new Exp($e->getMessage(),$e->getCode());
            jsonResponse(3,__tr($e->getMessage()));
        }
    }

    public function index()
    {
        try {

            $validation     = new SearchRequest(true);
            $request        = $validation->all();

            if (!empty($request["location"]) && !empty($request["start"]) && !empty($request["end"])){
                $shifts     = Shift::where("location","like","%{$request["location"]}%")->where("start",">",strtotime($request["start"]))->where("end","<",strtotime($request["end"]))->paginate(2);
            }else{
                $shifts     = Shift::paginate(10);
            }

            $data       = [];
            $lib_date   = new JalaliDate("Canada/Atlantic");
            foreach ($shifts as $key => $shift) {
                $data[$key]["user_name"]    = $shift->user_name;
                $data[$key]["user_email"]   = $shift->user_email;
                $data[$key]["type"]         = $shift->type;
                $data[$key]["location"]     = $shift->location;
                $data[$key]["area"]         = $shift->area;
                $data[$key]["rate"]         = $shift->rate;
                $data[$key]["charge"]       = $shift->charge;
                $data[$key]["start"]        = $lib_date->date("c","en",$shift->start);
                $data[$key]["end"]          = $lib_date->date('c',"en",$shift->end);
                $data[$key]["event"]        = null;
                $data[$key]["departments"]  = null;
                if (!empty($shift->event())){
                    $data[$key]["event"]["name"]    = $shift->event()->name;
                    $data[$key]["event"]["start"]   = $lib_date->date("c","en",$shift->event()->start);
                    $data[$key]["event"]["end"]     = $lib_date->date("c","en",$shift->event()->end);
                }
                if (!empty($shift->departments()->get())){
                    foreach ($shift->departments()->get() as $department) {
                        $data[$key]["departments"][] = $department->title;
                    }
                }
            }
            jsonResponse($data);
        }catch (\Exception $e){
            new Exp($e->getMessage(),$e->getCode());
            jsonResponse(3,$e->getMessage());
//            jsonResponse(3,__tr("The system has a problem. Please try again"));
        }
    }

    public function check($token)
    {
        try {
            if (empty($token)){
                jsonResponse(0,__tr("Unfortunately, you don't have active purchase now"));
            }

            //! checking this user has active purchase or not AND expired another purchase
            $purchase  = $this->check_purchase($token);

            if ($purchase){
                $Lib_date   = new JalaliDate("America/Chicago");
                $response = [
                    "status"        => $purchase->status,
                    "expire_date"   => $Lib_date->date("Y-m-d H:i:s","en",$purchase->expire_date),
                    "receipt"       => $purchase->receipt_code,
                ];
                jsonResponse($response,__tr("Unfortunately, you don't have active purchase now"));
            }
            jsonResponse(0,__tr("Unfortunately, you don't have active purchase now"));
        }catch (\Exception $e){
            new Exp($e->getMessage(),$e->getCode());
            jsonResponse(3,__tr("The system has a problem. Please try again"));
        }
    }

    public function execute()
    {
        //! run migration
        $tables = new DBBuilder();
    }

    public function reset()
    {
        //! empty database
        $tables = new DBBuilder(true);
    }
}