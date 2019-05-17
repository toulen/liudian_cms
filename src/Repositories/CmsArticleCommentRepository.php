<?php
namespace Liudian\Cms\Repositories;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Liudian\Admin\Events\AdminLog;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Cms\Model\CmsArticle;
use Liudian\Cms\Model\CmsArticleCategory;
use Liudian\Cms\Model\CmsArticleComment;

class CmsArticleCommentRepository
{
    use CommonReturn;

    protected $cmsArticleComment;

    public function __construct(CmsArticleComment $cmsArticleComment){

        $this->cmsArticleComment = $cmsArticleComment;
    }

    /**
     * 获取文章列表
     * @return mixed
     */
    public function getLists(){

        $model = $this->cmsArticleComment->with(['parent', 'adminUser'])->where('status', '>=', 0);

        $limit = Input::get('limit', 10);

        $lists = $model->orderBy('left_key')->paginate($limit);

        return $lists;
    }

    public function reply($data){

        $param = Input::get('param', []);

        $validator = Validator::make($param, $this->rules(), $this->messages());

        if($validator->fails()){
            // 有错
            $errors = $validator->errors()->getMessages();

            if($errors){

                $errors = array_pop($errors);

                return self::returnErrorArr($errors[0]);
            }
        }

        $param['article_id'] = $data->article_id;

        $param['status'] = 1;

        $param['admin_user_id'] = \AdminAuth::user()->id;

        $reply = $this->cmsArticleComment->create($param);

        if(!$reply){
            return self::returnErrorArr('保存失败！');
        }

        // 设置下级
        $reply->makeChildOf($data);

        event(new AdminLog(CmsArticleComment::class, $reply->id, '回复评论', '回复评论（'.$reply->content.'）', $param));

        return self::returnOkArr();
    }

    public function findById($id){
        return $this->cmsArticleComment->where('status', '>=', 0)->where([
            'id' => $id
        ])->first();
    }

    public function delete($id){

        $data = $this->findById($id);

        $name = $data->content;

        $data->delete();

        event(new AdminLog(CmsArticle::class, $data->id, '删除', '删除评论（'.$name.'）'));

        return self::returnOkArr();
    }

    public function rules(){
        return [
            'content' => 'required',
        ];
    }

    public function messages(){
        return [
            'content.required' => '回复内容必填！'
        ];
    }
}