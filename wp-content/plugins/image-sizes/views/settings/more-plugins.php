<?php
$plugins = [
	'woolementor'	=> [
		'name'	=> __( 'Woolementor', 'image-sizes' ),
		'url'	=> 'https://codexpert.io/woolementor',
		'desc'	=> __( 'Every part of your WooCommerce store can now be edited with Elementor. Be it your Shop or cart page, or even checkout fields. Everything!
			A great shop and checkout always boost your sales. <strong>Woolementor</strong> helps you do just that.', 'image-sizes' ),
		'thumb'	=> plugins_url( 'assets/img/woolementor.png', CXIS )
	],
	'wc-affiliate'	=> [
		'name'	=> __( 'WC Affiliate', 'image-sizes' ),
		'url'	=> 'https://codexpert.io/wc-affiliate',
		'desc'	=> __( 'The most feature-rich WooCommerce affiliate plugin. <strong>WC Affiliate</strong> helps you build a full-featured affiliate program for your online site.
			Multi-level commissions, cross-domain cookie sharing, automated payout, shortlink and much more!', 'image-sizes' ),
		'thumb'	=> plugins_url( 'assets/img/wc-affiliate.png', CXIS )
	],
	'share-logins'	=> [
		'name'	=> __( 'Share Logins', 'image-sizes' ),
		'url'	=> 'https://share-logins.com',
		'desc'	=> __( 'It\'s always boring for your users to register and log in to different sites. This is where <strong>Share Logins</strong> came into picture.
			With this one-of-a-kind plugin, you can sync your userbase accross multiple sites. Even if they use different database on different domains!', 'image-sizes' ),
		'thumb'	=> plugins_url( 'assets/img/share-logins.png', CXIS )
	]
];

$utm = [
	'utm_campaign'	=> 'image-sizes',
	'utm_source'	=> 'free-plugins',
	'utm_medium'	=> 'settings-page',
];

echo '<p class="cxis-desc">Supercharge your WordPress sites with these exclusive plugins! 🤩</p>';
echo '<div id="cxis-more-plugins">';
	
	foreach ( $plugins as $slug => $plugin ) {
		$url = add_query_arg( $utm, $plugin['url'] );
		echo "
		<div class='cxis-plugin' id='cxis-{$slug}'>
			<div class='cxis-thumb-wrap'>
				<a href='{$url}' target='_blank'><img class='cxis-thumb' src='{$plugin['thumb']}' /></a>
			</div>
			<div class='cxis-name-wrap'>
				<a href='{$url}' target='_blank'><h2 class='cxis-name'>{$plugin['name']}</h2></a>
			</div>
			<div class='cxis-desc-wrap'>";
				foreach ( explode( PHP_EOL, $plugin['desc'] ) as $line ) {
					echo "<p class='cxis-desc'>{$line}</p>";
				}
			echo "
			</div>
			<div class='cxis-url-wrap'>
				<a class='cxis-url' href='{$url}' target='_blank'>" . __( 'Learn More..', 'image-sizes' ) . "</a>
			</div>
		</div>";
	}

	echo "
	<div class='cxis-plugin' id='cxis-custom'>
		<div class='cxis-name-wrap'>
			<h2 class='cxis-name'>" . __( 'Something else?', 'image-sizes' ) . "</h2>
		</div>
		<div class='cxis-desc-wrap'>
			<p class='cxis-desc'>Even there are thousands of plugins available out there, we know sometimes none of them fits your requirements and you need a <strong>custom solution</strong> built especially for you.</p>
			<p class='cxis-desc'>We understand your situation and are available for custom developments. We're a team of some talented developers working exclusively with WordPress for more than a decade now!</p>
			<p class='cxis-desc'>Please reach out to us and elaborate on your requirements. One of our representatives will get back to as soon as possible.</p>
		</div>
		<div class='cxis-url-wrap'>
			<a class='cxis-url' href='" . add_query_arg( $utm, 'https://codexpert.io/hire/' ) . "' target='_blank'>" . __( 'Get A Free Quote', 'image-sizes' ) . "</a>
		</div>
	</div>";

echo '</div>';