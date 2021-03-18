<?php

namespace MPHB\Admin\MenuPages;

/**
 * @since 3.8
 */
class EditBookingMenuPage extends AbstractMenuPage
{
    /** @var int */
    protected $bookingId = 0;

    public function onLoad()
    {
        if (!$this->isCurrentPage()) {
            return;
        }

        if (isset($_GET['booking_id'])) {
            $this->bookingId = mphb_posint($_GET['booking_id']);
        }
    }

    public function render()
    {
        $backUrl = $this->getBackUrl();

        ?>
		<div class="wrap">
            <h1 class="wp-heading-inline"><?php printf(__('Edit Booking #%d', 'motopress-hotel-booking'), $this->bookingId); ?></h1>

            <?php if (!empty($backUrl)) { ?>
                <a href="<?php echo esc_url($backUrl); ?>" class="page-title-action"><?php _e('Cancel', 'motopress-hotel-booking'); ?></a>
            <?php } ?>

			<hr class="wp-header-end" />

			<?php echo mphb_upgrade_to_premium_message('div'); ?>
		</div>
        <?php
    }

    /**
     * @return string Back URL or empty string "".
     */
    protected function getBackUrl()
    {
        if ($this->bookingId == 0) {
            return '';
        } else {
            return get_edit_post_link($this->bookingId);
        }
    }

    public function getUrl($moreArgs = array())
    {
        if ($this->bookingId != 0) {
            $moreArgs['booking_id'] = $this->bookingId;
        }

        if (isset($_GET['lang'])) {
            $moreArgs['lang'] = sanitize_text_field($_GET['lang']);
        }

        return parent::getUrl($moreArgs);
    }

    /**
     * @return string
     */
    protected function getPageTitle()
    {
        return __('Edit Booking', 'motopress-hotel-booking');
    }

    /**
     * @return string
     */
    protected function getMenuTitle()
    {
        return __('Edit Booking', 'motopress-hotel-booking');
    }
}
