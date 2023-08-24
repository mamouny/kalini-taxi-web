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
                        <form action="{{route('admin.drivers.store')}}" method="POST">
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
                                    <option value="">Selectionnez</option>
                                    <option value="1">initié</option>
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
                                    <option value="">Selectionnez</option>
                                    <option value="1">Oui</option>
                                    <option value="2">Non</option>
                                </select>
                                @error('etat_disponibilite')
                                    <div class="invalid-feedback">
                                        {{$errors->first('etat_disponibilite')}}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-plus-circle"></i> Ajouter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection