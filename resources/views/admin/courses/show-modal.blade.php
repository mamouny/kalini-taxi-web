<div class="modal fade mt-4" id="showModal-{{$course['id']}}" tabindex="-1" role="dialog" aria-labelledby="showModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showModalLabel">
                    Les infos de la course
                </h5>
                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="card mb-3">
                    <div class="card-header bg-warning text-white">
                        <h5 >Client Info</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Nom & Prénom :</strong> {{$course['client']['nom']}}</p>
                        <p><strong>Téléphone :</strong> {{$course['client']['tel']}}</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 >Driver Info</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Nom & Prénom :</strong> {{$course['driver']['nom'].' '. $course['driver']['prenom']}}</p>
                        <p class="mb-1"><strong>Téléphone :</strong> {{$course['driver']['tel']}}</p>
                        <p class="mb-1"><strong>Voiture Immatriculation :</strong> {{$course['driver']['car']['immatriculation']}}</p>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h5 >Trajet de la course</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-1"><strong>Lieu de départ :</strong> {{$course['emplacements']['firstPlace']['description']}}</p>
                        <p class="mb-1"><strong>Lieu d'arrivée :</strong> {{$course['emplacements']['secondPlace']['description']}}</p>
                        <p class="mb-1"><strong>Distance :</strong> {{$course['kilometrage']}} km</p>
                        <p class="mb-1"><strong>Prix :</strong> {{$course['price']}} mru</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
