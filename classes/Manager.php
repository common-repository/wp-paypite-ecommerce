<?php
	namespace Paypite\Ecommerce;

	//Exit if accessed directly
	if(!defined("ABSPATH")) exit;
	
	class Manager
	{
		public function require_vendor_id()
		{
			//Require vendor ID
			if($this->get_vendor_id() == "" && isset($_GET["page"]) && $_GET["page"] != "wp-settings-paypite-ecommerce")
			{
				echo "<script>window.location.href='?page=wp-settings-paypite-ecommerce';</script>";
			}
		}
		
		public function enqueue_styles()
		{
			add_action("admin_init", array($this, "_enqueue_styles"));
			add_action("wp_head", array($this, "_enqueue_styles"));
		}
		
		public function enqueue_scripts()
		{
			add_action("admin_footer", array($this, "_enqueue_scripts"));
		}
		
		public function define_constants()
		{
			define(FORM_VALIDATION_ERROR, "Erreur dans la requête");
			define(WP_PAYPITE_ECOMMERCE_PLUGIN_URL, plugin_dir_path(__DIR__));
			define(CRYPTO_HELP, "La cryptomonnaie avec laquelle vous souhaitez encaisser les ventes dans votre portefeuille.");
			define(CALLBACK_URL_HELP, "C’est l’URL à laquelle la personne devra être redirigée après le paiement. Si vous n'en spécifiez pas, sa valeur sera l'URL de la page sur laquelle le produit est affiché.");
		}
		
		public function get_vendor_id()
		{
			//Getting vendor id using prepare
			global $wpdb;
			
			$settings_table = $wpdb->prefix . "paypite_settings";
			
			$sql_vendor_id = $wpdb->prepare(
				"SELECT vendor_id
				FROM $settings_table
				WHERE id = 1",
				array()
			);
			
			return $wpdb->get_var($sql_vendor_id);
		}
		
		public function escape_input($text, $type)
		{
			$text = esc_html($text);
			$text = esc_attr($text);
			$text = esc_js($text);
			$text = esc_textarea($text);
			
			if($type == "url")
			{
				$text = esc_url($text);
			}
			
			return $text;
		}
		
		public function _enqueue_styles()
		{
			wp_enqueue_style("admin-style", plugins_url("assets/css/styles.css", dirname(__FILE__)));
		}
		
		public function _enqueue_scripts()
		{
			wp_enqueue_script("admin-script", plugins_url("assets/javascript/scripts.js", dirname(__FILE__)));
		}
	}