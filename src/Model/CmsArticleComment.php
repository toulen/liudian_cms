<?php
namespace Liudian\Cms\Model;


use Liudian\Admin\Model\AdminUser;
use Liudian\Cms\Foundation\CommentNode;

class CmsArticleComment extends CommentNode
{

    protected $table = 'cms_article_comments';

    protected $leftColumn = 'left_key';

    protected $rightColumn = 'right_key';

    protected $fillable = ['article_id', 'user_id', 'content', 'status', 'praise_count', 'admin_user_id'];

    protected $guarded = array('id', 'parent_id', 'left_key', 'right_key', 'depth');

    public function parent(){
        return $this->hasOne(self::class, 'id', 'parent_id');
    }
    public function adminUser(){
        return $this->hasOne(AdminUser::class, 'id', 'admin_user_id');
    }

}