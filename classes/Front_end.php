<?php
	namespace Paypite\Ecommerce;

	//Exit if accessed directly
	if(!defined("ABSPATH")) exit;
	
	class Front_end extends Manager
	{
		public function init_shortcode()
		{
			add_shortcode("paypite", array($this, "shortcode"));
		}
		
		public function shortcode($atts)
		{
			if(!isset($atts["id"]))
			{
				echo "Ce produit n'existe pas ou a été supprimé";
			}
			else
			{
				global $wpdb;
				
				//Get attributes
				$id = $atts["id"];
				
				if(isset($atts["show_details"]) && ($atts["show_details"] == "yes" || $atts["show_details"] == "YES"))
				{
					$show_details = strtolower($atts["show_details"]);
				}
				else
				{
					$show_details = "no";
				}
				
				//Get product infos using prepare
				$products_table = $wpdb->prefix . "paypite_products";
				
				$sql_product = $wpdb->prepare(
					"SELECT *
					FROM $products_table
					WHERE id = $id",
					array()
				);
				
				$product = $wpdb->get_results($sql_product);
				
				$product = $product[0];
				
				//Output results
				if(!empty($product))
				{
					require_once(WP_PAYPITE_ECOMMERCE_PLUGIN_URL . "views/front-end/output.php");
				}
				else
				{
					echo "Ce produit n'existe pas ou a été supprimé";
				}
			}
		}
		
		public function generate_transaction_id()
		{
			$random_string = substr(md5(mt_rand()), 0, 8);
		
			return date("Ymdhis") . "_" . $random_string;
		}
		
		public function get_callback($product_id)
		{
			//Get callback using prepare
			global $wpdb;
			
			$products_table = $wpdb->prefix . "paypite_products";
			
			$sql_callback = $wpdb->prepare(
				"SELECT callback_url
				FROM $products_table
				WHERE id = $product_id",
				array()
			);
			
			$callback = $wpdb->get_var($sql_callback);
			
			if($callback == "")
			{
				return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]" . parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
			}
			else
			{
				return $callback;
			}
		}
	}