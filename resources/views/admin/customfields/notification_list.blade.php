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


                                </div>
                            </div>
                            <div class="widget-content widget-content-area">
                                <table id="t1" class="table table-hover" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Notification</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <thead class="searchnow">
                                        <tr>
                                            <th class="searchcls">Notification</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Notification</th>
                                            <th>Date</th>
                                        </tr>
                                    </tfoot>
                                </table>
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
    <script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script>

    <script type="text/javascript">
    var t1;
    $.fn.dataTable.ext.errMode = 'none';
    $('#t1').on('error.dt', function(e, settings, techNote, message) {
        showSweetAlert('something went wrong', 'please refresh page and try again', 0);
    });

    $(document).ready(function() {

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
            pageLength: 100,
            order: [
                [1, "desc"]
            ],
            ajax: {
                data: {
                    testonly: 0,
                },
                url: "{{ route('get-all-notifications-list') }}",
                type: "get"
            },
            aoColumnDefs: [{
                'bSortable': false,
                'aTargets': [-1, -2, ]
            }],
        });
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



     </script>
    <!----Add Custom Js --end------->




</body>

</html>