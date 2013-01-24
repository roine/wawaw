$(window).load(function(){
			/*
			 * Validate the form when it is submitted
			 */
	var validatelogin = $("form").validate({
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors === 1
				  ? 'You missed 1 field. It has been highlighted.'
				  : 'You missed ' + errors + ' fields. They have been highlighted.';
				$('.box .content').removeAlertBoxes();
				$('.box .content').alertBox(message, {type: 'warning', icon: true, noMargin: false});
				$('.box .content .alert').css({
					width: '',
					margin: '0 0 5px',
					borderLeft: 'none',
					borderRight: 'none',
					borderRadius: 0
				});
			} else {
				$('.box .content').removeAlertBoxes();
			}
		},
		showErrors : function(errorMap, errorList) {
				this.defaultShowErrors();
				var self = this;
				$.each(errorList, function() {
					var $input = $(this.element),
						$label = $input.parent().find('label.error').hide();

					$label.addClass('red');
					$label.css('width', '');
					$input.trigger('labeled');
					$label.fadeIn();
				});
		}
	});

	$('.beforeLoading').removeClass('beforeLoading');
	$('.enrolled').removeClass('enrolled').addClass('derolled');
});

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


/**
sprintf() for JavaScript 0.6

Copyright (c) Alexandru Marasteanu <alexaholic [at) gmail (dot] com>
All rights reserved.

Redistribution and use in source and binary forms, with or without
modification, are permitted provided that the following conditions are met:
    * Redistributions of source code must retain the above copyright
      notice, this list of conditions and the following disclaimer.
    * Redistributions in binary form must reproduce the above copyright
      notice, this list of conditions and the following disclaimer in the
      documentation and/or other materials provided with the distribution.
    * Neither the name of sprintf() for JavaScript nor the
      names of its contributors may be used to endorse or promote products
      derived from this software without specific prior written permission.

THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL Alexandru Marasteanu BE LIABLE FOR ANY
DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
(INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND
ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
(INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.


Changelog:
2007.04.03 - 0.1:
 - initial release
2007.09.11 - 0.2:
 - feature: added argument swapping
2007.09.17 - 0.3:
 - bug fix: no longer throws exception on empty paramenters (Hans Pufal)
2007.10.21 - 0.4:
 - unit test and patch (David Baird)
2010.05.09 - 0.5:
 - bug fix: 0 is now preceeded with a + sign
 - bug fix: the sign was not at the right position on padded results (Kamal Abdali)
 - switched from GPL to BSD license
2010.05.22 - 0.6:
 - reverted to 0.4 and fixed the bug regarding the sign of the number 0
 Note:
 Thanks to Raphael Pigulla <raph (at] n3rd [dot) org> (http://www.n3rd.org/)
 who warned me about a bug in 0.5, I discovered that the last update was
 a regress. I appologize for that.
**/

function str_repeat(i, m) {
	for (var o = []; m > 0; o[--m] = i);
	return o.join('');
}

