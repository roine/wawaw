/* ==================================================
 * 1) WebSocket client implementation
 * 2) webkitNotifications implementation
 * 3) FullScreen implementation
 * ================================================== */

var wn = window.webkitNotifications;

var maxDisplay = 10;
if(typeof localStorage.view == 'undefined') localStorage.view = 0;
localStorage.view++;
var still = maxDisplay - localStorage.view;
// If Websocket not supported ask for upgrade
if(!window.WebSocket){
	if(still > 0)
		$.jGrowl("Your browser is outdated, in order to use the live notification please use at least <b>Chrome 6</b> or <b>Firefox 8</b> or <b>Internet Explorer 10</b> or <b>Opera 12.10</b> or <b>Safari 5.0</b>.<br />This message will be displayed "+still+" times", {
			theme : 'information',
			life: 10000
		});
}
else{
	var ws = new WebSocket('ws://192.168.1.214:8000/server.php');

	ws.onerror = function(){
		console.log('error');
	}
	
	ws.onclose = function(){
		$.jGrowl('Connection to websocket server closed', {theme : 'information'});
	}
	// if Notification not supported ask to switch to chrome
	if(!window.webkitNotifications){

		if(localStorage.view < maxDisplay)
			$.jGrowl("Notification only works on <b>Chrome</b> from the <b>version 6</b>.<br />This message will be displayed "+still+" times", {
				theme : 'information',
				life: 10000
			});

		ws.onmessage = function(e){
			
			var msg = jQuery.parseJSON(e.data);
			if(msg.text && msg.url && msg.form)
				text = sprintf(msg.text, msg.form)+' <a href="'+msg.url+'">Click here</a>';
			else
				text = '';
			$.jGrowl(text, {
				theme : 'information',
				life: 10000
			});
		}

	}
	else{
		// notification allowed = 0
		if(wn.checkPermission() == 0) {
			ws.onmessage = function(e){

				var msg = jQuery.parseJSON(e.data);
				if(msg.text && msg.form){
					text = sprintf(msg.text, msg.form);
				}
				else
					text = '';
				var notification =  wn.createNotification(
					'http://192.168.1.214:3000/assets/img/icons/25x25/dark/user.png', 
					'Notification From the IKON backoffice', 
					text);
				notification.show();
				window.setTimeout(function(){notification.close()}, 10000)
			}	
		}
		else{
			$.jGrowl("<a href='#' onclick=allowNotification(); return false;>Allow the notification on your browser</a>", {
				theme : 'information',
				life: 10000
			});
			ws.onmessage = function(e){
			
				var msg = jQuery.parseJSON(e.data);
				if(msg.text && msg.url && msg.form)
				var text = sprintf(msg.text, msg.form)+' <a href="'+msg.url+'">Click here</a>';
				$.jGrowl(e.data, {
					theme : 'information',
					life: 10000
				});
			}

		}
	}

}

var allowNotification = function(){
	allowed = wn.requestPermission();
	if(allowed)
		wn.createNotification('http://192.168.1.214:3000/assets/img/icons/16x16/user.png', 'info', 'Notification allowed for the IKON backoffice')
}


/* ==================================================
 * 3) FullScreen
 * ================================================== */
var fs = function(){
	$.jGrowl("You are now in fullscreen Mode, to quit it press <b>ESC</b> on your keyboard", {
		theme : 'information',
		life: 10000
	});
	if(document.documentElement.webkitRequestFullscreen)
		document.documentElement.webkitRequestFullscreen();
	else if(document.documentElement.RequestFullscreen)
		document.documentElement.RequestFullscreen();
	else if(document.documentElement.mozRequestFullScreen)
		document.documentElement.mozRequestFullScreen();

}

if(document.mozFullScreenEnabled || document.webkitFullscreenEnabled || document.fullScreenEnabled){
	$.jGrowl("<a href='#' onclick='fs(); return false;'>Fullscreen</a>", {
				theme : 'information',
				life: 10000
	});
}
	