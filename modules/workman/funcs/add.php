<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_WORKMAN')) {
    exit('Stop!!!');
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$row = [];
$error = '';

if ($nv_Request->isset_request('submit', 'post')) {
    $row['title'] = $nv_Request->get_title('title', 'post', '');
    $row['description'] = $nv_Request->get_string('description', 'post', '');
    $row['bodytext'] = $nv_Request->get_editor('bodytext', 'bodytext', 'post', '');
    $row['image'] = $nv_Request->get_string('image', 'post', '');

    if (empty($row['title'])) {
        $error = 'Lỗi: Tiêu đề không được để trống';
    } elseif (empty($row['bodytext'])) {
        $error = 'Lỗi: Nội dung không được để trống';
    } else {
        $alias = change_alias($row['title']);
        $catid = 0; // Default category
        $add_time = NV_CURRENTTIME;
        $status = 1; // Active by default

        $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . ' (title, alias, catid, image, description, bodytext, add_time, edit_time, status, hitstotal) VALUES (:title, :alias, :catid, :image, :description, :bodytext, :add_time, :add_time, :status, 0)';
        
        $sth = $db->prepare($sql);
        $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
        $sth->bindParam(':alias', $alias, PDO::PARAM_STR);
        $sth->bindParam(':catid', $catid, PDO::PARAM_INT);
        $sth->bindParam(':image', $row['image'], PDO::PARAM_STR);
        $sth->bindParam(':description', $row['description'], PDO::PARAM_STR);
        $sth->bindParam(':bodytext', $row['bodytext'], PDO::PARAM_STR);
        $sth->bindParam(':add_time', $add_time, PDO::PARAM_INT);
        $sth->bindParam(':status', $status, PDO::PARAM_INT);

        if ($sth->execute()) {
             $url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
             nv_redirect_location($url);
        } else {
             $error = 'Lỗi: Không thể lưu dữ liệu';
        }
    }
} else {
    $row['title'] = '';
    $row['description'] = '';
    $row['bodytext'] = '';
    $row['image'] = '';
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $row['bodytext'] = nv_aleditor('bodytext', '100%', '300px', $row['bodytext']);
} else {
    $row['bodytext'] = '<textarea style="width:100%;height:300px" name="bodytext">' . $row['bodytext'] . '</textarea>';
}

$contents = nv_theme_workman_add($row, $error);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
