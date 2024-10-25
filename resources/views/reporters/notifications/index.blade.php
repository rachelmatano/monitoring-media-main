@extends('reporters.partial.layout')
@section('content')

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
                    <?php $filter = session('filter-notification-reporter');?>
                    <div id="accordionOne" class="accordion-collapse collapse @if($filter) show @else in @endif" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <form action="{{url('filter_notification')}}" method="POST">
                                @csrf
                                
                            <div class="row">
                                <div class="divider">
                                    <div class="divider-text">
                                       <i class="bx bx-filter"></i>
                                    </div>
                                </div>
                                <div class="mb-3 col-lg-12 ">
                                    <label class="form-label" for="performanceYear">Title</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="performanceYear"><i
                                                class="bx bx-cross"></i></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            placeholder="Title" name="title" value="{{$filter['title']??''}}"/>
                                           
                                    </div>
                                </div>
                                <div class="mb-3 col-lg-2 ">
                                    <label class="form-label" for="month">Start Date</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="month"><i
                                                class="bx bx-calendar"></i></label>
                                                <input type="date" class="form-control @error("start_date")
                                                is-invalid @enderror" id="basic-icon-default-fullname"
                                                placeholder="John Doe" value="{{$filter['startDate']??''}}"
                                                aria-label="John Doe" name="startDate"
                                                aria-describedby="basic-icon-default-fullname2" />
                                    </div>
                                </div>
                                <div class="mb-3 col-lg-2 ">
                                    <label class="form-label" for="performanceYear">End Date</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="performanceYear"><i
                                                class="bx bx-calendar"></i></label>
                                        <input type="date" class="form-control @error("end_date")
                                                is-invalid @enderror" id="basic-icon-default-fullname"
                                                placeholder="John Doe" value="{{$filter['endDate']??''}}"
                                                aria-label="John Doe" name="endDate"
                                                aria-describedby="basic-icon-default-fullname2" />
                                    </div>
                                </div>
                                <div class="mb-3 col-lg-2">
                                    <label class="form-label" for="month">Type</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="month"><i
                                                class="bx bx-cross"></i></label>
                                        <select class="form-select @error('tipe') is-invalid @enderror"
                                            id="type" name="tipe">
                                            <option value="all" selected >
                                                All Type
                                            </option> 
                                            <option value="public"
                                                {{$filter && $filter['tipe'] && $filter['tipe']=='public'?'selected':''}}>Public
                                            </option>
                                            <option value="private"
                                                {{$filter && $filter['tipe'] && $filter['tipe']=='private'?'selected':''}}>Private
                                            </option>
                                             
                                           
                                        </select>

                                    </div>
                                </div>
                                <div class="mb-3 col-lg-6">
                                    <label class="form-label" for="performanceYear">Category</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="performanceYear"><i
                                                class="bx bx-cross"></i></label>
                                        <select class="form-select @error('category') is-invalid @enderror" id="project"
                                            name="category">
                                            <option value="all" selected>
                                                All Category
                                            </option>
                                            <option value="project"
                                                {{$filter && $filter['category'] && $filter['category']=='project'?'selected':''}}>Project
                                            </option>
                                            <option value="information"
                                                {{$filter && $filter['category'] && $filter['category']=='information'?'selected':''}}>Information
                                            </option>
                                              
                                        </select>
                                        <button type="submit" class="btn btn-primary">Get
                                            Data</button>
                                        @if($filter)
                                            <a href="{{url('reporter_clear_filter/notification')}}" class="btn btn-danger"><i class="bx bx-trash"></i></a>
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
                                        You have done <span class="fw-medium">72%</span> more sales today. Check your
                                        new badge
                                        in
                                        your profile.
                                    </p>
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

                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                    <tr class="text-nowrap">
                                                        <th>#</th>
                                                        <th width="35%">Title</th>
                                                        <th>Time</th>
                                                        <th>Category</th>
                                                        <th>Type</th>
                                                        <th>Status</th>
                                                        <th>Media</th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=$notification->currentPage();?>
                                                    @foreach($notification as $d)
                                                    <tr>
                                                        <th scope="row">{{$i}}</th>

                                                        <td><a href="{{url('notification/'.$d->id)}}">{{$d->title}}</a></td>
                                                       
                                                        <td>{{date('d F Y H:i:s a',strtotime($d->notif_time))}}</td>
                                                        <td>{{$d->category}}</td>
                                                        <td>{{$d->tipe}}</td>
                                                        <td><span
                                                                class="badge bg-{{$d->status=='read'?'info':'danger'}}" style="text-transform:uppercase">{{$d->status}}</span></td>
                                                                
                                                        
                                                        <td>{{$d->media_id=='-'?'General':$d->media}}</td>
                                                        
                                                    </tr>
                                                    <?php $i+=1;?>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="text-nowrap">
                                                        <td colspan='9'>{{$notification->links()}}</td>
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

@endsection
