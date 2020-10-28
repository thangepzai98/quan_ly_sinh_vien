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
                <li class="active">Quản lý điểm</li>
            </ul>
        </div>
        <!-- END BREADCRUMB -->

        <div class="page-header title">
            <!-- PAGE TITLE ROW -->
            <h1>Quản lý điểm <span class="sub-title">Danh sách điểm</span></h1>
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
                            <label>Lớp</label>
                            <select class="form-control" id="classId">
                                <option value="">-- Chọn lớp --</option>
                                @foreach ($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Học phần</label>
                            <select class="form-control" id="subjectId">
                                <option value="">-- Chọn học phần --</option>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="name">Tìm kiếm</label>
                            <input type="text" class="form-control" id="searchWord">
                        </div>
                        <button type="button" class="btn btn-primary" id="filter">Tìm kiếm</button>
                    </div>
                </div>
            </div>
            <div class="row actions mb-10">
                <div class="col-lg-12">
                    
                </div>
            </div>
            <table id="tableModel" class="datatable table table-hover table-striped table-bordered tc-table">
                <thead>
                    <tr>
                        <th data-class="expand">STT</th>
                        <th data-hide="phone,tablet">Mã sinh viên</th>
                        <th data-hide="phone,tablet">Họ tên sinh viên</th>
                        <th data-hide="phone,tablet">Lớp</th>
                        <th data-hide="phone,tablet">Học phần</th>
                        <th data-hide="phone,tablet">Điểm chuyên cần</th>
                        <th data-hide="phone,tablet">Điểm kiểm tra</th>
                        <th data-hide="phone,tablet">Điểm thi</th>
                        <th data-hide="phone,tablet">Ngày tạo</th>
                        <th data-hide="phone,tablet">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            @include('score.edit')
        </div>
        <!-- END YOUR CONTENT HERE -->
    </div>
</div>
@endsection

@section('scripts')
<script>
    //Select2 
    $("#classId, #subjectId").select2({
        width: 175
    });

    // init table
    function fill_datatable(searchWord = '', classId = '', subjectId = '') {
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
                "url": "{{ url('/getDataScore') }}",
                "dataType": "json",
                "type": "post",
                "data":{ _token: "{{csrf_token()}}", searchWord: searchWord, class_id: classId, subject_id: subjectId}
            },
            "order": [[ 0, "desc" ]],
            "columnDefs": [
                {
                "targets": 0,
                "orderable": false,
                },
                {
                "targets": 1,
                "orderable": false,
                },
                {
                "targets": 2,
                "orderable": false,
                },
                {
                "targets": 3,
                "orderable": false,
                },
                {
                "targets": 4,
                "orderable": false,
                },
                {
                "targets": 5,
                "orderable": false,
                },
                {
                "targets": 6,
                "orderable": false,
                },
                {
                "targets": 7,
                "orderable": false,
                },
                {
                "targets": 8,
                "orderable": false,
                },
                {
                "targets": 9,
                "orderable": false,
                },
            ],
            "columns": [
                { "data": "index" },
                { "data": "student_code" },
                { "data": "student_name" },
                { "data": "class_name"},
                { "data": "subject_name"},
                { "data": "score_1" },
                { "data": "score_2" },
                { "data": "score_3" },
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

    // filter data
    $('#filter').click(function() {
        var searchWord = $('#searchWord').val();
        var classId = $('#classId').val();
        var subjectId = $('#subjectId').val();
        if(classId == '') {
            alert('Vui lòng chọn lớp');
            return;
        }
        if(subjectId == '') {
            alert('Vui lòng chọn học phần');
            return;
        }
        $('#tableModel').DataTable().destroy();
        fill_datatable(searchWord, classId, subjectId);
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
                form.find("input[name='score_1']").val(response.data.score_1);
                form.find("input[name='score_2']").val(response.data.score_2);
                form.find("input[name='score_3']").val(response.data.score_3);
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
            url: 'updateScore/' + id,
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
