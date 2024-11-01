<?php
if ( ! defined( 'ABSPATH' ) ) exit;
// Retrieve the current settings for 'gmwpdl_usershow' option.
$gmwpdl_usershow = get_option( 'gmwpdl_usershow' );
?>

<form method="post" action="options.php">
    <!-- Output the nonce, action, and option_group fields needed for WordPress settings API -->
    <?php settings_fields( 'gmwpdl_general_options_group' ); ?>

    <div class="metabox-holder">
        <div class="postbox">
            <div class="postbox-header">
                <h3 class="hndle">Setting</h3>
            </div>
            <div class="inside">
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <!-- Label for the checkbox group -->
                            <label><?php echo esc_html( 'Show On Shop Page', 'short-description-and-attribute-show-loop-for-woocommerce' ); ?></label>
                        </th>
                        <td>
                            <!-- Checkbox to enable/disable SKU display -->
                            <input type="checkbox" name="gmwpdl_usershow[short_description]" value="1" <?php checked( isset( $gmwpdl_usershow['short_description'] ), 1 ); ?> >
                            <label for="gmwpdl_usershow[short_description]"><?php echo esc_html('Short Description', 'short-description-and-attribute-show-loop-for-woocommerce'); ?></label>
                            <br>

                            <!-- Checkbox to enable/disable SKU display -->
                            <input type="checkbox" name="gmwpdl_usershow[sku]" value="1" <?php checked( isset( $gmwpdl_usershow['sku'] ), 1 ); ?> >
                            <label for="gmwpdl_usershow[sku]"><?php echo esc_html('SKU', 'short-description-and-attribute-show-loop-for-woocommerce'); ?></label>
                            <br>

                            <!-- Checkbox to enable/disable weight display -->
                            <input type="checkbox" name="gmwpdl_usershow[weight]" value="1" <?php checked( isset( $gmwpdl_usershow['weight'] ), 1 ); ?> >
                            <label for="gmwpdl_usershow[weight]"><?php echo esc_html('Weight', 'short-description-and-attribute-show-loop-for-woocommerce'); ?></label>
                            <br>

                            <!-- Checkbox to enable/disable dimensions display -->
                            <input type="checkbox" name="gmwpdl_usershow[dimen]" value="1" <?php checked( isset( $gmwpdl_usershow['dimen'] ), 1 ); ?> >
                            <label for="gmwpdl_usershow[dimen]"><?php echo esc_html('Dimension', 'short-description-and-attribute-show-loop-for-woocommerce'); ?></label>
                            <br>

                            <!-- Checkbox to enable/disable categories display -->
                            <input type="checkbox" name="gmwpdl_usershow[cat]" value="1" <?php checked( isset( $gmwpdl_usershow['cat'] ), 1 ); ?> >
                            <label for="gmwpdl_usershow[cat]"><?php echo esc_html('Categories', 'short-description-and-attribute-show-loop-for-woocommerce'); ?></label>
                            <br>

                            <!-- Checkbox to enable/disable tags display -->
                            <input type="checkbox" name="gmwpdl_usershow[tag]" value="1" <?php checked( isset( $gmwpdl_usershow['tag'] ), 1 ); ?> >
                            <label for="gmwpdl_usershow[tag]"><?php echo esc_html('Tags', 'short-description-and-attribute-show-loop-for-woocommerce'); ?></label>
                            <br>

                            <!-- Checkbox to enable/disable attributes display -->
                            <input type="checkbox" name="gmwpdl_usershow[attr]" value="1" <?php checked( isset( $gmwpdl_usershow['attr'] ), 1 ); ?> >
                            <label for="gmwpdl_usershow[attr]"><?php echo esc_html('Attributes', 'short-description-and-attribute-show-loop-for-woocommerce'); ?></label>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <!-- Output the submit button for saving settings -->
    <?php submit_button(); ?>
</form>
