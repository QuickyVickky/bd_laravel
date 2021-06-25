var pusher = new Pusher( PUSHER_APP_KEY , {
      cluster: PUSHER_APP_CLUSTER
    });

    var channel = pusher.subscribe(PUSHER_APP_CHANNELNAME+PUSHER_APP_USERWEBID);
    	channel.bind(PUSHER_APP_EVENTNAME+PUSHER_APP_USERWEBID, function(data) {
      	sendPushernotificationUserWeb(data);  /*alert(JSON.stringify(data)); */
    });
	
	$(document).ready(function(){

	/*Notification.requestPermission(permission => {
    if(permission === 'granted') {
		//sendPushernotificationUserWeb();
    }
});*/


Notification.requestPermission().then(function(permission) {
	if(permission === 'granted') {
		//sendPushernotificationUserWeb();
    }
});

});

function sendPushernotificationUserWeb(data){
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

	
