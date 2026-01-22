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

/**
 * nv_theme_workman_add()
 * 
 * @param mixed $row
 * @param mixed $error
 * @return
 */
function nv_theme_workman_add($row, $error)
{
    global $module_info, $lang_module, $module_file, $module_name, $op;

    $temp = $module_info['template'];
    if (!file_exists(NV_ROOTDIR . '/themes/' . $temp . '/modules/' . $module_file . '/add.tpl')) {
        $temp = 'default';
    }

    $xtpl = new XTemplate('add.tpl', NV_ROOTDIR . '/themes/' . $temp . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('TEMPLATE', $module_info['template']);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    
    if (!empty($error)) {
        $xtpl->assign('ERROR', $error);
        $xtpl->parse('main.error');
    }

    $xtpl->assign('ROW', $row);

    $xtpl->parse('main');
    return $xtpl->text('main');
}
