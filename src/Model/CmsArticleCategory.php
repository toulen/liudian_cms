<?php
namespace Liudian\Cms\Model;


use Liudian\Admin\Baum\Node;

class CmsArticleCategory extends Node
{
    protected $table = 'cms_article_categories';

    protected $leftColumn = 'left_key';

    protected $rightColumn = 'right_key';

    protected $fillable = ['name', 'status'];

    protected $guarded = array('id', 'parent_id', 'left_key', 'right_key', 'depth');

    public function articles(){
        return $this->hasMany(CmsArticle::class, 'category_id', 'id');
    }
}