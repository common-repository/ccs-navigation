<?php
/*
Plugin Name: CCS-Navigation
Description: A handy little plug-in for easy dynamic navigation of a hierachical site.
Author: David Gregg, Creative Cloud Solution.
Version: 1.0
Author URI: http://www.creativecloudsolutions.com/products/wordpress/plugins/ccs-navigation/
*/

/*
	Copyright (C) 2012 David Gregg, Creative Cloud Solutions

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

function widget_ccs_nav($args) {
	global $post;

	if ( is_page() ) {
		extract($args);
		
		// Current page level
		$ccs_page_depth = count($post->ancestors);
		$depth = 1;
		$links = 1;
		$rootPost = $post;
		$rootTitle = $rootPost->post_title;
		$parentPost = &get_post($rootPost->post_parent);
		$parentTitle = $parentPost->post_title;
		$output = wp_list_pages('sort_column=menu_order&depth='.$depth.'&title_li=&echo=0&child_of='.$rootPost->ID);

		if (empty($output)) {
			$output = '<li>No additional links</li>';
			$links = 0;
		}

		if ( !is_front_page() AND (($rootPost->post_parent != 0) OR ($rootPost->post_parent == 0 AND $links == 1)) ) {
			echo $before_widget . $before_title . '<a href="' . get_permalink($rootPost->ID) . '">' . $rootTitle . '</a>' . $after_title . '<ul><ul>' . $output . '</ul></ul>' . $after_widget;
		}

		// Loop for each ancestor page level
		for ($i=1; $i<=$ccs_page_depth; $i++) {
			if ($rootPost->post_parent != 0) {
				$rootPost = &get_post($rootPost->post_parent);
				$rootTitle = $rootPost->post_title;
				$output = wp_list_pages('sort_column=menu_order&depth='.$depth.'&title_li=&echo=0&child_of='.$rootPost->ID);
			
				if (!empty($output)) {
					echo $before_widget . $before_title . '<a href="' . get_permalink($rootPost->ID) . '">' . $rootTitle . '</a>' . $after_title . '<ul><ul>' . $output . '</ul></ul>' . $after_widget;
				}
			}
		}
	}
}

function widget_ccs_nav_init() {
	if ( function_exists('register_sidebar_widget') ) {
		register_sidebar_widget(array('CCS-Navigation', 'widgets'), 'widget_ccs_nav');
	} else {
		return;
	}
}

add_action('widgets_init', 'widget_ccs_nav_init');

?>
