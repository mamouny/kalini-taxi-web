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
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li class="mb-0">{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <form action="{{route('admin.drivers.store')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-3">
                                <label for="nom" class="mb-1">Nom</label>
                                <input type="text" class="form-control" id="nom" name="nom" value="{{old('nom')}}" placeholder="Nom">
                            </div>
                            <div class="mb-3">
                                <label for="prenom" class="mb-1">Prénom</label>
                                <input type="text" class="form-control" id="prenom" name="prenom" value="{{old('prenom')}}" placeholder="Prénom">
                            </div>
                            <div class="mb-3">
                                <label for="tel" class="mb-1">Téléphone</label>
                                <input type="text" class="form-control" id="tel" name="tel" value="{{old('tel')}}" placeholder="Téléphone">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="mb-1">Mot de passe</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Mot de passe">
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
                                <label for="etat_chauffeur_id" class="form-label">Etat du chauffeur</label>
                                <select class="form-select" id="etat_chauffeur_id" name="etat_chauffeur_id">
                                    <option value="1" selected>initié</option>
                                    <option value="2">Validé</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="etat_disponibilite" class="form-label">Etat de disponibilité</label>
                                <select class="form-select" id="etat_disponibilite" name="etat_disponibilite">
                                    <option value="1">Oui</option>
                                    <option value="0" selected>Non</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="permis_conduire" class="mb-1">Permis de conduire</label>
                                <input type="file" class="form-control" id="permis_conduire" name="permis_conduire">
                            </div>
                            <button type="submit" class="btn btn-success" id="btnCreateDriver">
                                <i class="mdi mdi-plus-circle"></i> Ajouter
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script>
        const nom = document.getElementById('nom');
        const prenom = document.getElementById('prenom');
        const tel = document.getElementById('tel');
        const password = document.getElementById('password');
        const password_confirmation = document.getElementById('password_confirmation');
        const permis_conduire = document.getElementById('permis_conduire');

        nom.addEventListener('input', function() {
            if (!/^[a-zA-Z ]+$/.test(nom.value)) {
                nom.classList.add('is-invalid');
                nom.classList.remove('is-valid');

               if(!$('#nom').parent().find('.invalid-feedback').length){
                    $('#nom').parent().append('<span class="invalid-feedback">Le nom ne doit pas contenir des caractères spéciaux ou des chiffres</span>');
                }
            } else {
                nom.classList.remove('is-invalid');
                nom.classList.add('is-valid');
            }
        });

        prenom.addEventListener('input', function() {
            if (!/^[a-zA-Z ]+$/.test(prenom.value)) {
                prenom.classList.add('is-invalid');
                prenom.classList.remove('is-valid');
                if (!$('#prenom').parent().find('.invalid-feedback').length) {
                    $('#prenom').parent().append('<span class="invalid-feedback">Le prénom ne doit pas contenir des caractères spéciaux ou des chiffres</span>');
                }
            } else {
                prenom.classList.remove('is-invalid');
                prenom.classList.add('is-valid');
            }
        });

        tel.addEventListener('input', function() {
            const cleanedValue = tel.value.replace(/\D/g, '');

            if (/^[2-4]\d{7}$/.test(cleanedValue) && cleanedValue.length === 8 && cleanedValue[0] !== '0') {
                tel.classList.remove('is-invalid');
                tel.classList.add('is-valid');
            } else {
                tel.classList.add('is-invalid');
                tel.classList.remove('is-valid');
                if (!$('#tel').parent().find('.invalid-feedback').length) {
                    $('#tel').parent().append('<span class="invalid-feedback">Le numéro de téléphone doit être composé de 8 chiffres et commencer par 2, 3 ou 4</span>');
                }
            }
        });

        password.addEventListener('input', function() {
            const passwordValue = password.value;

            if (!/^\d{4}$/.test(passwordValue) || isSequential(passwordValue)) {
                password.classList.add('is-invalid');
                password.classList.remove('is-valid');

                if (!$('#password').parent().find('.invalid-feedback').length) {
                    $('#password').parent().append('<span class="invalid-feedback">Le mot de passe doit être composé de 4 chiffres non consécutifs</span>');
                }

                if (passwordValue.length > 4) {
                    password.value = '';
                }
            } else {
                password.classList.remove('is-invalid');
                password.classList.add('is-valid');
            }
        });

        function isSequential(value) {
            for (let i = 0; i < value.length - 1; i++) {
                if (parseInt(value[i]) + 1 !== parseInt(value[i + 1])) {
                    return false;
                }
            }
            return true;
        }

        password_confirmation.addEventListener('input', function() {
            if (password_confirmation.value !== password.value) {
                password_confirmation.classList.add('is-invalid');
                password_confirmation.classList.remove('is-valid');
                if (!$('#password_confirmation').parent().find('.invalid-feedback').length) {
                    $('#password_confirmation').parent().append('<span class="invalid-feedback">Les mots de passe ne correspondent pas</span>');
                }
            } else {
                password_confirmation.classList.remove('is-invalid');
                password_confirmation.classList.add('is-valid');
            }
        });

        permis_conduire.addEventListener('input', function() {
            const uploadedFile = permis_conduire.files[0];

            if (uploadedFile) {
                const validImageTypes = ['image/jpeg', 'image/png', 'image/gif'];

                if (validImageTypes.includes(uploadedFile.type)) {
                    permis_conduire.classList.remove('is-invalid');
                    permis_conduire.classList.add('is-valid');
                } else {
                    permis_conduire.classList.add('is-invalid');
                    permis_conduire.classList.remove('is-valid');
                }
            } else {
                permis_conduire.classList.add('is-invalid');
                permis_conduire.classList.remove('is-valid');
            }
        });

        //document.getElementById('btnCreateDriver').disabled = !(nom.classList.contains('is-valid') && prenom.classList.contains('is-valid') && tel.classList.contains('is-valid') && password.classList.contains('is-valid') && password_confirmation.classList.contains('is-valid') && permis_conduire.classList.contains('is-valid'));
    </script>
@endsection
