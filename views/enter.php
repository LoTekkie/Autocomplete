<div class=" autocomplete-enter-api-key-box centered">
  <a href="#"><?php esc_html_e( 'Manually enter an API key', 'autocomplete' ); ?></a>
	<div class="hidden enter-api-key">
		<form action="<?php echo esc_url( AutoComplete_Admin::get_page_url() ); ?>" method="post">
			<?php wp_nonce_field( AutoComplete_Admin::NONCE ) ?>
			<input type="hidden" name="action" value="enter-key">
			<div style="display:flex;flex-flow: row nowrap;justify-content:space-between;align-items:center;padding:0 15px">
				<input id="key" name="key" type="text" size="15" value="<?php AutoComplete::get_api_key() ?? '' ?>" placeholder="<?php esc_attr_e( 'Enter your AutoComplete.sh API key' , 'autocomplete' ); ?>" class="regular-text code" style="flex-grow: 1; margin-right: 1rem;">
				<input type="submit" name="submit" id="submit" class="autocomplete-button" value="<?php esc_attr_e( 'Use this Key', 'autocomplete' );?>">
			</div>
		</form>
	</div>
</div>