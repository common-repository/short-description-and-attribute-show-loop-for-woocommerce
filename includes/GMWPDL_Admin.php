<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * This class is loaded on the back-end since its main job is 
 * to display the Admin UI components for the plugin.
 */
class GMWPDL_Admin {
    
    /**
     * Constructor to add hooks for initializing admin settings, menu, scripts, etc.
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'GMWPDL_admin_init' ) );
        add_action( 'admin_menu', array( $this, 'GMWPDL_admin_menu' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'GMWPDL_admin_script' ) );
        add_action( 'init', array( $this, 'GMWPDL_init' ) );
        
        // Early return if not in admin area
        if ( is_admin() ) {
            return;
        }
    }

    /**
     * Enqueue admin-specific styles and scripts.
     *
     * @param string $hook The current admin page hook suffix.
     */
    public function GMWPDL_admin_script($hook) {
        if ($hook == 'toplevel_page_GMWPDL') {
            wp_enqueue_style( 'gmwpdl_admin_css', GMWPDL_PLUGIN_URL . 'assents/css/admin-style.css' );
            wp_enqueue_style( 'gmwpdl_select2_css', GMWPDL_PLUGIN_URL . 'js/select2/select2.css' );
            wp_enqueue_script( 'gmwpdl_select2_js', GMWPDL_PLUGIN_URL . 'js/select2/select2.js' );
            wp_enqueue_script( 'wp-color-picker' ); 
            wp_enqueue_script( 'gmwpdl_admin_js', GMWPDL_PLUGIN_URL . 'js/admin-script.js' );
        }
    }

    /**
     * Placeholder function for initializing settings.
     * Can be used to register settings, post types, etc.
     */
    public function GMWPDL_init() {
        // Initialization code here (if needed)
    }

    /**
     * Add custom admin menu for the plugin.
     */
    public function GMWPDL_admin_menu() {
        add_menu_page(
            'Short Description Loop Show',          // Page title
            'Short Description Loop Show',          // Menu title
            'manage_options',                       // Capability
            'GMWPDL',                               // Menu slug
            array( $this, 'GMWPDL_page' )           // Callback function
        );
    }

    /**
     * Render the plugin's main admin page.
     */
    public function GMWPDL_page() {
        ?>
        <div>
            <h2><?php echo esc_html_e( 'Short Description and Attribute Show Loop For Woocommerce', 'short-description-and-attribute-show-loop-for-woocommerce' ); ?></h2>
            <?php
            $navarr = array(
                'page=GMWPDL' => 'General Settings',
            );
            ?>
            <h2 class="nav-tab-wrapper">
                <?php
                foreach ($navarr as $keya => $valuea) {
                    $pagexl = explode( "=", $keya );
                    if ( ! isset( $pagexl[2] ) ) {
                        $pagexl[2] = '';
                    }
                    if ( ! isset( $_REQUEST['view'] ) ) {
                        $_REQUEST['view'] = '';
                    }
                    ?>
                  <a href="<?php echo esc_url( admin_url( 'admin.php?' . $keya ) ); ?>" class="nav-tab <?php if ( isset($_REQUEST['view']) && $pagexl[2] == $_REQUEST['view'] ) { echo 'nav-tab-active'; } ?>"><?php echo esc_html( $valuea ); ?></a>

                    <?php
                }
                ?>
            </h2>
            <?php
            // Include general settings page if no specific view is set
            if ( $_REQUEST['view'] == '' ) {
                include( GMWPDL_PLUGIN_DIR . 'includes/GMWPDL_General.php' );
            }
            ?>
        </div>
        <?php
    }

    /**
     * Initialize admin settings.
     */
    public function GMWPDL_admin_init() {
        register_setting( 'gmwpdl_general_options_group', 'gmwpdl_usershow', array( $this, 'gmwpdl_multiple_callback' ) );
    }

    /**
     * Sanitize text input.
     *
     * @param string $input The input text to sanitize.
     * @return string Sanitized text.
     */
    public function gmwpdl_text_callback( $input ) {
        $sanitized_input = sanitize_text_field( $input );
        return $sanitized_input;
    }

    /**
     * Sanitize textarea input with allowed HTML tags.
     *
     * @param string $input The input textarea content to sanitize.
     * @return string Sanitized content with allowed HTML tags.
     */
    public function gmwpdl_textarea_callback( $input ) {
        $allowed_html = array(
            'a'      => array( 'href' => true, 'title' => true ),
            'br'     => array(),
            'em'     => array(),
            'strong' => array(),
            'p'      => array(),
            'table'  => array( 'border' => true, 'cellpadding' => true, 'cellspacing' => true, 'summary' => true, 'width' => true, 'class' => true, 'id' => true, 'style' => true ),
            'thead'  => array(),
            'tbody'  => array(),
            'tr'     => array(),
            'th'     => array( 'colspan' => true, 'rowspan' => true, 'scope' => true ),
            'td'     => array( 'colspan' => true, 'rowspan' => true, 'headers' => true ),
            // Add more tags and attributes as needed
        );
        $sanitized_input = wp_kses( $input, $allowed_html );
        return $sanitized_input;
    }

    /**
     * Sanitize multiple input fields.
     *
     * @param array $input Array of input fields.
     * @return array Sanitized array of input fields.
     */
    public function gmwpdl_multiple_callback( $input ) {
        $sanitized_data = array();

        // Iterate through each field in the form
        foreach ( $input as $key => $value ) {
            $sanitized_data[$key] = sanitize_text_field( $value );
        }

        return $sanitized_data;
    }

    /**
     * Callback for access token. 
     * Currently just returns the option unchanged.
     *
     * @param mixed $option The input option value.
     * @return mixed Unmodified option value.
     */
    public function gmwpdl_accesstoken_callback( $option ) {
        return $option;
    }

}
