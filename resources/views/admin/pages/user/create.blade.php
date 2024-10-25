@extends('admin.partial.layout')
@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h5 class="card-title text-primary">{{$pageName}}</h5>
                                    <p class="mb-4">

                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <a style="float:right" href="{{url('user')}}" class="btn btn-info"><i
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
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <form action="{{route('user.store')}}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-fullname">Email</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i class="bx bx-envelope"></i></span>
                                                        <input type="email" class="form-control @error("email")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="Masukan Email" value="{{old('email')}}"
                                                            aria-label="John Doe" name="email"
                                                            aria-describedby="basic-icon-default-fullname2" />
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-fullname">Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control @error("name")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="Masukan Nama" value="{{old('name')}}"
                                                            aria-label="John Doe" name="name"
                                                            aria-describedby="basic-icon-default-fullname2" />
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-phone">Level</label>
                                                    <div class="input-group">
                                                        <label class="input-group-text" for="inputGroupSelect01"><i
                                                                class="bx bx-user"></i></label>
                                                        <select
                                                            class="form-select @error('level') is-invalid @enderror"
                                                            id="inputGroupSelect01" name="level">
                                                            <option selected>Choose...</option>
                                                            <option value="Super Admin" {{old('level')=='Super Admin'?'selected':''}}>Super Admin
                                                            </option>
                                                            <option value="Admin" {{old('level')=='Admin'?'selected':''}}>
                                                                Admin</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-fullname">Password</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i class="bx bx-lock"></i></span>
                                                        <input type="password" class="form-control @error("password")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="Password" value="{{old('password')}}"
                                                            aria-label="John Doe" name="password"
                                                            aria-describedby="basic-icon-default-fullname2" />
                                                    </div>
                                                </div>
                                                

                                                <button type="submit" class="btn btn-primary">Send</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
    @endsection
