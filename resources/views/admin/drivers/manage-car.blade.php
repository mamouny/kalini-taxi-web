<div class="modal fade mt-4" id="carModal-{{$driver['id']}}" tabindex="-1" role="dialog" aria-labelledby="carModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="carModalLabel">
                    @if (isset($driver['car']) && !empty($driver['car']['immatriculation']) && !empty($driver['car']['type_car']) && !empty($driver['car']['type_course']))
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
                <form action="{{ isset($driver['car']) && !empty($driver['car']['immatriculation']) && !empty($driver['car']['type_car']) && !empty($driver['car']['type_course']) ? route('admin.drivers.update-car', $driver['id']) : route('admin.drivers.store-car', $driver['id']) }}" method="POST">
                    @csrf
                    @if (isset($driver['car']) && !empty($driver['car']['immatriculation']) && !empty($driver['car']['type_car']) && !empty($driver['car']['type_course']))
                        @method('PUT')
                    @endif
                    <div class="mb-3">
                        <label for="immatriculation" class="col-form-label">Immatriculation</label>
                        <input type="text" class="form-control" id="immatriculation" name="immatriculation" placeholder="Immatriculation" value="{{ $driver['car']['immatriculation'] ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="type_course" class="col-form-label">Type de course</label>
                        <select class="form-control" id="type_course" name="type_course">
                            <option value="0">Sélectionner un type de course</option>
                            <option value="1" {{ isset($driver['car']['type_course']) && $driver['car']['type_course'] == 1 ? 'selected' : '' }}>Economic</option>
                            <option value="2" {{ isset($driver['car']['type_course']) && $driver['car']['type_course'] == 2 ? 'selected' : '' }}>VIP</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="type_car" class="col-form-label">Type de voiture</label>
                        <select class="form-control" id="type_car" name="type_car">
                            <option value="0">Sélectionner un type de voiture</option>
                            <option value="1" {{ isset($driver['car']['type_car']) && $driver['car']['type_car'] == 1 ? 'selected' : '' }}>4x4</option>
                            <option value="2" {{ isset($driver['car']['type_car']) && $driver['car']['type_car'] == 2 ? 'selected' : '' }}>Petite voiture</option>
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
