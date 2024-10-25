@extends('reporters.partial.layout')
@section('content')
<style>
    /* Style the Image Used to Trigger the Modal */
    #myImg {
        border-radius: 5px;
        cursor: pointer;
        transition: 0.3s;
    }

    #myImg:hover {
        opacity: 0.7;
    }

    /* The Modal (background) */
    .modal {
        display: none;
        /* Hidden by default */
        position: fixed;
        /* Stay in place */
        z-index: 999;
        /* Sit on top */
        padding-top: 100px;
        /* Location of the box */
        left: 0;
        top: 0;
        width: 100%;
        /* Full width */
        height: 100%;
        /* Full height */
        overflow: auto;
        /* Enable scroll if needed */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.9);
        /* Black w/ opacity */
    }

    /* Modal Content (Image) */
    .modal-content {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
    }

    /* Caption of Modal Image (Image Text) - Same Width as the Image */
    #caption {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        text-align: center;
        color: #ccc;
        padding: 10px 0;
        height: 150px;
    }

    /* Add Animation - Zoom in the Modal */
    .modal-content,
    #caption {
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @keyframes zoom {
        from {
            transform: scale(0)
        }

        to {
            transform: scale(1)
        }
    }

    /* The Close Button */
    .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    .close:hover,
    .close:focus {
        color: #bbb;
        text-decoration: none;
        cursor: pointer;
    }

    /* 100% Image Width on Smaller Screens */
    @media only screen and (max-width: 700px) {
        .modal-content {
            width: 100%;
        }
    }

</style>
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-9">
                                    <h5 class="card-title text-primary">{{$pageName}} : {{$data->title}}</h5>
                                    <p class="mb-4">
                                        Uploaded on : {{date('d F Y H:i:s a',strtotime($data->date_posted))}}
                                    </p>
                                </div>
                                <div class="col-sm-2">
                                    <a style="float:right" href="{{url('reporter_prove_gallery/'.$data->id)}}"
                                        class="btn btn-primary"><i class="bx bx-list-plus me-1"></i>Add Image /
                                        Video</a>
                                </div>
                                <div class="col-sm-1">
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
                                    @if(session()->get('success'))
                                    <div class="alert alert-success alert-dismissible show fade">
                                        {{session()->get('success')}}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                    @endif
                                    <div class=" text-center ">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="table-responsive">
                                                    <table width="100%" class="table table-striped table-bordered">
                                                        <tr>
                                                            <th colspan='2'>News Media</th>
                                                        </tr>
                                                        <tr>
                                                            <th rowspan='2'>
                                                                @if($media->logo=='-')
                                                                <img src="{{asset('no_photo.png')}}" width="150px" />
                                                                @else
                                                                <img id="myImg" onclick="javascript:imageClick(this)"
                                                                    alt="{{$media->m_name}}"
                                                                    src="{{asset('/news_media_logo/'.$media->logo)}}"
                                                                    width="150px"
                                                                    style="max-height: 150px;object-fit:cover" />
                                                                @endif
                                                            </th>
                                                            <td>Name : {{$media->m_name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Email : {{$media->email}}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="table-responsive">
                                                    <table width="100%" class="table table-striped table-bordered">
                                                        <tr>
                                                            <th colspan='2'>Reporter</th>
                                                        </tr>
                                                        <tr>
                                                            <th rowspan='2'>
                                                                @if($reporter->photo=='-' || $reporter->photo=="")
                                                                <img src="{{asset('no_photo.png')}}" width="150px" />
                                                                @else
                                                                <img id="myImg" onclick="javascript:imageClick(this)"
                                                                    alt="{{$reporter->name}}"
                                                                    src="{{asset('/reporters_photo/'.$reporter->photo)}}"
                                                                    width="150px"
                                                                    style="max-height: 150px;object-fit:cover" />
                                                                @endif
                                                            </th>
                                                            <td>Name : {{$reporter->name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Email : {{$reporter->email}}</td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="divider">
                                                <div class="divider-text">
                                                    <h5 class="text-info">Contribution</h5>
                                                </div>
                                            </div>
                                            <div class="col-sm-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped">
                                                        <tr><th>Title</th><td>{{$data->title}}</td></tr>
                                                        <tr><th>Link</th><td>
                                                            @if($data->link!='-')
                                                                <a href="{{$data->link}}">{{$data->link}}</a>
                                                            @else <b>No Link</b>
                                                            @endif
                                                        </td></tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="divider">
                                <div class="divider-text">
                                    <h5 class="text-info">Detail Project</h5>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered">
                                        <tr>
                                            <th>
                                                <h4>Project : {{$project->title}}</h4> <span>Start From :
                                                    {{date('d F Y',strtotime($project->date_posted))}} -
                                                    {{date('d F Y',strtotime($project->valid_until))}}</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>{!!$project->content!!}</th>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="divider">
                                <div class="divider-text">
                                    <h5 class="text-info">Image / Video</h5>
                                </div>
                            </div>
                            <div class="row">
                                @foreach($gallery as $g)
                                <div class="col-sm-3">
                                    <div class="card">

                                        <img class="card-img-top" id="myImg" onclick="javascript:imageClick(this)"
                                            alt="{{$media->m_name}}" src="{{asset('prove_galleries/'.$g->link_path)}}"
                                            width="100%"
                                            style="max-height: 200px;min-height:200px;object-fit:cover;object-align:center" />

                                        <div class="card-footer">
                                            <form action="{{ url('/reporter_prove_gallery_delete/'. $g->id) }}" method="post">
                                                @csrf
                                                @method('delete')

                                                <button type="submit" class="btn btn-danger" style="width:100%"
                                                    onclick="return confirm('Apakah anda yakin untuk menghapus data ini?')">
                                                    <i class="bx bx-trash me-1"></i> Delete
                                                </button>

                                            </form>

                                        </div>

                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="divider">
                                <div class="divider-text">{{$gallery->links()}}</div>
                            </div>
                        </div>

                    </div>


                </div>
            </div>

        </div>
    </div>


</div>
<!-- The Modal -->
<div id="myModal" class="modal">

    <!-- The Close Button -->
    <span class="close">&times;</span>

    <!-- Modal Content (The Image) -->
    <img class="modal-content" id="img01">

    <!-- Modal Caption (Image Text) -->
    <div id="caption"></div>
</div>
<script>
    var modal = document.getElementById("myModal");

    // Get the image and insert it inside the modal - use its "alt" text as a caption
    var img = document.getElementById("myImg");
    var modalImg = document.getElementById("img01");
    var captionText = document.getElementById("caption");
    function imageClick(img) {
        modal.style.display = "block";
        modalImg.src = img.src;
        captionText.innerHTML = img.alt;
    }

    // Get the <span> element that closes the modal
    var span = document.getElementsByClassName("close")[0];

    // When the user clicks on <span> (x), close the modal
    modal.onclick = function () {
        modal.style.display = "none";
    }

</script>
@endsection
