<div class="modal fade text-left" id="deleteUser" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel1">User Information</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="row">

                      <form action="{{ route('delete.super-admin.super-admin-user') }}" method="POST" >
                        @csrf

                        <input type="hidden" name="remove_userId" id="removeUserId">
                        <input type="hidden" name="remove_roleId" id="removeRoleId">

                        <div class="col-12 mb-2">
                            <label >Are you sure do you want to delete?</label>
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button type="button" class="btn" data-bs-dismiss="modal">
                                <i class="bx bx-x d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Close</span>
                            </button>
                            <button type="submit" class="btn btn-danger ml-1" >
                                <i class="bx bx-check d-block d-sm-none"></i>
                                <span class="d-none d-sm-block">Confirm</span>
                            </button>
                        </div>

                      </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
