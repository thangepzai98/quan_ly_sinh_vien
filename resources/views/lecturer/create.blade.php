<!-- Add Modal -->
<div class="modal fade modal-scroll" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-circle"></i> Thêm giảng viên</h4>
            </div>
            <div class="modal-body padding-2x">
                <form role="form" method="post" id="fmAdd" action="javascript:void(0)" enctype="multipart/form-data">
                    <div id="addError"></div>   
                    <div class="form-group">
                        <label>Mã giảng viên</label> (<span style="color:red">*</span>)
                        <input type="text" class="form-control" name="lecturer_code">
                    </div>
                    <div class="form-group">
                        <label>Tên giảng viên</label> (<span style="color:red">*</span>)
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="form-group">
                        <label>Ngày sinh</label> (<span style="color:red">*</span>)
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="glyphicon glyphicon-calendar"></i>
                            </span>
                            <input class="datepicker form-control" name="date_of_birth">													
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label">Giới tính</label> (<span style="color:red">*</span>)
                        <div class="tcb">
                            <label>
                                <input type="radio" name="sex"  value="1" class="tc">
                                <span class="labels">Nam</span>
                            </label>
                            <label>
                                <input type="radio" name="sex"  value="0" class="tc">
                                <span class="labels">Nữ</span>
                            </label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Trình độ</label> (<span style="color:red">*</span>)
                        <select class="form-control" name="degree">
                            <option value="">-- Chọn trình độ --</option>
                            <option value="1">Thạc sĩ</option>
                            <option value="2">Tiến sĩ</option>
                            <option value="3">Phó giáo sư</option>
                            <option value="4">Giáo sư</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Khoa</label> (<span style="color:red">*</span>)
                        <select class="form-control" name="faculty_id">
                            <option value="">-- Chọn khoa --</option>
                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}">{{ $faculty->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary" id="btnAdd"><i class="fa fa-floppy-o" title="Save"></i> Lưu</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->