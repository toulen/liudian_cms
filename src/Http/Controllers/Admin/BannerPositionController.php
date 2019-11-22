<?php
namespace Liudian\Cms\Http\Controllers\Admin;

use Liudian\Admin\Foundation\ControllerCURD;
use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Cms\Repositories\CmsBannerPositionRepository;

class BannerPositionController extends Controller
{

    use CommonReturn, ControllerFoundation, ControllerCURD;

    protected $pageConfig = [
        'pageTitle' => 'Banner位',
        'viewPrefix' => 'cms::banner_position',
        'indexRoute' => 'admin_cms_banner_position_index'
    ];

    protected $modelRepository;

    public function __construct(CmsBannerPositionRepository $cmsBannerPositionRepository){

        $this->modelRepository = $cmsBannerPositionRepository;
    }
}