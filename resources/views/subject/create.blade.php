<!-- Add Modal -->
<div class="modal fade modal-scroll" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-circle"></i> Thêm môn học</h4>
            </div>
            <div class="modal-body padding-2x">
                <form role="form" method="post" id="fmAdd" action="javascript:void(0)" enctype="multipart/form-data">
                    <div id="addError"></div>   
                    <div class="form-group">
                        <label>Mã môn học</label> (<span style="color:red">*</span>)
                        <input type="text" class="form-control" name="subject_code">
                    </div>
                    <div class="form-group">
                        <label>Tên môn học</label> (<span style="color:red">*</span>)
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="form-group">
                        <label>Số tín chỉ</label> (<span style="color:red">*</span>)
                        <input type="text" class="form-control" name="credit">
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary" id="btnAdd"><i class="fa fa-floppy-o" title="Save"></i> Lưu</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->