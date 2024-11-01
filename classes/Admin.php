<?php
	namespace Paypite\Ecommerce;

	//Exit if accessed directly
	if(!defined("ABSPATH")) exit;

	class Admin extends Manager
	{
		protected $currencies = array(
			"EUR", "XOF", "CAD", "BIF", "KMF", "XAF", "DJF", "HTG", "GNF", "MUR", "CHF", "MRO", "RWF", "VUV", "MGA", "SCR"
		);
        
        protected $cryptos = array(
			"PIT", "PIT-EUR", "PIT-MGA", "PIT-XOF"
		);
		
		public function init_admin()
		{
			//Create tables if not exists
			$this->create_tables();
			
			//Add menu in admin's dashboard
			add_action("admin_menu", array($this, "menu"));
		}
		
		public function menu()
		{
			//Parent menu
			add_menu_page( 
				"Paypite Ecommerce", 
				"Paypite", 
				"edit_posts", 
				"wp-paypite-ecommerce", 
				array(
					$this,
					"dashboard"
				), 
				"dashicons-admin-site",
				65
			);
			
			//List of products (same as parent menu) + Update (as tab)
			add_submenu_page(
				"wp-paypite-ecommerce",
				"Liste des produits",
				"Tous les produits",
				"edit_posts",
				"wp-paypite-ecommerce",
				array(
					$this,
					"dashboard"
				)
			);
			
			//Add product
			add_submenu_page(
				"wp-paypite-ecommerce",
				"Ajouter un produit",
				"Ajouter nouveau",
				"edit_posts",
				"wp-add-product-paypite-ecommerce",
				array(
					$this,
					"add"
				)
			);
			
			//Settings
			add_submenu_page(
				"wp-paypite-ecommerce",
				"Paramètres",
				"Paramètres",
				"edit_posts",
				"wp-settings-paypite-ecommerce",
				array(
					$this,
					"settings"
				)
			);
			
			//Shortcode page
			add_submenu_page(
				"wp-paypite-ecommerce",
				"Shortcode",
				"Shortcode",
				"edit_posts",
				"wp-shortcode-paypite-ecommerce",
				array(
					$this,
					"shortcode"
				)
			);
            
			//FAQ
			add_submenu_page(
				"wp-paypite-ecommerce",
				"Foires aux questions",
				"FAQ",
				"edit_posts",
				"wp-faq-paypite-ecommerce",
				array(
					$this,
					"faq"
				)
			);
			
			//Support
			add_submenu_page(
				"wp-paypite-ecommerce",
				"Foires aux questions",
				"Support",
				"edit_posts",
				"wp-support-paypite-ecommerce",
				array(
					$this,
					"support"
				)
			);
		}
		
		public function dashboard()
		{
			//Get product's list
			global $wpdb;
			
			$products_table = $wpdb->prefix . "paypite_products";
			
			//Update product
			if(isset($_POST["update_product"]) && $_POST["update_product"] == true)
			{
				//Sanitizing datas
				$id = sanitize_text_field($_GET["id"]);
				$name = sanitize_text_field($_POST["name"]);
				$currency = sanitize_text_field($_POST["currency"]);
				$crypto = sanitize_text_field($_POST["crypto"]);
				$amount = sanitize_text_field($_POST["amount"]);
				$description = sanitize_textarea_field($_POST["description"]);
				$callback_url = sanitize_text_field($_POST["callback_url"]);
				
				//Validating datas
				!is_numeric($id) ? die(FORM_VALIDATION_ERROR) : "";
				!is_string($name) ? die(FORM_VALIDATION_ERROR) : "";
				!is_string($currency) ? die(FORM_VALIDATION_ERROR) : "";
				!is_string($crypto) ? die(FORM_VALIDATION_ERROR) : "";
				!is_numeric($amount) ? die(FORM_VALIDATION_ERROR) : "";
				!is_string($description) ? die(FORM_VALIDATION_ERROR) : "";
				!is_string($callback_url) ? die(FORM_VALIDATION_ERROR) : "";
				
				//Escaping datas
				$id = $this->escape_input($id, "numeric");
				$name = $this->escape_input($name, "string");
				$currency = $this->escape_input($currency, "string");
				$crypto = $this->escape_input($crypto, "string");
				$amount = $this->escape_input($amount, "numeric");
				$description = $this->escape_input($description, "string");
				$callback_url = $this->escape_input($callback_url, "url");
				
				//Update product using prepare
				$sql_update = $wpdb->prepare(
					"UPDATE $products_table
					SET
						name = %s,
						currency = %s,
						crypto = %s,
						amount = %d,
						description = %s,
						callback_url = %s
					WHERE id = %d",
					array(
						$name, 
						$currency, 
						$crypto, 
						$amount, 
						$description, 
						$callback_url,
						$id
					)
				);
				
				$wpdb->query($sql_update);
				
				$_SESSION["action_performed_notice"] = "Mise à jour effectuée";
			}
			
			if(!isset($_GET["tab"]))
			{
				//Get product list using prepare
				$sql_products = $wpdb->prepare("SELECT * FROM $products_table ORDER BY id DESC", array());
				$products = $wpdb->get_results($sql_products);
				
				require_once(WP_PAYPITE_ECOMMERCE_PLUGIN_URL . "views/admin/list.php");
			}
			else if($_GET["tab"] == "update" && isset($_GET["id"]) && $_GET["id"] != "")
			{
				//Sanitizing id
				$id = sanitize_text_field($_GET["id"]);
				
				//Validating id
				!is_numeric($id) ? die(FORM_VALIDATION_ERROR) : "";
				
				//Escaping id
				$id = $this->escape_input($id, "numeric");
				
				//Update product form using prepare
				$sql_product = $wpdb->prepare("SELECT * FROM $products_table WHERE id = %d", array($id));
				$product = $wpdb->get_results($sql_product);
				
				require_once(WP_PAYPITE_ECOMMERCE_PLUGIN_URL . "views/admin/update.php");
			}
			else if($_GET["tab"] == "delete" && isset($_GET["id"]) && $_GET["id"] != "")
			{
				//Delete product
				if(!isset($_GET["proceed"]))
				{
					//Require confirmation
					require_once(WP_PAYPITE_ECOMMERCE_PLUGIN_URL . "views/admin/confirm-deletion.php");
				}
				else if($_GET["proceed"] == true)
				{
					//Sanitizing id
					$id = sanitize_text_field($_GET["id"]);
					
					//Validating id
					!is_numeric($id) ? die(FORM_VALIDATION_ERROR) : "";
					
					//Escaping id
					$id = $this->escape_input($id, "numeric");
					
					//Actually delete using prepare
					$sql_delete = $wpdb->prepare("DELETE FROM $products_table WHERE id = %d", array($id));
					$wpdb->query($sql_delete);
					
					//Redirect to products list
					echo "<script>window.location.href='?page=wp-paypite-ecommerce'</script>";
				}
			}
			else
			{
				die("Request Error");
			}
		}
		
		public function add()
		{
			global $wpdb;
			
			//Add product
			if(isset($_POST["add_product"]) && $_POST["add_product"] == true)
			{
				//Sanitizing datas
				$name = sanitize_text_field($_POST["name"]);
				$currency = sanitize_text_field($_POST["currency"]);
				$crypto = sanitize_text_field($_POST["crypto"]);
				$amount = sanitize_text_field($_POST["amount"]);
				$description = sanitize_textarea_field($_POST["description"]);
				$callback_url = sanitize_text_field($_POST["callback_url"]);
				
				//Validating datas
				!is_string($name) ? die(FORM_VALIDATION_ERROR) : "";
				!is_string($currency) ? die(FORM_VALIDATION_ERROR) : "";
				!is_string($crypto) ? die(FORM_VALIDATION_ERROR) : "";
				!is_numeric($amount) ? die(FORM_VALIDATION_ERROR) : "";
				!is_string($description) ? die(FORM_VALIDATION_ERROR) : "";
				!is_string($callback_url) ? die(FORM_VALIDATION_ERROR) : "";
				
				//Escaping datas
				$name = $this->escape_input($name, "string");
				$currency = $this->escape_input($currency, "string");
				$crypto = $this->escape_input($crypto, "string");
				$amount = $this->escape_input($amount, "numeric");
				$description = $this->escape_input($description, "string");
				$callback_url = $this->escape_input($callback_url, "url");
				
				$products_table = $wpdb->prefix . "paypite_products";
				
				//Insert with prepare
				$sql_insert = $wpdb->prepare(
					"INSERT INTO $products_table
					(name, currency, crypto, amount, description, callback_url)
					VALUES(%s, %s, %s, %d, %s, %s)",
					array(
						$name,
						$currency,
						$crypto,
						$amount,
						$description,
						$callback_url
					)
				);
				
				$wpdb->query($sql_insert);
				
				$_SESSION["action_performed_notice"] = "Le produit a bien été créé";
			}
			
			require_once(WP_PAYPITE_ECOMMERCE_PLUGIN_URL . "views/admin/add.php");
		}
		
		public function settings()
		{
			//Update settings
			if(isset($_POST["update_settings"]) && $_POST["update_settings"] == true)
			{
				global $wpdb;
				
				$settings_table = $wpdb->prefix . "paypite_settings";
				
				//Sanitizing data
				$vendor_id = sanitize_text_field($_POST["vendor_id"]);
				
				//Validating datas
				!is_string($vendor_id) ? die(FORM_VALIDATION_ERROR) : "";
				
				//Escaping datas
				$vendor_id = $this->escape_input($vendor_id, "string");
				
				//Update settings with prepare
				$sql_update_settings = $wpdb->prepare(
					"UPDATE $settings_table
					SET vendor_id = %s
					WHERE id = 1",
					array($vendor_id)
				);
				
				$wpdb->query($sql_update_settings);
				
				$_SESSION["action_performed_notice"] = "Mise à jour effectuée";
			}
			
			require_once(WP_PAYPITE_ECOMMERCE_PLUGIN_URL . "views/admin/settings.php");
		}
        
		public function faq()
		{
			require_once(WP_PAYPITE_ECOMMERCE_PLUGIN_URL . "views/admin/faq.php");
		}
		
		public function support()
		{
			require_once(WP_PAYPITE_ECOMMERCE_PLUGIN_URL . "views/admin/support.php");
		}
		
		public function shortcode()
		{
			require_once(WP_PAYPITE_ECOMMERCE_PLUGIN_URL . "views/admin/shortcode.php");
		}
		
		public function create_tables()
		{
			//Creating tables if not exisitent using prepare
			global $wpdb;
			
			$settings_table = $wpdb->prefix . "paypite_settings"; 
			$products_table = $wpdb->prefix . "paypite_products"; 
			$transactions_table = $wpdb->prefix . "paypite_transactions"; 
			
			$charset_collate = $wpdb->get_charset_collate();

			$sql_settings = $wpdb->prepare(
				"CREATE TABLE IF NOT EXISTS $settings_table(
						id smallint(3) NOT NULL,
						vendor_id varchar(256) NULL,
						PRIMARY KEY (id)
					) $charset_collate;
				", array()
			);
			
			$sql_default_settings = $wpdb->prepare(
				"INSERT INTO $settings_table
				(id, vendor_id)
				VALUES ('1', '')", array()
			);
			
			$sql_products = $wpdb->prepare(
				"CREATE TABLE IF NOT EXISTS $products_table(
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					name varchar(256) NOT NULL,
					currency varchar(5) NOT NULL,
					crypto varchar(7) NOT NULL,
					amount mediumint(12) NOT NULL,
					description text NOT NULL,
					callback_url varchar(256) NOT NULL,
					PRIMARY KEY (id)
				) $charset_collate;", array()
			);
			
			$sql_transactions = $wpdb->prepare(
				"CREATE TABLE IF NOT EXISTS $transactions_table(
					id mediumint(9) NOT NULL AUTO_INCREMENT,
					product_id mediumint(9) NOT NULL,
					transaction_id_vendor varchar(256) NOT NULL,
					datetime datetime NOT NULL,
					PRIMARY KEY (id)
				) $charset_collate;", array()
			);
			
			$wpdb->query($sql_settings);
			$wpdb->query($sql_default_settings);
			$wpdb->query($sql_products);
			$wpdb->query($sql_transactions);
		}
	}