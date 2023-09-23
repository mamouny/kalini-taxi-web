<div class="modal fade mt-4" id="walletModal-{{$driver['id']}}" tabindex="-1" role="dialog" aria-labelledby="walletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="walletModalLabel">
                    {{trans('traduction.wallet_driver')}} {{$driver['nom'].' ' .$driver['prenom']}}
                </h5>
                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.drivers.add-wallet', $driver['id'])}}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="amount" class="col-form-label">{{trans('traduction.amount')}}</label>
                        <input type="number" class="form-control" id="amount" name="amount" placeholder="Montant" min="0"
                               value="@if ($driver['wallet']['amount'] != 0){{$driver['wallet']['amount']}}@endif"
                        >
                    </div>
                    <button class="btn btn-primary" type="submit">
                        <i class="mdi mdi-check-circle me-1"></i> {{trans('traduction.add')}}
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{trans('traduction.cancel')}}</button>
            </div>
        </div>
    </div>
</div>
