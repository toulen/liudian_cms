<?php
namespace Liudian\Cms\Repositories;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Liudian\Admin\Events\AdminLog;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Cms\Model\CmsBanner;
use Liudian\Cms\Model\CmsBannerPosition;

class CmsBannerRepository
{
    use CommonReturn;

    protected $model;

    public function __construct(CmsBanner $cmsBanner){

        $this->model = $cmsBanner;
    }

    /**
     * 获取Banner列表
     * @return mixed
     */
    public function getLists(){

        $model = $this->model->with(['position'])->where('status', '>=', 0);

        $param = Input::get('param', []);

        if(isset($param['position_id']) && $param['position_id']){

            $model->where('position_id', '=', $param['position_id']);
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

        event(new AdminLog(CmsBanner::class, $data->id, '创建', '新增Banner图片（'.$data->image_url.'）', $param));

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

        event(new AdminLog(CmsBanner::class, $data->id, '修改', '修改Banner图片（'.$data->image_url.'）', $param));

        return self::returnOkArr();
    }

    public function delete($id){

        $data = $this->findById($id);

        $data->status = -1;

        $data->save();

        event(new AdminLog(CmsBanner::class, $data->id, '删除', '删除Banner图片（'.$data->image_url.'）'));

        return self::returnOkArr();
    }

    public function rules(){
        return [
            'image_url' => 'required',
        ];
    }

    public function messages(){
        return [
            'image_url.required' => 'Banner图片必填！',
        ];
    }
}