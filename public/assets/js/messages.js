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

