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
                                            <th>#Invoice</th>
                                            <th>Date</th>
                                            <th>Contains</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <thead class="searchnow">
                                        <tr>
                                            <th class="searchcls">#Invoice</th>
                                            <th>Date</th>
                                            <th>Contains</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>#Invoice</th>
                                            <th>Date</th>
                                            <th>Contains</th>
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

    <!----order payment confirm display Modal starts---->
    <div class="modal fade fadeInUp" id="pModal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="">Mark As Payment Paid.</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
                </div>
                <form id="paymentregisterformid">
                    @csrf
                    <div class="modal-body">

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group required">
                                    <label>Payment Date *</label>
                                    <br>
                                    <input type="text" name="payment_datetime" class="form-control datepicker"
                                        data-date-format="dd-mm-yyyy" id="payment_datetime" placeholder="Payment Date"
                                        value="{{ date('d-m-Y') }}" required>
                                </div>
                            </div>
						<input type="hidden" name="invoice_id" id="invoice_id" value="0">
                            <div class="col-sm-6">
                                <div class="form-group required">
                                    <label>Payment Type *</label>
                                    <br>
                                    <select class="form-control payment_type_manualcls" name="payment_type_manual" id="payment_type_manual">
                                        @foreach(constants('payment_type_manual') as $row)
                                        <option value="{{ $row['short'] }}"> {{ $row['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-6" id="if_cheque_numberrowid" style="display: none;">
                                <div class="form-group required">
                                    <label>Cheque Number</label>
                                    <br>
                                    <input type="text" name="if_cheque_number" class="form-control"
                                        id="if_cheque_number" placeholder="Cheque Number" maxlength="20" required>
                                </div>
                            </div>


                            <div class="col-sm-6">
                                <div class="form-group required">
                                    <label>Payment Discount</label>
                                    <br>
                                    <input type="text" name="payment_discount" class="form-control allowdecimal"
                                        id="payment_discount" placeholder="Payment Discount" maxlength="6" required>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group required">
                                    <label>Transaction Id</label>
                                    <br>
                                    <input type="text" name="if_transaction_number" class="form-control"
                                        id="if_transaction_number" placeholder="Transaction Id" maxlength="25">
                                </div>
                            </div>
                            
                 <div class="col-sm-12">
                    <div class="form-group required">
                      <label for="name">Account Bank (required)</label>
                      <select class="form-control"  name="accountid_from" id="accountid_from" required>
                      @foreach($dataAccAccountBanks as $row)
                        <option value="{{ $row->id }}"> {{ $row->name }} | {{ $row->account_id }}</option>
                       @endforeach
                      </select>
                    </div>
                  </div>

                            <div class="col-sm-12">
                                <div class="form-group required">
                                    <label>Payment Comment</label>
                                    <br>
                                    <input type="text" name="payment_comment" class="form-control" id="payment_comment"
                                        placeholder="Comment" maxlength="200">
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
    <link href="{{ asset('admin_assets/assets/css/bootstrap-datepicker.min.css')}}" rel="stylesheet" />
    <script src="{{ asset('admin_assets/assets/js/bootstrap-datepicker.min.js')}}"></script>
    <script type="text/javascript">
    $('.datepicker').datepicker({
        autoclose: true,
        todayHighlight: true,
    });
    var t1;
    cheque_payment_type = "{{ constants('payment_type_manual.CHQ.short') }}";

    $.fn.dataTable.ext.errMode = 'none';
    $('#t1').on('error.dt', function(e, settings, techNote, message) {
        showSweetAlert('something went wrong', 'please refresh page and try again', 0);
    });



    $(document).ready(function() {
        invoiceDatatable();
    });


    function invoiceDatatable() {
		payment_status = $("#payment_status").val();
        t1 = $('#t1').DataTable({
            processing: true,
            language: {
                processing: processingHTML_Datatable,
            },
            serverSide: true,
            destroy: true,
            paging: true,
            searching: true,
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
                    payment_status: payment_status,
                },
                url: "{{ route('get-invoice-list') }}",
                type: "get"
            },
            aoColumnDefs: [{
                'bSortable': false,
                'aTargets': [-1, -2, ]
            }],
        });
    }
	
	$('body').on('change', '#payment_status', function() {
        invoiceDatatable();
    });

    $('#t1 .searchnow th').each(function(colIdx) {
        var abc = $("#t1").find("tr:first th").length;
        if ($(this).hasClass("searchcls")) {
            $(this).html('<input type="text" style="max-width: 125px;"/>');
        } else {
            $(this).html('');
        }
        $('input', this).on('keyup change', function() {
            if (t1.column(colIdx).search() !== this.value) {
                t1.column(colIdx).search(this.value).draw();
            }
        });
    });

    $('body').on('change', '.payment_type_manualcls', function() {
        pt = $("#payment_type_manual").val();
        if (cheque_payment_type == pt) {
            $("#if_cheque_numberrowid").show();
        } else {
            $("#if_cheque_numberrowid").hide();
        }
    });

    $('body').on('click', '.vieworderit', function() {
        var invid = $(this).data('id');
        $.ajax({
            url: "{{ route('get-orderhtml-by-invoice') }}",
            data: {
                _token: '{{ csrf_token() }}',
                id: invid,
            },
            type: 'post',
            dataType: 'json',
            success: function(data) {
                $('.divremovecls').remove();
                $('#r' + invid).after(data.ehtml);
            },
            error: function() {
                showSweetAlert('Something Went Wrong!', 'please refresh page and try again', 0);
            }
        });
    });



    $('body').on('click', '.invoicepaymentregisterit', function() {
        $("#btn_submit_payment_status").prop('disabled',false);
        $('#paymentregisterformid')[0].reset();
        var invoice_id = $(this).data('id');
		$("#invoice_id").val(invoice_id);
        $("#pModal").modal("show");
        pt = $("#payment_type_manual").val();
        if (cheque_payment_type == pt) {
            $("#if_cheque_numberrowid").show();
        } else {
            $("#if_cheque_numberrowid").hide();
        }
});

    $('body').on('click', '#btn_submit_payment_status', function() {
        payment_datetime = $("#payment_datetime").val();
        payment_type_manual = $("#payment_type_manual").val();
        if_cheque_number = $("#if_cheque_number").val();
        payment_discount = $("#payment_discount").val();
        if_transaction_number = $("#if_transaction_number").val();
        payment_comment = $("#payment_comment").val();
		accountid_from = $("#accountid_from").val();
		invoice_id = $("#invoice_id").val();

        if (payment_datetime.length != 10 || payment_type_manual == '' || accountid_from<1 || invoice_id<1) {
            showSweetAlert('Missing Value Required.', 0);
            return false;
        }
		$("#pModal").modal("hide");
        $("#btn_submit_payment_status").prop('disabled',true);
        

        $.ajax({
            url: "{{ route('mark-as-payment-paid-invoice') }}",
            data: {
                _token: '{{ csrf_token() }}',
                invoice_id: invoice_id,
                status: 1,
                payment_datetime: payment_datetime,
                payment_type_manual: payment_type_manual,
                if_cheque_number: if_cheque_number,
                payment_discount: payment_discount,
                if_transaction_number: if_transaction_number,
                payment_comment: payment_comment,
				accountid_from: accountid_from,
            },
            type: 'post',
            dataType: 'json',
            success: function(data) {
				showAlert(data);
                t1.draw();
            },
            error: function() {
                showSweetAlert('Something Went Wrong!', 'please refresh page and try again', 0);
            }
        });
    });


    $('body').on('click', '.delete_invoice_btnidcls', function() {
        dataid = $(this).data('id');
        inv = $(this).data('inv');

        if (dataid == '' || inv == '') {
            return false;
        }

        swal({
            title: 'Are You Sure ?',
            text: 'Delete This Invoice.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            padding: '-2em'
        }).then(function(result) {
            if (result.value) {
				
                $.ajax({
                    url: "{{ route('delete-order-invoice') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: dataid,
                        invoice_number: inv,
                    },
                    type: 'post',
                    dataType: 'json',
                    success: function(data) {
						showAlert(data);
                        t1.draw();
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