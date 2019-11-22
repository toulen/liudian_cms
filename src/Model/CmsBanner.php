<?php
namespace Liudian\Cms\Model;

use Illuminate\Database\Eloquent\Model;

class CmsBanner extends Model
{
    protected $table = 'cms_banners';

    protected $fillable = [
        'position_id', 'image_url', 'link', 'status', 'sort_id'
    ];

    public function position(){
        return $this->hasOne(CmsBannerPosition::class, 'id', 'position_id');
    }

}