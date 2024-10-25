@extends('admin.partial.layout')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-sm-10">
                            <h1>{{$notif->title}}</h1>
                            <p>posted at : {{date('d F Y H:i:s',strtotime($notif->created_at))}}</p>
                        </div>
                        <div class="col-sm-2">
                            <a style="float:right" href="{{url('notification')}}" class="btn btn-primary"><i
                                    class="bx bx-left-arrow me-1"></i>Back</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            {!!$notif->content!!}
                        </div>

                    </div>
                </div>
            </div>


        </div>
    </div>

</div>

@endsection
