@extends('templates.base')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <!-- BEGIN BREADCRUMB -->
        <div class="breadcrumbs fixed">
            <ul class="breadcrumb">
                <li>
                    <a href="#">Trang chủ</a>
                </li>
                <li class="active">Quản lý môn học</li>
            </ul>
        </div>
        <!-- END BREADCRUMB -->

        <div class="page-header title">
            <!-- PAGE TITLE ROW -->
            <h1>Quản lý môn học <span class="sub-title">Danh sách môn học</span></h1>
        </div>

        <!-- /#ek-layout-button -->
        <div class="qs-layout-menu">
            <div class="btn btn-gray qs-setting-btn" id="qs-setting-btn">
                <i class="fa fa-cog bigger-150 icon-only"></i>
            </div>
            <div class="qs-setting-box" id="qs-setting-box">
                <div class="hidden-xs hidden-sm">
                    <span class="bigger-120">Layout Options</span>

                    <div class="hr hr-dotted hr-8"></div>
                    <label>
                        <input type="checkbox" class="tc" id="fixed-navbar" />
                        <span id="#fixed-navbar" class="labels"> Fixed NavBar</span>
                    </label>
                    <label>
                        <input type="checkbox" class="tc" id="fixed-sidebar" />
                        <span id="#fixed-sidebar" class="labels"> Fixed NavBar+SideBar</span>
                    </label>
                    <label>
                        <input type="checkbox" class="tc" id="sidebar-toggle" />
                        <span id="#sidebar-toggle" class="labels"> Sidebar Toggle</span>
                    </label>
                    <label>
                        <input type="checkbox" class="tc" id="in-container" />
                        <span id="#in-container" class="labels"> Inside<strong>.container</strong></span>
                    </label>

                    <div class="space-4"></div>
                </div>

                <span class="bigger-120">Color Options</span>

                <div class="hr hr-dotted hr-8"></div>

                <label>
                    <input type="checkbox" class="tc" id="side-bar-color" />
                    <span id="#side-bar-color" class="labels"> SideBar (Light)</span>
                </label>

                <ul>
                    <li><button class="btn" style="background-color: #d15050;" onclick="swapStyle('assets/css/themes/style.css')"></button></li>
                    <li><button class="btn" style="background-color: #86618f;" onclick="swapStyle('assets/css/themes/style-1.css')"></button></li>
                    <li><button class="btn" style="background-color: #ba5d32;" onclick="swapStyle('assets/css/themes/style-2.css')"></button></li>
                    <li><button class="btn" style="background-color: #488075;" onclick="swapStyle('assets/css/themes/style-3.css')"></button></li>
                    <li><button class="btn" style="background-color: #4e72c2;" onclick="swapStyle('assets/css/themes/style-4.css')"></button></li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="well white">
            <div class="row actions">
                <div class="col-lg-12">
                    <div class="btn btn-success" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus"></i>  Thêm</div>
                </div>
            </div>
            <table id="tableModel" class="datatable table table-hover table-striped table-bordered tc-table">
                <thead>
                    <tr>
                        <th data-class="expand">STT</th>
                        <th data-hide="phone,tablet">Mã môn học</th>
                        <th data-hide="phone,tablet">Tên môn học</th>
                        <th data-hide="phone,tablet">Số tín chỉ</th>
                        <th data-hide="phone,tablet">Ngày tạo</th>
                        <th data-hide="phone,tablet">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            @include('subject.create')
            @include('subject.edit')
        </div>
        <!-- END YOUR CONTENT HERE -->
    </div>
</div>
@endsection

