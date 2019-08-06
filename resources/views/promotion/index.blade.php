@extends('layouts.main')

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            推广列表
        </h1>
        <div class="page-options d-flex">
            <a href="/backend/promotion/create" class="btn btn-outline-success "> <i class="fe fe-plus mr-2"></i>添加推广</a>
        </div>
    </div>
    <div class="row row-cards">

        <div class="col-lg-12">
            <div class="card">
                @include('layouts.alert')
                <table class="table card-table table-vcenter">
                    <tbody>
                    <tr>
                        <th>UUID</th>
                        <th>分享标题</th>
                        <th>推广广告用户</th>
                        <th class="d-none d-sm-table-cell">音乐链接</th>
                        <th class="d-none d-sm-table-cell">创建时间</th>
                        <th class="d-none d-md-table-cell">操作</th>
                    </tr>
                    @foreach ($promotions as $promotion)
                        <tr id="promotion-{{ $promotion->id }}">
                            <td>{{ $promotion->uuid }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($promotion->share_title,20) }}</td>
                            <td>
                                <span class="tag tag-indigo">{{ $promotion->user() }}</span>
                            </td>
                            <td>
                                <a href="{{ $promotion->music_url }}" class="btn btn-primary btn-sm">推广页音乐链接</a>
                            </td>
                            <td>{{ $promotion->created_at }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="showUrl('{{ $promotion->uuid }}')"><i
                                            class="fe fe-copy"></i>链接</button>
                                <a href="/backend/promotion/pages/{{ $promotion->id }}" class="btn btn-sm btn-outline-dark"><i class="fe fe-eye"></i>页面管理</a>
                                <a href="/backend/promotion/{{ $promotion->id }}/edit" class="btn btn-sm btn-outline-secondary"><i class="fe fe-edit"></i>编辑</a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="delPromotion('{{ $promotion->id }}')"><i class="fe fe-trash-2"></i>删除
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $promotions->links() }}
        </div>
    </div>
@stop
@section('js')
    <script>
        require(['jquery', 'sweetalert'], function ($, swal) {

        });
        function showUrl(uuid) {
            $.ajax({
                url: "/backend/show_promotion_url",
                dataType: "json",
                data:{uuid:uuid},
                type: "GET",
                error:function () {
                    swal("系统错误", {
                        icon: "warning",
                    });
                },
                success:function (data) {
                    swal(data.data.url, {
                        icon: "success",
                    });
                }
            });
        }
        function delPromotion(id) {
            swal({
                title: "删除确认",
                text: "确认删除该推广页吗",
                icon: "warning",
                buttons: ['取消','确认'],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url:'/backend/promotion/'+id,
                        type:'DELETE',
                        dataType:'json',
                        error:function () {
                            swal("删除失败,系统错误", {
                                icon: "warning",
                            });
                        },
                        success:function (data) {
                            if (data.status === 1){
                                swal(data.msg, {
                                    icon: "warning",
                                });
                                return 0;
                            }
                            $("#promotion-"+data.data.id).remove();
                            swal(data.msg, {
                                icon: "success",
                            });
                        }
                    });
                    
                }
            });
        }
    </script>
@stop