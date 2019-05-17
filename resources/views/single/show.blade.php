@extends('admin::layouts.app')
@section('css')
    <link href="{{asset('admin/css/plugins/summernote/summernote-bs4.css')}}" rel="stylesheet">

    <style>
        .intro{
            padding: 15px;
            background: #f5f5f5;
        }
    </style>
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <h2>{{$data->title}}</h2>
                    <div class="content">
                        {!! $data->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')

@endsection