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
                                    <a style="float:right" href="{{url('news_media')}}" class="btn btn-info"><i
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
                                            <form action="{{route('news_media.store')}}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label" for="basic-icon-default-fullname">Media
                                                        Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control @error(" m_name")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="Masukan Nama Media" value="{{old('m_name')}}"
                                                            aria-label="John Doe" name="m_name"
                                                            aria-describedby="basic-icon-default-fullname2" />
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label" for="basic-icon-default-company">Media
                                                        Code
                                                        Reference</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-company2"
                                                            class="input-group-text"><i
                                                                class="bx bx-buildings"></i></span>
                                                        <input type="text" id="basic-icon-default-company"
                                                            name="ref_code" value="{{old('ref_code')}}"
                                                            class="form-control @error('ref_code') is-invalid @enderror"
                                                            placeholder="Masukan Kode" aria-label="ACME Inc."
                                                            aria-describedby="basic-icon-default-company2" />
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-email">Email</label>
                                                    <div class="input-group input-group-merge">
                                                        <span class="input-group-text"><i
                                                                class="bx bx-envelope"></i></span>
                                                        <input type="text" id="basic-icon-default-email" name="email"
                                                            value="{{old('email')}}"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            placeholder="Masukan Email" aria-label="john.doe"
                                                            aria-describedby="basic-icon-default-email2" />

                                                    </div>
                                                    <div class="form-text"></div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="basic-icon-default-phone">Phone
                                                        No</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-phone2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="basic-icon-default-phone" name="phone_no"
                                                            value="{{old('phone_no')}}"
                                                            class="form-control phone-mask @error('phone_no') is-invalid @enderror"
                                                            placeholder="Masukan No Tlp" aria-label="658 799 8941"
                                                            aria-describedby="basic-icon-default-phone2" />
                                                    </div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-message">Address</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-message2"
                                                            class="input-group-text"><i
                                                                class="bx bx-location-plus"></i></span>
                                                        <textarea id="basic-icon-default-message" class="form-control"
                                                            name="address" placeholder="Jl. Mana Saja"
                                                            aria-label="Hi, Do you have a moment to talk Joe?"
                                                            aria-describedby="basic-icon-default-message2">{{old('address')}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Logo (optional)</label>
                                                    <div class="input-group">
                                                        <input type="file" name="logo"
                                                            class="form-control @error('logo') is-invalid @enderror"
                                                            id="inputGroupFile02" value="{{old('logo')}}" />

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
</div>
@endsection
