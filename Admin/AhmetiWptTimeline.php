<?php

class AhmetiWptTimeline
{
    public static function index()
    {
        ?>
        <h2 class="page-title"><?php echo _e('Timeline List', 'ahmeti-wp-timeline'); ?></h2>

        <?php
        global $wpdb;

        $page = isset($_GET['is_page']) ? (int) $_GET['is_page'] : 1;
        $limit = (int) AhmetiWpTimelineAdmin::options('PageLimit');
        $total = (int) $wpdb->get_var($wpdb->prepare('SELECT COUNT(*) FROM '.AhmetiWpTimelineAdmin::table().' WHERE type = %s', 'group_name'));
        $start = ($page - 1) * $limit;

        if ($total > 0) {

            $sub = 'SELECT COUNT(`event_id`) from `'.AhmetiWpTimelineAdmin::table().'` as `sub_awpt` '.
                   'WHERE `sub_awpt`.`group_id` = `awpt`.`group_id` AND `sub_awpt`.`type` = "event"';

            $prepare = $wpdb->prepare('SELECT `group_id`, `title`, ('.$sub.') as `event_count` FROM '.AhmetiWpTimelineAdmin::table().' as `awpt` '.
                                      'WHERE `awpt`.`type` = "group_name" ORDER BY `awpt`.`group_id` DESC LIMIT %d, %d', $start, $limit);

            $timelines = $wpdb->get_results($prepare);

            ?>
            <table class="table">
                <colgroup>
                    <col style="width: 15%">
                    <col style="width: 20%">
                    <col style="width: 45%">
                    <col style="width: 20%">
                </colgroup>
                <tr>
                    <th><?php echo _e('ID', 'ahmeti-wp-timeline'); ?></th>
                    <th><?php echo _e('Actions', 'ahmeti-wp-timeline'); ?></th>
                    <th><?php echo _e('Name', 'ahmeti-wp-timeline'); ?></th>
                    <th><?php echo _e('Count Events', 'ahmeti-wp-timeline'); ?></th>
                </tr>
                <?php foreach ($timelines as $timeline) {
                    $nonce = wp_create_nonce('awt_timeline_delete_'.$timeline->group_id); ?>
                    <tr>
                        <td>
                            <?php echo $timeline->group_id; ?>
                        </td>
                        <td>
                            <a href="<?php echo AhmetiWpTimelineAdmin::url(); ?>&islem=TimelineEdit&id=<?php echo $timeline->group_id; ?>">Edit</a>
                            &nbsp;
                            <a onclick="return confirm('<?php echo _e('Events belonging to the timeline will also be deleted. Are you sure you want to delete this timeline?', 'ahmeti-wp-timeline'); ?>')"
                               href="<?php echo AhmetiWpTimelineAdmin::url().'&islem=TimelineDelete&id='.$timeline->group_id.'&_wpnonce='.$nonce; ?>">Delete</a>
                        </td>
                        <td>
                            <a href="<?php echo AhmetiWpTimelineAdmin::url(); ?>&islem=EventList&group_id=<?php echo $timeline->group_id; ?>"><?php echo $timeline->title; ?></a>
                        </td>
                        <td>
                            <?php echo $timeline->event_count; ?>
                        </td>
                    </tr>
                    <?php } ?>
            </table>
            <?php AhmetiWpTimelineAdmin::pagination(AhmetiWpTimelineAdmin::url(), $total, $page, $limit, '&islem=GroupList');
        } else {
            ?><p class="alert-danger"><?php echo _e('You have not added any group :(', 'ahmeti-wp-timeline'); ?></p><?php
        }
    }

    public static function create()
    {
        ?>
        <h2 class="page-title"><?php echo _e('New Timeline', 'ahmeti-wp-timeline'); ?></h2>

        <div>
            <form id="form_gonder" action="<?php echo AhmetiWpTimelineAdmin::url(); ?>&islem=TimelineStore" method="post">
                <h4 style="margin-bottom: 1px;"><?php echo _e('Timeline Name', 'ahmeti-wp-timeline'); ?></h4>
                <input type="text" name="name" size="40" required/>
                <br/><br/>
                <?php wp_nonce_field('awt_timeline_create') ?>
                <input type="submit" value="<?php echo _e('Save Timeline', 'ahmeti-wp-timeline'); ?>" class="button" id="gonder_button"/>
            </form>
        </div>
        <?php
    }

