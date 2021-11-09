<?php
/*
Plugin Name: Estimación de proyectos
Plugin URI: www.codeberrysolutions.com
Description: Estimación automática de proyectos para utilizar por nuevos clientes que acceden a la web de  Codeberry Solutions. puede usar el sortcode [estimate_form] para mostrar el formulario.
Version:1.0.0
Author:Codeberry Solutions
Author URI:www.codeberrysolutions.com
License:-
*/

if ( ! defined( 'ECBS_VER' ) ) {
	define( 'ECBS_VER', '1.0.0' );
}

//defined('ABSPATH') or die("Bye bye");
class EstimationCbsApi {
	public const URL = 'https://api.codeberrysolutions.com/';
	public const END_POINT = 'estimations/';

	static function style( $prefix ,$admin=false) {
		wp_register_script( $prefix . 'cbs-js_bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js');
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( $prefix . 'cbs-js_bootstrap' );
		wp_register_style( $prefix . 'cbs_bootstrap','https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css');
		wp_enqueue_style( $prefix . 'cbs_bootstrap' );
		if($admin){
			wp_register_style( $prefix . 'cbs_style', plugins_url( 'estimation-cbs/admin/css/style.css' ) );
			wp_enqueue_style( $prefix . 'cbs_style' );
			wp_register_script( $prefix . 'cbs-js_view', plugins_url( 'estimation-cbs/admin/js/view.js' ) );
			wp_enqueue_script( $prefix . 'cbs-js_view' );
        }else{
			wp_register_style( $prefix . 'cbs_style', plugins_url( 'estimation-cbs/public/css/style.css' ) );
			wp_enqueue_style( $prefix . 'cbs_style' );
        }
	}

	static function getResults() {
		$response  = wp_remote_get( self::URL . self::END_POINT, [ 'timeout' => 10 ] );
		$body      = wp_remote_retrieve_body( $response );
		$http_code = wp_remote_retrieve_response_code( $response );
		if ( $http_code != 200 ) {
			throw new HttpException( $http_code );
		} else {
			return $body;
		}
	}

	static function getResult($id) {
		$response  = wp_remote_get( self::URL . self::END_POINT.'/'.$id, [ 'timeout' => 10 ] );
		$body      = wp_remote_retrieve_body( $response );
		$http_code = wp_remote_retrieve_response_code( $response );
		if ( $http_code != 200 ) {
			throw new HttpException( $http_code );
		} else {
			return $body;
		}
	}

	static function setData( $postData ) {
		$url  = self::URL . self::END_POINT;
		$body = wp_json_encode( [ 'estimation' => self::completeData( $postData ) ] );

		$options   = [
			'body'        => $body,
			'headers'     => [
				'Content-Type' => 'application/json',
			],
			'timeout'     => 100,
			'data_format' => 'body',
		];
		$response  = wp_remote_post( $url, $options );
		$body      = wp_remote_retrieve_body( $response );
		$http_code = wp_remote_retrieve_response_code( $response );
		if ( $http_code != 200 ) {
			throw new HttpException( $http_code );
		} else {
			return json_decode( $body, true );
		}
	}

