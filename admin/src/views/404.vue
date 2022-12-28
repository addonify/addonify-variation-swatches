<script setup>
	let { __ } = wp.i18n;
</script>
<template>
	<div class="error-404">
		<h3>{{ __("404", "addonify-variation-swatches") }}</h3>
		<p>{{ __("Oops, page not found!", "addonify-variation-swatches") }}</p>
		<router-link to="/" class="adfy-button">
			{{ __("Go Back", "addonify-variation-swatches") }}
		</router-link>
	</div>
</template>
