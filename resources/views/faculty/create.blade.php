<!-- Add Modal -->
<div class="modal fade modal-scroll" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-circle"></i> Thêm khoa</h4>
            </div>
            <div class="modal-body padding-2x">
                <form role="form" method="post" id="fmAdd" action="javascript:void(0)" enctype="multipart/form-data">
                    <div id="addError"></div>   
                    <div class="form-group">
                        <label>Mã khoa</label> (<span style="color:red">*</span>)
                        <input type="text" class="form-control" name="faculty_code">
                    </div>
                    <div class="form-group">
                        <label>Tên khoa</label> (<span style="color:red">*</span>)
                        <input type="text" class="form-control" name="name">
                    </div>
                    <div class="btn-group">
                        <button type="submit" class="btn btn-primary" id="btnAdd"><i class="fa fa-floppy-o" title="Save"></i> Lưu</button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->