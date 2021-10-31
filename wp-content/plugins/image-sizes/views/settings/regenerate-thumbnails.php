<p class="cxis-desc"><?php _e( 'Since you updated the thumbnails to be generated, images you\'ll upload in the future will generate thumbnails based on your settings. But, what about the ones you already uploaded? Click the button below and it\'ll regenerate thumbnails of your existing images.', 'image-sizes' ); ?></p>

<div id="cxis-regen-wrap" class="cxis-regen-thumbs-panel">
	<p>
		<label for="image-sizes_regenerate-thumbs-limit"><?php _e( 'Chunk size:', 'image-sizes' ) ?></label>
		<input type="number" class="cx-field cx-field-number" id="image-sizes_regenerate-thumbs-limit" name="regen-thumbs-limit" value="50" placeholder="<?php _e( 'Images/request. Default is 50', 'image-sizes' ) ?>" required="">
		<span title="<?php _e( 'Number of images to process per request. More value means quicker result, but may arise issues on some low configuration servers. Recommended value is 50.', 'image-sizes' ) ?>">🤔</span>
	</p>
	<button id="cxis-regen-thumbs" class="button button-hero"><?php _e( 'Regenerate', 'image-sizes' ); ?></button>
</div>

<div id="cxis-message" style="display: none;"></div>