<?php
namespace Liudian\Cms\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Liudian\Admin\Foundation\ControllerCURD;
use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Cms\Repositories\CmsArticleCommentRepository;

class ArticleCommentController extends Controller
{

    use CommonReturn, ControllerFoundation;

    protected $pageConfig = [
        'pageTitle' => '文章',
        'viewPrefix' => 'cms::article_comment',
        'indexRoute' => 'admin_cms_article_comment_index'
    ];

    protected $modelRepository;

    public function __construct(CmsArticleCommentRepository $cmsArticleCommentRepository){

        $this->modelRepository = $cmsArticleCommentRepository;
    }

    public function index($id, Request $request){

        if($request->isMethod('POST')){

            $lists = $this->modelRepository->getLists($id);

            return self::returnTableData($lists);
        }

        $this->data['id'] = $id;

        $this->data['layout'] = false;

        return $this->render($this->pageConfig['viewPrefix'] . '.index');
    }

    public function reply($id, Request $request){

        if(!$data = $this->modelRepository->findById($id)){

            return self::returnErrorByMethod($request, '未找到数据！');
        }

        if($request->isMethod('POST')){

            $res = $this->modelRepository->reply($data);

            return self::returnJson($res);
        }
        $this->data['data'] = $data;

        $this->data['layout'] = false;

        return $this->render($this->pageConfig['viewPrefix'] . '.reply');
    }

    public function delete($id, Request $request){

        $res = $this->modelRepository->delete($id);

        return self::returnJson($res);
    }
}