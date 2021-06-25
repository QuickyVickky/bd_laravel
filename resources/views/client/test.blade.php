<!DOCTYPE html>
<html>
<head>
  <title>Pusher Test</title>
  <script src="{{ asset('admin_assets/assets/js/libs/jquery-3.1.1.min.js') }}"></script> 
  <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
  <script>
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
      	sendnotification(data);  //alert(JSON.stringify(data)); 
    });
  </script>

<script type="text/javascript">
$(document).ready(function(){

	/*Notification.requestPermission(permission => {
    if(permission === 'granted') {
		//sendnotification();
    }
});*/


Notification.requestPermission().then(function(permission) {
	if(permission === 'granted') {
		//sendnotification();
    }
});

});


function sendnotification(data){
		const myNoti = new Notification(data.title, {
    	body: data.message,
    	icon: data.icon,
    	image: data.image
		});
}

</script>


</head>
<body>
  <h1>Pusher Test</h1>
  
</body>
</html>