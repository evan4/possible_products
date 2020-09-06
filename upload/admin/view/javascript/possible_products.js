jQuery(function ($) {
	"use strict";
	$('#possibleTabs a').click(function (e) {
		e.preventDefault()
		$(this).tab('show')
	});
	$(document).on('click', '.alert-close', function () {
		$(this).parent().hide()
			.removeClass(function (index, className) {
				return (className.match(/(^|\s)alert-\S+/g) || []).join(' ');
			})
			.find('strong').text('');
	});
	$.ajaxSetup({
		dataType: "json",
		method: "POST",
	});
});

function resultOutput(msg, type) {
	const result = $('#result');

	result.show()
		.addClass(`alert-${type}`)
		.find('strong').text(msg);

	setTimeout(() => {
		result.hide()
			.removeClass(function (index, className) {
				return (className.match(/(^|\s)alert-\S+/g) || []).join(' ');
			})
			.find('strong').text('');
	}, 5000);
}