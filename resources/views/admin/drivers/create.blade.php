@extends('admin.layout.admin-layout')
@section('title','Kalini | Ajouter un chauffeur')
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
                            <li class="breadcrumb-item active">Ajouter un chauffeur</li>
                        </ol>
                    </div>
                    <h4 class="page-title">Ajouter un chauffeur</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>Ajouter un chauffeur</h4>
                        <a href="{{route('admin.drivers')}}">
                            <button type="button" class="btn btn-success waves-effect waves-light">
                                <i class="mdi mdi-arrow-left"></i> Retour
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
                        <form action="{{route('admin.drivers.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="nom" class="mb-1">Nom</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{old('nom')}}" placeholder="Nom">
                            @error('nom')
                                <div class="invalid-feedback">
                                    {{$errors->first('nom')}}
                                </div>
                            @enderror
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="mb-1">Prénom</label>
                                <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{old('prenom')}}" placeholder="Prénom">
                                @error('prenom')
                                    <div class="invalid-feedback">
                                        {{$errors->first('prenom')}}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="tel" class="mb-1">Téléphone</label>
                                <input type="text" class="form-control @error('tel') is-invalid @enderror" id="tel" name="tel" value="{{old('tel')}}" placeholder="Téléphone">
                                @error('tel')
                                    <div class="invalid-feedback">
                                        {{$errors->first('tel')}}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="mb-1">Mot de passe</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Mot de passe">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{$errors->first('password')}}
                                    </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="mb-1">Confirmation du mot de passe</label>
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Confirmez le mot de passe">
                                @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{$errors->first('password_confirmation')}}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="etat_chauffeur_id" class="form-label @error('etat_chauffeur_id') is-invalid @enderror">Etat du chauffeur</label>
                                <select class="form-select" id="etat_chauffeur_id" name="etat_chauffeur_id">
                                    <option value="1" selected>initié</option>
                                    <option value="2">Validé</option>
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
                                    <option value="1">Oui</option>
                                    <option value="0" selected>Non</option>
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
                                <i class="mdi mdi-plus-circle"></i> Ajouter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
