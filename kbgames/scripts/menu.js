$(document).ready(function() {
	var clicked_menu_head;
	var speed = 300;

	$('.menu_head').click(function() {
		clicked_menu_head = $(this);
		if (clicked_menu_head.next().is(':visible')) {
			clicked_menu_head.next().slideUp();
			clicked_menu_head.css({backgroundImage:"url(../images/backend/left.png)"});
		}

		// if clicked menu head is submenu, check if there are any submenus in it, then hide
		clicked_menu_head.next().find('p.menu_head').each(function () {
			$(this).next().slideUp(speed);
			$(this).css({backgroundImage:"url(../images/backend/left.png)"});
		});

		parent_menu_head = clicked_menu_head.parents('div.menu_body').prev(clicked_menu_head.parents('div.menu_body').size()-1);

		if (clicked_menu_head.next('div.menu_body').is(':visible')) {
		} else clicked_menu_head.css({backgroundImage:"url(../images/backend/down.png)"}).next('div.menu_body').slideDown(speed);


		$('#firstpane').children('p').siblings('p').each(function() {
			// loop through each main menu, if not equal, hide all sub menu
			if (parent_menu_head.attr('id')) {
				if (parent_menu_head.attr('id') != $(this).attr('id')) {
					$(this).children('div.menu_body').slideUp(speed);
				}
			} else {
				if (clicked_menu_head.attr('id') != $(this).attr('id')) {
					$(this).css({backgroundImage:"url(../images/backend/left.png)"})
					$(this).next().slideUp(speed).find('p.menu_head').each(function() {
						$(this).next().slideUp(speed);
					});
				}
			}

		});
	});
});