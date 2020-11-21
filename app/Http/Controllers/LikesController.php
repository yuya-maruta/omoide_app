<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Like;

use App\Post;
use Auth;
use Validator;


class LikesController extends Controller
{
    
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request)
    {
    
        $like = new Like;
        $like->post_id = $request->post_id;
        $like->user_id = Auth::user()->id;
        $like->save();

        
        return redirect('/');
    }
    
    public function destroy(Request $request)
    {
        $like = Like::find($request->like_id);
        $like->delete();
        return redirect('/');
    }
    
}
