<?php
namespace Liudian\Cms\Http\Controllers\Admin;

use Liudian\Admin\Foundation\ControllerCURD;
use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Cms\Repositories\CmsSinglePageRepository;

class SinglePageController extends Controller
{

    use CommonReturn, ControllerFoundation, ControllerCURD;

    protected $pageConfig = [
        'pageTitle' => '单页面',
        'viewPrefix' => 'cms::single',
        'indexRoute' => 'admin_cms_single_index'
    ];

    protected $modelRepository;

    public function __construct(CmsSinglePageRepository $cmsSinglePageRepository){

        $this->modelRepository = $cmsSinglePageRepository;
    }

    public function show($id){
        $data = $this->modelRepository->findById($id);

        $this->data['layout'] = false;

        $this->data['pageTitle'] = '预览文章';

        $this->data['data'] = $data;

        return $this->render($this->pageConfig['viewPrefix'] . '.show');
    }
}