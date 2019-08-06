@extends('layouts.main')

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            域名列表
        </h1>
        <div class="page-options d-flex">
            <a href="/backend/domain/create" class="btn btn-outline-success "> <i class="fe fe-plus mr-2"></i>添加域名</a>
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
                        <th>域名类型</th>
                        <th class="d-none d-sm-table-cell">域名地址</th>
                        <th class="d-none d-sm-table-cell">状态</th>
                        <th class="d-none d-sm-table-cell">创建时间</th>
                        <th class="d-none d-md-table-cell">操作</th>
                    </tr>
                    @foreach ($domains as $domain)
                        <tr id="domain-{{ $domain->id }}">
                            <td>{{ $domain->uuid }}</td>
                            @if ($domain->type == 1)
                                <td><span class="tag tag-blue">入口域名</span></td>
                                @elseif ($domain->type == 2)
                                <td><span class="tag tag-teal">落地域名</span></td>
                                @else
                                <td><span class="tag tag-success">广告域名</span></td>
                            @endif
                            <td>{{ $domain->domain_addr }}</td>
                            <td>
                                <label class="custom-switch p-0">
                                    <input type="checkbox" name="is_use" value="{{ $domain->is_use }}" {{ $domain->is_use ? 'checked' : '' }} class="custom-switch-input" onchange="changeDomainStatus('{{ $domain->id }}',this)">
                                    <span class="custom-switch-indicator"></span>
                                </label>
                            </td>
                            <td>{{ $domain->created_at }}</td>
                            <td>
                                <a href="/backend/domain/{{ $domain->id }}/edit" class="btn btn-sm btn-outline-primary"><i
                                            class="fe fe-edit-3"></i>编辑</a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="delDomain('{{ $domain->id }}')"><i class="fe fe-trash-2"></i>删除
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $domains->links() }}
        </div>
    </div>
@stop
@section('js')
    <script>
        require(['jquery', 'sweetalert'], function ($, swal) {

        });

        function changeDomainStatus(id,that) {
            let status = that.value;
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:'/backend/domain/status/'+id,
                type:'PUT',
                dataType:'json',
                error:function () {
                    swal("系统错误", {
                        icon: "warning",
                    });
                },
                success:function (data) {
                    if (data.status === 0){
                        $(that).val(data.is_use);
                        return 0 ;
                    }
                    swal("修改状态失败", {
                        buttons: false,
                        timer: 2000,
                    });
                }
            });
        }
        function delDomain(id) {
            swal({
                title: "删除确认",
                text: "确认删除该域名吗",
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
                        url:'/backend/domain/'+id,
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
                            $("#domain-"+data.data.id).remove();
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