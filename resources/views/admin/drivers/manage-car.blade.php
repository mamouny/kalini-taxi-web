<div class="modal fade mt-4" id="carModal-{{$driver['id']}}" tabindex="-1" role="dialog" aria-labelledby="carModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="carModalLabel">
                    @if (isset($driver['car']) && !empty($driver['car']['immatriculation']) && !empty($driver['car']['type_car']) && !empty($driver['car']['type_course']))
                        {{trans('traduction.edit_car_driver')}} {{$driver['nom'].' ' .$driver['prenom']}}
                    @else
                        {{trans('traduction.add_car_driver')}} {{$driver['nom'].' ' .$driver['prenom']}}
                    @endif
                </h5>
                <button type="button" class="close border-0" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li class="py-1">{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                <form action="{{ isset($driver['car']) && !empty($driver['car']['immatriculation']) && !empty($driver['car']['type_car']) && !empty($driver['car']['type_course']) ? route('admin.drivers.update-car', $driver['id']) : route('admin.drivers.store-car', $driver['id']) }}" method="POST"
                    enctype="multipart/form-data"
                >
                    @csrf
                    @if (isset($driver['car']) && !empty($driver['car']['immatriculation']) && !empty($driver['car']['type_car']) && !empty($driver['car']['type_course']))
                        @method('PUT')
                    @endif
                    <div class="mb-3">
                        <label for="immatriculation" class="col-form-label">{{trans('traduction.car_immatriculation')}}</label>
                        <input type="text" class="form-control" id="immatriculation" name="immatriculation" placeholder="Immatriculation" value="{{ $driver['car']['immatriculation'] ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label for="type_course" class="col-form-label">{{trans('traduction.type_car')}}</label>
                        <select class="form-control" id="type_course" name="type_course">
                            <option value="1" {{ isset($driver['car']['type_course']) && $driver['car']['type_course'] == 1 ? 'selected' : '' }}>{{trans('traduction.economic')}}</option>
                            <option value="2" {{ isset($driver['car']['type_course']) && $driver['car']['type_course'] == 2 ? 'selected' : '' }}>{{trans('traduction.vip')}}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="type_car" class="col-form-label">{{trans('traduction.type_car')}}</label>
                        <select class="form-control" id="type_car" name="type_car">
                            <option value="1" {{ isset($driver['car']['type_car']) && $driver['car']['type_car'] == 1 ? 'selected' : '' }}>{{trans('traduction.4x4')}}</option>
                            <option value="2" {{ isset($driver['car']['type_car']) && $driver['car']['type_car'] == 2 ? 'selected' : '' }}>{{trans('traduction.small_car')}}</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="assurance" class="mb-2">{{trans('traduction.assurance')}}</label>
                        <input type="file" name="assurance" class="form-control" id="assurance">
                    </div>
                    <div class="mb-3">
                        <label for="vignette" class="mb-2">{{trans('traduction.vignette')}}</label>
                        <input type="file" name="vignette" class="form-control" id="vignette">
                    </div>
                    <div class="mb-3">
                        <label for="carte_grise" class="mb-2">{{trans('traduction.carte_grise')}}</label>
                        <input type="file" name="carte_grise" class="form-control" id="carte_grise">
                    </div>
                    <button class="btn btn-primary" type="submit">
                        <i class="mdi mdi-plus-circle me-1"></i>
                        @if (isset($driver['car']) && !empty($driver['car']['immatriculation'])) {{trans('traduction.edit')}} @else {{trans('traduction.add')}} @endif
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{trans('traduction.cancel')}}</button>
            </div>
        </div>
    </div>
</div>

