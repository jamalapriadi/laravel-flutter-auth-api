<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\Models\User;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected array $defaultIncludes = [
        //
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
    public function transform(User $model)
    {
        return [
            'id'=>$model->id,
            'name'=>$model->name,
            'email'=>$model->email,
            'profile_picture'=>$model->profile_picture,
            'phone'=>$model->phone,
            'active'=>$model->active
        ];
    }
}
