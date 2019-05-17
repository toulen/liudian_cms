<?php
namespace Liudian\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Liudian\Admin\Foundation\ControllerCURD;
use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Cms\Repositories\ArticleCategoryRepository;

class ArticleCategoryController extends Controller
{

    use ControllerFoundation, CommonReturn, ControllerCURD;

    protected $modelRepository;

    protected $pageConfig = [
        'pageTitle' => '文章分类',
        'viewPrefix' => 'cms::category',
        'indexRoute' => 'admin_cms_article_category_index'
    ];

    public function __construct(ArticleCategoryRepository $articleCategoryRepository){

        $this->modelRepository = $articleCategoryRepository;
    }

    public function beforeCreate($request){

        $categories = $this->modelRepository->getCategories();

        $this->data['categories'] = $categories;
    }

    public function beforeEdit($id, $request){

        $categories = $this->modelRepository->getCategories();

        $this->data['categories'] = $categories;
    }

    public function move($id, Request $request){

        $type = Input::get('type', 'left');

        if(!$data = $this->modelRepository->findById($id)){
            return self::returnErrorByMethod($request, '未找到分类！');
        }

        $res = $this->modelRepository->move($data, $type);

        return self::returnJson($res);
    }
}