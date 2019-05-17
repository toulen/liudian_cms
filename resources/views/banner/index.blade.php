@extends('admin::layouts.app')

@section('css')
    <link href="{{asset('admin/js/layui/css/layui.css')}}" rel="stylesheet" rev="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="ibox-content m-b-sm border-bottom">
        <form id="searchForm">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="col-form-label" for="phone">Banner位</label>
                        <select class="form-control" name="position_id">
                            <option value="">全部</option>
                            @foreach($positions as $position)
                            <option value="{{$position->id}}" {!! $positionId == $position->id ? 'selected' : '' !!}>{{$position->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group pull-right">
                        <label class="col-form-label">&nbsp;</label>
                        <div>
                            <a href="javascript:;" id="searchFromBtn" class="btn btn-success">搜索</a>
                            @component('admin::layouts.btn', ['route' => 'admin_cms_banner_create'])
                                <a href="{{route('admin_cms_banner_create')}}" class="btn btn-primary">新增Banner位</a>
                            @endcomponent
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox ">
                <div class="ibox-content">

                    <div class="table-responsive">
                        <table id="dataTable" lay-filter="list"></table>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script type="text/html" id="positionField">
    @{{ d.position.name }}
    </script>
    <script type="text/html" id="imageField">
    <a href="javascript:;" onclick="showImage($(this))" data-image="@{{ d.image_url }}">查看图片</a>
    </script>
    <script type="text/html" id="bar">
        @component('admin::layouts.btn', ['route' => 'admin_cms_banner_edit'])
        <a href="/{{config('liudian_admin.route_prefix')}}/cms/banner/edit/@{{ d.id }}" class="btn btn-info btn-xs">编辑</a>
        @endcomponent
        @component('admin::layouts.btn', ['route' => 'admin_cms_banner_delete'])
        <a href="javascript:;" class="btn btn-danger btn-xs" lay-event="delete">删除</a>
        @endcomponent
    </script>
@endsection
@section('js')
    <script src="{{asset('admin/js/layui/layui.js')}}"></script>
    <script>

        function showImage($obj){
            var image = $obj.attr('data-image');

            layer.photos({
                photos: {
                    "title": "", //相册标题
                    "id": 123, //相册id
                    "start": 0, //初始显示的图片序号，默认0
                    "data": [   //相册包含的图片，数组格式
                        {
                            "alt": "",
                            "pid": 666, //图片id
                            "src": image, //原图地址
                            "thumb": "" //缩略图地址
                        }
                    ]
                } //格式见API文档手册页
                ,anim: 5 //0-6的选择，指定弹出图片动画类型，默认随机
            });
        }

        layui.use('table', function(){
            var table = layui.table;

            //第一个实例
            table.render({
                elem: '#dataTable'
                ,url: '{{route('admin_cms_banner_index')}}' //数据接口
                ,method: 'post'
                ,where:{_token:'{{csrf_token()}}',param:{position_id:'{{$positionId}}'}}
                ,data: {'_token':'{{csrf_token()}}'}
                ,page: {
                    layout: ['prev', 'page', 'next']
                } //开启分页
                ,limit: {{config('liudian_cms.page_size')}}
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, fixed: 'left'},
                    {field: 'name', title: 'Banner位', templet:'#positionField'},
                    {field: 'size', title: '图片', templet: '#imageField'},
                    {field: 'link', title: '链接'},
                    {field: 'sort_id', title: '排序', width: 100},
                    {field:'', title: '操作', align:'center', width: 180, templet: '#bar',fixed: 'right'}
                ]]
            });

            //监听工具条
            table.on('tool(list)', function(obj){
                var data = obj.data;
                var layEvent = obj.event;
                var tr = obj.tr;

                if(layEvent === 'delete'){ //删除
                    layer.confirm('确认删除当前数据？删除后无法撤回！', function(index){
                        postRequest('/{{config('liudian_admin.route_prefix')}}/cms/banner/delete/' + data.id, {}, true, function (res){
                            obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                            layer.close(index);
                        }, function (){});
                    });
                }
            });


            $('#searchFromBtn').on('click', function (){
                var data = $('#searchForm').serializeJSON();
                table.reload('dataTable', {
                    page: {
                        curr: 1 //重新从第 1 页开始
                    }
                    ,where: {
                        param: data
                    }
                });
            });

            $('#searchForm').submit(function (){
                var data = $(this).serializeJSON();
                return false;
            });

        });
    </script>
@endsection