@section('scripts')
<script>

    //for search 
    $(".liveSearch").select2({
        width: 175,
    });

    //for modal
    $(".liveSearchModal").select2({
        width: '100%',
    });

    // select mutiple
    $(".selectMutiple").select2({
        placeholder: "Select a Option",
        width: '100%',
        allowClear: true
	});
    
    // display datatable
    var responsiveHelper = undefined;
    var breakpointDefinition = {
        tablet: 1024,
        phone: 480
    };
    var tElement = $('#tableModel');
    var table = $('#tableModel').DataTable({
        "processing": true,
        "language": {
            "processing": "Đang xử lý",
            "search": "Tìm kiếm",
            "emptyTable": "Không tìm thấy bản ghi",
            "sLengthMenu": "Hiển thị _MENU_ bản ghi trên 1 trang",
        },
        "serverSide": true,
        "ajax": {
            "url": "{{ url('/getDataSubject') }}",
            "dataType": "json",
            "type": "post",
            "data": { _token: "{{csrf_token()}}" }
        },
        "order": [[ 0, "desc" ]],
        "columnDefs": [
            {
                "targets": 0,
                "orderable": false,
            },
            {
                "targets": 5,
                "orderable": false,
            }
        ],
        "columns": [
            { "data": "index" },
            { "data": "subject_code" },
            { "data": "name"},
            { "data": "credit"},
            { "data": "created_at"},
            { "data": "options" }
        ],
        "autoWidth": false,
        preDrawCallback: function () {
            // Initialize the responsive datatables helper once.
            if (!responsiveHelper) {
                responsiveHelper = new ResponsiveDatatablesHelper(tElement, breakpointDefinition);
            }
        },
        rowCallback: function (nRow) {
            responsiveHelper.createExpandIcon(nRow);
        },
        drawCallback: function (oSettings) {
            responsiveHelper.respond();
        }
    });

    // add data
    $('#btnAdd').on('click', function (e) {
        e.preventDefault();
        var table = $('#tableModel').DataTable();
        var form = $(this).closest('form');
        var btnSubmit = $(this);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        btnSubmit.attr("disabled", true);
        btnSubmit.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý');
        $.ajax({
            url: '/createSubject' ,
            type: "POST",
            data: form.serialize(),
            success: function(response) {
                if (response.status !== 1) {
                    btnSubmit.html('<i class="fa fa-floppy-o"></i> Lưu');
                    $('#addError').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + response.message + '</div>');
                    btnSubmit.attr("disabled", false);
                } else {
                    form.trigger("reset");
                    form.find('input').val('');
                    btnSubmit.html('<i class="fa fa-floppy-o"></i> Lưu');
                    $('#addError').html('');
                    $('#addModal').modal('hide');
                    $.gritter.add({
                        title: response.message,
                        class_name: "bg-success",
                        sticky: false
                    });
                    if(typeof table !== 'undefined' && table !== null) {
                        table.draw();
                    }
                    btnSubmit.attr("disabled", false);
                }
            }
        });
    });

    // edit
    $("body").on("click", ".editRecord", function () {
        var id_edit = $(this).data('id');
        var url_edit = $(this).data('url');
        var method_edit = $(this).data('method');
        var form = $('#fmEdit');
        $.ajax({
            type: method_edit,
            url: url_edit,
            data: { id: id_edit },
            success: function (response) {
                console.log(response.data);
                form.find("input[name='id']").val(response.data.id);
                form.find("input[name='subject_code']").val(response.data.subject_code);
                form.find("input[name='name']").val(response.data.name);
                form.find("input[name='credit']").val(response.data.credit);
            }
        });
    });

    //update
    $('#btnEdit').on('click', function () {
        var table = $('#tableModel').DataTable();
        var form = $(this).closest('form');
        var btnSubmit = $(this);
        var id = form.find("input[name='id']").val();
        btnSubmit.attr("disabled", true);
        btnSubmit.html('<i class="fa fa-spinner fa-spin"></i> Đang xử lý');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: 'updateSubject/' + id,
            type: "POST",
            data: form.serialize(),
            success: function (response) {
                if (response.status !== 1) {
                    btnSubmit.html('<i class="fa fa-floppy-o"></i> Lưu');
                    $('#editError').html('<div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>' + response.message + '</div>');
                    btnSubmit.attr("disabled", false);
                } else {
                    btnSubmit.html('<i class="fa fa-floppy-o"></i> Lưu');
                    $('#editError').html('');
                    $('#editModal').modal('hide');
                    $.gritter.add({
                        title: response.message,
                        class_name: "bg-success",
                        sticky: false
                    });
                    if(typeof table !== 'undefined' && table !== null) {
                        table.draw();
                    }
                    btnSubmit.attr("disabled", false);
                }
            }
        });
    });

    // remove record
    $(document).ready(function() {
    $('body').on('click', '.removeRecord', function (event) {
        event.preventDefault();
        var deleteTitle = $(this).data('delete-title');
        if (confirm('Bạn chắc chắn muốn xóa ' + deleteTitle)) {
            var deleteMethod = $(this).data('method');// request method POST or DELETE ,...
            var urlDelete = $(this).data('url');// request URL
            var table_name = $(this).data('template');
            var table = $(table_name).DataTable();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url: urlDelete,
                type: deleteMethod,
                success: function(response) {
                    console.log(response);
                    if (response.status !== 1) {
                        iziToast.error({
                        message: response.message,
                        position: 'topRight'
                        });
                    } else {
                        $.gritter.add({
                            title: response.message,
                            class_name: "bg-success",
                            sticky: false
                        });
                        if(typeof table !== 'undefined' && table !== null) {
                            table.draw();
                        }
                    }
                }
            });
        }
    });
    });
</script>
@endsection
