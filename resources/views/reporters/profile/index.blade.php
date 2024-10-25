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
                <div class="card-header row">
                    <h3 class="card-title col-sm-8">{{$pageName}}</h3>
                    <div class="col-sm-4">
                        <a style="float:right" href="{{url('edit_user_profile')}}" class="btn btn-primary"><i
                                class="bx bx-pencil me-1"></i>Edit Profile</a>
                        <a style="float:right" href="{{url('password_form')}}" class="btn btn-success"><i
                                    class="bx bx-lock me-1"></i>Change Password</a>
                    </div>
                    <hr class="my-0" />

                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center row">
                        <div class="col-sm-3">

                            @if($user->photo!="-" && $user->photo!="")
                            <img src="{{asset('/reporters_photo/'.$user->photo)}}" id="myImg" onclick="javascript:imageClick(this)" alt="{{$user->name}}" class="d-block rounded"
                                width="100%" />
                            @else
                            <img src="{{asset('/no_photo.png')}}" class="d-block rounded" width="100%" />
                            @endif
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

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <b>Created at : {{date('d F Y H:i:s a',strtotime($user->created_at))}}<b><span class="badge bg-warning" style="float:right">Last Updated at : {{date('d F Y H:i:s a',strtotime($user->updated_at))}}</span>
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
