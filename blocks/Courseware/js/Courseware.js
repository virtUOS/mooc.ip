define(['assets/js/block_types', './student_view'], function (block_types, StudentView) {

    'use strict';

    return block_types.add({
        name: 'Courseware',
        views: {
            student: StudentView
        }
    });
});

jQuery("document").ready(function($){
	
	var nav = $('section#courseware ol.active-subchapter');
	var nav_position_top = $('section#courseware ol.active-subchapter').offset().top;
	var nav_position_left = $('section#courseware ol.active-subchapter').offset().left;
	var nav_position_right = $('section#courseware ol.active-subchapter').offset().right;
	
	$(window).scroll(function () {
		if ($(this).scrollTop() > (nav_position_top-27)) {
			nav.addClass("fixed_navigation");
			nav.removeClass("navigation_position");
			nav.css("left", nav_position_left);
			nav.css("right", nav_position_right);
		} else {
			nav.removeClass("fixed_navigation");
			nav.addClass("navigation_position");
		}
	});

});