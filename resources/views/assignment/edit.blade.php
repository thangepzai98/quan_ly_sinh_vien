<!-- Edit Modal -->
<div class="modal fade modal-scroll" id="editModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-circle"></i> Chỉnh sửa phân công</h4>
            </div>
            <div class="modal-body padding-2x">
                <form role="form" method="post" id="fmEdit" action="javascript:void(0)" enctype="multipart/form-data">
                    <div id="editError"></div>   
                    <div class="form-group">
                        <label>Môn học</label> (<span style="color:red">*</span>)
                        <select class="form-control liveSearchModal" name="subject_id">
                            @foreach ($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }} - {{ $subject->subject_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lớp</label> (<span style="color:red">*</span>)
                        <select class="form-control liveSearchModal" name="class_id">
                            @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Giảng viên</label> (<span style="color:red">*</span>)
                        <select class="form-control liveSearchModal" name="lecturer_id">
                            @foreach ($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}">{{ $lecturer->name }} - {{ $lecturer->lecturer_code }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Học kỳ</label> (<span style="color:red">*</span>)
                        <select class="form-control liveSearchModal" name="semester">
                           <option value="1">Học kỳ 1</option>
                           <option value="2">Học kỳ 2</option>
                           <option value="3">Học kỳ 3</option>
                           <option value="4">Học kỳ 4</option>
                           <option value="5">Học kỳ 5</option>
                           <option value="6">Học kỳ 6</option>
                           <option value="7">Học kỳ 7</option>
                           <option value="8">Học kỳ 8</option>
                           <option value="9">Học kỳ 9</option>
                           <option value="10">Học kỳ 10</option>
                        </select>
                    </div>
                    <div class="btn-group">
                        <input type="hidden" name="id">
                        <button type="submit" class="btn btn-primary" id="btnEdit"><i class="fa fa-floppy-o" title="Save"></i> Lưu</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->