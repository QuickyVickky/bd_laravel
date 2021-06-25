<!-- BEGIN GLOBAL MANDATORY SCRIPTS --> 
<script type="text/javascript">
    window.processingHTML_Datatable = '<div class="spinner-border spinner-border-reverse align-self-center loader-lg text-secondary"></div>';
</script>

<script src="{{ asset('admin_assets/assets/js/libs/jquery-3.1.1.min.js') }}"></script> 
<script src="{{ asset('admin_assets/bootstrap/js/popper.min.js') }}"></script> 
<script src="{{ asset('admin_assets/bootstrap/js/bootstrap.min.js') }}"></script> 
<script src="{{ asset('admin_assets/plugins/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script> 
<script src="{{ asset('admin_assets/assets/js/app.js') }}"></script> 
<script src="{{ asset('admin_assets/plugins/sweetalerts/sweetalert2.min.js')}}"></script> 
<script src="{{ asset('admin_assets/plugins/sweetalerts/custom-sweetalert.js')}}"></script>
<script src="{{ asset('admin_assets/assets/js/custom.js')}}"></script> 
<script src="{{ asset('admin_assets/plugins/fullcalendar/moment.min.js')}}"></script> 
<!----our custom own-------->
<script src="{{ asset('js/mycustomall.js')}}"></script>  
  <!----our custom own-------->  
<script>
        $(document).ready(function() {
            App.init();
        });
    </script> 

<!-- END GLOBAL MANDATORY SCRIPTS --> 

<!----Add here global Js ----start----->
<script type="text/javascript" >
const toast = swal.mixin({
					toast: true,
					position: 'top-end',
					showConfirmButton: false,
					timer: 3000,
					padding: '2em'
  			});
			

function showSweetAlert(text1='Completed',text2='Successfully Done', alerttype='1'){
		if(0==alerttype){  alerttype ="error";  } else if(1==alerttype) { alerttype ="success";  } else { alerttype ="question";  }
		swal(text1,text2,alerttype);
}

function showToastAlert(text1='Error', alerttype='error'){
		toast({
				type: alerttype,
				title: text1,
				padding: '2em',
	});
}
function showLoader(type=1){
	
}

function showAlertInToast(res){
		if(res.success==1){
			showToastAlert(res.msg, alerttype='success');
		}
		else
		{
			showToastAlert(res.msg, alerttype='error');
		}
}

function showAlert(res){
		if(res.success==1){
			showSweetAlert("Completed",res.msg, res.success); 
		}
		else
		{
			showSweetAlert("Wrong",res.msg, res.success); 
		}
}

function fileDownload(dir,filename){
		urlFile = "{{ sendPath() }}"+dir+'/'+filename;
	    $.ajax({
        url: urlFile,
        method: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function (data) {
            var a = document.createElement('a');
            var urlFile = window.URL.createObjectURL(data);
            a.href = urlFile;
            a.download = filename;
            document.body.append(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(urlFile);
        }
    });
}

function fileDownloadByUrl(urlFile=''){
    if(urlFile==''){
        return false;
    }
    
    var filename = urlFile.replace(/^.*[\\\/]/, '');

        $.ajax({
        url: urlFile,
        method: 'GET',
        xhrFields: {
            responseType: 'blob'
        },
        success: function (data) {
            var a = document.createElement('a');
            var urlFile = window.URL.createObjectURL(data);
            a.href = urlFile;
            a.download = filename;
            document.body.append(a);
            a.click();
            a.remove();
            window.URL.revokeObjectURL(urlFile);
        }
    });
}


$('body').on('click', '.filedownloadit', function () {
    var filename = $(this).data('filename');
    var folder = $(this).data('folder');
    fileDownload(folder,filename);
});

function isEmail(emailidgiven='') {
	 var regexemail = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(emailidgiven==''){  return true; }
	else
	{
  		return regexemail.test(emailidgiven);
	}
}

/*---------Notification--Append---starts----------*/

function NotificationAppend(){
    ehtml = '';
    

    if ($("#notification_scroll_liid").hasClass("show")) {
        return false;
    }

    $.ajax({
                    url: "{{ route('notification-append-admin') }}",
                    data: {
                        _token: '{{ csrf_token() }}',
                    },
                    type: 'get',
                    dataType: 'json',
                    success: function(data) {
                        for (var i = 0; i < data.length; i++) {
                            if(data[i].notification_link==null){
                                data[i].notification_link = '#';
                            }
                            ehtml += '<div class="dropdown-item"><a href="'+data[i].notification_link+'"><div class="media"> <div class="media-body"><div class="notification-para">'+data[i].notification_text +'</div><div class="notification-para"><b>'+ moment(data[i].created_at).fromNow() +'</b></div></div></div></a></div>';
                        }
						$("#notification_scroll_divid").html('');
                        $("#notification_scroll_divid").html(ehtml);
                        $(".notificationdotinheaderclsshowhide").hide();
                    },
                    error: function() {
                        showSweetAlert('Something Went Wrong!',
                            'please refresh page and try again.', 0);
                    }
    });
}

/*---------Notification--Append---ends----------*/



</script>
<!----Add here global Js --end------->

<!----pusher notification starts here------->
<script src="https://js.pusher.com/7.0/pusher.min.js"></script>
<script type="text/javascript">
var PUSHER_APP_KEY = '{{ env("PUSHER_APP_KEY") }}';
  var PUSHER_APP_CLUSTER = '{{ env("PUSHER_APP_CLUSTER") }}';
  var PUSHER_APP_CHANNELNAME = '{{ env("PUSHER_APP_CHANNELNAME") }}';
  var PUSHER_APP_EVENTNAME = '{{ env("PUSHER_APP_EVENTNAME") }}';
    // Enable pusher logging - don't include this in production
    //Pusher.logToConsole = true;
    var pusher = new Pusher( PUSHER_APP_KEY , {
      cluster: PUSHER_APP_CLUSTER
    });

    var channel = pusher.subscribe(PUSHER_APP_CHANNELNAME);
    	channel.bind(PUSHER_APP_EVENTNAME, function(data) {
      	sendPushernotification(data);  //alert(JSON.stringify(data)); 
    });
	
	
	

$(document).ready(function(){
		
	/*---ask location------*/
	/*if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
	} 
	function showPosition(position) {
 	innerHTML = "Latitude: " + position.coords.latitude + "<br>Longitude: " + position.coords.longitude;
  	console.log(innerHTML);
	}*/
  /*---ask location------*/

	/*Notification.requestPermission(permission => {
    if(permission === 'granted') {
		//sendPushernotification();
    }
});*/


Notification.requestPermission().then(function(permission) {
	if(permission === 'granted') {
		//sendPushernotification();
    }
});

});

function sendPushernotification(data){
        $(".notificationdotinheaderclsshowhide").show();
		if(typeof refreshthistablecls == 'function') { refreshthistablecls();}
		const myNoti = new Notification(data.title, {
    	body: data.message,
    	icon: data.icon,
    	image: data.image.
		link = data.linkurl,
		});
		myNoti.onclick = function(event) {
  			event.preventDefault(); 
			if(data.linkurl!=''){ window.open(data.linkurl, '_blank'); }
		}
}

	

</script>

