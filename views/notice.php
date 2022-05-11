<?php

//phpcs:disable VariableAnalysis
// There are "undefined" variables here because they're defined in the code that includes this file as a template.

?>
<?php if ( $type == 'plugin' ) : ?>
<div class="updated" id="autocomplete_setup_prompt">
	<form name="autocomplete_activate" action="<?php echo esc_url( AutoComplete_Admin::get_page_url() ); ?>" method="POST">
		<div class="autocomplete_activate">
			<div class="aa_button_container">
				<div class="aa_button_border">
					<input type="submit" class="aa_button" value="<?php esc_attr_e( 'Configure AutoComplete.sh', 'autocomplete' ); ?>" />
				</div>
			</div>
			<div class="aa_description"><?php _e('<strong>Almost done</strong> - Configure AutoComplete.sh and say goodbye to manually typing blog posts!', 'autocomplete');?></div>
		</div>
	</form>
</div>
<?php elseif ( $type == 'spam-check' ) : ?>
<div class="notice notice-warning">
	<p><strong><?php esc_html_e( 'autocomplete has detected a problem.', 'autocomplete' );?></strong></p>
	<p><?php esc_html_e( 'Some comments have not yet been checked for spam by autocomplete. They have been temporarily held for moderation and will automatically be rechecked later.', 'autocomplete' ); ?></p>
	<?php if ( $link_text ) { ?>
		<p><?php echo $link_text; ?></p>
	<?php } ?>
</div>
<?php elseif ( $type == 'alert' ) : ?>
<div class='error'>
	<p><strong><?php printf( esc_html__( 'autocomplete Error Code: %s', 'autocomplete' ), $code ); ?></strong></p>
	<p><?php echo esc_html( $msg ); ?></p>
	<p><?php

	/* translators: the placeholder is a clickable URL that leads to more information regarding an error code. */
	printf( esc_html__( 'For more information: %s' , 'autocomplete'), '<a href="https://autocomplete.com/errors/' . $code . '">https://autocomplete.com/errors/' . $code . '</a>' );

	?>
	</p>
</div>
<?php elseif ( $type == 'notice' ) : ?>
<div class="autocomplete-alert autocomplete-critical">
	<h3 class="autocomplete-key-status failed"><?php echo $notice_header; ?></h3>
	<p class="autocomplete-description">
		<?php echo $notice_text; ?>
	</p>
</div>
<?php elseif ( $type == 'missing-functions' ) : ?>
<div class="autocomplete-alert autocomplete-critical">
	<h3 class="autocomplete-key-status failed"><?php esc_html_e('Network functions are disabled.', 'autocomplete'); ?></h3>
	<p class="autocomplete-description"><?php printf( __('Your web host or server administrator has disabled PHP&#8217;s <code>gethostbynamel</code> function.  <strong>autocomplete cannot work correctly until this is fixed.</strong>  Please contact your web host or firewall administrator and give them <a href="%s" target="_blank">this information about autocomplete&#8217;s system requirements</a>.', 'autocomplete'), 'https://blog.autocomplete.com/autocomplete-hosting-faq/'); ?></p>
</div>
<?php elseif ( $type == 'servers-be-down' ) : ?>
<div class="autocomplete-alert autocomplete-critical">
	<h3 class="autocomplete-key-status failed"><?php esc_html_e("Your site can&#8217;t connect to the autocomplete servers.", 'autocomplete'); ?></h3>
	<p class="autocomplete-description"><?php printf( __('Your firewall may be blocking autocomplete from connecting to its API. Please contact your host and refer to <a href="%s" target="_blank">our guide about firewalls</a>.', 'autocomplete'), 'https://blog.autocomplete.com/autocomplete-hosting-faq/'); ?></p>
</div>
<?php elseif ( $type == 'active-dunning' ) : ?>
<div class="autocomplete-alert autocomplete-critical">
	<h3 class="autocomplete-key-status"><?php esc_html_e("Please update your payment information.", 'autocomplete'); ?></h3>
	<p class="autocomplete-description"><?php printf( __('We cannot process your payment. Please <a href="%s" target="_blank">update your payment details</a>.', 'autocomplete'), 'https://autocomplete.com/account/'); ?></p>
