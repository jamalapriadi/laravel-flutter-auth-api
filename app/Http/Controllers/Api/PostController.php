<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Transformers\Cms\PostTransformer;
use App\Models\Cms\Post;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $model = Post::select('*')->where('visibility','publish')
            ->orderBy('created_at','desc');

        if($request->filled('q')){
            $model=$model->where('title','like','%'.request('q').'%');
        }

        if($request->filled('type'))
        {
            $model = $model->where('post_type', $request->input('type'));
        }
        
        if($request->filled('per_page')){
            $halaman=$request->input('per_page');
        }else{
            $halaman=25;
        }

        $model = $model->paginate($halaman);

        $response = fractal($model, new PostTransformer())
            ->toArray();

        return response()->json($response, 200);
    }

    public function show($id)
    {
        $model = Post::where('visibility','publish')
            ->orderBy('created_at','desc')
            ->where('slug', $id)
            ->first();

        if($model){
            $response = fractal($model, new PostTransformer())
                ->toArray();

            return response()->json($response, 200);
        }else{
            return abort(404);
        }

        
    }
}
