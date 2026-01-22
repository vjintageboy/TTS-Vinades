<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2022 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * nv_aleditor()
 *
 * @param string $textareaname
 * @param string $width
 * @param string $height
 * @param string $val
 * @param string $customtoolbar
 * @param string $path
 * @param string $currentpath
 * @return string
 */
function nv_aleditor($textareaname, $width = '100%', $height = '450px', $val = '', $customtoolbar = '', $path = '', $currentpath = '')
{
    global $global_config, $module_upload, $module_data, $admin_info;

    $textareaid = preg_replace('/[^a-z0-9\-\_ ]/i', '_', $textareaname);
    $editor_id = $module_data . '_' . $textareaid;

    $return = '<div id="outer_' . $editor_id . '" data-toggle="outerNvCkeditor5"><textarea style="width: ' . $width . '; height:' . $height . ';" id="' . $editor_id . '" name="' . $textareaname . '">' . $val . '</textarea></div>';

    if (!defined('CKEDITOR5_CLASSIC')) {
        define('CKEDITOR5_CLASSIC', true);
        $return .= '<link rel="stylesheet" href="' . NV_STATIC_URL . NV_EDITORSDIR . '/ckeditor5-classic/ckeditor.css?t=' . $global_config['timestamp'] . '">';
        $return .= '<script type="text/javascript" src="' . NV_STATIC_URL . NV_EDITORSDIR . '/ckeditor5-classic/language/' . NV_LANG_INTERFACE . '.js?t=' . $global_config['timestamp'] . '"></script>';
        $return .= '<script type="text/javascript" src="' . NV_STATIC_URL . NV_EDITORSDIR . '/ckeditor5-classic/ckeditor.js?t=' . $global_config['timestamp'] . '"></script>';
    }

    $create = [];
    $create[] = 'language: "' . NV_LANG_INTERFACE . '"';
    $create[] = 'nukeviet: {
        editorId: "' . $editor_id . '",
        height: "' . $height . '"
    }';

    $custom_toolbar = false;
    if (!empty($customtoolbar)) {
        $customtoolbar = json_decode($customtoolbar, true);
        if (is_array($customtoolbar)) {
            $custom_toolbar = true;
            $create[] = "toolbar : " . json_encode($customtoolbar);
        }
    }
    $toolbars = [
        'undo',
        'redo',
        'selectAll',
        '|',
        'link',
        'bookmark',
        'imageInsert',
        'nvmediaInsert',
        'nvbox',
        'insertTable',
        'nviframeInsert',
        'nvdocsInsert',
        'code',
        'codeBlock',
        'horizontalLine',
        'specialCharacters',
        'pageBreak',
        '|',
        'findAndReplace',
        'showBlocks',
        '|',
        'bulletedList',
        'numberedList',
        'outdent',
        'indent',
        'blockQuote',
        'heading',
        'fontSize',
        'fontFamily',
        'fontColor',
        'fontBackgroundColor',
        'highlight',
        'alignment',
        '|',
        'bold',
        'italic',
        'underline',
        'emoji',
        'strikethrough',
        'subscript',
        'superscript',
        '|',
        'sourceEditing',
        'nvtools',
        'removeFormat',
        'fullscreen'
    ];

    // Thiết lập nvbox, nvmedia và simpleUpload
    if (defined('NV_IS_ADMIN')) {
        if (empty($path) and empty($currentpath)) {
            $path = NV_UPLOADS_DIR;
            $currentpath = NV_UPLOADS_DIR;

            if (!empty($module_upload) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . date('Y_m'))) {
                $currentpath = NV_UPLOADS_DIR . '/' . $module_upload . '/' . date('Y_m');
                $path = NV_UPLOADS_DIR . '/' . $module_upload;
            } elseif (!empty($module_upload) and file_exists(NV_UPLOADS_REAL_DIR . '/' . $module_upload)) {
                $currentpath = NV_UPLOADS_DIR . '/' . $module_upload;
            }
        }

        if (!empty($admin_info['allow_files_type'])) {
            $create[] = 'simpleUpload: {
                uploadUrl: "' . (NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_NAME_VARIABLE . '=upload&' . NV_OP_VARIABLE . '=upload&editor=ckeditor5-classic&path=' . $currentpath) . '",
                withCredentials: true
            }';
        }
        $create[] = 'nvbox: {
            browseUrl: "' . (NV_BASE_SITEURL . NV_ADMINDIR . '/index.php?' . NV_NAME_VARIABLE . '=upload&popup=1&CKEditorFuncNum=1&editor_id=' . $editor_id . '&path=' . $path . '&currentpath=' . $currentpath) . '",
            options: {
                noCache: false
            }
        }';
    } else {
        // Không có quyền upload thì bỏ duyệt file và nút upload ảnh, media
        $create[] = 'removePlugins: ["NVBox"]';
        $create[] = 'image: {insert: {integrations: ["url"]}}';
        $create[] = 'nvmedia: {insert: {integrations: ["url"]}}';
        $toolbars = array_diff($toolbars, ['nvbox']);
    }
    if (!$custom_toolbar) {
        $create[] = "toolbar: {
            items: ['" . implode("', '", $toolbars) . "'],
            shouldNotGroupWhenFull: true
        }";
    }
    $create[] = "htmlSupport: {
		disallow: [{
			name: /.*/,
			attributes: [ /^on.*/ ]
		}, {
			name: 'script',
			attributes: [
				'action',
				'background',
				'codebase',
				'dynsrc',
				'lowsrc',
				'allownetworking',
				'allowscriptaccess',
				'fscommand',
				'seeksegmenttime'
			]
		}, {
			name: /^(script|style|link)$/
		}],
		allow: [{
			name: /" . (!empty($global_config['allowed_html_tags']) ? ('^(' . implode('|', $global_config['allowed_html_tags']) . ')$') : '.*') . "/,
			attributes: true,
			classes: true,
			styles: true
		}]
	}";

    $return .= '<script>
        (async () => {
            const editorId = "' . $editor_id . '";
            await ClassicEditor
            .create(document.getElementById("' . $editor_id . '"), {' . implode(', ', $create) . '}).catch(error => {
                console.error(error);
            });
        })();
    </script>';

    return $return;
}
