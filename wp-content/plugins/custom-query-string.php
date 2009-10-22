<?php

/* <WP plugin data>
 * Plugin Name:   Custom Query String
 * Version:       2.7
 * Plugin URI:    http://mattread.com/projects/wp-plugins/custom-query-string-plugin/
 * Description:   Change the number of posts displayed when viewing different archive pages.
 * Author:        Matt Read
 * Author URI:    http://www.mattread.com/
 *
 * License:       GNU General Public License
 *
 * 
 * Copyright (C) 2005  Matt Read
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 * 
 * You can contact the author by the following:
 * Matt Read
 * 102 Delaney Drive
 * Ottawa, ON K0A 1L0 CA
 * mattread@mattread.com
 */

# define the current verion
define('k_CQS_VER', '2.7');

class cqs
{
	var $query = false;
	var $category = false;
	
	var $query_vars = array();
	var $query_string;
	
	var $options = array();
	var $option = array();
	
	var $what_to_shows = array('posts', 'days');
	var $conditions = array('is_archive', 'is_author', 'is_category', 'is_date', 'is_year', 'is_month', 'is_day', 'is_time', 'is_search', 'is_home', 'is_paged', 'is_feed');
	var $orderbys = array('date','category','title','author');
	var $orders = array('DESC', 'ASC');
	
	var $request = array();

	
	/**
	 * Register WordPress plugin actions and gets options.
	 * @since version 2.6
	 */
	function cqs() {
		add_action('activate_'.$this->plugin_basename(__FILE__), array(&$this, 'activate'));
		add_action('deactivate_'.$this->plugin_basename(__FILE__), array(&$this, 'deactivate'));
		add_action('admin_menu', array(&$this, 'admin_menu'));
		add_filter('query_string', array(&$this, 'custom_query_string'));
		
		if (!$this->options = get_option('cqs_options'))
			$this->options = array();
	}
	
	
	/**
	 * Initializes the variables etc..
	 * @param string  the query string to be modified.
	 * @since version 2.0.1
	 */
	function init($query_string) {
		parse_str($query_string, $qv);
		$this->query_string = $query_string;
		$this->query_vars = $qv;
		if ($this->options) {
			$this->get_query();
			$this->get_category();
		}
	}
	
	
	/**
	 * The main function, merges query string with our new queries
	 * @param string  the query string to be modified.
	 * @since version 2.0
	 * @return string  the new customized query string.
	 * @access public
	 */
	function custom_query_string($query_string) {
		$this->init($query_string);
	
		/*
		 * Check if have a custom query for this category then check
		 * if have a custom query and only add it if it exsists.
		 */
		if ( $this->category )
			$this->option = $this->options[$this->category];
		elseif ( $this->query )
			$this->option = $this->options[$this->query];

		if ( $this->option ) {
			$custom_query_string = array(
				'posts_per_page' => $this->option['posts_per_page'],
				'what_to_show'   => $this->option['what_to_show'],
				'orderby'        => $this->option['orderby'],
				'order'          => $this->option['order']
				);
			/*
			 * Make sure we don't override any queries already set.  Merge
			 * the original query vars to the new ones.  Then build the string
			 * from the new array.
			 */
			$this->query_vars = array_merge($custom_query_string,$this->query_vars);
			$this->query_string = $this->build_query_string($this->query_vars);
		}
		return $this->query_string;
	}
	
	
	/**
	 * Runs after plugin is activated. Adds default settings to the options table.
	 * @since Version 2.4
	 */
	function activate() {
		if (!get_option('cqs_options'))
			add_option('cqs_options', array(), 'The Custom Query String plugin settings.', 'no');
		return true;
	}
	
	
	/**
	 * Runs after plugin is deactivated. Removes all settings from the options table.
	 * @since Version 2.4
	 */
	function deactivate() {
		delete_option('cqs_options');
		return true;
	}
	
	
	/**
	 * Get the plugin basename.
	 * @param str $file The plugin file path.
	 * @since version 2.7
	 */
	function plugin_basename($file) {
		$file = preg_replace('/^.*wp-content[\\\\\/]plugins[\\\\\/]/', '', $file);
		return $file;
	}
	
	
	/**
	 * Rebuild query string from assosiative array
	 * @since version 2.0
	 */
	function build_query_string ($query_vars) {
		foreach ($query_vars as $key => $value) {
			$query[] = $key.'='.$value;
		}
		return join('&', $query);
	}


