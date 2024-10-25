@extends('admin.partial.layout')
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
                                    <form action="{{route('news_media.update',$data->id)}}" method="POST"
                                        enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-sm-8">

                                                @csrf
                                                @method('put')
                                                <div class="mb-3">
                                                    <label class="form-label" for="basic-icon-default-fullname">Media
                                                        Name</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-fullname2"
                                                            class="input-group-text"><i class="bx bx-user"></i></span>
                                                        <input type="text" class="form-control @error(" m_name")
                                                            is-invalid @enderror" id="basic-icon-default-fullname"
                                                            placeholder="John Doe" value="{{$data->m_name}}"
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
                                                            name="ref_code" value="{{$data->ref_code}}"
                                                            class="form-control @error('ref_code') is-invalid @enderror"
                                                            placeholder="ACME Inc." aria-label="ACME Inc."
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
                                                            value="{{$data->email}}"
                                                            class="form-control @error('email') is-invalid @enderror"
                                                            placeholder="john.doe" aria-label="john.doe"
                                                            aria-describedby="basic-icon-default-email2" />

                                                    </div>
                                                    <div class="form-text">You can use letters, numbers & periods</div>
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label" for="basic-icon-default-phone">Phone
                                                        No</label>
                                                    <div class="input-group input-group-merge">
                                                        <span id="basic-icon-default-phone2" class="input-group-text"><i
                                                                class="bx bx-phone"></i></span>
                                                        <input type="text" id="basic-icon-default-phone" name="phone_no"
                                                            value="{{$data->phone_no}}"
                                                            class="form-control phone-mask @error('phone_no') is-invalid @enderror"
                                                            placeholder="658 799 8941" aria-label="658 799 8941"
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
                                                            aria-describedby="basic-icon-default-message2">{{$data->address}}</textarea>
                                                    </div>
                                                </div>

                                                <button type="submit" class="btn btn-primary">Send</button>

                                            </div>
                                            <div class="col-sm-4">
                                                <div class="card">
                                                    @if($data->logo=="-")
                                                    <img class="card-img-top" src="{{asset('no_photo.png')}}"
                                                        width="100%" />
                                                    @else
                                                    <img id="myImg" onclick="javascript:imageClick(this)"
                                                        alt="{{$data->m_name}}" s class="card-img-top"
                                                        src="{{asset('news_media_logo/'.$data->logo)}}" width="100%"
                                                        style="max-height: 430px;object-fit:cover;object-align:center" />
                                                    @endif

                                                    <div class="card-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Change Logo (optional)</label>
                                                            <div class="input-group">
                                                                <input type="file" name="logo"
                                                                    class="form-control @error('logo') is-invalid @enderror"
                                                                    id="inputGroupFile02" value="{{$data->logo}}" />

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
