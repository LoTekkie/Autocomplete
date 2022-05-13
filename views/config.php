<?php

//phpcs:disable VariableAnalysis
// There are "undefined" variables here because they're defined in the code that includes this file as a template.

?>
<div id="autocomplete-plugin-container">
    <div class="autocomplete-masthead">
        <div class="autocomplete-masthead__inside-container">
            <div class="autocomplete-masthead__logo-container">
                <p><a id="autocomplete-logo-text" href="<?php echo autocomplete_url(); ?>" target="_blank">./AutoComplete.sh</a></p>
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
                                    <span for="key" style="font-size:1.2em;position:relative;bottom:10px;"><?php esc_html_e('AutoComplete API Key', 'autocomplete');?></span>
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

        <br>
        <div class="autocomplete-card">
            <div class="autocomplete-section-header">
                <div class="autocomplete-section-header__label">
                    <span><?php esc_html_e( 'Account Details' , 'autocomplete'); ?></span>
                </div>
            </div>
            <div class="inside">
              <div class="autocomplete-account-details">
                <?php if ( isset($details['username']) ) { ?>
                  <p><?php esc_html_e( 'Username' , 'autocomplete'); ?>: <?php echo ucwords($details['username']) ?></p>
                <?php } ?>
                <?php if ( isset($details['balance']) ) { ?>
                   <p><?php esc_html_e( 'Balance' , 'autocomplete'); ?>: <?php echo number_format($details['balance']) ?></p>
                <?php } ?>
              </div>
            </div>
        </div>

        <br>
        <div class="autocomplete-card">
            <div class="autocomplete-section-header">
                <div class="autocomplete-section-header__label">
                    <span><?php esc_html_e( 'Getting Started' , 'autocomplete'); ?></span>
                </div>
            </div>
            <div class="inside">
              <div class="">

              </div>
            </div>
        </div>

    </div>
</div>
