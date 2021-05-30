<?php

namespace App\Http\Controllers;

use App\Models\UserProfile;
use App\Models\UserCode;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

class ezBookController extends Controller
{
    //
    public function profile()
    {
        $user = auth()->user();
        $userId = $user->id;
        $userName = $user->name;
        $userProfile = UserProfile::where("user_id", '=', $userId)->first();

        return view("profile", ["userProfile" => $userProfile, "name" => $userName]);
    }

    public function viewProfile($id)
    {
        $user = User::find($id);
        $userId = $user->id;
        $userName = $user->name;
        $userProfile = UserProfile::where("user_id", '=', $userId)->first();

        return view("viewprofile", ["userProfile" => $userProfile, "name" => $userName, "state" => "view"]);
    }

    public function saveProfile(Request $request)
    {
        $user = auth()->user();
        $userId = $user->id;
        $userProfile = UserProfile::where("user_id", '=', $userId)->first();

        if ($userProfile == null) {
            $userProfile = new UserProfile();
        }

        $userProfile->user_id = $userId;
        $userProfile->age = $request->age;
        $userProfile->birthdate = $request->birthdate;
        $userProfile->address = $request->address;
        $userProfile->phone = $request->phone;
        $userProfile->save();

        return redirect("/home");
    }

    public function deleteProfile()
    {
        $user = auth()->user();
        $userId = $user->id;
        $userProfile = UserProfile::where("user_id", '=', $userId)->first();
        $userProfile->delete();

        return redirect("/home");
    }

    public function generateCode()
    {
        $user = auth()->user();
        $userId = $user->id;
        $userCode = UserCode::where("user_id", '=', $userId)->first();
        $code = Str::random(6);

        if ($userCode == null) {
            $userCode = new UserCode();
        }

        $userCode->user_id = $userId;
        $userCode->code = $code;
        $userCode->save();

        return redirect("/home");
    }
}
