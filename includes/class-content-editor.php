<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;}

class Haet_ContentEditor {
	public function __construct() {
		add_filter( 'block_editor_settings_all', [ $this, 'block_editor_settings' ] );
		add_filter( 'block_editor_settings_all', [ $this, 'remove_theme_json' ], 20 );
		// add_action(
		// 	'enqueue_block_editor_assets',
		// 	function () {
		// 		error_log('##enqueue_block_editor_assets');
		// 		// // Enqueue block editor styles
		// 		// wp_enqueue_style(
		// 		// 	'cq-theme-css',
		// 		// 	get_stylesheet_directory_uri() . '/assets/css/theme.css',
		// 		// 	[],
		// 		// 	filemtime(get_stylesheet_directory() . '/assets/css/theme.css')
		// 		// );
		// 	}
		// );
	}

	public function block_editor_settings( array $settings ) {
		$settings['availableLegacyWidgets'] = (object) [];
		$settings['hasPermissionsToManageWidgets'] = false;

		// Start with no patterns
		$settings['__experimentalBlockPatterns'] = [];

		return $settings;
	}

	// Gutenberg 10.3.2 adds detection for theme.json. If this doesn't exist in the theme then it loads 'classic.css', which overrides a bunch of P2 styles
	// Until we have proper theme.json support just remove this dependency
	public function remove_theme_json( $settings ) {
		$exclude = [
			'wp-edit-blocks' => [ 'wp-editor-classic-layout-styles' ],
			'wp-reset-editor-styles' => [ 'forms', 'common' ],
		];
		$styles = wp_styles();

		foreach ( $exclude as $handle => $deps ) {
			// Find the handle
			$style = $styles->query( $handle, 'registered' );

			if ( $style ) {
				// Remove the dependencies without breaking the parent style itself
				$style->deps = array_filter(
					$style->deps,
					function( $item ) use ( $deps ) {
						return ! in_array( $item, $deps );
					}
				);
			}
		}

		return $settings;
	}

	/**
	 * Get a list of allowed blocks by looking at the allowed comment tags
	 *
	 * @return string[]
	 */
	public function get_allowed_blocks() {
		global $allowedtags;

		$allowed = [ 'core/heading', 'core/paragraph', 'core/list', 'core/code', 'core/table', 'core/group', 'core/columns' ];
		$convert = [
			'blockquote' => 'core/quote',
			'h1' => 'core/heading',
			'h2' => 'core/heading',
			'h3' => 'core/heading',
			'img' => 'core/image',
			'ul' => 'core/list',
			'ol' => 'core/list',
			'pre' => 'core/code',
			'table' => 'core/table',
		];

		foreach ( array_keys( $allowedtags ) as $tag ) {
			if ( isset( $convert[ $tag ] ) ) {
				$allowed[] = $convert[ $tag ];
			}
		}

		return array_unique( $allowed );
	}

	/**
	 * Load Gutenberg
	 *
	 * Based on wp-admin/edit-form-blocks.php
	 *
	 * @return void
	 */
	public function load() {
		//$this->load_extra_blocks();

		// Gutenberg scripts
		wp_enqueue_script( 'wp-block-library' );
		wp_enqueue_script( 'wp-format-library' );
		wp_enqueue_script( 'wp-editor' );

		// Gutenberg styles
		wp_enqueue_style( 'wp-edit-post' );
		wp_enqueue_style( 'wp-format-library' );

		// Keep Jetpack out of things
		add_filter(
			'jetpack_blocks_variation',
			function() {
				return 'no-post-editor';
			}
		);

		wp_tinymce_inline_scripts();
		wp_enqueue_editor();

		do_action( 'enqueue_block_editor_assets' );

		add_action( 'wp_print_footer_scripts', array( '_WP_Editors', 'print_default_editor_scripts' ), 45 );

		$this->setup_rest_api();
	}

	/**
	 * Set up Gutenberg editor settings
	 *
	 * @return Array
	 */
	public function get_editor_settings() {
		$color_palette = current( (array) get_theme_support( 'editor-color-palette' ) );

		$max_upload_size = wp_max_upload_size();
		if ( ! $max_upload_size ) {
			$max_upload_size = 0;
		}

		// at least these styles get applied to the mouse-over preview when we insert new blocks
		$styles = array(
			array(
				'css'            => 'body { background: blue; font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif } h2{color:red; font-family:Arial;}',
				'__unstableType' => 'core',
			),
		);


		$image_size_names = apply_filters(
			'image_size_names_choose',
			array(
				'thumbnail' => __( 'Thumbnail' ),
				'medium'    => __( 'Medium' ),
				'large'     => __( 'Large' ),
				'full'      => __( 'Full Size' ),
			)
		);

		$available_image_sizes = array();
		foreach ( $image_size_names as $image_size_slug => $image_size_name ) {
			$available_image_sizes[] = array(
				'slug' => $image_size_slug,
				'name' => $image_size_name,
			);
		}

		// https://developer.wordpress.org/reference/hooks/block_editor_settings_all/
		$editor_settings = array(
			'alignWide'              => true,
			'disableCustomColors'    => false,
			'disableCustomGradients' => true,
			'disableCustomFontSizes' => false,
			'disablePostFormats'     => true,
			'enableCustomSpacing'	 => true,
			'titlePlaceholder'       => __( 'Add title' ),
			'bodyPlaceholder'        => __('start writing your email content','wp-html-mail'),
			'isRTL'                  => is_rtl(),
			'autosaveInterval'       => AUTOSAVE_INTERVAL,
			'maxUploadFileSize'      => $max_upload_size,
			'allowedMimeTypes'       => [],
			'styles'                 => $styles,
			'imageSizes'             => $available_image_sizes,
			'richEditingEnabled'     => true,
			'codeEditingEnabled'     => true,
			'allowedBlockTypes'      => $this->get_allowed_blocks(),
			'__experimentalCanUserUseUnfilteredHTML' => false,
			'__experimentalFeatures' => [
				'appearanceTools'		=> true,
				'border'				=> [
					'color'					=> true,	
					'radius'				=> true,
					'style'					=> true,
					'width'					=> true
				],
				'color'					=> [
					'background'			=> true,
					'link'					=> true,
					'text'					=> true
				],	
				'spacing'				=> [
					'blockGap'				=> 22,
					'margin'				=> true
				],
				'typography'			=> [
      				'dropCap'				=> false,
      				'fontSizes'				=> [
        				'default'				=> [
							[
            					'name'				=> 'Small',
            					'slug'				=> 'small',
            					'size'				=> '12px'
							],
							[
            					'name'				=> 'Medium',
            					'slug'				=> 'medium',
            					'size'				=> '15px'
							],
						]
					],
					'fontStyle'				=> true,
					'fontWeight'			=> true,
					'textDecoration'		=> true,
					'textTransform'			=> true,
					//'fontFamilies'

				],
			],
			'fontSizes'				=> [
				'default'				=> [
					[
						'name'				=> 'Small',
						'slug'				=> 'small',
						'size'				=> '12px'
					],
					[
						'name'				=> 'Medium',
						'slug'				=> 'medium',
						'size'				=> '15px'
					],
				]
			],
		);

		if ( false !== $color_palette ) {
			$editor_settings['colors'] = $color_palette;
		}

		return $editor_settings;
	}


