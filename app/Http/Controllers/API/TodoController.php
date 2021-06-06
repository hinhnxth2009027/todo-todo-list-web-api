<?php

namespace App\Http\Controllers\API;

use App\Enum\JobStatus;
use App\Enum\Status;
use App\Http\Controllers\Controller;
use App\Models\SessionUser;
use App\Models\todoList;
use App\Models\working_day;
use Illuminate\Http\Request;
use Psy\Util\Str;

class TodoController extends Controller
{
    public function create_new_working_day(Request $request)
    {
        $token = $request->header('token');
        $checkTokenIsValid = SessionUser::where('token', $token)->first();
        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Vui lòng đăng nhập để tiếp tục'
            ], 401);
        } elseif (empty($checkTokenIsValid)) {
            return response()->json([
                'code' => 401,
                'message' => 'Phiên đã hết hạn vui lòng đăng nhập lại'
            ], 401);
        } else {
            $working = working_day::where('working_day', $request->today)->where('user_id', $checkTokenIsValid->user_id)->get();
            if (sizeof($working) == 0) {
                $new_working_day = new working_day();
                $new_working_day->working_day = $request->today;
                $new_working_day->user_id = $checkTokenIsValid->user_id;
                $new_working_day->working_day_code = \Illuminate\Support\Str::random(30);
                $new_working_day->save();
                return response()->json([
                    'code' => 201,
                    'message' => 'tạo ngày làm việc mới thành công',
                    'data' => working_day::all()
                ], 201);
            } else {
                return response()->json([
                    'code' => 200,
                    'message' => 'ngày hôm nay bạn đã tạo lịch trình làm việc rồi, hiện tại không thể tạo thêm',
                    'data' => $working
                ], 200);
            }
        }
    }
    public function create_new_job_in_date(Request $request)
    {
        $token = $request->header('token');
        $checkTokenIsValid = SessionUser::where('token', $token)->first();
        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Vui lòng đăng nhập để tiếp tục'
            ], 401);
        } elseif (empty($checkTokenIsValid)) {
            return response()->json([
                'code' => 401,
                'message' => 'Phiên đã hết hạn vui lòng đăng nhập lại'
            ], 401);
        } else {
            $check = working_day::where('working_day', $request->header('today'))->where('user_id', $checkTokenIsValid->user_id)->get();
            if (sizeof($check) <= 0) {
                $this->create_new_working_day();
                $check = working_day::where('working_day', $request->header('today'))->get();
            }
            $job = new todoList();
            $job->fill($request->all());
            $job->working_day_code = $check[0]->working_day_code;
            $job->save();
            return response([
                'code' => 200,
                'message' => 'tạo thành công một đầu việc trong ngày',
                'data' => todoList::where('working_day_code',$job->working_day_code)->get()
            ], 201);
        }
    }
    public function get_all_work_day(Request $request)
    {
        $token = $request->header('token');
        $checkTokenIsValid = SessionUser::where('token', $token)->first();
        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Vui lòng đăng nhập để tiếp tục'
            ], 401);
        } elseif (empty($checkTokenIsValid)) {
            return response()->json([
                'code' => 401,
                'message' => 'Phiên đã hết hạn vui lòng đăng nhập lại'
            ], 401);
        } else {
            $result = working_day::where('status', Status::ACTIVE)->where('user_id', $checkTokenIsValid->user_id)->get();
            return response()->json([
                'code' => 200,
                'data' => $result
            ], 200);
        }
    }




    public function get_all_job_in_day(Request $request)
    {
        $token = $request->header('token');
        $checkTokenIsValid = SessionUser::where('token', $token)->first();
        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Vui lòng đăng nhập để tiếp tục'
            ], 401);
        } elseif (empty($checkTokenIsValid)) {
            return response()->json([
                'code' => 401,
                'message' => 'Phiên đã hết hạn vui lòng đăng nhập lại'
            ], 401);
        } else {
            $todoList = todoList::where('working_day_code',$request->working_day_code)->get();
            return response()->json([
                'code' => 200,
                'data' => $todoList
            ],200);
        }
    }



    public function refreshToken($token){
        $checkTokenIsValid = SessionUser::where('token', $token)->first();
        if(!empty($checkTokenIsValid)){
            if ($checkTokenIsValid->token_time < time()){
                $checkTokenIsValid->update([
                    'token' => \Illuminate\Support\Str::random(50),
                    'refresh_token' => \Illuminate\Support\Str::random(50),
                    'token_time' => date('Y-m-d H:i:s', strtotime('+30 day')),
                    'refresh_token_time' => date('Y-m-d H:i:s', strtotime('+100 day')),
                ]);
                $token = $checkTokenIsValid->token;
            }
        }
        return $token;
    }
    public function update_NOT_COMPLETED_job_in_day(Request $request){
        $token = $request->header('token');
        $checkTokenIsValid = SessionUser::where('token', $token)->first();
        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Vui lòng đăng nhập để tiếp tục'
            ], 401);
        } elseif (empty($checkTokenIsValid)) {
            return response()->json([
                'code' => 401,
                'message' => 'Phiên đã hết hạn vui lòng đăng nhập lại'
            ], 401);
        } else {
            $job = todoList::find($request->id);
            $job->update([
                'status'=>JobStatus::NOT_COMPLETED
            ]);
            $todoList = todoList::where('working_day_code',$request->working_day_code)->get();
            return response()->json([
                'code' => 200,
                'data' => $todoList
            ],200);
        }
    }
    public function update_done_job_in_day(Request $request){
        $token = $request->header('token');
        $checkTokenIsValid = SessionUser::where('token', $token)->first();
        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Vui lòng đăng nhập để tiếp tục'
            ], 401);
        } elseif (empty($checkTokenIsValid)) {
            return response()->json([
                'code' => 401,
                'message' => 'Phiên đã hết hạn vui lòng đăng nhập lại'
            ], 401);
        } else {
            $job = todoList::find($request->id);
            $job->update([
                'status'=>JobStatus::DONE
            ]);
            $todoList = todoList::where('working_day_code',$request->working_day_code)->get();
            return response()->json([
                'code' => 200,
                'data' => $todoList
            ],200);
        }
    }






    public function delete_job_in_day(Request $request){
        $token = $request->header('token');
        $checkTokenIsValid = SessionUser::where('token', $token)->first();
        if (empty($token)) {
            return response()->json([
                'code' => 401,
                'message' => 'Vui lòng đăng nhập để tiếp tục'
            ], 401);
        } elseif (empty($checkTokenIsValid)) {
            return response()->json([
                'code' => 401,
                'message' => 'Phiên đã hết hạn vui lòng đăng nhập lại'
            ], 401);
        } else {
            $job = todoList::find($request->id);
            $job->delete();
            $todoList = todoList::where('working_day_code',$request->working_day_code)->get();
            return response()->json([
                'code' => 200,
                'data' => $todoList
            ],200);
        }
    }

}
