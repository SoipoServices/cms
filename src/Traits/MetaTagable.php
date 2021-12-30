<?php

namespace SoipoServices\Cms\Traits;

use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait MetaTagable {

    public function getMetaTagsAttribute()
    {
        $tags = [];
        if (is_null($this->tags->first())) {
            $tags[] = config('soiposervices.meta_tag_keyword');
        }else{
            foreach ($this->tags as $tag) {
                $tags[] = $tag->slug;
            }
        }
        $media = Media::where('model_id', $this->id)->first();
        if(!isset($media)){
            $image = config('soiposervices.meta_tag_image');
        }else{
            $image = 'storage/'.$media->id.'/'.$media->file_name;
        }
        $metaTags = [
            'title' => is_null($this->title)?config('soiposervices.meta_tag_name'):$this->title,
            'description' => is_null($this->summary)?config('soiposervices.meta_tag_description'):$this->summary,
            'keywords' => implode(',',$tags),
            'image' => $image
        ];

        return $metaTags;
    }
}
