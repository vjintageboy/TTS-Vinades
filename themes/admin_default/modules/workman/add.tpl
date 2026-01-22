<!-- BEGIN: main -->
<div class="workman-add">
    <!-- BEGIN: error -->
    <div class="alert alert-danger">{ERROR}</div>
    <!-- END: error -->

    <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="submit" value="1" />
        
        <div class="form-group">
            <label for="title" class="control-label">Tiêu đề đồ án <span class="text-danger">(*)</span></label>
            <input type="text" class="form-control" name="title" id="title" value="{ROW.title}" required="required" maxlength="255">
        </div>

        <div class="form-group">
            <label for="image" class="control-label">Hình ảnh (URL hoặc đường dẫn)</label>
            <input type="text" class="form-control" name="image" id="image" value="{ROW.image}" placeholder="Nhập đường dẫn ảnh">
        </div>

        <div class="form-group">
            <label for="description" class="control-label">Mô tả ngắn</label>
            <textarea class="form-control" rows="3" name="description" id="description">{ROW.description}</textarea>
        </div>

        <div class="form-group">
            <label for="bodytext" class="control-label">Nội dung chi tiết <span class="text-danger">(*)</span></label>
            {ROW.bodytext}
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-primary">Lưu đồ án</button>
        </div>
    </form>
</div>
<!-- END: main -->
