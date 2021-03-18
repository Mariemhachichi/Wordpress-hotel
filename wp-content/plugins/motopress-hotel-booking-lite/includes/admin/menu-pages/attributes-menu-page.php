<?php

namespace MPHB\Admin\MenuPages;

class AttributesMenuPage extends AbstractMenuPage
{
    public function onLoad() {}

    public function render()
    {
        ?>
        <div class="wrap">
            <h1 class="wp-heading-inline"><?php _e('Attributes', 'motopress-hotel-booking'); ?></h1>

            <button class="page-title-action button-disabled"><?php _ex( 'Add New', 'Add New Attribute', 'motopress-hotel-booking' ); ?></button>
            <?php echo mphb_upgrade_to_premium_message(); ?>

            <hr class="wp-header-end" />

            <p><?php _e('Attributes let you define extra accommodation data, such as location or type. You can use these attributes in the search availability form as advanced search filters.', 'motopress-hotel-booking'); ?></p>

            <ul class="subsubsub">
                <li class="all"><?php echo translate('All'); ?> <span class="count">(0)</span></a></li>
            </ul>

            <table class="wp-list-table widefat fixed striped posts">
                <thead>
                    <tr>
                        <td id="cb" class="manage-column column-cb check-column">
                            <label class="screen-reader-text" for="cb-select-all-1"><?php echo translate('Select All'); ?></label>
                            <input id="cb-select-all-1" type="checkbox" />
                        </td>
                        <th scope="col" id="title" class="manage-column column-title column-primary"><?php echo translate('Title'); ?></th>
                        <th scope="col" id="mphb_slug" class="manage-column column-mphb_slug"><?php echo translate('Slug'); ?></th>
                        <th scope="col" id="terms" class="manage-column column-terms"><?php _e('Terms', 'motopress-hotel-booking'); ?></th>
                    </tr>
                </thead>

                <tbody id="the-list">
                    <tr class="no-items">
                        <td class="colspanchange" colspan="4">
                            <?php _e('No Attributes found', 'motopress-hotel-booking'); ?>
                        </td>
                    </tr>
                </tbody>

                <tfoot>
                    <tr>
                        <td class="manage-column column-cb check-column">
                            <label class="screen-reader-text" for="cb-select-all-2"><?php echo translate('Select All'); ?></label>
                            <input id="cb-select-all-2" type="checkbox" />
                        </td>
                        <th scope="col" class="manage-column column-title column-primary"><?php echo translate('Title'); ?></th>
                        <th scope="col" class="manage-column column-mphb_slug"><?php echo translate('Slug'); ?></th>
                        <th scope="col" class="manage-column column-terms"><?php _e('Terms', 'motopress-hotel-booking'); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php
    }

    protected function getMenuTitle()
    {
        return __('Attributes', 'motopress-hotel-booking');
    }

    protected function getPageTitle()
    {
        return __('Attributes', 'motopress-hotel-booking');
    }
}
