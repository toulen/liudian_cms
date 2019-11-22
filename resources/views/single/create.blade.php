@extends('admin::layouts.app')
@section('css')
    <link href="{{asset('admin/css/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet">

    <style>
        .zhuanzaiShow{
            display: none;
        }

        .logoFormBtn{
            display: block;
            width: 160px;
            height: 160px;
            border: 1px solid #ddd;
            padding: 2px;
            position: relative;
            cursor: pointer;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <form method="post" class="checkColorForm theForm" id="createdForm" data-index="{{route('admin_cms_single_index')}}">
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">单页面标题</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[title]" autocomplete="false" placeholder="" value="" required></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">SEO关键词</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[seo_keyword]" placeholder="" value=""></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">SEO描述</label>
                            <div class="col-sm-10"><textarea class="form-control" name="param[seo_description]"></textarea></div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">单页面正文</label>
                            <div class="col-sm-10"><textarea class="summernote" name="param[content]" required></textarea></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">发布状态</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="param[status]">
                                    <option value="1">发布</option>
                                    <option value="0">不发布</option>
                                </select>
                            </div>
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
    <script src="{{asset('admin/js/jquery.html5upload.js')}}"></script>
    <script src="{{asset('admin/js/plugins/summernote/summernote-bs4.js')}}"></script>

    <script>

        $('.summernote').summernote({
            height: 300,
            lang: 'zh-CN'
        });
    </script>
@endsection