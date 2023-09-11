<div class="modal fade mt-4" id="deleteModal-{{$client['id']}}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteModalLabel">
                    Suppréssion du client {{$client['nom']}}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Etes vous sûr de vouloir supprimer ce client ?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button type="button" class="btn btn-danger"
                        onclick="event.preventDefault();
                        document.getElementById('delete-form-{{$client['id']}}').submit();"
                >
                    <i class="mdi mdi-trash-can"></i> Supprimer
                </button>
            </div>
        </div>
    </div>
</div>
