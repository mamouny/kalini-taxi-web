@extends('admin.layout.admin-layout')
@section('title','Kalini | Clients')
@section('content')
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Kalini</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Taxi</a></li>
                            <li class="breadcrumb-item active">Clients</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Clients</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Clients</h4>
                    </div>
                    <div class="card-body">
                        @include('admin.partials.messages')
                        <div class="tab-content">
                            <div class="tab-pane show active" id="basic-datatable-preview">
                                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Nom & Prénom</th>
                                        <th>Téléphone</th>
                                        <td>Mondant dans le Portefeuille</td>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($clients as $client)
                                        <tr>
                                            <td>{{$client['nom']}}</td>
                                            <td>{{$client['tel']}}</td>
                                            <td>{{$client['wallet']['amount']}} mru</td>
                                            <td class="d-flex gap-1">
                                                <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#showModal-{{$client['id']}}"
                                                >
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                                <form action="{{route('clients.destroy',$client['id'])}}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous supprimer ce client ?')">
                                                        <i class="mdi mdi-trash-can-outline"></i>
                                                    </button>
                                                </form>
                                            </td>

                                            @include('admin.clients.delete-modal')
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div> <!-- end preview-->

                        </div> <!-- end tab-content-->

                    </div> <!-- end card body-->
                </div> <!-- end card -->
            </div><!-- end col-->
        </div>
        <!-- end row-->
    </div>
@endsection

