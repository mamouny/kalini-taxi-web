<div class="modal fade" id="createDriverModal" tabindex="-1" aria-labelledby="createDriverModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createDriverModalLabel">
                    {{trans('creer_chauffeur')}}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
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
                            <option value="2" @if($driver['etat_disponibilite'] == 0) selected @endif>Non</option>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{trans('Annuler')}}</button>
            </div>
        </div>
    </div>
</div>
