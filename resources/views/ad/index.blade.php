@extends('layouts.main')

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            广告列表
        </h1>
        <div class="page-options d-flex">
            <a href="/backend/ad/create" class="btn btn-outline-success "> <i class="fe fe-plus mr-2"></i>添加广告</a>
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
                        <th>发布用户</th>
                        <th class="d-none d-sm-table-cell">广告URL</th>
                        <th class="d-none d-sm-table-cell">发布时间</th>
                        <th class="d-none d-md-table-cell">操作</th>
                    </tr>
                    @foreach ($ads as $ad)
                        <tr id="ad-{{ $ad->id }}">
                            <td>{{ $ad->uuid }}</td>
                            <td>{{ $ad->user->name }}</td>
                            <td>{{ $ad->url }}</td>
                            <td>{{ $ad->created_at }}</td>
                            <td>
                                <a href="/backend/ad/{{ $ad->id }}/edit" class="btn btn-sm btn-outline-primary"><i
                                            class="fe fe-edit-3"></i>编辑</a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="delAd('{{ $ad->id }}')"><i class="fe fe-trash-2"></i>删除
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $ads->links() }}
        </div>
    </div>
@stop
@section('js')
    <script>
        require(['jquery', 'sweetalert'], function ($, swal) {

        });

        function delAd(id) {
            swal({
                title: "删除确认",
                text: "确认删除该广告吗",
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
                        url:'/backend/ad/'+id,
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
                            $("#ad-"+data.data.id).remove();
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