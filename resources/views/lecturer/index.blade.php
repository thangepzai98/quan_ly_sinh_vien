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
                <li class="active">Quản lý giảng viên</li>
            </ul>
        </div>
        <!-- END BREADCRUMB -->

        <div class="page-header title">
            <!-- PAGE TITLE ROW -->
            <h1>Quản lý giảng viên <span class="sub-title">Danh sách giảng viên</span></h1>
        </div>
        
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="well white">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Tìm kiếm thông tin</h3>
                </div>
                <div class="panel-body">
                    <div class="form-inline" role="form">
                        <div class="form-group">
                            <label for="name">Trình độ</label>
                            <select  id="degree" class="form-control">
                                <option value="">-- Chọn trình độ --</option>
                                <option value="1">Thạc sĩ</option>
                                <option value="2">Tiến sĩ</option>
                                <option value="3">Phó giáo sư</option>
                                <option value="4">Giáo sư</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Khoa</label>
                            <select class="form-control" id="facultyId">
                                <option value="">-- Chọn khoa --</option>
                                @foreach ($faculties as $faculty)
                                    <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Tìm kiếm</label>
                            <input type="text" class="form-control" id="searchWord" placeholder="Tên, mã lớp...">
                        </div>
                        <button type="button" class="btn btn-primary" id="filter">Tìm kiếm</button>
                    </div>
                </div>
            </div>
            <div class="row actions mb-10">
                <div class="col-lg-12">
                    <div class="btn btn-success" data-toggle="modal" data-target="#addModal"><i class="fa fa-plus"></i>  Thêm</div>
                </div>
            </div>
            <table id="tableModel" class="datatable table table-hover table-striped table-bordered tc-table">
                <thead>
                    <tr>
                        <th data-class="expand">STT</th>
                        <th data-hide="phone,tablet">Mã giảng viên</th>
                        <th data-hide="phone,tablet">Tên giảng viên</th>
                        <th data-hide="phone,tablet">Ngày sinh</th>
                        <th data-hide="phone,tablet">Giới tính</th>
                        <th data-hide="phone,tablet">Trình độ</th>
                        <th data-hide="phone,tablet">Khoa</th>
                        <th data-hide="phone,tablet">Ngày tạo</th>
                        <th data-hide="phone,tablet">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            @include('lecturer.create')
            @include('lecturer.edit')
        </div>
        <!-- END YOUR CONTENT HERE -->
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/plugins/datatables/dataTables.editor.min.js') }}"></script> 
<script>
    function formatDateString(str) {
        var str = str.split(/\D/);
        return str.reverse().join('/');
    }

    //Bootstrap Datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: 'dd/mm/yyyy'
    });

    //Select2 
    $("#facultyId").select2({
        width: 175
    });

    // init table
    function fill_datatable(searchWord = '', degree = '', facultyId = '') {
        var responsiveHelper = undefined;
        var breakpointDefinition = {
            tablet: 1024,
            phone : 480
        };
        var tElement = $('#tableModel');
        tElement.DataTable({
            "processing": true,
            "language": {
                "processing": "Đang xử lý",
                "search": "Tìm kiếm ",
                "emptyTable": "Không tìm thấy bản ghi",
                "sLengthMenu":    "Hiển thị _MENU_ bản ghi trên 1 trang",
            },
            "searching": false,
            "serverSide": true,
            "ajax":{
                "url": "{{ url('/getDataLecturer') }}",
                "dataType": "json",
                "type": "post",
                "data":{ _token: "{{csrf_token()}}", searchWord: searchWord, degree: degree, faculty_id: facultyId}
            },
            "order": [[ 0, "desc" ]],
            "columnDefs": [
                {
                "targets": 0,
                "orderable": false,
                },
                {
                "targets": 8,
                "orderable": false,
                }
            ],
            "columns": [
                { "data": "index" },
                { "data": "lecturer_code" },
                { "data": "name"},
                { "data": "date_of_birth" },
                { "data": "sex" },
                { "data": "degree" },
                { "data": "faculty_name" },
                { "data": "created_at" },
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
        })
    }

    //display datatable
    fill_datatable();

    // filter data
    $('#filter').click(function() {
        var searchWord = $('#searchWord').val();
        var degree = $('#degree').val();
        var facultyId = $('#facultyId').val();
        $('#tableModel').DataTable().destroy();
        fill_datatable(searchWord, degree, facultyId);
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
            url: '/createLecturer' ,
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
                form.find("input[name='lecturer_code']").val(response.data.lecturer_code);
                form.find("input[name='name']").val(response.data.name);4
                form.find("input[name='date_of_birth']").val(formatDateString(response.data.date_of_birth));
                form.find('[name="sex"][value="' + response.data.sex + '"]').prop('checked', true);
                form.find('[name="faculty_id"] option[value="' + response.data.faculty_id + '"]').prop('selected', true);
                form.find('[name="degree"] option[value="' + response.data.degree + '"]').prop('selected', true);
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
            url: 'updateLecturer/' + id,
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
