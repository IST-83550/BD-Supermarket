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
});

$(document).on('change', 'select', function() {
	$("select option").each(function()
	{
	    $(this).show();
	});
	$('select option[value="' + this.value + '"]').hide();
});