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
                                    <div class="table-responsive" id="table_place" >
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
        const media = document.getElementById('media');
        $('#chart_test').remove();
        $('#chart_place').append('<canvas id="chart_test"></canvas>');
        $.get('{{url("/get_media_data_per_year")}}/' + year.value+"/"+media.value+"/"+project.value, function (data) {
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
    function createTable(data){
        var mainDiv = document.getElementById('table_place');

        var check = document.getElementById('data_prove');
        if(check){
            mainDiv.removeChild(check);
        }
        
        var table = document.createElement('TABLE');
        table.setAttribute('id','data_prove');
        table.setAttribute('class','table table-striped table-bordered');
        table.appendChild(createTableHead(data["year"]));
        var tBody = document.createElement('TBODY');
        // alert(data["year"]);
        for(i=0;i<data["data"][0]["data"].length;i++){
            var tr = document.createElement("TR");
            var td = document.createElement('TD');
            td.appendChild(document.createTextNode((i+1)));
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
    function createTableHead(period){
        var tHead = document.createElement('THEAD');
        var trHead = document.createElement('TR');
        var th = document.createElement('TH');
        th.width='5%';
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
    // $(function(){
    //     getDataPerYear();
    // });

</script>
@endsection
