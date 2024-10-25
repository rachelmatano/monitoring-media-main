@extends('admin.partial.layout')
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
                    <?php
                    $filter = session('filter_project');
                    ?>
                    <div id="accordionOne" class="accordion-collapse collapse @if($filter) show @else in @endif" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <form action="{{url('filter-project')}}" method="POST">
                                @csrf
                                
                            <div class="row">
                                <div class="divider">
                                    <div class="divider-text">
                                       <i class="bx bx-filter"></i>
                                    </div>
                                </div>
                                <div class="mb-3 col-lg-12 ">
                                    <label class="form-label" for="performanceYear">Project Title</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="performanceYear"><i
                                                class="bx bx-cross"></i></label>
                                        <input type="text" class="form-control @error('title') is-invalid @enderror"
                                            placeholder="Project title" name="title" value="{{$filter['title']??''}}"/>
                                        
                                    </div>
                                </div>
                               
                                <div class="mb-3 col-lg-3 ">
                                    <label class="form-label" for="month">Start Date</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="month"><i
                                                class="bx bx-calendar"></i></label>
                                                <input type="date" class="form-control @error("startDate")
                                                is-invalid @enderror" id="basic-icon-default-fullname"
                                                placeholder="John Doe" value="{{$filter['startDate']??''}}"
                                                aria-label="John Doe" name="startDate"
                                                aria-describedby="basic-icon-default-fullname2" />
                                    </div>
                                </div>
                                <div class="mb-3 col-lg-3 ">
                                    <label class="form-label" for="performanceYear">End Date</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="performanceYear"><i
                                                class="bx bx-calendar"></i></label>
                                        <input type="date" class="form-control @error("endDate")
                                                is-invalid @enderror" id="basic-icon-default-fullname"
                                                placeholder="John Doe" value="{{$filter['endDate']??''}}"
                                                aria-label="John Doe" name="endDate"
                                                aria-describedby="basic-icon-default-fullname2" />
                                    </div>
                                </div>
                                <div class="mb-3 col-lg-6 ">
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
                                        <button onclick="getDataPerMonth()" class="btn btn-primary">Get
                                            Data</button>
                                        @if($filter)
                                            <a href="{{url('admin_clear_filter/project')}}" class="btn btn-danger"><i class="bx bx-trash"></i></a>
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
                                <?php
                                $user = Auth::user();
                                if($user->level=="Super Admin"){
                                    ?>
                                <div class="col-sm-2">
                                    <a style="float:right" href="{{route('project.create')}}" class="btn btn-primary"><i
                                        class="bx bx-list-plus me-1"></i>Add Project</a>
                                </div>
                                <?php }?>
                                <div class="col-sm-12">
                                    @if(session()->get('error'))
                                    <div class="alert alert-danger alert-dismissible show fade">
                                        {{session()->get('error')}}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif
                                    @if(session()->get('success'))
                                    <div class="alert alert-success alert-dismissible show fade">
                                        {{session()->get('success')}}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                    @endif
                                    <div class=" text-center ">

                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                    <tr class="text-nowrap">
                                                        <th>#</th>
                                                        <th>Title</th>
                                                        <th width="50%">Content</th>
                                                        <th>Min. Cont.</th>
                                                        <th>Start From</th>
                                                        <th>Valid Until</th>
                                                        {{-- <th>Total Participant</th> --}}
                                                        <th>Action</th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=$data->currentPage();?>
                                                    @foreach($data as $d) 
                                                    <tr>
                                                        <th scope="row">{{$i}}</th>
                                                       
                                                        <td>{{$d->title}}</td>
                                                        <td>{!!$d->content!!}</td>
                                                        <td>{{$d->minimum}}</td>
                                                        <td>{{date('d F Y',strtotime($d->date_posted))}}</td>
                                                        <td>{{date('d F Y',strtotime($d->valid_until))}}</td>
                                                        {{-- <td><a href="{{url('project/'.$d->id)}}">{{$d->total_participant}} Media</td> --}}
                                                        <td>
                                                            @if($user->level=="Super Admin")
                                                            <div class="dropdown">
                                                                <form action="{{url('project/'.$d->id)}}" method="POST">
                                                                    @csrf
                                                                    @method('delete')
                                                                <button type="button"
                                                                    class="btn p-0 dropdown-toggle hide-arrow"
                                                                    data-bs-toggle="dropdown">
                                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                                </button>
                                                                <div class="dropdown-menu">
                                                                    <a class="dropdown-item"
                                                                    href="{{url('/project/'.$d->id)}}"><i
                                                                    class="bx bx-detail me-1"></i> Detail</a>
                                                                    <a class="dropdown-item"
                                                                        href="{{url('/project/'.$d->id.'/edit')}}"><i
                                                                        class="bx bx-edit-alt me-1"></i> Edit</a>
                                                                    <button type="submit" class="dropdown-item"><i
                                                                            class="bx bx-trash me-1"></i> Delete</button>
                                                                    
                                                                </div>
                                                            </form>
                                                            </div>
                                                            @else
                                                                <a href="{{url('/project/'.$d->id)}}" class="btn btn-info"><i class="bx bx-detail me-1"></i>Detail</a>
                                                            @endif
                                                            {{-- <form action="{{ url('project/'. $d->id) }}" method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <a id="berkasId" href="{{ url('project/'. $d->id) }}"
                                                                    class="btn btn-info">
                                                                    <i class="bx bx-detail me-1"></i>
                                                                </a>
                                                                <a id="berkasId" href="{{ url('project/'. $d->id.'/edit') }}"
                                                                    class="btn btn-warning">
                                                                    <i class="bx bx-edit-alt me-1"></i>
                                                                </a>
                                                             
                                                                <button type="submit" class="btn btn-danger"
                                                                    onclick="return confirm('Apakah anda yakin untuk menghapus data ini?')">
                                                                    <i class="bx bx-trash me-1"></i>
                                                                </button>
                                                              
                                                            </form> --}}
                                                            
                                                        </td>
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


        </div></div>

        </div>
        @endsection
