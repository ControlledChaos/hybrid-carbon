<?php
/**
 * Attached location type class.
 *
 * Grabs the first attached image for a post and returns it.
 *
 * @package   HybridCarbon
 * @author    Justin Tadlock <justintadlock@gmail.com>
 * @copyright Copyright (c) 2018, Justin Tadlock
 * @link      https://github.com/justintadlock/hybrid-carbon
 * @license   http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 */

namespace Hybrid\Carbon\Types;

use Hybrid\Carbon\Image\Attachment;
use function Hybrid\Carbon\is_image_attachment;

/**
 * Attached location class.
 *
 * @since  1.0.0
 * @access public
 */
class Attached extends Base {

	/**
	 * Returns an `Image` object or `false` if no image is found.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @param  array      $args
	 * @return Image|bool
	 */
	public function make() {

		$image         = '';
		$attachment_id = 0;

		if ( is_image_attachment( $this->args['post_id'] ) ) {

			$attachment_id = $this->args['post_id'];
		} else {

			$attachments = get_children( [
				'numberposts'    => 1,
				'post_parent'    => $this->args['post_id'],
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => 'ASC',
				'orderby'        => 'menu_order ID',
				'fields'         => 'ids'
			] );

			// Check if any attachments were found.
			if ( $attachments ) {
				$attachment_id = array_shift( $attachments );
			}
		}

		if ( 0 < $attachment_id && is_image_attachment( $attachment_id ) ) {

			$image = new Attachment( $attachment_id, $this->args );
		}

		return $this->validate( $image ) ? $image : false;
	}
}