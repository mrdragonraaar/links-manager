/**
 * links_manager.js
 *
 * (c)2013 mrdragonraaar.com
 */

// change order
$(document).ready(function() {
	$('#links tbody').sortable({
		cursor: 'move',
		placeholder: 'placeholder-link',
		helper: function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		},
		update: function() {
			var order = '';
			$('#links tbody tr').each(function(index) {
				var link = $(this).attr('rel');
				if (link)
				{
					order = order + ',' + link;
				}
			});
			$('[name=link-order]').val(order);
		}
	});
	$('#links tbody').disableSelection();
});

// validate
$(document).ready(function() {
	$("#edit").validate({
		errorClass: "invalid"
	});
	$("#link-name").focus();
	$("#category-name").focus();
});

// disable url
$(document).ready(function() {
	function urlEnableDisable()
	{
		if ($("#link-page-slug").val() != '')
		{
			$("#link-url").prop('disabled', true);
		}
		else
		{
			$("#link-url").prop('disabled', false);
		}
	}

	// initial url state
	urlEnableDisable();

	// change state on page select
	$("#link-page-slug").change(function() { urlEnableDisable() });
});

