@extends('admin.layout.admin-layout')
@section('title',trans('traduction.edit_driver'))
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
                        <h4>{{trans('traduction.edit_driver')}}</h4>
                        <a href="{{route('admin.drivers')}}">
                            <button class="btn btn-success">
                                <i class="mdi mdi-arrow-left-circle"></i> {{trans('traduction.back')}}
                            </button>
                        </a>
                    </div>
                    <div class="card-body">
                        @include('admin.partials.messages')
                        <form action="{{route('admin.drivers.update', $id)}}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="nom" class="mb-1">{{trans('traduction.nom_chauffeur')}}</label>
                                <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{$driver['nom']}}" placeholder="Nom">
                                @error('nom')
                                <div class="invalid-feedback">
                                    {{$errors->first('nom')}}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="mb-1">{{trans('traduction.prenom_chauffeur')}}</label>
                                <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{$driver['prenom']}}" placeholder="Prénom">
                                @error('prenom')
                                <div class="invalid-feedback">
                                    {{$errors->first('prenom')}}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="tel" class="mb-1">{{trans('traduction.tel_chauffeur')}}</label>
                                <input type="text" class="form-control @error('tel') is-invalid @enderror" id="tel" name="tel" value="{{$driver['tel']}}" placeholder="Téléphone">
                                @error('tel')
                                <div class="invalid-feedback">
                                    {{$errors->first('tel')}}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="etat_chauffeur_id" class="form-label @error('etat_chauffeur_id') is-invalid @enderror">{{trans('traduction.etat_chauffeur')}}</label>
                                <select class="form-select" id="etat_chauffeur_id" name="etat_chauffeur_id">
                                    <option value="1" @if($driver['etat_chauffeur_id'] == DriverStateEnum::INITIAL->value) selected @endif>{{trans('traduction.etat_chauffeur_init')}}</option>
                                    <option value="2" @if($driver['etat_chauffeur_id'] == DriverStateEnum::VALIDATED->value) selected @endif>{{trans('traduction.etat_chauffeur_valide')}}</option>
                                </select>
                                @error('etat_chauffeur_id')
                                <div class="invalid-feedback">
                                    {{$errors->first('etat_chauffeur_id')}}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="etat_disponibilite" class="form-label @error('etat_disponibilite') is-invalid @enderror">{{trans('traduction.etat_disponibilite')}}</label>
                                <select class="form-select" id="etat_disponibilite" name="etat_disponibilite">
                                    <option value="1" @if($driver['etat_disponibilite'] == DriverDisponibilityEnum::AVAILABLE->value) selected @endif>{{trans('traduction.oui')}}</option>
                                    <option value="0" @if($driver['etat_disponibilite'] == DriverDisponibilityEnum::UNAVAILABLE->value) selected @endif>{{trans('traduction.non')}}</option>
                                </select>
                                @error('etat_disponibilite')
                                <div class="invalid-feedback">
                                    {{$errors->first('etat_disponibilite')}}
                                </div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="permis_conduire" class="mb-1">{{trans('traduction.permis')}}</label>
                                <input type="file" class="form-control @error('permis_conduire') is-invalid @enderror" id="permis_conduire" name="permis_conduire">
                                @error('permis_conduire')
                                    <div class="invalid-feedback">
                                        {{$errors->first('permis_conduire')}}
                                    </div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-success">
                                    <i class="mdi mdi-pencil-circle"></i> {{trans('traduction.edit')}}
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
