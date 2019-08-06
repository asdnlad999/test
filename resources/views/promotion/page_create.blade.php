@extends('layouts.main')

@section('content')
    <div class="row">
        <div class="col-12">
            <form action="/backend/promotion/page/create" method="post" class="card" id="promotion_form"
                  enctype="multipart/form-data">
                @csrf
                <div class="card-header">
                    <h3 class="card-title">添加页面</h3>
                </div>
                <input type="hidden" name="background_image" value="">
                <div class="card-body">
                    @include('layouts.alert')
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <input type="hidden" name="promotion_id" value="{{ $promotion_id }}">
                            <div class="form-group">
                                <label class="form-label">页面边距</label>
                                <input type="text" class="form-control" name="padding" placeholder="请输入页面边距,例如:5" required>
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
                                <input type="text" class="form-control" name="background_color" placeholder="页面背景颜色,例如#ccc">
                            </div>

                            <div class="form-group">
                                <div class="form-label">上传页面图片</div>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="img_urls">
                                    <label class="custom-file-label" id="img_urls_label">选择图片(一次传一张)</label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="card">
                                    <table class="table card-table table-vcenter" id="img_table">
                                        <tbody>
                                        <tr>
                                            <th>图片名称</th>
                                            <th class="d-none d-sm-table-cell">广告链接</th>
                                            <th class="d-none d-sm-table-cell">宽度</th>
                                            <th class="d-none d-sm-table-cell">高度</th>
                                            <th class="d-none d-sm-table-cell">顶部高度</th>
                                            <th class="d-none d-sm-table-cell">左边距</th>
                                            <th class="d-none d-sm-table-cell">动画方式</th>
                                            <th class="d-none d-sm-table-cell">动画延迟</th>
                                            <th class="d-none d-md-table-cell">操作</th>
                                        </tr>
{{--                                        <tr>--}}
{{--                                            <td>111</td>--}}
{{--                                            <td><input type="text" style="width: 100px" required placeholder="http://www.baidu.com"></td>--}}
{{--                                            <td><input type="text" style="width: 60px" required placeholder="100%"></td>--}}
{{--                                            <td><input type="text" style="width: 60px" required placeholder="40%"></td>--}}
{{--                                            <td><input type="text" style="width: 60px" required placeholder="50%"></td>--}}
{{--                                            <td><input type="text" style="width: 60px" required placeholder="1"></td>--}}
{{--                                            <td><input type="text" style="width: 60px" required placeholder="0.5"></td>--}}
{{--                                            <td><button type="button" class="btn btn-sm btn-danger">删除</button></td>--}}
{{--                                        </tr>--}}
                                        </tbody>
                                    </table>
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
                        $("input[name='background_image']").val(data.data.path);
                    }).fail(function (data) {
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
                        let tr_html = " <tr>\n" +
                            "<td>"+ origin_name +"</td>\n" +
                            "<td><input type=\"text\" style=\"width: 100px\" name='href[]'  placeholder=\"http://www.baidu.com\"></td>\n" +
                            "<td><input type=\"text\" style=\"width: 60px\" required name='width[]' value='100%' placeholder=\"100%\"></td>\n" +
                            "<td><input type=\"text\" style=\"width: 60px\" required name='height[]' value='100%' placeholder=\"40%\"></td>\n" +
                            "<td><input type=\"text\" style=\"width: 60px\" required name='top[]' value='100%' placeholder=\"50%\"></td>\n" +
                            "<td><input type=\"text\" style=\"width: 60px\" required name='left[]' value='0%' placeholder=\"0%\"></td>\n" +
                            "<td><select name='animation[]' class=\"input input--dropdown js--animations form-control\">\n" +
                            "        <optgroup label=\"Attention Seekers\">\n" +
                            "          <option value=\"v-flash\">v-flash\</option>\n" +
                            "          <option value=\"bounce\">bounce</option>\n" +
                            "          <option value=\"flash\">flash</option>\n" +
                            "          <option value=\"pulse\">pulse</option>\n" +
                            "          <option value=\"rubberBand\">rubberBand</option>\n" +
                            "          <option value=\"shake\">shake</option>\n" +
                            "          <option value=\"swing\">swing</option>\n" +
                            "          <option value=\"tada\">tada</option>\n" +
                            "          <option value=\"wobble\">wobble</option>\n" +
                            "          <option value=\"jello\">jello</option>\n" +
                            "          <option value=\"heartBeat\">heartBeat</option>\n" +
                            "        </optgroup>\n" +
                            "\n" +
                            "        <optgroup label=\"Bouncing Entrances\">\n" +
                            "          <option value=\"bounceIn\">bounceIn</option>\n" +
                            "          <option value=\"bounceInDown\">bounceInDown</option>\n" +
                            "          <option value=\"bounceInLeft\">bounceInLeft</option>\n" +
                            "          <option value=\"bounceInRight\">bounceInRight</option>\n" +
                            "          <option value=\"bounceInUp\">bounceInUp</option>\n" +
                            "        </optgroup>\n" +
                            "\n" +
                            "        <optgroup label=\"Bouncing Exits\">\n" +
                            "          <option value=\"bounceOut\">bounceOut</option>\n" +
                            "          <option value=\"bounceOutDown\">bounceOutDown</option>\n" +
                            "          <option value=\"bounceOutLeft\">bounceOutLeft</option>\n" +
                            "          <option value=\"bounceOutRight\">bounceOutRight</option>\n" +
                            "          <option value=\"bounceOutUp\">bounceOutUp</option>\n" +
                            "        </optgroup>\n" +
                            "\n" +
                            "        <optgroup label=\"Fading Entrances\">\n" +
                            "          <option value=\"fadeIn\">fadeIn</option>\n" +
                            "          <option value=\"fadeInDown\">fadeInDown</option>\n" +
                            "          <option value=\"fadeInDownBig\">fadeInDownBig</option>\n" +
                            "          <option value=\"fadeInLeft\">fadeInLeft</option>\n" +
                            "          <option value=\"fadeInLeftBig\">fadeInLeftBig</option>\n" +
                            "          <option value=\"fadeInRight\">fadeInRight</option>\n" +
                            "          <option value=\"fadeInRightBig\">fadeInRightBig</option>\n" +
                            "          <option value=\"fadeInUp\">fadeInUp</option>\n" +
                            "          <option value=\"fadeInUpBig\">fadeInUpBig</option>\n" +
                            "        </optgroup>\n" +
                            "\n" +
                            "        <optgroup label=\"Fading Exits\">\n" +
                            "          <option value=\"fadeOut\">fadeOut</option>\n" +
                            "          <option value=\"fadeOutDown\">fadeOutDown</option>\n" +
                            "          <option value=\"fadeOutDownBig\">fadeOutDownBig</option>\n" +
                            "          <option value=\"fadeOutLeft\">fadeOutLeft</option>\n" +
                            "          <option value=\"fadeOutLeftBig\">fadeOutLeftBig</option>\n" +
                            "          <option value=\"fadeOutRight\">fadeOutRight</option>\n" +
                            "          <option value=\"fadeOutRightBig\">fadeOutRightBig</option>\n" +
                            "          <option value=\"fadeOutUp\">fadeOutUp</option>\n" +
                            "          <option value=\"fadeOutUpBig\">fadeOutUpBig</option>\n" +
                            "        </optgroup>\n" +
                            "\n" +
                            "        <optgroup label=\"Flippers\">\n" +
                            "          <option value=\"flip\">flip</option>\n" +
                            "          <option value=\"flipInX\">flipInX</option>\n" +
                            "          <option value=\"flipInY\">flipInY</option>\n" +
                            "          <option value=\"flipOutX\">flipOutX</option>\n" +
                            "          <option value=\"flipOutY\">flipOutY</option>\n" +
                            "        </optgroup>\n" +
                            "\n" +
                            "        <optgroup label=\"Lightspeed\">\n" +
                            "          <option value=\"lightSpeedIn\">lightSpeedIn</option>\n" +
                            "          <option value=\"lightSpeedOut\">lightSpeedOut</option>\n" +
                            "        </optgroup>\n" +
                            "\n" +
                            "        <optgroup label=\"Rotating Entrances\">\n" +
                            "          <option value=\"rotateIn\">rotateIn</option>\n" +
                            "          <option value=\"rotateInDownLeft\">rotateInDownLeft</option>\n" +
                            "          <option value=\"rotateInDownRight\">rotateInDownRight</option>\n" +
                            "          <option value=\"rotateInUpLeft\">rotateInUpLeft</option>\n" +
                            "          <option value=\"rotateInUpRight\">rotateInUpRight</option>\n" +
                            "        </optgroup>\n" +
                            "\n" +
                            "        <optgroup label=\"Rotating Exits\">\n" +
                            "          <option value=\"rotateOut\">rotateOut</option>\n" +
                            "          <option value=\"rotateOutDownLeft\">rotateOutDownLeft</option>\n" +
                            "          <option value=\"rotateOutDownRight\">rotateOutDownRight</option>\n" +
                            "          <option value=\"rotateOutUpLeft\">rotateOutUpLeft</option>\n" +
                            "          <option value=\"rotateOutUpRight\">rotateOutUpRight</option>\n" +
                            "        </optgroup>\n" +
                            "\n" +
                            "        <optgroup label=\"Sliding Entrances\">\n" +
                            "          <option value=\"slideInUp\">slideInUp</option>\n" +
                            "          <option value=\"slideInDown\">slideInDown</option>\n" +
                            "          <option value=\"slideInLeft\">slideInLeft</option>\n" +
                            "          <option value=\"slideInRight\">slideInRight</option>\n" +
                            "\n" +
                            "        </optgroup>\n" +
                            "        <optgroup label=\"Sliding Exits\">\n" +
                            "          <option value=\"slideOutUp\">slideOutUp</option>\n" +
                            "          <option value=\"slideOutDown\">slideOutDown</option>\n" +
                            "          <option value=\"slideOutLeft\">slideOutLeft</option>\n" +
                            "          <option value=\"slideOutRight\">slideOutRight</option>\n" +
                            "          \n" +
                            "        </optgroup>\n" +
                            "        \n" +
                            "        <optgroup label=\"Zoom Entrances\">\n" +
                            "          <option value=\"zoomIn\" selected>zoomIn</option>\n" +
                            "          <option value=\"zoomInDown\">zoomInDown</option>\n" +
                            "          <option value=\"zoomInLeft\">zoomInLeft</option>\n" +
                            "          <option value=\"zoomInRight\">zoomInRight</option>\n" +
                            "          <option value=\"zoomInUp\">zoomInUp</option>\n" +
                            "        </optgroup>\n" +
                            "        \n" +
                            "        <optgroup label=\"Zoom Exits\">\n" +
                            "          <option value=\"zoomOut\">zoomOut</option>\n" +
                            "          <option value=\"zoomOutDown\">zoomOutDown</option>\n" +
                            "          <option value=\"zoomOutLeft\">zoomOutLeft</option>\n" +
                            "          <option value=\"zoomOutRight\">zoomOutRight</option>\n" +
                            "          <option value=\"zoomOutUp\">zoomOutUp</option>\n" +
                            "        </optgroup>\n" +
                            "\n" +
                            "        <optgroup label=\"Specials\">\n" +
                            "          <option value=\"hinge\">hinge</option>\n" +
                            "          <option value=\"jackInTheBox\">jackInTheBox</option>\n" +
                            "          <option value=\"rollIn\">rollIn</option>\n" +
                            "          <option value=\"rollOut\">rollOut</option>\n" +
                            "        </optgroup>\n" +
                            "      </select></td>" +
                            "<td style='display: none;'><input type='hidden' name='src[]' value='"+ data.data.path +"'></td>" +
                            "<td><input type=\"text\" style=\"width: 60px\" required name='delay[]' value='0' placeholder=\"0.5\"></td>\n" +
                            "<td><button type=\"button\" class=\"btn btn-sm btn-danger\" onclick=\"this.parentNode.parentNode.parentNode.removeChild(this.parentNode.parentNode)\">删除</button></td>\n" +
                            "</tr>";
                        // let tr_html = "<tr>";
                        // tr_html += "<td>" + origin_name + "</td>";
                        // tr_html += "<td>" + data.data.path + "</td>";
                        // tr_html += "<td style='display: none'><input type='hidden' name='img_urls[]' value='" + data.data.path + "'></td>";
                        // tr_html += "<td><button class='btn btn-sm btn-danger'>删除</button></td></tr>";
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
