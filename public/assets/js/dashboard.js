/*
// moving the stats to display russian an taiwanese
*/


// time running like digital clock
var run = function(){
	var $time = $('.time.running');
	var $hour = $time.find('span:nth-child(1)');
	var $minute = $time.find('span:nth-child(2)');
	var $second = $time.find('span:nth-child(3)');
	var current_second = parseInt($time.find('span:nth-child(3)').text(),10);
	var current_minute = parseInt($time.find('span:nth-child(2)').text(),10);
	// increment the seconds
	$second.text(function(e,val){
		str = parseInt(val, 10) + 1;
		if(str > 0 && str < 10)
			str = '0'+str;
		else if(str >= 60){
			str = '00';
		}
		return str;
	});
	// increment the minutes
	if(current_second == 0){
		$minute.text(function(e,val){
		str = parseInt(val, 10) + 1;
		if(str > 0 && str < 10)
			str = '0'+str;
		else if(str >= 60){
			str = '00';
		}
		return str;
	});
	}
	// increment the hours
	if(current_minute == 0 && current_second == 0){
		$hour.text(function(e,val){
		str = parseInt(val, 10) + 1;
		if(str > 0 && str < 10)
			str = '0'+str;
		else if(str >= 24){
			str = '00';
		}
		return str;
	});
	}
	setTimeout("run()", 1000);
}

$(document).ready(function(){

	run();
	var height =  $("div.grid_6").outerHeight();
	$("#stats_container").css({"height":parseInt(height), "overflow":"hidden"});
	$(document).keydown(function(e){
	
	el = $("div.en, div.cn");
	position = el.position();
	//is Down
	if(e.keyCode == 40){
		e.preventDefault();
		$("div.grid_6").removeClass("isInitialPosition").css({"-moz-transform":"translateY(-"+height+"px)", "-webkit-transform":"translateY(-"+height+"px)", "-o-transform":"translateY(-"+height+"px)", "-ms-transform":"translateY(-"+height+"px)"});
		
	}
	//is up
	else if(e.keyCode == 38){
		e.preventDefault();
		$("div.grid_6").addClass("isInitialPosition").removeAttr("style");
		
	}
});
	$('#accordion').accordion();
	$(window).resize();
	toPull = new Array();

	$('#stats_container .stats-list a').each(function(){
		if(typeof $(this).attr('id') != 'undefined'){
			toPull.push($(this).attr('id'));
		}
	});
	$.ajax({
		url:'/ajax/dashboard',
		type:'POST',
		data:{values:toPull},
		success:function(data){
			window.p = data;
			var lang = ["en", "cn", "ru", "tw"];
			if(localStorage){
				if(localStorage['stat'] === null)
					localStorage.setItem('stats', JSON.stringify(data));
			}
			for(k in lang){
				var language = lang[k];
				var total = 0;
				for(stat in data[language]){
					var day = data[language][stat][0].day;
					var anteday  = typeof(data[language][stat][0].anteday) === 'undefined' ? '-' : data[language][stat][0].anteday;
					var week = data[language][stat][0].week;
					var anteweek = typeof(data[language][stat][0].anteweek) === 'undefined' ? '-' : data[language][stat][0].anteweek;
					var month = data[language][stat][0].month;
					var antemonth = typeof(data[language][stat][0].antemonth) === 'undefined' ? '-' : data[language][stat][0].antemonth;
					total += data[language][stat][0].day != null ? parseInt(data[language][stat][0].day) : 0;
					trend = (anteday > day) ? 'down' : (anteday < day) ? 'up' : 'equal';
					$("#stats_container ."+language+" #"+stat+" span:nth-child(1)").addClass(trend).html(day+'<small>'+anteday+'</small>');
					trend = (anteweek > week) ? 'down' : (anteweek < week) ?'up' : 'equal';
					$("#stats_container ."+language+" #"+stat+" span:nth-child(2)").addClass(trend).html(week+'<small>'+anteweek+'</small>');
					trend = (antemonth > month) ? 'down' : (antemonth < month) ? 'up' : 'equal';
					$("#stats_container ."+language+" #"+stat+" span:nth-child(3)").addClass(trend).html(month+'<small>'+antemonth+'</small>');
				}
				var text = "<span class='icon'></span>Today "+total+" people registered.";
				$("#"+language+"_stat .info").html(text);
			}
		}
	});
});

$('.notificationsSend input[type=submit]').click(function(){
	if(ws){
		var data = {
			name:$('.toolcaption span').text(),
			text:$('.notificationsSend textarea').val()
		};
		var msg = JSON.stringify(data);
		ws.send(msg);
			
	}
	else{
		$.jGrowl('no connection to the web socket server', {
			theme : 'information',
			life: 3000
		});
	}
	$('.notificationsSend textarea').val('');
	
});

