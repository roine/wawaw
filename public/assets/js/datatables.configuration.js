$(document).ready(function (){



	/* ==================================================
	 * Define object with columns configuration
	 * ================================================== */
	var oTable;
	// define crucial columns (note:country is not anymore crucial on 09.12)
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
			promotions:{country:"none",website:"none",date:13,hide:[0,1,2,14]},
			videoconference:{country:4,website:13,date:16,hide:[0,1,2,10,11,12,]},
			demoaccount:{country:10,website:13,date:15,hide:[0,2,3,4,5,6,7,14]},
			fb_home:{country:"none", website:"none", date:10, hide:[0,3,8]},
			pay_order_info:{country:"none",website:"none",date:12,hide:[0,11]},
			cmginfo:{country:"none",website:"none",date:9,hide:[0,8]}

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
		"fnServerParams": function ( aoData ) {
			aoData.push( { "name": "more_data", "value": "my_value" } );
		},
		"aoColumnDefs": [
		 { "sClass": "read_only", "aTargets": [ 0 ] },
		 { "bVisible": false, "aTargets": col[table].hide }
		],
		"sDom": '<"tools"<"tools-left"T><"tools-right"C><"clear">><"top"lf<"clear">>trt<"actions"<"actions-left"i><"actions-right"p>>',
		"aaSorting": [[ col[table].date, "desc" ]],
		"oLanguage": {
			"sSearch": "Search all columns:",
			"sProcessing": "Processing on table "+table+"<span id='s1'>.</span><span id='s2'>.</span><span id='s3'>.</span>",
			"sLengthMenu": "_MENU_",
		},
		"oColReorder": {
			"iFixedColumns": 0	
		},
		"aLengthMenu": [[5, 10, 25, 50, 100, 200], [5, 10, 25, 50, 100, 200]], 
		"iDisplayLength": 25, 
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
		},
		"fnDrawCallback": function(nRow, aData, iDisplayIndex ) {
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
	new FixedHeader( oTable ); 


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
	$('.chosen_lang').chosen({allow_single_deselect: true}).change(function(el){
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
	 * datepicker configuration
	 * ================================================== */
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
        beforeShowDay: highlightDays,
	}).on("click", function(){
		$("#ui-datepicker-div").css({"z-index":"1001"});
	});

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
        beforeShowDay: highlightDays,
	}).on("click", function(){
		$("#ui-datepicker-div").css({"z-index":"1001"});
	});

	function highlightDays(date){
	 console.log(date)
      return [true, '']; 

	}

	/* ==================================================
	 * Details about users, select a user by clicking on the row and press D on keyboard
	 * ================================================== */
	// trigger on click on row
	var selected_data='';
	$("tbody tr").live("click", function(){
			selected_data = oTable.fnGetData(this);

			$('span.details, span.delete').remove();
			if(canDelete && table != 'all')
				control = '<span class="btn delete" id="btnDeleteRow">Delete</span><span class="btn details">[D]etails</span>';
			else
				control = '<span class="btn details">[D]etails</span>';
			$(this).before(control);
	});


	$('.details').live('click', function(){

		// bug auto add row_selected to the parent
		if($(this).hasClass('row_selected')){
			$(this).addClass('row_selected').parent().removeClass('row_selected');
			$(this).next().addClass('row_selected');
		}
		

		showInfoBox()
	});

	$('.delete').live('click', function(){
		var data = {id:selected_data[0], table:table}

		// bug auto add row_selected to the parent
		$(this).addClass('row_selected').parent().removeClass('row_selected');
		$(this).nextUntil('tr').next().addClass('row_selected');
		$.ajax({
			url:'/ajax/deleteData',
			data:data,
			type:'POST',
			success:function(data){
				if(data == 1){
					$.jGrowl("Row #"+selected_data[0]+" in "+table+" successfully deleted, please be careful when deleting row there is no rollback", {
						theme : 'information'
					});
				oTable.fnDraw();
				}
			}
		})

	});

	// Event for show Box
	$(document).keyup(function(e){
		//68 is d
		if(e.keyCode === 68 && $(".row_selected").length > 0) showInfoBox()
	});

	var showInfoBox = function(){
		var row_data = selected_data;
		var row_head = new Array();

		for(i=0;i<oTable.fnSettings().aoColumns.length;i++)
			row_head[i] = oTable.fnSettings().aoColumns[i].sTitle;

		var formatted = "<div id='custom_modal'>";

		for(i = 0; i < row_data.length-1; i++){
			formatted += "<div><span class='modal_head'>"+row_head[i]+"</span>";
			if(row_head[i] == 'E-mail')
				formatted += "<span class='modal_data'><a href='mailto:"+row_data[i]+"'>"+row_data[i]+"</a></span></div>";
			else
				formatted += "<span class='modal_data'>"+row_data[i]+"</span></div>";
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

});