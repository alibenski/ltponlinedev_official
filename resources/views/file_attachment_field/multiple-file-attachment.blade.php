<div id="filesContainer" class="form-group my-3 py-3 border">
                            
    <div class="col-md-3 my-1 py-1">
    <button type="button" id="addFile" onclick="return Count();" class="btn btn-secondary"><i class="fa fa-plus"></i> Add</button>
    </div>

    <label for="addFile" class="col-md-12 control-label">Additional Files: (optional 3 maximum) </label>
    <input name="addFile0" type="file" class="col-md-9 form-control-static my-1 py-1"/><button type="button" id="file-reset" for="addFile0" class="btn btn-danger remove-btn">remove file</button>
</div>

<script>
let count = 0;
function Count() {
    count++;
    if (count < 3) {
    let addFileInput =  $('<input/>').attr('type', 'file').attr('name', 'addFile'+count).addClass('col-md-9 form-control-static my-1 py-1');
    let resetBtn = $('<button></button>').attr('type', 'button').attr('id', 'file-reset').attr('for', 'addFile'+count).addClass('btn btn-danger remove-btn').text('remove file');
    $('#filesContainer').append(
        addFileInput, resetBtn
    );
    } else {
    $('#addFile').attr('disable', true).addClass('');
    }
    return false;
}
</script> 
<script>
$(document).on('click', '.remove-btn', function() {    
    let fileCount = $(this).attr('for');
    $('input[name='+fileCount+']').val('');
});
</script>