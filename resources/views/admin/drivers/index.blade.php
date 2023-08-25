@extends('admin.layout.admin-layout')
@section('title','Kalini | Chauffeurs')
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
                            <li class="breadcrumb-item active">Chauffeurs</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Chauffeurs</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Chauffeurs</h4>
                        <a href="{{route('admin.drivers.create')}}" class="btn btn-primary">
                            <i class="mdi mdi-plus-circle me-1"></i> Ajouter un chauffeur
                        </a>
                    </div>
                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check me-2"></i>
                                {{session('success')}}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @elseif(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-block-helper me-2"></i>
                                {{session('error')}}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @elseif(session('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-alert-outline me-2"></i>
                                {{session('warning')}}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <div class="tab-content">
                            <div class="tab-pane show active" id="basic-datatable-preview">
                                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Téléphone</th>
                                        <th>Etat</th>
                                        <th>Disponibilité</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                      @foreach($drivers as $driver)
                                            <tr>
                                                <td>{{$driver['nom']}}</td>
                                                <td>{{$driver['prenom']}}</td>
                                                <td>{{$driver['tel']}}</td>
                                                <td>
                                                    @if($driver['etat_chauffeur_id'] == 3)
                                                        <span class="badge bg-success">Actif</span>
                                                    @else
                                                        <span class="badge bg-danger">Inactif</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($driver['etat_disponibilite'] == 1)
                                                        <span class="badge bg-success">Disponible</span>
                                                    @else
                                                        <span class="badge bg-danger">Indisponible</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{route('admin.drivers.edit', $driver['id'])}}" class="btn btn-primary btn-sm"><i class="mdi mdi-pencil"></i></a>
                                                    <a href="{{route('admin.drivers.show', $driver['id'])}}" class="btn btn-info btn-sm"><i class="mdi mdi-eye"></i></a>
                                                    <button class="btn btn-warning btn-sm text-white" data-bs-toggle="modal" data-bs-target="#carModal-{{$driver['id']}}"
                                                    >
                                                        <i class="mdi mdi-car"></i>
                                                    </button>
                                                    <button class="btn btn-secondary btn-sm text-white" data-bs-toggle="modal" data-bs-target="#walletModal-{{$driver['id']}}">
                                                        <i class="mdi mdi-wallet"></i>
                                                    </button>
                                                    @if($driver['etat_chauffeur_id'] == 2 && $driver['etat_disponibilite'] == 1)
                                                        <button class="btn btn-warning btn-sm text-white"
                                                        onclick="
                                                            event.preventDefault();
                                                            document.getElementById('update-state-{{$driver['id']}}').submit();"
                                                        >
                                                            <i class="mdi mdi-close"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-success btn-sm"
                                                                onclick="
                                                            event.preventDefault();
                                                            document.getElementById('update-state-{{$driver['id']}}').submit();"
                                                        >
                                                            <i class="mdi mdi-check"></i>
                                                        </button>
                                                    @endif
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
                                                <div class="modal fade" id="carModal-{{$driver['id']}}" tabindex="-1" role="dialog" aria-labelledby="carModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="carModalLabel">
                                                                    @if (isset($driver['car']) && !empty($driver['car']['immatriculation']) && !empty($driver['car']['car_type']) && !empty($driver['car']['course_type']))
                                                                        Modifier le véhicule de {{$driver['nom'].' ' .$driver['prenom']}}
                                                                    @else
                                                                        Ajouter un véhicule au chauffeur {{$driver['nom'].' ' .$driver['prenom']}}
                                                                    @endif
                                                                </h5>
                                                                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="{{ isset($driver['car']) && !empty($driver['car']['immatriculation']) && !empty($driver['car']['car_type']) && !empty($driver['car']['course_type']) ? route('admin.drivers.update-car', $driver['id']) : route('admin.drivers.store-car', $driver['id']) }}" method="POST">
                                                                    @csrf
                                                                    @if (isset($driver['car']) && !empty($driver['car']['immatriculation']) && !empty($driver['car']['car_type']) && !empty($driver['car']['course_type']))
                                                                        @method('PUT')
                                                                    @endif
                                                                    <div class="mb-3">
                                                                        <label for="immatriculation" class="col-form-label">Immatriculation</label>
                                                                        <input type="text" class="form-control" id="immatriculation" name="immatriculation" placeholder="Immatriculation" value="{{ $driver['car']['immatriculation'] ?? '' }}">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="car_type" class="col-form-label">Type de voiture</label>
                                                                        <select class="form-control" id="car_type" name="car_type">
                                                                            <option value="0">Sélectionner un type de voiture</option>
                                                                            <option value="1" {{ isset($driver['car']['car_type']) && $driver['car']['car_type'] == 1 ? 'selected' : '' }}>Economic</option>
                                                                            <option value="2" {{ isset($driver['car']['car_type']) && $driver['car']['car_type'] == 2 ? 'selected' : '' }}>VIP</option>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="course_type" class="col-form-label">Type de course</label>
                                                                        <select class="form-control" id="course_type" name="course_type">
                                                                            <option value="0">Sélectionner un type de course</option>
                                                                            <option value="1" {{ isset($driver['car']['course_type']) && $driver['car']['course_type'] == 1 ? 'selected' : '' }}>Normale</option>
                                                                            <option value="2" {{ isset($driver['car']['course_type']) && $driver['car']['course_type'] == 2 ? 'selected' : '' }}>Ouvert</option>
                                                                        </select>
                                                                    </div>
                                                                    <button class="btn btn-primary" type="submit">
                                                                        <i class="mdi mdi-plus-circle me-1"></i>
                                                                        @if (isset($driver['car']) && !empty($driver['car']['immatriculation'])) Modifier @else Ajouter @endif
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- add or update wallet to driver modal -->
                                                <div class="modal fade" id="walletModal-{{$driver['id']}}" tabindex="-1" role="dialog" aria-labelledby="walletModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="walletModalLabel">
                                                                    @if ($driver['wallet']['amount'] == 0)
                                                                        Ajouter un portefeuille au chauffeur {{$driver['nom'].' ' .$driver['prenom']}}
                                                                    @else
                                                                        Mettre à jour le portefeuille du chauffeur {{$driver['nom'].' ' .$driver['prenom']}}
                                                                    @endif
                                                                </h5>
                                                                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <form action="@if ($driver['wallet']['amount']== 0){{ route('admin.drivers.add-wallet', $driver['id']) }}@else{{ route('admin.drivers.update-wallet', $driver['id']) }}@endif" method="POST">
                                                                    @csrf
                                                                    @if($driver['wallet']['amount'] != 0)
                                                                        @method('PUT')
                                                                    @endif
                                                                    <div class="mb-3">
                                                                        <label for="amount" class="col-form-label">Montant</label>
                                                                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Montant" min="0"
                                                                               value="@if ($driver['wallet']['amount'] != 0){{$driver['wallet']['amount']}}@endif"
                                                                        >
                                                                    </div>
                                                                    <button class="btn btn-primary" type="submit">
                                                                        <i class="mdi mdi-plus-circle me-1"></i> @if ($driver['wallet']['amount'] == 0) Ajouter @else Mettre à jour @endif
                                                                    </button>
                                                                </form>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- End add wallet to driver modal -->

                                                <!-- Delete Modal -->
                                                <div class="modal fade" id="deleteModal-{{$driver['id']}}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h1 class="modal-title fs-5" id="deleteModalLabel">
                                                                    Suppréssion du chauffeur {{$driver['nom']. ' '. $driver['prenom'] }}
                                                                </h1>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                Etes vous sûr de vouloir supprimer ce chauffeur ?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                                                <button type="button" class="btn btn-danger"
                                                                        onclick="event.preventDefault();
                                                                            document.getElementById('delete-form-{{$driver['id']}}').submit();"
                                                                >Supprimer</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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
