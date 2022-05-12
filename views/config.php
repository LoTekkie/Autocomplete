<div id="autocomplete-plugin-container">
	<div class="autocomplete-masthead">
		<div class="autocomplete-masthead__inside-container">
			<div class="autocomplete-masthead__logo-container">
        <p><a href="" id="autocomplete-logo-text">./AutoComplete.sh</a></p>
			</div>
		</div>
	</div>
	<div class="autocomplete-lower">
		<?php if ( AutoComplete::get_api_key() ) { ?>
		<?php } ?>
		<?php if ( ! empty( $notices ) ) { ?>
			<?php foreach ( $notices as $notice ) { ?>
				<?php AutoComplete::view( 'notice', ['type' => $notice] ); ?>
			<?php } ?>
		<?php } ?>

    <div class="autocomplete-card">
      <div class="autocomplete-section-header">
        <div class="autocomplete-section-header__label">
          <span><?php esc_html_e( 'Settings' , 'autocomplete'); ?></span>
        </div>
      </div>

      <div class="inside">
        <form action="<?php echo esc_url( AutoComplete_Admin::get_page_url() ); ?>" method="POST">
          <table cellspacing="0" class="autocomplete-settings" style="margin:0;width:100%;">
            <tbody>
              <?php if ( ! AutoComplete::predefined_api_key() ) { ?>
              <tr class="autocomplete-api-key">
                <td align="left">
                    <span for="key" style="font-size:1.2em;position:relative;bottom:10px;"><?php esc_html_e('API Key', 'autocomplete');?></span>
                    <span class="api-key full-max-width" style="margin-top:10px;"><input id="key" name="key" type="text" size="15" value="<?php echo esc_attr( get_option('autocomplete_api_key') ); ?>" style="width:100%;"></span>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          <div class="autocomplete-card-actions">
            <?php if ( ! AutoComplete::predefined_api_key() ) { ?>
            <div id="delete-action">
              <a class="submitdelete deletion" href="<?php echo esc_url( AutoComplete_Admin::get_page_url( 'delete_key' ) ); ?>"><?php esc_html_e('Delete this API Key', 'autocomplete'); ?></a>
            </div>
            <?php } ?>
            <?php wp_nonce_field(AutoComplete_Admin::NONCE) ?>
            <div id="publishing-action">
              <input type="hidden" name="action" value="enter-key">
              <input type="submit" name="submit" id="submit" class="autocomplete-button autocomplete-could-be-primary" value="<?php esc_attr_e('Save Changes', 'autocomplete');?>">
            </div>
            <div class="clear"></div>
          </div>
        </form>
      </div>
    </div>
	</div>
</div>