</div>
<?php elseif ( $type == 'cancelled' ) : ?>
<div class="autocomplete-alert autocomplete-critical">
	<h3 class="autocomplete-key-status"><?php esc_html_e("Your autocomplete plan has been cancelled.", 'autocomplete'); ?></h3>
	<p class="autocomplete-description"><?php printf( __('Please visit your <a href="%s" target="_blank">autocomplete account page</a> to reactivate your subscription.', 'autocomplete'), 'https://autocomplete.com/account/'); ?></p>
</div>
<?php elseif ( $type == 'suspended' ) : ?>
<div class="autocomplete-alert autocomplete-critical">
	<h3 class="autocomplete-key-status failed"><?php esc_html_e("Your autocomplete subscription is suspended.", 'autocomplete'); ?></h3>
	<p class="autocomplete-description"><?php printf( __('Please contact <a href="%s" target="_blank">autocomplete support</a> for assistance.', 'autocomplete'), 'https://autocomplete.com/contact/'); ?></p>
</div>
<?php elseif ( $type == 'active-notice' && $time_saved ) : ?>
<div class="autocomplete-alert autocomplete-active">
	<h3 class="autocomplete-key-status"><?php echo esc_html( $time_saved ); ?></h3>
	<p class="autocomplete-description"><?php printf( __('You can help us fight spam and upgrade your account by <a href="%s" target="_blank">contributing a token amount</a>.', 'autocomplete'), 'https://autocomplete.com/account/upgrade/'); ?></p>
</div>
<?php elseif ( $type == 'missing' ) : ?>
<div class="autocomplete-alert autocomplete-critical">
	<h3 class="autocomplete-key-status failed"><?php esc_html_e( 'There is a problem with your API key.', 'autocomplete'); ?></h3>
	<p class="autocomplete-description"><?php printf( __('Please contact <a href="%s" target="_blank">autocomplete support</a> for assistance.', 'autocomplete'), 'https://autocomplete.com/contact/'); ?></p>
</div>
<?php elseif ( $type == 'no-sub' ) : ?>
<div class="autocomplete-alert autocomplete-critical">
	<h3 class="autocomplete-key-status failed"><?php esc_html_e( 'You don&#8217;t have an autocomplete plan.', 'autocomplete'); ?></h3>
	<p class="autocomplete-description">
		<?php printf( __( 'In 2012, autocomplete began using subscription plans for all accounts (even free ones). A plan has not been assigned to your account, and we&#8217;d appreciate it if you&#8217;d <a href="%s" target="_blank">sign into your account</a> and choose one.', 'autocomplete'), 'https://autocomplete.com/account/upgrade/' ); ?>
		<br /><br />
		<?php printf( __( 'Please <a href="%s" target="_blank">contact our support team</a> with any questions.', 'autocomplete' ), 'https://autocomplete.com/contact/' ); ?>
	</p>
</div>
<?php elseif ( $type == 'new-key-valid' ) :
	global $wpdb;
	
	$check_pending_link = false;
	
	$at_least_one_comment_in_moderation = !! $wpdb->get_var( "SELECT comment_ID FROM {$wpdb->comments} WHERE comment_approved = '0' LIMIT 1" );
	
	if ( $at_least_one_comment_in_moderation)  {
		$check_pending_link = 'edit-comments.php?autocomplete_recheck=' . wp_create_nonce( 'autocomplete_recheck' );
	}
	
	?>
<div class="autocomplete-alert autocomplete-active">
	<h3 class="autocomplete-key-status"><?php esc_html_e( 'autocomplete is now protecting your site from spam. Happy blogging!', 'autocomplete' ); ?></h3>
	<?php if ( $check_pending_link ) { ?>
		<p class="autocomplete-description"><?php printf( __( 'Would you like to <a href="%s">check pending comments</a>?', 'autocomplete' ), esc_url( $check_pending_link ) ); ?></p>
	<?php } ?>
