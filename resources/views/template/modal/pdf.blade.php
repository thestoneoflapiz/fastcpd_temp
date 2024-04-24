<div class="modal fade" id="print_pdf_modal" tabindex="-1" role="dialog" aria-labelledby="print_pdf_modal_label" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="print_pdf_modal_label"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="print_pdf_modal_body">
                <form id="print_modal_form">
                    <div class="form-group">
                        <label for="page_orientation">Orientation</label>
                        <select class="form-control form-control-sm" name="page_orientation">
                            <option value="P" selected>Portrait</option>
                            <option value="L">Landscape</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="page_size">Size</label>
                        <select class="form-control form-control-sm" name="page_size">
                            <option value="A4" selected>A4 (8.27" × 11.69")</option>
                            <option value="Letter">Letter (8.5" x 11")</option>
                            <option value="Legal">Legal (8.5" x 14")</option>
                            <option value="Ledger">Ledger (11" x 17")</option>
                            <option value="Tabloid">Tabloid (17" x 11")</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="cancel_print" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" id="confirm_print" class="btn btn-primary" data-dismiss="modal">Confirm Setup</button>
            </div>
        </div>
    </div>
</div>