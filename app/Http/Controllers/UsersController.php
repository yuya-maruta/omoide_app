<?php

namespace App\Http\Controllers;

use App\User;
use Auth;

use Validator;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    
    public function __construct()
    {
        
        $this->middleware('auth');
    }
    
    public function show($user_id)
    {
        $user = User::where('id', $user_id)
            ->firstOrFail();
            
         
        return view('user/show', ['user' => $user]);
    }
    public function edit()
    {
        $user = Auth::user();
            
        
        return view('user/edit', ['user' => $user]);
    }
    
    public function update(Request $request)
    {
        
        $validator = Validator::make($request->all() , [
            'user_name' => 'required|string|max:255',
            'user_password' => 'required|string|min:6|confirmed',
            ]);

        
        if ($validator->fails())
        {
          return redirect()->back()->withErrors($validator->errors())->withInput();
        }
        
        $user = User::find($request->id);
        $user->name = $request->user_name;
        if ($request->user_profile_photo !=null) {
            $request->user_profile_photo->storeAs('public/user_images', $user->id . '.jpg');
            $user->profile_photo = $user->id . '.jpg';
        }
        
        $user->password = bcrypt($request->user_password);
         if ($request->user_profile_photo !=null) {
            $user->image = base64_encode(file_get_contents($request->user_profile_photo));
        }
        $user->save();

        return redirect('/users/'.$request->id);
    }
    
}

