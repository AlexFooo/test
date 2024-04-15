<?php

/**
 * Sydney functions and definitions
 *
 * @package Sydney
 */

if (!function_exists('sydney_setup')) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function sydney_setup()
	{

		/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on Sydney, use a find and replace
	 * to change 'sydney' to the name of your theme in all the template files
	 */
		load_theme_textdomain('sydney', get_template_directory() . '/languages');

		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		// Content width
		global $content_width;
		if (!isset($content_width)) {
			$content_width = 1170; /* pixels */
		}

		/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
		add_theme_support('title-tag');

		/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
		add_theme_support('post-thumbnails');
		add_image_size('sydney-large-thumb', 1000);
		add_image_size('sydney-medium-thumb', 550, 400, true);
		add_image_size('sydney-small-thumb', 230);
		add_image_size('sydney-service-thumb', 350);
		add_image_size('sydney-mas-thumb', 480);

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus(array(
			'primary' 	=> __('Primary Menu', 'sydney'),
			'mobile' 	=> __('Mobile menu (optional)', 'sydney'),
		));

		/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
		add_theme_support('html5', array(
			'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
		));

		/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
		add_theme_support('post-formats', array(
			'aside', 'image', 'video', 'quote', 'link',
		));

		// Set up the WordPress core custom background feature.
		add_theme_support('custom-background', apply_filters('sydney_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		)));

		//Gutenberg align-wide support
		add_theme_support('align-wide');

		//Enable template editing. Can't use theme.json right now because it disables wide/full alignments
		add_theme_support('block-templates');

		//Forked Owl Carousel flag
		$forked_owl = get_theme_mod('forked_owl_carousel', false);
		if (!$forked_owl) {
			set_theme_mod('forked_owl_carousel', true);
		}

		//Set the compare icon for YTIH button
		update_option('yith_woocompare_button_text', sydney_get_svg_icon('icon-compare', false));
	}
endif; // sydney_setup
add_action('after_setup_theme', 'sydney_setup');

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function sydney_widgets_init()
{
	register_sidebar(array(
		'name'          => __('Sidebar', 'sydney'),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	));

	//Footer widget areas
	for ($i = 1; $i <= 4; $i++) {
		register_sidebar(array(
			'name'          => __('Footer ', 'sydney') . $i,
			'id'            => 'footer-' . $i,
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		));
	}

	//Register the front page widgets
	if (defined('SITEORIGIN_PANELS_VERSION')) {
		register_widget('Sydney_List');
		register_widget('Sydney_Services_Type_A');
		register_widget('Sydney_Services_Type_B');
		register_widget('Sydney_Facts');
		register_widget('Sydney_Clients');
		register_widget('Sydney_Testimonials');
		register_widget('Sydney_Skills');
		register_widget('Sydney_Action');
		register_widget('Sydney_Video_Widget');
		register_widget('Sydney_Social_Profile');
		register_widget('Sydney_Employees');
		register_widget('Sydney_Latest_News');
		register_widget('Sydney_Portfolio');
	}
	register_widget('Sydney_Contact_Info');
}
add_action('widgets_init', 'sydney_widgets_init');

/**
 * Load the front page widgets.
 */
if (defined('SITEORIGIN_PANELS_VERSION')) {
	require get_template_directory() . "/widgets/fp-list.php";
	require get_template_directory() . "/widgets/fp-services-type-a.php";
	require get_template_directory() . "/widgets/fp-services-type-b.php";
	require get_template_directory() . "/widgets/fp-facts.php";
	require get_template_directory() . "/widgets/fp-clients.php";
	require get_template_directory() . "/widgets/fp-testimonials.php";
	require get_template_directory() . "/widgets/fp-skills.php";
	require get_template_directory() . "/widgets/fp-call-to-action.php";
	require get_template_directory() . "/widgets/video-widget.php";
	require get_template_directory() . "/widgets/fp-social.php";
	require get_template_directory() . "/widgets/fp-employees.php";
	require get_template_directory() . "/widgets/fp-latest-news.php";
	require get_template_directory() . "/widgets/fp-portfolio.php";

	/**
	 * Page builder support
	 */
	require get_template_directory() . '/inc/so-page-builder.php';
}
require get_template_directory() . "/widgets/contact-info.php";

/**
 * Enqueue scripts and styles.
 */
function sydney_admin_scripts()
{
	wp_enqueue_script('sydney-admin-functions', get_template_directory_uri() . '/js/admin-functions.js', array('jquery'), '20211006', true);
	wp_localize_script('sydney-admin-functions', 'sydneyadm', array(
		'fontawesomeUpdate' => array(
			'confirmMessage' => __('Are you sure? Keep in mind this is a global change and you will need update your icons class names in all theme widgets and post types that use Font Awesome 4 icons.', 'sydney'),
			'errorMessage' => __('It was not possible complete the request, please reload the page and try again.', 'sydney')
		),
		'headerUpdate' => array(
			'confirmMessage' => __('Are you sure you want to upgrade your header?', 'sydney'),
			'errorMessage' => __('It was not possible complete the request, please reload the page and try again.', 'sydney')
		),
		'headerUpdateDimiss' => array(
			'confirmMessage' => __('Are you sure you want to dismiss this notice?', 'sydney'),
			'errorMessage' => __('It was not possible complete the request, please reload the page and try again.', 'sydney')
		),
	));
}
add_action('admin_enqueue_scripts', 'sydney_admin_scripts');

/**
 * Use the modern header in new installs
 */
function sydney_set_modern_header_flag()
{
	update_option('sydney-update-header', true);

	//Disable old content position code
	update_option('sydney_woo_content_pos_disable', true);

	//Disable single product sidebar
	set_theme_mod('swc_sidebar_products', true);

	//Disable shop archive sidebar
	set_theme_mod('shop_archive_sidebar', 'no-sidebar');
}
add_action('after_switch_theme', 'sydney_set_modern_header_flag');

/**
 * Elementor editor scripts
 */
function sydney_elementor_editor_scripts()
{
	wp_enqueue_script('sydney-elementor-editor', get_template_directory_uri() . '/js/elementor.js', array('jquery'), '20200504', true);
}
add_action('elementor/frontend/after_register_scripts', 'sydney_elementor_editor_scripts');

/**
 * Enqueue scripts and styles.
 */
function sydney_scripts()
{

	$is_amp = sydney_is_amp();

	if (null !== sydney_google_fonts_url()) {
		wp_enqueue_style('sydney-google-fonts', esc_url(sydney_google_fonts_url()), array(), null);
	}

	wp_enqueue_style('sydney-ie9', get_template_directory_uri() . '/css/ie9.css', array('sydney-style'));
	wp_style_add_data('sydney-ie9', 'conditional', 'lte IE 9');

	if (!$is_amp) {
		wp_enqueue_script('sydney-functions', get_template_directory_uri() . '/js/functions.min.js', array(), '20240307', true);

		//Enqueue hero slider script only if the slider is in use
		$slider_home = get_theme_mod('front_header_type', 'nothing');
		$slider_site = get_theme_mod('site_header_type');
		if (($slider_home == 'slider' && is_front_page()) || ($slider_site == 'slider' && !is_front_page())) {
			wp_enqueue_script('sydney-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '', true);
			wp_enqueue_script('sydney-hero-slider', get_template_directory_uri() . '/js/hero-slider.js', array('jquery'), '', true);
			wp_enqueue_style('sydney-hero-slider', get_template_directory_uri() . '/css/components/hero-slider.min.css', array(), '20220824');
		}
	}

	if (class_exists('Elementor\Plugin')) {
		wp_enqueue_script('sydney-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '', true);

		wp_enqueue_style('sydney-elementor', get_template_directory_uri() . '/css/components/elementor.min.css', array(), '20220824');
	}

	if (defined('SITEORIGIN_PANELS_VERSION')) {

		wp_enqueue_style('sydney-siteorigin', get_template_directory_uri() . '/css/components/siteorigin.min.css', array(), '20220824');

		wp_enqueue_script('sydney-scripts', get_template_directory_uri() . '/js/scripts.js', array('jquery'), '', true);

		wp_enqueue_script('sydney-so-legacy-scripts', get_template_directory_uri() . '/js/so-legacy.js', array('jquery'), '', true);

		wp_enqueue_script('sydney-so-legacy-main', get_template_directory_uri() . '/js/so-legacy-main.min.js', array('jquery'), '', true);

		if (get_option('sydney-fontawesome-v5')) {
			wp_enqueue_style('sydney-font-awesome-v5', get_template_directory_uri() . '/fonts/font-awesome-v5/all.min.css');
		} else {
			wp_enqueue_style('sydney-font-awesome', get_template_directory_uri() . '/fonts/font-awesome.min.css');
		}
	}

	if (is_singular() && (comments_open() || '0' != get_comments_number())) {
		wp_enqueue_style('sydney-comments', get_template_directory_uri() . '/css/components/comments.min.css', array(), '20220824');
	}

	if (is_singular() && comments_open() && get_option('thread_comments')) {
		wp_enqueue_script('comment-reply');
	}

	wp_enqueue_style('sydney-style-min', get_template_directory_uri() . '/css/styles.min.css', '', '20240307');

	wp_enqueue_style('sydney-style', get_stylesheet_uri(), '', '20230821');
}
add_action('wp_enqueue_scripts', 'sydney_scripts');

/**
 * Disable Elementor globals on theme activation
 */
function sydney_disable_elementor_globals()
{
	update_option('elementor_disable_color_schemes', 'yes');
	update_option('elementor_disable_typography_schemes', 'yes');
	update_option('elementor_onboarded', true);
}
add_action('after_switch_theme', 'sydney_disable_elementor_globals');

/**
 * Enqueue Bootstrap
 */
function sydney_enqueue_bootstrap()
{
	wp_enqueue_style('sydney-bootstrap', get_template_directory_uri() . '/css/bootstrap/bootstrap.min.css', array(), true);
}
add_action('wp_enqueue_scripts', 'sydney_enqueue_bootstrap', 9);

/**
 * Elementor editor scripts
 */

/**
 * Change the excerpt length
 */
function sydney_excerpt_length($length)
{

	$excerpt = get_theme_mod('exc_lenght', 22);
	return $excerpt;
}
add_filter('excerpt_length', 'sydney_excerpt_length', 999);

/**
 * Blog layout
 */
function sydney_blog_layout()
{
	$layout = get_theme_mod('blog_layout', 'layout2');
	return $layout;
}

/**
 * Menu fallback
 */
function sydney_menu_fallback()
{
	if (current_user_can('edit_theme_options')) {
		echo '<a class="menu-fallback" href="' . admin_url('nav-menus.php') . '">' . __('Create your menu here', 'sydney') . '</a>';
	}
}

/**
 * Header image overlay
 */
function sydney_header_overlay()
{
	$overlay = get_theme_mod('hide_overlay', 0);
	if (!$overlay) {
		echo '<div class="overlay"></div>';
	}
}

/**
 * Header video
 */
function sydney_header_video()
{

	if (!function_exists('the_custom_header_markup')) {
		return;
	}

	$front_header_type 	= get_theme_mod('front_header_type');
	$site_header_type 	= get_theme_mod('site_header_type');

	if ((get_theme_mod('front_header_type') == 'core-video' && is_front_page() || get_theme_mod('site_header_type') == 'core-video' && !is_front_page())) {
		the_custom_header_markup();
	}
}

/**
 * Preloader
 * Hook into 'wp_body_open' to ensure compatibility with 
 * header/footer builder plugins
 */
function sydney_preloader()
{

	$preloader = get_theme_mod('enable_preloader', 1);

	if (sydney_is_amp() || !$preloader) {
		return;
	}

?>
	<div class="preloader">
		<div class="spinner">
			<div class="pre-bounce1"></div>
			<div class="pre-bounce2"></div>
		</div>
	</div>
<?php
}
add_action('wp_body_open', 'sydney_preloader');
add_action('elementor/theme/before_do_header', 'sydney_preloader'); // Elementor Pro Header Builder

/**
 * Header clone
 */
function sydney_header_clone()
{

	$front_header_type 	= get_theme_mod('front_header_type', 'nothing');
	$site_header_type 	= get_theme_mod('site_header_type');

	if (class_exists('Woocommerce')) {

		if (is_shop()) {
			$shop_thumb = get_the_post_thumbnail_url(get_option('woocommerce_shop_page_id'));

			if ($shop_thumb) {
				return;
			}
		} elseif (is_product_category()) {
			global $wp_query;
			$cat 				= $wp_query->get_queried_object();
			$thumbnail_id 		= get_term_meta($cat->term_id, 'thumbnail_id', true);
			$shop_archive_thumb	= wp_get_attachment_url($thumbnail_id);

			if ($shop_archive_thumb) {
				return;
			}
		}
	}

	if (($front_header_type == 'nothing' && is_front_page()) || ($site_header_type == 'nothing' && !is_front_page())) {
		echo '<div class="header-clone"></div>';
	}
}
add_action('sydney_before_header', 'sydney_header_clone');

/**
 * Get image alt
 */
function sydney_get_image_alt($image)
{
	global $wpdb;

	if (empty($image)) {
		return false;
	}

	$attachment  = $wpdb->get_col($wpdb->prepare("SELECT ID FROM {$wpdb->posts} WHERE guid=%s;", strtolower($image)));
	$id   = (!empty($attachment)) ? $attachment[0] : 0;

	$alt = get_post_meta($id, '_wp_attachment_image_alt', true);

	return $alt;
}

/**
 * Fix skip link focus in IE11.
 *
 * This does not enqueue the script because it is tiny and because it is only for IE11,
 * thus it does not warrant having an entire dedicated blocking script being loaded.
 *
 * from TwentyTwenty
 * 
 * @link https://git.io/vWdr2
 */
function sydney_skip_link_focus_fix()
{

	if (sydney_is_amp()) {
		return;
	}
?>
	<script>
		/(trident|msie)/i.test(navigator.userAgent) && document.getElementById && window.addEventListener && window.addEventListener("hashchange", function() {
			var t, e = location.hash.substring(1);
			/^[A-z0-9_-]+$/.test(e) && (t = document.getElementById(e)) && (/^(?:a|select|input|button|textarea)$/i.test(t.tagName) || (t.tabIndex = -1), t.focus())
		}, !1);
	</script>
	<?php
}
add_action('wp_print_footer_scripts', 'sydney_skip_link_focus_fix');

/**
 * Get SVG code for specific theme icon
 */
function sydney_get_svg_icon($icon, $echo = false)
{
	$svg_code = wp_kses( //From TwentTwenty. Keeps only allowed tags and attributes
		Sydney_SVG_Icons::get_svg_icon($icon),
		array(
			'svg'     => array(
				'class'       => true,
				'xmlns'       => true,
				'width'       => true,
				'height'      => true,
				'viewbox'     => true,
				'aria-hidden' => true,
				'role'        => true,
				'focusable'   => true,
				'fill'        => true,
			),
			'path'    => array(
				'fill'      => true,
				'fill-rule' => true,
				'd'         => true,
				'transform' => true,
				'stroke'	=> true,
				'stroke-width' => true,
				'stroke-linejoin' => true
			),
			'polygon' => array(
				'fill'      => true,
				'fill-rule' => true,
				'points'    => true,
				'transform' => true,
				'focusable' => true,
			),
			'rect'    => array(
				'x'      => true,
				'y'      => true,
				'width'  => true,
				'height' => true,
				'transform' => true
			),
		)
	);

	if ($echo != false) {
		echo $svg_code; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		return $svg_code;
	}
}

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Page metabox
 */
require get_template_directory() . '/inc/classes/class-sydney-page-metabox.php';

/**
 * Posts archive
 */
require get_template_directory() . '/inc/classes/class-sydney-posts-archive.php';

/**
 * Display conditions
 */
require get_template_directory() . '/inc/display-conditions.php';

/**
 * Header
 */
require get_template_directory() . '/inc/classes/class-sydney-header.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';

/**
 * Slider
 */
require get_template_directory() . '/inc/slider.php';

/**
 * Styles
 */
require get_template_directory() . '/inc/styles.php';

/**
 * Woocommerce basic integration
 */
require get_template_directory() . '/inc/woocommerce.php';

/**
 * WPML
 */
if (class_exists('SitePress')) {
	require get_template_directory() . '/inc/integrations/wpml/class-sydney-wpml.php';
}

/**
 * LifterLMS
 */
if (class_exists('LifterLMS')) {
	require get_template_directory() . '/inc/integrations/lifter/class-sydney-lifterlms.php';
}

/**
 * Learndash
 */
if (class_exists('SFWD_LMS')) {
	require get_template_directory() . '/inc/integrations/learndash/class-sydney-learndash.php';
}

/**
 * Learnpress
 */
if (class_exists('LearnPress')) {
	require get_template_directory() . '/inc/integrations/learnpress/class-sydney-learnpress.php';
}

/**
 * Max Mega Menu
 */
if (function_exists('max_mega_menu_is_enabled')) {
	require get_template_directory() . '/inc/integrations/class-sydney-maxmegamenu.php';
}

/**
 * AMP
 */
require get_template_directory() . '/inc/integrations/class-sydney-amp.php';

/**
 * Upsell
 */
require get_template_directory() . '/inc/customizer/upsell/class-customize.php';

/**
 * Gutenberg
 */
require get_template_directory() . '/inc/editor.php';

/**
 * Fonts
 */
require get_template_directory() . '/inc/fonts.php';

/**
 * SVG codes
 */
require get_template_directory() . '/inc/classes/class-sydney-svg-icons.php';

/**
 * Review notice
 */
require get_template_directory() . '/inc/notices/class-sydney-review.php';

/**
 * Schema
 */
require get_template_directory() . '/inc/schema.php';

/**
 * Theme update migration functions
 */
require get_template_directory() . '/inc/theme-update.php';

/**
 * Theme dashboard.
 */
require get_template_directory() . '/inc/dashboard/class-dashboard.php';

/**
 * Theme dashboard settings.
 */
require get_template_directory() . '/inc/dashboard/class-dashboard-settings.php';

/**
 * Performance
 */
require get_template_directory() . '/inc/performance/class-sydney-performance.php';

/**
 * Add global colors support for Elementor
 */
require get_template_directory() . '/inc/integrations/elementor/class-sydney-elementor-global-colors.php';
/**
 * Template library for Elementor
 */
function sydney_elementor_template_library()
{
	if (did_action('elementor/loaded')) {
		require get_template_directory() . '/inc/integrations/elementor/library/library-manager.php';
		require get_template_directory() . '/inc/integrations/elementor/library/library-source.php';
	}
}
add_action('init', 'sydney_elementor_template_library');

/**
 * Premium modules
 */
require get_template_directory() . '/inc/classes/class-sydney-modules.php';

/**
 * Block styles
 */
require get_template_directory() . '/inc/block-styles.php';

/*
 * Enable fontawesome 5 on first time theme activation
 * Check if the old theme is sydney to avoid enable the fa5 automatic and break icons
 * Since this hook also run on theme updates
 */
function sydney_enable_fontawesome_latest_version($old_theme_name)
{
	$old_theme_name = strtolower($old_theme_name);
	if (!get_option('sydney-fontawesome-v5') && strpos($old_theme_name, 'sydney') === FALSE) {
		update_option('sydney-fontawesome-v5', true);
	}
}
add_action('after_switch_theme', 'sydney_enable_fontawesome_latest_version');

/**
 * Autoload
 */
require_once get_parent_theme_file_path('vendor/autoload.php');

/**
 * Sydney Toolbox and fontawesome update notice
 */
if (defined('SITEORIGIN_PANELS_VERSION') && (isset($pagenow) && $pagenow == 'themes.php') && isset($_GET['page']) && $_GET['page'] == 'theme-dashboard') {
	function sydney_toolbox_fa_update_admin_notice()
	{
		$all_plugins    = get_plugins();
		$active_plugins = get_option('active_plugins');
		$theme_version  = wp_get_theme('sydney')->Version;

		// Check if Sydney Toolbox plugin is active
		if (!in_array('sydney-toolbox/sydney-toolbox.php', $active_plugins)) {
			return;
		}

		if (version_compare($all_plugins['sydney-toolbox/sydney-toolbox.php']['Version'], '1.16', '>=')) {
			if (!get_option('sydney-fontawesome-v5')) { ?>
				<div class="notice notice-success thd-theme-dashboard-notice-success is-dismissible">
					<p>
						<strong><?php esc_html_e('Sydney Font Awesome Update: ', 'sydney'); ?></strong> <?php esc_html_e('Your website is currently running the version 4. Click in the below button to update to version 5.', 'sydney'); ?>
						<br>
						<strong><?php esc_html_e('Important: ', 'sydney'); ?></strong> <?php esc_html_e('This is a global change. That means this change will affect all website icons and you will need update the icons class names in all theme widgets and post types that use Font Awesome 4 icons. For example: "fa-android" to "fab fa-android".', 'sydney'); ?>
					</p>
					<a href="#" class="button sydney-update-fontawesome" data-nonce="<?php echo esc_attr(wp_create_nonce('sydney-fa-updt-nonce')); ?>" style="margin-bottom: 9px;"><?php esc_html_e('Update to v5', 'sydney'); ?></a>
					<br>
				</div>
		<?php
			}
			return;
		} ?>

		<div class="notice notice-success thd-theme-dashboard-notice-success is-dismissible">
			<p>
				<?php echo wp_kses_post(sprintf(__('<strong>Optional:</strong> Now <strong>Sydney</strong> is compatible with Font Awesome 5. For it is needed the latest version of <strong>Sydney Toolbox</strong> plugin. You can update the plugin <a href="%s">here</a>.', 'sydney'), admin_url('plugins.php'))); ?><br>
				<strong><?php esc_html_e('Important: ', 'sydney'); ?></strong> <?php esc_html_e('This is a global change. That means this change will affect all website icons and you will need update the icons class names in all theme widgets and post types that use Font Awesome 4 icons. For example: "fa-android" to "fab fa-android".', 'sydney'); ?>
			</p>
		</div>
	<?php
	}
	add_action('admin_notices', 'sydney_toolbox_fa_update_admin_notice');
}



$movies = array(
	array(
		'title' => 'Форрест Гамп',
		'content' => 'Форрест Гамп - невероятная история человека с низким IQ, который становится свидетелем и участником ключевых событий 20 века.',
		'price' => '200',
		'release_date' => '1994-07-06',
		'genres' => array('драма', 'романтика'),
		'actors' => array('Том Хэнкс', 'Робин Райт'),
		'country' => 'США',
		'thumbnail' => 'https://avatars.mds.yandex.net/get-kinopoisk-image/1599028/3560b757-9b95-45ec-af8c-623972370f9d/220x330',
	),
	array(
		'title' => 'Зеленая миля',
		'content' => 'История охранника тюрьмы, который обнаруживает, что один из заключенных обладает необычными способностями.',
		'price' => '180',
		'release_date' => '1999-12-10',
		'genres' => array('драма', 'фэнтези'),
		'actors' => array('Том Хэнкс', 'Майкл Кларк Дункан'),
		'country' => 'США',
		'thumbnail' => 'https://upload.wikimedia.org/wikipedia/ru/b/b0/Green_mile_film.jpg'
	),
	array(
		'title' => 'Побег из Шоушенка',
		'content' => 'Драматическая история о невинно осужденном банкире Энди Дюфрейне, который строит дружбу с заключенным Реддом.',
		'price' => '220',
		'release_date' => '1994-09-23',
		'genres' => array('драма'),
		'actors' => array('Тим Роббинс', 'Морган Фриман'),
		'country' => 'США',
		'thumbnail' => 'https://upload.wikimedia.org/wikipedia/ru/d/de/Movie_poster_the_shawshank_redemption.jpg'
	),
	array(
		'title' => 'Звёздные войны: Эпизод 4 – Новая надежда',
		'content' => 'Эпическая космическая сага о борьбе между добром и злом в далекой галактике.',
		'price' => '150',
		'release_date' => '1977-05-25',
		'genres' => array('фантастика', 'боевик'),
		'actors' => array('Марк Хэмилл', 'Харрисон Форд', 'Кэрри Фишер'),
		'country' => 'США',
		'thumbnail' => 'https://avatars.mds.yandex.net/get-kinopoisk-image/1600647/9bdc6690-de82-4a8c-a114-aa3a353bc1da/220x330'
	),
	array(
		'title' => 'Назад в будущее',
		'content' => 'Молодой изобретатель Док Браун создает машину времени из автомобиля и отправляет своего друга Марти МакФлай в прошлое.',
		'price' => '190',
		'release_date' => '1985-07-03',
		'genres' => array('фантастика', 'комедия'),
		'actors' => array('Майкл Дж. Фокс', 'Кристофер Ллойд'),
		'country' => 'США',
		'thumbnail' => 'https://upload.wikimedia.org/wikipedia/ru/9/90/BTTF_DVD_rus.jpg'
	),
	array(
		'title' => 'Интерстеллар',
		'content' => 'Группа исследователей путешествует сквозь червоточины в космосе в поисках нового дома для человечества.',
		'price' => '240',
		'release_date' => '2014-11-07',
		'genres' => array('фантастика', 'драма'),
		'actors' => array('Мэттью Макконахи', 'Энн Хэтэуэй'),
		'country' => 'США',
		'thumbnail' => 'https://upload.wikimedia.org/wikipedia/ru/c/c3/Interstellar_2014.jpg'
	),
	array(
		'title' => 'Темный рыцарь',
		'content' => 'Бэтмен сражается с преступным гением Джокером, который хочет привести Готэм к хаосу.',
		'price' => '210',
		'release_date' => '2008-07-18',
		'genres' => array('боевик', 'драма'),
		'actors' => array('Кристиан Бэйл', 'Хит Леджер'),
		'country' => 'США',
		'thumbnail' => 'https://thumbs.dfs.ivi.ru/storage5/contents/3/b/0d5de29534357c386470875cabb776.jpg'
	),
	array(
		'title' => 'Пираты Карибского моря: Проклятие Черной жемчужины',
		'content' => 'Капитан Джек Воробей и команда отправляются на поиски пропавшей легендарной ацтекской монеты.',
		'price' => '170',
		'release_date' => '2003-07-09',
		'genres' => array('приключения', 'фэнтези'),
		'actors' => array('Джонни Депп', 'Орландо Блум', 'Кира Найтли'),
		'country' => 'США',
		'thumbnail' => 'https://avatars.mds.yandex.net/get-kinopoisk-image/1773646/d7e3dbd6-e4a9-4485-b751-d02f49825166/220x330'
	),
	array(
		'title' => 'Титаник',
		'content' => 'Романтическая история о пассажирах лайнера "Титаник", который столкнулся с айсбергом во время своего первого плавания.',
		'price' => '220',
		'release_date' => '1997-12-19',
		'genres' => array('драма', 'романтика'),
		'actors' => array('Леонардо ДиКаприо', 'Кейт Уинслет'),
		'country' => 'США',
		'thumbnail' => 'https://mogilevnews.by/sites/default/files/uploaded/titanik_1.jpg'
	),
	array(
		'title' => 'Гарри Поттер и философский камень',
		'content' => 'Юный волшебник Гарри Поттер учится в Хогвартсе и раскрывает тайну таинственного камня.',
		'price' => '160',
		'release_date' => '2001-11-16',
		'genres' => array('фэнтези', 'приключения'),
		'actors' => array('Дэниел Рэдклифф', 'Эмма Уотсон', 'Руперт Гринт'),
		'country' => 'Великобритания',
		'thumbnail' => 'https://avatars.mds.yandex.net/get-kinopoisk-image/1898899/27ed5c19-a045-49dd-8624-5f629c5d96e0/600x900'
	),
);

function create_movie_post_type()
{
	$labels = array(
		'name'               => 'Фильмы',
		'singular_name'      => 'Фильм',
		'add_new'            => 'Добавить новый',
		'add_new_item'       => 'Добавить новый фильм',
		'edit_item'          => 'Редактировать фильм',
		'new_item'           => 'Новый фильм',
		'view_item'          => 'Посмотреть фильм',
		'search_items'       => 'Найти фильм',
		'not_found'          => 'Фильмы не найдены',
		'not_found_in_trash' => 'В корзине фильмы не найдены',
		'parent_item_colon'  => '',
		'menu_name'          => 'Фильмы'
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => array('slug' => 'movie'),
		'capability_type'     => 'post',
		'has_archive'         => true,
		'hierarchical'        => false,
		'menu_position'       => null,
		'supports'            => array('title', 'editor', 'thumbnail', 'custom-fields')
	);

	register_post_type('movie', $args);
}
add_action('init', 'create_movie_post_type');


function add_movies_from_array($movies)
{
	foreach ($movies as $movie) {
		$existing_post = get_page_by_title($movie['title'], OBJECT, 'movie');

		if ($existing_post) {
			continue;
		}

		$post_id = wp_insert_post(array(
			'post_type' => 'movie',
			'post_title' => $movie['title'],
			'post_content' => $movie['content'],
			'post_status' => 'publish',
		));

		if ($post_id) {
			foreach ($movie as $key => $value) {
				if (in_array($key, array('title', 'content', 'actors', 'genres', 'release_date', 'country', 'price', 'thumbnail'))) {
					continue;
				}
				update_post_meta($post_id, $key, $value);
			}

			update_post_meta($post_id, 'actors', $movie['actors']);
			update_post_meta($post_id, 'genres', $movie['genres']);
			update_post_meta($post_id, 'thumbnail', $movie['thumbnail']);
		}
	}
}

add_movies_from_array($movies);

function create_movie_taxonomies()
{

	$labels = array(
		'name'              => 'Жанры',
		'singular_name'     => 'Жанр',
		'search_items'      => 'Искать Жанр',
		'all_items'         => 'Все Жанры',
		'parent_item'       => 'Родительский Жанр',
		'parent_item_colon' => 'Родительский Жанр:',
		'edit_item'         => 'Редактировать Жанр',
		'update_item'       => 'Обновить Жанр',
		'add_new_item'      => 'Добавить новый Жанр',
		'new_item_name'     => 'Новое имя Жанра',
		'menu_name'         => 'Жанры',
	);
	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array('slug' => 'genre'),
	);
	register_taxonomy('genre', 'movie', $args);

	$labels = array(
		'name'              => 'Страны',
		'singular_name'     => 'Страна',
		'search_items'      => 'Искать Страну',
		'all_items'         => 'Все Страны',
		'edit_item'         => 'Редактировать Страну',
		'update_item'       => 'Обновить Страну',
		'add_new_item'      => 'Добавить новую Страну',
		'new_item_name'     => 'Новое имя Страны',
		'menu_name'         => 'Страны',
	);
	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array('slug' => 'country'),
	);
	register_taxonomy('country', 'movie', $args);

	$labels = array(
		'name'              => 'Актеры',
		'singular_name'     => 'Актер',
		'search_items'      => 'Искать Актера',
		'all_items'         => 'Все Актеры',
		'edit_item'         => 'Редактировать Актера',
		'update_item'       => 'Обновить Актера',
		'add_new_item'      => 'Добавить нового Актера',
		'new_item_name'     => 'Новое имя Актера',
		'menu_name'         => 'Актеры',
	);
	$args = array(
		'hierarchical'      => false,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array('slug' => 'actor'),
	);
	register_taxonomy('actor', 'movie', $args);
}
add_action('init', 'create_movie_taxonomies');


function add_movie_taxonomies()
{
	global $movies;

	$genres = array();
	$countries = array();
	$actors = array();

	foreach ($movies as $movie) {
		foreach ($movie['genres'] as $genre) {
			$genres[] = $genre;
		}
		$countries[] = $movie['country'];
		foreach ($movie['actors'] as $actor) {
			$actors[] = $actor;
		}
	}

	foreach (array_unique($genres) as $genre) {
		if (!term_exists($genre, 'genre')) {
			wp_insert_term($genre, 'genre');
		}
	}

	foreach (array_unique($countries) as $country) {
		if (!term_exists($country, 'country')) {
			wp_insert_term($country, 'country');
		}
	}

	foreach (array_unique($actors) as $actor) {
		if (!term_exists($actor, 'actor')) {
			wp_insert_term($actor, 'actor');
		}
	}
}

add_action('init', 'add_movie_taxonomies', 20);


function link_movie_taxonomies()
{
	global $movies;

	foreach ($movies as $movie) {
		$post = get_page_by_title($movie['title'], OBJECT, 'movie');
		if ($post) {
			$post_id = $post->ID;
			wp_set_object_terms($post_id, $movie['genres'], 'genre', true);
			wp_set_object_terms($post_id, array($movie['country']), 'country', true);
			wp_set_object_terms($post_id, $movie['actors'], 'actor', true);
		}
	}
}

add_action('init', 'link_movie_taxonomies', 30);


function add_movie_meta_fields_from_array($movies)
{
	foreach ($movies as $movie) {
		$args = array(
			'post_type' => 'movie',
			'posts_per_page' => 1,
			'title' => $movie['title'],
		);
		$existing_movie = get_posts($args);

		if ($existing_movie) {

			$movie_id = $existing_movie[0]->ID;

			update_post_meta($movie_id, 'price', $movie['price']);
			update_post_meta($movie_id, 'release_date', $movie['release_date']);
		}
	}
}

add_action('init', function () use ($movies) {
	add_movie_meta_fields_from_array($movies);
});

function get_product_id_by_title($product_title)
{
	global $wpdb;
	$product_id = $wpdb->get_var($wpdb->prepare("
        SELECT post.ID
        FROM $wpdb->posts AS post
        WHERE post.post_type = 'product'
        AND post.post_title = %s
    ", $product_title));

	return $product_id;
}


add_action('wp_ajax_movie_sort', 'movie_sort_ajax_handler');
add_action('wp_ajax_nopriv_movie_sort', 'movie_sort_ajax_handler');

function movie_sort_ajax_handler()
{
	$args = array(
		'post_type' => 'movie',
		'posts_per_page' => -1,
	);

	$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'date';
	$sort_order = isset($_GET['sort_order']) ? $_GET['sort_order'] : 'DESC';

	switch ($sort_by) {
		case 'price':
			$args['meta_key'] = 'price';
			$args['orderby'] = 'meta_value_num';
			break;
		case 'release_date':
			$args['meta_key'] = 'release_date';
			$args['orderby'] = 'meta_value';
			break;
		default:
			$args['orderby'] = 'date';
	}
	$args['order'] = $sort_order;

	$movies_query = new WP_Query($args);

	$movies = array();

	if ($movies_query->have_posts()) {
		while ($movies_query->have_posts()) {
			$movies_query->the_post();

			$title = get_the_title();
			$content = get_the_content();
			$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
			$price = get_post_meta(get_the_ID(), 'price', true);
			$release_date = get_post_meta(get_the_ID(), 'release_date', true);
			$country_terms = wp_get_post_terms(get_the_ID(), 'country');
			$country = !empty($country_terms) ? $country_terms[0]->name : '';
			$actors = get_post_meta(get_the_ID(), 'actors', true);
			$product_id = get_product_id_by_title($title);

			$movies[] = array(
				'title' => $title,
				'content' => $content,
				'thumbnail' => $thumbnail,
				'price' => $price,
				'release_date' => $release_date,
				'country' => $country,
				'actors' => $actors,
				'product_id' => $product_id,
			);
		}
	}

	wp_send_json($movies);
}

function movie_list_shortcode()
{
	ob_start();
	?>
	<div class="sort-buttons">
		<a href="#" class="sort-button" data-sort-by="price" data-sort-order="DESC">Цена ▲</a>
		<a href="#" class="sort-button" data-sort-by="price" data-sort-order="ASC">Цена ▼</a>
		<a href="#" class="sort-button" data-sort-by="release_date" data-sort-order="DESC">Дата ▲</a>
		<a href="#" class="sort-button" data-sort-by="release_date" data-sort-order="ASC">Дата ▼</a>
	</div>
	<div class="movies-list" id="movie-results">
		<?php echo movie_list_items_shortcode(); ?>
	</div>

<?php
	return ob_get_clean();
}

add_shortcode('movie_list', 'movie_list_shortcode');

function movie_list_items_shortcode()
{
	$args = array(
		'post_type' => 'movie',
		'posts_per_page' => -1,
	);

	$movies_query = new WP_Query($args);

	if ($movies_query->have_posts()) {
		$output = '';

		while ($movies_query->have_posts()) {
			$movies_query->the_post();

			$title = get_the_title();
			$content = get_the_content();
			$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
			$price = get_post_meta(get_the_ID(), 'price', true);
			$release_date = get_post_meta(get_the_ID(), 'release_date', true);
			$country_terms = wp_get_post_terms(get_the_ID(), 'country');
			$country = !empty($country_terms) ? $country_terms[0]->name : '';
			$actors = get_post_meta(get_the_ID(), 'actors', true);
			$product_id = get_product_id_by_title($title);

			$output .= '<div class="movie">';
			$output .= '<div class="movie-left">';
			$output .= '<h2>' . $title . '</h2>';
			$output .= '<div>' . $content . '</div>';
			$output .= '<div>Стоимость: ' . $price . '</div>';
			$output .= '<div>Дата выхода: ' . $release_date . '</div>';
			$output .= '<div>Страна: ' . $country . '</div>';
			$output .= '<div>Актерский состав: ' . implode(', ', $actors) . '</div>';
			$output .= '<a href="?add-to-cart=' . $product_id . '">Заказать</a>';
			$output .= '</div>';
			$output .= '<div class="movie-right">';
			$output .= '<img src="' . $thumbnail . '" alt="' . $title . '" />';
			$output .= '</div>';
			$output .= '</div>';
		}

		return $output;
	} else {
		return 'Фильмы не найдены';
	}

	wp_reset_postdata();
}

add_shortcode('movie_list_items', 'movie_list_items_shortcode');


function enqueue_movie_list_ajax_script()
{
	wp_enqueue_script('movie-list-ajax', get_template_directory_uri() . '/js/movie-sort-ajax.js', array('jquery'), null, true);
	wp_localize_script('movie-list-ajax', 'movieListAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_movie_list_ajax_script');






function movie_filter_shortcode()
{
	ob_start();
?>
	<form id="movie-filter">
		<div class="price-filter">
			<label for="price-from">Стоимость от:</label>
			<input type="number" id="price-from" name="price_from">
			<label for="price-to">до:</label>
			<input type="number" id="price-to" name="price_to">
		</div>
		<div class="data-filter">
			<label for="date-from">Дата от:</label>
			<input type="date" id="date-from" name="date_from">
			<label for="date-to">до:</label>
			<input type="date" id="date-to" name="date_to">
		</div>
		<div class="genres-filter">
			<h4>Жанры:</h4>
			<?php

			$genres = get_terms(array(
				'taxonomy' => 'genre',
				'hide_empty' => false,
			));

			if (!empty($genres) && !is_wp_error($genres)) {
				foreach ($genres as $genre) {
					echo '<label><input type="checkbox" name="genres[]" value="' . esc_attr($genre->slug) . '">' . esc_html($genre->name) . '</label><br>';
				}
			}
			?>
		</div>
		<div class="countres-filter">
			<h4>Страны:</h4>
			<?php

			$countries = get_terms(array(
				'taxonomy' => 'country',
				'hide_empty' => false,
			));

			if (!empty($countries) && !is_wp_error($countries)) {
				foreach ($countries as $country) {
					echo '<label><input type="checkbox" name="countries[]" value="' . esc_attr($country->slug) . '">' . esc_html($country->name) . '</label><br>';
				}
			}
			?>
		</div>
		<div class="actors-filter">
			<h4>Актеры:</h4>
			<?php

			$actors = get_terms(array(
				'taxonomy' => 'actor',
				'hide_empty' => false,
			));

			if (!empty($actors) && !is_wp_error($actors)) {
				foreach ($actors as $actor) {
					echo '<label><input type="checkbox" name="actors[]" value="' . esc_attr($actor->slug) . '">' . esc_html($actor->name) . '</label><br>';
				}
			}
			?>
		</div>
		<input type="submit" value="Применить фильтр">
		<button type="button" id="reset-filter">Сбросить фильтр</button>
	</form>

<?php
	return ob_get_clean();
}
add_shortcode('movie_filter', 'movie_filter_shortcode');




function enqueue_movie_filter_ajax_script()
{
	wp_enqueue_script('movie-filter-ajax', get_template_directory_uri() . '/js/movie-filter-ajax.js', array('jquery'), null, true);
	wp_localize_script('movie-filter-ajax', 'movie_filter_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'enqueue_movie_filter_ajax_script');




add_action('wp_ajax_filter_movies', 'filter_movies');
add_action('wp_ajax_nopriv_filter_movies', 'filter_movies');




function filter_movies()
{
	$args = array(
		'post_type' => 'movie',
		'posts_per_page' => -1,
	);

	if (isset($_POST['data'])) {
		parse_str($_POST['data'], $filters);

		if (!empty($filters['price_from'])) {
			$args['meta_query'][] = array(
				'key' => 'price',
				'value' => $filters['price_from'],
				'type' => 'NUMERIC',
				'compare' => '>=',
			);
		}

		if (!empty($filters['price_to'])) {
			$args['meta_query'][] = array(
				'key' => 'price',
				'value' => $filters['price_to'],
				'type' => 'NUMERIC',
				'compare' => '<=',
			);
		}

		if (!empty($filters['date_from'])) {
			$args['meta_query'][] = array(
				'key' => 'release_date',
				'value' => $filters['date_from'],
				'type' => 'DATE',
				'compare' => '>=',
			);
		}

		if (!empty($filters['date_to'])) {
			$args['meta_query'][] = array(
				'key' => 'release_date',
				'value' => $filters['date_to'],
				'type' => 'DATE',
				'compare' => '<=',
			);
		}

		if (!empty($filters['genres'])) {
			$args['tax_query'][] = array(
				'taxonomy' => 'genre',
				'field' => 'slug',
				'terms' => $filters['genres'],
			);
		}

		if (!empty($filters['countries'])) {
			$args['tax_query'][] = array(
				'taxonomy' => 'country',
				'field' => 'slug',
				'terms' => $filters['countries'],
			);
		}

		if (!empty($filters['actors'])) {
			$args['tax_query'][] = array(
				'taxonomy' => 'actor',
				'field' => 'slug',
				'terms' => $filters['actors'],
			);
		}

		if (!empty($filters['sort_by'])) {
			$sort_by = $filters['sort_by'];
			$sort_order = isset($filters['sort_order']) ? $filters['sort_order'] : 'ASC';

			switch ($sort_by) {
				case 'price':
					$args['meta_key'] = 'price';
					$args['orderby'] = 'meta_value_num';
					break;
				case 'release_date':
					$args['meta_key'] = 'release_date';
					$args['orderby'] = 'meta_value';
					break;
				default:
					$args['orderby'] = 'date';
			}

			$args['order'] = $sort_order;
		}
	}

	$movies_query = new WP_Query($args);

	ob_start();
	$output = '';

	if ($movies_query->have_posts()) {
		$output .= '<div class="movies-list">';
		while ($movies_query->have_posts()) {
			$movies_query->the_post();
			$title = get_the_title();
			$content = get_the_content();
			$thumbnail = get_post_meta(get_the_ID(), 'thumbnail', true);
			$price = get_post_meta(get_the_ID(), 'price', true);
			$release_date = get_post_meta(get_the_ID(), 'release_date', true);
			$country_terms = wp_get_post_terms(get_the_ID(), 'country');
			$country = !empty($country_terms) ? $country_terms[0]->name : '';
			$actors = get_post_meta(get_the_ID(), 'actors', true);
			$product_id = get_product_id_by_title($title);

			$output .= '<div class="movie">';
			$output .= '<div class="movie-left">';
			$output .= '<h2>' . $title . '</h2>';
			$output .= '<div>' . $content . '</div>';
			$output .= '<div>Стоимость: ' . $price . '</div>';
			$output .= '<div>Дата выхода: ' . $release_date . '</div>';
			$output .= '<div>Страна: ' . $country . '</div>';
			$output .= '<div>Актерский состав: ' . implode(', ', $actors) . '</div>';
			$output .= '<a href="?add-to-cart=' . $product_id . '">Заказать</a>';
			$output .= '</div>';
			$output .= '<img src="' . $thumbnail . '" alt="' . $title . '" />';
			$output .= '</div>';
		}
		$output .= '</div>';
		wp_reset_postdata();
	} else {
		$output = 'Фильмы не найдены.';
	}

	echo $output;
	wp_die();
}






function create_product_on_movie_publish($post_id, $post, $update)
{
	if ($post->post_type == 'movie' && $update === true) {
		$existing_product = get_page_by_title($post->post_title, OBJECT, 'product');

		if ($existing_product) {
			update_product_from_movie($existing_product->ID, $post_id);
			return;
		}

		$price = get_post_meta($post_id, 'price', true);
		$description = $post->post_content;
		$release_date = get_post_meta($post_id, 'release_date', true);
		$country_terms = wp_get_post_terms($post_id, 'country');
		$country = !empty($country_terms) ? $country_terms[0]->name : '';


		$product_id = wp_insert_post(array(
			'post_title' => $post->post_title,
			'post_type' => 'product',
			'post_status' => 'publish',
			'meta_input' => array(
				'_price' => $price,
				'_regular_price' => $price,
				'_description' => $description,
				'_release_date' => $release_date,
				'_country' => $country,
			)
		));


		update_post_meta($post_id, 'woocommerce_product_id', $product_id);
	}
}

function update_product_from_movie($product_id, $post_id)
{
	$price = get_post_meta($post_id, 'price', true);
	$description = get_post_field('post_content', $post_id);
	$release_date = get_post_meta($post_id, 'release_date', true);
	$country_terms = wp_get_post_terms($post_id, 'country');
	$country = !empty($country_terms) ? $country_terms[0]->name : '';

	wp_update_post(array(
		'ID' => $product_id,
		'post_title' => get_the_title($post_id),
		'meta_input' => array(
			'_price' => $price,
			'_regular_price' => $price,
			'_description' => $description,
			'_release_date' => $release_date,
			'_country' => $country,
		)
	));
}

add_action('save_post', 'create_product_on_movie_publish', 10, 3);






function redirect_to_home_after_add_to_cart($url)
{
	return home_url();
}
add_filter('woocommerce_add_to_cart_redirect', 'redirect_to_home_after_add_to_cart');



function enqueue_custom_styles()
{

	wp_enqueue_style('custom-styles', get_stylesheet_directory_uri() . '/css/test.css');
}


add_action('wp_enqueue_scripts', 'enqueue_custom_styles');