	/**
	 * Set up the Gutenberg REST API and preloaded data
	 *
	 * We set the 'post' to be whatever the latest P2 post is, but we change the post ID to 0
	 *
	 * @return void
	 */
	public function setup_rest_api() {
		global $post;

		$post_type = 'post';

		// Preload common data.
		$preload_paths = array(
			'/',
			'/wp/v2/types?context=edit',
			'/wp/v2/taxonomies?per_page=-1&context=edit',
			'/wp/v2/themes?status=active',
			sprintf( '/wp/v2/types/%s?context=edit', $post_type ),
			sprintf( '/wp/v2/users/me?post_type=%s&context=edit', $post_type ),
			array( '/wp/v2/media', 'OPTIONS' ),
			array( '/wp/v2/blocks', 'OPTIONS' ),
		);

		/**
		 * @psalm-suppress TooManyArguments
		 */
		$preload_paths = apply_filters( 'block_editor_preload_paths', $preload_paths, $post );
		$preload_data = array_reduce( $preload_paths, 'rest_preload_api_request', array() );

		$encoded = wp_json_encode( $preload_data );
		if ( $encoded !== false ) {
			wp_add_inline_script(
				'wp-editor',
				sprintf( 'wp.apiFetch.use( wp.apiFetch.createPreloadingMiddleware( %s ) );', $encoded ),
				'after'
			);
		}
	}



	public function output_template_styles_for_contenteditor( WP_REST_Request $request ) {
		$param = $request->get_param( 'post_id' );
		if( $post_id )
			$post_id = intval( $post_id );
		$template = Haet_Mail()->get_template( Haet_Mail()->get_theme_options( 'default' ) );
		$css_desktop = '';
		$css_mobile = '';
		if( $post_id ){
			$css_desktop = get_post_meta( $post_id, 'mailbuilder_css_desktop', true );
			if ( $css_desktop ) {
				$css_desktop = $this->validate_css( $css_desktop, true );
			}

			$css_mobile = get_post_meta( $post_id, 'mailbuilder_css_mobile', true );
			if ( $css_mobile ) {
				$css_mobile = $this->validate_css( $css_mobile, true );
			}
		}

		preg_match_all( '/<style>(.*)<\/style>/smU', $template, $output_array );
		if ( count( $output_array ) > 1 && count( $output_array[1] ) > 0 ) {
			$css = $output_array[1][0];

			$css = str_replace( '/**** ADD CSS HERE ****/', $css_desktop . '/**** ADD CSS HERE ****/', $css );
			$css = str_replace( '/**** ADD MOBILE CSS HERE ****/', $css_mobile . '/**** ADD MOBILE CSS HERE ****/', $css );

			// replace template classes by block editor classes.
			$css = str_replace('.container', '.editor-styles-wrapper', $css);
			$css = str_replace('.content', '.is-root-container.block-editor-block-list__layout', $css);
			$css = str_replace('.body-text', '.is-root-container.block-editor-block-list__layout', $css);

			include_once HAET_MAIL_PATH . '/vendor/autoload.php';
			$editor_selector = 'html :where(.editor-styles-wrapper)';
			$css_parser    = new Sabberworm\CSS\Parser( $css );
			$css_object    = $css_parser->parse();
			foreach ( $css_object->getAllDeclarationBlocks() as $css_block ) {
				foreach ( $css_block->getSelectors() as $css_selector ) {
					// Loop over all selector parts (the comma-separated strings in a selector) and prepend the id.
					if ( $css_selector->getSelector() === 'body' ) {
						$css_selector->setSelector( $editor_selector );
					} else {
						$css_selector->setSelector( $editor_selector . ' ' . $css_selector->getSelector() );
					}
				}
			}
			// Note that esc_html() cannot be used because `div &gt; span` is not interpreted properly.
			// see https://developer.wordpress.org/reference/functions/wp_custom_css_cb/ how CSS is escaped in core.
			// phpcs:disable
			return new \WP_REST_Response( strip_tags( $css_object->render() ) );
			// phpcs:enable
		}
	}
}
