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
                                <div class="col-sm-2">
                                    <a style="float:right" href="{{route('user.create')}}" class="btn btn-primary"><i
                                            class="bx bx-list-plus me-1"></i>Add User</a>
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
                                                        <th>Email</th>
                                                        <th>Name</th>
                                                        <th>Level</th>
                                                        <th>Created At</th>
                                                        <th>Updated At</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i=$data->currentPage();?>
                                                    @foreach($data as $d)
                                                    <tr>
                                                        <th scope="row">{{$i}}</th>

                                                        <td>{{$d->email}}</td>
                                                        <td>{{$d->name}}</td>
                                                        <td>{{$d->level}}</td>
                                                        <td>{{date('d F Y',strtotime($d->created_at))}}</td>
                                                        <td>{{date('d F Y',strtotime($d->updated_at))}}</td>

                                                        <td>
                                                            <form action="{{ route('user.destroy', $d->id) }}" method="post">
                                                                @csrf
                                                                @method('delete')
                                                                <a id="berkasId"
                                                                    href="{{ url('user/'. $d->id.'/edit') }}"
                                                                    class="btn btn-warning">
                                                                    <i class="bx bx-edit-alt me-1"></i>
                                                                </a>

                                                                <button type="submit" class="btn btn-danger"
                                                                    onclick="return confirm('Apakah anda yakin untuk menghapus data ini?')">
                                                                    <i class="bx bx-trash me-1"></i>
                                                                </button>

                                                            </form>
                                                            {{-- <div class="dropdown">
                                                                <form action="{{url('news_media/'.$d->id)}}"
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
                                                                    href="{{url('/news_media/'.$d->id.'/edit')}}"><i
                                                                        class="bx bx-edit-alt me-1"></i> Edit</a>
                                                                <button type="submit" class="dropdown-item"><i
                                                                        class="bx bx-trash me-1"></i> Delete</button>

                                                            </div>
                                                            </form>
                                        </div> --}}
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


    </div>
</div>

</div>
@endsection
