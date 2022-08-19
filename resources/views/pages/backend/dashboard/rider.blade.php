@extends('layouts.app_layouts.backend.backend-master')
@section('content')
  <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">{{ $user_status }} Dashboard</h1>
                    </div>

                    <div class="row">
                        <div class="col-xl-8 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                                <thead>
                                                <tr>
                                                    <th scope="col">Vehicle Image</th>
                                                    <th scope="col">Vehcle Model</th>
                                                    <th scope="col">Plate No</th>
                                                    <th scope="col">Booked By</th>
                                                    <th scope="col">User Contact</th>
                                                    <th scope="col">Journey Date</th>
                                                    <th scope="col">Seat Booked</th>
                                                    <th scope="col">Remaining Seat</th>
                                                    <th scope="col">Travel Route</th>
                                                    <th scope="col">Fare</th>
                                                    <th scope="col">Status</th>
                                                    <th scope="col">Action</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-4 col-md-6 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <div class="pb-2">
                                        <h4 class="text-title text-success">Trip Registration </h4>

                                        <form class="row g-3">
                                    <div class="col-md-6">
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
                                            <div class="col-md-6">
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
                                            <div class="col-12">
                                                <div class="form-floating mb-3">
                                                    <select class="form-select text-success" id="plate" name="plate" aria-label="Vehicle Number">
                                                        <option selected value="">Select a vehicle number</option>
                                                        @forelse($vehicle_details as $vehicle)
                                                        <option  value ="{{ $vehicle->plate }}">{{ $vehicle->plate }}</option>
                                                        @empty
                                                        <option selected value="">No vehicle found</option>
                                                        @endforelse
                                                        </select>
                                                        <label for="plate">Vehicle Number</label>
                                                        @error('plate')
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                        </div>
                                            </div>

                                            <div class="col">
                                        <input type="number" class="form-control" id="year" name="year" placeholder="Year" required>
                                        <span class="text-danger year_error"></span>
                                        <small id="yearhelper" class="form-text text-muted">Type year like this: 1997</small>
                                    </div>
                                    <div class="col">
                                        <input type="number" class="form-control" id="month" name="month" placeholder="Month" required>
                                        <span class="text-danger month_error"></span>
                                        <small id="yearhelper" class="form-text text-muted">Type month like this: 04</small>
                                    </div>
                                    <div class="col">
                                        <input type="number" class="form-control" id="date" name="date" placeholder="Date" required>
                                        <span class="text-danger date_error"></span>
                                        <small id="yearhelper" class="form-text text-muted">Type date like this: 16</small>
                                    </div>
                                            <div class="col-12">
                                                <input type="number" class="form-control" id="date" name="date" placeholder="Per seat fare" required>
                                            </div>
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-success">Register</button>
                                            </div>
                                            </form>

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>



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

});
</script>
@endsection
