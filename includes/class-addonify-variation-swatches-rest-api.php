<?php
/**
 * The class to define REST API endpoints used in settings page.
 *
 * This is used to define REST API endpoints used in admin settings page to get and update settings values.
 *
 * @since      1.0.7
 * @package    Addonify_Variation
 * @subpackage Addonify_Variation/includes
 * @author     Addonify <contact@addonify.com>
 */

if ( ! class_exists( 'Addonify_Variation_Swatches_Rest_API' ) ) {
	/**
	 * Register rest api.
	 *
	 * @package    Addonify_Variation
	 * @subpackage Addonify_Variation/includes
	 * @author     Adodnify <contact@addonify.com>
	 */
	class Addonify_Variation_Swatches_Rest_API {

		/**
		 * The namespace of the Rest API.
		 *
		 * @since    1.0.7
		 * @access   protected
		 * @var      string    $rest_namespace.
		 */
		protected $rest_namespace = 'addonify_variation_swatches_options_api';


		/**
		 * Register new REST API endpoints.
		 *
		 * @since    1.0.7
		 */
		public function __construct() {

			add_action( 'rest_api_init', array( $this, 'register_rest_endpoints' ) );
		}


		/**
		 * Define the REST API endpoints to get all setting options and update all setting options.
		 *
		 * @since    1.0.7
		 * @access   public
		 */
		public function register_rest_endpoints() {

			register_rest_route(
				$this->rest_namespace,
				'/get_options',
				array(
					'methods'             => 'GET',
					'callback'            => array( $this, 'rest_handler_get_settings_fields' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				)
			);

			register_rest_route(
				$this->rest_namespace,
				'/update_options',
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'rest_handler_update_options' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				)
			);

			register_rest_route(
				$this->rest_namespace,
				'/reset_options',
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'rest_handler_reset_options' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				)
			);

			register_rest_route(
				$this->rest_namespace,
				'/export_options',
				array(
					'methods'             => \WP_REST_Server::READABLE,
					'callback'            => array( $this, 'rest_handler_export_options' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				)
			);

			register_rest_route(
				$this->rest_namespace,
				'/import_options',
				array(
					'methods'             => \WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'rest_handler_import_options' ),
					'permission_callback' => array( $this, 'permission_callback' ),
				)
			);
		}


		/**
		 * Callback function to get all settings options values.
		 *
		 * @since    1.0.7
		 */
		public function rest_handler_get_settings_fields() {

			return addonify_variation_get_settings_fields();
		}


		/**
		 * Callback function to update all settings options values.
		 *
		 * @since    1.0.7
		 * @param    \WP_REST_Request $request    The request object.
		 * @return   \WP_REST_Response   $return_data   The response object.
		 */
		public function rest_handler_update_options( $request ) {

			$return_data = array(
				'success' => false,
				'message' => __( 'Ooops, error saving settings!!!', 'addonify-variation' ),
			);

			$params = $request->get_params();

			if ( ! isset( $params['settings_values'] ) ) {

				$return_data['message'] = __( 'No settings values to update!!!', 'addonify-variation' );
				return $return_data;
			}

			if ( addonify_variation_update_settings( $params['settings_values'] ) === true ) {

				$return_data['success'] = true;
				$return_data['message'] = __( 'Settings saved successfully', 'addonify-variation' );
			}

			return rest_ensure_response( $return_data );
		}

		/**
		 * Define callback function that handles request coming to /reset_options endpoint.
		 *
		 * @since    1.0.5
		 * @param object $request  \WP_REST_Request The request object.
		 * @return json $return_data   \WP_REST_Response The response object.
		 */
		public function rest_handler_reset_options( $request ) {
			global $wpdb;

			$option_keys = array_keys( addonify_variation_settings_defaults() );

			$where  = '';
			$first  = true;
			$values = array();
			if ( ! empty( $option_keys ) ) {
				foreach ( $option_keys as $key ) {
					if ( ! $first ) {
						$where .= ' OR ';
					}
					$where   .= ' option_name = %s';
					$values[] = ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . $key;
					$first    = false;
				}
			}

			$query = 'DELETE FROM ' . $wpdb->options . ' WHERE ' . $where;

			if ( $wpdb->query( $wpdb->prepare( $query, $values ) ) ) { //phpcs:ignore
				$return_data = array(
					'success' => true,
					'message' => esc_html__( 'Options reset success.', 'addonify-variation' ),
				);
			} else {
				$return_data = array(
					'success' => false,
					'message' => esc_html__( 'Error! Options reset unsuccessful.', 'addonify-variation' ),
				);
			}

			return rest_ensure_response( $return_data );
		}

		/**
		 * Define callback function that handles request coming to /reset_options endpoint.
		 *
		 * @since    1.0.5
		 */
		public function rest_handler_export_options() {
			global $wpdb;

			$query = 'SELECT option_name, option_value FROM ' . $wpdb->options . ' WHERE option_name LIKE %s';

			$results = $wpdb->get_results( $wpdb->prepare( $query, '%' . ADDONIFY_VARIATION_SWATCHES_DB_INITIALS . '%' ), ARRAY_A ); //phpcs:ignore

			$content = wp_json_encode( $results );

			$file_name = time() . wp_rand( 100000, 999999 ) . '.json';

			if ( file_put_contents( trailingslashit( wp_upload_dir()['path'] ) . $file_name, $content ) ) { //phpcs:ignore
				$message = array(
					'success' => true,
					'url'     => trailingslashit( wp_upload_dir()['url'] ) . $file_name,
				);
			} else {
				$message = array(
					'success' => false,
					'message' => esc_html__( 'Error! Options export failed!.', 'addonify-variation' ),
				);
			}

			return rest_ensure_response( $message );
		}

		/**
		 * Callback function to update all settings options values.
		 *
		 * @since    1.0.7
		 * @param object $request  \WP_REST_Request The request object.
		 */
		public function rest_handler_import_options( $request ) {
			if ( empty( $_FILES ) ) {
				return rest_ensure_response(
					array(
						'success' => false,
						'message' => esc_html__( 'No file provided.', 'addonify-variation' ),
					)
				);
			}
			$file_contents = file_get_contents( $_FILES['addonify_variation_import_file']['tmp_name'] ); //phpcs:ignore

			if ( isset( $_FILES['addonify_variation_import_file']['type'] ) && 'application/json' !== $_FILES['addonify_variation_import_file']['type'] ) {
				return rest_ensure_response(
					array(
						'success' => false,
						'message' => esc_html__( 'File format not supported.', 'addonify-variation' ),
					)
				);
			}

			$uploaded_options_array = $this->json_to_array( $file_contents );

			if ( ! is_array( $uploaded_options_array ) ) {
				return rest_ensure_response(
					array(
						'success' => false,
						'message' => esc_html__( 'Ooops, error saving settings!!! File does not contain supported json.', 'addonify-variation' ),
					)
				);
			}

			foreach ( $uploaded_options_array as $option ) {
				update_option( $option->option_name, $option->option_value );
			}

			return rest_ensure_response(
				array(
					'success' => true,
					'message' => esc_html__( 'Addonify Variation options has been imported successfully.', 'addonify-variation' ),
				)
			);
		}



		/**
		 * Permission callback function to check if current user can access the rest api route.
		 *
		 * @since    1.0.7
		 */
		public function permission_callback() {

			if ( ! current_user_can( 'manage_options' ) ) {

				return new WP_Error( 'rest_forbidden', esc_html__( 'Ooops, you are allowed to manage options.', 'addonify-variation' ), array( 'status' => 401 ) );
			}
			return true;
		}

		/**
		 * Converts json data to array.
		 *
		 * @param mixed $data JSON Data to convert to array format.
		 * @return array|false Array if correct json format, false otherwise
		 */
		private function json_to_array( $data ) {
			if ( ! is_string( $data ) ) {
				return false;
			}
			try {
				$return_data = json_decode( $data );
				if ( JSON_ERROR_NONE === json_last_error() ) {
					if ( gettype( $return_data ) === 'array' ) {
						return $return_data;
					} elseif ( gettype( $return_data ) === 'object' ) {
						return (array) $return_data;
					}
				} else {
					return false;
				}
			} catch ( Exception $e ) {
				error_log( $e->getMessage() ); //phpcs:ignore
			}
		}
	}
}