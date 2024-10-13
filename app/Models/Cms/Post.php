<?php

namespace App\Models\Cms;
use Illuminate\Support\Str;

use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{

    use Sluggable;

    protected $table="posts";

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    protected $appends = [
        'main_title',
        'main_description',
        'main_teaser',
        'link_url'
    ];

    public function getMainTitleAttribute(){
        if(session()->has('locale')){
            $locale = session()->get('locale');
        }else{
            $locale = 'ID';
        }

        if($locale == 'ID')
        {
            return $this->title;
        }else{
            $childs = \App\Models\Cms\Post::where('parent_id', $this->id)
                ->where('post_type','sublang')
                ->where('lang', $locale)
                ->first();

            if($childs)
            {
                return $childs->title;
            }else{
                return $this->title;
            }
        }

        return null;
    }

    public function getMainDescriptionAttribute(){
        if(session()->has('locale')){
            $locale = session()->get('locale');
        }else{
            $locale = 'ID';
        }

        if($locale == 'ID')
        {
            return $this->description;
        }else{
            $childs = \App\Models\Cms\Post::where('parent_id', $this->id)
                ->where('post_type','sublang')
                ->where('lang', $locale)
                ->first();

            if($childs)
            {
                return $childs->description;
            }else{
                return $this->description;
            }
        }

        return null;
    }

    public function getMainTeaserAttribute(){
        if(session()->has('locale')){
            $locale = session()->get('locale');
        }else{
            $locale = 'ID';
        }

        if($locale == 'ID')
        {
            return $this->teaser;
        }else{
            $childs = \App\Models\Cms\Post::where('parent_id', $this->id)
                ->where('post_type','sublang')
                ->where('lang', $locale)
                ->first();

            if($childs)
            {
                return $childs->teaser;
            }else{
                return $this->teaser;
            }
        }

        return null;
    }

    public function getLinkUrlAttribute(){
        if(session()->has('locale')){
            $locale = session()->get('locale');
        }else{
            $locale = 'ID';
        }

        if($locale == 'ID')
        {
            return \URL::to($this->post_type.'/'.$this->slug);
        }else{
            return \URL::to(strtolower($locale).'/'.$this->post_type.'/'.$this->slug);
        }
    }

    public function penulis()
    {
        return $this->belongsTo(\App\Models\User::class,'published_by','id')
            ->select(
                [
                    "users.id",
                    "name",
                    "slug",
                    "email",
                    "email_verified_at",
                    "gender",
                    "phone_number",
                    "birth_date",
                    "profile_picture",
                    "timezone",
                    "opt_in",
                ]
            );
    }

    public function comment()
    {
        return $this->hasMany(Comment::class,'post_id');
    }

    public function getTotalCommentsAttribute()
    {
        return $this->hasMany(Comment::class,'post_id')->count();
    }

    public function main_category(){
        $categories = $this->categories;
        $main = array();

        foreach($categories as $key=>$val)
        {
            if($key == 0)
            {
                $main = $val;
            }
        }

        return $main;
    }

    public function categories(){
        return $this->belongsToMany(\App\Models\Cms\Post::class,'post_has_category','post_id','category_id')
            ->where('post_type','post_categories');
    }

    public function artikel_categories(){
        return $this->belongsToMany(\App\Models\Cms\Post::class,'post_has_category','post_id','category_id')
            ->where('post_type','Category');
    }

    public function subcategory(){
        return $this->belongsToMany(\App\Models\Cms\Category::class,'post_has_category','post_id','category_id')
            ->select(
                [
                    'post_categories.id',
                    'category_name'
                ]
            )->where('post_categories.type','Subcategory');
    }

    public function related(){
        return $this->belongsToMany(Post::class,'post_related','post_id','related_id')
            ->withPivot(
                [
                    'post_id',
                    'related_id'
                ]
            );
    }

    public function tags(){
        return $this->belongsToMany(Tag::class,'post_tags','post_id','tag_id');
    }

    public function medias(){
        return $this->hasMany(\App\Models\Media::class,'model_id')
            ->where('model_type','Media')
            ->orderBy('created_at','desc');
    }

    public function media(){
        return $this->hasOne(\App\Models\Media::class,'path','featured_image');
    }

    public function options(){
        return $this->hasMany(PostOption::class,'post_id');
    }

    public function option_value_key($post_id, $key){
        $option = PostOption::where('post_id', $post_id)
            ->where('key', $key)
            ->first();

        if($option)
        {
            return json_decode($option->value, true);
        }else{
            return "";
        }
    }

    public function parent(){
        return $this->belongsTo(\App\Models\Cms\Post::class,'parent_id');
    }

    public function childs(){
        return $this->hasMany(\App\Models\Cms\Post::class,'parent_id')
            ->orderBy('created_at','asc');
    }

    public function with_post_type($post_id, $post_type){
        return \App\Models\Cms\Post::where('parent_id', $post_id)
            ->where('post_type',$post_type)
            ->orderBy('created_at','asc')
            ->get();
    }

    public function contents(){
        return $this->hasMany(\App\Models\Cms\Content::class,'post_id')
            ->orderBy('no_urut','asc');
    }

    public function option($slug)
    {
        $opt = \App\Models\Cms\PostOption::where('post_id', $this->id)
            ->where('key',$slug)
            ->first();

        if($opt){
            return json_decode($opt->value, true);
        }else{
            return "-";
        }
    }
}