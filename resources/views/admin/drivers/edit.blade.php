@extends('admin.layout.admin-layout')
@section('title','Kalini | Editer un chauffeur')
@section('content')
    @php
        use App\Http\Enums\DriverStateEnum;
        use App\Http\Enums\DriverDisponibilityEnum;
    @endphp
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Editer un chauffeur</h4>
                        <a href="{{route('admin.drivers')}}">
                            <button class="btn btn-success">
                                <i class="mdi mdi-arrow-left-circle"></i> Retour
                            </button>
                        </a>
                    </div>
                    <div class="card-body">
                        @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-check-all mr-2"></i>{{Session::get('success')}}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @elseif(Session::has('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-block-helper mr-2"></i>{{Session::get('error')}}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @elseif(Session::has('warning'))
                            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-alert-outline mr-2"></i>{{Session::get('warning')}}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @elseif(Session::has('info'))
                            <div class="alert alert-info alert-dismissible fade show" role="alert">
                                <i class="mdi mdi-alert-circle mr-2"></i>{{Session::get('info')}}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        <form action="{{route('admin.drivers.update', $id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="nom" class="mb-1">Nom</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{$driver['nom']}}" placeholder="Nom">
                                @error('nom')
                                <div class="invalid-feedback">
                                    {{$errors->first('nom')}}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="mb-1">Prénom</label>
                                <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{$driver['prenom']}}" placeholder="Prénom">
                                @error('prenom')
                                <div class="invalid-feedback">
                                    {{$errors->first('prenom')}}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="tel" class="mb-1">Téléphone</label>
                                <input type="text" class="form-control @error('tel') is-invalid @enderror" id="tel" name="tel" value="{{$driver['tel']}}" placeholder="Téléphone">
                                @error('tel')
                                <div class="invalid-feedback">
                                    {{$errors->first('tel')}}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="etat_chauffeur_id" class="form-label @error('etat_chauffeur_id') is-invalid @enderror">Etat du chauffeur</label>
                                <select class="form-select" id="etat_chauffeur_id" name="etat_chauffeur_id">
                                    <option value="1" @if($driver['etat_chauffeur_id'] == DriverStateEnum::INITIAL->value) selected @endif>initié</option>
                                    <option value="2" @if($driver['etat_chauffeur_id'] == DriverStateEnum::VALIDATED->value) selected @endif>Vérifié</option>
                                </select>
                                @error('etat_chauffeur_id')
                                <div class="invalid-feedback">
                                    {{$errors->first('etat_chauffeur_id')}}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="etat_disponibilite" class="form-label @error('etat_disponibilite') is-invalid @enderror">Etat de disponibilité</label>
                                <select class="form-select" id="etat_disponibilite" name="etat_disponibilite">
                                    <option value="">Selectionnez</option>
                                    <option value="1" @if($driver['etat_disponibilite'] == DriverDisponibilityEnum::AVAILABLE->value) selected @endif>Oui</option>
                                    <option value="0" @if($driver['etat_disponibilite'] == DriverDisponibilityEnum::UNAVAILABLE->value) selected @endif>Non</option>
                                </select>
                                @error('etat_disponibilite')
                                <div class="invalid-feedback">
                                    {{$errors->first('etat_disponibilite')}}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="permis_conduire" class="mb-1">Permis de conduire</label>
                                <input type="file" class="form-control @error('permis_conduire') is-invalid @enderror" id="permis_conduire" name="permis_conduire">
                                @error('permis_conduire')
                                    <div class="invalid-feedback">
                                        {{$errors->first('permis_conduire')}}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success">
                                    <i class="mdi mdi-pencil-circle"></i> Modifier
                            </button>
                        </form>
                    </div>
{{--                    <div class="card-footer">--}}
{{--                        <button class="btn btn-info">--}}
{{--                        <i class="mdi mdi-form-textbox-password"></i>--}}
{{--                            Réinitialisé le mot de passe--}}
{{--                        </button>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
    </div>
@endsection
