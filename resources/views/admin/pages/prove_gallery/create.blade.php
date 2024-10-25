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
                                    <h5 class="card-title text-primary">{{$pageName}}</h5>
                                    <p class="mb-4">

                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <a style="float:right" href="{{url('media_prove')}}" class="btn btn-info"><i
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
                                    <form action="{{route('prove_gallery.store')}}" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-sm-12">

                                                @csrf
                                                <input type="hidden" value="{{$prove->id}}" name="prove_id" />
                                                <div class="mb-3">
                                                    <label class="form-label" for="basic-icon-default-fullname">Media
                                                        Prove <span style="font-size:10px;text-transform:lowercase;"
                                                            class="text-danger">(readonly)</span></label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i
                                                                class="bx bx-slideshow"></i></span>
                                                        <input type="text" class="form-control @error(" title")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="John Doe" value="{{$prove->title}}"
                                                            aria-label="John Doe" name="media_prove" readonly
                                                            aria-describedby="basic-icon-default-fullname2" />

                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-phone">Type</label>
                                                    <div class="input-group">
                                                        <label class="input-group-text" for="inputGroupSelect01"><i
                                                                class="bx bx-cross"></i></label>
                                                        <select class="form-select @error('tipe') is-invalid @enderror"
                                                            id="inputGroupSelect01" name="tipe">
                                                            <option selected>Choose...</option>
                                                            <option value="Image"
                                                                {{old('tipe')=='Image'?'selected':''}}>Image
                                                            </option>
                                                            <option value="Video"
                                                                {{old('tipe')=='Video'?'selected':''}}>Video
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Photo (optional)</label>
                                                    <div class="input-group">
                                                        <input type="file" name="file"
                                                            class="form-control @error('file') is-invalid @enderror"
                                                            id="inputGroupFile02" value="{{old('file')}}" />

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

    </div>
</div>

</div>
@endsection
