<?php

class DSM_ImageAccordion extends ET_Builder_Module {
	public $icon_path;

	protected $module_credits = array(
		'module_uri' => 'https://divisupreme.com/',
		'author'     => 'Divi Supreme',
		'author_uri' => 'https://divisupreme.com/',
	);

	public function init() {
		$this->slug             = 'dsm_image_accordion';
		$this->child_slug       = 'dsm_image_accordion_child';
		$this->vb_support       = 'on';
		$this->name             = esc_html__( 'Supreme Image Accordion', 'dsm-supreme-modules-pro-for-divi' );
		$this->icon_path        = plugin_dir_path( __FILE__ ) . 'icon.svg';
		$this->main_css_element = '%%order_class%%';
	}

	public function get_settings_modal_toggles() {
		return array(
			'general'  => array(
				'toggles' => array(
					'settings' => esc_html__( 'Accordion Settings', 'dsm-supreme-modules-pro-for-divi' ),
				),
			),
			'advanced' => array(
				'toggles' => array(
					'ia_icon'       => esc_html__( 'Icon', 'dsm-supreme-modules-pro-for-divi' ),
					'ia_image'      => esc_html__( 'Icon Image', 'dsm-supreme-modules-pro-for-divi' ),
					'ia_title'      => esc_html__( 'Title', 'dsm-supreme-modules-pro-for-divi' ),
					'ia_desc'       => esc_html__( 'Description', 'dsm-supreme-modules-pro-for-divi' ),
					'overlay'       => esc_html__( 'Overlay Color', 'dsm-supreme-modules-pro-for-divi' ),
					'overlay_title' => esc_html__( 'Overlay Content', 'dsm-supreme-modules-pro-for-divi' ),
				),
			),
		);
	}

