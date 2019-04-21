@extends('common.admin_base')

@section('title','下发记录列表')


<!--页面顶部信息-->
@section('pageHeader')
    <div class="pageheader">
        <h2><i class="fa fa-home"></i> 下发记录列表 <span>Subtitle goes here...</span></h2>
        {{--<div class="breadcrumb-wrapper">--}}
            {{--<a class="btn btn-sm btn-danger" href="/admin/brand/add">+ 商品品牌</a>--}}
        {{--</div>--}}
    </div>
@endsection

@section('content')
    {{csrf_field()}}
    <div class="row" id="brand_list">
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-primary  mb30">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>发送类型</th>
                        <th>接收者</th>
                        <th>发送内容</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                        @if(!empty($message))
                            @foreach($message as $mes)
                                <tr>
                                    <td>{{$mes->id}}</td>
                                    <td>{{$mes->type == 1 ? '短信' : '邮件' }}</td>
                                    <td>{{$mes->get_user}}</td>
                                    <td>{{$mes->content}}</td>
                                    <td>
                                        <a class="btn btn-sm btn-danger" href="/admin/message/del/{{$mes->id}}">删除</a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div><!-- table-responsive -->
        </div>
    </div>
@endsection