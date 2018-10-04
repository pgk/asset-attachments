<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://kountanis.com
 * @since      1.0.0
 *
 * @package    Asset_Attachments
 * @subpackage Asset_Attachments/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php wp_nonce_field( 'asset_attachement_nonce', 'asset_attachement_nonce' );
?>
<ul class="asset-attachment-list">
<?php if ( ! empty( $asset_attachment_ids ) && is_array( $asset_attachment_ids ) ): ?>

	<?php foreach ( $asset_attachment_ids as $v ): ?>
		<li class="asset-attachment-list-item">
			<input type="hidden" name="asset_attachement_ids[]" class="asset-attachment" value="<?php echo esc_attr( $v ); ?>">
			<span><?php echo esc_html( wp_get_attachment_url( $v) ); ?></span>
			<button class="button asset-attachment-list-item-remove"><?php esc_html_e( 'Remove', 'asset-attachment' ); ?></button>
		</li>
	<?php endforeach; ?>

<?php endif; ?>
</ul>

<?php if ( ! empty( $asset_attachment_ids ) ): ?>
	<hr />
<?php endif; ?>

<button class="button button-large add-asset-attachment-button"><?php esc_html_e( 'Add asset', 'asset-attachment' ); ?></button>
