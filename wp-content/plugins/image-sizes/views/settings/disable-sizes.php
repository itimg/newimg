<?php
namespace codexpert\Image_Sizes;
$image_sizes 	= $args['image_sizes'] ? : [];
$selected_sizes = Helper::get_option( 'prevent_image_sizes', 'disables', [] );

echo '<div id="cxis-wrap"><p class="cxis-desc">' . sprintf( __( 'You currently have <strong>%1$d thumbnails</strong> registered. It means, if you upload an image, it\'ll generate %1$d duplicates of that image. Select the sizes from the list below that you want to disable from generating.', 'image-sizes' ), count( get_option( '_image-sizes', [] ) ) ) . '</p>';
echo '

	<div id="check-wrap">
		<p><strong>' . __( 'Disable Thumbnails', 'image-sizes' ) . '</strong></p>
		<div id="check-all-wrap">
			<label for="check-all">' . __( 'All', 'image-sizes' ) . '</label>
			<label class="cxis-switch">
			  <input type="checkbox" id="check-all" class="checkbox check-all cxis-switch-checkbox">
			  <span class="cxis-slider round"></span>
			</label>
		</div>
		<div id="check-default-wrap">
			<label for="check-default">' . __( 'Default', 'image-sizes' ) . '</label>
			<label class="cxis-switch">
			  <input type="checkbox" id="check-default" class="checkbox check-all-default cxis-switch-checkbox">
			  <span class="cxis-slider round"></span>
			</label>
		</div>
		<div id="check-custom-wrap">
			<label for="check-custom">' . __( 'Custom', 'image-sizes' ) . '</label>
			<label class="cxis-switch">
			  <input type="checkbox" id="check-custom" class="checkbox check-all-custom cxis-switch-checkbox">
			  <span class="cxis-slider round"></span>
			</label>
		</div>
	</div>

	<div id="sizes-counter">
		<div id="disabled-counter" class="size-counter">
			<span class="counter">6</span> ' . __( 'thumbnails disabled', 'image-sizes' ) . '
		</div>
		<div id="enabled-counter" class="size-counter">
			<span class="counter">4</span> ' . __( 'thumbnails will be generated', 'image-sizes' ) . '
		</div>
	</div>
</div>

<table class="form-table" id="cxis-form-table">
	<thead>
		<tr>
			<th>' . __( 'Disable Thumbnail', 'image-sizes' ) . '</th>
			<th>' . __( 'Name', 'image-sizes' ) . '</th>
			<th>' . __( 'Type', 'image-sizes' ) . '</th>
			<th>' . __( 'Width (px)', 'image-sizes' ) . '</th>
			<th>' . __( 'Height (px)', 'image-sizes' ) . '</th>
			<th>' . __( 'Cropped?', 'image-sizes' ) . '</th>
		</tr>
	</thead>
	<tbody>
		<tr id="row-main-file">
			<td class="cxis-switch-col">
				<label class="cxis-switch">
				  <input type="checkbox" class="cxis-switch-checkbox" disabled>
				  <span class="cxis-slider round"></span>
				</label>
			</td>
			<td>' . __( 'Original Image', 'image-sizes' ) . '</td>
			<td>' . __( 'original', 'image-sizes' ) . '</td>
			<td>' . __( 'auto', 'image-sizes' ) . '</td>
			<td>' . __( 'auto', 'image-sizes' ) . '</td>
			<td>' . __( 'No', 'image-sizes' ) . '</td>
		</tr>
';

foreach ( $image_sizes as $id => $size ) {
	$_checked = in_array( $id, $selected_sizes ) ? 'checked' : '';
	$_cropped = $size['cropped'] ? __( 'Yes', 'image-sizes' ) : __( 'No', 'image-sizes' );
	echo "
		<tr id='row-{$id}'>
			<td class='cxis-switch-col'>
				<label class='cxis-switch'>
				  <input type='checkbox' class='checkbox check-this check-{$size['type']} cxis-switch-checkbox' name='disables[]' value='{$id}' {$_checked}>
				  <span class='cxis-slider round'></span>
				</label>
			</td>
			<td>{$id}</td>
			<td>{$size['type']}</td>
			<td>{$size['width']}</td>
			<td>{$size['height']}</td>
			<td>{$_cropped}</td>
		</tr>";
}

echo '
	</tbody>
</table>
';