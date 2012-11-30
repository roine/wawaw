$(window).load(function(){
			/*
			 * Validate the form when it is submitted
			 */
	var validatelogin = $("form").validate({
		invalidHandler: function(form, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
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
					var $input = $(this.element);
					var $label = $input.parent().find('label.error').hide();
					$label.addClass('red');
					$label.css('width', '');
					$input.trigger('labeled');
					$label.fadeIn();
				});
		}
	});

	$('.beforeLoading').removeClass('beforeLoading');
	$('.enrolled').removeClass('enrolled').addClass('derolled')
});
$('form').submit(function(){
	var username = $(this).find('#username').val();
	var password = $(this).find('#password').val();
	$.ajax({
		url:'ajax/login',
		data:{username: username, password: password},
		type:'POST',
		success: function (data) {
			$.ajax({
				url:'/',
				success:function(data){
					$('body').animate({top:'-'+document.height}, 'slow', function(){

						document.write(data);
						$('body').css('top', 1000).animate({top: 0}, 'slow', function(){
							history.pushState(null, null, '/')
						})
						
					})
					
				}
			})

		}
	});
	return false;
})
