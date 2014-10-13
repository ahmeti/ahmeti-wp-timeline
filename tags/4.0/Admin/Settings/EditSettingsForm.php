<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
global $wpdb;

/* OPTIONS*/
$ahmetiWpTimelineOpt=json_decode(get_option('AhmetiWpTimelineOptions'));
?>
<h2><?php echo _e('Edit Settings','ahmeti-wp-timeline'); ?></h2>

<div style="display: block;padding: 0 0 10px 0">
    <form id="form_gonder" action="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=EditSettingsPost" method="post">
        <table class="ahmetiwptablesSettings">
            <tr>
                <th><?php echo _e('Default Sort','ahmeti-wp-timeline'); ?></th>
                <td>
                    <select name="DefaultSort">
                        <option <?php if( $ahmetiWpTimelineOpt->DefaultSort == 'ASC' ){ echo ' selected="selected" '; } ?> value="ASC">ASC</option>
                        <option <?php if( $ahmetiWpTimelineOpt->DefaultSort == 'DESC' ){ echo ' selected="selected" '; } ?> value="DESC">DESC</option>
                    </select>
                </td>
            </tr>
            
            <tr>
                <th><?php echo _e('Start State','ahmeti-wp-timeline'); ?></th>
                <td>
                    <select name="StartState">
                        <option <?php if( $ahmetiWpTimelineOpt->StartState == 'open' ){ echo ' selected="selected" '; } ?> value="open">Expand All</option>
                        <option <?php if( $ahmetiWpTimelineOpt->StartState == 'close' ){ echo ' selected="selected" '; } ?> value="close">Collapse All</option>
                    </select>
                </td>
            </tr>
            
            <tr>
                <th><?php echo _e('Admin Page Limit','ahmeti-wp-timeline'); ?></th>
                <td>
                    <input type="text" name="PageLimit" value="<?php echo (int)$ahmetiWpTimelineOpt->PageLimit; ?>" size="4"/>
                </td>
            </tr>
            
            <tr>
                <th></th>
                <td>
                    <input type="submit" value="<?php echo _e('Update Settings','ahmeti-wp-timeline'); ?>" class="button" id="gonder_button"/>
                </td>
            </tr>
            
        </table>
    </form>
</div>