	public function get_fields() {

		$fields = array();

		$fields['image_accordion_type'] = array(
			'label'       => esc_html__( 'Accordion Type', 'dsm-supreme-modules-pro-for-divi' ),
			'type'        => 'select',
			'default'     => 'on_hover',
			'options'     => array(
				'on_hover' => esc_html__( 'By Hover', 'dsm-supreme-modules-pro-for-divi' ),
				'on_click' => esc_html__( 'By Click', 'dsm-supreme-modules-pro-for-divi' ),
			),
			'toggle_slug' => 'settings',
		);

		$fields['image_accordion_animation'] = array(
			'label'       => esc_html__( 'Accordion Content Animation', 'dsm-supreme-modules-pro-for-divi' ),
			'type'        => 'select',
			'default'     => 'fade_in',
			'options'     => array(
				'fade_in'    => esc_html__( 'Fade In', 'dsm-supreme-modules-pro-for-divi' ),
				'zoom_in'    => esc_html__( 'Zoom In', 'dsm-supreme-modules-pro-for-divi' ),
				'push_up'    => esc_html__( 'Push Up', 'dsm-supreme-modules-pro-for-divi' ),
				'push_down'  => esc_html__( 'Push Down', 'dsm-supreme-modules-pro-for-divi' ),
				'push_left'  => esc_html__( 'Push Left', 'dsm-supreme-modules-pro-for-divi' ),
				'push_right' => esc_html__( 'Push Right', 'dsm-supreme-modules-pro-for-divi' ),
			),
			'toggle_slug' => 'settings',
		);

		$fields['image_accordion_animation_sequence'] = array(
			'label'       => esc_html__( 'Use Sequenced Animation', 'dsm-supreme-modules-pro-for-divi' ),
			'type'        => 'yes_no_button',
			'default'     => 'off',
			'options'     => array(
				'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
				'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
			),
			'toggle_slug' => 'settings',
		);

		$fields['ia_direction'] = array(
			'label'          => esc_html__( 'Accordion Direction', 'dsm-supreme-modules-pro-for-divi' ),
			'type'           => 'select',
			'default'        => 'horizontal',
			'mobile_options' => true,
			'options'        => array(
				'horizontal' => esc_html__( 'Horizontal', 'dsm-supreme-modules-pro-for-divi' ),
				'vertical'   => esc_html__( 'Vertical', 'dsm-supreme-modules-pro-for-divi' ),
			),
			'toggle_slug'    => 'settings',
		);

		$fields['ia_height'] = array(
			'label'          => esc_html__( 'Accordion Height', 'dsm-supreme-modules-pro-for-divi' ),
			'type'           => 'range',
			'default'        => '380px',
			'default_unit'   => 'px',
			'range_settings' => array(
				'min'  => '1',
				'max'  => '1200',
				'step' => '10',
			),
			'validate_unit'  => true,
			'mobile_options' => true,
			'toggle_slug'    => 'settings',
		);
		/*
		$fields['anim_speed'] = array(
			'label'          => esc_html__( 'Toggle Animation Speed', 'dsm-supreme-modules-pro-for-divi' ),
			'type'           => 'range',
			'default'        => '300ms',
			'default_unit'   => 'ms',
			'range_settings' => array(
				'min'  => '1',
				'max'  => '2000',
				'step' => '1',
			),
			'toggle_slug'    => 'settings',
		);

		$fields['anim_delay'] = array(
			'label'          => esc_html__( 'Toggle Animation Delay', 'dsm-supreme-modules-pro-for-divi' ),
			'type'           => 'range',
			'default'        => '80ms',
			'default_unit'   => 'ms',
			'range_settings' => array(
				'min'  => '1',
				'max'  => '2000',
				'step' => '1',
			),
			'toggle_slug'    => 'settings',
		);*/

		$fields['ia_icon_color'] = array(
			'label'          => esc_html__( 'Icon Color', 'dsm-supreme-modules-pro-for-divi' ),
			'type'           => 'color-alpha',
			'tab_slug'       => 'advanced',
			'default'        => '#fff',
			'mobile_options' => true,
			'toggle_slug'    => 'ia_icon',
		);

		$fields['use_ia_icon_font_size'] = array(
			'label'       => esc_html__( 'Use Icon Font Size', 'dsm-supreme-modules-pro-for-divi' ),
			'type'        => 'yes_no_button',
			'options'     => array(
				'off' => esc_html__( 'No', 'dsm-supreme-modules-pro-for-divi' ),
				'on'  => esc_html__( 'Yes', 'dsm-supreme-modules-pro-for-divi' ),
			),
			'tab_slug'    => 'advanced',
			'toggle_slug' => 'ia_icon',
		);

		$fields['ia_icon_font_size'] = array(
			'label'            => esc_html__( 'Icon Font Size', 'dsm-supreme-modules-pro-for-divi' ),
			'type'             => 'range',
			'default'          => '40px',
			'default_unit'     => 'px',
			'default_on_front' => '40px',
			'allowed_units'    => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
			'show_if'          => array(
				'use_ia_icon_font_size' => 'on',
			),
			'range_settings'   => array(
				'min'  => '1',
				'max'  => '150',
				'step' => '1',
			),
			'validate_unit'    => true,
			'mobile_options'   => true,
			'tab_slug'         => 'advanced',
			'toggle_slug'      => 'ia_icon',
		);

		$fields['overlay_color'] = array(
			'label'          => esc_html__( 'Overlay Color', 'dsm-supreme-modules-pro-for-divi' ),
			'type'           => 'color-alpha',
			'mobile_options' => true,
			'tab_slug'       => 'advanced',
			'toggle_slug'    => 'overlay',
			'hover'          => 'tabs',
		);

		$fields['ia_overlay_align_horizontal'] = array(
			'label'          => esc_html__( 'Horizontal Align', 'dsm-supreme-modules-pro-for-divi' ),
			'type'           => 'select',
			'default'        => 'center',
			'mobile_options' => true,
			'options'        => array(
				'flex-start' => esc_html__( 'Left', 'dsm-supreme-modules-pro-for-divi' ),
				'center'     => esc_html__( 'Center', 'dsm-supreme-modules-pro-for-divi' ),
				'flex-end'   => esc_html__( 'Right', 'dsm-supreme-modules-pro-for-divi' ),
			),
			'tab_slug'       => 'advanced',
			'toggle_slug'    => 'overlay_title',
		);

		$fields['ia_overlay_align_vertical'] = array(
			'label'          => esc_html__( 'Vertical Align', 'dsm-supreme-modules-pro-for-divi' ),
			'type'           => 'select',
			'default'        => 'center',
			'mobile_options' => true,
			'options'        => array(
				'flex-start' => esc_html__( 'Top', 'dsm-supreme-modules-pro-for-divi' ),
				'center'     => esc_html__( 'Center', 'dsm-supreme-modules-pro-for-divi' ),
				'flex-end'   => esc_html__( 'Bottom', 'dsm-supreme-modules-pro-for-divi' ),
			),
			'tab_slug'       => 'advanced',
			'toggle_slug'    => 'overlay_title',
		);

		$fields['overlay_content_padding'] = array(
			'label'           => esc_html__( 'Padding', 'dsm-supreme-modules-pro-for-divi' ),
			'description'     => esc_html__( 'Here you can define a custom padding size for the Overlay Content.', 'dsm-supreme-modules-pro-for-divi' ),
			'type'            => 'custom_padding',
			'option_category' => 'layout',
			'tab_slug'        => 'advanced',
			'toggle_slug'     => 'overlay_title',
			'default_unit'    => 'px',
			'allowed_units'   => array( '%', 'em', 'rem', 'px', 'cm', 'mm', 'in', 'pt', 'pc', 'ex', 'vh', 'vw' ),
			'range_settings'  => array(
				'min'  => '1',
				'max'  => '80',
				'step' => '1',
			),
			'default'         => '10px|10px|10px|10px',
			'mobile_options'  => true,
			'responsive'      => true,
			'hover'           => 'tabs',
		);

		return $fields;
	}

