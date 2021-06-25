<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.layout.meta')
    <link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('admin_assets/assets/css/select2.min.css')}}" rel="stylesheet" />

</head>

<body data-spy="scroll" data-target="#navSection" data-offset="100">

    <style>
    td span:first-child {
        margin-bottom: 5px;
    }
    </style>


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
                                        <h4>{{ $control }}</h4>
                                         @if(!$errors->isEmpty())
              @foreach ($errors->all(':message') as $input_error)
              <div class="alert alert-warning alert-dismissible fade show">{{ $input_error }}
                <button type="button" class="close h-100" data-dismiss="alert" aria-label="Close"><span><i class="fa fa-times" aria-hidden="true"></i></span></button></div>
              @endforeach 
              @endif
              @if(Session::get("msg")!='')
                  <div class="alert alert-{{ Session::get("cls") }} mb-4" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span> <i class="fa fa-times" aria-hidden="true"></i></span></button>
                    {{ Session::get("msg") }} </div>
                  @endif 
                                    </div>


                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <div class="table-responsive mb-4 mt-4">
                                    <table id="t1" class="table table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="5%">BD LR Number</th>
                                                <th width="5%">Transporter LR Number</th>
                                                <th width="10%">Customer Details</th>
                                                <th width="20%">Location</th>
                                                <th width="5%">Delivery Charge</th>
                                                <th width="8%">Date</th>
                                                <th width="5%">Status</th>
                                                <th width="5%">Driver</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                                        <thead class="searchnow">
                                            <tr>
                                                <th width="5%" class="searchcls">BD LR Number</th>
                                                <th width="5%" class="searchcls">Transporter LR Number</th>
                                                <th width="10%">Customer Details</th>
                                                <th width="20%">Location</th>
                                                <th width="5%" class="searchcls">Delivery Charge</th>
                                                <th width="8%">Date</th>
                                                <th width="5%">Status</th>
                                                <th width="5%">Driver</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody id="t1tbody">
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="5%">#</th>
                                                <th width="10%">Customer Details</th>
                                                <th width="20%">Location</th>
                                                <th width="5%">Delivery Charge</th>
                                                <th width="8%">Date</th>
                                                <th width="5%">Status</th>
                                                <th width="5%">Driver</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </tfoot>

                                    </table>
                                </div>
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

    <!----assign driver Modal start---->
    <div class="modal fade" id="assignModal" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="exampleModalLabel">Assign This Order to Driver</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <div class="modal-body">
                    <form id="assign_form">
                        @csrf
                        <div class="col-sm-12">
                            <div class="form-group required">
                                <select id="driver_id" name="driver_id" class="form-control" required>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="ovalueid" id="ovalueid" value="0">
                        <input type="hidden" name="dvalueid" id="dvalueid" value="0">
                        <input type="hidden" name="bigdaddylrnumberhid" id="bigdaddylrnumberhid" value="0">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group required">
                                    <label>Bigdaddy LR Number </label>
                                    <p id="BigdaddyLRNumberid"></p>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group required">
                                    <label>Order Date</label>
                                    <p id="OrderDateid"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
                            <button type="submit" class="btn btn-primary admin-button-add-vnew">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!----assign driver Modal end---->


    <!----order payment confirm display Modal starts---->
    <div class="modal fade fadeInUp" id="pModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="">Select</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <form id="">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group required">
                                    <label>is This Order payment is Paid or Pending ?</label>
                                    <br>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="payment_status1" name="payment_status"
                                            class="custom-control-input payment_status" value="1" checked>
                                        <label class="custom-control-label" for="payment_status1">Paid</label>
                                    </div>
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="payment_status0" name="payment_status"
                                            class="custom-control-input payment_status" value="0">
                                        <label class="custom-control-label" for="payment_status0">Pending</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="modal-footer">
                            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
                            <button type="button" class="btn btn-primary" id="btn_submit_payment_status">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!----order payment confirm display Modal ends---->
    @include('admin.layout.js')
    <script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script>
    <script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('admin_assets/assets/js/select2.full.min.js')}}"></script>
    <script type="text/javascript">
    var t1;
    $.fn.dataTable.ext.errMode = 'none';
    errorCount = 1;
    $('#t1').on('error.dt', function(e, settings, techNote, message) {
        if (errorCount > 2) {
            showSweetAlert('something went wrong', 'please refresh page and try again', 0);
        } else {
            t1.draw(false);
        }
        errorCount++;
    });




    $(document).ready(function() {
        t1 = $('#t1').DataTable({
            processing: true,
            language: {
                processing: processingHTML_Datatable,
            },
            serverSide: true,
            paging: true,
            searching: true,
			searchDelay: 999,
            lengthMenu: [
                [10, 50, 100, 500],
                [10, 50, 100, 500]
            ],
            pageLength: 50,
            order: [
                [0, "desc"]
            ],
			"search": {"regex": true },
            ajax: {
                data: {
                    "status": 0,
                },
                url: "{{ route('get-order-list') }}",
                type: "get"
            },
            aoColumnDefs: [{
                'bSortable': false,
                'aTargets': [-1, -2, -3]
            }],
        });


        $('#t1 .searchnow th').each(function(colIdx) {
            var abc = $("#t1").find("tr:first th").length;
            if ($(this).hasClass("searchcls")) {
                $(this).html('<input type="text" style="max-width: 100px;" />');
            } else {
                $(this).html('');
            }
            $('input', this).on('keyup change', function() {
                if (t1.column(colIdx).search() !== this.value) {
                    console.log(this.value);
                    t1.column(colIdx).search(this.value).draw();
                }
            });
        });
    });



    </script>
    <!----Add Custom Js --end------->

     @include('admin.order.order_logs')
</body>

</html>