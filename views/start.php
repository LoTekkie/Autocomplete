<div id="autocomplete-plugin-container">
	<div class="autocomplete-masthead">
		<div class="autocomplete-masthead__inside-container">
			<div class="autocomplete-masthead__logo-container">
        <p id="autocomplete-logo-text"><a href="<?php echo autocomplete_url(); ?>" target="_blank">./AutoComplete.sh</a></p>
			</div>
		</div>
	</div>
	<div class="autocomplete-lower">
		<div class="autocomplete-boxes">
			<?php
        AutoComplete::view( 'activate' );
			?>
		</div>
	</div>
</div>