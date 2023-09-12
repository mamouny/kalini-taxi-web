@php
    use App\Http\Enums\DriverStateEnum;
    use App\Http\Enums\DriverDisponibilityEnum;
    use App\Models\DriverDocument;

    $driverPermisPhoto = DriverDocument::query()->where("driver_id_firebase",$driver["user_id"])->first()->driver_permis_photo;
@endphp

<div class="modal fade mt-4" id="showModalDriver-{{$driver['id']}}" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showModalLabel">
                  Les infos du chauffeur {{ $driver['prenom'] ." ". $driver['nom']}}
                </h5>
                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label for="nom" class="mb-1">Nom</label>
                        <input type="text" class="form-control" id="nom" name="nom" value="{{$driver['nom']}}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="prenom" class="mb-1">Prénom</label>
                        <input type="text" class="form-control" id="prenom" name="prenom" value="{{$driver['prenom']}}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="tel" class="mb-1">Numéro de téléphone</label>
                        <input type="text" class="form-control" id="tel" name="tel" value="{{$driver['tel']}}" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="etat_chauffeur_id" class="form-label">Etat du chauffeur</label>
                        <select class="form-select" id="etat_chauffeur_id" name="etat_chauffeur_id" disabled>
                            <option value="1" @if($driver['etat_chauffeur_id'] == DriverStateEnum::INITIAL->value) selected @endif>initié</option>
                            <option value="2" @if($driver['etat_chauffeur_id'] == DriverStateEnum::VALIDATED->value) selected @endif>Vérifié</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="etat_disponibilite" class="form-label">Etat de disponibilité</label>
                        <select class="form-select" id="etat_disponibilite" name="etat_disponibilite" disabled>
                            <option value="1" @if($driver['etat_disponibilite'] == DriverDisponibilityEnum::AVAILABLE->value) selected @endif>Oui</option>
                            <option value="0" @if($driver['etat_disponibilite'] == DriverDisponibilityEnum::UNAVAILABLE->value) selected @endif>Non</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Permis de conduire</label>
                        <div class="h-50 mt-2">
                            <img src="{{asset("uploads/drivers/documents/".$driverPermisPhoto)}}" class="img-fluid" alt="permis de conduire"/>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

