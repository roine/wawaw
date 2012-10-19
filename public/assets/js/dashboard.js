/*
// moving the stats to display russian an taiwanese
*/
$(document).keydown(function(e){
	var height =  $("div.grid_6").height();
	$("#stats_container").css({"height":parseInt(height)+5, "overflow":"hidden"});
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

$(document).ready(function(){
	
	$('#accordion').accordion();
	$(window).resize();

	$.ajax({
		url:'/ajax/dashboard',
		type:'POST',
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
					trend = (anteweek > week) ? 'down' : (week < anteweek) ?'up' : 'equal';
					$("#stats_container ."+language+" #"+stat+" span:nth-child(2)").addClass(trend).html(week+'<small>'+anteweek+'</small>');
					trend = (antemonth > month) ? 'down' : (antemonth < month) ? 'up' : 'equal';
					$("#stats_container ."+language+" #"+stat+" span:nth-child(3)").addClass(trend).html(month+'<small>'+antemonth+'</small>');
				}
				var text = "<span class='icon'></span>Today "+total+" people registered.";
				$("#"+language+"_stat .info").html(text);
			}
		}
	})
})