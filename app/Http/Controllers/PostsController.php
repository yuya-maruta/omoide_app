<?php

namespace App\Http\Controllers;

use App\Post;
use Auth;
use Validator;

use Illuminate\Http\Request;

class PostsController extends Controller
{
   public function __construct()
    {
        $this->middleware('auth');
    }
    
      public function index()
    {
        $posts = Post::limit(10)
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('post/index', ['posts' => $posts]);
    }
    
   
    public function new()
    {

        return view('post/new');
        
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all() , ['caption' => 'required|max:255', 'photo' => 'required']);


        if ($validator->fails())
        {
            return redirect()->back()->withErrors($validator->errors())->withInput();
        }


        $post = new Post;
        $post->caption = $request->caption;
        $post->user_id = Auth::user()->id;

        $post->save();
        
        $request->photo->storeAs('public/post_images', $post->id . '.jpg');
        

        return redirect('/');
    }

   public function destroy($post_id)
    {
        $post = Post::find($post_id);
        $post->delete();
        return redirect('/');
    }
  
}
