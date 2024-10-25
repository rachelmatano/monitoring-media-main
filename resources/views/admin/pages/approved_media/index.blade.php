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
        <div class="col-md mb-4 mb-md-2">
            <div class="accordion mt-3" id="accordionExample">
                <div class="card accordion-item active">
                    <h2 class="accordion-header" id="headingOne">
                        <button type="button" class="accordion-button text-primary" data-bs-toggle="collapse"
                            data-bs-target="#accordionOne" aria-expanded="true" aria-controls="accordionOne" style="font-size:18px">
                            Filter
                           
                        </button>
                    </h2>
                    <?php $filter = session('filter-approved-list');?>
                    <div id="accordionOne" class="accordion-collapse collapse @if($filter) show @else in @endif" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <form action="{{url('filter-approved-list')}}" method="POST">
                                @csrf
                                
                            <div class="row">
                                <div class="divider">
                                    <div class="divider-text">
                                       <i class="bx bx-filter"></i>
                                    </div>
                                </div>
                                <div class="mb-3 col-sm-2">
                                    <label class="form-label" for="month">Month</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="month"><i
                                                class="bx bx-cross"></i></label>
                                        <select class="form-select @error('month') is-invalid @enderror"
                                            id="month" name="month">
                                                <?php
                                                $month = [
                                                    ["01","January"],["02","February"],
                                                    ["03","March"],["04","April"],
                                                    ["05","May"],["06","June"],
                                                    ["07","July"],["08","August"],
                                                    ["09","September"],["10","October"],
                                                    ["11","November"],["12","December"]
                                        ];
                                            $index = 0;
                                                ?>

                                                @foreach($month as $m)
                                                    <option value="{{$m[0]}}" @if($m[0]==date('m',strtotime('now'))||($filter && isset($filter['month']) && $filter['month']==$m[0])) selected @endif>{{$m[1]}}</option>
                                                    @php $index+=1; @endphp
                                                @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="mb-3 col-sm-2">
                                    <label class="form-label" for="performanceYear">Performance
                                        Year</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="performanceYear"><i
                                                class="bx bx-cross"></i></label>
                                        <select class="form-select @error('year') is-invalid @enderror"
                                            id="performanceYear" name="year">
                                            
                                            @for($i=2000;$i<=2024;$i++) <option value="{{$i}}" @if(date('Y',strtotime('now'))==$i || ($filter && isset($filter['year']) && $filter['year']==$i)) selected @endif>{{$i}}</option>
                                                @endfor
                                                {{-- <option value="{{date('Y',strtotime('now'))}}" selected>
                                                    {{date('Y',strtotime('now'))}}</option> --}}
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="mb-3 col-lg-8 ">
                                    <label class="form-label" for="performanceYear">Media</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="performanceYear"><i
                                                class="bx bx-cross"></i></label>
                                        <select class="form-select @error('media') is-invalid @enderror"
                                            id="performanceYear" name="media">
                                            <option value="all" selected >Semua Media</option>
                                            @foreach($media as $m)
                                                <option value="{{$m->id}}" @if($filter && $filter['media'] && $filter['media']==$m->id) selected @endif>{{$m->m_name}}</option>
                                            @endforeach
                                           
                                        </select>
                                        
                                    </div>
                                </div>
                                
                                <div class="mb-3 col-lg-12">
                                    <label class="form-label" for="performanceYear">Project</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="performanceYear"><i
                                                class="bx bx-cross"></i></label>
                                        <select class="form-select @error('tipe') is-invalid @enderror" id="project"
                                            name="project">
                                            <option value="All" selected>All Project</option>
                                            @foreach($project as $p)
                                            <option value="{{$p->id}}" @if($filter && $filter['project'] && $filter['project']==$p->id) selected @endif>{{$p->title}} Project</option>
                                            @endforeach

                                        </select>
                                        <button type="submit" class="btn btn-primary">Get
                                            Data</button>
                                        @if($filter)
                                            <a href="{{url('filter-clear-approved-list')}}" class="btn btn-danger"><i class="bx bx-trash"></i></a>
                                        @endif
                                    </div>
                                </div>
                                
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
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
                                {{-- <div class="col-sm-2">
                                    <a style="float:right" href="{{route('news_media.create')}}"
                                        class="btn btn-primary"><i class="bx bx-list-plus me-1"></i>Add Media</a>
                                </div> --}}
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
                                        <?php
                                            $user = Auth::User();
                                        ?>

                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                    <tr class="text-nowrap">
                                                        <th>#</th>
                                                        <th>Logo</th>
                                                        <th>Name</th>
                                                        <th>Email</th>
                                                        <th>Period</th>
                                                        <th>Online</th>
                                                        <th>Printed</th>
                                                        <th>Total</th>
                                                        @if($user->level=='Super Admin')
                                                        <th>Action</th>
                                                        @endif

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=$data->currentPage();?>
                                                    @foreach($data as $d)
                                                    <tr>
                                                        <th scope="row">{{$i}}</th>
                                                        <td>@if($d->logo=='-')
                                                            <img src="{{asset('no_photo.png')}}" width="50px" />
                                                            @else
                                                            <img id="myImg" onclick="javascript:imageClick(this)" alt="{{$d->m_name}}" src="{{asset('/news_media_logo/'.$d->logo)}}"
                                                                width="50px"
                                                                style="max-height: 100px;object-fit:cover;border-radius:10px" />
                                                            @endif</td>
                                                        <td>{{$d->m_name}}</td>
                                                        <td>{{$d->email}}</td>
                                                        <td>{{$d["detail"]["period"]}}</td>
                                                        <td>{{$d["detail"]["online"] + $d["detail"]["both"]}}</td>
                                                        <td>{{$d["detail"]["printed"]+$d["detail"]["both"]}}</td>
                                                        <td>{{$d["detail"]["total"]}}</td>

                                                        @if($user->level=='Super Admin')
                                                        <td>
                                                            <?php 
                                                                    if($d['detail']['status']=='none'){
                                                            
                                                                $total = $d["detail"]["online"] + $d["detail"]["printed"] + ($d["detail"]["both"]*2);
                                                                $printed = $d["detail"]["printed"]+$d['detail']['both'];
                                                                $online = $d['detail']['online']+$d['detail']['both'];
                                                                if($d["detail"]["project"]=="All Project" && ($online>=25 || $printed>=3)){?>
                                                                   <form action="{{ url('approved_media/store') }}"
                                                                   method="post">
                                                                   @csrf
                                                                   {{-- @method('delete') --}}
                                                                   {{-- <a id="berkasId"
                                                                       href="{{ url('news_media/'. $d->id.'/edit') }}"
                                                                       class="btn btn-warning">
                                                                       <i class="bx bx-edit-alt me-1"></i>
                                                                   </a> --}}
                                                                   <input type="hidden" value="{{$d->id}}" name="media"/>
                                                                   <input type="hidden" value="{{date('Y-m',strtotime('now'))}}" name='period'/>
                                                                   {{-- <input type="hidden" value="" --}}
                                                                   <button type="submit" class="btn btn-success"
                                                                       onclick="return confirm('Do you sure to make this Media in Approved for This Month?')">
                                                                       <i class="bx bx-check me-1"></i> Approve
                                                                   </button>
   
                                                               </form><?php
                                                                }else{
                                                                
                                                                echo "<span style='font-size:11px;color:red;'>Not Qualified Yet, Minimum Total Contribution is (25)</span>";
                                                                
                                                            }
                                                            }else { echo "<b>".$d['detail']['status']."</b>";}?>
                                                           <form action="{{url('filter-media-prove')}}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="media" value="{{$m["media_id"]}}"/>
                                                            <input type="hidden" name="startDate" value="{{date('Y-m-01',strtotime('first day this month'))}}"/>
                                                            <input type="hidden" name="endDate" value="{{date('Y-m-t',strtotime('last day this month'))}}"/>
                                                            <button type="submit" class="btn btn-info">Detail</button>

                                                        
                                                        </form>
                                                        
                                        </td>
                                        @endif
                                        </tr>
                                        <?php $i+=1;?>
                                        @endforeach
                                        </tbody>
                                        <tfoot>
                                            <tr class="text-nowrap">
                                                <td colspan='9'>{{$data->links()}}</td>
                                            </tr>
                                        </tfoot>
                                        </table>

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