	static function completeData( $data ) {
		return [
			'platform_ios'                    => isset( $data['platform_ios'] ) ? 1 : 0,
			'platform_android'                => isset( $data['platform_android'] ) ? 1 : 0,
			'platform_web'                    => isset( $data['platform_web'] ) ? 1 : 0,
			'user_registration_yes'           => isset( $data['user_registration_yes'] ) ? 1 : 0,
			'user_registration_no'            => isset( $data['user_registration_no'] ) ? 1 : 0,
			'user_registration_not_sure'      => isset( $data['user_registration_not_sure'] ) ? 1 : 0,
			'user_content_user_profile'       => isset( $data['user_content_user_profile'] ) ? 1 : 0,
			'user_content_upload_media'       => isset( $data['user_content_upload_media'] ) ? 1 : 0,
			'user_content_ratings'            => isset( $data['user_content_ratings'] ) ? 1 : 0,
			'user_content_save_favorites'     => isset( $data['user_content_save_favorites'] ) ? 1 : 0,
			'user_content_marketplace'        => isset( $data['user_content_marketplace'] ) ? 1 : 0,
			'community_chat'                  => isset( $data['community_chat'] ) ? 1 : 0,
			'community_forums'                => isset( $data['community_forums'] ) ? 1 : 0,
			'community_social_sharing'        => isset( $data['community_social_sharing'] ) ? 1 : 0,
			'community_push'                  => isset( $data['community_push'] ) ? 1 : 0,
			'utilities_free_text_search'      => isset( $data['utilities_free_text_search'] ) ? 1 : 0,
			'utilities_geolocation'           => isset( $data['utilities_geolocation'] ) ? 1 : 0,
			'utilities_custom_location'       => isset( $data['utilities_custom_location'] ) ? 1 : 0,
			'utilities_calendar_events'       => isset( $data['utilities_calendar_events'] ) ? 1 : 0,
			'utilities_offline_mode'          => isset( $data['utilities_offline_mode'] ) ? 1 : 0,
			'monetization_advertising'        => isset( $data['monetization_advertising'] ) ? 1 : 0,
			'monetization_in_app_payment'     => isset( $data['monetization_in_app_payment'] ) ? 1 : 0,
			'monetization_subscriptions'      => isset( $data['monetization_subscriptions'] ) ? 1 : 0,
			'monetization_freemium_content'   => isset( $data['monetization_freemium_content'] ) ? 1 : 0,
			'integrations_internal'           => isset( $data['integrations_internal'] ) ? 1 : 0,
			'integrations_external'           => isset( $data['integrations_external'] ) ? 1 : 0,
			'integrations_both'               => isset( $data['integrations_both'] ) ? 1 : 0,
			'integrations_neither'            => isset( $data['integrations_neither'] ) ? 1 : 0,
			'integrations_not_sure'           => isset( $data['integrations_not_sure'] ) ? 1 : 0,
			'administration_cms'              => isset( $data['administration_cms'] ) ? 1 : 0,
			'administration_user_management'  => isset( $data['administration_user_management'] ) ? 1 : 0,
			'administration_moderate_content' => isset( $data['administration_moderate_content'] ) ? 1 : 0,
			'administration_analytic_suite'   => isset( $data['administration_analytic_suite'] ) ? 1 : 0,
			'security_basic'                  => isset( $data['security_basic'] ) ? 1 : 0,
			'security_encrypted'              => isset( $data['security_encrypted'] ) ? 1 : 0,
			'security_not_sure'               => isset( $data['security_not_sure'] ) ? 1 : 0,
			'ui_bare_bones'                   => isset( $data['ui_bare_bones'] ) ? 1 : 0,
			'ui_standard'                     => isset( $data['ui_standard'] ) ? 1 : 0,
			'ui_beautiful'                    => isset( $data['ui_beautiful'] ) ? 1 : 0,
			'number_of_pages_less_10'         => isset( $data['number_of_pages_less_10'] ) ? 1 : 0,
			'number_of_pages_10_30'           => isset( $data['number_of_pages_10_30'] ) ? 1 : 0,
			'number_of_pages_30_100'          => isset( $data['number_of_pages_30_100'] ) ? 1 : 0,
			'number_of_pages_100'             => isset( $data['number_of_pages_100'] ) ? 1 : 0,
			'lang_1'                          => isset( $data['lang_1'] ) ? 1 : 0,
			'lang_2'                          => isset( $data['lang_2'] ) ? 1 : 0,
			'lang_3_more'                     => isset( $data['lang_3_more'] ) ? 1 : 0,
			'client_name'                     => $data['client_name'] ?? '',
			'client_last_name'                => $data['client_last_name'] ?? '',
			'client_email'                    => $data['client_email'] ?? '',
		];
	}
}

/**
 * Activate the plugin.
 */
function estimation_cbs_activate() {
	// Trigger our function that registers the custom post type plugin.

	// Clear the permalinks after the post type has been registered.

}

register_activation_hook( __FILE__, 'estimation_cbs_activate' );

/**
 * Activate the plugin.
 */
