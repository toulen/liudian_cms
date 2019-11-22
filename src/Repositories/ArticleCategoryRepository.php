<?php
namespace Liudian\Cms\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Liudian\Admin\Events\AdminLog;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Cms\Model\CmsArticleCategory;

class ArticleCategoryRepository
{

    use CommonReturn;

    protected $cmsArticleCategory;

    public function __construct(CmsArticleCategory $cmsArticleCategory){

        $this->cmsArticleCategory = $cmsArticleCategory;
    }

    public function getCategories(){
        return $this->cmsArticleCategory->where([
            'status' => 1
        ])->orderBy('left_key')->get();
    }

    /**
     * 获取菜单列表
     * @return mixed
     */
    public function getLists(){

        $model = $this->cmsArticleCategory->where('status', '>=', 0);

        $param = Input::get('param', []);

        if(isset($param['name']) && trim($param['name'])){

            $model->where('name', 'like', '%'.trim($param['name']).'%');
        }

        $model->orderBy('left_key');

        $lists = $model->paginate(999999);

        return $lists;
    }

    /**
     * 新增菜单
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

        \DB::beginTransaction();

        $category = $this->cmsArticleCategory->create($param);

        if(!$category){

            \DB::rollBack();
            return self::returnErrorArr('保存失败！');
        }

        if($param['parent_id']){

            $parent = $this->findById($param['parent_id']);

            if(!$param){

                \DB::rollBack();
                return self::returnErrorArr('保存失败！');
            }

            try {
                $category->makeChildOf($parent);
            }catch (\Exception $e){}
        }

        event(new AdminLog(CmsArticleCategory::class, $category->id, '创建', '创建新文章分类（'.$category->name.'）', $param));

        \DB::commit();

        return self::returnOkArr();
    }

    public function findById($id){

        return $this->cmsArticleCategory->where('status', '>=', 0)->where([
            'id' => $id
        ])->first();
    }

    /**
     * 编辑
     * @param $id
     * @return array
     */
    public function edit($id){

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

        $data = $this->findById($id);

        // 修改
        $oldParentId = $data->parent_id;

        $data->update($param);

        if($oldParentId != $param['parent_id']){
            // 更换
            if($param['parent_id'] == 0){
                $data->makeRoot();
            }else{
                $parent = $this->cmsArticleCategory->where([
                    'status' => 1,
                    'id' => $param['parent_id']
                ])->first();

                $data->makeChildOf($parent);
            }
        }

        event(new AdminLog(CmsArticleCategory::class, $data->id, '编辑', '编辑文章分类（'.$data->name.'）', $param));

        return self::returnOkArr();

    }


    /**
     * 删除
     * @param $id
     * @return array
     */
    public function delete($id){

        $data = $this->findById($id);

        $name = $data->name;

        $data->delete();

        event(new AdminLog(CmsArticleCategory::class, $id, '删除', '删除文章分类（'.$name.'）'));

        return self::returnOkArr();
    }

    /**
     * 移动
     * @param $data
     * @param $type
     * @return array
     */
    public function move($data, $type){

        $typeName = [
            'left' => '上',
            'right' => '下'
        ];

        if(!isset($typeName[$type])){

            return self::returnErrorArr('移动失败！');
        }

        $method = 'move' . ucfirst($type);

        $data->$method();


        event(new AdminLog(CmsArticleCategory::class, $data->id, '移动', $typeName[$type] . '移动文章分类（'.$data->name.'）'));

        return self::returnOkArr();
    }

    public function rules(){

        return [
            'name' => 'required',
        ];
    }

    public function messages(){

        return [
            'name.required' => '分类名称必填！'
        ];
    }
}