    public static function store()
    {
        ?><h2 class="page-title"><?php echo _e('New Timeline', 'ahmeti-wp-timeline'); ?></h2> <?php

        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : null;

        if (! isset($_POST['_wpnonce']) || ! wp_verify_nonce($_POST['_wpnonce'], 'awt_timeline_create')) {
            ?><div class="alert-danger"><?php echo _e('Sorry, your nonce did not verify.', 'ahmeti-wp-timeline'); ?></div><?php
            return;
        }

        if (empty($name)) {
            ?><div class="alert-danger"><?php echo _e('Do not leave empty fields.', 'ahmeti-wp-timeline'); ?></div><?php
            return;
        }

        global $wpdb;

        $lastId = $wpdb->get_row('SELECT group_id FROM '.AhmetiWpTimelineAdmin::table().' ORDER BY group_id DESC LIMIT 0,1', ARRAY_N);
        $newId = isset($lastId[0]) ? ((int) $lastId[0]) + 1 : 1;

        $status = $wpdb->query($wpdb->prepare('INSERT INTO '.AhmetiWpTimelineAdmin::table().' (group_id, title, type , timeline_bc ,timeline_date , event_content)
            VALUES ( %d, %s, %s ,%s, %s, %s)', $newId, $name, 'group_name', '0', null, null));

        if ($status) {
            ?><div class="alert-success"><?php echo _e('The timeline has successfully added.', 'ahmeti-wp-timeline'); ?></div> <?php
        } else {
            ?><div class="alert-danger"><?php echo _e('An error occurred while adding group.', 'ahmeti-wp-timeline'); ?></div><?php
        }
    }

    public static function edit()
    {
        ?>
        <h2 class="page-title"><?php echo _e('Edit Timeline', 'ahmeti-wp-timeline'); ?></h2><?php

        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

        if (empty($id)) {
            ?><div class="alert-danger"><?php echo _e('Do not leave empty fields.', 'ahmeti-wp-timeline'); ?></div><?php
            return;
        }

        global $wpdb;
        $prepare = $wpdb->prepare('SELECT `group_id`, `title` FROM '.AhmetiWpTimelineAdmin::table().' as `awpt` WHERE `awpt`.`type` = "group_name" AND `awpt`.`group_id` = %d', $id);
        $timeline = $wpdb->get_row($prepare);

        ?>
        <div>
            <form id="form_gonder" action="<?php echo AhmetiWpTimelineAdmin::url(); ?>&islem=TimelineUpdate" method="post">
                <h4 style="margin-bottom: 1px;"><?php echo _e('Timeline Name', 'ahmeti-wp-timeline'); ?></h4>
                <input type="text" name="name" size="40" value="<?php echo esc_attr($timeline->title); ?>"/>
                <input type="hidden" name="id" value="<?php echo esc_attr($timeline->group_id); ?>"/>
                <br/><br/>
                <?php wp_nonce_field('awt_timeline_update_'.$timeline->group_id) ?>
                <input type="submit" value="<?php echo _e('Update Timeline', 'ahmeti-wp-timeline'); ?>" class="button"/>
            </form>
        </div><?php

    }

    public static function update()
    {
        ?><h2 class="page-title"><?php echo _e('Edit Timeline', 'ahmeti-wp-timeline'); ?></h2> <?php

        $id = isset($_POST['id']) ? (int) $_POST['id'] : null;
        $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : null;

        if (! isset($_POST['_wpnonce']) || ! wp_verify_nonce($_POST['_wpnonce'], 'awt_timeline_update_'.$id)) {
            ?><div class="alert-danger"><?php echo _e('Sorry, your nonce did not verify.', 'ahmeti-wp-timeline'); ?></div><?php
            return;
        }

        if (empty($id) || empty($name)) {
            ?><div class="alert-danger"><?php echo _e('Do not leave empty fields.', 'ahmeti-wp-timeline'); ?></div><?php
            return;
        }

        global $wpdb;
        $status = $wpdb->update(AhmetiWpTimelineAdmin::table(), ['title' => $name], ['group_id' => $id, 'type' => 'group_name'], ['%s'], ['%d', '%s']);

        if ($status) {
            ?><div class="alert-success"><?php echo _e('Timeline successfully updated.', 'ahmeti-wp-timeline'); ?></div><?php
        } else {
            ?><div class="alert-danger"><?php echo _e('An error occurred while updating the group.', 'ahmeti-wp-timeline'); ?></div><?php
        }
    }

    public static function delete()
    {
        ?><h2 class="page-title"><?php echo _e('Edit Timeline', 'ahmeti-wp-timeline'); ?></h2> <?php

        $id = isset($_GET['id']) ? (int) $_GET['id'] : null;

        if (! isset($_GET['_wpnonce']) || ! wp_verify_nonce($_GET['_wpnonce'], 'awt_timeline_delete_'.$id)) {
            ?><div class="alert-danger"><?php echo _e('Sorry, your nonce did not verify.', 'ahmeti-wp-timeline'); ?></div><?php
            return;
        }

        if (empty($id)) {
            ?><div class="alert-danger"><?php echo _e('Do not leave empty fields.', 'ahmeti-wp-timeline'); ?></div><?php
            return;
        }

        global $wpdb;
        $sql = $wpdb->delete(AhmetiWpTimelineAdmin::table(), ['group_id' => $id], ['%d']);

        if ($sql) {
            ?><div class="alert-success"><?php echo _e('Timeline deleted successfully.', 'ahmeti-wp-timeline'); ?></div><?php
        } else {
            ?><div class="alert-danger"><?php echo _e('An error occurred while deleting the timeline.', 'ahmeti-wp-timeline'); ?></div><?php
        }
    }
}
