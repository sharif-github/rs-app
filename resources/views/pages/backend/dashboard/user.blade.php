@extends('layouts.app_layouts.backend.backend-master')
@section('content')
  <!-- Begin Page Content -->
                <div class="container-fluid">

                <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">{{ $user_status }} Dashboard</h1>
                    </div>

                    <!-- Content Row -->

                        <!-- From -->
                        <form class="row g-3" action ="#" method="GET" >
                        @csrf
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                                <div class="form-floating mb-3">
                                                     <select class="form-select text-success" id="from_city" name="from_city" aria-label="from_city">
                                                        <option selected value="">Select a city</option>
                                                        @foreach($fetch_city as $city)
                                                            <option  value ="{{ $city->name }}">{{ $city->name }}</option>
                                                        @endforeach
                                                    </select>
                                                     <label for="from_city">Select your starting point</label>
                                                        @error('from_city')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- To -->
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-secondary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="form-floating mb-3">
                                            <select class="form-select text-success" id="to_city" name="to_city" aria-label="to_city">
                                                <option selected value="">Select starting point first</option>
                                                </select>
                                                <label for="to_city">Select your destination</label>
                                                <span id="wait_to_city"></span>
                                                @error('to_city')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                             <!-- Collapsable Card Example -->
                            <div class="card  border-left-success shadow h-100 py-2">
                                <!-- Card Header - Accordion -->
                                <a href="#collapseCardExample" class="d-block card-header py-3" data-toggle="collapse"
                                    role="button" aria-expanded="true" aria-controls="collapseCardExample">
                                    <h4 class="m-0 font-weight-bold text-success">Select your desired vehicle type</h4>
                                </a>
                                <!-- Card Content - Collapse -->
                                <div class="collapse hide" id="collapseCardExample">
                                    <div class="card-body">

                                        <fieldset class="row mb-3">
    <legend class="col-form-label col-sm-2 pt-0">Select any type:</legend>
    <div class="col-sm-10">
    @foreach($fetch_vehicle_type as $vehicle_type)
      <div class="form-check">
        <input class="form-check-input" type="radio" name="vehicle_type" id="vehicle_type" value="{{ $vehicle_type->type }}">
        <label class="form-check-label" for="vehicle_type">
          {{ $vehicle_type->type }}
        </label>
      </div>
      @endforeach
    </div>
  </fieldset>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-12">
                            <button type="button"  class="btn btn-primary btn-icon-split">
                                        <span class="icon text-white-50">
                                            <i class="fa-solid fa-magnifying-glass"></i>
                                        </span>
                                        <span class="text">Search Vehicle</span>
                                    </button>
                        </div>
                        </form>


                </div>
                <!-- /.container-fluid -->



            </div>
            <!-- End of Main Content -->
<script>
       $(document).ready(function(){
        var spinner = '<div class="d-flex align-items-center"><span class="text-success">Please wait</span><div class="spinner-border text-success ms-auto spinner-border-sm" role="status" aria-hidden="true"></div></div>';
        var wait ='<div class="text-white"><i class="fas fa-spinner fa-pulse"></i> Please wait....</div>';
        /** Fetch to city */
        $(document).on('change','#from_city', function (e) {
            e.preventDefault();
            var selected_from_city = $(this).val();
            $.ajax({
                    type:"GET",
                    url:"{{ route('fetch.toCity') }}",
                    data:{'selected_from_city':selected_from_city},
                    dataType: "json",
                    beforeSend:function(){
                        $('#wait_to_city').html(spinner);
                    },
                    success:function(response){
                        $('#wait_to_city').html('');
                        $('#to_city').html('');
                        if(response.fetched_to_cities == 'Select your starting point first'){
                            var dropdownData = $("<option value=''>Select your starting point first</option>");
                            $('#to_city').append(dropdownData);
                        }else{
                             $.each(response.fetched_to_cities,function(key,ddata){
                               var dropdownData = $("<option value='" + ddata.name  + "'>" + ddata.name  +"</option>");
                            $('#to_city').append(dropdownData);
                           });
                        }
                    }
                });
        });
        /** Change to city */
        $(document).on('change','#to_city', function (e) {
            e.preventDefault();
            if( !$('#fruit_name').val() ) {
                $('#collapseCardExample').addClass('collapse show');
            }else{
                $('#collapseCardExample').removeClass('collapse hide');
            }
        });
       });
</script>
@endsection
