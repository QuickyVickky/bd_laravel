<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.layout.meta')
    <link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('admin_assets/assets/css/select2.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('admin_assets/assets/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" />
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
                                <div class="row" style="align-items: center;">
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <h4>{{ $control }}</h4>
                                        @if(Session::get("msg")!='')
                                        <div class="alert alert-{{ Session::get('cls') }} mb-4" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-x close" data-dismiss="alert">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg></button>
                                            {{ Session::get("msg") }}
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="col-sm-2">
                                            <select class="form-control-sm " name="payment_status" id="payment_status">
                                                <option value="" selected> All</option>
                                                @foreach($payment_status as $row)
                                                <option value="{{ $row->short }}"> {{ $row->name }}</option>
                                                @endforeach
                                            </select>

                                        </div>
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



    @include('admin.layout.js')
    <script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script>
    <!----Add Custom Js ----start----->
    <script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="{{ asset('admin_assets/assets/js/select2.full.min.js')}}"></script>
    <script src="{{ asset('admin_assets/assets/js/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript">
    $('.datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
    });

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


    function orderDatatable() {

        payment_status = $("#payment_status").val();

        t1 = $('#t1').DataTable({
            processing: true,
            language: {
                processing: processingHTML_Datatable,
            },
            serverSide: true,
            destroy: true,
            paging: true,
            stateSave: true,
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
            ajax: {
                data: {
                    payment_status: payment_status,
                },
                url: "{{ route('get-order-list-delivered') }}",
                type: "get"
            },
            aoColumnDefs: [{
                'bSortable': false,
                'aTargets': [-1, -2, -3]
            }],
        });
    }
	
	$('#t1 .searchnow th').each(function(colIdx) {
            var abc = $("#t1").find("tr:first th").length;
            if ($(this).hasClass("searchcls")) {
                $(this).html('<input type="text" style="max-width: 100px;" />');
            } else {
                $(this).html('');
            }
            $('input', this).on('keyup change', function() {
                if (t1.column(colIdx).search() !== this.value) {
                    t1.column(colIdx).search(this.value).draw();
                }
            });
        });



    $('body').on('change', '#payment_status', function() {
        orderDatatable();
    });
    $(document).ready(function() {
        orderDatatable();
    });
    </script>
    <!----Add Custom Js --end------->
    @include('admin.order.order_logs')
</body>

</html>