@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="/backend/user/{{ $user->id }}" method="post" class="card">
                @method('PATCH')
                @csrf
                <div class="card-header">
                    <h3 class="card-title">编辑用户</h3>
                </div>
                <div class="card-body">
                    @include('layouts.alert')
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="form-label">用户名</label>
                                <input type="text" class="form-control" name="name" placeholder="请输入用户名" value="{{ $user->name }}" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">邮箱</label>
                                <input type="email" class="form-control" name="email" placeholder="请输入用户邮箱,非必填" value="{{ $user->email }}">
                            </div>
                            <div class="form-group">
                                <div class="form-label">用户角色</div>
                                <div class="custom-controls-stacked mt-4">
                                    <label class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="is_admin" value="0"
                                               {{ $user->is_admin ? '' : 'checked' }}>
                                        <span class="custom-control-label">代理用户</span>
                                    </label>
                                    <label class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="is_admin" value="1" {{ $user->is_admin ? 'checked' : '' }}>
                                        <span class="custom-control-label">管理员</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label class="form-label">用户手机号</label>
                                <input type="text" class="form-control" name="phone" placeholder="请输入手机号,非必填" value="{{ $user->phone }}" >
                            </div>
                            <div class="form-group">
                                <label class="form-label">上级代理</label>
                                <select class="js-example-basic-multiple form-control" style="height: 30px" name="pid" required>
                                    <option value="0">系统用户</option>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}" {{ $user->pid == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="card-footer text-right">
                    <div class="d-flex">
                        <button type="submit" class="btn btn-primary btn-block">提交</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
@stop
@section('js')
    <script>
        require(['jquery', 'select2'], function ($, select2) {
            $(document).ready(function() {
                $('.js-example-basic-multiple').select2();
            });
        });
    </script>
@stop
