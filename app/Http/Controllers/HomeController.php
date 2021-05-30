<?php

namespace App\Http\Controllers;

use App\Models\UserCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $userId = $user->id;
        $userType = $user->type;
        $userCode = UserCode::where("user_id", '=', $userId)->first();

        if ($userCode == null) {
            if($userType == 'admin'){
                $clients = DB::table('users')
                ->leftJoin('user_codes', 'users.id', '=', 'user_codes.user_id')
                ->where('users.type', '!=', 'admin')
                ->select(['users.id AS uid', 'user_codes.code', 'users.name', 'user_codes.updated_at'])
                ->get();

                $mytime = Carbon::now();
                $mytime = $mytime->toDateTimeString();

                return view("home", ["userType" => $userType, "clients" => $clients, "mytime" => $mytime]);
            }else{
                return view("home", ["userType" => $userType]);
            }
        }

        return view("home", ["code" => $userCode->code, "userType" => $userType]);
    }
}
