<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.layout.meta')
    <link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
</head>

<body data-spy="scroll" data-target="#navSection" data-offset="100">

    <style>
    td span:first-child {
        margin-bottom: 5px;
    }
	.redcolorcls {
		color:red;
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
                                        <h4>{{ $control }} <span class="badge badge-info admid-select-color" onClick="refreshthistablecls();">Refresh <i class="fas fa-sync-alt"></i></span></h4>
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
                                                <th width="5%">Driver</th>
                                                <th width="15%">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="t1tbody">
                                        </tbody>
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
    <script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/easytimer@1.1.1/src/easytimer.min.js" type="text/javascript"></script>
    <script type="text/javascript">
    var t1;
	myTimerArray  = [];
    $.fn.dataTable.ext.errMode = 'none';
    errorCount = 1;
    $('#t1').on('error.dt', function(e, settings, techNote, message) {
        if (errorCount > 3) {
            showSweetAlert('something went wrong', 'please refresh page and try again', 0);
            errorCount = 0;
        } else {
            t1.draw();
			
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
			stateSave: true,
			stateDuration: 60 * 60 * 2,
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
                url: "{{ route('get-orderlist-tracking') }}",
                type: "get"
            },
            aoColumnDefs: [{
                'bSortable': false,
                'aTargets': [-1, -2, -3]
            }],
			createdRow: function (row, data, dataIndex) { 
			 		$(row).find("td:eq(5)").attr('id', 't5d'+data[6]); 
					var L=data.length;
					if(typeof myTimerArray[data[6]] !== "undefined"){ myTimerArray[data[6]].stop(); } 
					
					 //for(var i=0; i<L; i++) { 
					 	if(data[5]>0){
					 		myTimerArray[data[6]] = new Timer();
					 		myTimerArray[data[6]].start({ startValues: {seconds: data[5] } });    
					 		myTimerArray[data[6]].addEventListener('secondsUpdated', function (e){
					  			$('#t5d'+data[6]).html("<b>"+myTimerArray[data[6]].getTimeValues().toString()+"<b>").attr({ style:"color:red;",title:"This Order is Late." });
					  		});
						}
						else if(data[5]<0)
						{
							
						}
					//}
					//if(data[5]>0){$(row).attr({ style:"background-color:#dd91a2" });}
			},
        });
    });
	
function refreshthistablecls(){
	if(typeof t1 !== "undefined"){ t1.draw(); } 
}
$(document).ready(function() {
     setInterval(function(){  refreshthistablecls(); }, 60*1000*5.0);
});

</script>
<script>
function timings(secondss){ 
var distance = secondss + 1;
var days = Math.floor(distance / (1000 * 60 * 60 * 24));
var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
var seconds = Math.floor((distance % (1000 * 60)) / 1000);
var thisdatetime =  hours + "h " + minutes + "m " + seconds + "s ";
return thisdatetime;
}

</script> 
<!----Add Custom Js --end------->
@include('admin.order.order_logs')
</body>

</html>