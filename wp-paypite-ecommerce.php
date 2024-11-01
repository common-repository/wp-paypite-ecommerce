<?php
	/*
		Plugin name: WP Paypite Ecommerce
		Author: Paypite
		Author uri: https://paypite.org
		Description: Vendez en ligne vos produits et services avec Paypite.
		Version: 1.1
		License: GNU General Public License v3
		License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
	*/
	
	require_once("classes/Manager.php");
	require_once("classes/Admin.php");
	require_once("classes/Front_end.php");
	
	$manager = new Paypite\Ecommerce\Manager;
	$manager->require_vendor_id();
	$manager->enqueue_styles();
	$manager->enqueue_scripts();
	$manager->define_constants();
	
	$admin = new Paypite\Ecommerce\Admin;
	$admin->init_admin();
	
	$front_end = new Paypite\Ecommerce\Front_end;
	$front_end->init_shortcode();