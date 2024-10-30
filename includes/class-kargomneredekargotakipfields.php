<?php /**
	   * View Settings
	   *
	   * @package for Admin settings
	   */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'KargomNeredeKargoTakipFields' ) ) {

	/**
	 * Class for Form Fields.
	 */
	class KargomNeredeKargoTakipFields {

		/**
		 * Form name.
		 *
		 * @var string
		 */
		public $form_name = null;

		/**
		 * Form id.
		 *
		 * @var mull
		 */
		public $form_id = null;

		/**
		 * Form action post.
		 *
		 * @var string
		 */
		public $form_action = '';

		/**
		 * Form method post.
		 *
		 * @var string
		 */
		protected $form_method = 'post';

		/**
		 * Form header getter.
		 *
		 * @param string $app_name app name.
		 */
		public function __construct( $app_name ) {

			$this->form_name = $app_name . '_save_form';
			$this->form_id   = $app_name . '_save_form';

		}

		/**
		 * Form header getter.
		 *
		 * @param  string $method set method.
		 */
		public function set_form_method( $method ) {
			$this->form_method = $method;
		}

		/**
		 * Form header getter.
		 *
		 * @param  string $action set action.
		 */
		public function set_form_action( $action ) {
			$this->form_action = $action;
		}

		/**
		 * Form header getter.
		 */
		public function get_form_header() {
			$this->form_action = admin_url( 'admin-post.php' );
			$form_header       = '<form enctype="multipart/form-data" method="' . $this->form_method . '" action="' . esc_url( $this->form_action ) . '" ';
			if ( isset( $this->form_name ) && ! empty( $this->form_name ) ) {
				$form_header .= ' name="' . esc_attr( $this->form_name ) . '" '; }
			if ( isset( $this->form_id ) && ! empty( $this->form_id ) ) {
				$form_header .= ' id="' . esc_attr( $this->form_id ) . '" '; }
			$form_header .= '>';
			return $form_header;

		}

		/**
		 * Form footer getter.
		 *
		 * @return html Generate form footer html.
		 */
		public function get_form_footer() {

			$form_footer  = '<div class="submit-button-plugin"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></div>';
			$form_footer .= '</form>';
			return $form_footer;
		}
		/**
		 * Echo  form html.
		 *
		 * @param array $fields_markup for all fields.
		 * For the field.
		 */
		public function render( $fields_markup ) {
			$form_output = '';
			if ( empty( $fields_markup ) ) {
				$form_output = 'Please add form field first!';
			}
			$form_output .= '<div class="wpic_plugin_setting">

					<div class="wpic_setting_container">';
			$form_header  = $this->get_form_header();
			$form_html    = $form_header . $fields_markup . $this->get_form_footer();
			$form_output .= $form_html;

			$form_output .= '</div>
			</div>';

			$form_output = balanceTags( $form_output );

			echo wp_kses_post( $form_output );

		}

		/**
		 * Output a text input box.
		 *
		 * @param array $field for all fields.
		 * For the field.
		 */
		public function wpub_wp_status( $field ) {

			$field['class']         = isset( $field['class'] ) ? $field['class'] . ' basic-text' : 'short basic-text';
			$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
			$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
			$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
			$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
			$field['type']          = isset( $field['type'] ) ? $field['type'] : 'text';
			$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;

			// Custom attribute handling.
			$custom_attributes = array();

			if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

				foreach ( $field['custom_attributes'] as $attribute => $value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
				}
			}

			$form_element  = '<div class="wpic-group ' . ( $field['id'] ) . '_field ' . ( $field['wrapper_class'] ) . '">';
			$form_element .= '<div class="wpic-row">';
			$form_element .= '<div class="wpic-cd-4">
								<div class="wpic-label">';
			$form_element .= '<label for="' . esc_attr( $field['name'] ) . '">' . ( $field['label'] ) . '</label>';
			$form_element .= '</div></div>';

			$form_element .= '<div class="wpic-cd-6">';

			$form_element .= '<div class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . ' id="' . esc_attr( $field['id'] ) . ' ' . implode( ' ', $custom_attributes ) . ' > ';
			$form_element .= ( $field['value'] );

			$form_element .= '</div>';

			$form_element .= '</div>';
			if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
				$form_element .= '<span class="description">' . ( $field['description'] ) . '</span>';
			}
			$form_element .= '</div>';// End of row.
			$form_element .= '</div>'; // end of group.

			return $form_element;
		}

		/**
		 * Output a text input box.
		 *
		 * @param array $field for all fields.
		 * For the field.
		 */
		public function wpub_wp_text_input( $field ) {

			$field['placeholder']   = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
			$field['class']         = isset( $field['class'] ) ? $field['class'] . ' basic-text' : 'short basic-text';
			$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
			$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
			$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
			$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
			$field['type']          = isset( $field['type'] ) ? $field['type'] : 'text';
			$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;

			$data_type = empty( $field['data_type'] ) ? '' : $field['data_type'];

			switch ( $data_type ) {
				case 'url':
					$field['class'] .= ' wc_input_url';
					$field['value']  = esc_url( $field['value'] );
					break;

				default:
					break;
			}

			// Custom attribute handling.
			$custom_attributes = array();

			if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

				foreach ( $field['custom_attributes'] as $attribute => $value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
				}
			}

			$form_element  = '<div class="wpic-group ' . ( $field['id'] ) . '_field ' . ( $field['wrapper_class'] ) . '">';
			$form_element .= '<div class="wpic-row">';
			$form_element .= '<div class="wpic-cd-4">
								<div class="wpic-label">';
			$form_element .= '<label for="' . ( $field['name'] ) . '">' . ( $field['label'] ) . '</label>';
			$form_element .= '</div></div>';

			$form_element .= '<div class="wpic-cd-6">';

			// $form_element .= '<input type="' . esc_attr( $field['type'] ) . '" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" ' . implode( ' ', $custom_attributes ) . ' /> ';
			$form_element .= '<p " class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" > ' . esc_attr( $field['value'] ) . '</p> ';

			$form_element .= '</div>';
			if ( ! empty( $field['description'] ) && false !== $field['desc_tip'] ) {
				$form_element .= '<span class="description">' . ( $field['description'] ) . '</span>';
			}
			$form_element .= '</div>';// End of row.
			$form_element .= '</div>'; // end of group.

			return $form_element;
		}

		/**
		 * Output a hidden input box.
		 *
		 * @param array $field for all fields.
		 * For the field.
		 */
		public function wpub_wp_hidden_input( $field ) {

			$field['value'] = isset( $field['value'] ) ? $field['value'] : '';
			$field['class'] = isset( $field['class'] ) ? $field['class'] : '';
			$field['id']    = isset( $field['id'] ) ? $field['id'] : '';

			return '<input type="hidden" class="' . esc_attr( $field['class'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['value'] ) . '" /> ';
		}

		/**
		 * Output a textarea input box.
		 *
		 * @param array $field for all fields.
		 * For the field.
		 */
		public function wpub_wp_textarea_input( $field ) {

			$field['placeholder']   = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
			$field['class']         = isset( $field['class'] ) ? $field['class'] : 'short';
			$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
			$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
			$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
			$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;
			$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
			$field['rows']          = isset( $field['rows'] ) ? $field['rows'] : 2;
			$field['cols']          = isset( $field['cols'] ) ? $field['cols'] : 20;

			$hide_form_field = ( $field['show'] ) ? '' : 'hide';

			// Custom attribute handling.
			$custom_attributes = array();

			if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

				foreach ( $field['custom_attributes'] as $attribute => $value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
				}
			}

			$element = '';

			$element = ' <div class="wpic-group form-field ' . esc_attr( $field['wrapper_class'] ) . ' ' . esc_attr( $hide_form_field ) . '" id="' . esc_attr( $field['id'] ) . '">';

			$element .= '<div class="wpic-row">';
			$element .= '<div class="wpic-cd-4">
								<div class="wpic-label">
				<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';

			$element .= '</div></div>';

			$element .= '<div class="wpic-cd-6">';
			$element .= '<textarea class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '"  name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" placeholder="' . esc_attr( $field['placeholder'] ) . '" rows="' . esc_attr( $field['rows'] ) . '" cols="' . esc_attr( $field['cols'] ) . '" ' . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $field['value'] ) . '</textarea> ';
			$element .= '';

			if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
				$element .= '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
			}
			$element .= '</div></div>';// End of row.
			$element .= '</div>';
			return $element;
		}

		/**
		 * Output a checkbox input box.
		 *
		 * @param array $field for all fields.
		 * For the field.
		 */
		public function wpub_wp_checkbox( $field ) {

			$field['class']         = isset( $field['class'] ) ? $field['class'] : 'checkbox';
			$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
			$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
			$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
			$field['cbvalue']       = isset( $field['cbvalue'] ) ? $field['cbvalue'] : 'yes';
			$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
			$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;

			// Custom attribute handling.
			$custom_attributes = array();

			if ( ! empty( $field['custom_attributes'] ) && is_array( $field['custom_attributes'] ) ) {

				foreach ( $field['custom_attributes'] as $attribute => $value ) {
					$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $value ) . '"';
				}
			}

			$element = '';

			$element .= '<div  class="wpic-group ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '">';
			$element .= '<div class="wpic-row">';
			$element .= '<div class="wpic-cd-4">
								<div class="wpic-label">';
			$element .= '<label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label>';
			$element .= '</div></div>';
			$element .= '<div class="wpic-cd-6">
				<div class="wpic_checkout_container">';

			$element .= '<input type="checkbox" class="' . esc_attr( $field['class'] ) . '" style="' . esc_attr( $field['style'] ) . '" name="' . esc_attr( $field['name'] ) . '" id="' . esc_attr( $field['id'] ) . '" value="' . esc_attr( $field['cbvalue'] ) . '" ' . checked( $field['value'], $field['cbvalue'], false ) . '  ' . implode( ' ', $custom_attributes ) . '/> ';
			$element .= '</div>';

			if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
				$element .= '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
			}

			$element .= '</div>';
			$element .= '</div>';
			$element .= '</div>';
			return $element;
		}

		/**
		 * Output a html row attribute.
		 *
		 * @param array $raw_attributes for all fields.
		 * For the field.
		 */
		public function wpub_implode_html_attributes( $raw_attributes ) {
			$attributes = array();
			foreach ( $raw_attributes as $name => $value ) {
				$attributes[] = esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
			}
			return implode( ' ', $attributes );
		}

		/**
		 * Output separator with heading
		 *
		 * @param array $field for all fields.
		 * For the field.
		 */
		public function wpub_wp_group( $field ) {

			$field['class']         = isset( $field['class'] ) ? $field['class'] : 'short';
			$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
			$field['label']         = isset( $field['label'] ) ? $field['label'] : '';
			$element                = '';

			$element .= '<div class="wpic-group wpic-setting-separator">
				<div class="wpic-row"> 
					<div class="wpic-cd-12">
						<h3 class="wpic-black-white">' . $field['label'] . '</h3>
					</div>
				</div>
			</div>';

			return $element;
		}

		/**
		 * Output a HTMl content.
		 *
		 * @param array $field for all fields.
		 * For the field.
		 */
		public function wpub_wp_html( $field ) {

			$field['class']         = isset( $field['class'] ) ? $field['class'] : 'short';
			$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
			$field['label']         = isset( $field['label'] ) ? $field['label'] : '';
			$field['is_full_width'] = isset( $field['is_full_width'] ) ? $field['is_full_width'] : false;
			$field['html']          = isset( $field['html'] ) ? $field['html'] : '';

			if ( empty( $field['html'] ) ) {
				return;
			}

			$element = '';

			$element .= '<div class="wpic-group">
				<div class="wpic-row"> ';
			if ( $field['is_full_width'] ) {

				$element .= '<div class="wpic-cd-12">
								' . $field['html'] . '
					</div>';

			} else {
				$element .= '<div class="wpic-cd-12">
						<h3 class="wpic-black-white">' . $field['label'] . '</h3>
					</div>';

			}

				$element .= '</div>
			</div>';

			return $element;
		}

		/**
		 * Output a select input box.
		 *
		 * @param array $field Data about the field to render.
		 */
		public function wpub_wp_select( $field ) {

			$field['value'] = isset( $field['value'] ) ? $field['value'] : '';
			$field          = wp_parse_args(
				$field,
				array(
					'class'             => 'select short',
					'style'             => '',
					'wrapper_class'     => '',
					'value'             => $field['value'],
					'name'              => $field['id'],
					'desc_tip'          => false,
					'custom_attributes' => array(),
				)
			);

			$wrapper_attributes = array(
				'class' => $field['wrapper_class'] . " form-field {$field['id']}_field",
			);

			$label_attributes = array(
				'for' => $field['id'],
			);

			$field_attributes          = (array) $field['custom_attributes'];
			$field_attributes['style'] = $field['style'];
			$field_attributes['id']    = $field['id'];
			$field_attributes['name']  = $field['name'];
			$field_attributes['class'] = $field['class'];

			$tooltip     = ! empty( $field['description'] ) && false !== $field['desc_tip'] ? $field['description'] : '';
			$description = ! empty( $field['description'] ) && false === $field['desc_tip'] ? $field['description'] : '';

			$element = '<div  class="wpic-group' . esc_attr( $field['wrapper_class'] ) . '" id="' . esc_attr( $field['id'] ) . '">
			<div class="wpic-row">
		<div class="wpic-cd-4"><div class="wpic-label"><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label></div></div>';

			$element .= '<div class="wpic-cd-6"><div class="form-table__input-wrap"><select ' . $this->wpub_implode_html_attributes( $field_attributes ) . '>';
			foreach ( $field['options'] as $key => $value ) {
				$element .= '<option value="' . esc_attr( $key ) . '"' . wc_selected( $key, $field['value'] ) . '>' . esc_html( $value ) . '</option>';
			}
			$element .= '</select>';
			if ( $description ) :
				$element .= '<span class="description">' . wp_kses_post( $description ) . '</span>';
			endif;
			$element .= ' </div>
			</div></div>';

			return $element;

		}

		/**
		 * Output a radio input box.
		 *
		 * @param array $field for all fields.
		 * For the field.
		 */
		public function wpub_wp_radio( $field ) {

			$field['class']         = isset( $field['class'] ) ? $field['class'] : 'select short';
			$field['style']         = isset( $field['style'] ) ? $field['style'] : '';
			$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
			$field['value']         = isset( $field['value'] ) ? $field['value'] : '';
			$field['name']          = isset( $field['name'] ) ? $field['name'] : $field['id'];
			$field['desc_tip']      = isset( $field['desc_tip'] ) ? $field['desc_tip'] : false;

			$elements = '<div  class="wpic-group' . esc_attr( $field['wrapper_class'] ) . '" id="' . esc_attr( $field['id'] ) . '">
		<div class="wpic-row">
		<div class="wpic-cd-4"><div class="wpic-label"><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label></div></div>';

			$elements .= '<div class="wpic-cd-6"><ul class="wc-radios">';

			foreach ( $field['options'] as $key => $value ) {

				$elements .= '<li><label><input
						name="' . esc_attr( $field['name'] ) . '"
						value="' . esc_attr( $key ) . '"
						type="radio"
						class="' . esc_attr( $field['class'] ) . '"
						style="' . esc_attr( $field['style'] ) . '"
						' . checked( esc_attr( $field['value'] ), esc_attr( $key ), false ) . '
						/> ' . esc_html( $value['label'] ) . '</label>
				</li>';
			}
			$elements .= '</ul>';

			if ( ! empty( $field['description'] ) && false === $field['desc_tip'] ) {
				$elements .= '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
			}

			$elements .= '
			</div>
			</div>
			</div>';
			return $elements;
		}



	}
}










