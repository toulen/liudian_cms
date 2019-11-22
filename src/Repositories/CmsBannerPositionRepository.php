<?php
namespace Liudian\Cms\Repositories;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Liudian\Admin\Events\AdminLog;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Cms\Model\CmsBannerPosition;

class CmsBannerPositionRepository
{
    use CommonReturn;

    protected $model;

    public function __construct(CmsBannerPosition $cmsBannerPosition){

        $this->model = $cmsBannerPosition;
    }

    /**
     * 获取Banner位列表
     * @return mixed
     */
    public function getLists(){

        $model = $this->model->where('status', '>=', 0);

        $param = Input::get('param', []);

        if(isset($param['name']) && trim($param['name'])){
            $model->where('name', 'like', '%'.trim($param['name']).'%');
        }

        $limit = Input::get('limit', 10);

        $lists = $model->paginate($limit);

        return $lists;
    }

    /**
     * 发布Banner位
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

        $data = $this->model->create($param);

        if(!$data){
            return self::returnErrorArr('保存失败！');
        }

        event(new AdminLog(CmsBannerPosition::class, $data->id, '创建', '发布新Banner位（'.$data->name.'）', $param));

        return self::returnOkArr();
    }

    public function findById($id){
        return $this->model->where('status', '>=', 0)->where([
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

        event(new AdminLog(CmsBannerPosition::class, $data->id, '修改', '修改Banner位（'.$data->name.'）', $param));

        return self::returnOkArr();
    }

    public function delete($id){

        $data = $this->findById($id);

        $data->status = -1;

        $data->save();

        event(new AdminLog(CmsBannerPosition::class, $data->id, '删除', '删除Banner位（'.$data->name.'）'));

        return self::returnOkArr();
    }

    public function rules(){
        return [
            'name' => 'required',
        ];
    }

    public function messages(){
        return [
            'name.required' => 'Banner位名称必填！',
        ];
    }
}