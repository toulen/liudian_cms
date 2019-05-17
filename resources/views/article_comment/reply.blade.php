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
                    <form method="post" class="checkColorForm theForm" id="editForm" data-index="{{route('admin_cms_article_comment_index', $data->article_id)}}">
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">评论</label>
                            <div class="col-sm-10">
                                <p style="padding-top: 10px;">{{$data->content}}</p>
                            </div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">回复</label>
                            <div class="col-sm-10"><textarea class="form-control" name="param[content]"></textarea></div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-white" type="reset">重置表单</button>
                                <button class="btn btn-primary" type="submit">确认回复</button>
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