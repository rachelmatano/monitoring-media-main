@extends('admin.partial.layout')
@section('content')

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
                                
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="mb-3 col-sm-2">
                                            <label class="form-label" for="month">Month</label>
                                            <div class="input-group">
                                                <label class="input-group-text" for="month"><i
                                                        class="bx bx-cross"></i></label>
                                                <select class="form-select @error('tipe') is-invalid @enderror"
                                                    id="month" name="tipe">
                                                        <?php
                                                        $month = [
                                                            "January","February","March","April","May","June","July","August","September","October","November","December"
                                                ];
                                                    $index = 1;
                                                        ?>

                                                        @foreach($month as $m)
                                                            <option value="{{$index<10?"0".$index:$index}}" @if($index==date('m',strtotime('now'))) selected @endif>{{$month[$index-1]}}</option>
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
                                                <select class="form-select @error('tipe') is-invalid @enderror"
                                                    id="performanceYear" name="tipe">
                                                    
                                                    @for($i=2000;$i<2024;$i++) <option value="{{$i}}">{{$i}}</option>
                                                        @endfor
                                                        <option value="{{date('Y',strtotime('now'))}}" selected>
                                                            {{date('Y',strtotime('now'))}}</option>
                                                </select>
                                                
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-3">
                                            <label class="form-label" for="performanceYear">News Media</label>
                                            <div class="input-group">
                                                <label class="input-group-text" for="performanceYear"><i
                                                        class="bx bx-cross"></i></label>
                                                <select class="form-select @error('tipe') is-invalid @enderror"
                                                    id="media" name="tipe">
                                                    <option value="All" selected>Select News Media</option>
                                                    @foreach($media as $m)
                                                        <option value="{{$m->id}}">{{$m->m_name}} </option>
                                                    @endforeach
                                                    
                                                </select>
                                                
                                            </div>
                                        </div>
                                        <div class="mb-3 col-sm-5">
                                            <label class="form-label" for="performanceYear">Project</label>
                                            <div class="input-group">
                                                <label class="input-group-text" for="performanceYear"><i
                                                        class="bx bx-cross"></i></label>
                                                <select class="form-select @error('tipe') is-invalid @enderror"
                                                    id="project" name="tipe">
                                                    <option value="All" selected>All Project</option>
                                                    @foreach($project as $p)
                                                        <option value="{{$p->id}}">{{$p->title}} Project</option>
                                                    @endforeach
                                                    
                                                </select>
                                                <button onclick="javascript:getDataPerMonth()" class="btn btn-primary">Get
                                                    Data</button>
                                            </div>
                                        </div>
                                    </div>
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
                                    <div class=" text-center row">
                                        <div id="chart_place" class="col-sm-6"><canvas id="chart_test"></canvas></div>
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
     function getDataPerMonth() {
        const month = document.getElementById('month');
        const year = document.getElementById('performanceYear');
        const project = document.getElementById('project');
        const media = document.getElementById('media');
        // alert("Month : "+month.value+'{{url("/data_per_month")}}/' + year.value+"-"+month.value+"/"+project.value);
        $('#chart_test').remove();
        $('#chart_place').append('<canvas id="chart_test"></canvas>');
        alert('{{url("/get_media_data_per_month")}}/' + year.value+"-"+month.value+"/"+media.value+"/"+project.value);
        $.get('{{url("/get_media_data_per_month")}}/' + year.value+"-"+month.value+"/"+media.value+"/"+project.value, function (data) {
            alert(JSON.stringify(data["online_count"]));

            var config = {
                type: "bar",
                label:"# Contribution",
                data: {
                    labels: ["Contribution"],
                    datasets: [{
                        label:"Online News",
                        data:[data["online_count"]+data["both_count"]],
                        backgroundColor:['rgba(255,99,132,0.2)'],
                        borderColor:['rgba(255,99,132,1)'],
                        borderWidth:1
                    },{
                        label:"Printed News",
                        data:[data['printed_count']+data["both_count"]],
                        backgroundColor:['rgba(54,162,235,0.2)'],
                        borderColor:['rgba(54,162,235,1)'],
                        borderWidth:1
                    }
                ]

                },
                options: {
                    title:{
                        display:true,
                        text:media.options[media.selectedIndex].text+' Status Contribution on '+month.options[month.selectedIndex].text+' '+year.value +' on '+project.options[project.selectedIndex].text
                    },
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
            
        });
    }
    
</script>
@endsection
