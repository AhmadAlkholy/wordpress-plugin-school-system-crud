<?php

/**
*@package first-plugin
**/
/*
Plugin name: School System
Plugin URI:
Description: plugin for school system to show and edit teachers students and subjects
Version: 1.0.0
Author: Ahmad Alkholy
Author URI:
License: GPLv2 or later
Text Domain: get-status
*/

defined('ABSPATH') or die('access denied'); //for security

if (!class_exists('SchoolSystemClass')) 
{
	class SchoolSystemClass
	{

		function activate () {
			
		    global $wpdb;

		    $subjects = $wpdb->prefix . "subjects";
		    $teachers = $wpdb->prefix . "teachers";
		    $students = $wpdb->prefix . "students"; 


			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE IF NOT EXISTS $subjects (
			   id mediumint(9) NOT NULL AUTO_INCREMENT,
			   name varchar(30)  NOT NULL,
			   code varchar(255) NOT NULL,
			   teacher_id mediumint(9) NOT NULL,
			   PRIMARY KEY  (id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );

			$sql = "CREATE TABLE IF NOT EXISTS $teachers (
					   id mediumint(9) NOT NULL AUTO_INCREMENT,
					   name varchar(30)  NOT NULL,
					   address varchar(255) NOT NULL,
					   email varchar(120) NOT NULL,
					   phone varchar(20) NOT NULL,
					   description text DEFAULT NULL,
					   PRIMARY KEY  (id)
					)$charset_collate;";
			dbDelta( $sql );

			$sql = "CREATE TABLE IF NOT EXISTS $students (
					   id mediumint(9) NOT NULL AUTO_INCREMENT,
					   name varchar(30)  NOT NULL,
					   address varchar(255) NOT NULL,
					   email varchar(100) NOT NULL,
					   phone varchar(20) NOT NULL,
					   description text DEFAULT NULL,
					   PRIMARY KEY  (id)
					)$charset_collate;";

			dbDelta( $sql );
		}

		function addAdminPages(){
			add_menu_page( 'school', 'School System'/*sidebar*/, 'manage_options','school_system', '', plugins_url( 'images/open-book.png' , __FILE__), 6);

			$submenu = add_submenu_page( 'school_system', 'teachers', 'Teachers', 'manage_options', 'teachers', [$this, 'schoolSystemPage'] ); 
			add_action( 'load-' . $submenu, [$this, 'addPagesScripts'] );

			$submenu = add_submenu_page( 'school_system', 'subjects', 'Subjects', 'manage_options', 'subjects', [$this, 'schoolSystemPage'] );
			add_action( 'load-' . $submenu, [$this, 'addPagesScripts'] );

			$submenu = add_submenu_page( 'school_system', 'students', 'Students', 'manage_options', 'students', [$this, 'schoolSystemPage'] );
			add_action( 'load-' . $submenu, [$this, 'addPagesScripts'] );


			$submenu = add_submenu_page( null, 'new_teacher', 'new_teacher', 'manage_options', 'new_teacher', [$this, 'add_new'] );
			add_action( 'load-' . $submenu, [$this, 'addPagesScripts'] );
			$submenu = add_submenu_page( null, 'edit_teacher', 'edit_teacher', 'manage_options', 'edit_teacher', [$this, 'add_new'] );
			add_action( 'load-' . $submenu, [$this, 'addPagesScripts'] );

			$submenu = add_submenu_page( null, 'new_student', 'new_student', 'manage_options', 'new_student', [$this, 'add_new'] );
			add_action( 'load-' . $submenu, [$this, 'addPagesScripts'] );
			$submenu = add_submenu_page( null, 'edit_student', 'edit_student', 'manage_options', 'edit_student', [$this, 'add_new'] );
			add_action( 'load-' . $submenu, [$this, 'addPagesScripts'] );

			$submenu = add_submenu_page( null, 'new_subject', 'new_subject', 'manage_options', 'new_subject', [$this, 'add_new'] );
			add_action( 'load-' . $submenu, [$this, 'addPagesScripts'] );
			$submenu = add_submenu_page( null, 'edit_subject', 'edit_subject', 'manage_options', 'edit_subject', [$this, 'add_new'] );
			add_action( 'load-' . $submenu, [$this, 'addPagesScripts'] );

			remove_submenu_page('school_system','school_system');
			unset($GLOBALS['submenu']['forms'][0]);

		}

		function addPagesScripts(){
			wp_enqueue_style('my_plugin_style' , plugins_url('pages/css/main.css', __FILE__) );

			wp_enqueue_script('ff' , plugins_url('pages/vendor/jquery/jquery-3.2.1.min.js', __FILE__) );

			wp_enqueue_script('ff' , plugins_url('pages/js/main.js', __FILE__) );
			
		}

		function delete_javascript() { ?>
			<script type="text/javascript" >
			jQuery(document).ready(function($) {

				$('.crud-btn').on('click', function(){

					var action = $(this).attr('data-action');
					var id = $(this).attr('data-target');
					var table = '<?php echo $_GET['page']; ?>';
					var btn = $('this');

					var data = {
						'action': 'delete',
						'trigger': action,
						'id': id,
						'table': table,
					};

					jQuery.post(ajaxurl, data, function(response) {
						if( response == 1){
							$('#row'+id).remove();
						}
					});
				});
				
			});
			</script> <?php
		}

		function delete() {
			global $wpdb; // this is how you get access to the database

			$action = $_POST['trigger'] ;
			$id = $_POST['id'];

			if ($action == "delete") {
				$query1 = $wpdb->query(
	              'DELETE  FROM '.$wpdb->prefix. $_POST['table'] . '
	               WHERE id = "'.$id.'"'
				);

				$query2 = 1;
				if ( $_POST['table'] == "teachers") {
					$match = $wpdb->get_results( " SELECT id FROM ".$wpdb->prefix."subjects WHERE teacher_id = $id" );
					if (count($match)) {
						$query2 = $wpdb->query(
							'UPDATE '.$wpdb->prefix . 'subjects SET teacher_id = "" WHERE teacher_id = "'.$id.'"'
						);
					}
						
				}
					
				if ($query1 && $query2) {
					echo 1;
				}
			}

			wp_die(); // this is required to terminate immediately and return a proper response
		}

		function admin_redirects($page){
				$path = admin_url( "admin.php?page=$page", 'http');
				echo "<script>window.location = '$path'</script>";
				exit();				
		}

		function add_new(){

			global $wpdb;
			$slug = $_GET['page'];

			if ( isset($_POST['add_teacher']) ||  isset($_POST['edit_teacher']) ) {
				$cells = [
					'name'=> $_POST['name'], 
					'address'=>$_POST['address'], 
					'email'=>$_POST['email'], 
					'phone'=>$_POST['phone'], 
					'description'=>$_POST['description'] ];
				if ( isset( $_POST['id']) ) {
					$id = $_POST['id'];
					$wpdb->update($wpdb->prefix.'teachers', $cells, ['id'=>$id]);
				}
				else{
					$wpdb->insert($wpdb->prefix.'teachers',$cells);
					$id = $wpdb->insert_id;
				}
				$this->admin_redirects("teachers#row$id");
				exit();
			}
			elseif (isset( $_POST['add_student']) ||  isset($_POST['edit_student'])  ) {
				$cells = [
					'name'=> $_POST['name'], 
					'address'=>$_POST['address'], 
					'email'=>$_POST['email'], 
					'phone'=>$_POST['phone'], 
					'description'=>$_POST['description'] ];
				if ( isset( $_POST['id']) ) {
					$id = $_POST['id'];
					$wpdb->update($wpdb->prefix.'students', $cells, ['id'=>$id]);
				}
				else{
					$wpdb->insert($wpdb->prefix.'students',$cells);
					$id = $wpdb->insert_id;
				}
				$this->admin_redirects("students#row$id");
				exit();
			}
			elseif (isset( $_POST['add_subject']) ||  isset($_POST['edit_subject'])  ) {
				$cells = [
					'name'=> $_POST['name'], 
					'code'=>$_POST['code'], 
					'teacher_id'=>$_POST['teacher'], 
				];
				if ( isset( $_POST['id']) ) {
					$id = $_POST['id'];
					$wpdb->update($wpdb->prefix.'subjects', $cells, ['id'=>$id]);
				}
				else{
					$wpdb->insert($wpdb->prefix.'subjects',$cells);
					$id = $wpdb->insert_id;
				}
				$this->admin_redirects("subjects#row$id");
				exit();
			}
			else{
				if ($slug == "new_subject") {
					$teachers = $wpdb->get_results( " SELECT name , id FROM ".$wpdb->prefix."teachers" );
				}
				$result = 0;
				$file = str_replace('edit_', 'new_', $slug);
				$folder = str_replace('new_', '', $file."s");
				if ( isset($_GET['id']) ) {
					$table = $wpdb->prefix. str_replace('new_', '', $slug."s") ;
					$result = $wpdb->get_results( " SELECT * FROM $table WHERE id = ".$_GET['id']);
				}

				include_once plugin_dir_path( __FILE__ ) . "pages/$folder/$file.php" ;
				return 1;
			}
	
		}

		function schoolSystemPage($atts){
			
			$param = shortcode_atts( array(
				'page' => 0,
				'bar'=>"bing",
			), $atts );

			if ($param['page']) {

				if ( !in_array($param['page'], ['teachers','students','subjects']) ) {
					return false;
				}
				$slug = $param['page'];
				wp_enqueue_style('my_plugin_style' , plugins_url('pages/css/main.css', __FILE__) );
			}
			else{
				$slug = $_GET['page'];
			}
			

			if ( isset($slug) ) {
				global $wpdb;

				$table = $wpdb->prefix . $slug;
				$results = $wpdb->get_results( " SELECT * FROM $table" );					

				$cell = substr($slug, 0, -1);
				$add_url = admin_url( "admin.php?page=new_$cell" );

				if(!empty($results)){
					

					include_once plugin_dir_path( __FILE__ ) . "pages/$slug/show_$slug.php" ;		
				}
				else{
					echo "<h1>no $slug in the table yet</h1>";

					if(is_admin()): ?>
						<a href="<?php echo $add_url ?>"><h2> Add New <?php echo  substr($slug, 0, -1)?> </h2></a>
					<?php endif;
				}
					
			}
		}

		



	}
}
		

if (class_exists('SchoolSystemClass')) {

	$pluginObject = new SchoolSystemClass;
}

register_activation_hook(__FILE__ , [$pluginObject, 'activate'] );// runs activate method of the $pluginObject on activation of the plugin

add_action('admin_menu', [$pluginObject, 'addAdminPages']);
add_action( 'admin_footer', [$pluginObject, 'delete_javascript'] ); // Write our JS below here
add_action( 'wp_ajax_delete', [$pluginObject, 'delete'] );
add_action('wp_safe_redirect', [$pluginObject, 'admin_redirects']);
add_shortcode('addTable', [$pluginObject, 'schoolSystemPage'] );

?>