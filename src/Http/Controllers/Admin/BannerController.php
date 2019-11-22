<?php
namespace Liudian\Cms\Http\Controllers\Admin;

use Illuminate\Support\Facades\Input;
use Liudian\Admin\Foundation\ControllerCURD;
use Liudian\Admin\Foundation\ControllerFoundation;
use Liudian\Admin\Helper\CommonReturn;
use Liudian\Cms\Model\CmsBannerPosition;
use Liudian\Cms\Repositories\CmsBannerRepository;

class BannerController extends Controller
{

    use CommonReturn, ControllerFoundation, ControllerCURD;

    protected $pageConfig = [
        'pageTitle' => 'Banner图片',
        'viewPrefix' => 'cms::banner',
        'indexRoute' => 'admin_cms_banner_index'
    ];

    protected $modelRepository;

    public function __construct(CmsBannerRepository $cmsBannerRepository){

        $this->modelRepository = $cmsBannerRepository;
    }

    public function beforeIndex(){

        $positionId = Input::get('position_id', 0);

        $this->data['positionId'] = $positionId;

        $positions = CmsBannerPosition::where('status', '>=', 0)->get();

        $this->data['positions'] = $positions;
    }

    public function beforeCreate($request){

        $positions = CmsBannerPosition::where('status', '>=', 0)->get();

        $this->data['positions'] = $positions;
    }
    public function beforeEdit($id, $request){

        $positions = CmsBannerPosition::where('status', '>=', 0)->get();

        $this->data['positions'] = $positions;
    }
}