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
				<?php AutoComplete::view( 'notice', $notice ); ?>
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
          <table cellspacing="0" class="autocomplete-settings">
            <tbody>
              <?php if ( ! AutoComplete::predefined_api_key() ) { ?>
              <tr>
                <th class="autocomplete-api-key" width="10%" align="left" scope="row"><?php esc_html_e('API Key', 'autocomplete');?></th>
                <td width="5%"/>
                <td align="left">
                  <span class="api-key"><input id="key" name="key" type="text" size="15" value="<?php echo esc_attr( get_option('autocomplete_api_key') ); ?>" class="<?php echo esc_attr( 'regular-text code ' . $autocomplete_user->status ); ?>"></span>
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
