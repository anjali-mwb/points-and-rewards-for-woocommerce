<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           points-and-rewards-for-wooCommerce
 *
 * @wordpress-plugin
 * Plugin Name:       Points and Rewards for WooCommerce
 * Plugin URI:        https://makewebbetter.com/product/points-rewards-for-woocommerce/
 * Description:       This woocommerce extension allow merchants to reward their customers with loyalty points.
 * Version:           1.0.0
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com/
 * Plugin URI:        https://wordpress.org/plugins/points-rewards-for-woocommerce/
 * Text Domain:       points-rewards-for-woocommerce
 * Domain Path:       /languages
 *
 * Requires at least: 4.6
 * Tested up to:      5.3
 * WC requires at least: 3.0
 * WC tested up to: 3.8.0
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}
// To Activate plugin only when WooCommerce is active.
$activated = false;

// Check if WooCommerce is active.
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

	$activated = true;
}
if ( $activated ) {
	/**
	 * Define the constatant of the plugin.
	 *
	 * @name define_rewardeem_woocommerce_points_rewards_constants.
	 */
	function define_rewardeem_woocommerce_points_rewards_constants() {

		rewardeem_woocommerce_points_rewards_constants( 'REWARDEEM_WOOCOMMERCE_POINTS_REWARDS_VERSION', '1.0.0' );
		rewardeem_woocommerce_points_rewards_constants( 'MWB_RWPR_DIR_PATH', plugin_dir_path( __FILE__ ) );
		rewardeem_woocommerce_points_rewards_constants( 'MWB_RWPR_DIR_URL', plugin_dir_url( __FILE__ ) );
		rewardeem_woocommerce_points_rewards_constants( 'MWB_RWPR_Domain', 'points-rewards-for-woocommerce' );
		/* For License Validation. */
		rewardeem_woocommerce_points_rewards_constants( 'REWARDEEM_WOOCOMMERCE_POINTS_REWARDS_SPECIAL_SECRET_KEY', '59f32ad2f20102.74284991' );
		rewardeem_woocommerce_points_rewards_constants( 'REWARDEEM_WOOCOMMERCE_POINTS_REWARDS_SERVER_URL', 'https://makewebbetter.com' );
		rewardeem_woocommerce_points_rewards_constants( 'REWARDEEM_WOOCOMMERCE_POINTS_REWARDS_ITEM_REFERENCE', 'Rewardeem-Woocommerce Points Rewards' );
		rewardeem_woocommerce_points_rewards_constants( 'MWB_RWPR_HOME_URL', admin_url() );
	}


	/**
	 * Callable function for defining plugin constants.
	 *
	 * @name rewardeem_woocommerce_points_rewards_constants.
	 * @param string $key key of the constant.
	 * @param string $value value of the constant.
	 */
	function rewardeem_woocommerce_points_rewards_constants( $key, $value ) {

		if ( ! defined( $key ) ) {

			define( $key, $value );
		}
	}

	/**
	 * Dynamically Generate Coupon Code
	 *
	 * @name mwb_wpr_coupon_generator
	 * @param number $length length of the coupon.
	 * @return string
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wpr_coupon_generator( $length = 5 ) {
		if ( '' == $length ) {
			$length = 5;
		}
		$password = '';
		$alphabets = range( 'A', 'Z' );
		$numbers = range( '0', '9' );
		$final_array = array_merge( $alphabets, $numbers );
		while ( $length-- ) {
			$key = array_rand( $final_array );
			$password .= $final_array[ $key ];
		}

		return $password;
	}
	/**
	 * Dynamically Generate referral Code
	 *
	 * @name mwb_wpr_create_referral_code
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wpr_create_referral_code() {
		$length = 10;
		$pkey = '';
		$alphabets = range( 'A', 'Z' );
		$numbers = range( '0', '9' );
		$final_array = array_merge( $alphabets, $numbers );
		while ( $length-- ) {
			$key = array_rand( $final_array );
			$pkey .= $final_array[ $key ];
		}
		return $pkey;
	}

	/**
	 * The core plugin class that is used to define internationalization,
	 * admin-specific hooks, and public-facing site hooks.
	 */
	require plugin_dir_path( __FILE__ ) . 'includes/class-points-rewards-for-woocommerce.php';

	add_shortcode( 'MYCURRENTPOINT', 'mwb_wpr_mytotalpoint_shortcode' );

	/**
	 * Shortcode for the total points
	 *
	 * @name mwb_wpr_mytotalpoint_shortcode
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wpr_mytotalpoint_shortcode() {
		$user_ID = get_current_user_ID();
		$mwb_wpr_other_settings = get_option( 'mwb_wpr_other_settings', array() );
		if ( ! empty( $mwb_wpr_other_settings['mwb_wpr_other_shortcode_text'] ) ) {
			$mwb_wpr_shortcode_text_point = $mwb_wpr_other_settings['mwb_wpr_other_shortcode_text'];
		} else {
			$mwb_wpr_shortcode_text_point = __( 'Your Current Point', 'points-rewards-for-woocommerce' );
		}
		if ( isset( $user_ID ) && ! empty( $user_ID ) ) {
			$get_points = (int) get_user_meta( $user_ID, 'mwb_wpr_points', true );
			return '<div class="mwb_wpr_shortcode_wrapper">' . $mwb_wpr_shortcode_text_point . ' ' . $get_points . '</div>';
		}
	}
	add_shortcode( 'MYCURRENTUSERLEVEL', 'mwb_wpr_mycurrentlevel_shortcode' );
	/**
	 * Display your Current Level by using shortcode
	 *
	 * @name mwb_wpr_mycurrentlevel_shortcode
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wpr_mycurrentlevel_shortcode() {
		$user_ID = get_current_user_ID();
		$mwb_wpr_other_settings = get_option( 'mwb_wpr_other_settings', array() );
		if ( ! empty( $mwb_wpr_other_settings['mwb_wpr_shortcode_text_membership'] ) ) {
			$mwb_wpr_shortcode_text_membership = $mwb_wpr_other_settings['mwb_wpr_shortcode_text_membership'];
		} else {
			$mwb_wpr_shortcode_text_membership = __( 'Your Current Level', 'points-rewards-for-woocommerce' );
		}
		if ( isset( $user_ID ) && ! empty( $user_ID ) ) {
			$user_level = get_user_meta( $user_ID, 'membership_level', true );
			if ( isset( $user_level ) && ! empty( $user_level ) ) {
				return $mwb_wpr_shortcode_text_membership . ' ' . $user_level;
			}
		}
	}
	add_shortcode( 'SIGNUPNOTIFICATION', 'mwb_wpr_signupnotif_shortcode' );
	/**
	 * Display the SIgnup Notification by using shortcode
	 *
	 * @name mwb_wpr_signupnotif_shortcode
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wpr_signupnotif_shortcode() {
		$general_settings = get_option( 'mwb_wpr_settings_gallery', true );
		$enable_mwb_signup = isset( $general_settings['mwb_wpr_general_signup'] ) ? intval( $general_settings['mwb_wpr_general_signup'] ) : 0;
		if ( $enable_mwb_signup && ! is_user_logged_in() ) {
			$mwb_wpr_signup_value = isset( $general_settings['mwb_wpr_general_signup_value'] ) ? intval( $general_settings['mwb_wpr_general_signup_value'] ) : 1;

			 return '<div class="woocommerce-message">' . esc_html__( 'You will get ', 'points-rewards-for-woocommerce' ) . esc_html( $mwb_wpr_signup_value ) . esc_html__( ' points for SignUp', 'points-rewards-for-woocommerce' ) . '</div>';
		}
	}

	/**
	 * Begins execution of the plugin.
	 *
	 * Since everything within the plugin is registered via hooks,
	 * then kicking off the plugin from this point in the file does
	 * not affect the page life cycle.
	 *
	 * @since    1.0.0
	 */
	function run_rewardeem_woocommerce_points_rewards() {
		define_rewardeem_woocommerce_points_rewards_constants();
		$plugin = new Points_Rewards_For_Woocommerce();
		$plugin->run();

	}
	run_rewardeem_woocommerce_points_rewards();

	// Add settings link on plugin page.
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'rewardeem_woocommerce_points_rewards_settings_link' );

	/**
	 * Settings tab of the plugin.
	 *
	 * @name rewardeem_woocommerce_points_rewards_settings_link
	 * @param array $links array of the links.
	 * @since    1.0.0
	 */
	function rewardeem_woocommerce_points_rewards_settings_link( $links ) {

		$my_link = array(
			'<a href="' . admin_url( 'admin.php?page=mwb-rwpr-setting' ) . '">' . esc_html__( 'Settings', 'points-rewards-for-woocommerce' ) . '</a>',
		);
		return array_merge( $my_link, $links );
	}

	/**
	 * This function is used to return the date format as per WP settings
	 *
	 * @name mwb_wpr_set_the_wordpress_date_format
	 * @param string $saved_date saved data in the wordpress formet.
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function mwb_wpr_set_the_wordpress_date_format( $saved_date ) {
		$saved_date = strtotime( $saved_date );
		$date_format = get_option( 'date_format', 'Y-m-d' );
		$time_format = get_option( 'time_format', 'g:i a' );
		$wp_date = date_i18n( $date_format, $saved_date );
		$wp_time = date_i18n( $time_format, $saved_date );
		$return_date = $wp_date . ' ' . $wp_time;
		return $return_date;
	}
	if ( ! function_exists( 'array_key_first' ) ) {
		/**
		 * This function is used to return the first key
		 *
		 * @name array_key_first
		 * @param array $arr optional parameter.
		 * @author makewebbetter<webmaster@makewebbetter.com>
		 * @link https://www.makewebbetter.com/
		 */
		function array_key_first( array $arr ) {
			foreach ( $arr as $key => $unused ) {
				return $key;
			}
			return null;
		}
	}
} else {


	// WooCommerce is not active so deactivate this plugin.
	add_action( 'admin_init', 'rewardeem_woocommerce_points_rewards_activation_failure' );

	/**
	 * This function is used to deactivate plugin.
	 *
	 * @name rewardeem_woocommerce_points_rewards_activation_failure
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function rewardeem_woocommerce_points_rewards_activation_failure() {

		deactivate_plugins( plugin_basename( __FILE__ ) );
	}

	// Add admin error notice.
	add_action( 'admin_notices', 'rewardeem_woocommerce_points_rewards_activation_failure_admin_notice' );

	/**
	 * This function is used to deactivate plugin.
	 *
	 * @name rewardeem_woocommerce_points_rewards_activation_failure
	 * @author makewebbetter<webmaster@makewebbetter.com>
	 * @link https://www.makewebbetter.com/
	 */
	function rewardeem_woocommerce_points_rewards_activation_failure_admin_notice() {
			// to hide Plugin activated notice.
		unset( $_GET['activate'] );
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e( 'WooCommerce is not activated, Please activate WooCommerce first to activate Ultimate WooCommerce Points and Rewards.', 'points-rewards-for-woocommerce' ); ?></p>
			</div>

			<?php
		}

	}
}