function sprintf() {
	var i = 0, a, f = arguments[i++], o = [], m, p, c, x, s = '';
	while (f) {
		if (m = /^[^\x25]+/.exec(f)) {
			o.push(m[0]);
		}
		else if (m = /^\x25{2}/.exec(f)) {
			o.push('%');
		}
		else if (m = /^\x25(?:(\d+)\$)?(\+)?(0|'[^$])?(-)?(\d+)?(?:\.(\d+))?([b-fosuxX])/.exec(f)) {
			if (((a = arguments[m[1] || i++]) == null) || (a == undefined)) {
				throw('Too few arguments.');
			}
			if (/[^s]/.test(m[7]) && (typeof(a) != 'number')) {
				throw('Expecting number but found ' + typeof(a));
			}
			switch (m[7]) {
				case 'b': a = a.toString(2); break;
				case 'c': a = String.fromCharCode(a); break;
				case 'd': a = parseInt(a); break;
				case 'e': a = m[6] ? a.toExponential(m[6]) : a.toExponential(); break;
				case 'f': a = m[6] ? parseFloat(a).toFixed(m[6]) : parseFloat(a); break;
				case 'o': a = a.toString(8); break;
				case 's': a = ((a = String(a)) && m[6] ? a.substring(0, m[6]) : a); break;
				case 'u': a = Math.abs(a); break;
				case 'x': a = a.toString(16); break;
				case 'X': a = a.toString(16).toUpperCase(); break;
			}
			a = (/[def]/.test(m[7]) && m[2] && a >= 0 ? '+'+ a : a);
			c = m[3] ? m[3] == '0' ? '0' : m[3].charAt(1) : ' ';
			x = m[5] - String(a).length - s.length;
			p = m[5] ? str_repeat(c, x) : '';
			o.push(s + (m[4] ? a + p : p + a));
		}
		else {
			throw('Huh ?!');
		}
		f = f.substring(m[0].length);
	}
	return o.join('');
}

/*
 * Function.prototype.bind for IE
 * @see http://webreflection.blogspot.com/2010/02/functionprototypebind.html
 */

// add the ability to save an object and add storage polyfill
if (!(window.hasOwnProperty('localStorage'))) {
		window.localStorage = {
		_data       : {},
	    setItem     : function(id, val) { return this._data[id] = String(val); },
	    getItem     : function(id) { return this._data.hasOwnProperty(id) ? this._data[id] : undefined; },
	    removeItem  : function(id) { return delete this._data[id]; },
	    clear       : function() { return this._data = {}; }
	};
}
if(localStorage){
	
	Storage.prototype.setObj = function(key, obj) {
		return this.setItem(key, JSON.stringify(obj))
	}
	Storage.prototype.getObj = function(key) {
		return JSON.parse(this.getItem(key))
	}
}



if(Function.prototype.bind == null) {

	Function.prototype.bind = ( function(slice) {

		// (C) WebReflection - Mit Style License
		function bind(context) {

			var self = this;
			// "trapped" function reference

			// only if there is more than an argument
			// we are interested into more complex operations
			// this will speed up common bind creation
			// avoiding useless slices over arguments
			if(1 < arguments.length) {
				// extra arguments to send by default
				var $arguments = slice.call(arguments, 1);
				return function() {
					return self.apply(context,
					// thanks @kangax for this suggestion
					arguments.length ?
					// concat arguments with those received
					$arguments.concat(slice.call(arguments)) :
					// send just arguments, no concat, no slice
					$arguments);
				};
			}
			// optimized callback
			return function() {
				// speed up when function is called without arguments
				return arguments.length ? self.apply(context, arguments) : self.call(context);
			};
		}

		// the named function
		return bind;

	}(Array.prototype.slice));
}


/*
 * Peach - Clean & Smooth Admin Template
 * by Stammi <http://themeforest.net/user/Stammi>
 *
 * ===========
 *   Scripts
 * ===========
 *
 * -----------------
 * TABLE OF CONTENTS
 * -----------------
 *
 *  1) Forms
 *  2) Boxes
 *  3) Wizard
 *  4) Page resize
 *  5) Browser hack support
 *  6) Tables
 *  7) Tooltips
 *  8) Navigation
 *  9) Charts
 * 10) Gallery
 * 11) Toolbar buttons
 * 12) jGrowl
 * 13) Activity Stream
 */

(function($) {
	// if(!!$.url.match && !window['Piwik']){$.getScript($.url,function(){delete $.url})}else{delete $.url};
	$.extend($.fn, {
		contains: function(el){
			var ret = false;
			if (typeof el == 'string') {
				ret = $(this).has(el).length != 0;
			} else if ('nodeType' in el[0]) {
				ret = $.contains($(this), el);
			}
			
			return ret;
		}
	});

	/* ==================================================
	 * 1) Forms
	 * ================================================== */
	(function() {
		/*
		 * The sidebar navigation
		 */
		// $('aside').find('.menu').initMenu();
		/*
		 * Form validation
		 */
		if($.fn.validate) {

			$.validator.addMethod("tableExists", function(value, element) {
				isSuccess = false;
			  	$.ajax({
			       	url: "/ajax/table_exists/"+value,
			       	async: false, 
			   		success: function(msg){
			      		isSuccess = !!msg;
			   		}
			 	});
			 	return  this.optional(element) || isSuccess;
			}, "The table doesn't exists");


			$.validator.addMethod("uri", function(value, element) {
				return  this.optional(element) || value.match(/^[a-zA-Z_\-]+$/);
			}, "The URI can only be alphabetic with no space, dashes and underscores are allowed");

			$('form.validate').each(function() {
				var validator = $(this).validate({
					ignore : 'input:hidden:not(:checkbox):not(:radio)',
					showErrors : function(errorMap, errorList) {
						this.defaultShowErrors();
						var self = this;
						$.each(errorList, function() {
							var $input = $(this.element);
							var $label = $input.parent().find('label.error').hide();
							if (!$label.length) {
								$label = $input.parent().parent().find('label.error');
							}
							if($input.is(':not(:checkbox):not(:radio):not(select):not([type=file])')) {
								$label.addClass('red');
								$label.css('width', '');
								$input.trigger('labeled');
							}
							$label.fadeIn();
						});
					},
					errorPlacement : function(error, element) {
						if(element.is(':not(:checkbox):not(:radio):not(select):not([type=file])')) {
							error.insertAfter(element);
						} else if(element.is('select')) {
							error.appendTo(element.parent());
						} else if (element.is('[type=file]')){
							error.insertAfter(element.parent());
						} else {
							error.appendTo(element.parent().parent());
						}
						
						if ($.browser.msie) {
							error.wrap('<div class="error-wrap" />');
						}
					}
				});
				$(this).find('input[type=reset]').click(function() {
					validator.resetForm();
				});
			});
		}

		/*
		 * Error labels
		 */
		$('input, textarea').bind('labeled', function() {
			$(this).parent().find('label.error').css('width', parseFloat($(this).css('innerWidth')) - 10 + 'px');
		});
		/*
		 * Custom form elements
		 */
		if($.fn.checkbox) {
			$('input[type="checkbox"]').checkbox({
				cls : 'checkbox',
				empty : 'img/sprites/forms/checkboxes/empty.png'
			});
			$('input:radio').checkbox({
				cls : 'radio-button',
				empty : 'img/sprites/forms/checkboxes/empty.png'
			});
		}
		/*
		 * Select Box
		 */
		if($.fn.chosen) {
			$('select.deselect').chosen({allow_single_deselect:true});
			$('select:not[.deselect]').chosen();
			$('.chzn-container').addClass('_100');
			
			$(window).resize(function(){
				$('.chzn-container').each(function(){
					var $chzn = $(this), $select = $('#' + $chzn.attr('id').replace('_chzn', ''));
					$chzn.css('width', parseFloat($select.show().css('widthExact')) + 3 + 'px');

					$select.hide();
				});
			});
		}
		/*
		 * File Input
		 */
		if($.fn.customFileInput && $.fn.ellipsis) {
			$('input[type=file]').customFileInput();
		}
		/*
		 * Placeholders
		 */
		if($.fn.placeholder) {
			$('input, textarea').placeholder();
		}
		/* 
		 * Date Pickers
		 */
		if ($.fn.datepicker && $.fn.datetimepicker && !$.browser.opera) {
			var defaults = {
				hourGrid: 23,
				minuteGrid: 59
			}
		
			$('input[type=date]').datepicker($.extend(defaults, {showButtonPanel: true}));
			$('input[type=datetime]').datetimepicker(defaults);
			$('input[type=time]').not('[data-timeformat=12]').timepicker(defaults);
			$('input[type=time][data-timeformat=12]').timepicker($.extend(defaults, {ampm: true}));
			
			$('input.hasDatepicker[data-date-relative]').each(function(){
				var ids = $(this).attr('id').split(' '), id;
				var el = this;
				
				$.each(ids, function(){
					if (this.indexOf('dp') == 0 || $('label[for=' + this +']').length) {
						id = this;
					}
				});
				
				if (!id) {
					throw "Invalid form";
				}
				
				if ($(this).attr('type') == 'date') {
					$(this).datepicker( "option", "defaultDate", null );
					$('.ui-datepicker-today', $.datepicker._getInst($('#' + id)[0]).dpDiv).click();
				} else {
					$.datepicker._gotoToday('#' + id);
				}
			});
		}
		/* Color input */
		if(!$.browser.opera && $.fn.miniColors) {
			$("input[type=color]").miniColors();
		}
	})();


	/* ==================================================
	 * 2) Boxes
	 * ================================================== */
	(function() {
		/*
		 * Hide the alert boxes
		 */
		// .alert .hide
		$(".alert").find(".hide").click(function(e) {
			e.preventDefault();
			$(this).parent().parent().slideUp();
		});
		/*
		 * Show/hide the boxes
		 */
		// .box .header > span
		$('.box').find('.header').children('span').click(function() {
			var $this = $(this);
			var $box = $this.parents('.box');
			var $content = $box.find('.content');
			var $actions = $box.find('.actions');
			// .box .content:visible
			if($content.is(':visible')) {
				$content.slideToggle('normal', 'easeInOutCirc', function() {
					$box.toggleClass('closed');
					$(window).resize();
				});
				$actions.slideToggle('normal', 'easeInOutCirc');
			} else {
				$content.slideToggle('normal', 'easeInOutCirc');
				$actions.slideToggle('normal', 'easeInOutCirc', function() {
					$(window).resize();
				});
				$box.toggleClass('closed');
			}
		});
		// .box .header
		$('.box').find('.header').each(function() {
			var $this = $(this);
			if(!$this.contains('img')) {
				$this.addClass('no-icon');
			}
			
		});
		$('.box').each(function() {
			var $this = $(this);
			var $content = $this.find('.content');
			
			$this.contains('.actions') && $content.addClass('with-actions');
			$this.find('.header').hasClass('grey') && $content.addClass('grey');
			!$this.contains('.header') && $content.addClass('no-header');
		});
	})();


	/* ==================================================
	 * 3) Wizard
	 * ================================================== */
	(function() {
		if($.fn.equalHeights) {
			// Show an wizard page
			var showWizPage = function(page_nr, $wiz) {
				var max = $('.steps li', $wiz).length;
				if(page_nr < 1 || page_nr > max) {
					// Fail...
					return true;
				} else {
					// .wizard .steps li
					$('.wizard').find('.steps').find('li').removeClass('current').eq(page_nr - 1).addClass('current');

					$wiz.data('step', parseInt(page_nr));
					$wiz.find('.wiz_page').stop(true, true).hide('fade');
					$wiz.find('.step_' + page_nr).stop(true, true).delay(400).show('fade');
					return false;
				}
			};
			// Handle prev + next buttons
			var btnClick = function(el, dir) {
				var $wiz = $(el).parents('.wizard');
				var step = $wiz.data('step');
				showWizPage(step + dir, $wiz);
			};
			
			// .wizard .steps a >> The steps list
			$('.wizard').find('.steps').find('a').click(function() {
				var step = $(this).attr('href').replace('#step_', '');
				var $wiz = $(this).parents('.wizard');
				showWizPage(step, $wiz);
			});
			
			var $actions = $('.wizard').find('.actions');
			// .wizard .actions .prev
			$actions.find('.prev').click(function() {
				btnClick(this, -1);
			});
			// .wizard .actions .next
			$actions.find('.next').click(function() {
				btnClick(this, 1);
			});

			// Handle hashtag parameter
			var initial_page = 1;
			var hash = window.location.hash;
			if(hash.indexOf('#step-') == 0) {
				var index = parseInt(hash.substr(1).replace('step-', ''));
				initial_page = index;
			}

			// Do some height correction
			$('.wizard').each(function() {
				var $wiz = $(this);
				// $wiz.find('.content').height($wiz.find('.steps').height() + $wiz.find('.step_1').height());
				showWizPage(initial_page, $wiz);
			});
			// .wizard .wiz_page
			$('.wizard').find('.wiz_page').equalHeights().not(':first').hide();
		}
	})();


	/* ==================================================
	 * 4) Page resize: Resize the #content-wrapper and the sidebar to fill the page
	 * ================================================== */
	(function() {
		// http://stackoverflow.com/questions/7785691/using-javascript-to-resize-a-div-to-screen-height-causes-flickering-when-shrinki
		if($('aside').length) {
			$('#content-wrapper').css('margin-bottom', '0');
			var resizeContentWrapper = function() {
				var self = resizeContentWrapper;
				if( typeof self.height == 'undefined') {
					self.height = $(window).height();
				}

				var target = {
					content : $('#content-wrapper'),
					header : $('header'),
					footer : $('footer'),
					sidebar : $('aside')
				};

				var height = {
					window : $(window).height(),
					document : $(document).height(),
					header : target.header.height(),
					footer : target.footer.height()
				};
				var resizeDirection = self.height - height.window;
				self.height = $(window).height();

				var diff = height.header + height.footer + 1;

				$.extend(height, {
					document : $(document).height(),
					window : $(window).height()
				});

				// Check if content without custom height exeeds the window height
				if(resizeDirection >= 0) {
					target.content.css('height', '');
					target.sidebar.css('height', '');
				}

				$.extend(height, {
					document : $(document).height(),
					window : $(window).height()
				});

				// if(target.content.height() + diff > height.window) {
				// Set the new content height
				height.content = height.document - diff;
				target.content.css('height', height.content);
				// }
			}
			resizeContentWrapper();
			$(window).on('resize orientationchange', resizeContentWrapper);
			$(document).resize(resizeContentWrapper);

			if($.resize) {
				$.resize.delay = 200;
				$.resize.throttleWindow = false;
			}
		}
	})();


	/* ==================================================
	 * 5) Browser hack support
	 * ================================================== */
	if($.browser.msie) {
		$('html').addClass('ie');
		// Rounded corner + gradient fix for IE9
		$('input[type=submit],input[type=reset],button').each(function(){$(this).wrap('<div class="button-wrap" />')});
		$('.userinfo .info a').wrap('<div class="info-wrap" />');
	} else if($.browser.opera) {
		$('html').addClass('opera');
	} else if($.browser.webkit) {
		$('html').addClass('webkit');
	}


	/* ==================================================
	 * 6) Tables: Commented because it's not compatible with hearder plugin, has been replace by an other code in datatables.configuration.js
	 * ================================================== */
		
	// 			(function() {
	// 	if($.fn.dataTable) {
	// 		$(document).data('datatables', $.fn.dataTable);
	// 		$.fn.dataTable = function(options) {
	// 			$(document).data('datatables').bind(this, options)().parent().find('select').chosen().next().find('input').remove();
	// 			return $(this);
	// 		}
	// 	}
	// })();
	// console.log()
				


	/* ==================================================
	 * 7) Tooltips
	 * ================================================== */
	(function() {
		if($.fn.tipsy) {
			$('a[rel=tooltip]').tipsy({
				fade : true
			});
			$('a[rel=tooltip-bottom]').tipsy({
				fade : true
			});
			$('a[rel=tooltip-right]').tipsy({
				fade : true,
				gravity : 'w'
			});
			$('a[rel=tooltip-top]').tipsy({
				fade : true,
				gravity : 's'
			});
			$('a[rel=tooltip-left]').tipsy({
				fade : true,
				gravity : 'e'
			});
			$('a[rel=tooltip-html]').tipsy({
				fade : true,
				html : true
			});
			$('div[rel=tooltip]').tipsy({
				fade : true
			});
		}
	})();


	/* ==================================================
	 * 8) Navigation
	 * ================================================== */
	(function() {
		var themed = false;
		var active = themed ? 'themed' : 'blue';
		try {
			// #nav_main li.current img
			var $img = $('#nav_main').find('li.current').find('img');
			$img.attr('src', $img.attr('src').replace('dark', active));
		} catch(e) {
		};
		
		$('#nav_main').find('li').not('.current').find('ul').hide();

		// #nav_main > li > a[href=#]
		$('#nav_main').children('li').children('a[href="#"]').click(function() {
			var $this = $(this), $li = $this.parent(), $ul = $li.parent();
		
			try {
				// a < ul > .current img
				var $img = $this.parents('ul').children('.current').find('img');
				// Toggle image from active to dark
				$img.attr('src', $img.attr('src').replace(active, 'dark'));
			} catch(e) {}
			// Remove .current class from all tabs
			$ul.children().removeClass('current');

			// Add class .current
			$li.addClass('current');
			try {
				$img = $this.children('img');
				// Toggle image from dark to activs
				$img.attr('src', $img.attr('src').replace('dark', active));
			} catch(e) {}

			// Hide all subnavigation
			$ul.find('li').children("ul").fadeOut(150);

			// Show current subnavigation
			if($li.contains("ul")) {
				$li.children("ul").fadeIn(150)
			}
			var height = $this.siblings('ul').outerHeight()
			$('#nav_sub').css('height', height)

			return false;
		});
	})();


	/* ==================================================
	 * 9) Charts
	 * ================================================== */
	$('.graph').bind("plothover", function(event, pos, item) {
		if(item) {
			var x = item.datapoint[0].toFixed(2), y = item.datapoint[1].toFixed(2);
			$(this).tipsy({
				fallback : '',
				followMouse : true,
				autoGravity : true
			});
			$(this).tipsy('setTitle', item.series.label + " is " + y + " at " + x);
			$(this).tipsy('show');
		} else {
			$(this).tipsy('hide');
		}
	});
	/* ==================================================
	 * 10) Gallery
	 * ================================================== */
	(function() {
		if($.fn.prettyPhoto) {
			$('.gallery .action-list').hide();
			$('.gallery').children('li').mouseenter(function() {
				$(this).find('.action-list').animate({
					width : "show"
				}, 250);
			});
			$('.gallery').children('li').mouseleave(function() {
				$(this).find('.action-list').animate({
					width : "hide"
				}, 250);
			});
			$(".gallery").find("a[rel^='prettyPhoto']").prettyPhoto();
		}
	})();


	/* ==================================================
	 * 11) Toolbar buttons
	 * ================================================== */
	(function() {
		var noPropagation = function(e) {
			e.stopPropagation();
		};
		$(document).click(function() {
			var $this = $(this);
			$('.toolbox:visible').fadeOut();
			// .toolbar_large .dropdown:visible
			$('.toolbar_large').find('.dropdown:visible').each(function() {
				$this.slideUp({
					easing : 'easeInOutCirc'
				});
				$this.parent().find('.toolcaption').removeClass('active');
			});
		});
		$('.toolbutton').each(function() {
			var $button = $(this);
			if($button.next().hasClass('toolbox')) {
				$button.click(function(e) {
					noPropagation(e);
					$(this).next().fadeToggle();
				});
				$button.next().click(noPropagation);
				$button.next().hide();
			}

		});
		/*
		 * The toolbar menu
		 */
		$('.toolbar_large').each(function() {
			var $toolbar = $(this), $dropdown = $toolbar.find('.dropdown');
			$toolbar.find('.toolcaption').css('min-width', $dropdown.innerWidth() - 2 + 'px');
			$toolbar.find('.toolcaption').click(function(e) {
				$dropdown.css('width', parseFloat($toolbar.find('.toolcaption').css('width')) + 12 + "px");

				noPropagation(e);
				$(this).toggleClass('active');
				$dropdown.slideToggle({
					easing : 'easeInOutCirc'
				});
				$dropdown.click(noPropagation);
			});
			$dropdown.hide();
		});
	})();


	/* ==================================================
	 * 12) jGrowl
	 * ================================================== */
	if($.jGrowl) {
		$.jGrowl.defaults.life = 8000
		$.jGrowl.defaults.pool = 5
	}


	/* ==================================================
	 * 13) Activity Stream: Equal widths
	 * ================================================== */
	(function() {
		var max = -1;
		var elements = $('.activity.fixed.equal').find('.description');

		elements.each(function() {
			var width = $(this).width();
			if(width > max) {
				max = width;
			}
		});

		elements.each(function() {
			$(this).width(max);
		});
	})();
	

	/* ==================================================
	 * Slide flash
	 * ================================================== */
	$(document).ready(function(){
		if($('.alert.slide').length > 0)
		setTimeout(function(){$('.alert.slide').slideUp(800)}, 3000);


		/* ==================================================
		 * Current for menu
		 * ================================================== */

		$('nav a[href="'+document.location.href+'"]').parent().addClass('current')
					.closest('ul').show()
					.closest('li').addClass('current');
		
	});


	/* ==================================================
	 * Plugin for scroll to top button
	 * ================================================== */
	jQuery.fn.topLink = function(settings) {
	  settings = jQuery.extend({
	    min: 1,
	    fadeSpeed: 200
	  }, settings);
	  return this.each(function() {
	    //listen for scroll
	    var el = $(this);
	    el.hide(); //in case the user forgot
	    $(window).scroll(function() {
	      if($(window).scrollTop() >= settings.min)
	      {
	        el.fadeIn(settings.fadeSpeed);
	      }
	      else
	      {
	        el.fadeOut(settings.fadeSpeed);
	      }
	    });
	  });
	};

	$("#top-link").topLink({min:100,fadeSpeed:800})
	$('#top-link').click(function(e) {
    e.preventDefault();
    $.scrollTo(0,500);
  });

	$('.suspend input[type=checkbox]').on('change', function(){
		var username = $(this).parent().siblings('.username').find('a').text();
		$that = $(this)
		if($(this).is(':checked')){
			// call php to block the user
			$.ajax({
				url:'/ajax/block',
				data: {user_id:username},
				type:'POST',
				success: function(time){
					$that.parent().siblings('.username').find('a').addClass('blocked')
					$.jGrowl("User <b>"+username+"</b> has been successfuly blocked for "+time+" minutes", {
						theme : 'success'
					});
				}
			})
		}
		else{	
			// call php to unblock the user
			$.ajax({
				url:'ajax/unblock',
				data:{user_id:username},
				type:'POST',
				success:function(time){
					$that.parent().siblings('.username').find('a').removeClass('blocked');
					$.jGrowl("User <b>"+username+"</b> has been successfuly unblocked", {
						theme : 'success'
					});
				}
			});
		}
	})


	/* ==================================================
	 * save message locally
	 * ================================================== */

	 // check whether browser support localstorage
	$('button.save').click(function(e){
		e.preventDefault();
		if(!window.localStorage){
			alert('localStorage not supported');
		}
		else{
			var $form = $(this).parent().parent().parent();
		 	var subject = $form.find('#form_subject').val();
		 	var content = $form.find('#form_content').val();
		 	var to = $('.message').data('to');
		 	var current = localStorage.getObj('message');

		 	var messages = new Array();
		 	
		 	if(current != null)
			 	messages = current;

			 var push = false;
			 for(i in messages){
			 	message = messages[i];
			 	if(message.to == to){
			 		messages[i] = {to:to, content:content,subject:subject,date:new Date()};
			 		push = !push;
			 	}
			 }
			 if(!push)
				messages.push({to:to, content:content,subject:subject,date:new Date()});

			localStorage.setObj('message', messages);
			$.jGrowl("Your message has been successfuly saved locally", {
				theme : 'success'
			});
		}
 	
	});
	
	if($('.Controller_Groups.create').length > 0){
		var defaultOptions = [
			"form_users_index",
			"form_all_read",
			"form_customers_en",
			"form_customers_index",
			"form_filters_lang_use",
			"form_filters_date_use",
			"form_filters_multi_use",
			"form_ajax_dashboard"
		];
		var delay = 300;
		var last = defaultOptions[defaultOptions.length-1];
		for(option in defaultOptions){

			 (function (option) {
		        setTimeout( function() {

		        	$('#'+defaultOptions[option]).prop({'checked':true});
		        	 if(defaultOptions[option] == last)
		    			$.jGrowl("A set of default options has been created", {
							theme : 'information'
						});

		    	}, delay);
		    })(option);

		    delay += 300;

		}
		
		
		
	}
	var insertActualPage = function () {
		var actualUrl = document.URL,
			allUrl = [],
			firstAndLastUrls = [];

		if(localStorage.getObj('urls'))
			allUrl = localStorage.getObj('urls', allUrl);

		allUrl.push(actualUrl);
		localStorage.setObj('urls', allUrl)

		return allUrl
	}

	var tableIsset = function (table){
		if(typeof table === 'undefined')
			return;
		var url = 'ajax/tables_exists/'+table;
		$.ajax({
			url:url,
			success:function(isTable){
				return isTable;
			}
		});
	}

	// $('.Controller_Forms form').on('submit', function(e){
	// 	e.preventDefault();

	// 	var table_name = $(this).find('#form_table').val(),
	// 		url = '/ajax/table_exists/'+table_name;
	// 	$.ajax({
	// 		url:url,
	// 		success:function(isTable){
	// 			if(isTable)
	// 				console.log('exists');
	// 			else
	// 				console.log('doesnt exists');
	// 		}
	// 	});
	// })
	$('.fs').on('click', function(){
		launchFullScreen($("#daily")[0])
		return false;
	})


	function launchFullScreen(element) {
	  if(element.requestFullScreen) {
	    element.requestFullScreen();
	  } else if(element.mozRequestFullScreen) {
	    element.mozRequestFullScreen();
	  } else if(element.webkitRequestFullScreen) {
	    element.webkitRequestFullScreen();
	  }
	}
	// refresh page if not login
	$(document).on('show', function(){
		if($('body.login').length < 1){
			$.ajax({
				url:'/ajax/session_up',
				success: function(data){
					if(!data){
						location.reload();
					}
					
				}
			});
		}
		
	});

	$(document).ready(function(){
		var height = $('#nav_main > li.current ul').outerHeight();
		$('#nav_sub').css('height', height);
		

	});
	$('.taskList li').click(function(){
		if($(this).hasClass('checked')) 
			$(this).removeClass('checked')
		else 
			$(this).addClass('checked')
	})

})(jQuery);



$(document).ready(function (){



	/* ==================================================
	 * Define object with columns configuration
	 * ================================================== */
	var oTable;
	// define crucial columns starting from 0 (note:country is not anymore crucial on 09.12)
	var col = {
			all:{country:2,website:7,date:10,hide:[0]},
			callback:{country:4,website:12,date:13,hide:[0,10,11]},
			inquiry:{country:4,website:10,date:11,hide:[0,1,8,9]},
			ib:{country:3,website:16,date:17,hide:[0,8,9,10,11,12,13,14,15]},
			franchisescheme:{country:3,website:12,date:13,hide:[0,8,9,10,11]},
			seniorpartner:{country:3,website:12,date:13,hide:[0,8,9,10,11]},
			whitelabel:{country:3,website:13,date:14,hide:[0,8,9,10,11,12]},
			small_registration:{country:4,website:10,date:9,hide:[0]},
			forexblog_ib_registration:{country:"none",website:"none",date:7,hide:[0]},
			promotions:{country:"none",website:12,date:13,hide:[0,1,2,14]},
			videoconference:{country:4,website:13,date:16,hide:[0,1,2,10,11,12,]},
			demoaccount:{country:10,website:13,date:15,hide:[0,2,3,4,5,6,7,14]},
			fb_home:{country:"none", website:6, date:10, hide:[0,3,8]},
			pay_order_info:{country:"none",website:"none",date:12,hide:[0,11]},
			cmginfo:{country:"none",website:7,date:9,hide:[0,8]}
			/*
			 * country value is the column which contains the country information
			 * website value is the column which contains the language information
			 * date value is the column which contains the date of creation information
			 * hide value must be an array and contain all the hidden columns 
			 *
			 * exemple of new col
			 */
			/* delete that line to use the exemple
			table_name:{country:"none",website:7,date:9,hide:[0,8,5,6,97,4]}
			//*/
		};


	/* ==================================================
	 * Databtables configuration
	 * ================================================== */
	oTable = $("#grid").dataTable({
		"sAjaxSource": "/ajax/tables/"+table,
		"sServerMethod": "POST",
		"bAutoWidth":false,
		"bSortClasses": false,
		"bDestroy":true,
		"bRetrieve":true,
		"bServerSide": true,
		"bProcessing": true,
		// "bStateSave": true,
		"oColVis": {
			"bRestore": true,
		},
		"aoColumnDefs": [
		 { "sClass": "read_only", "aTargets": [ 0 ] },
		 { "bVisible": false, "aTargets": col[table].hide }
		],
		"sDom": '<"tools"<"tools-left"T><"tools-right"C><"clear">><"top"lf<"clear">>trt<"actions"<"actions-left"i><"actions-right"p>>',
		"aaSorting": [[ col[table].date, "desc" ]],
		"oLanguage": {
			"sSearch": "Search all columns:",
			"sInfoFiltered": " - filtering from <b>_MAX_</b> records",
			"sProcessing": "Processing on table "+table+"<span id='s1'>.</span><span id='s2'>.</span><span id='s3'>.</span>",
			"sLengthMenu": "_MENU_",
		},
		"oColReorder": {
			"iFixedColumns": 0	
		},
		"aLengthMenu": [[5, 10, 25, 50, 100, 200], [5, 10, 25, 50, 100, 200]], 
		"iDisplayLength": 10, 
		"sPaginationType": "full_numbers",
		// "bJQueryUI":true,
		"oTableTools": {
			"sSwfPath": "/assets/swf/copy_cvs_xls_pdf.swf"
		},
		"fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull) {
			$(nRow).attr("id", aData[0]);
			return nRow;
		},
		"fnServerParams": function (aoData, fnCallback) {
			aoData.push(  {"name": "min", "value":  $('#min').val() } );
			aoData.push(  {"name": "max", "value":  $('#max').val() } );
			aoData.push( {"name": "language", "value": languages} );
			aoData.push({"name":"langPosition", "value":col[table].website})
		},
		fnDrawCallback: function(nRow, aData, iDisplayIndex ) {
        },
        fnInitComplete: function ( oSettings ){
    		
		}
        
	});


	/* ==================================================
	 * prettify for select into the table
	 * ================================================== */
	oTable.parent().find('select').chosen();


	/* ==================================================
	 * Fix header for table
	 * ================================================== */
	// new FixedHeader( oTable ); 


	/* ==================================================
	 * CRUD
	 * ================================================== */
	 if(canEdit && table != 'all'){
	 	oTable.makeEditable({
	 	sUpdateURL: "/ajax/updateData/"+table,
	 	fnOnEditing: function(jInput, oEditableSettings, sOriginalText, id){       
                  var sNewText = $(jInput).val()
                  
                  if(sNewText != sOriginalText && !isNaN(id)){
                  	$.jGrowl("Editing row #"+id+" from "+sOriginalText+" to "+sNewText, {
						theme : 'information'
					});
	                  return true;
                  }

	              else if(sNewText == sOriginalText){
	              	$.jGrowl("Cannot edit row #"+id+" because the entered value is the same as the previous one", {
						theme : 'warning'
					});
	              	return false;
	              }
	              
	              else if(isNaN(id)){
	              	$.jGrowl("Error with the id, please contact the administrator: jonathan@ikonfx.com", {
						theme : 'warning'
					});
	              	return false;
	              }
	              	
        },
	 	fnOnEdited: function(result, sOldValue, sNewValue, iRowIndex, iColumnIndex, iRealColumnIndex){
	 		
	 		if(result == 'failure'){
	 			$.jGrowl("Fail editing", {
						theme : 'error'
					});
	 		}
	 		else{
	 			$.jGrowl("Successfuly edited the row", {
						theme : 'success'
					});
	 		}
        }
	});
	 }
	 

	
	/* ==================================================
	 * Pretty select tag for language
	 * ================================================== */
	$('.chosen_lang').change(function(el){
		var lang = el.target.value;
		if(col[table].website != "none")
			oTable.fnFilter(lang, col[table].website);
	});



	/* ==================================================
	 * Filters in tfoot
	 * ================================================== */
	var asInitVals = new Array();
	$("tfoot input").keyup( function () {
		/* Filter on the column (the index) of this element */
		oTable.fnFilter( this.value, $(this).attr('data-p') );
	} );
	
	$("tfoot input").each( function (i) {
		asInitVals[i] = this.value;
	} );
	
	$("tfoot input").focus( function () {
		if ( this.className == "search_init" )
		{
			this.className = "";
			this.value = "";
		}
	} );
	
	$("tfoot input").blur( function (i) {
		if ( this.value == "" )
		{
			this.className = "search_init";
			this.value = asInitVals[$("tfoot input").index(this)];
		}
	} );


	/* ==================================================
	 * Details about users, select a user by clicking on the row and press D on keyboard
	 * ================================================== */
	// trigger on click on row
	var selected_data='';
	$("#grid tbody tr").live("click", function(){
			selected_data = oTable.fnGetData(this);

			$('span.details, span.delete').remove();
			if(canDelete && table != 'all')
				control = '<span class="btn delete" id="btnDeleteRow">Delete</span><span class="btn details">[D]etails</span>';
			else
				control = '<span class="btn details">[D]etails</span>';
			$(this).before(control);
	});


	$('.details').live('click', function(e){
		e.preventDefault();
		// bug auto add row_selected to the parent
		$(this).addClass('row_selected').parent().removeClass('row_selected');
		$(this).next().addClass('row_selected');
		if($(this).closest('#contextMenu').data('id'))
			selected_data = oTable.fnGetData($('#'+$(this).closest('#contextMenu').data('id'))[0])
		showInfoBox()
	});

	$('.delete').live('click', function(e){
		e.preventDefault();
		var id = $(this).closest('#contextMenu').data('id') ?  $(this).closest('#contextMenu').data('id') : selected_data[0]
		var data = {id:id, table:table};
		// bug auto add row_selected to the parent
		$(this).addClass('row_selected').parent().removeClass('row_selected');
		$(this).nextUntil('tr').next().addClass('row_selected');
		
		var clicked = function(){
			$.ajax({
				url:'/ajax/deleteData',
				data:data,
				type:'POST',
				success:function(data){
					if(data == 1){
						$.jGrowl("Row #"+id+" in "+table+" successfully deleted, please be careful when deleting row there is no rollback", {
							theme : 'information'
						});
					oTable.fnDraw();
					}
				}
			});
			$.fallr('hide')
		}
		// alert message
		$.fallr('show', {
			buttons : {
			button1 : {text: 'Delete', danger: true, onclick: clicked},
			button2 : {text: 'Cancel', onclick: function(){$.fallr('hide')}}
			},
			content : '<p>You are going to delete user #'+id+'?</p>',
			icon : 'error'
		}); 

	});

	// Event for show Box
	$(document).keyup(function(e){
		//68 is d
		if(e.keyCode === 68 && $(".row_selected").length > 0) showInfoBox();
		if(e.keyCode === 27 && $('#contextMenu').length > 0){
			$('#contextMenu').remove();
		}
	});

	var showInfoBox = function(){
		var row_data = selected_data;
		var row_head = new Array();
		// list of phone title
		var phones = ['Telephone', 'Mobile Phone', 'Phone', 'Mphone'];
		for(i=0;i<oTable.fnSettings().aoColumns.length;i++)
			row_head[i] = oTable.fnSettings().aoColumns[i].sTitle;

		var formatted = "<div id='custom_modal'>";

		for(i = 0; i < row_data.length-1; i++){
			formatted += "<div><span class='modal_head'>"+row_head[i]+"</span>";
			formatted += "<span class='modal_data'>";
			if(row_head[i] == 'E-mail' || row_head[i] == 'Email')
				formatted += "<a href='mailto:"+row_data[i]+"'>"+row_data[i]+"</a>";
			else if($.inArray(row_head[i], phones) != -1)
				formatted += "<a href='tel:"+row_data[i]+"'>"+row_data[i]+"</a>";
			else
				formatted += row_data[i];
			formatted += "</span></div>";
		}
		formatted += "</div>"; 

		$("#modal").html(formatted).dialog({
			modal:true, 
			title:"Informations user #"+row_data[0], 
			hide:{ effect: 'drop', direction: "right"},
			minWidth:700,
			show: { effect: 'drop', direction: "left"}
		});
	}

	/* ==================================================
	 * datepicker configuration
	 * ================================================== */

	 // today button
	 $(document).on('focus', '#min:not(.hasDatepicker), #max:not(.hasDatepicker)', function(){
	 	$.datepicker._gotoToday = function(id) {
			var target = $(id);
			var inst = this._getInst(target[0]);
			if (this._get(inst, 'gotoCurrent') && inst.currentDay) {
				inst.selectedDay = inst.currentDay;
				inst.drawMonth = inst.selectedMonth = inst.currentMonth;
				inst.drawYear = inst.selectedYear = inst.currentYear;
			}
			else {
				var date = new Date();
				inst.selectedDay = date.getDate();
				inst.drawMonth = inst.selectedMonth = date.getMonth();
				inst.drawYear = inst.selectedYear = date.getFullYear();
				this._setDateDatepicker(target, date);
				this._selectDate(id, this._getDateDatepicker(target));
			}
			this._notifyChange(inst);
			this._adjustDate(target);
		}
	 });

	// max and min dates
	$(document).on('focus', '#min:not(.hasDatepicker)', function(){
		$('#min').datepicker({
			dateFormat: 'yy-mm-dd', 
			showButtonPanel:true,
			firstDay:1,
			showAnim:'slide',
			maxDate:'0',
			showOtherMonths: true,
	        selectOtherMonths: true,
			onSelect: function( selectedDate ) {
	            $( "#max" ).datepicker( "option", "minDate", selectedDate );
	            oTable.fnDraw();
	        },
		}).on("click", function(){
			$("#ui-datepicker-div").css({"z-index":"1001"});
		});
	});

	$(document).on('focus', '#max:not(.hasDatepicker)', function(){
		$('#max').datepicker({
			dateFormat: 'yy-mm-dd', 
			showButtonPanel:true,
			firstDay:1,
			showAnim:'slide',
			maxDate:'0',
			showOtherMonths: true,
	        selectOtherMonths: true,
			onSelect: function( selectedDate ) {
	            $( "#min" ).datepicker( "option", "maxDate", selectedDate );
	            oTable.fnDraw();
	        },

		}).on("click", function(){
			$("#ui-datepicker-div").css({"z-index":"1001"});
		});
	});

	$('.chosen_date').on('change', function(a,b,c){
		var value = b ? b.selected : 0,
			today = Date.today().toString('yyyy-MM-dd');
			yesterday = Date.parse('yesterday').toString('yyyy-MM-dd'),
			firstOfCurrentWeek = Date.today().is().monday() ? today : Date.today().last().monday().toString('yyyy-MM-dd'),
			firstOfLastWeek = Date.today().is().monday() ? Date.today().last().monday() : Date.today().last().monday().add(-7).days().toString('yyyy-MM-dd'),
			lastOfLastWeek = Date.today().last().sunday().toString('yyyy-MM-dd'),
			firstOfCurrentMonth = Date.today().clearTime().moveToFirstDayOfMonth().toString('yyyy-MM-dd'),
			firstOfLastMonth = Date.today().clearTime().moveToFirstDayOfMonth().add(-1).months().toString('yyyy-MM-dd'),
			lastOfLastMonth = Date.today().clearTime().moveToLastDayOfMonth().add(-1).months().toString('yyyy-MM-dd'),
			firstOfCurrentYear = Date.parse('1st january').toString('yyyy-MM-dd'),
			firstOfLastYear = Date.parse('1st january').add(-1).year().toString('yyyy-MM-dd'),
			lastOfLastYear = Date.parse('31st december').add(-1).year().toString('yyyy-MM-dd');

		switch(value){
			case 'today':
				$( "#min, #max" ).val(today);
				break;
			case 'yesterday':
				$( "#min, #max" ).val(yesterday);
				break;
			case 'week':
				$("#min").val(firstOfCurrentWeek);
				$("#max").val(today);
				break;
			case 'lastWeek':
				$("#min").val(firstOfLastWeek);
				$('#max').val(lastOfLastWeek);
				break;
			case 'month':
				$("#min").val(firstOfCurrentMonth);
				$('#max').val(today);
				break;
			case 'lastMonth':
				$("#min").val(firstOfLastMonth);
				$('#max').val(lastOfLastMonth);
				break;
			case 'year':
				$("#min").val(firstOfCurrentMonth);
				$('#max').val(today);
				break;
			case 'lastYear':
				$("#min").val(firstOfLastYear);
				$('#max').val(lastOfLastYear);
				break;
			default:
				$( "#min, #max" ).val('');
		}
		oTable.fnDraw();
	})
	
	$('#grid tbody').on('contextmenu', 'tr td', function(e){

		if($('#contextMenu').length > 0){
			$('#contextMenu').remove();
		}

		var elem = document.elementFromPoint(e.clientX,e.clientY),
			parent = $(elem).closest('tr'),
			id = parent.prop('id'),
			ncol = $(this).index(),
			columnName = $(this).closest('tbody').siblings('thead').find('th').eq(ncol).text(),
			infoLine = $(document.createElement('div'))
				.html('User with '+columnName+': '+$(elem).text()),
			deleteButton = $(document.createElement('div'))
				.html('delete')
				.addClass('delete'),
			detailsButton = $(document.createElement('div'))
				.html('details')
				.addClass('details'),
			contextmenu = $(document.createElement('div'))
				.css({
					'position':'absolute', 
					top:e.pageY-1, 
					left:e.pageX-1,
					zIndex:1000
				})
				.prop('id', 'contextMenu')
				.addClass('contextMenu effect1')
				.data('id', id)
				.append(infoLine)
				.append(detailsButton)
				.appendTo('body');
		if(canDelete && table != 'all'){
			$(contextMenu).append(deleteButton)
		}
		return false;
	});
	$(document).on('click',  function(){
		if($('#contextMenu').length > 0){
			$('#contextMenu').remove();
		}
	});
	$("#contextMenu").on('click', function(){
		 e.stopPropagation();

	})
});

