@extends('admin::layouts.app')
@section('css')
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form method="post" class="checkColorForm theForm" id="createdForm" data-index="{{route('admin_cms_banner_position_index')}}">
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">Banner位名称</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[name]" autocomplete="false" placeholder="" value="" required></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">图片尺寸</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[size]" placeholder="" value=""></div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-white" type="reset">重置表单</button>
                                <button class="btn btn-primary" type="submit">确认创建</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
@endsection