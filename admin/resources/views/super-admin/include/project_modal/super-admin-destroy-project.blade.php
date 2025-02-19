<div class="modal fade" id="destroyProject" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Remove project?</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">

                <div>
                    Are you sure you want to remove this project?
                </div>

                <div class="d-flex justify-content-end">
                    <form action="{{ route('destroy.super-admin.admin-projects') }}" method="POST">
                        @csrf

                         {{-- PROJECT ID --}}
                        <input type="hidden" name="projectId" id="projectIdf">
                        <input type="hidden" name="userId" id="userIdf">

                        <button type="submit" class="btn btn-danger">
                            Confirm
                        </button>

                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
