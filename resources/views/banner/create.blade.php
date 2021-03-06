@extends('admin::layouts.app')
@section('css')
    <style>

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
                    <form method="post" class="checkColorForm theForm" id="createdForm" data-index="{{route('admin_cms_banner_index')}}">
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">Banner位</label>
                            <div class="col-sm-10">
                                <select class="form-control" name="param[position_id]">
                                    @foreach($positions as $position)
                                    <option value="{{$position->id}}">{{$position->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">图片类型</label>
                            <div class="col-sm-10">
                                <select class="form-control imageType">
                                    <option value="1">手动上传</option>
                                    <option value="2">网络图片</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group  row uploadImageBox"><label class="col-sm-2 col-form-label">缩略图</label>
                            <div class="col-sm-10">
                                <div class="logoFormBtn uploadBtn text-center">
                                    <h4 style="padding-top: 60px">选择图片</h4>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row urlBox"><label class="col-sm-2 col-form-label">图片网址</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[image_url]" autocomplete="false" placeholder="" value="" required></div>
                        </div>
                        <div class="form-group row"><label class="col-sm-2 col-form-label">图片超链接</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[link]" autocomplete="false" placeholder="" value="#"></div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">排序值</label>
                            <div class="col-sm-10"><input type="text" class="form-control" name="param[sort_id]" placeholder="" value="0">
                            <p>值越大越靠前</p>
                            </div>
                        </div>
                        <div class="form-group  row"><label class="col-sm-2 col-form-label">图片状态</label>
                            <div class="col-sm-10">
                                <select class="form-control">
                                    <option value="1">显示</option>
                                    <option value="0">不显示</option>
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
    <script>
        $('.uploadBtn').h5upload({
            fileTypeExts: 'jpg,png,gif,jpeg',
            url: "{!! URL::route('upload_ajax') !!}",
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
                    $('input[name="param[image_url]"]').val(res.data.url);
                }else{
                    layer.msg('上传失败了！');
                }
            }
        });
        $('.imageType').on('change', function (){
            var _v = $(this).val();

            if(_v == 1){
                $('.urlBox').hide();
                $('.uploadImageBox').show();
            }else{
                $('.urlBox').show();
                $('.uploadImageBox').hide();
            }
        })

        $('.urlBox').hide();
    </script>
@endsection