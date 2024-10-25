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
                <div class="card-header">
                    <h3 class="card-title">{{$pageName}}</h3>
                    <hr class="my-0" />

                </div>
                <div class="d-flex align-items-center row">
                    <div class="col-sm-3">
                        <div class="card-body">
                            @if($media->logo!="-")
                            <img src="{{asset('/news_media_logo/'.$media->logo)}}" id="myImg"
                                onclick="javascript:imageClick(this)" alt="{{$media->m_name}}" class="d-block rounded"
                                width="100%" />
                            @else
                            <img src="{{asset('/no_photo.png')}}" class="d-block rounded" width="100%" />
                            @endif
                        </div>

                    </div>
                    <div class="col-sm-9">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <th width="20%">Media Name</th>
                                    <td>
                                        <h4 class="text-primary"><b>{{$media->m_name}}</b></h4>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email Media</th>
                                    <td>{{$media->email}}</td>
                                </tr>
                                <tr>
                                    <th>No. Telp</th>
                                    <td>{{$media->phone_no}}</td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td>{{$media->address}}</td>
                                </tr>
                                <tr>
                                    <th colspan='2'><span style="font-size:12px">Created At
                                            {{date('d F Y H:i:s',strtotime($media->created_at))}}</span></th>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="col-lg-12">
                        <div class="divider">
                            <div class="divider-text">
                               <h5>REPORTERS</h5>
                            </div>
                        </div>
                        <div class=" text-center ">

                            <div class="table-responsive text-nowrap">
                                <table class="table table-striped table-hover table-bordered">
                                    <thead>
                                        <tr class="text-nowrap">
                                            <th>#</th>
                                            <th>Photo</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Media Ref. Code</th>
                                            <th>Gender</th>
                                            <th>Phone No.</th>
                                           
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=$reporter->currentPage();?>
                                        @foreach($reporter as $d)
                                        <tr>
                                            <th scope="row">{{$i}}</th>
                                            <td>@if($d->photo=='-')
                                                <img src="{{asset('no_photo.png')}}" width="150px" />
                                                @else
                                                <img id="myImg" onclick="javascript:imageClick(this)"
                                                    alt="{{$d->name}}"
                                                    src="{{asset('/reporters_photo/'.$d->photo)}}"
                                                    width="150px"
                                                    style="max-height: 150px;object-fit:cover" />
                                                @endif</td>
                                            <td>{{$d->name}}</td>
                                            <td>{{$d->email}}</td>
                                            <td>{{$d->code}}</td>
                                            <td>{{$d->gender=='L'?'Laki-Laki':'Perempuan'}}</td>
                                            <td>{{$d->phone_no}}</td>
                                            
                                            
                                        </tr>
                                        <?php $i+=1;?>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr class="text-nowrap">
                                            <td colspan='9'>{{$reporter->links()}}</td>
                                        </tr>
                                    </tfoot>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Profile</h3>
                    <hr class="my-0" />

                </div>
                <div class="d-flex align-items-center row">
                    <div class="col-sm-3">
                        <div class="card-body">
                            @if($user->photo!="-" && $user->photo!="")
                            <img src="{{asset('/reporters_photo/'.$user->photo)}}" id="myImg"
                                onclick="javascript:imageClick(this)" alt="{{$user->name}}" class="d-block rounded"
                                width="100%" />
                            @else
                            <img src="{{asset('/no_photo.png')}}" class="d-block rounded" width="100%" />
                            @endif
                        </div>

                    </div>
                    <div class="col-sm-9">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <th width="20%">Name</th>
                                    <td>
                                        <h4 class="text-primary"><b>{{$user->name}}</b></h4>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{$user->email}}</td>
                                </tr>
                                <tr>
                                    <th>No. Telp</th>
                                    <td>{{$user->phone_no}}</td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td>{{$user->gender=='L'?'Male':'Female'}}</td>
                                </tr>
                                <tr>
                                    <th colspan='2'><span style="font-size:12px">Created At
                                            {{date('d F Y H:i:s',strtotime($media->created_at))}}</span></th>
                                </tr>
                            </table>
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
