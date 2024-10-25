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
                    <?php $filter = session('filter-media-prove-reporter');?>
                    <div id="accordionOne" class="accordion-collapse collapse @if($filter) show @else in @endif" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <form action="{{url('filter_media_prove')}}" method="POST">
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
                                            placeholder="Media Name" name="title" value="{{$filter['title']??''}}"/>
                                           
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
                                                    <option value="{{$m[0]}}" @if($m[0]==date('m',strtotime('now'))||($filter && $filter['month']==$m[0])) selected @endif>{{$m[1]}}</option>
                                                    @php $index+=1; @endphp
                                                @endforeach
                                        </select>

                                    </div>
                                </div>
                                <div class="mb-3 col-lg-2 ">
                                    <label class="form-label" for="performanceYear">Performance
                                        Year</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="performanceYear"><i
                                                class="bx bx-cross"></i></label>
                                        <select class="form-select @error('year') is-invalid @enderror"
                                                id="performanceYear" name="year">
                                                
                                                @for($i=2000;$i<=2024;$i++) <option value="{{$i}}" @if(date('Y',strtotime('now'))==$i || ($filter && $filter['year']==$i)) selected @endif>{{$i}}</option>
                                                    @endfor
                                                    {{-- <option value="{{date('Y',strtotime('now'))}}" selected>
                                                        {{date('Y',strtotime('now'))}}</option> --}}
                                            </select>
                                       
                                    </div>
                                </div>
                                <div class="mb-3 col-lg-2">
                                    <label class="form-label" for="month">Type</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="month"><i
                                                class="bx bx-cross"></i></label>
                                        <select class="form-select @error('tipe') is-invalid @enderror"
                                            id="type" name="tipe">
                                            <option selected>Choose...</option>
                                            <option value="Online"
                                                {{$filter && $filter['tipe'] && $filter['tipe']=='Online'?'selected':''}}>Online News
                                            </option>
                                            <option value="Printed"
                                                {{$filter && $filter['tipe'] && $filter['tipe']=='Printed'?'selected':''}}>Printed News
                                            </option>
                                            <option value="Both" {{$filter && $filter['tipe'] && $filter['tipe']=='Both'?'selected':''}}>
                                                Online & Printed
                                                News
                                            </option>  
                                           
                                        </select>

                                    </div>
                                </div>
                                <div class="mb-3 col-lg-6">
                                    <label class="form-label" for="performanceYear">Project</label>
                                    <div class="input-group">
                                        <label class="input-group-text" for="performanceYear"><i
                                                class="bx bx-cross"></i></label>
                                        <select class="form-select @error('tipe') is-invalid @enderror" id="project"
                                            name="tipe">
                                            <option value="All" selected>All Project</option>
                                            @foreach($project as $p)
                                            <option value="{{$p->id}}" @if($filter && $filter['project'] && $filter['project']==$p->id) selected @endif>{{$p->title}} Project</option>
                                            @endforeach

                                        </select>
                                        <button type="submit" class="btn btn-primary">Get
                                            Data</button>
                                            @if($filter)
                                            <a href="{{url('reporter_clear_filter/project_media')}}" class="btn btn-danger"><i class="bx bx-trash"></i></a>
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
                                <div class="col-sm-2">
                                    <a style="float:right" href="{{route('media_prove.create')}}"
                                        class="btn btn-primary"><i class="bx bx-list-plus me-1"></i>Add Media Prove</a>
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
                                                        <th>Date Posted</th>
                                                        <th>Title</th>
                                                        <th>Project</th>
                                                        <th>Link</th>
                                                        <th>Type</th>
                                                        <th>Reporter</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=$media_prove->currentPage() * 15;?>
                                                    @foreach($media_prove as $d)
                                                    <tr>
                                                        <th scope="row">{{$i}}</th>
                                                        <td>{{date('d F Y',strtotime($d->date_posted))}}</td>
                                                        <td>{{$d->title}}</td>
                                                        <td>{{$d->project}}</td>
                                                        <td>
                                                            @if($d->link!='-')
                                                            <a href="{{$d->link}}">Open</a>
                                                            @else
                                                            <b>No Link</b>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if($d->tipe=="Online")
                                                            <span class="badge bg-info">Online</span>
                                                            @elseif($d->tipe=="Printed")
                                                            <span class="badge bg-secondary">Printed</span>
                                                            @else
                                                            <span class="badge bg-primary">Online & Printed</span>
                                                            @endif
                                                        </td>
                                                        <td>{{$d->reporter}}</td>


                                                        <td>
                                                            {{-- <form action="{{ url('media_prove/'. $d->id) }}"
                                                            method="post">
                                                            @csrf
                                                            @method('delete')
                                                            <a id="berkasId"
                                                                href="{{ url('media_prove/'. $d->id.'/edit') }}"
                                                                class="btn btn-warning">
                                                                <i class="bx bx-edit-alt me-1"></i>
                                                            </a>

                                                            <button type="submit" class="btn btn-danger"
                                                                onclick="return confirm('Apakah anda yakin untuk menghapus data ini?')">
                                                                <i class="bx bx-trash me-1"></i>
                                                            </button>

                                                            </form> --}}
                                                            @if(date('Y-m',strtotime('now'))==date('Y-m',strtotime($d->date_posted)))
                                                            <div class="dropdown">
                                                                <form action="{{url('media_prove_reporter/'.$d->id)}}"
                                                                    method="POST">
                                                                    @csrf
                                                                    @method('delete')
                                                                    <button type="button"
                                                                        class="btn p-0 dropdown-toggle hide-arrow"
                                                                        data-bs-toggle="dropdown">
                                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                                    </button>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item"
                                                                            href="{{url('/media_prove_reporter_detail/'.$d->id)}}"><i
                                                                                class="bx bx-detail me-1"></i>
                                                                            Detail</a>
                                                                        <a class="dropdown-item"
                                                                            href="{{url('/media_prove_reporter/'.$d->id)}}"><i
                                                                                class="bx bx-edit-alt me-1"></i>
                                                                            Edit</a>
                                                                        <button type="submit" class="dropdown-item"><i
                                                                                class="bx bx-trash me-1"></i>
                                                                            Delete</button>

                                                                    </div>
                                                                </form>
                                                            </div>
                                                            @else
                                                            <span class="badge bg-success">Passed</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    <?php $i+=1;?>
                                                    @endforeach
                                                </tbody>
                                                <tfoot>
                                                    <tr class="text-nowrap">
                                                        <td colspan='9'>{{$media_prove->links()}}</td>
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
