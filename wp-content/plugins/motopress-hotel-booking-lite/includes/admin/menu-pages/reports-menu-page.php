<?php

namespace MPHB\Admin\MenuPages;

/**
 * @since 3.5.0
 */
class ReportsMenuPage extends AbstractMenuPage
{
    /** @var array [Tab name => Title] */
    protected $tabs = array();

    public function addActions()
    {
        parent::addActions();

        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));
    }

    public function enqueueScripts()
    {
        if ($this->isCurrentPage()) {
            MPHB()->getAdminScriptManager()->enqueue();
        }
    }

    public function onLoad()
    {
        if (!$this->isCurrentPage()) {
            return;
        }

        // Init tabs list
        $this->tabs['bookings'] = __('Reports', 'motopress-hotel-booking');
    }

    protected function getCurrentTab()
    {
        $currentTab = '';

        if (isset($_GET['tab']) && !is_string($_GET['tab'])) {
            $currentTab = sanitize_text_field($_GET['tab']);
        }

        if (!isset($this->tabs[$currentTab])) {
            $tabs = array_keys($this->tabs);
            $currentTab = reset($tabs);
        }

        return $currentTab;
    }

    public function render()
    {
        $currentTab = $this->getCurrentTab();

        echo '<div class="wrap">';

            // Render tabs
            echo '<h1 class="nav-tab-wrapper">';
                foreach ($this->tabs as $tabName => $title) {
                    if ($tabName == $currentTab) {
                        echo '<span class="nav-tab nav-tab-active">', esc_html($title), '</span>';
                    } else {
                        $tabUrl = admin_url('admin.php');
                        $tabUrl = add_query_arg(array('page' => $this->name, 'tab' => $tabName), $tabUrl);

                        echo '<a href="', esc_url($tabUrl), '" class="nav-tab">', esc_html($title), '</a>';
                    }
                }
            echo '</h1>';

            // Render postboxes
            $renderMethod = 'render' . ucfirst($currentTab) . 'Tab';

            if (method_exists($this, $renderMethod)) {
                // Wrap all .postbox'es with .metabox-holder. It's required to apply
                // styles of ".metabox-holder .postbox > h3"
                echo '<div class="metabox-holder">';

                // renderBookingsTab()
                $this->$renderMethod();

                echo '</div>';
            }

        echo '</div>';
    }

    /**
     * @since 3.7.0 added new filter - "mphb_export_bookings_methods".
     */
    protected function renderBookingsTab()
    {
        $roomTypes = array(-1 => __('All Accommodation Types', 'motopress-hotel-booking'))
            + MPHB()->getRoomTypeRepository()->getIdTitleList();

        $bookingStatuses = array('all' => __('All Statuses', 'motopress-hotel-booking'))
            + MPHB()->postTypes()->booking()->statuses()->getLabels();

        $searchBy = apply_filters('mphb_export_bookings_methods', array(
            'reserved-rooms' => __('Booking dates between', 'motopress-hotel-booking'),
            'check-in'       => __('Check-in date between', 'motopress-hotel-booking'),
            'check-out'      => __('Check-out date between', 'motopress-hotel-booking'),
            'in-house'       => __('In-house between', 'motopress-hotel-booking'),
            'booking-date'   => __('Date of reservation between', 'motopress-hotel-booking')
        ));

        $exportColumns = MPHB()->settings()->export()->getBundle()->getBookingsExportColumns();

        $exportingColumns = MPHB()->settings()->export()->getUserExportColumns(array_keys($exportColumns));

        ?>
        <div class="postbox mphb-export-bookings-report">
            <h3><?php _e('Export Bookings', 'motopress-hotel-booking'); ?></h3>
            <div class="inside">
                <form id="mphb-export-bookings-form" class="mphb-export-form" method="POST">
                    <p>
						<fieldset>
							<?php mphb_tmpl_select_html(array('name' => 'room'), $roomTypes, -1); ?>
							<?php mphb_tmpl_select_html(array('name' => 'status'), $bookingStatuses, 'all'); ?>
						</fieldset>
					</p>
					<p>
						<fieldset>
							<?php mphb_tmpl_select_html(array('name' => 'search_by'), $searchBy, 'reserved-rooms'); ?>
							<input name="start_date" class="mphb-datepick mphb-export-start-date" type="text" value="" placeholder="<?php echo esc_attr(__('Choose start date', 'motopress-hotel-booking')); ?>" autocomplete="off" />
							<input name="end_date" class="mphb-datepick mphb-export-end-date" type="text" value="" placeholder="<?php echo esc_attr(__('Choose end date', 'motopress-hotel-booking')); ?>" autocomplete="off" />
						</fieldset>
					</p>
                    <p>
                        <button class="mphb-toggle-export-columns button-link"><?php _e('Select columns to export', 'motopress-hotel-booking'); ?></button>
                    </p>

                    <fieldset class="mphb-export-columns mphb-hide">
                        <?php mphb_tmpl_multicheck_html('columns', $exportColumns, $exportingColumns); ?>
                    </fieldset>

                    <?php
                        echo '<p>';
                            echo '<button class="submit-button button button-secondary" disabled="disabled">', __('Generate CSV', 'motopress-hotel-booking'), '</button>';

                            echo ' ', '<span class="mphb-preloader mphb-hide"></span>';
                        echo '</p>';

                        echo mphb_upgrade_to_premium_message('div');
                    ?>

                    <div class="mphb-progress mphb-hide">
                        <div class="mphb-progress__bar"></div>
                        <div class="mphb-progress__text">0%</div>
                    </div>

                    <button class="cancel-button button button-primary mphb-hide" disabled="disabled"><?php _e('Cancel', 'motopress-hotel-booking'); ?></button>

                    <div class="mphb-errors-wrapper mphb-hide"></div>
                </form>
            </div>
        </div>
        <?php
    }

    protected function getMenuTitle()
    {
        return __('Reports', 'motopress-hotel-booking');
    }

    protected function getPageTitle()
    {
        return __('Reports', 'motopress-hotel-booking');
    }
}