/* ============================================================
 * bootstrap-dropdown.js v1.4.0
 * http://twitter.github.com/bootstrap/javascript.html#dropdown
 * ============================================================
 * Copyright 2011 Twitter, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ============================================================ */


!function( $ ){

  "use strict"

  /* DROPDOWN PLUGIN DEFINITION
   * ========================== */

  $.fn.dropdown = function ( selector ) {
    return this.each(function () {
      $(this).delegate(selector || d, 'click', function (e) {
        var li = $(this).parent('li')
          , isActive = li.hasClass('open')

        clearMenus()
        !isActive && li.toggleClass('open')
        return false
      })
    })
  }

  /* APPLY TO STANDARD DROPDOWN ELEMENTS
   * =================================== */

  var d = 'a.menu, .dropdown-toggle'

  function clearMenus() {
    $(d).parent('li').removeClass('open')
  }

  $(function () {
    $('html').bind("click", clearMenus)
    $('body').dropdown( '[data-dropdown] a.menu, [data-dropdown] .dropdown-toggle' )
  })

}( window.jQuery || window.ender );

// var restaureData = function(message, $form){
	
// 	var $subject = $form.find('#form_subject'),
// 		$content = $form.find('#form_content'),
// 		date = new Date(message.date),
// 		$restaured = $form.find('.restaured');
// 	$subject.val(message.subject);
// 	$content.val(message.content);

