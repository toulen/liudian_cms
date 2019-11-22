<?php
namespace Liudian\Cms\Repositories;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Liudian\Admin\Events\AdminLog;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Cms\Model\CmsArticle;
use Liudian\Cms\Model\CmsArticleCategory;

class CmsArticleRepository
{
    use CommonReturn;

    protected $cmsArticle;

    public function __construct(CmsArticle $cmsArticle){

        $this->cmsArticle = $cmsArticle;
    }

    /**
     * 获取分类列表
     * @return mixed
     */
    public function getCategories(){
        $lists = CmsArticleCategory::where([
            'status' => 1
        ])->orderBy('left_key')->get();

        return $lists;
    }

    /**
     * 获取文章列表
     * @return mixed
     */
    public function getLists(){

        $model = $this->cmsArticle->with(['category'])->where('status', '>=', 0);

        $param = Input::get('param', []);

        if(isset($param['title']) && trim($param['title'])){
            $model->where('title', 'like', '%'.trim($param['title']).'%');
        }
        if(isset($param['category_id']) && $param['category_id']){
            $model->where('category_id', '=', $param['category_id']);
        }

        $limit = Input::get('limit', 10);

        $lists = $model->paginate($limit);

        return $lists;
    }

    /**
     * 发布文章
     * @return array
     */
    public function create(){

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

        $param['admin_user_id'] = \AdminAuth::user()->id;

        $article = $this->cmsArticle->create($param);

        if(!$article){
            return self::returnErrorArr('保存失败！');
        }

        event(new AdminLog(CmsArticle::class, $article->id, '创建', '发布新文章（'.$article->title.'）', $param));

        return self::returnOkArr();
    }

    public function findById($id){
        return $this->cmsArticle->where('status', '>=', 0)->where([
            'id' => $id
        ])->first();
    }

    public function edit($id){

        $data = $this->findById($id);

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

        $data->update($param);

        event(new AdminLog(CmsArticle::class, $data->id, '修改', '修改文章（'.$data->title.'）', $param));

        return self::returnOkArr();
    }

    public function delete($id){

        $data = $this->findById($id);

        $data->status = -1;

        $data->save();

        event(new AdminLog(CmsArticle::class, $data->id, '删除', '删除文章（'.$data->title.'）'));

        return self::returnOkArr();
    }

    public function rules(){
        return [
            'title' => 'required',
            'category_id' => 'required',
            'content' => 'required'
        ];
    }

    public function messages(){
        return [
            'title.required' => '文章标题必填！',
            'category_id.required' => '文章分类必选！',
            'content.required' => '文章正文必填！'
        ];
    }
}