<?php
namespace Liudian\Cms\Model;

use Illuminate\Database\Eloquent\Model;

class CmsSinglePage extends Model
{
    protected $table = 'cms_single_pages';

    protected $fillable = [
        'title', 'seo_keyword', 'seo_description', 'content', 'status'
    ];
}