	public function get_advanced_fields_config() {

		$advanced_fields                   = array();
		$advanced_fields['text']           = false;
		$advanced_fields['text_shadow']    = false;
		$advanced_fields['fonts']          = array();
		$advanced_fields['fonts']['title'] = array(
			'label'           => esc_html__( 'Title', 'dsm-supreme-modules-pro-for-divi' ),
			'css'             => array(
				'main' => '%%order_class%% .dsm_image_accordion_title',
			),
			'hide_text_align' => true,
			'toggle_slug'     => 'ia_title',
			'line_height'     => array(
				'default'        => '1em',
				'range_settings' => array(
					'min'  => '1',
					'max'  => '3',
					'step' => '0.1',
				),
			),
			'header_level'    => array(
				'default' => 'h3',
			),
			'important'       => 'all',
		);

		$advanced_fields['fonts']['desc'] = array(
			'label'           => esc_html__( 'Description', 'dsm-supreme-modules-pro-for-divi' ),
			'css'             => array(
				'main' => '%%order_class%% .dsm_image_accordion_description',
			),
			'hide_text_align' => true,
			'line_height'     => array(
				'default'        => '1em',
				'range_settings' => array(
					'min'  => '1',
					'max'  => '3',
					'step' => '0.1',
				),
			),
			'toggle_slug'     => 'ia_desc',
		);

		$advanced_fields['fonts']['overlay_title'] = array(
			'label'           => esc_html__( 'Overlay Title', 'dsm-supreme-modules-pro-for-divi' ),
			'css'             => array(
				'main' => '%%order_class%% .dsm_image_accordion_overylay_title',
			),
			'hide_text_align' => true,
			'toggle_slug'     => 'overlay_title',
			'line_height'     => array(
				'default'        => '1em',
				'range_settings' => array(
					'min'  => '1',
					'max'  => '3',
					'step' => '0.1',
				),
			),
		);

		$advanced_fields['borders']['default'] = array(
			'css' => array(
				'main' => array(
					'border_radii'  => '%%order_class%%',
					'border_styles' => '%%order_class%%',
				),
			),
		);

		$advanced_fields['box_shadow']['default'] = array(
			'css' => array(
				'main' => '%%order_class%%',
			),
		);

		$advanced_fields['borders']['image'] = array(
			'label_prefix' => esc_html__( 'Image', 'dsm-supreme-modules-pro-for-divi' ),
			'css'          => array(
				'main' => array(
					'border_radii'  => '%%order_class%% .dsm-accordion-image',
					'border_styles' => '%%order_class%% .dsm-accordion-image',
				),
			),
			'tab_slug'     => 'advanced',
			'toggle_slug'  => 'ia_image',
		);

		$advanced_fields['box_shadow']['image'] = array(
			'label'       => esc_html__( 'Image', 'dsm-supreme-modules-pro-for-divi' ),
			'css'         => array(
				'main'    => '%%order_class%% .dsm-accordion-image',
				'overlay' => 'inset',
			),
			'tab_slug'    => 'advanced',
			'toggle_slug' => 'ia_image',
		);

		$advanced_fields['button']['button'] = array(
			'label'          => esc_html__( 'Button', 'dsm-supreme-modules-pro-for-divi' ),
			'use_alignment'  => true,
			'css'            => array(
				'main'      => '%%order_class%% .dsm_ia_button.et_pb_button',
				'alignment' => '%%order_class%% .dsm_image_accordion_child_content .et_pb_button_wrapper.dsm_image_accordion_button_wrapper',
				'important' => true,
			),
			'box_shadow'     => array(
				'css' => array(
					'main'      => '%%order_class%% .dsm_ia_button.et_pb_button',
					'important' => true,
				),
			),
			'margin_padding' => array(
				'css' => array(
					'main'      => '%%order_class%% .dsm_ia_button.et_pb_button',
					'important' => 'all',
				),
			),
		);

		return $advanced_fields;
	}

