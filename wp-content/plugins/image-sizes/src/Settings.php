<?php
/**
 * All settings related functions
 */
namespace codexpert\Image_Sizes;
use codexpert\plugin\Base;

/**
 * @package Plugin
 * @subpackage Settings
 * @author codexpert <hello@codexpert.io>
 */
class Settings extends Base {

	public $plugin;

	/**
	 * Constructor function
	 */
	public function __construct( $plugin ) {
		$this->plugin	= $plugin;
		$this->slug		= $this->plugin['TextDomain'];
		$this->name		= $this->plugin['Name'];
		$this->version	= $this->plugin['Version'];
	}
	
    public function admin_notices() {

        if( !current_user_can( 'manage_options' ) || get_option( 'image-sizes_regened' ) == 1 ) return;

        if( isset( $_GET['page'] ) && $_GET['page'] == 'image-sizes' ) {
            update_option( 'image-sizes_regened', 1 );
            update_option( 'image-sizes_version', 3.0 );
            return;
        }

        $version_updated = get_option( 'image-sizes_configured' ) != '';

        ?>
        <div class="notice notice-success cxis-notice">
        	<?php if( $version_updated ) echo '<i class="notice-dismiss cxis-dismiss" data-meta_key="image-sizes_regened"></i>'; ?>
            <?php echo '<h3>' . sprintf( __( 'Hello %s!', 'image-sizes' ), wp_get_current_user()->display_name ) . '</h3>'; ?>
            <div>
                <?php
                if( $version_updated ) {
                	echo '<p>' . sprintf( __( 'It looks like you already have disabled some thumbnails with the <strong>%s</strong> plugin. Congratulations!', 'image-sizes' ), 'Image Sizes'  ) . '</p>';
                	echo '<p>' . __( 'Do you know, you can now regenerate thumbnaiils of your existing images?', 'image-sizes' ) . '</p>';
                }
                else {
                	echo '<p>' . sprintf( __( 'Thank you for taking your decision to install the <strong>%s</strong> plugin. Congratulations!', 'image-sizes' ), 'Image Sizes' ) . '</p>';
                	echo '<p>' . sprintf( __( 'You can now prevent WordPress from generating unnecessary thumbnails when you upload an image. You just need to select the thumbnail sizes from the settings screen. And.. relax!', 'image-sizes' ), 'Image Sizes' ) . '</p>';
                }
                ?>
            </div>
            <a class="cx-notice-btn" href="<?php echo admin_url( 'upload.php?page=image-sizes' ); ?>">
            	<?php echo $version_updated ? __( 'Click Here To Regenerate Thumbnails', 'image-sizes' ) : __( 'Click Here To Disable Thumbnails', 'image-sizes' ); ?>
            </a>
        </div>
        <?php
    }
	
	public function init_menu() {
		
		$image_sizes = get_option( '_image-sizes', [] );
		$settings = [
			'id'            => $this->slug,
			'label'         => __( 'Image Sizes', 'image-sizes' ),
			'title'         => $this->name,
			'header'        => $this->name,
			'parent'        => 'upload.php',
			'priority'      => 10,
			'capability'    => 'manage_options',
			'icon'          => 'dashicons-image-crop',
			'position'      => '10.5',
			'sections'      => [
				'prevent_image_sizes'	=> 	[
					'id'        => 'prevent_image_sizes',
					'label'     => __( 'Disable Thumbnails', 'image-sizes' ),
					'icon'      => 'dashicons-images-alt2',
					'color'		=> '#4c3f93',
					'sticky'	=> true,
					'content'	=> Helper::get_template( 'disable-sizes', 'views/settings', [ 'image_sizes' => $image_sizes ] ),
					'fields'    => []
				],
				'image-sizes_regenerate'	=> 	[
					'id'        => 'image-sizes_regenerate',
					'label'     => __( 'Regenerate', 'image-sizes' ),
					'icon'      => 'dashicons-format-gallery',
					'color'		=> '#267942',
					'hide_form'	=> true,
					'content'	=> Helper::get_template( 'regenerate-thumbnails', 'views/settings' ),
					'fields'    => []
				],
				'image-more_plugins'	=> [
					'id'        => 'image-more_plugins',
					'label'     => __( 'Supercharge', 'image-sizes' ),
					'icon'      => 'dashicons-superhero',
					'color'		=> '#ff0993',
					'hide_form'	=> true,
					'content'	=> Helper::get_template( 'more-plugins', 'views/settings' ),
					'fields'    => [],
				],
			],
		];

		new \codexpert\plugin\Settings( $settings );
	}
}