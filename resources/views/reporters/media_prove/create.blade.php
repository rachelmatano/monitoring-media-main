@extends('reporters.partial.layout')
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
                                    <a style="float:right" href="{{url('media_prove_reporter')}}" class="btn btn-info"><i
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
                                    <form action="{{route('media_prove.store')}}" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-sm-12">

                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-fullname">Headline</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i
                                                                class="bx bx-slideshow"></i></span>
                                                        <input type="text" class="form-control @error(" title")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="Masukan Judul Berita" value="{{old('title')}}"
                                                            aria-label="John Doe" name="title"
                                                            aria-describedby="basic-icon-default-fullname2" />
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-fullname">Link</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i class="bx bx-link"></i></span>
                                                        <input type="text" class="form-control @error(" link")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="Masukan Link" value="{{old('link')}}"
                                                            aria-label="John Doe" name="link"
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
                                                            <option value="Online"
                                                                {{old('tipe')=='Online'?'selected':''}}>Online News
                                                            </option>
                                                            <option value="Printed"
                                                                {{old('tipe')=='Printed'?'selected':''}}>Printed News
                                                            </option>
                                                            <option value="Both" {{old('tipe')=='Both'?'selected':''}}>
                                                                Online & Printed
                                                                News
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-phone">Project</label>
                                                    <div class="input-group">
                                                        <label class="input-group-text" for="inputGroupSelect01"><i
                                                                class="bx bx-cylinder"></i></label>
                                                        <select
                                                            class="form-select @error('project_id') is-invalid @enderror"
                                                            id="inputGroupSelect01" name="project_id">
                                                            <option selected>Choose...</option>
                                                            @foreach($project as $p)
                                                            <option @if(old('project_id')==$p->id) selected @endif
                                                                value="{{$p->id}}">{{$p->title}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="media_id" value="{{$media->id}}"/>
                                                <input type="hidden" name="reporter_id" value="{{$user->id}}"/>
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
@endsection