</div>
<?php elseif ( $type == 'new-key-invalid' ) : ?>
<div class="autocomplete-alert autocomplete-critical">
	<h3 class="autocomplete-key-status"><?php esc_html_e( 'The key you entered is invalid. Please double-check it.' , 'autocomplete'); ?></h3>
</div>
<?php elseif ( $type == 'existing-key-invalid' ) : ?>
<div class="autocomplete-alert autocomplete-critical">
	<h3 class="autocomplete-key-status"><?php echo esc_html( __( 'Your API key is no longer valid.', 'autocomplete' ) ); ?></h3>
	<p class="autocomplete-description">
		<?php

		echo wp_kses(
			sprintf(
				/* translators: The placeholder is a URL. */
				__( 'Please enter a new key or <a href="%s" target="_blank">contact autocomplete support</a>.', 'autocomplete' ),
				'https://autocomplete.com/contact/'
			),
			array(
				'a' => array(
					'href' => true,
					'target' => true,
				),
			)
		);

		?>
	</p>
</div>
<?php elseif ( $type == 'new-key-failed' ) : ?>
<div class="autocomplete-alert autocomplete-critical">
	<h3 class="autocomplete-key-status"><?php esc_html_e( 'The API key you entered could not be verified.' , 'autocomplete'); ?></h3>
	<p class="autocomplete-description">
		<?php

		echo wp_kses(
			sprintf(
				/* translators: The placeholder is a URL. */
				__( 'The connection to autocomplete.com could not be established. Please refer to <a href="%s" target="_blank">our guide about firewalls</a> and check your server configuration.', 'autocomplete' ),
				'https://blog.autocomplete.com/autocomplete-hosting-faq/'
			),
			array(
				'a' => array(
					'href' => true,
					'target' => true,
				),
			)
		);

		?>
	</p>
</div>
<?php elseif ( $type == 'limit-reached' && in_array( $level, array( 'yellow', 'red' ) ) ) : ?>
<div class="autocomplete-alert autocomplete-critical">
	<?php if ( $level == 'yellow' ): ?>
	<h3 class="autocomplete-key-status failed"><?php esc_html_e( 'You&#8217;re using your autocomplete key on more sites than your Plus subscription allows.', 'autocomplete' ); ?></h3>
	<p class="autocomplete-description">
		<?php

		echo wp_kses(
			sprintf(
				/* translators: The placeholder is a URL. */
				__( 'Your Plus subscription allows the use of autocomplete on only one site. Please <a href="%s" target="_blank">purchase additional Plus subscriptions</a> or upgrade to an Enterprise subscription that allows the use of autocomplete on unlimited sites.', 'autocomplete' ),
				'https://docs.autocomplete.com/billing/add-more-sites/'
			),
			array(
				'a' => array(
					'href' => true,
					'target' => true,
				),
			)
		);

		?>
		<br /><br />
		<?php printf( __( 'Please <a href="%s" target="_blank">contact our support team</a> with any questions.', 'autocomplete' ), 'https://autocomplete.com/contact/'); ?>
	</p>
	<?php elseif ( $level == 'red' ): ?>
	<h3 class="autocomplete-key-status failed"><?php esc_html_e( 'You&#8217;re using autocomplete on far too many sites for your Plus subscription.', 'autocomplete' ); ?></h3>
	<p class="autocomplete-description">
		<?php printf( __( 'To continue your service, <a href="%s" target="_blank">upgrade to an Enterprise subscription</a>, which covers an unlimited number of sites.', 'autocomplete'), 'https://autocomplete.com/account/upgrade/' ); ?>
		<br /><br />
		<?php printf( __( 'Please <a href="%s" target="_blank">contact our support team</a> with any questions.', 'autocomplete' ), 'https://autocomplete.com/contact/'); ?>
	</p>
	<?php endif; ?>
