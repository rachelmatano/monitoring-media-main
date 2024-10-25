@extends('admin.partial.layout')
@section('content')
<style>
    .clockdate-wrapper {
        background-color: #4297ff;
        padding: 15px;
        max-width: 550px;
        width: 100%;
        text-align: center;
        border-radius: 5px;
        margin: 0 auto;

    }

    #clock {
        background-color: #4265ff;
        font-family: sans-serif;
        font-size: 60px;
        text-shadow: 0px 0px 1px #fff;
        color: #fff;
        border-radius: 5px;
    }

    #clock span {
        color: #002744;
        text-shadow: 0px 0px 1px #333333;
        font-size: 30px;
        position: relative;
        top: -25px;
        left: 10px;
    }

    #date {
        letter-spacing: 3px;
        font-size: 14px;
        font-family: arial, sans-serif;
        color: #fff;
        margin-top: 10px;
    }

</style>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        
        <div class="col-lg-6 mb-4 order-0">
            <div class="card">
                <div class="card-body">
                    <div id="clockdate">
                        <div class="clockdate-wrapper">
                            <div id="clock"></div>
                            <div id="date"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 mb-4 order-0">
            <div class="card">
                <div class="card-body">
                    <h3>Notification <i class="bx bx-bell"></i></h3>
                   
                    <h1 style="background:#4265ff;padding:10px;color:#fff;border-radius:5px;text-align:center">{{$unreadNotif}} Unread</h1>
                    <span style="width:100%;text-align:center;" class="badge bg-info">{{$readNotif}} Read</span>
                </div>
            </div>
        </div>
        <div class="col-lg-3 mb-4 order-0">
            <div class="card">
                <div class="card-body">
                    <h3>Media Contribute</h3>
                   
                    <h1 style="background:#4265ff;padding:10px;color:#fff;border-radius:5px;text-align:center;">{{$totalMedia}} Media</h1>
                    <span style="width:100%;text-align:center;" class="badge bg-info">{{date('F Y',strtotime('now'))}}</span>
                </div>
            </div>
        </div>
        <div class="col-lg-12 mb-4 order-1">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h5 class="card-title text-primary">Current Active Project</h5>
                                    <p class="mb-4">

                                    </p>
                                </div>
                                
                                <div class="col-sm-12">
                                    
                                    <div class=" text-center ">

                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                    <tr class="text-nowrap">
                                                        <th>#</th>
                                                        <th>Title</th>
                                                        {{-- <th>Min. Cont.</th> --}}
                                                        <th>Start From</th>
                                                        <th>Valid Until</th>
                                                        {{-- <th>Total Participant</th> --}}
                                                        <th>Action</th>
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1;?>
                                                    @foreach($activeProject as $d) 
                                                    <tr>
                                                        <th scope="row">{{$i}}</th>
                                                       
                                                        <td>{{$d->title}}</td>
                                                        {{-- <td>{!!$d->content!!}</td> --}}
                                                        {{-- <td>{{$d->minimum}}</td> --}}
                                                        <td>{{date('d F Y',strtotime($d->date_posted))}}</td>
                                                        <td>{{date('d F Y',strtotime($d->valid_until))}}</td>
                                                        <td><a href="{{url('project/'.$d->id)}}" class="btn btn-info">Detail</a></td>
                                                        {{-- <td><a href="{{url('project/'.$d->id)}}">{{$d->total_participant}} Media</td> --}}
                                                        {{-- <td>
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
                                                            </div> --}}
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
                                                            
                                                        {{-- </td> --}}
                                                    </tr>
                                                    <?php $i+=1;?>
                                                    @endforeach
                                                </tbody>
                                               
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
        <div class="col-lg-12 mb-4 order-1">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h5 class="card-title text-primary">Media Performance this month</h5>
                                    
                                </div>
                                
                                <div class="col-sm-12">
                                    
                                    <div class=" text-center ">
                                        <?php
                                            $user = Auth::user();
                                            ?>

                                        <div class="table-responsive text-nowrap">
                                            <table class="table table-striped table-hover table-bordered">
                                                <thead>
                                                    <tr class="text-nowrap">
                                                        <th>#</th>
                                                        <th>Media</th>
                                                        <th>Period</th>
                                                        <th>Online</th>
                                                        <th>Printed</th>
                                                        <th>Total</th>
                                                        <th>Action</th>
                                    
                                                       
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=1;?>
                                                    @foreach($mediaThisMonth as $m) 
                                                    <tr>
                                                        <th scope="row">{{$i}}</th>
                                                       
                                                        <td>{{$m["media"]}}</td>
                                                        {{-- <td>{!!$d->content!!}</td> --}}
                                                        <td>{{$m["period"]}}</td>
                                                        <td>{{$m["online"]+$m["both"]}}</td>
                                                        <td>{{$m["printed"]+$m["both"]}}</td>
                                                        <td>{{$m["total"]}}</td>
                                                        @if($user->level=='Super Admin')
                                                        <td><form action="{{ url('approved_media/store') }}"
                                                            method="post">
                                                            @csrf
                                                            {{-- @method('delete') --}}
                                                            {{-- <a id="berkasId"
                                                                href="{{ url('news_media/'. $d->id.'/edit') }}"
                                                                class="btn btn-warning">
                                                                <i class="bx bx-edit-alt me-1"></i>
                                                            </a> --}}
                                                            <input type="hidden" value="{{$m["media_id"]}}" name="media"/>
                                                            <input type="hidden" value="{{date('Y-m',strtotime('now'))}}" name='period'/>
                                                            {{-- <input type="hidden" value="" --}}
                                                            <button type="submit" class="btn btn-success"
                                                                onclick="return confirm('Do you sure to make this Media in Approved for This Month?')">
                                                                <i class="bx bx-check me-1"></i> Approve
                                                            </button>

                                                        </form>
                                                        <form action="{{url('filter-media-prove')}}" method="POST">
                                                            @csrf
                                                            <input type="hidden" name="media" value="{{$m["media_id"]}}"/>
                                                            <input type="hidden" name="startDate" value="{{date('Y-m-01',strtotime('first day this month'))}}"/>
                                                            <input type="hidden" name="endDate" value="{{date('Y-m-t',strtotime('last day this month'))}}"/>
                                                            <button type="submit" class="btn btn-info">Detail</button>

                                                        
                                                        </form>
                                                    </td>
                                                    @endif
                                                    @if($user->level=='Admin')
                                                    <td>
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
        <div class="col-lg-12 mb-4 order-2">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-8">
                                    <h5 class="card-title text-primary">Total Media Contribution</h5>
                                    <p class="mb-4">

                                    </p>
                                </div>

                                <div class="col-sm-8">
                                    <div class="row">
                                        <div class="mb-3 col-sm-3">
                                            <label class="form-label" for="performanceYear">Performance
                                                Year</label>
                                            <div class="input-group">
                                                <label class="input-group-text" for="performanceYear"><i
                                                        class="bx bx-cross"></i></label>
                                                <select class="form-select @error('tipe') is-invalid @enderror"
                                                    id="performanceYear" name="tipe">

                                                    @for($i=2000;$i<2024;$i++) <option value="{{$i}}">{{$i}}</option>
                                                        @endfor
                                                        <option value="{{date('Y',strtotime('now'))}}" selected>
                                                            {{date('Y',strtotime('now'))}}</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="mb-3 col-sm-4">
                                            <label class="form-label" for="performanceYear">Project</label>
                                            <div class="input-group">
                                                <label class="input-group-text" for="performanceYear"><i
                                                        class="bx bx-cross"></i></label>
                                                <select class="form-select @error('tipe') is-invalid @enderror"
                                                    id="project" name="tipe">
                                                    <option value="All" selected>All Project</option>
                                                    @foreach($project as $p)
                                                    <option value="{{$p->id}}">{{$p->title}}</option>
                                                    @endforeach

                                                </select>
                                                <button onclick="getDataPerYear()" class="btn btn-primary">Get
                                                    Data</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-8">
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
                                        <div id="chart_place"><canvas id="chart_test"></canvas></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="table-responsive" id="table_place">
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    function getDataPerYear() {
        const year = document.getElementById('performanceYear');
        const project = document.getElementById('project');
        $('#chart_test').remove();
        $('#chart_place').append('<canvas id="chart_test"></canvas>');
        $.get('{{url("/get_data_per_year")}}/' + year.value + "/" + project.value, function (data) {
            // alert(JSON.stringify(data["data"]));

            var config = {
                type: "bar",

                data: {
                    labels: data["label"],
                    datasets: data["data"]

                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true
                            }
                        }]
                    }
                }
            };
            myChart = new Chart(
                'chart_test',
                config
            );
            createTable(data);
        });
    }

    function createTable(data) {
        var mainDiv = document.getElementById('table_place');

        var check = document.getElementById('data_prove');
        if (check) {
            mainDiv.removeChild(check);
        }

        var table = document.createElement('TABLE');
        table.setAttribute('id', 'data_prove');
        table.setAttribute('class', 'table table-striped table-bordered');
        table.appendChild(createTableHead(data["year"]));
        var tBody = document.createElement('TBODY');
        // alert(data["year"]);
        for (i = 0; i < data["data"][0]["data"].length; i++) {
            var tr = document.createElement("TR");
            var td = document.createElement('TD');
            td.appendChild(document.createTextNode((i + 1)));
            tr.appendChild(td);
            td = document.createElement('TD');
            // alert(i);
            td.appendChild(document.createTextNode(`${data["data"][0]["data"][i]["x"]}`));
            tr.appendChild(td);
            td = document.createElement('TD');
            td.appendChild(document.createTextNode(data["data"][0]["data"][i]["y"]));
            tr.appendChild(td);
            td = document.createElement('TD');
            td.appendChild(document.createTextNode(data["data"][1]["data"][i]["y"]));
            tr.appendChild(td);
            tBody.appendChild(tr);
        }
        table.appendChild(tBody);
        mainDiv.appendChild(table);

    }

    function createTableHead(period) {
        var tHead = document.createElement('THEAD');
        var trHead = document.createElement('TR');
        var th = document.createElement('TH');
        th.width = '5%';
        th.appendChild(document.createTextNode("No."));
        trHead.appendChild(th);
        th = document.createElement('TH');
        th.appendChild(document.createTextNode(`Period ${period}`));
        trHead.appendChild(th);
        th = document.createElement('TH');
        th.appendChild(document.createTextNode('Online'));
        trHead.appendChild(th);
        th = document.createElement('TH');
        th.appendChild(document.createTextNode('Printed'));
        trHead.appendChild(th);
        tHead.appendChild(trHead);
        return tHead;
    }
    $(function () {
        getDataPerYear();
        startTime();
    });

    function startTime() {
        var today = new Date();
        var hr = today.getHours();
        var min = today.getMinutes();
        var sec = today.getSeconds();
        ap = (hr < 12) ? "<span>AM</span>" : "<span>PM</span>";
        hr = (hr == 0) ? 12 : hr;
        hr = (hr > 12) ? hr - 12 : hr;
        //Add a zero in front of numbers<10
        hr = checkTime(hr);
        min = checkTime(min);
        sec = checkTime(sec);
        document.getElementById("clock").innerHTML = hr + ":" + min + ":" + sec + " " + ap;

        var months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October',
            'November', 'December'
        ];
        var days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        var curWeekDay = days[today.getDay()];
        var curDay = today.getDate();
        var curMonth = months[today.getMonth()];
        var curYear = today.getFullYear();
        var date = curWeekDay + ", " + curDay + " " + curMonth + " " + curYear;
        document.getElementById("date").innerHTML = date;

        var time = setTimeout(function () {
            startTime()
        }, 500);
    }

    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        }
        return i;
    }

</script>
@endsection
