@extends('admin.layout.admin-layout')
@section('title','Kalini | Courses')
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{trans('Kalini')}}</a></li>
                            <li class="breadcrumb-item"><a href="javascript: void(0);">{{trans('Taxi')}}</a></li>
                            <li class="breadcrumb-item active">{{trans('Courses')}}</li>
                        </ol>
                    </div>
                    <h4 class="page-title">{{trans('courses')}}</h4>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4>{{trans('courses')}}</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCourseModal" >
                            <i class="mdi mdi-plus-circle me-1"></i> {{trans('Créer une course')}}
                        </button>
                    </div>
                    <div class="card-body">
                        @include('admin.partials.messages')
                        <div class="tab-content">
                            <div class="tab-pane show active" id="basic-datatable-preview">
                                <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                                    <thead>
                                    <tr>
                                        <th>Client</th>
                                        <th>Chauffeur</th>
                                        <th>Type</th>
                                        <th>Date & Heure</th>
                                        <th>Prix</th>
                                        <th>Etat</th>
                                        <th>Actions</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($courses as $course)
                                        <tr>
                                            <td>{{$course['client']['nom']}}</td>
                                            <td>{{$course['driver']['nom'] }}</td>
                                            <td>{{$course['types_trajet_id'] == 1 ? "Normale" : "Ouverte"}}</td>
                                            <td>{{$course['date_debut']}}</td>
                                            <td>{{$course['price']}} mru</td>
                                            <td>
                                                @if($course['etat_course_id'] == 1)
                                                    <span class="badge bg-primary">Initié</span>
                                                @elseif($course['etat_course_id'] == 2)
                                                    <span class="badge bg-info">Lancé</span>
                                                @elseif($course['etat_course_id'] == 3)
                                                    <span class="badge bg-warning">En route</span>
                                                @elseif($course['etat_course_id'] == 4)
                                                    <span class="badge bg-dark">Passager au bord</span>
                                                @elseif($course['etat_course_id'] == 5)
                                                    <span class="badge bg-success">Terminé</span>
                                                @elseif($course['etat_course_id'] == 6)
                                                    <span class="badge bg-danger">Annulé</span>
                                                @endif
                                            </td>
                                            <td class="d-flex gap-1">
                                                <button class="btn btn-info btn-sm text-white" data-bs-toggle="modal" data-bs-target="#showModal-{{$course['id']}}"
                                                >
                                                    <i class="mdi mdi-eye"></i>
                                                </button>
                                                <form action="{{route('admin.courses.cancel',$course['id'])}}" method="post">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit" class="btn btn-warning btn-sm text-white" onclick="return confirm('Voulez-vous annuler cette course ?')"
                                                            @if($course['etat_course_id'] == 6)  disabled @endif>
                                                        <i class="mdi mdi-cancel"></i>
                                                    </button>
                                                </form>
                                                <form action="{{route('admin.courses.destroy',$course['id'])}}" method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous supprimer cette course ?')">
                                                        <i class="mdi mdi-trash-can-outline"></i>
                                                    </button>
                                                </form>
                                            </td>
                                            @include('admin.courses.show-modal')
                                        </tr>
                                       @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- create course modal -->
            @include('admin.courses.create-modal')
            <!-- show course modal -->

        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_MAPS_API_KEY')}}&libraries=places" async defer></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/geolib@3.3.3/lib/index.js"></script>
    <script type="text/javascript" src="{{asset('assets/js/polylineEncode.js')}}"></script>
    <script src="{{asset('assets/js/script.js')}}"></script>
@endsection
