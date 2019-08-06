@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="/backend/ad/{{ $ad->id }}" method="post" class="card">
                @csrf
                @method('PATCH')
                <div class="card-header">
                    <h3 class="card-title">编辑广告</h3>
                </div>
                <div class="card-body">
                    @include('layouts.alert')
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label class="form-label">广告链接</label>
                                <input type="text" class="form-control" name="url" placeholder="请输入广告链接" value="{{ $ad->url }}" required>
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


