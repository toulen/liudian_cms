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
                    <form method="post" class="checkColorForm theForm" id="editForm" data-index="{{route('admin_cms_article_index')}}">
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">所属分类</label>
                            <div class="col-sm-10">
                                <select class="form-control" required name="param[category_id]">
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}" {!! $category->id == $data->category_id ? 'selected' : '' !!}>@for($i=0;$i<$category->depth;$i++)&nbsp; &nbsp;@endfor{{$category->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">文章标题</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[title]" autocomplete="false" placeholder="" value="{{$data->title}}" required></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">文章摘要</label>
                            <div class="col-sm-10"><textarea class="form-control" name="param[intro]">{{$data->intro}}</textarea></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">SEO关键词</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[seo_keyword]" placeholder="" value="{{$data->seo_keyword}}"></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">SEO描述</label>
                            <div class="col-sm-10"><textarea class="form-control" name="param[seo_description]">{{$data->seo_description}}</textarea></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">文章来源</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="param[from_type]">
                                    <option value="1">原创</option>
                                    <option value="2" {{$data->from_type == 2 ? 'selected' : ''}}>转载</option>
                                </select>
                            </div>
                        </div>
                        <div class="zhuanzaiShow" {!! $data->form_type == 2 ? 'style="display: block"' : '' !!}>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">文章来源网站</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[from_text]" placeholder="" value="{{$data->from_text}}"></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">文章来源网址</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[from_link]" placeholder="" value="{{$data->from_link}}"></div>
                        </div>
                        </div>

                        <div class="hr-line-dashed"></div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">缩略图</label>
                            <div class="col-sm-10">
                                <div class="logoFormBtn uploadBtn text-center">
                                    @if($data->thumbnail)
                                        <img src="{{$data->thumbnail}}" width="160" height="160" />
                                        @else
                                    <h4 style="padding-top: 60px">选择图片</h4>
                                    @endif
                                </div>
                                <input type="hidden" class="form-control theHeadImg" name="param[thumbnail]" value="{{$data->thumbnail}}">
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">文章正文</label>
                            <div class="col-sm-10"><textarea class="summernote" name="param[content]" required>{!! $data->content !!}</textarea></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">是否允许评论</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="param[allow_comment]">
                                    <option value="1">允许</option>
                                    <option value="0" {!! $data->allow_comment == 0 ? 'selected' : '' !!}>不允许</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">发布状态</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="param[satus]">
                                    <option value="1">发布</option>
                                    <option value="0" {!! $data->status == 0 ? 'selected' : '' !!}>不发布</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">默认点击量</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[hits]" placeholder="" value="{{$data->hits}}"></div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group row">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-white" type="reset">重置表单</button>
                                <button class="btn btn-primary" type="submit">确认修改</button>
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

        $('.uploadBtn').h5upload({
            fileTypeExts: 'jpg,png,gif,jpeg',
            url: "{!! URL::route('admin_public_ajax_upload') !!}",
            fileObjName: 'upload',
            fileSizeLimit: 10 * 1024 * 1024,
            formData: {'_token': '{{ csrf_token() }}', 'type': 'image'},

            //进度监控
            onUploadProgress: function (file, data) {
                $('.uploadBtn').html('<p style="padding-top: 50px;">上传中...</p>');
            },

            // 上传成功的动作
            onUploadSuccess: function (file, res) {
                res = JSON.parse(res);
                if(res.status == 1){
                    var img = $('<img src="'+ res.data.url +'" width="160" height="160" />');
                    $('.uploadBtn').html(img);
                    $('.theHeadImg').val(res.data.url);
                }else{
                    layer.msg('上传失败了！');
                }
            }
        });

        $('select[name="param[from_type]"]').on('change', function (){
            var _v = $(this).val();

            if(_v == 1){
                $('.zhuanzaiShow').hide();
            }else{
                $('.zhuanzaiShow').show();
            }
        })
    </script>
@endsection