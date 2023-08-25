@extends('admin.layout.admin-layout')
@section('title','Kalini | Editer un chauffeur')
@section('content')
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
                                    <option value="">Selectionnez</option>
                                    <option value="1" @if($driver['etat_chauffeur_id'] == 1) selected @endif>initié</option>
                                    <option value="2" @if($driver['etat_chauffeur_id'] == 2) selected @endif>Validé</option>
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
                                    <option value="1" @if($driver['etat_disponibilite'] == 1) selected @endif>Oui</option>
                                    <option value="2" @if($driver['etat_disponibilite'] == 2) selected @endif>Non</option>
                                </select>
                                @error('etat_disponibilite')
                                <div class="invalid-feedback">
                                    {{$errors->first('etat_disponibilite')}}
                                </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-pencil-circle"></i> Modifier
                            </button>
                        </form>
                    </div>
                    <div class="card-footer">
                        <button class="btn btn-info">
                        <i class="mdi mdi-form-textbox-password"></i>
                            Réinitialisé le mot de passe
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
