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
                        <th data-hide="phone,tablet">Tên khoa môn học</th>
                        <th data-hide="phone,tablet">Ngày tạo</th>
                        <th data-hide="phone,tablet">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            @include('faculty.create')
            @include('faculty.edit')
        </div>
        <!-- END YOUR CONTENT HERE -->
    </div>
</div>
@endsection

@section('scripts')
<script>
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
            "url": "{{ url('/getDataFaculty') }}",
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
                "targets": 4,
                "orderable": false,
            }
        ],
        "columns": [
            { "data": "index" },
            { "data": "faculty_code" },
            { "data": "name"},
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
            url: '/createFaculty' ,
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
                form.find("input[name='faculty_code']").val(response.data.faculty_code);
                form.find("input[name='name']").val(response.data.name);
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
            url: 'updateFaculty/' + id,
            type: "POST",
            data: form.serialize(),
            success: function (response) {
                console.log(response);
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
