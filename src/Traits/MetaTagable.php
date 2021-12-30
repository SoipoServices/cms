<?php

namespace SoipoServices\Cms\Traits;

use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

trait MetaTagable{

    /**
     * @property-read \Illuminate\Database\Eloquent\Collection<\SoipoServices\Cms\Models\TagTranslation> $tags
     */
    public function getMetaTagsAttribute()
    {
        $tags = [];
        $tags[] = config('soiposervices.meta_tag_keyword');
        // if ($this->tags->count() == 0) {
        //     $tags[] = config('soiposervices.meta_tag_keyword');
        // }else{
        //     foreach ($this->tags as $tag) {
        //         $tags[] = $tag->attributes["slug"];
        //     }
        // }
        $media = Media::where('model_id', $this->id)->first();
        if(!isset($media)){
            $image = config('soiposervices.meta_tag_image');
        }else{
            $image = 'storage/'.$media->id.'/'.$media->attributes["file_name"];
        }
        $metaTags = [
            'title' => is_null($this->attributes["title"])?config('soiposervices.meta_tag_name'):$this->attributes["title"],
            'description' => is_null($this->attributes["summary"])?config('soiposervices.meta_tag_description'):$this->attributes["summary"],
            'keywords' => implode(',',$tags),
            'image' => $image
        ];

        return $metaTags;
    }
}
