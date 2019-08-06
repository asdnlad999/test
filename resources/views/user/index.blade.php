@extends('layouts.main')

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            用户列表
        </h1>
        <div class="page-options gutters-xs d-flex">
            <div class="col">
                <input type="text" class="form-control" placeholder="输入用户名搜索" id="search_user" value="{{ $keyword }}">
            </div>
            <span class="col-auto">
                <button class="btn btn-outline-secondary" type="button" onclick="search_user()"><i class="fe fe-search"></i>搜索</button>
            </span>
            <span class="col-auto">
                <a class="btn btn-outline-info" href="/backend/user"><i class="fa fa-reply"></i>重置</a>
            </span>
        </div>
        <div class="page-options d-flex">
            <a href="/backend/user/create" class="btn btn-outline-success "> <i class="fe fe-plus mr-2"></i>添加用户</a>
        </div>
    </div>
    <div class="row row-cards">

        <div class="col-lg-12">
            <div class="card">
                @include('layouts.alert')
                <table class="table card-table table-vcenter">
                    <tbody>
                    <tr>
                        <th>ID</th>
                        <th>用户名</th>
                        <th class="d-none d-sm-table-cell">邮箱</th>
                        <th class="d-none d-sm-table-cell">手机号</th>
                        <th class="d-none d-md-table-cell">用户角色</th>
                        <th class="d-none d-md-table-cell">代理等级</th>
                        <th class="d-none d-md-table-cell">添加时间</th>
                        <th class="d-none d-md-table-cell">操作</th>
                    </tr>
                    @foreach ($users as $user)
                        <tr id="user-{{ $user->id }}">
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td class="d-none d-sm-table-cell">{{ $user->email ? $user->email : '暂无' }}</td>
                            <td class="d-none d-sm-table-cell">{{ $user->phone ? $user->phone : '暂无' }}</td>
                            <td class="d-none d-md-table-cell">{{ $user->is_admin ? '管理员' : '代理用户' }}</td>
                            <td class="d-none d-md-table-cell">{{ $user->level ? $user->level.'级代理' : '系统用户' }}</td>
                            <td class="d-none d-md-table-cell">{{ $user->created_at }}</td>
                            <td>
                                <a href="/backend/user/{{ $user->id }}/edit" class="btn btn-sm btn-outline-primary"><i
                                            class="fe fe-edit-3"></i>编辑</a>
                                <button type="button" class="btn btn-sm btn-outline-danger"
                                        onclick="delUser('{{ $user->id }}')"><i class="fe fe-trash-2"></i>删除
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{ $users->appends(['keyword'=>$keyword])->links() }}
        </div>
    </div>
@stop
@section('js')
    <script>
        require(['jquery', 'sweetalert'], function ($, swal) {

        });
        function search_user() {
            let value = $("#search_user").val();
            if (value === ""){
                location.href = "/backend/user";
                return 0;
            }
            location.href = '/backend/user?keyword=' + value;
        }
        function delUser(id) {
            swal({
                title: "删除确认",
                text: "确认删除该用户吗",
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
                        url:'/backend/user/'+id,
                        type:'DELETE',
                        dataType:'json',
                        error:function () {
                            swal("删除失败,系统错误", {
                                icon: "warning",
                            });
                        },
                        success:function (data) {
                            if (data.status == 0){
                                swal(data.msg, {
                                    icon: "warning",
                                });
                                return 0;
                            }
                            $("#user-"+data.data.id).remove();
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