</div>
<?php elseif ( $type == 'usage-limit' && isset( AutoComplete::$limit_notices[ $code ] ) ) : ?>
<div class="error autocomplete-usage-limit-alert">
	<div class="autocomplete-usage-limit-logo">
		<img src="<?php echo esc_url( plugins_url( '../_inc/img/logo-a-2x.png', __FILE__ ) ); ?>" alt="autocomplete" />
	</div>
	<div class="autocomplete-usage-limit-text">
		<h3>
		<?php
		switch ( AutoComplete::$limit_notices[ $code ] ) {
			case 'FIRST_MONTH_OVER_LIMIT':
			case 'SECOND_MONTH_OVER_LIMIT':
				esc_html_e( 'Your autocomplete account usage is over your plan&#8217;s limit', 'autocomplete' );
				break;
			case 'THIRD_MONTH_APPROACHING_LIMIT':
				esc_html_e( 'Your autocomplete account usage is approaching your plan&#8217;s limit', 'autocomplete' );
				break;
			case 'THIRD_MONTH_OVER_LIMIT':
			case 'FOUR_PLUS_MONTHS_OVER_LIMIT':
				esc_html_e( 'Your account has been restricted', 'autocomplete' );
				break;
			default:
		}
		?>
		</h3>
		<p>
		<?php
		switch ( AutoComplete::$limit_notices[ $code ] ) {
			case 'FIRST_MONTH_OVER_LIMIT':
				echo esc_html(
					sprintf(
						/* translators: The first placeholder is a date, the second is a (formatted) number, the third is another formatted number. */
						__( 'Since %1$s, your account made %2$s API calls, compared to your plan&#8217;s limit of %3$s.', 'autocomplete' ),
						esc_html( gmdate( 'F' ) . ' 1' ),
						number_format( $api_calls ),
						number_format( $usage_limit )
					)
				);

				echo '<a href="https://docs.autocomplete.com/autocomplete-api-usage-limits/" target="_blank">';
				echo esc_html( __( 'Learn more about usage limits.', 'autocomplete' ) );
				echo '</a>';

				break;
			case 'SECOND_MONTH_OVER_LIMIT':
				echo esc_html( __( 'Your autocomplete usage has been over your plan&#8217;s limit for two consecutive months. Next month, we will restrict your account after you reach the limit. Please consider upgrading your plan.', 'autocomplete' ) );

				echo '<a href="https://docs.autocomplete.com/autocomplete-api-usage-limits/" target="_blank">';
				echo esc_html( __( 'Learn more about usage limits.', 'autocomplete' ) );
				echo '</a>';

				break;
			case 'THIRD_MONTH_APPROACHING_LIMIT':
				echo esc_html( __( 'Your autocomplete usage is nearing your plan&#8217;s limit for the third consecutive month. We will restrict your account after you reach the limit. Upgrade your plan so autocomplete can continue blocking spam.', 'autocomplete' ) );

				echo '<a href="https://docs.autocomplete.com/autocomplete-api-usage-limits/" target="_blank">';
				echo esc_html( __( 'Learn more about usage limits.', 'autocomplete' ) );
				echo '</a>';

				break;
			case 'THIRD_MONTH_OVER_LIMIT':
			case 'FOUR_PLUS_MONTHS_OVER_LIMIT':
				echo esc_html( __( 'Your autocomplete usage has been over your plan&#8217;s limit for three consecutive months. We have restricted your account for the rest of the month. Upgrade your plan so autocomplete can continue blocking spam.', 'autocomplete' ) );

				echo '<a href="https://docs.autocomplete.com/autocomplete-api-usage-limits/" target="_blank">';
				echo esc_html( __( 'Learn more about usage limits.', 'autocomplete' ) );
				echo '</a>';

				break;
			default:
		}
		?>
		</p>
	</div>
	<div class="autocomplete-usage-limit-cta">
		<a href="<?php echo esc_attr( $upgrade_url ); ?>" class="button" target="_blank">
			<?php
			// If only a qty upgrade is required, show a more generic message.
			if ( ! empty( $upgrade_type ) && 'qty' === $upgrade_type ) {
				esc_html_e( 'Upgrade your Subscription Level', 'autocomplete' );
			} else {
				echo esc_html(
					sprintf(
						/* translators: The placeholder is the name of a subscription level, like "Plus" or "Enterprise" . */
						__( 'Upgrade to %s', 'autocomplete' ),
						$upgrade_plan
					)
				);
			}
			?>
		</a>
	</div>
</div>
<?php endif; ?>
