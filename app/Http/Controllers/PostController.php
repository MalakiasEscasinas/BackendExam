<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\JWTAuth;
use App\Post;
class PostController extends Controller
{
/**
 * @var \Tymon\JWTAuth\JWTAuth
 */
    protected $jwt;

    public function __construct(JWTAuth $jwt)
    {
        $this->jwt = $jwt;
    }



     public function createpost(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
			'slug' => '',
            'content' => 'required'
        ]);

        try {

            $post = new Post;
            $post->title = $request->input('title');
            $post->slug = $request->input('slug');
			$post->content = $request->input('content');
       

            $post->save();

            //return successful response
            return response()->json(['post' => $post, 'message' => 'Post Created'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'The given data was invalid!'], 400);
        }

    }

	
    

}