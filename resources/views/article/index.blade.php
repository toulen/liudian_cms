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
                        <label class="col-form-label" for="phone">文章标题</label>
                        <input type="text" id="title" name="title" value="" placeholder="" class="form-control">
                    </div>
                </div>
                <div class="col-sm-2">
                    <label class="col-form-label" for="phone">文章标题</label>
                    <select class="form-control" name="category_id" id="category_id">
                        <option value="">全部分类</option>
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">@for($i=0;$i<$category->depth;$i++)&nbsp; &nbsp;@endfor{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-6">
                    <div class="form-group pull-right">
                        <label class="col-form-label">&nbsp;</label>
                        <div>
                            <a href="javascript:;" id="searchFromBtn" class="btn btn-success">搜索</a>
                            @component('admin::layouts.btn', ['route' => 'admin_cms_article_create'])
                                <a href="{{route('admin_cms_article_create')}}" class="btn btn-primary">发布新文章</a>
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

    <script type="text/html" id="allowCommentField">
        @{{ d.allow_comment == 1 ? '允许' : '-' }}
    </script>
    <script type="text/html" id="statusField">
    @{{ d.status == 1 ? '已发布' : '未发布' }}
    </script>
    <script type="text/html" id="categoryField">
    @{{ d.category ? d.category.name : '' }}
    </script>
    <script type="text/html" id="bar">
        @component('admin::layouts.btn', ['route' => 'admin_cms_article_comment_index'])
        <a href="javascript:;" class="btn btn-success btn-xs" lay-event="comment">查看评论</a>
        @endcomponent
        @component('admin::layouts.btn', ['route' => 'admin_cms_article_edit'])
        <a href="/{{config('liudian_admin.route_prefix')}}/cms/article/edit/@{{ d.id }}" class="btn btn-info btn-xs">编辑</a>
        @endcomponent
        @component('admin::layouts.btn', ['route' => 'admin_cms_article_delete'])
        <a href="javascript:;" class="btn btn-danger btn-xs" lay-event="delete">删除</a>
        @endcomponent
        @component('admin::layouts.btn', ['route' => 'admin_cms_article_show'])
        <a href="javascript:;" class="btn btn-primary btn-xs" lay-event="show">预览</a>
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
                ,url: '{{route('admin_cms_article_index')}}' //数据接口
                ,method: 'post'
                ,where:{_token:'{{csrf_token()}}'}
                ,data: {'_token':'{{csrf_token()}}'}
                ,page: {
                    layout: ['prev', 'page', 'next']
                } //开启分页
                ,limit: {{config('liudian_cms.page_size')}}
                ,cols: [[ //表头
                    {field: 'id', title: 'ID', width:80, fixed: 'left'},
                    {field: '', title: '分类',templet:'#categoryField',width: 140},
                    {field: 'title', title: '文章标题'},
                    {field: '', title: '状态',templet:'#statusField',width: 80},
                    {field: '', title: '评论',templet:'#allowCommentField',width: 80},
                    {field: 'hits', title: '阅读量',width: 90},
                    {field:'', title: '操作', align:'center', width: 220, templet: '#bar',fixed: 'right'}
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
                        postRequest('/{{config('liudian_admin.route_prefix')}}/cms/article/delete/' + data.id, {}, true, function (res){
                            obj.del(); //删除对应行（tr）的DOM结构，并更新缓存
                            layer.close(index);
                        }, function (){});
                    });
                }else if(layEvent === 'show'){
                    layer.open({
                        type: 2,
                        title: '预览文章',
                        shadeClose: true,
                        shade: 0.8,
                        area: ['80%', '90%'],
                        content: '/{{config('liudian_admin.route_prefix')}}/cms/article/show/' + data.id
                    });
                }else if(layEvent === 'comment'){
                    layer.open({
                        type: 2,
                        title: '查看评论',
                        shadeClose: true,
                        shade: 0.8,
                        area: ['80%', '90%'],
                        content: '/{{config('liudian_admin.route_prefix')}}/cms/article_comment/index/' + data.id
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