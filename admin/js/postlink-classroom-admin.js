(function( $ ) {
	'use strict';

	$(document).ready(function () {
		// Show the first tab and hide the rest
		$("#tabs-nav li:first-child").addClass("active");
		$(".tab-content").hide();
		$(".tab-content:first").show();

		// Click function
		$("#tabs-nav li").click(function () {
		$("#tabs-nav li").removeClass("active");
		$(this).addClass("active");
		$(".tab-content").hide();

		var activeTab = $(this).find("a").attr("href");
		$(activeTab).fadeIn();
		return false;
		});
	});
	
	function formdatasave() {
		$(document).on('click', '.save-btn', function () {
			let perpage_post = document.getElementById('posts-display').value;
			let users = document.getElementById('select-user').value;

				$.ajax({
					type: "POST",
					url: _ajax_url.ajax_url,
					data: {
						action: "configured_students_page",
						post_shows: perpage_post,
						users: users
					},
					beforeSend: function () {
						$('.warning').text('Submitting...');
						$('.warning').show();
					},
					success: function (response) {
						$('.warning').show();
						$('.warning').css('color', 'green');
						$('.inp-des').css('border', 'none');
						$('.warning').text('Success');
						$('.warning').fadeOut(2000);
					}
				});
			
		});
	}
	formdatasave();
})( jQuery );
