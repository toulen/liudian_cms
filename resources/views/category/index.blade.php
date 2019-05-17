@extends('admin::layouts.app')

@section('css')
    <link href="{{asset('admin/js/layui/css/layui.css')}}" rel="stylesheet" rev="stylesheet" type="text/css" />
@endsection
@section('content')
    <div class="ibox-content m-b-sm border-bottom">
        <form id="searchForm">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="col-form-label" for="phone">分类名称</label>
                        <input type="text" id="name" name="name" value="" placeholder="" class="form-control">
                    </div>
                </div>
                <div class="col-sm-2">
                </div>
                <div class="col-sm-6">
                    <div class="form-group pull-right">
                        <label class="col-form-label">&nbsp;</label>
                        <div>
                            <a href="javascript:;" id="searchFromBtn" class="btn btn-success">搜索</a>
                            @component('admin::layouts.btn', ['route' => 'admin_cms_article_category_create'])
                                <a href="{{route('admin_cms_article_category_create')}}" class="btn btn-primary">创建文章分类</a>
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

    <script type="text/html" id="bar">
        @component('admin::layouts.btn', ['route' => 'admin_cms_article_category_edit'])
        <a href="/{{config('liudian_admin.route_prefix')}}/cms/article_category/edit/@{{ d.id }}" class="btn btn-info btn-xs">编辑</a>
        @endcomponent
        @component('admin::layouts.btn', ['route' => 'admin_cms_article_category_delete'])
        <a href="javascript:;" class="btn btn-danger btn-xs" lay-event="delete">删除</a>
        @endcomponent
        @component('admin::layouts.btn', ['route' => 'admin_cms_article_category_move'])
        <a href="javascript:;" class="btn btn-success btn-xs" lay-event="move_left">上移</a>
        @endcomponent
        @component('admin::layouts.btn', ['route' => 'admin_cms_article_category_move'])
        <a href="javascript:;" class="btn btn-success btn-xs" lay-event="move_right">下移</a>
        @endcomponent
    </script>
@endsection
@section('js')
    <script src="{{asset('admin/js/layui/layui.js')}}"></script>
    <script>
        layui.config({
            base: '/admin/js/layui/lay/treeGrid/'
        }).extend({
            treeGrid:'treeGrid'
        }).use(['table', 'treeGrid'], function(){
            var table = layui.table;
            var treeGrid = layui.treeGrid;
            treeGrid.render({
                elem: '#dataTable'
                ,url: '{{route('admin_cms_article_category_index')}}' //数据接口
                ,method: 'post'
                ,idField:'id'
                ,treeId:'id'
                ,treeUpId:'parent_id'
                ,treeShowName:'name'
                ,isOpenDefault:false
                ,where:{_token:'{{csrf_token()}}'}
                ,data: {'_token':'{{csrf_token()}}'}
                ,page: false
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, fixed: 'left'},
                    {field: 'name', title: '文章分类名称'},
                    {field:'', title: '操作', align:'center', width: 200, templet: '#bar',fixed: 'right'}
                ]]
            });

            //监听工具条
            treeGrid.on('tool(list)', function(obj){
                console.log(obj)
                var data = obj.data;
                var layEvent = obj.event;
                var tr = obj.tr;

                if(layEvent === 'delete'){ //删除
                    layer.confirm('确认删除当前数据？删除后无法撤回！', function(index){
                        postRequest('/{{config('liudian_admin.route_prefix')}}/cms/article_category/delete/' + data.id, {}, true, function (res){
                            layer.close(index);
                            treeGrid.reload('dataTable');
                        }, function (){});
                    });
                }else if(layEvent === 'move_left'){ //删除
                    postRequest('/{{config('liudian_admin.route_prefix')}}/cms/article_category/move/' + data.id, {'type': 'left'}, true, function (res){
                        window.location.href = '{{route('admin_cms_article_category_index')}}';
                    }, function (){});
                }else if(layEvent === 'move_right'){ //删除
                    postRequest('/{{config('liudian_admin.route_prefix')}}/cms/article_category/move/' + data.id, {'type': 'right'}, true, function (res){
                        window.location.href = '{{route('admin_cms_article_category_index')}}';
                    }, function (){});
                }
            });


            $('#searchFromBtn').on('click', function (){
                var data = $('#searchForm').serializeJSON();
                treeGrid.reload('dataTable', {
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