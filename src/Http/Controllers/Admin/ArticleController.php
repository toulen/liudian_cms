<?php
namespace Liudian\Cms\Http\Controllers\Admin;

use Liudian\Admin\Foundation\ControllerCURD;
use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Cms\Repositories\CmsArticleRepository;

class ArticleController extends Controller
{

    use CommonReturn, ControllerFoundation, ControllerCURD;

    protected $pageConfig = [
        'pageTitle' => '文章',
        'viewPrefix' => 'cms::article',
        'indexRoute' => 'admin_cms_article_index'
    ];

    protected $modelRepository;

    public function __construct(CmsArticleRepository $cmsArticleRepository){

        $this->modelRepository = $cmsArticleRepository;
    }

    public function beforeIndex(){
        $this->data['categories'] = $this->modelRepository->getCategories();
    }
    public function beforeCreate($request){
        $this->data['categories'] = $this->modelRepository->getCategories();
    }
    public function beforeEdit($id, $request){
        $this->data['categories'] = $this->modelRepository->getCategories();
    }

    public function show($id){
        $data = $this->modelRepository->findById($id);

        $this->data['layout'] = false;

        $this->data['pageTitle'] = '预览文章';

        $this->data['data'] = $data;

        return $this->render($this->pageConfig['viewPrefix'] . '.show');
    }
}