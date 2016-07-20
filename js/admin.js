jQuery(document).ready(function($) {

	function mjr_generate_preview(mjr_theme_directory) {
		if(!$('#setting_mjr_body_background_pattern .mjr-preview-sidebar').length) {
			var superdiv = '<div style="position: relative; width: 100%; height: 400px;"><div class="mjr-preview-sidebar" style="z-index: 1; position: relative; width: 30%; height: 100%; float: left;">&nbsp;</div><div class="mjr-preview-body" style="position: absolute;width: 100%; height: 400px; float: right;">&nbsp;</div><div style="clear: both;"></div></div>';
			$('#setting_mjr_body_background_pattern').append(superdiv);
			$('.mjr_preview-sidebar').css('background', 'url("' + mjr_theme_directory + '/img/bg/' + $('#setting_mjr_body_background_pattern select').val() + '") repeat');
		}

		if($('#setting_mjr_body_background_pattern select').val() != 'none') {
			$('.mjr-preview-body').css('background', 'url("' + mjr_theme_directory + '/img/bg/' + $('#setting_mjr_body_background_pattern select').val() + '") repeat');
		} else {
			$('.mjr-preview-body').css('background', $('#setting_mjr_body_background_color input').val());
		}
	}

	$('#setting_mjr_body_background_pattern select, #setting_mjr_sidebar_background_pattern select').on('change', function() {
		mjr_generate_preview(mjr_theme_directory);
	});
	$('#setting_mjr_body_background_color input, #setting_mjr_sidebar_background_color input').on('keyup', function() {
		mjr_generate_preview(mjr_theme_directory);
	});

});