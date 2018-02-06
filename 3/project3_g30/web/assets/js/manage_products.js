/** Toggle creation form of primary provider. */
$(document).on('change', 'select.forn', function() {
	var optionSelected = $(this).find("option:selected");
	if (this.value == 'new-forn') {
		window.location.href = "manage_providers.php";
	}
});

var add_another = true;
$(document).on('change', 'select.forn-sec', function() {
	var optionSelected = $(this).find("option:selected");
	if (this.value == 'new-forn') {
		window.location.href = "manage_providers.php";
	}	
	else if (this.value != '' && add_another) {
		add_another = !add_another;
	}
});

$('select.forn-sec').last().change(function() {
	if ($(this).value != '') {
		$('.add-another').attr('disabled', false);
	}
	else {
		$('.add-another').attr('disabled', true);
	}
});

$(document).on('click', '.input-group-btn button', function() {
	
	$(this).closest(".forn-sec-wrapper").fadeOut(300, function() {
		
		$(this).remove();
		
		if ($('.input-group-btn button').length == 1)
			$('.input-group-btn button').attr('disabled', true);
			
		var current_selected = $('select').map(function() {
        	return this.value
    	}).get();
      
		$("select option").each(function() {
			if(!current_selected.includes(this.value)) {
				$(this).show();
			}
		});
		
		$('select.forn-sec').each(function() {
			if(this.value == '')
				$('.add-another').attr('disabled', true);
			else
				$('.add-another').attr('disabled', false);
		});
	});
	
});

$(document).on('click', '.add-another', function() { 
	$('.forn-sec-wrapper').last().clone().insertAfter($('.forn-sec-wrapper').last());
	$('.input-group-btn button').each(function() {
		$(this).attr('disabled', false);
	});
	$('.add-another').attr('disabled', true);
	$('select.forn-sec').last().change(function() {
		if ($(this).value != '') {
			$('.add-another').attr('disabled', false);
		}
		else {
			$('.add-another').attr('disabled', true);
		}
	});
});

$(document).on('change', 'select', function() {
	var current_selected = $('select').map(function() {
          return this.value
      }).get();
      
	$("select option").each(function() {
		if(!current_selected.includes(this.value)) {
			$(this).show();
		}
	});
	$('select option[value="' + this.value + '"]').hide();
});

$(function() {
	$( "#datepicker" ).datepicker({ dateFormat: 'dd-mm-yy' });
});