	public function render( $attrs, $content, $render_slug ) {

		$this->apply_css( $render_slug );
		$this->add_classname(
			array(
				"dsm_image_accordion_animation_{$this->props['image_accordion_animation']}",
				"dsm_image_accordion_animation_sequence_{$this->props['image_accordion_animation_sequence']}",
			)
		);
		wp_enqueue_script( 'dsm-image-accordion' );

		if ( isset( get_option( 'dsm_settings_misc' )['dsm_dynamic_assets'] ) && ! empty( get_option( 'dsm_settings_misc' )['dsm_dynamic_assets'] ) && 'on' === get_option( 'dsm_settings_misc' )['dsm_dynamic_assets'] ) {
			if ( isset( get_option( 'dsm_settings_misc' )['dsm_dynamic_assets_compatibility'] ) && ! empty( get_option( 'dsm_settings_misc' )['dsm_dynamic_assets'] ) && 'on' === get_option( 'dsm_settings_misc' )['dsm_dynamic_assets_compatibility'] ) {
				wp_enqueue_style( 'dsm-image-accordion', plugin_dir_url( __DIR__ ) . 'ImageAccordion/style.css', array(), DSM_PRO_VERSION, 'all' );
			} else {
				add_filter( 'et_global_assets_list', array( $this, 'dsm_load_required_divi_assets' ), 10, 3 );
				add_filter( 'et_late_global_assets_list', array( $this, 'dsm_load_required_divi_assets' ), 10, 3 );
			}
		}

		return sprintf(
			'<div class="dsm_image_accordion_wrapper dsm_image_accordion_trigger_%2$s">
                %1$s
            </div>',
			et_core_sanitized_previously( $this->content ),
			$this->props['image_accordion_type']
		);
	}

