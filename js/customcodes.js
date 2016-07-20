(function(){
	tinymce.PluginManager.requireLangPack('blist');
	tinymce.create('tinymce.plugins.blist', {
		init : function(ed, url){

			ed.addButton('mjrdesign', {
				image: url + './../img/m.png',
				onclick : function() {

					var p = jQuery(this).attr('_id');
					var current_id = 'body';
					var current_parent = jQuery(current_id);

					console.log(current_id);

					jQuery(document).ready(function($) {


						$( window ).resize(function() {
							$('.mjrdesign_shortcodes_popup').slideUp(100);
						});

						$('.mce-edit-area').click(function() {
							$('.mjrdesign_shortcodes_popup').slideUp(100);
						});

						if($('.mjrdesign_shortcodes_popup').length) {
							if($('.mjrdesign_shortcodes_popup').is(':visible')) {
								$('.mjrdesign_shortcodes_popup').slideUp(100);
							} else {
								if($('.mjrdesign_large_popup').is(':visible') == false) {
									var position = jQuery('#' + p).offset();
									$('.mjrdesign_shortcodes_popup').css({
										top: position.top + 27 + 'px',
										left: position.left + 'px'
									});
									$('.mjrdesign_shortcodes_popup').slideDown(100);
								}
							}
						} else {

							$('body').append('<div class="mjrdesign_shortcodes"></div>');
							$('.mjrdesign_shortcodes').load(url + './../shortcodes/mjrdesign_shortcodes.html?rand=' + Math.floor(Math.random()*1000), function(){
									var position = jQuery('#' + p).offset();
									$('.mjrdesign_shortcodes_popup').css({
										top: position.top + 27 + 'px',
										left: position.left + 'px'
									});

									$(window).scroll(function() {
										var position = jQuery('#' + p).offset();
										$('.mjrdesign_shortcodes_popup').css({
											top: position.top + 27 + 'px',
											left: position.left + 'px'
										});
									});
									$('.mjrdesign_shortcodes_popup').slideDown(100);

									$('.mjrdesign_shortcodes_popup li').click(function(){
										var shortcode = $(this).data('shortcode');
										ed.execCommand('mceInsertContent', false, shortcode);
										$('.mjrdesign_shortcodes_popup').slideUp(100);
									});

									$('.mjrdesign_shortcodes_popup li.noreplace').unbind('click');
									$('.mjrdesign_shortcodes_popup li.noreplace').click(function() {
										var current_text = tinymce.activeEditor.selection.getContent();
										var shortcode = $(this).data('shortcode');
										var endshortcode = $(this).data('endshortcode');
										ed.execCommand('mceInsertContent', false, shortcode + current_text + endshortcode);
										$('.mjrdesign_shortcodes_popup').slideUp(100);
									});

									$('.mjr_slideshow').unbind('click');
									$('.mjr_slideshow').click(function(){

										var replace_content = ed.getContent();
										replace_content = replace_content.replace(/\[gallery/g, '[slideshow speed="15000" animation="slide" direction="horizontal"');
										ed.setContent(replace_content);
										console.log(replace_content);
										$('.mjrdesign_shortcodes_popup').slideUp(100);

									});

									$('.mjr_icons').unbind('click');
									$('.mjr_icons').click(function(){

										$('.mjrdesign_shortcodes_popup').slideUp(100);
										if($('.mjrdesign_large_popup').length) {
											$('.mjrdesign_large_popup').fadeIn(100);

										} else {

											$.get(url + './../shortcodes/icons.html?rand=' + Math.floor(Math.random()*1000), function(data){

													$('body').prepend('<div class="mjrdesign_large_popup">' + data + '</div>');
													$('.mjrdesign_large_popup').fadeIn(200);

													$('.mjrdesign_large_popup li').click(function(){
														var shortcode = $(this).data('shortcode');
														ed.execCommand('mceInsertContent', false, shortcode);
														$('.mjrdesign_large_popup').fadeOut(100);
													});
													$('.mjrdesign_large_popup').click(function() {
														$('.mjrdesign_large_popup').fadeOut(100);
													});
											});

										}
									});

							});
						}

					}); // end document.ready

				}
			});

		},
		createControl : function(n, cm) {
			return null;
		},
	});
	tinymce.PluginManager.add('blist', tinymce.plugins.blist);
})();