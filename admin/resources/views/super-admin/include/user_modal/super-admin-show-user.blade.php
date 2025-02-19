

<div class="modal fade text-left" id="showUser" data-bs-backdrop="static" tabindex="-1" aria-labelledby="myModalLabel1" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel1">User Information</h5>
                <button type="button" class="close rounded-pill" data-bs-dismiss="modal" aria-label="Close">
                    <i data-feather="x"></i>
                </button>
            </div>
            <form id="showUserValidationForm" action="{{ route('edit.super-admin.super-admin-user') }}" method="POST">

                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="container-fluid">
                         <div class="row">

                            {{-- primary key --}}
                            <input type="hidden" name="userId" id="viewUserId">


                            <div class="col-6">
                                <div class="mb-1">
                                    <label for="Incharge">Status</label>
                                    <div class="form-group">

                                        <select name="roleId" class=" form-select bg-light" id="viewRoleId">
                                            @foreach ($roleRecord as $roles)
                                                <option value="{{ $roles->id }}">{{ $roles->description }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>


                                <div class="mb-1">
                                    <label>Name</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-lines-fill" viewBox="0 0 16 16">
                                                    <path d="M6 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m-5 6s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zM11 3.5a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1h-4a.5.5 0 0 1-.5-.5m.5 2.5a.5.5 0 0 0 0 1h4a.5.5 0 0 0 0-1zm2 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1zm0 3a.5.5 0 0 0 0 1h2a.5.5 0 0 0 0-1z"/>
                                                  </svg>
                                            </span>
                                            <input id="viewName" name="name" type="text" class="form-control" placeholder="Complete name" >
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-1">
                                    <label >Email</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" >
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-at" viewBox="0 0 16 16">
                                                    <path d="M2 2a2 2 0 0 0-2 2v8.01A2 2 0 0 0 2 14h5.5a.5.5 0 0 0 0-1H2a1 1 0 0 1-.966-.741l5.64-3.471L8 9.583l7-4.2V8.5a.5.5 0 0 0 1 0V4a2 2 0 0 0-2-2zm3.708 6.208L1 11.105V5.383zM1 4.217V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v.217l-7 4.2z"/>
                                                    <path d="M14.247 14.269c1.01 0 1.587-.857 1.587-2.025v-.21C15.834 10.43 14.64 9 12.52 9h-.035C10.42 9 9 10.36 9 12.432v.214C9 14.82 10.438 16 12.358 16h.044c.594 0 1.018-.074 1.237-.175v-.73c-.245.11-.673.18-1.18.18h-.044c-1.334 0-2.571-.788-2.571-2.655v-.157c0-1.657 1.058-2.724 2.64-2.724h.04c1.535 0 2.484 1.05 2.484 2.326v.118c0 .975-.324 1.39-.639 1.39-.232 0-.41-.148-.41-.42v-2.19h-.906v.569h-.03c-.084-.298-.368-.63-.954-.63-.778 0-1.259.555-1.259 1.4v.528c0 .892.49 1.434 1.26 1.434.471 0 .896-.227 1.014-.643h.043c.118.42.617.648 1.12.648Zm-2.453-1.588v-.227c0-.546.227-.791.573-.791.297 0 .572.192.572.708v.367c0 .573-.253.744-.564.744-.354 0-.581-.215-.581-.8Z"/>
                                                  </svg>
                                            </span>
                                            <input id="viewEmail" name="email" type="email" class="form-control" placeholder="Active email" >
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-6">

                                <div class="mb-1">
                                    <label >Address</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text" id="basic-addon1">
                                                <i class="bi bi-geo-alt"></i>
                                            </span>
                                            <input id="viewAddress" name="address" type="text" class="form-control" placeholder="Current address" >
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-1">
                                   <label >Contact</label>
                                   <div class="form-group">
                                       <div class="input-group mb-3">
                                           <span class="input-group-text" id="basic-addon1">
                                               <i class="bi bi-geo-alt"></i>
                                           </span>
                                           <input id="viewContact" name="contact" type="number" class="form-control" placeholder="Contact Number" >
                                       </div>
                                   </div>
                               </div>

                                <div class="mb-1">
                                    <label >Password</label>
                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text">
                                                <i class="bi bi-shield-lock"></i>
                                            </span>
                                            <input id="viewPlainPassword" name="password" type="text" class="form-control" placeholder="Enter new password">
                                        </div>
                                    </div>
                                </div>

                            </div>

                         </div>
                    </div>
                 </div>
                 <div class="modal-footer">
                     <button type="button" class="btn" data-bs-dismiss="modal">
                         <i class="bx bx-x d-block d-sm-none"></i>
                         <span class="d-none d-sm-block">Close</span>
                     </button>
                     <button type="submit" class="btn btn-primary ml-1">
                        <span class="d-none d-sm-block">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-save" viewBox="0 0 16 16">
                                <path d="M2 1a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H9.5a1 1 0 0 0-1 1v7.293l2.646-2.647a.5.5 0 0 1 .708.708l-3.5 3.5a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L7.5 9.293V2a2 2 0 0 1 2-2H14a2 2 0 0 1 2 2v12a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h2.5a.5.5 0 0 1 0 1z"/>
                            </svg>
                            Update
                        </span>
                    </button>
                 </div>

            </form>
        </div>
    </div>
</div>