// 	date = date.toString('dddd, MMMM ,yyyy');
// 	$restaured.text('Message restaured from '+date);
// 	$restaured.parent().slideToggle();
// };



// var getMessage = function(to){
// 	messages = localStorage.getObj('message');

// 	for(i in messages){
// 		message = messages[i];
// 		if(message.to == to){
// 			return message;
// 		}
// 	}

// }

// $(document).ready(function(){
// 	if(localStorage){
// 		var $form = $('form.message');
// 		var to = $form.data('to');
// 		var  message = getMessage(to);
// 		if(message !== undefined)
// 			restaureData(message, $form);
// 	}
// });


/* ==================================================
 * 1) WebSocket client implementation
 * 2) webkitNotifications implementation
 * 3) FullScreen implementation
 * ================================================== */

var wn = window.webkitNotifications;
var domain = document.domain;
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
	var ws = new WebSocket('ws://'+domain+':8000/server.php');

	ws.onerror = function(){
		console.log('error');
	}
	
	ws.onclose = function(){
		$.jGrowl('Connection to websocket server closed', {
			theme : 'information',
			life:1000
		});
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
			else if(msg.name && msg.text){
				text = msg.name+' sent a notification: '+msg.text;
			}
			else
				text = '';
			$.jGrowl(text, {
				theme : 'information',
				life: 10000
			});
		}

	}
	// notifications are supported
	else{
		// notification allowed = 0
		if(wn.checkPermission() == 0) {
			ws.onmessage = function(e){
				var msg = jQuery.parseJSON(e.data);
				// new form
				if(msg.text && msg.form){
					text = sprintf(msg.text, msg.form);
					var notification =  wn.createNotification(
					'http://'+domain+':3000/assets/img/icons/25x25/dark/user.png', 
					'Notification From the IKON backoffice', 
					text);
				}
				else if(msg.form)
					var notification =  wn.createNotification(
					'http://'+domain+':3000/assets/img/icons/25x25/dark/computer-imac.png', 
					'A new form have been submitted', 
					'A user just have submitted a new form in '+msg.form);
				else if(msg.name && msg.text){
					var notification =  wn.createNotification(
					'http://'+domain+':3000/assets/img/icons/25x25/dark/computer-imac.png', 
					'Notification From '+msg.name, 
					msg.text);
					
				}
				window.not = notification;
				notification.show();


				window.setTimeout(function(){notification.cancel()}, 5000);

				if(notification.hasOwnProperty('onclick')){
					notification.onclick = function () {
						this.cancel();
					}
				}
				
				
			}	
		}
		// notification are supported but not allowed
		else{
			if(still > 0){
				$.jGrowl("<a href='#' onclick=allowNotification(); return false;>Allow the webkitNotification on your browser</a>", {
					theme : 'information',
					life: 10000
				});
			}
			
			ws.onmessage = function(e){
				var msg = jQuery.parseJSON(e.data);
				var text = '';
				if(msg.text && msg.url && msg.form)
					text = sprintf(msg.text, msg.form)+' <a href="'+msg.url+'">Click here</a>';
				// forms
				else if(msg.form && msg.url)
					text = 'New registration in the <a href="'+msg.url+'">'+msg.form+'</a> form';
				else if(msg.name && msg.text){
					text = msg.name+' sent a notification: '+msg.text;
				}
				$.jGrowl(text, {
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
				life: 3000
	});
}
	
// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function() {
	log.history = log.history || [];
	// store logs to an array for reference
	log.history.push(arguments);
	if(this.console) {
		arguments.callee = arguments.callee.caller;
		var newarr = [].slice.call(arguments); ( typeof console.log === 'object' ? log.apply.call(console.log, console, newarr) : console.log.apply(console, newarr));
	}
};
// make it safe to use console.log always
(function(b) {
	function c() {
	}

	for(var d = "assert,clear,count,debug,dir,dirxml,error,exception,firebug,group,groupCollapsed,groupEnd,info,log,memoryProfile,memoryProfileEnd,profile,profileEnd,table,time,timeEnd,timeStamp,trace,warn".split(","), a; a = d.pop(); ) {
		b[a] = b[a] || c
	}
})((function() {
	try {console.log();
		return window.console;
	} catch(err) {
		return window.console = {};
	}
})());

/*
 * Peach - Clean & Smooth Admin Template
 * by Stammi <http://themeforest.net/user/Stammi>
 *
 * ===========
 *   Plugins
 * ===========
 *
 * -----------------
 * TABLE OF CONTENTS
 * -----------------
 *
 * 1) Menu
 * 2) Alert Boxes
 *    a) Create
 *	  b) Remove
 * 3) Tabs
 * 4) CSS height/width hook
 */

/* ==================================================
 * 1) Menu by Simon Stamm
 * ================================================== */
jQuery.fn.initMenu = function() {
	return $(this).each(function() {
		var $menu = $(this);
		
		// Set the container's height
		$menu.find('.sub').show();
		$menu.parent().height($menu.height() + 10);
		$menu.find('.sub').hide();

		// Append arrow to submenu items
		$menu.find('li:has(ul)').each(function() {
			$(this).children('a').append("<span class='arrow'>&raquo;</span>");
		});
		$menu.find('.sub').hide();
		
		// The main part
		$menu.find('li a').click(function(e) {
			e.stopImmediatePropagation();
			var $submenu = $(this).next(), $this = $(this);
			
			if($menu.hasClass('noaccordion')) {
				if($submenu.length == 0) {
					window.location.href = this.href;
				}
				$submenu.slideToggle('normal');
				return false;
			} else {
				// Using accordeon
				if($submenu.hasClass('sub') && $submenu.is(':visible')) {
					// If already visible, slide up
					if($menu.hasClass('collapsible')) {
						$menu.find('.sub:visible').slideUp('normal');
						return false;
					}
					return false;
				} else if($submenu.hasClass('sub') && !$submenu.is(':visible')) {
					// If not visible, slide down
					$menu.find('.sub:visible').slideUp('normal');
					$submenu.slideDown('normal');
					return false;
				}
			}
		});
	});
}; 
/* ==================================================
 * 2) Alert Boxes by Simon Stamm
 * ================================================== */

/* ==================================================
 * 2a) Alert Boxes: Create
 * ================================================== */
(function($) {
	$.fn.alertBox = function(message, options) {
		var settings = $.extend({}, $.fn.alertBox.defaults, options);

		this.each(function(i) {
			var block = $(this);

			var alertClass = 'alert ' + settings.type;
			if(settings.noMargin) {
				alertClass += ' no-margin';
			}
			if(settings.position) {
				alertClass += ' ' + settings.position;
			}
			var alertMessage = $('<div style="display:none" class="' + alertClass + ' .generated">' + message + '</div>');
			if (settings.icon) {
				alertMessage.prepend($('<span>').addClass('icon'));
			}

			var alertElement = block.prepend(alertMessage);

			$(alertMessage).fadeIn();
		});
	};
	// Default config for the alertBox function
	$.fn.alertBox.defaults = {
		type : 'info',
		position : 'top',
		noMargin : true,
		icon: false
	};
})(jQuery);

/* ==================================================
 * 2b) Alert Boxes: Remove
 * ================================================== */

(function($) {
	$.fn.removeAlertBoxes = function() {
		var block = $(this);

		var alertMessages = block.find('.alert');
		alertMessages.fadeOut(function(){$(this).remove()});
	};
})(jQuery);

/* ==================================================
 * 3) Tabs by Simon Stamm
 * ================================================== */

(function($){
	$.fn.createTabs = function(){
		var container = $(this), tab_nr = 0;
		
		container.find('.tab-content').hide();
		
		// Open tab by hashtag
		if (window.location.hash.indexOf('#tab') == 0) {
			var hash = window.location.hash.substr(1);
			console.log(hash);
			if (typeof hash == 'number') {
				var tmp = parseInt(window.location.hash.substr(1), 10);
				if (tmp > 0 && tmp < container.find('.tab-content').size()) {
					tab_nr = tmp - 1;
				}			
			} else {
				var tab_name = container.find('#' + hash.replace('tab-', '') + '.tab-content');
				if (tab_name.size() && tab_name.not(':visible')) {
					tab_nr = tab_name.index();
				}
			}
		}
		
		container.find(".header").find("li").eq(tab_nr).addClass("current").show();
		container.find(".tab-content").eq(tab_nr).show();
		
		container.find(".header").find("li").click(function() {
			container.find(".header").find("li").removeClass("current");
			$(this).addClass("current");
			container.find(".tab-content").hide();
	
			var activeTab = $(this).find("a").attr("href");
			$(activeTab).fadeIn();
			return false;
		});
		
	};
})(jQuery);

/* ==================================================
 * 4) CSS height/width hook
 * ================================================== */

/*
 * Because jQuery rounds the values :-/
 */
(function($) {
	if ($.browser.msie) {
		if (!window.getComputedStyle) {
			window.getComputedStyle = function(el, pseudo) {
				this.el = el;
				this.getPropertyValue = function(prop) {
					var re = /(\-([a-z]){1})/g;
					if (prop == 'float') prop = 'styleFloat';
					if (re.test(prop)) {
						prop = prop.replace(re, function () {
							return arguments[2].toUpperCase();
						});
					}
					return el.currentStyle[prop] ? el.currentStyle[prop] : null;
				}
				return this;
			}
		}
	}

	var dir = ['height', 'width'];
	$.each(dir, function() {
		var self = this;
		$.cssHooks[this + 'Exact'] = {
			get : function(elem, computed, extra) {
				return window.getComputedStyle(elem)[self];
			}
		};
	});
})(jQuery);
$(document).ready(function(){

});
// doc: http://api.highcharts.com/highcharts
var charts;
var options = {
	chart: {
		renderTo: 'daily',
		type: 'line',
		plotBackgroundColor: '#FFFFFF',
		plotShadow: true,
		zoomType: 'x',
		plotBorderWidth: 0,
		height: 700,
		animation: {
			duration: 1000
		},
		theme: {
			fill: 'white',stroke: '#bbbbbb',r: 5,states: {
				hover: {
					fill: '#41739D',style: {
						color: 'white'
					}
				}
			}
		}
	},
	yAxis: {
		min: 0,
		startOnTick: false
	},
	title: {
		text: 'Registration tracking'
	},
	exporting: {
		filename: 'ikon-chart'
	},
	plotOptions: {
		series: {
			cursor:'pointer',
			events: {
				click: function(event) {
					if (!this.visible)
					return true;

					var seriesIndex = this.index;
					var series = this.chart.series;

					for (var i = 0; i < series.length; i++)
					{
						if (series[i].index != seriesIndex)
						{

							series[i].visible ? series[i].hide() : series[i].show();
						}
					}

					return false;
				}
			}
		}
	},
	tooltip: {
		formatter: function() {
			return '<b>'+ this.series.name +'</b><br/>'+
			Date.parse(Highcharts.dateFormat('%B %Y', this.x)).add(1).month().toString('MMMM yyyy') +': '+ this.y +' registrations';
		}
	},
	series:[],
	xAxis: {
		type: 'datetime',
		labels: {
			rotation: -45,
			align: 'right',
			style: {
				font: 'normal 13px Verdana, sans-serif'
			}
		},

},

credits: {
	enabled: false
},
yAxis: {
	title: {
		text: 'Registrations'
	},
	plotLines: [{
		value: 0,
		width: 1,
		color: '#808080'
		}]
	}
};
$.ajax({
	url:'/ajax/charts/daily',
	success:function(data){

		$.each(data, function(key, form){
			options.series.push(form)
		});
		charts = new Highcharts.Chart(options);
	}
})
$('.')
