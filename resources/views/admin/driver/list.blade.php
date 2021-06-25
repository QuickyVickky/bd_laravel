<!DOCTYPE html>
<html lang="en">
<head>
@include('admin.layout.meta')
<link href="{{ asset('admin_assets/assets/css/scrollspyNav.css')}}" rel="stylesheet" type="text/css" />
<link href="{{ asset('admin_assets/assets/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" />
<script defer src="https://maps.googleapis.com/maps/api/js?key={{ constants('googleAPIKey0') }}&callback=initMap"></script>
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
        <!---maps---->
        <div class="col-lg-12 col-12 layout-spacing">
          <div class="statbox widget box box-shadow">
            <div class="widget-header">
              <div class="row">
                <div class="col-xl-6 col-md-6 col-sm-6 col-6">
                  <h4>Drivers Tracking</h4>
                </div>
                <div class="col-xl-6 col-md-6 col-sm-6 col-6"> 
                  <!--a class="btn btn-primary  m-2 btn-rounded float-right"
                               href=""><i class="fas fa-arrow-circle-left"></i> Back
                            </a--> 
                </div>
              </div>
            </div>
            <div class="widget-content widget-content-area">
              <div class="google-map" id="map" style="width:100%;height:400px"></div>
              <div id="response" data-response="{{ $drivers }}"></div>
            </div>
          </div>
        </div>
        <!---maps---->
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
                  <div class="alert alert-{{ Session::get("cls") }} mb-4" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span> <i class="fa fa-times" aria-hidden="true"></i></span></button>
                    {{ Session::get("msg") }} </div>
                  @endif </div>
              </div>
            </div>
            <div class="widget-content widget-content-area">
            <div class="table-responsive mb-4 mt-4">
              <table id="t1" class="table table-hover table-margin-5" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>FullName</th>
                    <th>Mobile</th>
                    <th>Vehicle</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                  <tr>
                    <th>#</th>
                    <th>FullName</th>
                    <th>Mobile</th>
                    <th>Vehicle</th>
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
    @include('admin.layout.footer') </div>
  <!--  END CONTENT AREA  --> 
</div>
<!-- END MAIN CONTAINER --> 
<!----order logs Modal start---->
<div class="modal fade" id="assignTableModal" tabindex="-1" role="dialog" aria-labelledby="xid" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h6 class="modal-title" id="xid">assign</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">x</button>
      </div>
      <div class="modal-body">
        <table id="assignTable" class="table table-hover" style="width:100%">
          <thead>
            <tr>
              <th>BigDaddy LR Number
                <input type="checkbox" id="selectAllid" onClick="selectAll(this)"></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
        <div class="modal-footer">
          <form id="assign_form">
            @csrf
            <input type="hidden" name="ovalueid" id="ovalueid" value="0">
            <input type="hidden" name="driver_id" id="driver_idx" value="0">
            <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Close</button>
            <button type="submit" class="btn btn-info float-right" onClick="assignMultiple()">Assign & Submit</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<!----order logs  Modal end----> 
@include('admin.layout.js') 
<script src="{{ asset('admin_assets/assets/js/scrollspyNav.js')}}"></script> 

<!----Add Custom Js ----start-----> 
<script src="{{ asset('admin_assets/assets/js/jquery.dataTables.min.js')}}"></script> 
<script src="{{ asset('admin_assets/assets/js/dataTables.bootstrap4.min.js')}}"></script> 
<script type="text/javascript" >
   
   	var assignTable;
	 var tbl = '{{ $tbl }}';
	 var isdelete = 'N';
     var dataTable;
	var order_status = "'P','PU'"; 
$.fn.dataTable.ext.errMode = 'none';
	$('#t1').on('error.dt', function(e, settings, techNote, message) {
   		showSweetAlert('something went wrong','please refresh page and try again', 0); 
	});


$('body').on('click', '.assignit', function () {
	$('#assignTableModal').modal("show");
	$('#selectAllid').prop("checked", false);
	var dvalue = $(this).data('id');
	$('#driver_idx').val(dvalue);
                    assignTable = $('#assignTable').DataTable({
                        processing: true,
						language: {
							processing: processingHTML_Datatable,
  						},
						destroy: true,
						paging: true,
						searching: false,
						lengthMenu : [[10, 50, 100], [10, 50, 100]],
						pageLength: 10,
                        order: [[0, 'desc']],
                        serverSide: true,
						ajax: "{{ route('get-order-with-lrnumber-only-tobeassigned') }}?order_status="+order_status,
                        'aoColumnDefs': [{
                            'bSortable': false,
                            'aTargets': [0],
                        }],
				
                });
			});

			 $(document).ready(function () {
                    dataTable = $('#t1').DataTable({
						 processing: true,
						language: {
							processing: processingHTML_Datatable,
  						},
                        stateSave: true,
						searching: true,
						searchDelay: 999,
                        order: [[1, 'desc']],
                        serverSide: true,
                        ajax: "{{ route('get-driver-list') }}",
                        'aoColumnDefs': [{
                            'bSortable': false,
                            'aTargets': [-1,-2],
                        }],
						"createdRow": function( row, data, dataIndex ){ if (data[5]>0){$(row).attr({ style:"background-color:#dd91a2",title:"" });} }
                    });
                });
				
	function selectAll(source) {
            checkboxes = document.getElementsByName('selectlist[]');
            for (var i = 0, n = checkboxes.length; i < n; i++) {
                checkboxes[i].checked = source.checked; }
                if($("#selectAllid").prop('checked') == true){
                var ck = [];
                        $.each($("input[name='selectlist[]']:checked"),function(){ ck.push($(this).data('id'));
								$("#ovalueid").val(ck);   
                        });
                 }
                else {  $("#ovalueid").val('');   }
          } 
		  
		  function assignMultiple() { 
			var ck = [];
            $.each($("input[name='selectlist[]']:checked"),function(){ ck.push($(this).data('id'));});   
			len=ck.length;
			if(len==0){ return false;} 
			var v = ck.toString();
			$("#ovalueid").val(v);
			return true;
		}
		
		$("#assign_form").submit(function (event) { event.preventDefault();
        var formData = new FormData($("#assign_form")[0]); 
		var x = assignMultiple();
		if(x==false){ alert("none selected"); return false; } 
		
      $.ajax({
			url: "{{ route('assign-order-to-driver') }}",
        	type: 'POST', 
			data: formData,
            contentType: false,
			processData: false,
         	success: function (data) { 
         			$('#assignTableModal').modal('hide');
         			dataTable.draw();
				if(data.success==1){
					showToastAlert(data.msg,'success');
				}
				else
				{
					showToastAlert(data.msg);
				}
				
         	},  error: function(){  showSweetAlert('could not be assigned,','please refresh page and try again', 0);  }
            });  
			return false; 
		});


     </script> 
