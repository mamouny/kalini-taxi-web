@extends('admin.layout.admin-layout')
@section('title', 'Tableau de bord | Kalini')
@section('content')
    <!-- Start Content-->
    <div class="container-fluid">
        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <form class="d-flex">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-light" id="dash-daterange">
                                <span class="input-group-text bg-warning border-warning text-white">
                                                    <i class="mdi mdi-calendar-range font-13"></i>
                                                </span>
                            </div>
                            <a href="javascript: void(0);" class="btn btn-warning ms-2 text-white">
                                <i class="mdi mdi-autorenew"></i>
                            </a>
                            <a href="javascript: void(0);" class="btn btn-warning ms-1 text-white">
                                <i class="mdi mdi-filter-variant"></i>
                            </a>
                        </form>
                    </div>
                    <h4 class="page-title">{{trans('traduction.dashboard')}}</h4>
                </div>
            </div>
        </div>
        <!-- end page title -->

        <div class="row">
           <div class="col-md-4">
               <div class="card widget-flat">
                   <div class="card-body">
                       <div class="float-end">
                           <i class="mdi mdi-account-multiple widget-icon"></i>
                       </div>
                       <h5 class="fw-semibold mt-0 d-flex align-items-center gap-1">
                            <i class="mdi mdi-car fs-3 text-warning"></i> {{trans('traduction.courses')}}
                       </h5>
                       <h3 class="mt-3 mb-3">{{$coursesCount}}</h3>
                   </div> <!-- end card-body-->
               </div>
           </div>
            <div class="col-md-4">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-account-multiple widget-icon"></i>
                        </div>
                        <h5 class="fw-semibold mt-0 d-flex align-items-center gap-1">
                            <i class="mdi mdi-account-multiple fs-3 text-warning"></i>  {{trans('traduction.drivers')}}
                        </h5>
                        <h3 class="mt-3 mb-3">{{$driversCount}}</h3>
                    </div> <!-- end card-body-->
                </div>
            </div>
            <div class="col-md-4">
                <div class="card widget-flat">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="mdi mdi-account-multiple widget-icon"></i>
                        </div>
                        <h5 class="fw-semibold mt-0 d-flex align-items-center gap-1">
                            <i class="mdi mdi-account-multiple fs-3 text-warning"></i> {{trans('traduction.clients')}}
                        </h5>
                        <h3 class="mt-3 mb-3">{{$clientsCount}}</h3>
                    </div> <!-- end card-body-->
                </div>
            </div>
        </div>
        <!-- end row -->
    </div>
    <!-- container -->

@endsection
