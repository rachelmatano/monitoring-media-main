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
                                    <a style="float:right" href="{{url('media_notification')}}" class="btn btn-info"><i
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
                                            <form action="{{route('media_notification.store')}}" method="POST">
                                                @csrf
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-fullname">Title</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i class="bx bx-captions"></i></span>
                                                        <input type="text" class="form-control @error(" title")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="John Doe" value="{{old('title')}}"
                                                            aria-label="John Doe" name="title"
                                                            aria-describedby="basic-icon-default-fullname2" />
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-message">Content</label>
                                                    <div class="input-group input-group-merge">

                                                        <textarea id="basic-icon-default-message" class="form-control"
                                                            name="content" placeholder="Jl. Mana Saja" id="content"
                                                            aria-label="Hi, Do you have a moment to talk Joe?"
                                                            aria-describedby="basic-icon-default-message2">{{old('content')}}</textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label"
                                                                for="basic-icon-default-phone">Type</label>
                                                            <div class="input-group">
                                                                <label class="input-group-text" for="inputGroupSelect01"><i
                                                                        class="bx bx-cross"></i></label>
                                                                <select
                                                                    class="form-select @error('tipe') is-invalid @enderror" onchange="javascript:changeTipe(this)"
                                                                    id="tipe" name="tipe">
                                                                    <option selected>Choose...</option>
                                                                    <option value="public" {{old('tipe')=='public'?'selected':''}}>Public
                                                                    </option>
                                                                    <option value="private" {{old('tipe')=='private'?'selected':''}}>
                                                                        Private</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-6">
                                                        <div class="mb-3">
                                                            <label class="form-label"
                                                                for="basic-icon-default-phone">Category</label>
                                                            <div class="input-group">
                                                                <label class="input-group-text" for="inputGroupSelect01"><i
                                                                        class="bx bx-cross"></i></label>
                                                                <select
                                                                    class="form-select @error('category') is-invalid @enderror"
                                                                    id="inputGroupSelect01" name="category">
                                                                    <option selected>Choose...</option>
                                                                    <option value="project" {{old('category')=='project'?'selected':''}}>Project
                                                                    </option>
                                                                    <option value="information" {{old('category')=='information'?'selected':''}}>
                                                                        Information</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-phone">News Media <span class="badge badge-info">(for private only)</span></label>
                                                    <div class="input-group">
                                                        <label class="input-group-text" for="inputGroupSelect01"><i
                                                                class="bx bx-buildings"></i></label>
                                                        <select
                                                            class="form-select @error('media_id') is-invalid @enderror"
                                                            id="media" name="media_id">
                                                            <option selected value="-">Choose...</option>
                                                            @foreach($media as $m)
                                                            <option @if(old('media_id')==$m->id) selected @endif value="{{$m->id}}">{{$m->m_name}}</option>
                                                            @endforeach
                                                        </select>
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
            function changeTipe(tipe){
                var media = document.getElementById('media');
                if(tipe.value=='private'){
                    media.disabled = false;
                }else{
                    media.disabled = true;
                    media.value = "-";
                }
            }
    </script>
</div>
@endsection
