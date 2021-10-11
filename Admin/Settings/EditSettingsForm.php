<?php if(!defined('AHMETI_WP_TIMELINE_KONTROL')){ echo 'Bu dosyaya erşiminiz engellendi.'; exit(); } ?>
<?php
global $wpdb;

/* OPTIONS*/
$ahmetiWpTimelineOpt=json_decode(get_option('AhmetiWpTimelineOptions'));
?>
<h2><?php echo _e('Edit Settings','ahmeti-wp-timeline'); ?></h2>

<div style="display: block;padding: 0 0 10px 0">
    <form id="form_gonder" action="<?php echo AHMETI_WP_TIMELINE_ADMIN_URL; ?>&islem=EditSettingsPost" method="post">
        <table class="ahmetiwptablestd ahmetiwptablesSettings">
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
                <th><?php echo _e('Date Format<br/>(Year)','ahmeti-wp-timeline'); ?></th>
                <td>
                    <input type="text" name="DateFormatYear" value="<?php echo $ahmetiWpTimelineOpt->DateFormatYear; ?>" size="30"/>
                    <?php echo _e('Example:','ahmeti-wp-timeline'); ?> <span class="dateformat">Y</span> 2014
                </td>
            </tr>

            <tr>
                <th><?php echo _e('Date Format<br/>(Year & Month)','ahmeti-wp-timeline'); ?></th>
                <td>
                    <input type="text" name="DateFormatYearMonth" value="<?php echo $ahmetiWpTimelineOpt->DateFormatYearMonth; ?>" size="30"/>
                    <?php echo _e('Example:','ahmeti-wp-timeline'); ?> <span class="dateformat">m.Y</span> 12.2014
                </td>
            </tr>

            <tr>
                <th><?php echo _e('Date Format<br/>(Year & Month & Day)','ahmeti-wp-timeline'); ?></th>
                <td>
                    <input type="text" name="DateFormatYearMonthDay" value="<?php echo $ahmetiWpTimelineOpt->DateFormatYearMonthDay; ?>" size="30"/>
                    <?php echo _e('Example:','ahmeti-wp-timeline'); ?> <span class="dateformat">m.d.y</span> 01.12.14
                </td>
            </tr>
            
            <tr>
                <th><?php echo _e('Date Format<br/>(Hour & Minutes & Second)','ahmeti-wp-timeline'); ?></th>
                <td>
                    <input type="text" name="DateFormatHourMinutesSecond" value="<?php echo $ahmetiWpTimelineOpt->DateFormatHourMinutesSecond; ?>" size="30"/>
                    <?php echo _e('Example:','ahmeti-wp-timeline'); ?> <span class="dateformat">H:i:s</span> 21:10:01
                    <br/><div class="ahmetiRow">
                        For more date formats: <a target="_blank" href="http://php.net/manual/en/function.date.php">Php Date</a>
                    </div>     
                </td>
            </tr>            
            
        </table>
        <p>
            <input type="submit" value="<?php echo _e('Update Settings','ahmeti-wp-timeline'); ?>" class="button" id="gonder_button"/>
        </p>

    </form>
</div>


               