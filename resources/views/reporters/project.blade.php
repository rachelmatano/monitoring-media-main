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
                    <?php $filter = session('filter-project-reporter');?>
                    <div id="accordionOne" class="accordion-collapse collapse @if($filter) show @else in @endif" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            <form action="{{url('project_filter')}}" method="POST">
                                @csrf
                            
                            <div class="row">
                                <div class="divider">
                                    <div class="divider-text">
                                       <i class="bx bx-filter"></i>
                                    </div>
                                </div>
                                <div class="mb-3 col-lg-6 ">
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
                                <div class="mb-3 col-lg-4 ">
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
                <div class="card-header">
                    <h3 class="card-title">{{$pageName}}</h3>
                    <hr class="my-0" />

                </div>
                <div class="card-body">
                    <div class="d-flex align-items-end row">
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
                            <div class="table-responsive  text-nowrap">
                                <table class="table table-bordered table-striped" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th width="10%">No.</th>
                                            <th width="35%">Project Title</th>
                                            <th>Date Posted</th>
                                            <th>Valid Until</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                    $index = $project->currentPage();
                                ?>
                                        @foreach($project as $p)
                                        <tr>
                                            <td>{{$index}}</td>
                                            <td><a href="{{url('project_media/'.$p->id)}}"><b>{{$p->title}}</b></a>
                                            </td>
                                            <td><b>{{date('d F Y',strtotime($p->date_posted))}}</b></td>
                                            <td><b>{{date('d F Y',strtotime($p->valid_until))}}</b></td>
                                        </tr>
                                        @php $index+=1; @endphp
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4">{{$project->links()}}</th>
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

@endsection
