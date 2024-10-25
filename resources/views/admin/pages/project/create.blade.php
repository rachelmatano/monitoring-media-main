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
                                    <a style="float:right" href="{{url('project')}}" class="btn btn-info"><i
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
                                            <form action="{{route('project.store')}}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-fullname">Title</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control @error(" title")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="Masukan Judul" value="{{old('title')}}"
                                                            aria-label="John Doe" name="title"
                                                            aria-describedby="basic-icon-default-fullname2" />
                                                    </div>
                                                </div>
                                                {{-- <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-fullname">Minimum Contribution</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i class="bx bx-signal-5"></i></span>
                                                        <input type="minimum" class="form-control @error("minimum")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="John Doe" value="{{old('minimum')??25}}"
                                                            aria-label="John Doe" name="minimum"
                                                            aria-describedby="basic-icon-default-fullname2" />
                                                    </div>
                                                </div> --}}
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-message">Content</label>
                                                    <div class="input-group input-group-merge">

                                                        <textarea id="basic-icon-default-message" class="form-control"
                                                            name="content" placeholder="Ket." id="content"
                                                            aria-label="Hi, Do you have a moment to talk Joe?"
                                                            aria-describedby="basic-icon-default-message2">{{old('content')}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label"
                                                                for="basic-icon-default-company">Start From</label>
                                                            <div class="input-group input-group-merge">
                                                                <span id="basic-icon-default-company2"
                                                                    class="input-group-text"><i
                                                                        class="bx bx-calendar"></i></span>
                                                                <input type="date" id="basic-icon-default-company"
                                                                    name="date_posted" value="{{old('date_posted')}}"
                                                                    class="form-control @error('date_posted') is-invalid @enderror"
                                                                    placeholder="ACME Inc." aria-label="ACME Inc."
                                                                    aria-describedby="basic-icon-default-company2" />
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label"
                                                                for="basic-icon-default-email">Valid Until</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text"><i
                                                                        class="bx bx-calendar-event"></i></span>
                                                                <input type="date" id="basic-icon-default-email"
                                                                    name="valid_until" value="{{old('valid_until')}}"
                                                                    class="form-control @error('valid_until') is-invalid @enderror"
                                                                    placeholder="john.doe" aria-label="john.doe"
                                                                    aria-describedby="basic-icon-default-email2" />

                                                            </div>

                                                        </div>
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
    <script src="https://cdn.ckeditor.com/4.22.1/standard/ckeditor.js"></script>
    <script>
        CKEDITOR
            .replace('content', {
                width: '100%'
            });

    </script>
</div>
@endsection
