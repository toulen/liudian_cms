@extends('admin::layouts.app')

@section('css')
    <link href="{{asset('admin/js/layui/css/layui.css')}}" rel="stylesheet" rev="stylesheet" type="text/css" />
@endsection
@section('content')
    {{--<div class="ibox-content m-b-sm border-bottom">--}}
        {{--<form id="searchForm">--}}
            {{--<div class="row">--}}
                {{--<div class="col-sm-4">--}}
                    {{--<div class="form-group">--}}
                        {{--<label class="col-form-label" for="phone">文章标题</label>--}}
                        {{--<input type="text" id="title" name="title" value="" placeholder="" class="form-control">--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<div class="col-sm-2">--}}

                {{--</div>--}}
                {{--<div class="col-sm-6">--}}
                    {{--<div class="form-group pull-right">--}}
                        {{--<label class="col-form-label">&nbsp;</label>--}}
                        {{--<div>--}}
                            {{--<a href="javascript:;" id="searchFromBtn" class="btn btn-success">搜索</a>--}}
                        {{--</div>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</form>--}}
    {{--</div>--}}
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

    <script type="text/html" id="contentField">
    <span @{{ d.parent_id ? 'style="color:red"' : 'style="display:none"' }}>【@{{d.admin_user ? '管理员（'+ d.admin_user.nickname +'）' : ''}}回复：@{{ d.parent ? d.parent.content : '' }}】：</span> @{{ d.content }}
    </script>
    <script type="text/html" id="bar">
        @component('admin::layouts.btn', ['route' => 'admin_cms_article_comment_reply'])
        <a href="/{{config('liudian_admin.route_prefix')}}/cms/article_comment/reply/@{{ d.id }}" class="btn btn-info btn-xs">回复</a>
        @endcomponent
        @component('admin::layouts.btn', ['route' => 'admin_cms_article_comment_delete'])
        <a href="javascript:;" class="btn btn-danger btn-xs" lay-event="delete">删除</a>
        @endcomponent
    </script>
@endsection
@section('js')
    <script src="{{asset('admin/js/layui/layui.js')}}"></script>
    <script>
        layui.use('table', function(){
            var table = layui.table;

            //第一个实例
            table.render({
                elem: '#dataTable'
                ,url: '{{route('admin_cms_article_comment_index', $id)}}' //数据接口
                ,method: 'post'
                ,where:{_token:'{{csrf_token()}}'}
                ,data: {'_token':'{{csrf_token()}}'}
                ,page: {
                    layout: ['prev', 'page', 'next']
                } //开启分页
                ,limit: {{config('liudian_cms.page_size')}}
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, fixed: 'left'},
                    {field: 'content', title: '内容', templet:'#contentField'},
                    {field: 'created_at', title: '评论时间',width: 170},
                    {field: 'praise_count', title: '点赞',width: 90},
                    {field:'', title: '操作', align:'center', width: 120, templet: '#bar',fixed: 'right'}
                ]]
            });

            //监听工具条
            table.on('tool(list)', function(obj){
                console.log(obj)
                var data = obj.data;
                var layEvent = obj.event;
                var tr = obj.tr;

                if(layEvent === 'delete'){ //删除
                    layer.confirm('确认删除当前数据？删除后无法撤回！', function(index){
                        postRequest('/{{config('liudian_admin.route_prefix')}}/cms/article_comment/delete/' + data.id, {}, true, function (res){
                            layer.close(index);
                            table.reload('dataTable', {
                                page: {
                                    curr: 1 //重新从第 1 页开始
                                }
                                ,where: {
                                    param: data
                                }
                            });
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