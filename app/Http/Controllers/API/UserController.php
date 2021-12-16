<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        return $this->middleware("auth:api");
    }
    public function index()
    {
        $users = User::all();
        return $users;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|max:40|string", 
            "email" =>"required|email|unique:users|max:80",
            "bio" => "",
            // "password" => "required|min:8|string",
            "type" => "required",
            // "photo" => "required|image|max:2048,mimes:jpg,jpeg,png,svg"
        ]);
        $data = User::create([
            "name" => $request["name"],
            "email" => $request["email"],
            "bio" => $request["bio"],
            // "password" => Hash::make($request["password"]),
            "type" => $request["type"],
        ]); 
        // return $request->all();
        return $data;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::FindorFail($id);
        return $user;
    }
    public function profile()
    {
        return auth("api")->user();
    }
    public function updateprofile(Request $request)
    {
        $user = auth("api")->user();
        $request->validate([
            "name" => "required|max:40|string",
            "email" =>"required|email|max:80|unique:users,email,".$user->id,
            // "photo" => "required|image|mimes:jpg,jpeg,png,gif"
        ]);
        $currentPhoto = $user->photo;
        if($request->photo != $currentPhoto):
            $file = md5(microtime()).'.' . explode('/', explode(':', substr($request->photo, 0,
            strpos($request->photo,';')))[1])[1];

            $request->validate([
                "photo" => "required|image|mimes:jpg,jpeg,png,gif"
            ]);

            

            if($request["photo"] != NULL || $request["photo"] != ""):
                $path = public_path("images/uploads/profile/");
                $old = $path.$user->photo;
                if($user->photo != "default.png"):
                    unlink($old);
                endif;
            endif;
            $request->merge(["photo" => $file]);
        else:
            $user->photo = $user->photo;
        endif;

        if(!empty($request->password)):
            $request->validate([
                "password" => "min:8|string",
            ]);
             $request->merge(["password" => Hash::make($request["password"])]);
        endif;

        $user->update($request->all());
        return $user;

        // $request->validate([
        //     "name" => "required|max:40|string",
        //     "email" =>"required|email|max:80",
        //     "bio" => "",
        // ]);

        // if($request['photo']):
        //     $request->validate([
        //         "photo" => "required|max:2048|image|mimes:jpg,jpeg,png",
        //     ]);

        //     $file = $request['photo'];
        //     $image = md5(microtime()).".".$file->getClientOriginalExtension();
        //     $file->move(public_path("./images/uploads/profile"),$image);
        // else:
        //     $image = "default.png";
        // endif;
        
        // $user->update([
        //     "name" => $request["name"],
        //     "email" => $request["email"],
        //     "bio" => $request["bio"],
        //     "photo" => $image
        // ]);
        // return $user;

        
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::FindorFail($id);
        $request->validate([
            "name" => "required|max:40|string",
            "email" =>"required|email|max:80",
            "bio" => "",
            // "password" => "min:8|string",
            "type" => "required",
        ]);
        $user->update([
            "name" => $request["name"],
            "email" => $request["email"],
            "bio" => $request["bio"],
            // "password" => Hash::make($request["password"]),
            "type" => $request["type"],
        ]);
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::FindOrFail($id);
        $user->delete();
        // return ["message"] =>"Delete";
        return $user;
    }
}
