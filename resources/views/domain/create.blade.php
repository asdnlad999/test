@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="/backend/domain" method="post" class="card">
                @csrf
                <div class="card-header">
                    <h3 class="card-title">添加域名</h3>
                </div>
                <div class="card-body">
                    @include('layouts.alert')
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label class="form-label">域名地址</label>
                                <input type="text" class="form-control" name="domain_addr" placeholder="例如:jd.com" required>
                            </div>
                            <div class="form-group">
                                <div class="form-label">域名类型</div>
                                <div class="custom-controls-stacked mt-4">
                                    <label class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="type" value="2" checked>
                                        <span class="custom-control-label">落地域名</span>
                                    </label>
                                    <label class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="type" value="1">
                                        <span class="custom-control-label">入口域名</span>
                                    </label>
                                    <label class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="type" value="3">
                                        <span class="custom-control-label">广告域名</span>
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">是否启用</div>
                                <div class="custom-controls-stacked mt-4">
                                    <label class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="is_use" value="1"
                                               checked>
                                        <span class="custom-control-label">启用</span>
                                    </label>
                                    <label class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" class="custom-control-input" name="is_use" value="0">
                                        <span class="custom-control-label">禁用</span>
                                    </label>
                                </div>
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