	public function apply_css( $render_slug ) {
		$this->height_css( $render_slug );
		$ia_direction             = $this->props['ia_direction'];
		$ia_direction_tablet      = $this->props['ia_direction_tablet'];
		$ia_direction_phone       = $this->props['ia_direction_phone'];
		$ia_direction_last_edited = $this->props['ia_direction_last_edited'];

		$ia_direction_responsive_status = et_pb_get_responsive_status( $ia_direction_last_edited );

		// Overlay Title.
		$this->generate_styles(
			array(
				'base_attr_name' => 'ia_overlay_align_horizontal',
				'selector'       => '%%order_class%% .dsm_image_accordion_child .dsm_image_accordion_overylay_content',
				'css_property'   => 'justify-content',
				'render_slug'    => $render_slug,
				'sticky'         => false,
				'responsive'     => true,
				'hover'          => false,
			)
		);

		$this->generate_styles(
			array(
				'base_attr_name' => 'ia_overlay_align_vertical',
				'selector'       => '%%order_class%% .dsm_image_accordion_child .dsm_image_accordion_overylay_content',
				'css_property'   => 'align-items',
				'render_slug'    => $render_slug,
				'sticky'         => false,
				'responsive'     => true,
				'hover'          => false,
			)
		);

		$this->apply_custom_margin_padding(
			$render_slug,
			'overlay_content_padding',
			'padding',
			'%%order_class%% .dsm_image_accordion_overylay_content'
		);

		if ( 'vertical' === $ia_direction ) :

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .dsm_image_accordion_wrapper',
					'declaration' => 'flex-direction: column;',
				)
			);

		elseif ( 'horizontal' === $ia_direction ) :

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .dsm_image_accordion_wrapper',
					'declaration' => 'flex-direction: row;',
				)
			);

		endif;

		if ( $ia_direction_responsive_status ) {

			if ( 'vertical' === $ia_direction_tablet ) :

				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .dsm_image_accordion_wrapper',
						'declaration' => 'flex-direction: column;',
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
					)
				);

			elseif ( 'horizontal' === $ia_direction_tablet ) :

				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .dsm_image_accordion_wrapper',
						'declaration' => 'flex-direction: row;',
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
					)
				);

			endif;

			if ( 'vertical' === $ia_direction_phone ) :

				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .dsm_image_accordion_wrapper',
						'declaration' => 'flex-direction: column;',
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
					)
				);

			elseif ( 'horizontal' === $ia_direction_phone ) :

				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .dsm_image_accordion_wrapper',
						'declaration' => 'flex-direction: row;',
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
					)
				);

			endif;

		}

		/*
		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .dsm_image_accordion_child .dsm_image_accordion_child_content, %%order_class%% .dsm_image_accordion_child.dsm_image_accordion_active_item .dsm_image_accordion_child_content',
				'declaration' => sprintf( 'transition-duration:  %1$s;', $this->props['image_accordion_animation_speed'] ),
			)
		);

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .dsm_image_accordion_child',
				'declaration' => sprintf( 'transition-duration: %1$s;', $this->props['anim_speed'] ),
			)
		);

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .dsm_image_accordion_child, %%order_class%% .dsm_image_accordion_child.dsm_image_accordion_active_item .dsm_image_accordion_child_content',
				'declaration' => sprintf( 'transition-delay:  %1$s;', $this->props['anim_delay'] ),
			)
		);*/

		$use_ia_icon_font_size = $this->props['use_ia_icon_font_size'];

		$ia_icon_color = $this->props['ia_icon_color'];

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .dsm_image_accordion_icon',
				'declaration' => "color: {$ia_icon_color};",
			)
		);

		$ia_icon_color_last_edited       = $this->props['ia_icon_color_last_edited'];
		$ia_icon_color_responsive_status = et_pb_get_responsive_status( $ia_icon_color_last_edited );

		if ( $ia_icon_color_responsive_status ) {

			$ia_icon_color_tablet = $this->props['ia_icon_color_tablet'];
			$ia_icon_color_phone  = $this->props['ia_icon_color_phone'];

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .dsm_image_accordion_icon',
					'declaration' => "color: {$ia_icon_color_tablet};",
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
				)
			);
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .dsm_image_accordion_icon',
					'declaration' => "color: {$ia_icon_color_phone};",
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
				)
			);
		}

		if ( 'on' === $use_ia_icon_font_size ) {
			$ia_icon_font_size        = $this->props['ia_icon_font_size'];
			$ia_icon_font_size_tablet = $this->props['ia_icon_font_size_tablet'];
			$ia_icon_font_size_phone  = $this->props['ia_icon_font_size_phone'];

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .dsm_image_accordion_icon',
					'declaration' => "font-size: {$ia_icon_font_size};",
				)
			);

			$ia_icon_font_size_last_edited       = $this->props['ia_icon_font_size_last_edited'];
			$ia_icon_font_size_responsive_status = et_pb_get_responsive_status( $ia_icon_font_size_last_edited );

			if ( $ia_icon_font_size_responsive_status ) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .dsm_image_accordion_icon',
						'declaration' => "font-size: {$ia_icon_font_size_tablet};",
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
					)
				);

				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%% .dsm_image_accordion_icon',
						'declaration' => "font-size: {$ia_icon_font_size_phone};",
						'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
					)
				);
			}
		}

		// Overlay.
		if ( '' !== $this->props['overlay_color'] ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .dsm_image_accordion_child>div:before',
					'declaration' => sprintf(
						'background: %1$s;',
						$this->props['overlay_color']
					),
				)
			);
		}

		$overlay_color_last_edited       = $this->props['overlay_color_last_edited'];
		$overlay_color_responsive_status = et_pb_get_responsive_status( $overlay_color_last_edited );
		if ( $overlay_color_responsive_status ) {
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .dsm_image_accordion_child>div:before',
					'declaration' => sprintf(
						'background: %1$s;',
						$this->props['overlay_color_tablet']
					),
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
				)
			);
			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .dsm_image_accordion_child>div:before',
					'declaration' => sprintf(
						'background: %1$s;',
						$this->props['overlay_color_phone']
					),
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
				)
			);

		}
		if ( isset( $this->props['overlay_color__hover'] ) ) {

			$overlay_color_hover = explode( '|', $this->props['overlay_color__hover'] );

			if ( isset( $overlay_color_hover ) ) {
				ET_Builder_Element::set_style(
					$render_slug,
					array(
						'selector'    => '%%order_class%%:hover .dsm_image_accordion_child>div:before',
						'declaration' => sprintf(
							'background: %1$s;',
							$this->props['overlay_color__hover']
						),
					)
				);
			}
		}
	}

	private function height_css( $render_slug ) {
		$ia_height        = $this->props['ia_height'];
		$ia_height_tablet = $this->props['ia_height_tablet'];
		$ia_height_phone  = $this->props['ia_height_phone'];

		$ia_height_last_edited       = $this->props['ia_height_last_edited'];
		$ia_height_responsive_status = et_pb_get_responsive_status( $ia_height_last_edited );

		ET_Builder_Element::set_style(
			$render_slug,
			array(
				'selector'    => '%%order_class%% .dsm_image_accordion_wrapper',
				'declaration' => sprintf( 'height: %1$s;', $ia_height ),
			)
		);

		if ( $ia_height_responsive_status ) {

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .dsm_image_accordion_wrapper',
					'declaration' => sprintf( 'height: %1$s;', $ia_height_tablet ),
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
				)
			);

			ET_Builder_Element::set_style(
				$render_slug,
				array(
					'selector'    => '%%order_class%% .dsm_image_accordion_wrapper',
					'declaration' => sprintf( 'height: %1$s;', $ia_height_phone ),
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
				)
			);
		}
	}

	public function apply_custom_margin_padding( $function_name, $slug, $type, $class, $important = false ) {
		$slug_value                   = $this->props[ $slug ];
		$slug_value_tablet            = $this->props[ $slug . '_tablet' ];
		$slug_value_phone             = $this->props[ $slug . '_phone' ];
		$slug_value_last_edited       = $this->props[ $slug . '_last_edited' ];
		$slug_value_responsive_active = et_pb_get_responsive_status( $slug_value_last_edited );

		if ( isset( $slug_value ) && ! empty( $slug_value ) ) {
			ET_Builder_Element::set_style(
				$function_name,
				array(
					'selector'    => $class,
					'declaration' => et_builder_get_element_style_css( $slug_value, $type, $important ),
				)
			);
		}

		if ( isset( $slug_value_tablet ) && ! empty( $slug_value_tablet ) && $slug_value_responsive_active ) {
			ET_Builder_Element::set_style(
				$function_name,
				array(
					'selector'    => $class,
					'declaration' => et_builder_get_element_style_css( $slug_value_tablet, $type, $important ),
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
				)
			);
		}

		if ( isset( $slug_value_phone ) && ! empty( $slug_value_phone ) && $slug_value_responsive_active ) {
			ET_Builder_Element::set_style(
				$function_name,
				array(
					'selector'    => $class,
					'declaration' => et_builder_get_element_style_css( $slug_value_phone, $type, $important ),
					'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
				)
			);
		}
		if ( et_builder_is_hover_enabled( $slug, $this->props ) ) {
			if ( isset( $this->props[ $slug . '__hover' ] ) ) {
				$hover = $this->props[ $slug . '__hover' ];
				ET_Builder_Element::set_style(
					$function_name,
					array(
						'selector'    => $this->add_hover_to_order_class( $class ),
						'declaration' => et_builder_get_element_style_css( $hover, $type, $important ),
					)
				);
			}
		}
	}

	/**
	 * Force load global styles.
	 *
	 * @param array $assets_list Current global assets on the list.
	 *
	 * @return array
	 */
	public function dsm_load_required_divi_assets( $assets_list, $assets_args, $instance ) {
		$assets_prefix     = et_get_dynamic_assets_path();
		$all_shortcodes    = $instance->get_saved_page_shortcodes();
		$this->_cpt_suffix = et_builder_should_wrap_styles() && ! et_is_builder_plugin_active() ? '_cpt' : '';

		if ( ! isset( $assets_list['et_jquery_magnific_popup'] ) ) {
			$assets_list['et_jquery_magnific_popup'] = array(
				'css' => "{$assets_prefix}/css/magnific_popup.css",
			);
		}

		if ( ! isset( $assets_list['et_pb_overlay'] ) ) {
			$assets_list['et_pb_overlay'] = array(
				'css' => "{$assets_prefix}/css/overlay{$this->_cpt_suffix}.css",
			);
		}

		// ImageAccordion.
		if ( ! isset( $assets_list['dsm_image_accordion'] ) ) {
			$assets_list['dsm_image_accordion'] = array(
				'css' => plugin_dir_url( __DIR__ ) . 'ImageAccordion/style.css',
			);
		}
		if ( ! isset( $assets_list['et_icons_all'] ) ) {
			$assets_list['et_icons_all'] = array(
				'css' => "{$assets_prefix}/css/icons_all.css",
			);
		}

		if ( ! isset( $assets_list['et_icons_fa'] ) ) {
			$assets_list['et_icons_fa'] = array(
				'css' => "{$assets_prefix}/css/icons_fa_all.css",
			);
		}

		return $assets_list;
	}
}

new DSM_ImageAccordion();