	/**
	 * Determines the type of query to use
	 * the order is very important!
	 * archive includes: author, category and date.
	 * date includes: time, day, month, year.
	 * paged overrides all. Use with caution!
	 * @since version 2.0
	 */
	function get_query() {
		global $wp_query;
		$wp_query->parse_query($this->query_string);

		if ($wp_query->is_feed AND $this->options['is_feed'])
			$this->query = 'is_feed';

		elseif ($wp_query->is_paged AND $this->options['is_paged'])
			$this->query = 'is_paged';

		elseif ($wp_query->is_archive)
		{
			if ($wp_query->is_author AND $this->options['is_author'])
				$this->query = 'is_author';

			elseif ($wp_query->is_category AND $this->options['is_category'])
				$this->query = 'is_category';
			
			elseif ($wp_query->is_date)
			{
				if ($wp_query->is_time AND $this->options['is_time'])
					$this->query = 'is_time';
				
				elseif ($wp_query->is_day AND $this->options['is_day'])
					$this->query = 'is_day';
				
				elseif ($wp_query->is_month AND $this->options['is_month'])
					$this->query = 'is_month';
				
				elseif ($wp_query->is_year AND $this->options['is_year'])
					$this->query = 'is_year';
				
				elseif ($this->options['is_date'])
					$this->query = 'is_date';
			}
			elseif ($this->options['is_archive'])
				$this->query = 'is_archive';
		}
		elseif ($wp_query->is_search AND $this->options['is_search'])
			$this->query = 'is_search';

		elseif ($wp_query->is_home AND $this->options['is_home'])
			$this->query = 'is_home';
	}
	
	
	/**
	 * Get the category ID
	 * @since version 2.2
	 */
	function get_category() {
		global $wp_query;
		if ( $wp_query->is_category ) {
			if ( !($category = $wp_query->get('cat')) ) {
				$category = $wp_query->get('category_name');
				$category = $this->get_category_id($category);
			}
			if ( $this->options['cat_'.$category] ) {
				$this->category = 'cat_'.$category;
			}
		}
	}
	
	
	/**
	 * Get the category ID from category nice_name.
	 * @since version 2.2
	 * @param string  The category nice_name.
	 * @return int  The category id.
	 */
	function get_category_id($category_nicename) {
		global $wpdb;
		return (int) $wpdb->get_var("SELECT cat_ID FROM $wpdb->categories WHERE category_nicename = '". $wpdb->escape($category_nicename) ."'");
	}
	
	
	/**
	 * Get the category nice_name from category ID.
	 * NOT IN USE. Names change and ID's don't.
	 * @since version 2.7
	 * @param int  The category ID.
	 * @return string  The category nice_name.
	 */
	function get_category_nicename($category_ID) {
		global $wpdb;
		return $wpdb->get_var("SELECT category_nicename FROM $wpdb->categories WHERE cat_ID = '". $wpdb->escape((int) $category_ID) ."'");
	}
	
	
	/**
	 * Adds CQS to the admin menu
	 * @since version 2.6
	 */
	function admin_menu() {
		add_options_page('CQS Options', 'CQS', 'manage_options', $this->plugin_basename(__FILE__), array(&$this, 'options_page'));
	}
	
	
	/**
	 * initializes the options page
	 * @since version 2.0
	 */
	function options_page() {
		load_plugin_textdomain('custom-query-string');
		$this->request = $_REQUEST['cqs'];

		if ($this->request['add'] || $this->request['addCategory']) {
			$this->add_options();
			$this->display_options_page(true);
		}
		elseif ($this->request['deleteChecked']) {
			$this->delete_options();
			$this->display_options_page(true);
		}
		else {
			$this->display_options_page();
		}
	}
	
	
	/**
	 * adds/updates new options
	 * @since version 2.0
	 */
	function add_options() {
		if ($this->request['addCategory']) {
			$cqs_new_options = array(
				'cat_'. $_REQUEST['cat'] => array(
					'posts_per_page' => intval($this->request['category']['posts_per_page']),
					'what_to_show'   => $this->request['category']['what_to_show'],
					'orderby'        => $this->request['category']['orderby'],
					'order'          => $this->request['category']['order']
				));
		}
		else {
			$cqs_new_options = array(
				$this->request['condition'] => array(
					'posts_per_page' => intval($this->request['posts_per_page']),
					'what_to_show'   => $this->request['what_to_show'],
					'orderby'        => $this->request['orderby'],
					'order'          => $this->request['order']
				));
		}
		$this->options = array_merge($this->options, $cqs_new_options);
		update_option('cqs_options', $this->options);
	}
	
	
	/**
	 * deletes selected options
	 * @since version 2.0
	 */
	function delete_options() {
		if ($this->request['delete']) {
			foreach($this->request['delete'] as $delete)
				unset($this->options[$delete]);
		}
		update_option('cqs_options', $this->options);
	}
	
	
	/**
	 * Display the footer with Credit.
	 * @since version 2.2
	 */
	function footer() {
		echo '<p style="text-align:center;margin-top:3em;">Custom Query String '. k_CQS_VER .' by <a href="http://mattread.com" style="font-family:cursive; font-size:1.1em;">Matt Read</a>.</p>';
	}
	
	
	/**
	 * Display "check all" JS and button.
	 * @since version 2.7
	 */
	function check_all_js() {
		ob_start();
		?>
		<script type="text/javascript">
		//<!--
		function checkAll(form) {
			for (i = 0, n = form.elements.length; i < n; i++) {
				if(form.elements[i].type == "checkbox") {
					if(form.elements[i].checked == false)
						form.elements[i].checked = true;
				}
			}
		}
		//-->
		</script>
		
		<input type="button" class="button" name="checkall" onclick="checkAll(document.forms[0]); return false;" value="<?php _e("Check All", 'cqs'); ?>" />
		<?php
		ob_end_flush();
	}
	
	
	/**
	 * display the options page
	 * @param string Which update message to show. Either 'updated' or 'delete'.
	 * @since version 2.0
	 */
	function display_options_page ($updated = false) {
		if ($updated)
			echo '<div id="message" class="updated fade"><p><strong>'. __('Options saved.', 'cqs') .'</strong></p></div>';
		
		ob_start();
		?>
		
	<form name="cqsoptions" method="post">
	<?php if ($this->options) : ?>
	<div class="wrap" onmouseover="window.status='Customize My Queries Please!'; return true;"><h2>Custom Query String <?php echo k_CQS_VER; ?></h2>
	
	<h3><?php _e('Current Conditions', 'cqs'); ?></h3>
	
		<table cellspacing="2" cellpadding="5" width="100%">
		<tr class="alternate">
		<th></th>
		<th><?php _e('Condition', 'cqs'); ?></th>
		<th><?php _e('Result', 'cqs'); ?></th>
		</tr>
	
		<?php
		$i = 0;
		foreach ($this->options as $condition => $cqs_option) :
		
		# get the name of cat for display.
		if (strpos($condition, 'cat_') !== false) {
			$cat_id = str_replace('cat_', '', $condition);
			$cat_name = $this->get_category_nicename(intval($cat_id));
			$display_condition = 'cat_'. $cat_name;
		}
		else
			$display_condition = $condition;
		
		?>
		
		<tr valign="top" <?php if ($i % 2 != 0) echo 'class="alternate"'; ?>>
			<td><input type="checkbox" name="cqs[delete][]" value="<?php echo $condition; ?>" /></td>
			<td><strong><?php echo $display_condition; ?></strong></td>
			
			<td><?php
			$string = __('Show <strong>%posts_per_page% %what_to_show%</strong> per page, ordered by %orderby% %order%', 'cqs');
			echo str_replace(array('%posts_per_page%', '%what_to_show%', '%orderby%', '%order%'), array($cqs_option['posts_per_page'], $cqs_option['what_to_show'], $cqs_option['orderby'], $cqs_option['order']), $string);
			?></td>
		</tr>
		
		<?php
		$i++;
		endforeach;
		?>
		
		</table>
		
		<p class="submit">
		<input type="submit" class="button" name="cqs[deleteChecked]" value="<?php _e('Delete Checked', 'cqs'); ?>" />
		<?php $this->check_all_js(); ?>
		</p>
	<?php endif; ?>
	
	<h3 style="margin-top:4em;"><?php _e('Add New Condition', 'cqs'); ?></h3>
	
		<p><?php _e('To update current conditions replace them with new ones here. Use \'-1\' to show all posts.', 'cqs'); ?></p>
		
		<table cellspacing="2" cellpadding="5" width="100%">
			<tr class="alternate">
				<th>&nbsp;</th>
				<th><?php _e('Condition', 'cqs'); ?></th>
				<th><?php _e('Show', 'cqs'); ?></th>
				<th><?php _e('Order By', 'cqs'); ?></th>
				<th>&nbsp;</th>
			</tr>
			
			<tr>
				<th scope="row"><?php _e('Query', 'cqs'); ?></th>
				
				<td>
				<select name="cqs[condition]">
				<?php
				foreach ($this->conditions as $condition) {
					echo "<option>$condition</option>";
				}
				?>
				</select>
				</td>
			
				<td>
				<input type="text" name="cqs[posts_per_page]" size="3" />
				<select name="cqs[what_to_show]">
				<?php 
				foreach ($this->what_to_shows as $what_to_show) {
					echo "<option>$what_to_show</option>";
				}
				?>
				</select>
				</td>
			
			
				<td>
				<select name="cqs[orderby]">
				<?php 
				foreach ($this->orderbys as $orderby) {
					echo "<option>$orderby</option>";
				}
				?>
				</select>
				<select name="cqs[order]">
				<?php 
				foreach ($this->orders as $order) {
					echo "<option>$order</option>";
				}
				?>
				</select>
				</td>
				
				<td><input type="submit" class="button" name="cqs[add]" value="<?php _e('Add', 'cqs'); ?>" /></td>
			</tr>
			
			<tr class="alternate">
				<th scope="row"><?php _e('Category', 'cqs'); ?></th>
				
				<td>
				<?php dropdown_cats(0, 'All', 'ID', 'asc', 0, 0, 0, FALSE, 0, 0) ?>
				</td>
			
				<td>
				<input type="text" name="cqs[category][posts_per_page]" size="3" />
				<select name="cqs[category][what_to_show]">
				<?php 
				foreach ($this->what_to_shows as $what_to_show) {
					echo "<option>$what_to_show</option>";
				}
				?>
				</select>
				</td>
			
			
				<td>
				<select name="cqs[category][orderby]">
				<?php 
				foreach ($this->orderbys as $orderby) {
					echo "<option>$orderby</option>";
				}
				?>
				</select> 
				<select name="cqs[category][order]">
				<?php 
				foreach ($this->orders as $order) {
					echo "<option>$order</option>";
				}
				?>
				</select>
				</td>
				
				<td><input type="submit" class="button" name="cqs[addCategory]" value="<?php _e('Add', 'cqs'); ?>" /></td>
			</tr>
			
			</table>
			
			<?php $this->footer(); ?>
	</div>
	
	</form>
		
		<?php
		ob_end_flush();
	}
}

$cqs = new cqs();

?>