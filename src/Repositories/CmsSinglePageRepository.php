<?php
namespace Liudian\Cms\Repositories;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Liudian\Admin\Events\AdminLog;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Cms\Model\CmsSinglePage;

class CmsSinglePageRepository
{
    use CommonReturn;

    protected $cmsSinglePage;

    public function __construct(CmsSinglePage $cmsSinglePage){

        $this->cmsSinglePage = $cmsSinglePage;
    }

    /**
     * 获取单页列表
     * @return mixed
     */
    public function getLists(){

        $model = $this->cmsSinglePage->where('status', '>=', 0);

        $param = Input::get('param', []);

        if(isset($param['title']) && trim($param['title'])){
            $model->where('title', 'like', '%'.trim($param['title']).'%');
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

        $singlePage = $this->cmsSinglePage->create($param);

        if(!$singlePage){
            return self::returnErrorArr('保存失败！');
        }

        event(new AdminLog(CmsSinglePage::class, $singlePage->id, '创建', '发布新单页面（'.$singlePage->title.'）', $param));

        return self::returnOkArr();
    }

    public function findById($id){
        return $this->cmsSinglePage->where('status', '>=', 0)->where([
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

        event(new AdminLog(CmsSinglePage::class, $data->id, '修改', '修改单页面（'.$data->title.'）', $param));

        return self::returnOkArr();
    }

    public function delete($id){

        $data = $this->findById($id);

        $data->status = -1;

        $data->save();

        event(new AdminLog(CmsSinglePage::class, $data->id, '删除', '删除单页面（'.$data->title.'）'));

        return self::returnOkArr();
    }

    public function rules(){
        return [
            'title' => 'required',
            'content' => 'required'
        ];
    }

    public function messages(){
        return [
            'title.required' => '单页面标题必填！',
            'content.required' => '单页面正文必填！'
        ];
    }
}