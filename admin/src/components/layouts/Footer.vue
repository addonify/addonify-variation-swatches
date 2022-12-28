<script setup>
	let { __ } = wp.i18n;
	let versionNumber = ADDONIFY_WISHLIST_LOCOLIZER.version_number;
	let thisYear = new Date().getFullYear();
</script>
<template>
	<footer class="adfy-colopon">
		<div class="adfy-row">
			<div class="adfy-col left">
				<p class="text">
					© 2020 - {{ thisYear }} Addonify WooCommerce Wishlist
					<span class="version"
						>{{ __("Version", "addonify-variation-swatches") }}:
						{{ versionNumber }}</span
					>
				</p>
			</div>
			<div class="adfy-col right">
				<p class="text">
					<a
						href="https://wordpress.org/plugins/addonify-variation-swatches/#reviews"
						class="adfy-link"
						target="_blank"
					>
						{{ __("Rate", "addonify-variation-swatches") }}
						<span class="icon">
							<i class="dashicons dashicons-star-filled"></i>
							<i class="dashicons dashicons-star-filled"></i>
							<i class="dashicons dashicons-star-filled"></i>
							<i class="dashicons dashicons-star-filled"></i>
							<i class="dashicons dashicons-star-filled"></i>
						</span>
						:)
					</a>
				</p>
			</div>
		</div>
	</footer>
</template>
