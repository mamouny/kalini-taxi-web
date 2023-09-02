<div class="modal fade mt-5" id="createCourseModal" tabindex="-1" aria-labelledby="createCourseModalLabel"
     aria-hidden="true"
     style="z-index: 20">
    <div class="modal-dialog modal-lg" >
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createCourseModalLabel">
                    {{trans('creer_course')}}
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.courses.store')}}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom_client" class="mb-1">{{trans('nom_client')}}</label>
                            <input type="text" class="form-control @error('nom_client') is-invalid @enderror"
                                   id="nom_client" name="nom_client" value="{{old('nom_client')}}"
                                   placeholder="Nom du client">
                            @error('nom_client')
                            <div class="invalid-feedback">
                                {{$errors->first('nom_client')}}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tel_client" class="mb-1">{{trans('tel_client')}}</label>
                            <input type="text" class="form-control @error('tel_client') is-invalid @enderror"
                                   id="tel_client" name="tel_client" value="{{old('tel_client')}}"
                                   placeholder="Téléphone du client">
                            @error('tel_client')
                            <div class="invalid-feedback">
                                {{$errors->first('tel_client')}}
                            </div>
                            @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="type_course_id" class="mb-2">Type de course</label>
                            <select class="form-control" name="type_course_id" id="type_course_id">
                                <option>{{trans('selectionnez')}}</option>
                                <option value="1">Economique</option>
                                <option value="2">VIP</option>
                            </select>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label for="type_trajet" class="mb-2">Type de Trajet</label>
                            <select class="form-control" name="type_trajet" id="type_trajet">
                                <option>{{trans('selectionnez')}}</option>
                                <option value="1">Trajet normal</option>
                                <option value="2">Trajet ouvert</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3" id="lieu_depart_id" style="display: none">
                            <label for="lieu_depart" class="mb-1">{{trans('lieu_depart')}}</label>
                            <input type="text" class="form-control @error('lieu_depart') is-invalid @enderror"
                                   id="lieu_depart" name="lieu_depart" value="{{old('lieu_depart')}}"
                                   onclick="searchPlace('lieu_depart','#latitudeLieuDepart','#longitudeLieuDepart',false,1);"
                                   placeholder="Lieu de départ">
                            @error('lieu_depart')
                                <div class="invalid-feedback">
                                    {{$errors->first('lieu_depart')}}
                                </div>
                            @enderror
                            <input type="hidden" name="latitudeLieuDepart" id="latitudeLieuDepart">
                            <input type="hidden" name="longitudeLieuDepart" id="longitudeLieuDepart">
                        </div>
                        <div class="col-md-6 mb-3" id="destination_id" style="display: none">
                            <label for="destination" class="mb-1">{{trans('destination')}}</label>
                            <input type="text" class="form-control @error('destination') is-invalid @enderror"
                                   id="destination" name="destination" value="{{old('destination')}}"
                                   onclick="searchPlace('destination','#latitudeDestination','#longitudeDestination',true, 2);"
                                   placeholder="Destination">
                            @error('destination')
                            <div class="invalid-feedback">
                                {{$errors->first('destination')}}
                            </div>
                            @enderror
                            <input type="hidden" name="latitudeDestination" id="latitudeDestination">
                            <input type="hidden" name="longitudeDestination" id="longitudeDestination">
                        </div>
                    </div>
                    <div id="map" style="width: 100%;height: 500px;display: none" class="mb-1"></div>
                    <div class="my-2 d-none" id="driverInfos">
                        <p id="driver_name">Chauffeur : </p>
                        <p id="driver_phone">Numéro du chauffeur : </p>
                        <p>Véhicule : </p>
                    </div>
                    <button type="submit" class="btn btn-primary" id="searchDriverBtn">
                        <i class="mdi mdi-check-circle"></i> {{trans('fr.creer_course')}}
                        <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        <span class="sr-only d-none"></span>
                    </button>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{trans('Annuler')}}</button>
            </div>
        </div>
    </div>
</div>

