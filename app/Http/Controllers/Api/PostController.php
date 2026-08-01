<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Post::with('user');

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if(in_array($sortBy,['created_at', 'title'])){   
           $query->orderBy($sortBy,$sortOrder);
        }

        if ($request->filled('date_from')){
            $query->whereDate('created_at','>=', $request->date_from);
        }

        if ($request->filled('date_to')){
            $query->whereDate('created_at','<=', $request->date_to);
        }

        $limit = (int) $request->get('limit',10);
        $offset = (int) $request->get('offset',0);

        $total = $query->count();
        $posts = $query->skip($offset)->take($limit)->get();

        return response()->json([
            'data'=> $posts->map(function($post){
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'text' => $post->text,
                    'author' => $post->user->name,
                    'created_at' => $post->created_at->toISOString(),
                ];       
            }),
            'meta' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,                    
            ],
        ]);           
    }

    public function create(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title'=> 'required|string|max:255',
            'text'=> 'required|string',
        ]); 

        $post = Post::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'text' => $validated['text'],
        ]);

        return response()->json([
            'message' => 'Пост создан успешно',
            'post' => [
                'id' => $post->id,
                'title' => $post->title,
                'text' => $post->text,
                'created_at' => $post->created_at->toISOString()
            ],

        ],201);
    }

    public function getMyPosts(Request $request): JsonResponse
    {

        $query = Post::where('user_id',$request->user()->id);

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

          if(in_array($sortBy,['created_at', 'title'])){   
           $query->orderBy($sortBy,$sortOrder);
        }

        if ($request->filled('date_from')){
            $query->whereDate('created_at','>=', $request->date_from);
        }

        if ($request->filled('date_to')){
            $query->whereDate('created_at','<=', $request->date_to);
        }

        $limit = (int) $request->get('limit',10);
        $offset = (int) $request->get('offset',0);

        $total = $query->count();
        $posts = $query->skip($offset)->take($limit)->get();

        return response()->json([
            'data'=> $posts->map(function($post){
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'text' => $post->text,
                    'author' => $post->user->name,
                    'created_at' => $post->created_at->toISOString(),
                ];       
            }),
            'meta' => [
                'total' => $total,
                'limit' => $limit,
                'offset' => $offset,                    
            ],
        ]);           

    }





}

 
