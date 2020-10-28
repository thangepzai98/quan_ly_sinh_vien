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
                <li class="active">Danh sách kết quả học tập</li>
            </ul>
        </div>
        <!-- END BREADCRUMB -->

        <div class="page-header title">
            <!-- PAGE TITLE ROW -->
            <h1>Danh sách kết quả học tập </h1>
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
                            <label for="name">Tìm kiếm</label>
                            <input type="text" class="form-control" id="searchWord">
                        </div>
                        <button type="button" class="btn btn-primary" id="filter">Tìm kiếm</button>
                    </div>
                </div>
            </div>
            <div class="row actions mb-10">
                <div class="col-lg-12">
                    <a href="/getStudyAgainExport" class="btn btn-success"><i class="fa fa-file-excel-o" aria-hidden="true"></i>Xuất excel</a>
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
                        <th data-hide="phone,tablet">Điểm trung bình</th>
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
                "url": "{{ url('/getDataStudyAgain') }}",
                "dataType": "json",
                "type": "post",
                "data":{ _token: "{{csrf_token()}}", searchWord: searchWord, class_id: classId, subject_id: subjectId}
            },
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
                { "data": "total_score" }
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

    fill_datatable();

    // filter data
    $('#filter').click(function() {
        var searchWord = $('#searchWord').val();
        var classId = $('#classId').val();
        var subjectId = $('#subjectId').val();
        $('#tableModel').DataTable().destroy();
        fill_datatable(searchWord, classId, subjectId);
    });
</script>
@endsection
