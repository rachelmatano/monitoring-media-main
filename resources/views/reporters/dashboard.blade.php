@extends('reporters.partial.layout')
@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="card-header bg-primary">
                    <h3 class="card-title text-white">Welcome {{$user->name}}</h3>
                </div>
                <div class="d-flex align-items-end row">
                    <div class="col-sm-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h3>Welcome {{$user->name}}</h3>
                                    <div class="row">
                                        <div class="col-sm-12" style="text-align: center">
                                            <h3 class="text-primary">Visi</h3>
                                            <h3 style="text-align: center">"Minahasa Maju Dalam Ekonomi Dan Budaya, Berdaulat, Adil, dan Sejahtera"</h3>
                                        </div>
                                        <div class="col-sm-12" style="text-align: center">
                                            <h3 class="text-primary">Misi Diskominfo</h3>
                                            <h3 style="text-align:center">"Mewujudkan Birokrasi Layanan Komunikasi dan Informatika yang Profesional, Integritas, Berkomitmen dan Beretos Kerja yang Hebat"</h3>
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
