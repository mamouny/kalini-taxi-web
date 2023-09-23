<div class="modal fade mt-4" id="deleteModal-{{$driver['id']}}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="deleteModalLabel">
                    {{trans('traduction.delete_driver')}} {{$driver['nom']. ' '. $driver['prenom'] }}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                {{trans('traduction.confirm_delete_driver')}}
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{trans('traduction.cancel')}}
                </button>
                <button type="button" class="btn btn-danger"
                        onclick="event.preventDefault();
                        document.getElementById('delete-form-{{$driver['id']}}').submit();"
                >
                    <i class="mdi mdi-trash-can"></i> {{trans('traduction.delete')}}
                </button>
            </div>
        </div>
    </div>
</div>