function estimation_cbs_desactivate() {
	// Trigger our function that registers the custom post type plugin.

	// Clear the permalinks after the post type has been registered.

}

register_deactivation_hook( __FILE__, 'estimation_cbs_desactivate' );

add_action( 'admin_menu', 'estimation_admin_page' );
function estimation_admin_page() {
	add_menu_page(
		'Estimations',
		'Projects estimated',
		'manage_options',
		plugin_dir_path( __FILE__ ) . 'admin/view.php',
		null,
		'dashicons-chart-line',
		20
	);
}

function estimateForm() {
	EstimationCbsApi::style( 'frontend' );
	wp_enqueue_script( 'cbs_custom_script', plugins_url( 'estimation-cbs/public/js/functions.js' ) );
	wp_enqueue_style( 'cbs_custom_style', plugins_url( 'estimation-cbs/public/css/style.css' ) );

	global $wp;
	$current_url = add_query_arg( $wp->query_string, '', home_url( $wp->request ) );
	if ( ! empty( $_POST ) && isset( $_POST['estimate-form'] ) ) {
		array_shift( $_POST );
		$response = EstimationCbsApi::setData( $_POST ) ?>
        <div class="col-md-12">
            <div class="card"
                 style="box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;border: none">
                <div class="card-head">
                    <span class="dashicons dashicons-saved"
                          style="width: auto;height: auto;font-size: 80px;color: white;border-radius: 100%;border: 3px solid white;"></span>
                    <h1 style="color: white;font-weight: normal">Success!</h1>
                </div>
                <div class="card-body" style="text-align: center;">
                    <h4 style="text-align: center;color: green;font-weight: normal">Congratulations, your app has been
                        successfully estimated.</h4>
                    <div class="col-md-12" style="margin-top: 20px;text-align: left">
                        <div class="col-md-6"><h6
                                    style="color: green;font-weight: normal"><?= 'Total of days: <span style="color: black">' . round( $response['results']['final']['totalDays'] ) . '</span>' ?></h6>
                        </div>
                        <div class="col-md-6"><h6
                                    style="color: green;font-weight: normal"><?= 'Total of hours: <span style="color: black">' . round( $response['results']['final']['totalHours'] ) . '</span>' ?></h6>
                        </div>
                    </div>
                    <a class="btn estimate-button-return" href="<?= $current_url ?>" style="color: black;">Return</a>
                </div>
            </div>
        </div>
	<?php } else {
		// get current url with query string.

		?>

        <form class="form" id="estimate_form_frontend" method="post" action="#">
            <div class="row estimate-text text-center">
                <div class="col-md-12">
                    <h1>Let's estimate your app!</h1>
                </div>

                <div class="col-md-12">
                    <div class="col-md-12"><p>In wich plataform(s) can users access the app?</p></div>
                    <div class="form-group group-flex">
                        <input id="estimate-form" type="hidden" name="estimate-form" value="estimate_form_frontend"/>
                        <input id="check_platform_ios" type="checkbox" hidden class=" plataform"
                               name="platform_ios"/>
                        <a id="platform_ios" class="btn estimate-button" family="plataform-any">iOS</a>
                        <input id="check_platform_android" type="checkbox" hidden class=" plataform"
                               name="platform_android"/>
                        <a id="platform_android" class="btn estimate-button" family="plataform-any">Android</a>
                        <input id="check_platform_web" type="checkbox" hidden class=" plataform"
                               name="platform_web"/>
                        <a id="platform_web" class="btn estimate-button" family="plataform-any">Web</a>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>

                <div class="col-md-12">
                    <div class="col-md-12"><p>Do users need to register?<span style="color:red;">*</span></p></div>
                    <div class="form-group group-flex">
                        <input id="check_user_registration_yes" type="checkbox"
                               hidden class=" user_registration" name="user_registration_yes"/>
                        <a id="user_registration_yes" class="btn estimate-button" family="user_registration-one">Yes</a>
                        <input id="check_user_registration_no" type="checkbox" hidden class=" user_registration"
                               name="user_registration_no"/>
                        <a id="user_registration_no" class="btn estimate-button" family="user_registration-one">No</a>
                        <input id="check_user_registration_not_sure" type="checkbox"
                               hidden class=" user_registration" name="user_registration_not_sure"/>
                        <a id="user_registration_not_sure" class="btn estimate-button" family="user_registration-one">Not
                            sure</a>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>

                <div class="col-md-12">
                    <div class="col-md-12"><p>Will users be creating content?</p></div>
                    <div class="form-group group-flex">
                        <input id="check_user_content_user_profile" type="checkbox"
                               hidden class=" user_generated_content" name="user_content_user_profile"/>
                        <a id="user_content_user_profile" class="btn estimate-button"
                           family="user_generated_content-any">User profile</a>
                        <input id="check_user_content_upload_media" type="checkbox"
                               hidden class=" user_generated_content" name="user_content_upload_media"/>
                        <a id="user_content_upload_media" class="btn estimate-button"
                           family="user_generated_content-any">Upload media (images, audio, video)</a>
                        <input id="check_user_content_ratings" type="checkbox"
                               hidden class=" user_generated_content" name="user_content_ratings"/>
                        <a id="user_content_ratings" class="btn estimate-button" family="user_generated_content-any">Ratings
                            & reviews</a>
                        <input id="check_user_content_save_favorites" type="checkbox"
                               hidden class=" user_generated_content" name="user_content_save_favorites"/>
                        <a id="user_content_save_favorites" class="btn estimate-button"
                           family="user_generated_content-any">Save favorites</a>
                        <input id="check_user_content_marketplace" type="checkbox"
                               hidden class=" user_generated_content" name="user_content_marketplace"/>
                        <a id="user_content_marketplace" class="btn estimate-button"
                           family="user_generated_content-any">User Marketplace</a>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>

                <div class="col-md-12">
                    <div class="col-md-12"><p>Does the app include community or engagement features?</p></div>
                    <div class="form-group group-flex">
                        <input id="check_community_chat" type="checkbox" hidden class=" community"
                               name="community_chat"/>
                        <a id="community_chat" class="btn estimate-button" family="community-any">Chat system</a>
                        <input id="check_community_forums" type="checkbox" hidden class=" community"
                               name="community_forums"/>
                        <a id="community_forums" class="btn estimate-button" family="community-any">Forum</a>
                        <input id="check_community_social_sharing" type="checkbox" hidden class=" community"
                               name="community_social_sharing"/>
                        <a id="community_social_sharing" class="btn estimate-button" family="community-any">Social
                            sharing</a>
                        <input id="check_community_push" type="checkbox" hidden class=" community"
                               name="community_push"/>
                        <a id="community_push" class="btn estimate-button" family="community-any">Push notifications</a>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>

                <div class="col-md-12">
                    <div class="col-md-12"><p>Does the app include any of the following utilities?</p></div>
                    <div class="form-group group-flex">
                        <input id="check_utilities_free_text_search" type="checkbox" hidden class=" utilities"
                               name="utilities_free_text_search"/>
                        <a id="utilities_free_text_search" class="btn estimate-button" family="utilities-any">Free-text
                            search</a>
                        <input id="check_utilities_geolocation" type="checkbox" hidden class=" utilities"
                               name="utilities_geolocation"/>
                        <a id="utilities_geolocation" class="btn estimate-button" family="utilities-any">Geolocation</a>
                        <input id="check_utilities_custom_location" type="checkbox" hidden class=" utilities"
                               name="utilities_custom_location"/>
                        <a id="utilities_custom_location" class="btn estimate-button" family="utilities-any">Add custom
                            locations to the map</a>
                        <input id="check_utilities_calendar_events" type="checkbox" hidden class=" utilities"
                               name="utilities_calendar_events"/>
                        <a id="utilities_calendar_events" class="btn estimate-button" family="utilities-any">Calendar /
                            Events</a>
                        <input id="check_utilities_offline_mode" type="checkbox" hidden class=" utilities"
                               name="utilities_offline_mode"/>
                        <a id="utilities_offline_mode" class="btn estimate-button" family="utilities-any">Offline
                            mode</a>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>

                <div class="col-md-12">
                    <div class="col-md-12"><p>What type of payment do you need?</p></div>
                    <div class="form-group group-flex">
                        <input id="check_monetization_advertising" type="checkbox" hidden class=" monetization"
                               name="monetization_advertising"/>
                        <a id="monetization_advertising" class="btn estimate-button" family="monetization-any">Advertising</a>
                        <input id="check_monetization_in_app_payment" type="checkbox"
                               hidden class=" monetization" name="monetization_in_app_payment"/>
                        <a id="monetization_in_app_payment" class="btn estimate-button" family="monetization-any">In app
                            payments</a>
                        <input id="check_monetization_subscriptions" type="checkbox"
                               hidden class=" monetization" name="monetization_subscriptions"/>
                        <a id="monetization_subscriptions" class="btn estimate-button" family="monetization-any">Subscriptions</a>
                        <input id="check_monetization_freemium_content" type="checkbox"
                               hidden class=" monetization" name="monetization_freemium_content"/>
                        <a id="monetization_freemium_content" class="btn estimate-button" family="monetization-any">Premium content</a>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>

                <div class="col-md-12">
                    <div class="col-md-12"><p>What type of integration do you need?<span style="color:red;">*</span></p></div>
                    <div class="form-group group-flex">
                        <input id="check_integrations_internal" type="checkbox" hidden class=" integrations"
                               name="integrations_internal"/>
                        <a id="integrations_internal" class="btn estimate-button" family="integrations-one">Internal</a>
                        <input id="check_integrations_external" type="checkbox" hidden class=" integrations"
                               name="integrations_external"/>
                        <a id="integrations_external" class="btn estimate-button" family="integrations-one">External</a>
                        <input id="check_integrations_both" type="checkbox" hidden class=" integrations"
                               name="integrations_both"/>
                        <a id="integrations_both" class="btn estimate-button" family="integrations-one">Both</a>
                        <input id="check_integrations_neither" type="checkbox" hidden class=" integrations"
                               name="integrations_neither"/>
                        <a id="integrations_neither" class="btn estimate-button" family="integrations-one">Neither</a>
                        <input id="check_integrations_not_sure" type="checkbox" hidden class=" integrations"
                               name="integrations_not_sure"/>
                        <a id="integrations_not_sure" class="btn estimate-button" family="integrations-one">Not sure</a>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>

                <div class="col-md-12">
                    <div class="col-md-12"><p>What type of administrative control do you need?</p></div>
                    <div class="form-group group-flex">
                        <input id="check_administration_cms" type="checkbox" hidden class=" administration"
                               name="administration_cms"/>
                        <a id="administration_cms" class="btn estimate-button" family="administration-any">Content
                            Management System</a>
                        <input id="check_administration_user_management" type="checkbox"
                               hidden class=" administration"
                               name="administration_user_management admin control"/>
                        <a id="administration_user_management" class="btn estimate-button" family="administration-any">User
                            Management</a>
                        <input id="check_administration_moderate_content" type="checkbox"
                               hidden class=" administration" name="administration_moderate_content"/>
                        <a id="administration_moderate_content" class="btn estimate-button" family="administration-any">Moderate
                            User Content</a>
                        <input id="check_administration_analytic_suite" type="checkbox"
                               hidden class=" administration" name="administration_analytic_suite"/>
                        <a id="administration_analytic_suite" class="btn estimate-button" family="administration-any">Analytics
                            Suite</a>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>

                <div class="col-md-12">
                    <div class="col-md-12"><p>What type of security do you need?<span style="color:red;">*</span></p></div>
                    <div class="form-group group-flex">
                        <input id="check_security_basic" type="checkbox" hidden class=" security"
                               name="security_basic"/>
                        <a id="security_basic" class="btn estimate-button" family="security-one">Basic</a>
                        <input id="check_security_encrypted" type="checkbox" hidden class=" security"
                               name="security_encrypted"/>
                        <a id="security_encrypted" class="btn estimate-button" family="security-one">Encrypted</a>
                        <input id="check_security_not_sure" type="checkbox" hidden class=" security"
                               name="security_not_sure"/>
                        <a id="security_not_sure" class="btn estimate-button" family="security-one">Not sure</a>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>

                <div class="col-md-12">
                    <div class="col-md-12"><p>How should the UI look?<span style="color:red;">*</span></p></div>
                    <div class="form-group group-flex">
                        <input id="check_ui_bare_bones" type="checkbox" hidden class=" ui"
                               name="ui_bare_bones"/>
                        <a id="ui_bare_bones" class="btn estimate-button" family="ui-one">Bare bonds</a>
                        <input id="check_ui_standard" type="checkbox" hidden class=" ui" name="ui_standard"/>
                        <a id="ui_standard" class="btn estimate-button" family="ui-one">Standard</a>
                        <input id="check_ui_beautiful" type="checkbox" hidden class=" ui" name="ui_beautiful"/>
                        <a id="ui_beautiful" class="btn estimate-button" family="ui-one">Beautiful</a>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>

                <div class="col-md-12">
                    <div class="col-md-12"><p>How many pages will the app contain?<span style="color:red;">*</span></p></div>
                    <div class="form-group group-flex">
                        <input id="check_number_of_pages_less_10" type="checkbox" hidden class=" number_of_page"
                               name="number_of_pages_less_10"/>
                        <a id="number_of_pages_less_10" class="btn estimate-button" family="number_of_page-one"><10</a>
                        <input id="check_number_of_pages_10_30" type="checkbox" hidden class=" number_of_page"
                               name="number_of_pages_10_30"/>
                        <a id="number_of_pages_10_30" class="btn estimate-button" family="number_of_page-one">Between
                            10-30</a>
                        <input id="check_number_of_pages_30_100" type="checkbox" hidden class=" number_of_page"
                               name="number_of_pages_30_100"/>
                        <a id="number_of_pages_30_100" class="btn estimate-button" family="number_of_page-one">Between
                            30-100</a>
                        <input id="check_number_of_pages_100" type="checkbox" hidden class=" number_of_page"
                               name="number_of_pages_100"/>
                        <a id="number_of_pages_100" class="btn estimate-button" family="number_of_page-one">100+</a>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>

                <div class="col-md-12">
                    <div class="col-md-12"><p>Should the app be available in multiple languages?<span style="color:red;">*</span></p></div>
                    <div class="form-group group-flex">
                        <input id="check_lang_1" type="checkbox" hidden class=" languages" name="lang_1"/>
                        <a id="lang_1" class="btn estimate-button" family="languages-one">1 language</a>
                        <input id="check_lang_2" type="checkbox" hidden class=" languages" name="lang_2"/>
                        <a id="lang_2" class="btn estimate-button" family="languages-one">2 languages</a>
                        <input id="check_lang_3_more" type="checkbox" hidden class=" languages"
                               name="lang_3_more"/>
                        <a id="lang_3_more" class="btn estimate-button" family="languages-one">3+ languages</a>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>

                <div class="row col-md-12">
                    <div class="col-md-12"><p>Client Contact <span style="color:red;">*</span></p></div>
                    <div class="form-group group-flex col-md-6">
                        <label id="label_client_name" for="client_name">Name</label><input id="client_name" type="text"
                                                                    class="client form-control" family="client" placeholder="Name"
                                                                    name="client_name" required/>
                    </div>
                    <div class="form-group group-flex col-md-6">
                        <label id="label_client_last_name" for="client_last_name">Last name</label><input id="client_last_name" type="text"
                                                                              class="form-control client"
                                                                              name="client_last_name" placeholder="Last Name" required/>
                    </div>
                    <div class="form-group group-flex col-md-12">
                        <label id="label_client_email" for="client_email">Email</label><input id="client_email" type="email"
                                                                      class="form-control client" name="client_email" placeholder="name@gmail.com"
                                                                      required/>
                    </div>
                    <hr class="col-12" style="margin-top: 10px;margin-bottom:10px">
                </div>
                <div class="col-md-12">
                    <input id="enviar_form" class="btn estimate-button-submit offset-md-3" type="button"
                           value="Send"/>
                </div>
            </div>
        </form>
		<?php
	}
}

add_shortcode( 'estimate_form', 'estimateForm' );

