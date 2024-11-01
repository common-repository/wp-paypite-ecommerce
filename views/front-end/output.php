<div class="paypite-wrapper">
	<?php
		//Output payment status
		if(isset($_GET["statut"]))
		{
			//Sanitizing status
			$status = sanitize_text_field($_GET["statut"]);
			
			//Validating status
			!is_string($status) ? die(FORM_VALIDATION_ERROR) : "";
			
			//Escaping status
			$status = $this->escape_input($status, "string");
			
			//Retain only the status parameter on the URL (avoid bug on resubmit purchase)
			if(isset($_GET["payeur_id"]))
			{
				
				
				$url = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH) . "?statut=" . $status;
				
				?>
					<script>
						window.location.href = "<?php echo $url; ?>";
					</script>
				<?php
			}
			
			?>
				<div class="paypite-notification paypite-text-green paypite-text-bold paypite-pb-1">
					<?php
						if($status == "ANNULE")
						{
							echo "Le paiement a été annulé";
						}
						else if($status == "TRANSFERE")
						{
							echo "Le paiement a bien été effectué";
						}
						else
						{
							echo "";
						}
					?>
				</div>
			<?php
		}
	?>
	
	<?php
		if($show_details == "yes")
		{
			?>
				<div class="paypite-infos">
					<label>Nom du produit:</label> <?php echo $product->name; ?>
				</div>
				
				<div class="paypite-infos">
					<label>Prix:</label> <?php echo $product->amount . " " . $product->currency; ?>
				</div>
				
				<div class="paypite-infos paypite-pb-1">
					<label>Description:</label><br /><?php echo $product->description; ?>
				</div>
			<?php
		}
	?>
	
	<div class="paypite-button">
		<?php
			//Define button URL with parameters
            $url = "https://compte.paypite.fr/integration/paiement";
			$url .= "?devise=" . $product->currency;
			$url .= "&crypto=" . $product->crypto;
			$url .= "&montant=" . $product->amount;
			$url .= "&description=" . $product->description;
			$url .= "&idTransactionVendeur=" . $this->generate_transaction_id();
			$url .= "&idVendeur=" . $this->get_vendor_id();
			$url .= "&callback=" . $this->get_callback($product->id);
		
            //Define button crypto text
            if($product->crypto === "PIT")
            {
                $crypto_button_text = "paypites";
            }
            else
            {
                $crypto_button_text = $product->crypto;
            }
        ?>
	
		<a href="<?php echo $url; ?>">Payer en <?php echo $crypto_button_text; ?></a>
	</div>
<div>