@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="/backend/promotion/{{ $promotion->id }}" method="post" class="card" id="promotion_form"
                  enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="card-header">
                    <h3 class="card-title">编辑推广页</h3>
                </div>
                <input type="hidden" name="music_url" value="{{ $promotion->music_url }}">
                <input type="hidden" name="bg_img" value="{{ $promotion->bg_img }}">
                <div class="card-body">
                    @include('layouts.alert')
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label class="form-label">分享标题</label>
                                <input type="text" class="form-control" name="share_title" value="{{ $promotion->share_title }}" placeholder="请输入分享出去的标题" required>
                            </div>
                            <div class="form-group">
                                <div class="form-label">上传推广音乐</div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="music" id="music">
                                    <label class="custom-file-label" id="mucis_label">选择音乐文件</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-label">上传页面背景图片</div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" name="bg_img_file" id="bg_img">
                                    <label class="custom-file-label" id="bg_img_file">选择图片</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">背景颜色</label>
                                <input type="text" class="form-control" name="bg_color" placeholder="页面背景颜色" value="{{ $promotion->bg_color }}">
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet"/>
@stop
@section('js')
    <script>
        require(['jquery', 'select2', 'sweetalert','selectize'], function ($, select2, sweetalert,selectize) {
            $(document).ready(function () {
                $('.js-example-basic-multiple').select2();
                $("input[name='bg_img_file']").change(function () {
                    let fd = new FormData();
                    fd.append('imgs',$("input[name='bg_img_file']")[0].files[0]);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $("#bg_img_file").html("正在上传中");
                    $.ajax({
                        url: "/common/upload_images",
                        type: 'POST',
                        cache: false,
                        data: fd,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                    }).done(function (data) {
                        if (data.status === 1) {
                            swal(data.msg, {
                                dangerMode: true,
                                buttons: false,
                                timer: 2000,
                            });
                            return 1;
                        }
                        $("#bg_img_file").html(data.data.path);
                        $("input[name='bg_img']").val(data.data.path);
                    }).fail(function (data) {
                        swal('文件上传失败', {
                            dangerMode: true,
                            buttons: false,
                            timer: 2000,
                        });
                    })
                });
                $("input[name='music']").change(function () {
                    let fd = new FormData();
                    fd.append('music_file', $("#music")[0].files[0]);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $("#mucis_label").html("正在上传中");
                    $.ajax({
                        url: "/common/upload_music",
                        type: 'POST',
                        cache: false,
                        data: fd,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                    }).done(function (data) {
                        if (data.status === 1) {
                            swal(data.msg, {
                                dangerMode: true,
                                buttons: false,
                                timer: 2000,
                            });
                            $("#mucis_label").html("上传失败,请重新选择文件");
                            return 1;
                        }
                        $("input[name='music_url']").val(data.data.path);
                        $("#mucis_label").html(data.data.path);
                    }).fail(function (data) {
                        console.log(data);
                        swal('文件上传失败', {
                            dangerMode: true,
                            buttons: false,
                            timer: 2000,
                        });
                    })
                });
                $("input[id='img_urls']").change(function () {
                    let fd = new FormData();
                    let obj = $("#img_urls");
                    fd.append('imgs', obj[0].files[0]);
                    let origin_name = obj[0].files[0].name;
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: "/common/upload_images",
                        type: 'POST',
                        cache: false,
                        data: fd,
                        dataType: 'json',
                        processData: false,
                        contentType: false,
                    }).done(function (data) {
                        if (data.status === 1) {
                            swal(data.msg, {
                                dangerMode: true,
                                buttons: false,
                                timer: 2000,
                            });
                            return 1;
                        }
                        let tr_html = "<tr>";
                        tr_html += "<td>" + origin_name + "</td>";
                        tr_html += "<td>" + data.data.path + "</td>";
                        tr_html += "<td style='display: none'><input type='hidden' name='img_urls[]' value='" + data.data.path + "'></td>";
                        tr_html += "<td><button class='btn btn-sm btn-danger'>删除</button></td></tr>";
                        $("#img_table tbody").append(tr_html);
                    }).fail(function (data) {
                        swal('文件上传失败', {
                            dangerMode: true,
                            buttons: false,
                            timer: 2000,
                        });
                    })
                });
            });
        });

    </script>
@stop
