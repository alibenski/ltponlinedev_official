<!-- Modal -->
<div class="modal fade" id="modalCheckPash" tabindex="-1" role="dialog" aria-labelledby="memberModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <span><h3 class="modal-title" id="memberModalLabel"><i class="fa fa-lg fa-exclamation-circle text-danger btn-space"></i>Input Needed</h3></span>
            </div>
            <div class="modal-body">
                <p>The batch has already ran and the system has created a record in the "Manage Classes" table.</p>
                <p>Your input addresses the issue where a student has been moved to another class after the batch ran (which means the original is different from the current class code). Please select one and confirm the record that needs to change:
                </p>
                <br />
                <div class="input-group">
                    
                    @foreach ($records as $item)
                        <input id="CheckBoxPashRecord" name="CheckBoxPashRecord" class="" type="radio" value={{ $item->id }} >
                        <label for="CheckBoxPashRecord" class="">
                            <span style="margin-left:5px;">{{ $item->courses->Description }}</span>
                        </label><br /><hr />
                    @endforeach
                        
                </div>
            </div>
            <div class="modal-footer">
                <button id="ContinueBtn" type="button" class="btn btn-success" data-dismiss="modal" disabled>Continue</button>
            </div>
            <script>
                $("#CheckBoxPashRecord").on("change", function () {
                    $("#ContinueBtn").removeAttr("disabled");
                })
            </script>
        </div>
    </div>
</div><!-- End Modal -->

