jQuery(document).ready(function($) {
	$('div.tag-manager-edit').each(function() {
		$(this).append('<input type="submit" class="tag-manager" value="Cancel"/>').children().hide();
		$(this).prepend('<a href="#" title="Click to edit this tag">' + $(this).children('.tag-manager-name').val() + '</a>');
		$(this).children('a').click(function(e) {
			$(this).siblings().andSelf().toggle();
			e.preventDefault();
		});
		$(this).children('input[value="Cancel"]').click(function(e) {
			$(this).siblings().andSelf().toggle();
			e.preventDefault();
		});
	});
	
	$('input.tag-manager[value="Save"]').click(function(e) {
		e.preventDefault();

		var thisField = $(this);
		var oldHandle = $(this).attr('data-handle');
		var newValue = $(this).prev().val();
		var fieldId = Symphony.Context.get('env')[1];

		if(newValue.length == 0) {
			alert('New tag name cannot be empty.');
		}
		else {
			// Let's drop some AJAX
			$.ajax({
				type: 'POST',
				dataType: 'html',
				url: Symphony.Context.get('root') + '/symphony/extension/tag_manager/ajaxupdate/',
				data: {
					oldHandle: oldHandle,
					newValue: newValue,
					fieldId: fieldId
				},
				success: function(xml) {
					if($(xml).find('status').text() == 'true') {
						thisField.siblings('a').text(newValue);
						thisField.siblings().andSelf().toggle();
						thisField.parent().append('<span class="tag-manager-success">Updated!</span>').children().last().delay(2000).fadeOut('slow');
					} else {
						alert('Oops! Something went wrong.');
					}
				},
				cache: false
			});
		}
	});
});