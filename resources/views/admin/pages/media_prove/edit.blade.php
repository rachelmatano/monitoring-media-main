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
                                    <form action="{{route('media_prove.update',$data->id)}}" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-sm-12">

                                                @csrf
                                                @method('put')
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-fullname">Title</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control @error(" title")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="John Doe" value="{{$data->title}}"
                                                            aria-label="John Doe" name="title"
                                                            aria-describedby="basic-icon-default-fullname2" />
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-fullname">Link</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i
                                                                class="bx bx-slideshow"></i></span>
                                                        <input type="text" class="form-control @error(" link")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="John Doe" value="{{$data->link}}"
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
                                                                {{$data->tipe=='Online'?'selected':''}}>Online News
                                                            </option>
                                                            <option value="Printed"
                                                                {{$data->tipe=='Printed'?'selected':''}}>Printed News
                                                            </option>
                                                            <option value="Both" {{$data->tipe=='Both'?'selected':''}}>
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
                                                            <option @if($data->project_id==$p->id) selected @endif
                                                                value="{{$p->id}}">{{$p->title}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-phone">Media</label>
                                                    <div class="input-group">
                                                        <label class="input-group-text" for="inputGroupSelect01"><i
                                                                class="bx bx-news"></i></label>
                                                        <select
                                                            class="form-select @error('media_id') is-invalid @enderror"
                                                            id="inputGroupSelect01" name="media_id"
                                                            onchange="javascript:getReporter(this.value)">
                                                            <option selected>Choose...</option>
                                                            @foreach($media as $m)
                                                            <option @if($data->media_id==$m->id) selected @endif
                                                                value="{{$m->id}}">{{$m->m_name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"
                                                        for="basic-icon-default-phone">Reporter</label>
                                                    <div class="input-group">
                                                        <label class="input-group-text" for="reporter_id"><i
                                                                class="bx bx-user-voice"></i></label>
                                                        <select
                                                            class="form-select @error('reporter_id') is-invalid @enderror"
                                                            id="reporter_id" name="reporter_id">
                                                            <option selected>Choose...</option>
                                                            @foreach($reporter as $r)
                                                            <option @if($data->reporter_id==$r->id) selected @endif
                                                                value="{{$r->id}}">{{$r->name}}</option>
                                                            @endforeach
                                                        </select>
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


        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            function getReporter(id) {
                $.get("{{url('api/get_reporter')}}/" + id, function (data) {
                    // var element = JSON.parse(data);
                    alert(JSON.stringify(data) + ":" + "{{url('api/get_reporter')}}/" + id);
                    createOptions('reporter_id', data['data']);


                });
            }

            function createOptions(id, data) {
                var selectElement = document.getElementById(id);
                while (selectElement.options.length) {
                    selectElement.remove(0);
                }
                for (let i = 0; i < data.length; i++) {
                    var tmp = new Option(data[i]["name"], data[i]["id"]);
                    selectElement.options.add(tmp);
                }
            }

        </script>
    </div>
</div>
@endsection
