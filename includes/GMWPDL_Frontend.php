<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Class GMWPDL_Frontend
 *
 * This class handles front-end functionality for displaying additional product details on the shop and category pages.
 */
class GMWPDL_Frontend {

    /**
     * Constructor to set up hooks for initializing and enqueueing scripts.
     */
    public function __construct() {
        // Hook into WordPress 'init' action for any necessary initializations.
        add_filter( 'init', array( $this, 'gmwqp_init' ) );
        
        // Hook into WordPress 'wp' action to enqueue scripts and add WooCommerce hooks.
        add_filter( 'wp', array( $this, 'gmwqp_wp' ) );
    }

    /**
     * Placeholder function for initialization tasks.
     * This can be used to set up default settings or configurations.
     */
    public function gmwqp_init() {
        // Initialization code can go here (if needed).
    }

    /**
     * Set up actions for enqueueing scripts and adding WooCommerce hooks.
     */
    public function gmwqp_wp() {
        // Enqueue front-end styles and scripts.
        add_action( 'wp_enqueue_scripts', array( $this, 'gmwqp_insta_scritps' ) );
        
        // Hook into WooCommerce shop loop to display product details.
        add_action( 'woocommerce_after_shop_loop_item', array( $this, 'gmwqp_woocommerce_after_shop_loop_item' ), 10 );
    }

    /**
     * Enqueue front-end styles and scripts.
     */
    public function gmwqp_insta_scritps() {
        wp_enqueue_style(
            'gmwqp-stylee',
            GMWQP_PLUGIN_URL . '/assents/css/style.css',
            array(),
            '1.0.0',
            'all'
        );
        wp_enqueue_script(
            'gmwqp-script',
            GMWQP_PLUGIN_URL . '/assents/js/script.js',
            array(),
            '1.0.0',
            true
        );
        wp_localize_script(
            'gmwqp-script',
            'gmwqp_ajax_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ) )
        );
    }

    /**
     * Display additional product details after the shop loop item.
     */
    public function gmwqp_woocommerce_after_shop_loop_item() {
        global $product;

        // Retrieve the saved options for displaying product details.
        $gmwpdl_usershow = get_option( 'gmwpdl_usershow', array() );

        // Start outputting the product details container.
        echo "<div class='product-details' style='margin-bottom: 10px;'>";

        // Display SKU if enabled.
        if ( isset( $gmwpdl_usershow['short_description'] ) && $gmwpdl_usershow['short_description'] == 1 ) {
            $short_description = $product->get_short_description();
            if ( $short_description ) {
                echo esc_html('Shrot Description: ', 'short-description-and-attribute-show-loop-for-woocommerce') . esc_html( $short_description ) . '<br>';
            }
        }
        // Display SKU if enabled.
        if ( isset( $gmwpdl_usershow['sku'] ) && $gmwpdl_usershow['sku'] == 1 ) {
            $sku = $product->get_sku();
            if ( $sku ) {
                echo esc_html('SKU: ', 'short-description-and-attribute-show-loop-for-woocommerce') . esc_html( $sku ) . '<br>';
            }
        }

        // Display weight if enabled.
        if ( isset( $gmwpdl_usershow['weight'] ) && $gmwpdl_usershow['weight'] == 1 ) {
            $weight = $product->get_weight();
            if ( $weight ) {
                $weight_unit = get_option( 'woocommerce_weight_unit' );
                echo esc_html('Weight: ', 'short-description-and-attribute-show-loop-for-woocommerce') . esc_html( $weight ) . ' ' . esc_html( $weight_unit ) . '<br>';
            }
        }

        // Display dimensions if enabled.
        if ( isset( $gmwpdl_usershow['dimen'] ) && $gmwpdl_usershow['dimen'] == 1 ) {
            $dimensions = $product->get_dimensions();
            if ( $dimensions && $dimensions !== 'N/A' ) {
                echo esc_html('Dimensions: ', 'short-description-and-attribute-show-loop-for-woocommerce') . esc_html( $dimensions ) . '<br>';
            }
        }

        // Display categories if enabled.
        if ( isset( $gmwpdl_usershow['cat'] ) && $gmwpdl_usershow['cat'] == 1 ) {
            $categories = get_the_terms( $product->get_id(), 'product_cat' );
            if ( $categories && ! is_wp_error( $categories ) ) {
                $category_names = array_map( function ( $cat ) {
                    return $cat->name;
                }, $categories );
                echo esc_html('Categories: ', 'short-description-and-attribute-show-loop-for-woocommerce') . esc_html( implode( ', ', $category_names ) ) . '<br>';
            }
        }

        // Display tags if enabled.
        if ( isset( $gmwpdl_usershow['tag'] ) && $gmwpdl_usershow['tag'] == 1 ) {
            $tags = get_the_terms( $product->get_id(), 'product_tag' );
            if ( $tags && ! is_wp_error( $tags ) ) {
                $tag_names = array_map( function ( $tag ) {
                    return $tag->name;
                }, $tags );
                echo esc_html('Tags: ', 'short-description-and-attribute-show-loop-for-woocommerce') . esc_html( implode( ', ', $tag_names ) ) . '<br>';
            }
        }

        // Display attributes if enabled.
        if ( isset( $gmwpdl_usershow['attr'] ) && $gmwpdl_usershow['attr'] == 1 ) {
            // Display product attributes
            $attributes = $product->get_attributes();
            if (!empty($attributes)) {
                echo '<div class="product-attributes">';
                
                 foreach ( $attributes as $attribute_key => $attribute ) {
                        // Check if the attribute is an object (taxonomy-based or custom attributes)
                        if ( is_object( $attribute ) ) {
                            // Taxonomy-based attributes (like color, size)
                            if ( $attribute->is_taxonomy() ) {
                                $name = wc_attribute_label( $attribute->get_name() );
                                $terms = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
                                $values = is_array( $terms ) ? implode( ', ', $terms ) : $terms;  // Ensure terms are an array
                            } else {
                                // Custom product attributes
                                $name = wc_attribute_label( $attribute->get_name() );
                                $options = $attribute->get_options();
                                $values = is_array( $options ) ? implode( ', ', $options ) : $options;  // Ensure options are an array
                            }
                        } else {
                            // Handle attributes stored as strings (custom attributes)
                            $name = wc_attribute_label( $attribute_key );
                            $values = is_array( $attribute ) ? implode( ', ', $attribute ) : $attribute;  // Ensure attribute is an array
                        }

                        // Output the attribute name and values
                        echo esc_html( $name ) . ': ' . esc_html( $values ) . '<br>';
                    }

                echo '</div>';
            }

        }

        // Close the product details container.
        echo "</div>";
    }

}