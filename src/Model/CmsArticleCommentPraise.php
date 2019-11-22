<?php
namespace Liudian\Cms\Model;

use Illuminate\Database\Eloquent\Model;

class CmsArticleCommentPraise extends Model
{

    const UPDATED_AT = null;

    protected $table = 'cms_article_comment_praises';

    protected $fillable = [
        'comment_id', 'user_id', 'user_client_ip'
    ];
}