<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.layout.meta')
    <link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
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

                                <div class="row" style="align-items: center;">
                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <h4>{{ $control }}</h4>
                                        @if(Session::get("msg")!='')
                                        <div class="alert alert-{{ Session::get('cls') }} mb-4" role="alert"> <button
                                                type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-x close" data-dismiss="alert">
                                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                                </svg></button> {{ Session::get("msg") }}</div>
                                        @endif
                                    </div>

                                    <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                                        <div class="col-sm-2">
                                            <select class="form-control-sm " name="customer_type" id="customer_type">
                                                <option value="" selected> All Customer</option>
                                                @foreach($customer_type as $row)
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
                                            <th>#</th>
                                            <th>FullName</th>
                                            <th>GST Number</th>
                                            <th>Business Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#</th>
                                            <th>FullName</th>
                                            <th>GST Number</th>
                                            <th>Business Name</th>
                                            <th>Status</th>
                                            <th>Action</th>
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
    <script type="text/javascript">
    var tbl = '{{ $tbl }}';
    var isdelete = 'N';
    var dataTable;

    $.fn.dataTable.ext.errMode = 'none';
    $('#t1').on('error.dt', function(e, settings, techNote, message) {
        showSweetAlert('something went wrong', 'please refresh page and try again', 0);
    });


    $('body').on('change', '#customer_type', function() {
        customerDatatable();
    });

    $(document).ready(function() {
        customerDatatable();
    });

    function customerDatatable() {
        customer_type = $("#customer_type").val();

        dataTable = $('#t1').DataTable({
            processing: true,
            language: {
                processing: processingHTML_Datatable,
            },
            serverSide: true,
            destroy: true,
            paging: true,
            stateSave: false,
            searching: true,
			searchDelay: 999,
            lengthMenu: [
                [10, 50, 100, 500, 1000],
                [10, 50, 100, 500, 1000]
            ],
            pageLength: 50,
            order: [
                [1, "desc"]
            ],
            ajax: {
                data: {
                    customer_type: customer_type,
                },
                url: "{{ route('get-customer-list') }}",
                type: "get"
            },
            aoColumnDefs: [{
                'bSortable': false,
                'aTargets': [-1, -2, 0]
            }],
            createdRow: function(row, c, dataIndex) {
                if (c[6] > 0) {
                    $(row).attr({
                        style: "background-color:#dd91a2",
                        title: c[6] + " order Undelivered"
                    });
                }
            },
        });

    }






    $('body').on('click', '.change_status_confirm', function() {
        var id = $(this).data('id');
        var status = $(this).data('val');

        if (status == 1) {
            texttext = "Do You Want to DeActivate This Customer ?";
            sendLog = "DeActivated By ";
            imageUrlimageUrl = "{{ asset('admin_assets/assets/etc/deactivate.png') }}";
        } else if (status == 0) {
            texttext = "Do You Want to Activate This Customer ?";
            sendLog = "Activated By ";
            imageUrlimageUrl = "{{ asset('admin_assets/assets/etc/activate.png') }}";
        } else {
            return false;
        }


        swal({
            title: 'Are You Sure ?',
            text: texttext,
            imageUrl: imageUrlimageUrl,
            imageWidth: 400,
            imageHeight: 200,
            imageAlt: 'cancel order image',
            // type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            padding: '-2em'
        }).then(function(result) {
            if (result.value) {

                $.ajax({
                    url: "{{ route('change-customer-status') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id,
                        status: status,
                        sendLog: sendLog,
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(data) {
                        dataTable.draw();
                        swal(
                            'Done!',
                            'Customer Status Changed Successfully',
                            'success'
                        );
                    },
                    error: function() {
                        showSweetAlert('Something Went Wrong!',
                            'please refresh page and try again', 0);
                    }
                });

            }
        });
    });
    </script>
    <!----Add Custom Js --end------->




</body>

</html>