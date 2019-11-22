<?php
namespace Liudian\Cms\Model;

use Illuminate\Database\Eloquent\Model;

class CmsBannerPosition extends Model
{
    protected $table = 'cms_banner_positions';

    protected $fillable = [
        'name', 'size', 'status'
    ];
}