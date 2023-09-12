@extends('admin.layout.admin-layout')
@section('title',trans("traduction.drivers"))
@section('content')
    @php
        use App\Http\Enums\DriverStateEnum;
        use App\Http\Enums\DriverDisponibilityEnum;
    @endphp
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{trans("traduction.app_name")}}</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{trans("traduction.app_name")}}</a></li>
                            <li class="breadcrumb-item active">{{trans("traduction.drivers")}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{trans("traduction.drivers")}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>{{trans("traduction.drivers")}}</h4>
                        <a href="{{route('admin.drivers.create')}}" class="btn btn-warning text-white">
                            <i class="mdi mdi-plus-circle me-1"></i> {{trans("traduction.add_driver")}}
                        </a>
                    </div>
                    <div class="card-body">
                        @include('admin.partials.messages')
                        <div class="tab-content">
                            <div class="tab-pane show active" id="basic-datatable-preview">
                                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>{{trans("traduction.nom_chauffeur")}}</th>
                                        <th>{{trans("traduction.prenom_chauffeur")}}</th>
                                        <th>{{trans("traduction.tel_chauffeur")}}</th>
                                        <th>{{trans("traduction.etat_chauffeur")}}</th>
                                        <th>{{trans("traduction.etat_disponibilite")}}</th>
                                        <th>{{trans("traduction.actions")}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      @foreach($drivers as $driver)
                                            <tr>
                                                <td>{{$driver['nom']}}</td>
                                                <td>{{$driver['prenom']}}</td>
                                                <td>{{$driver['tel']}}</td>
                                                <td>
                                                    @if($driver['etat_chauffeur_id'] == DriverStateEnum::INITIAL->value)
                                                          <span class="badge bg-warning">Initié</span>
                                                        @elseif($driver['etat_chauffeur_id'] == DriverStateEnum::VALIDATED->value)
                                                            <span class="badge bg-info">Vérifier</span>
                                                         @elseif($driver['etat_chauffeur_id'] == DriverStateEnum::VALIDATED_ON_RIDE->value)
                                                            <span class="badge bg-success">Validé</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($driver['etat_disponibilite'] == DriverDisponibilityEnum::AVAILABLE->value)
                                                        <span class="badge bg-success">Disponible</span>
                                                    @else
                                                        <span class="badge bg-danger">Indisponible</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{route('admin.drivers.edit', $driver['id'])}}" class="btn btn-primary btn-sm"><i class="mdi mdi-pencil"></i></a>
{{--                                                    <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#showModalDriver-{{$driver['id']}}"--}}
{{--                                                    >--}}
{{--                                                        <i class="mdi mdi-eye"></i>--}}
{{--                                                    </button>--}}
                                                    @php
                                                        $driverPermisPhoto = \App\Models\DriverDocument::query()->where('driver_id_firebase', $driver['user_id'])->first()->driver_permis_photo;
                                                    @endphp
                                                    <a href="{{asset('uploads/drivers/documents/'.$driverPermisPhoto)}}" target="_blank" class="btn btn-success btn-sm text-white">
                                                        <i class="mdi mdi-file-document"></i>
                                                    </a>
                                                    <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#carModal-{{$driver['id']}}"
                                                    >
                                                        <i class="mdi mdi-car"></i>
                                                    </button>
                                                    <button class="btn btn-secondary btn-sm text-white" data-bs-toggle="modal" data-bs-target="#walletModal-{{$driver['id']}}">
                                                        <i class="mdi mdi-wallet"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal-{{$driver['id']}}">
                                                        <i class="mdi mdi-delete"></i>
                                                    </button>
                                                    <form action="{{route('admin.drivers.destroy', $driver['id'])}}" method="POST" id="delete-form-{{$driver['id']}}" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                    <!-- form to update driver state  -->
                                                    <form action="{{route('admin.drivers.update-state', $driver['id'])}}" method="POST" id="update-state-{{$driver['id']}}" style="display: none;">
                                                        @csrf
                                                        @method('PUT')
                                                    </form>
                                                </td>
                                                <!-- Add or update car for driver Modal -->
                                                @include('admin.drivers.manage-car')
                                                <!-- End add or update car for driver Modal -->

                                                <!-- add or update wallet to driver modal -->
                                                @include('admin.drivers.manage-wallet')
                                                <!-- End add wallet to driver modal -->

                                                <!-- Delete Modal -->
                                                @include( 'admin.drivers.delete-modal')
                                                <!-- End Delete Modal -->

                                                <!-- show Modal -->
                                                @include( 'admin.drivers.show-modal')
                                                <!-- End show Modal -->
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

