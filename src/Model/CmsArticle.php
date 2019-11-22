<?php
namespace Liudian\Cms\Model;

use Illuminate\Database\Eloquent\Model;

class CmsArticle extends Model
{
    protected $table = 'cms_articles';

    protected $fillable = [
        'category_id', 'title', 'intro', 'seo_keyword', 'seo_description', 'from_type', 'from_text', 'from_link', 'thumbnail', 'content', 'admin_user_id', 'status', 'hits', 'allow_comment'
    ];

    public function category(){
        return $this->hasOne(CmsArticleCategory::class, 'id', 'category_id');
    }
}