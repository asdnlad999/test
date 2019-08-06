@extends('layouts.main')

@section('content')
    <div class="page-header">
        <h1 class="page-title">
            推广页面列表
        </h1>
        <div class="page-options d-flex">
            <a href="/backend/promotion/page/create/{{ $promotion_id }}" class="btn btn-outline-success "> <i class="fe fe-plus mr-2"></i>添加页面</a>
        </div>
    </div>
    <div class="row row-cards">

        <div class="col-lg-12">
            <div class="card">
                @include('layouts.alert')
                <table class="table card-table table-vcenter">
                    <tbody>
                    <tr>
                        <th>页面边距</th>
                        <th>背景颜色</th>
                        <th>背景图片</th>
                        <th class="d-none d-md-table-cell">操作</th>
                    </tr>
                    @foreach ($pages as $page)
                        <tr id="page-{{ $page->id }}">
                            <td>{{ $page->padding }}</td>
                            <td>{{ $page->background_color }}</td>
                            <td><a href="{{ $page->background_image }}" class="btn btn-sm btn-outline-success" target="_blank">背景图片</a></td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-dark"  data-toggle="modal" data-target="#exampleModal" data-id="{{ $page->id }}"><i class="fe fe-eye"></i>图片列表</button>
                                <a href="/backend/promotion/page/edit/{{ $page->id }}" class="btn btn-sm btn-outline-info"><i class="fe fe-edit"></i>编辑</a>
                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="delPromotionPage('{{ $page->id }}')"><i class="fe fe-trash-2"></i>删除
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">图片列表</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <table class="table card-table table-vcenter" id="img_table">
                            <tbody>
                            <tr>
                                <th>图片链接</th>
                                <th class="d-none d-sm-table-cell">广告链接</th>
                                <th class="d-none d-sm-table-cell">宽度</th>
                                <th class="d-none d-sm-table-cell">高度</th>
                                <th class="d-none d-sm-table-cell">顶部高度</th>
                                <th class="d-none d-sm-table-cell">左边距</th>
                                <th class="d-none d-sm-table-cell">动画方式</th>
                                <th class="d-none d-sm-table-cell">动画延迟</th>
                            </tr>
{{--                            <tr>--}}
{{--                                <td><a href="http://www.baidu.com" class="btn btn-sm btn-outline-dark" target="_blank">图片链接地址</a></td>--}}
{{--                                <td><a href="http://www.baidu.com" class="btn btn-outline-success btn-sm" target="_blank">广告链接地址</a></td>--}}
{{--                                <td>100%</td>--}}
{{--                                <td>100%</td>--}}
{{--                                <td>50%</td>--}}
{{--                                <td>1</td>--}}
{{--                                <td>0.6</td>--}}
{{--                            </tr>--}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                </div>
            </div>
        </div>
    </div>
@stop
@section('js')
    <script>
        require(['jquery', 'sweetalert'], function ($, swal) {
            $("#exampleModal").on("show.bs.modal",function (e) {
                let id = $(e.relatedTarget).data("id");
                getImgs(id,$)
            });
            $("#exampleModal").on("hidden.bs.modal",function (e) {
                $("#img_table tr:gt(0)").remove();
            });
        });
        function showUrl(uuid) {
            $.ajax({
                url: "/backend/show_page_url",
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
        function delPromotionPage(id) {
            swal({
                title: "删除确认",
                text: "确认删除该页面吗",
                icon: "warning",
                buttons: ['取消','确认'],
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    $.ajax({
                        url:'/backend/promotion/page/delete/'+id,
                        type:'GET',
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
                            $("#page-"+data.data.id).remove();
                            swal(data.msg, {
                                icon: "success",
                            });
                        }
                    });

                }
            });
        }
        function getImgs(id,$) {
            $.get("/backend/promotion/page/get_img/"+id,{},function (data) {
                $.each(data.data.pictures,function (i,v) {
                    let html_str = "<tr>\n" +
                        "<td><a href="+v.src +" class=\"btn btn-sm btn-outline-dark\" target=\"_blank\">图片链接地址</a></td>\n" +
                        "<td><a href="+ v.href +" class=\"btn btn-outline-success btn-sm\" target=\"_blank\">广告链接地址</a></td>\n" +
                        "<td>"+ v.width +"</td>\n" +
                        "<td>"+ v.height +"</td>\n" +
                        "<td>"+ v.top +"</td>\n" +
                        "<td>"+ v.left +"</td>\n" +
                        "<td>"+ v.animation +"</td>\n" +
                        "<td>"+ v.delay +"</td>\n" +
                        "</tr>";
                    $("#img_table").append(html_str);
                })
            });
        }
    </script>
@stop