<script>
        var addresses = $("#response").data('response');
        // console.log('addresses',addresses);

        for (var i = 0; i < addresses.length; i++) {
            // console.log("address:",addresses[i].first_name);
        }

        let map, markers = [], geocoder;
        //setInterval("getCurrentAddress()", 1200000); //milliseconds
        function initMap() {
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: {
					/*lat: 41.85,
                    lng: -87.65*/
                    lat: 	21.1702,
                    lng: 	72.8311
                }
            });
            // setMap(map);
            geocoder = new google.maps.Geocoder();
            for (var i = 0; i < addresses.length; i++) {
                console.log("addresses:",addresses[i]);
                geocodeAddress(geocoder, map, addresses[i]);
            }
        }

        function addMarker(location,name,latitude,longitude) {
			//const myLatLng = { lat: 21.19473385439555, lng: 72.86521868749732 };
			const myLatLng = { lat: parseFloat(latitude), lng: parseFloat(longitude) };
			
			//console.log(" la "  +latitude);console.log("lo " +longitude);
			
			
            const marker = new google.maps.Marker({
                position: myLatLng,
				//position: location,
                map: map,
                icon: "{{ asset('admin_assets/driver-location.png') }}",
                animation:google.maps.Animation.BOUNCE,
            });
            markers.push(marker);
            const infowindow = new google.maps.InfoWindow({
                content: "<p>Driver:<b>" + name + "</b></p>"
            });
            google.maps.event.addListener(marker, "click", () => {
                infowindow.open(map, marker);
            });
        }

        function setMapOnAll(map) {
            for (let i = 0; i < markers.length; i++) {
                markers[i].setMap(map);
            }
        }

        function showMarkers() {
            setMapOnAll(map);
        }

        function clearMarkers() {
            setMapOnAll(null);
        }

        function geocodeAddress(geocoder, resultsMap, address) {
            geocoder.geocode({address: address.current_location}, (results, status) => {
                    if (status === "OK") {
                        resultsMap.setCenter(results[0].geometry.location);
                        addMarker(results[0].geometry.location,(address.fullname),address.current_latitude,address.current_longitude);
                    } else {
                       console.log("Geocode was not successful for the following reason: " + status);
                    }
                }
            );
        }


        /*function getCurrentAddress() {
            $.get('/api/drivers/location', {}, function (res, resp) {
                if(res.Success == true && res.Data.current_location != ''){
                    var records = res.Data;
                    clearMarkers();
                    markers = [];
                    for (var i = 0; i < records.length; i++) {
                        console.log("addresses:",records[i]);
                        geocodeAddress(geocoder, map, records[i]);
                    }

                }

            }, "json");
        }*/
    </script> 

<!----Add Custom Js --end-------> 
<script>

$('body').on('click', '.change_status_confirm', function () {
		var id = $(this).data('id');
		var status = $(this).data('val');
		
		if(status==1){
			texttext = "Do You Want to DeActivate This Driver ?";
			sendLog = "DeActivated By ";
			imageUrlimageUrl = "{{ asset('admin_assets/assets/etc/deactivate.png') }}";
		}
		else if(status==0){
			texttext = "Do You Want to Activate This Driver ?";
			sendLog = "Activated By ";
			imageUrlimageUrl = "{{ asset('admin_assets/assets/etc/activate.png') }}";
		}
		else
		{
			return false;
		}


  swal({
      title: 'Are You Sure ?',
      text: texttext,
	  imageUrl: imageUrlimageUrl,
    imageWidth: 400,
    imageHeight: 200,
    imageAlt: 'cancel order  image',
     // type: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Yes',
      padding: '-2em'
    }).then(function(result) {
      if (result.value) {
		  
		  $.ajax({
            url: "{{ route('change-driver-status') }}",
            data: {
              _token : '{{ csrf_token() }}',
              id: id, 
			  status: status,
			  sendLog: sendLog,
            },
            type: 'post',
            dataType: 'json',
            success: function (data) {
             dataTable.draw(); 
						 swal(
					  'Done!',
					  'Driver Status Changed Successfully',
					  'success'
					);
            },  error: function(){ showSweetAlert('Something Went Wrong!','please refresh page and try again', 0);  }
        });

      }
    });
});



</body>
</html>