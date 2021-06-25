<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.layout.meta')
    <link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
</head>

<body data-spy="scroll" data-target="#navSection" data-offset="100">
    @include('admin.layout.header')

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">
        <div class="overlay"></div>
        <div class="search-overlay"></div>
        @include('admin.layout.sidebar')

        <!--  BEGIN CONTENT AREA  -->
        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                <div class="row layout-top-spacing">
                    <div class="col-lg-12 col-12 layout-spacing">
                        <div class="statbox widget box box-shadow">
                            <div class="widget-header">
                                <div class="row">
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <h4>Add {{ $control }} </h4>
                                        @if(!$errors->isEmpty())
              @foreach ($errors->all(':message') as $input_error)
              <div class="alert alert-warning alert-dismissible fade show">{{ $input_error }}
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times" aria-hidden="true"></i></span></button></div>
              @endforeach 
              @endif
              @if(Session::get("msg")!='')
                  <div class="alert alert-{{ Session::get("cls") }} mb-4" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span> <i class="fa fa-times" aria-hidden="true"></i></span></button>
                    {{ Session::get("msg") }} </div>
                  @endif 
                                    </div>
                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <form method="post" action="{{ route('submit-driver-add') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group required">
                                                <label for="name">Full Name *</label>
                                                <input type="text" name="fullname" class="form-control" id="fullname"
                                                    placeholder="Enter Full Name" value="" maxlength="80" minlength="2"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group required">
                                                <label for="name">PAN Number (optional)</label>
                                                <input type="text" name="pan_card" class="form-control" id="pan_card"
                                                    placeholder="Enter PAN No" value="" maxlength="10" minlength="10">
                                            </div>
                                        </div>
                                        <!--div class="col-sm-4">
                    <div class="form-group required">
                      <label for="name">Vehicle Number *</label>
                      <input type="text" name="vehicle_number" class="form-control" id="vehicle_number" placeholder="Enter Vehicle Number" value="" required>
                    </div>
                  </div-->
                                        <div class="col-sm-4">
                                            <div class="form-group required">
                                                <label for="name">Email (optional)</label>
                                                <input type="email" name="email" class="form-control" id="email"
                                                    placeholder="Enter Email Address" value="" maxlength="80">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group required">
                                                <label for="name">Mobile Number *</label>
                                                <input type="text" name="mobile" class="form-control" id="mobile"
                                                    placeholder="Enter Mobile Number" value="" required maxlength="10"
                                                    minlength="10">
                                            </div>
                                        </div>
                                        <!--div class="col-sm-4">
                    <div class="form-group required">
                      <label for="password">Password *</label>
                      <span id="e2xf5c4rf7hyn" class="badge badge-primary float-right"> Generate Random Password </span>
                      <input type="text" name="password" maxlength="25" class="form-control showcls24mec" id="password" placeholder="Enter Password" value="" required >
                    </div>
                  </div-->
                                        <div class="col-sm-12">
                                            <div class="form-group required">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="status0" name="status"
                                                        class="custom-control-input" checked>
                                                    <label class="custom-control-label" for="status0">Active</label>
                                                </div>
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="status1" name="status"
                                                        class="custom-control-input">
                                                    <label class="custom-control-label" for="status1">Deactive</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group required">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        name="is_salary_based" id="is_salary_based" value="1">
                                                    <label class="custom-control-label" for="is_salary_based">Check This
                                                        to Make This Driver Salary Based.</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2" id="salary_amount_divrowid" style="display: none;">
                                            <div class="form-group required">
                                                <label for="name">Salary Amount &#x20B9; *</label>
                                                <input type="text" name="salary_amount"
                                                    class="form-control allownumber showcls24mec" id="salary_amount"
                                                    placeholder="Salary Amount" value="0"
                                                    min="100" required>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h6> {{ $control }} Address</h6>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-2">
                                            <div class="form-group required">
                                                <label for="name">Pincode (optional)</label>
                                                <input type="text" name="pincode" class="form-control allownumber"
                                                    id="pincode1" placeholder="Pincode" value="" maxlength="6"
                                                    minlength="6">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group required">
                                                <label for="name">Country *</label>
                                                <input type="text" name="country" class="form-control" id="country1"
                                                    placeholder="Country" value="India" required maxlength="30">
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group required">
                                                <label for="name">State *</label>
                                                <input type="text" name="state" class="form-control" id="state1"
                                                    placeholder="State" value="" required maxlength="50">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group required">
                                                <label for="name">City *</label>
                                                <input type="text" name="city" class="form-control" id="city1"
                                                    placeholder="City" value="" required maxlength="50">
                                            </div>
                                        </div>

                                        <div class="col-sm-12">
                                            <div class="form-group  required">
                                                <label for="description">Address (optional)</label>
                                                <textarea name="address" class="form-control" rows="2" id="address1"
                                                    placeholder="Enter Address" maxlength="250"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h6> {{ $control }} Document <span id="dxtb45dcS5g7b"
                                            class="badge badge-success admid-select-color"> Add Other Document</span>
                                    </h6>
                                    <p>Plesae Do not Upload a single File more than 1 MB</p>
                                    <br>
                                    <div id="driver-div-document-id">
                                        <div class="row" id="append-div-id">
                                            <div class="col-sm-12">
                                                <div class="form-group required">
                                                    <label for="name">Profile Pic (optional)</label>
                                                    <input type="file" name="profile_pic" class="form-control"
                                                        id="profile_pic"
                                                        accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp">
                                                </div>
                                            </div>
                                            <!--------file add---------->

                                            <div class="col-sm-12">
                                                <div class="form-group required">
                                                    <label>Aadhar Card (optional)</label>
                                                    <input
                                                        accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp,application/pdf"
                                                        multiple type="file" name="aadhar_card_file[]"
                                                        class="form-control" id="aadhar_card_file">
                                                </div>
                                            </div>

                                             <div class="col-sm-12">
                                                <div class="form-group required">
                                                    <label>Par Card (optional)</label>
                                                    <input
                                                        accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp,application/pdf"
                                                        multiple type="file" name="pan_card_file[]"
                                                        class="form-control" id="pan_card_file">
                                                </div>
                                            </div>


                                            <div class="col-sm-9">
                                                <div class="form-group required">
                                                    <label>License (optional)</label>
                                                    <input
                                                        accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp,application/pdf"
                                                        multiple type="file" name="license_file[]" class="form-control"
                                                        id="license_file">
                                                </div>
                                            </div>
                                            <div class="col-sm-3">
                                                <div class="form-group required">
                                                    <label for="name">Expiry Date </label>
                                                    <input type="text" name="license_expiry"
                                                        class="form-control datepicker" id="license_expiry"
                                                        value="{{ date('Y-m-d', strtotime(' + 375 days')) }}">
                                                </div>
                                            </div>
                                       
                                            <!--------file add---------->
                                        </div>
                                    </div>
                                    <hr style="width:100%">
                                    <button type="submit"
                                        class="btn btn-info float-right admin-button-add-vnew">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin.layout.footer')
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->

    @include('admin.layout.js')
    <script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <!----Add Custom Js ----start----->
    <script type="text/javascript">
    $(function() {
        $(".datepicker").datepicker({
            dateFormat: "yy-mm-dd"
        });
    });

    $('body').on('click', '#e2xf5c4rf7hyn', function() {
        var password = makeid(randomIntFromInterval(5, 12));
        $('#password').val(password);
    });

    $("#is_salary_based").change(function() {
        if (this.checked) {
            $("#salary_amount_divrowid").show();
        } else {
            $("#salary_amount_divrowid").hide();
        }
    });

    $('body').on('click', '#dxtb45dcS5g7b', function() {
        ehtml = '';
        var r_val = makeid(9, 1);
        ehtml += '<div class="col-sm-4" id="m' + r_val +
            '"> <div class="form-group required"> <label>Document Name </label> <input type="text" name="justnameO[]" class="form-control"  id="jnO' +
            r_val + '"  value="" required> </div> </div> <div class="col-sm-6" id="m1' + r_val +
            '"> <div class="form-group required"> <label>Other Document <span onclick="removethisid(' + r_val +
            ')" class="badge badge-danger "> Delete This</span></label> <input type="file" name="justimgO[]" class="form-control" accept="image/gif,image/jpg,image/jpeg,image/png,image/bmp,application/pdf" id="dftO' +
            r_val + '" required> </div> </div>';
        $('#append-div-id').append(ehtml);


    });

    function removethisid(id) {
        x = confirm("do you want to delete this row, are you sure ?");
        if (x == true) {
            $("#m" + id).remove();
            $("#m1" + id).remove();
        }
    }
    </script>
    <!----Add Custom Js --end------->
    @include('admin.layout.crudhelper')
</body>

</html>