@extends('admin.partial.layout')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
       
        <div class="col-lg-6 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h5 class="card-title text-primary">Password Form</h5>
                                    <p class="mb-4">
                                       update password here
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <a style="float:right" href="{{url('user_profile')}}" class="btn btn-info"><i
                                            class="bx bx-left-arrow-alt me-1"></i>Back</a>
                                </div>
                                <div class="col-sm-12">
                                    @if(session()->get('error'))
                                    <div class="alert alert-danger alert-dismissible show fade">
                                        {{session()->get('error')}}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                    @endif
                                    @if(session()->get('success'))
                                    <div class="alert alert-success alert-dismissible show fade">
                                        {{session()->get('success')}}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif
                                    <form action="{{url('update_password_reporter')}}" method="POST">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <input type="hidden" name="id" value="{{$data->id}}"/>
                                                @csrf
                                                {{-- @method('put') --}}
                                                 <div class="mb-3">
                                                    <label class="form-label" for="basic-icon-default-fullname">Old Password</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i class="bx bx-lock"></i></span>
                                                        <input type="password" class="form-control @error("old_password")
                                                            is-invalid @enderror" id="basic-icon-default-fullname" 
                                                            placeholder="Old Password" 
                                                            aria-label="John Doe" name="old_password"
                                                            aria-describedby="basic-icon-default-fullname2" />
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="basic-icon-default-fullname">New Password</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                                        <input type="password" class="form-control @error("password")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="Password" name="new_password"
                                                            aria-describedby="basic-icon-default-fullname2" />
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="basic-icon-default-fullname">Confirm New Password</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                                                        <input type="password" class="form-control @error("new_password_confirmation")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="Confirm Password" name="new_password_confirmation"
                                                            aria-describedby="basic-icon-default-fullname2" />
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Send</button>

                                            </div>
                                            
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div></div>

    </div>
    @endsection
