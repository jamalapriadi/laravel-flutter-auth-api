<?php

namespace App\Transformers\Cms;

use League\Fractal\TransformerAbstract;
use App\Models\Cms\Category;
use App\Models\Cms\Post;
use Illuminate\Support\Facades\URL;

class PostTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected array $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(Post $model)
    {

        return [
            'id'=>$model->id,
            'slug'=>$model->slug,
            'title'=>$model->title,
            'teaser'=>$model->teaser,
            'description'=>$model->description,
            'featured_image'=>$model->featured_image,
            'created_at_human'=>$model->created_at->diffForHumans(),
            'updated_at_human'=>$model->updated_at->diffForHumans(),
            'visibility'=>$model->visibility,
            'user_id'=>$model->user_id,
        ];
    }
}
