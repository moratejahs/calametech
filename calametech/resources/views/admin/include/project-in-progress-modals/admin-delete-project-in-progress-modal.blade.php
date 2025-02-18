<div class="modal fade text-left" id="deleteProjectInProgressModal" tabindex="-1" aria-labelledby="myModalLabel1"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel1">Confirmation</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form action="{{ route('delete.admin.admin-projects-in-progress') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-12 mb-2">
                                <input type="hidden" id="projectIdf" name="projectId">
                                <input type="hidden" id="userId" name="userId">
                                <label for="">Are you sure do you want to delete?</label>
                            </div>
                            <div class="col-12 d-flex justify-content-end">
                                <button type="button" class="btn" data-bs-dismiss="modal">
                                    <i class="bx bx-x d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Close</span>
                                </button>
                                <button type="submit" class="btn btn-danger ml-1">
                                    <i class="bx bx-check d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Confirm</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
