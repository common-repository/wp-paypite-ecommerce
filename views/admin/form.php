<?php
	$update_tab = false;
	
	$name = "";
	$product_currency = "";
	$product_crypto = "";
	$amount = "";
	$description = "";
	$callback_url = "";
	
	if(isset($_GET["tab"]) && $_GET["tab"] == "update")
	{
		$update_tab = true;
		
		$name = $product[0]->name;
		$product_currency = $product[0]->currency;
		$product_crypto = $product[0]->crypto;
		$amount = $product[0]->amount;
		$description = $product[0]->description;
		$callback_url = $product[0]->callback_url;
	}
?>

<form method="post" action="" class="paypite-form insert-update">
	<div class="paypite-mb-1">
		<label for="name">Nom du produit</label>
		<input type="text" name="name" id="name" maxlength="50" value="<?php echo $name; ?>" required />
	</div>

	<div class="price paypite-mb-1">
		<div>
			<label for="currency">Devise</label>
			<select name="currency" id="currency">
				<?php
					foreach($this->currencies as $currency)
					{
						?>
							<option value="<?php echo $currency; ?>" <?php echo ($currency == $product_currency ? "selected" : ""); ?>><?php echo $currency; ?></option>
						<?php
					}
				?>
			</select>
		</div>
        
        <div>
			<label for="crypto">Crypto<span class="dashicons dashicons-editor-help paypite-dashicon" title="<?php echo CRYPTO_HELP; ?>"></span></label>
			<select name="crypto" id="crypto">
				<?php
					foreach($this->cryptos as $crypto)
					{
						?>
							<option value="<?php echo $crypto; ?>" <?php echo ($crypto == $product_crypto ? "selected" : ""); ?>><?php echo $crypto; ?></option>
						<?php
					}
				?>
			</select>
		</div>
		
		<div>
			<label for="amount">Montant</label>
			<input type="number" name="amount" id="amount" value="<?php echo $amount; ?>" onKeyPress="if(this.value.length==6 && event.keyCode!=6) return false;" required />
		</div>
	</div>
	
	<div class="paypite-mb-1">
		<label for="description">Description</label>
		<textarea name="description" id="description" required><?php echo $description; ?></textarea>
	</div>
	
	<div class="paypite-mb-1">
		<label for="callback_url">URL de callback (optionnel)<span class="dashicons dashicons-editor-help paypite-dashicon" title="<?php echo CALLBACK_URL_HELP; ?>"></span></label>
		<input type="text" name="callback_url" id="callback_url" value="<?php echo $callback_url; ?>" />
	</div>
	
	<div>
		<input type="hidden" name="<?php echo (!$update_tab ? "add_product" : "update_product"); ?>" value="1" />
		<input type="submit" name="submit" value="<?php echo (!$update_tab ? "Ajouter" : "Mettre à jour"); ?>" />
	</div>
	
	<?php
		require_once(WP_PAYPITE_ECOMMERCE_PLUGIN_URL . "views/admin/action-performed-notice.php");
	?>
</form>