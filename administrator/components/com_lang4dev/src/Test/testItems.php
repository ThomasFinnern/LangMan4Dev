$CopyDbGalleries = Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_GALLERIES');
$CopyDbGalleries = Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_GALLERIES');
$CopyDbGalleries = Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_GALLERIES2' . Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_GALLERIES_DESC'));
//* first line COM_LANG4DEV_Comment01
/* first line COM_LANG4DEV_Comment02
   second lineCOM_LANG4DEV_Comment02
   third line 
last line */
/* COM_LANG4DEV_Comment10 */ COM_LANG4DEV_FLIP_HORIZONTAL /* COM_LANG4DEV_Comment11 */ 

$CopyDbGalleriesDesc = Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_GALLERIES_DESC');
$CopyDbImages = Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_IMAGES');
$CopyDbImagesDesc = Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_IMAGES_DESC');
$CopyImages = Text::_('COM_LANG4DEV_MOVE_J3X_IMAGES');
$CopyImagesDesc = Text::_('COM_LANG4DEV_MOVE_J3X_IMAGES_DESC');
$RSG2_Zone = new zoneContainer(Text::_('COM_LANG4DEV_RSGALLERY2_ZONE'), Text::_('COM_LANG4DEV_RSGALLERY2_ZONE_DESC'), 'rsg2', 'rsg2Zone');
$app->enqueueMessage(Text::_('COM_LANG4DEV_SERVER_DIR_EMPTY'));
$app->enqueueMessage(Text::_('COM_LANG4DEV_SERVER_DIR_NOT_EXIST') . '<br>'
$app->enqueueMessage(Text::_('COM_LANG4DEV_SERVER_FILES_DO_NOT_EXIST') . '<br>'
$app->enqueueMessage(Text::_('COM_LANG4DEV_ZIP_FILE_NO_IMAGE_EXIST') . '<br>'
$attr2 .= ' search-placeholder="' . $this->escape(Text::_('COM_LANG4DEV_TYPE_OR_SELECT_GALLERY')) . '" ';
$childBar->standardButton('arrow-down-4', 'COM_LANG4DEV_FLIP_VERTICAL', 'image.flip_image_vertical')->icon('fa fa-arrows-alt-v');
$childBar->standardButton('arrow-down-4', 'COM_LANG4DEV_FLIP_VERTICAL','imagesProperties.flip_images_vertical')->icon('fa fa-arrows-alt-v');
$childBar->standardButton('backward-2', 'COM_LANG4DEV_ROTATE_180', 'images.rotate_image_180')->icon('fa fa-sync fa-rotate-180');
$childBar->standardButton('backward-2', 'COM_LANG4DEV_ROTATE_180','imagesProperties.rotate_images_180')->icon('fa fa-sync fa-rotate-180');
$childBar->standardButton('fa-arrows', 'COM_LANG4DEV_FLIP_HORIZONTAL', 'image.flip_image_horizontal')->icon('fa fa-arrows-alt-h');
$childBar->standardButton('fa-arrows', 'COM_LANG4DEV_FLIP_HORIZONTAL','imagesProperties.flip_images_horizontal')->icon('fa fa-arrows-alt-h');
$childBar->standardButton('redo-2', 'COM_LANG4DEV_ROTATE_RIGHT', 'images.rotate_image_right')->icon('fa fa-redo');
$childBar->standardButton('redo-2', 'COM_LANG4DEV_ROTATE_RIGHT','imagesProperties.rotate_images_right')->icon('fa fa-redo');
$childBar->standardButton('undo-2', 'COM_LANG4DEV_ROTATE_LEFT', 'image.rotate_image_left')->icon('fa fa-undo');
$childBar->standardButton('undo-2', 'COM_LANG4DEV_ROTATE_LEFT','imagesProperties.rotate_images_left')->icon('fa fa-undo');
$configurationText = Text::_('COM_LANG4DEV_MENU_CONFIG');
$configurationTitle = Text::_('COM_LANG4DEV_INSTALL_GOTO_CONFIGURATION_TITLE');
$controlPanelText = Text::_('COM_LANG4DEV_MENU_CONTROL_PANEL');
$controlPanelTitle = Text::_('COM_LANG4DEV_INSTALL_GOTO_CONTROL_PANEL_TITLE');
$danger_Zone = new zoneContainer(Text::_('COM_LANG4DEV_DANGER_ZONE'), Text::_('COM_LANG4DEV_DANGER_ZONE_DESCRIPTION'), 'danger', 'dangerZone');
$developer4Test_Zone = new zoneContainer(Text::_('COM_LANG4DEV_DEVELOP_TEST_ZONE'), Text::_('COM_LANG4DEV_DEVELOP_TEST_ZONE_DESCRIPTION'), 'devTest', 'devTestZone');
$developer_Zone = new zoneContainer(Text::_('COM_LANG4DEV_DEVELOPER_ZONE'), Text::_('COM_LANG4DEV_DEVELOPER_ZONE_DESCRIPTION'), 'developer', 'developerZone');
$displayData['title'] = Text::_('COM_LANG4DEV_LATEST_IMAGES');
$displayData['title'] = Text::_('COM_LANG4DEV_RANDOM_IMAGES');
$errMsg = Text::_('COM_LANG4DEV_FOLDER_DOES_NOT_EXIST') . ': "' . $fullPath . '""';
$errMsg = Text::_('COM_LANG4DEV_FOLDER_DOES_NOT_EXIST');
$galleriesText = Text::_('COM_LANG4DEV_MENU_GALLERIES');
$galleriesTitle = Text::_('COM_LANG4DEV_INSTALL_GOTO_GALLERIES_TITLE');
$header = Text::_('COM_LANG4DEV_J3X_ACTIONS_NEEDED');
$headerDesc = Text::_('COM_LANG4DEV_J3X_ACTIONS_NEEDED_DESC');
$html[] = '            <td>' . Text::_('COM_LANG4DEV_DOCUMENTATION') . '</td>';
$html[] = '            <td>' . Text::_('COM_LANG4DEV_FORUM') . '</td>';
$html[] = '            <td>' . Text::_('COM_LANG4DEV_HOME_PAGE') . '</td>';
$html[] = '            <td>' . Text::_('COM_LANG4DEV_INSTALLED_VERSION') . ': ' . '</td>';
$html[] = '            <td>' . Text::_('COM_LANG4DEV_LICENSE') . ': ' . '</td>';
$html[] = '         data-original-title="' . HtmlHelper::tooltipText('COM_LANG4DEV_ASSIGN_GALLLERY_IN_ROW') . '" ';
$html[] = '         data-original-title="' . HtmlHelper::tooltipText('COM_LANG4DEV_CREATE_MISSING_IMAGES_IN_ROW') . '" ';
$html[] = '         data-original-title="' . HtmlHelper::tooltipText('COM_LANG4DEV_DELETE_SUPERFLOUS_ITEMS_IN_ROW') . '" ';
$html[] = '         data-original-title="' . HtmlHelper::tooltipText('COM_LANG4DEV_REPAIR_ISSUES_IN_ROW') . '" ';
$html[] = '         title="' . HTMLHelper::tooltipText('COM_LANG4DEV_CREATE_DATABASE_ENTRY') . '" ';
$html[] = '      title="' . HTMLHelper::tooltipText('COM_LANG4DEV_DATABASE_ENTRY_FOUND') . '" ';
$html[] = '      title="' . HTMLHelper::tooltipText('COM_LANG4DEV_DISPLAY_IMAGE_FOUND') . '" ';
$html[] = '      title="' . HTMLHelper::tooltipText('COM_LANG4DEV_DISPLAY_IMAGE_NOT_FOUND') . '" ';
$html[] = '      title="' . HtmlHelper::tooltipText('COM_LANG4DEV_ORIGINAL_IMAGE_FOUND') . '" ';
$html[] = '      title="' . HtmlHelper::tooltipText('COM_LANG4DEV_ORIGINAL_IMAGE_NOT_FOUND') . '" ';
$html[] = '      title="' . HtmlHelper::tooltipText('COM_LANG4DEV_SIZE_IMAGE_FOUND') . '" ';
$html[] = '      title="' . HtmlHelper::tooltipText('COM_LANG4DEV_SIZE_IMAGE_NOT_FOUND') . '" ';
$html[] = '      title="' . HtmlHelper::tooltipText('COM_LANG4DEV_THUMB_IMAGE_FOUND') . '" ';
$html[] = '      title="' . HtmlHelper::tooltipText('COM_LANG4DEV_THUMB_IMAGE_NOT_FOUND') . '" ';
$html[] = '    <caption><h3>' . Text::_('COM_LANG4DEV_MISSING_IMAGE_REFERENCES_LIST') . '</h3></caption>';
$html[] = Text::_('COM_LANG4DEV_ACTION');
$html[] = Text::_('COM_LANG4DEV_DISPLAY_BR_FOLDER');
$html[] = Text::_('COM_LANG4DEV_FILENAME');
$html[] = Text::_('COM_LANG4DEV_GALLERY'); // COM_LANG4DEV_PARENT_BR_GALLERY
$html[] = Text::_('COM_LANG4DEV_IMAGE');
$html[] = Text::_('COM_LANG4DEV_IN_BR_DATABASE');
$html[] = Text::_('COM_LANG4DEV_NUM');
$html[] = Text::_('COM_LANG4DEV_ORIGINAL_BR_FOLDER');
$html[] = Text::_('COM_LANG4DEV_SIZES_BR_FOLDERS');
$html[] = Text::_('COM_LANG4DEV_THUMB_BR_FOLDER');
$keyTranslation = 'J4x ' . Text::_('COM_LANG4DEV_GALLERIES_LIST_IS_EMPTY');
$keyTranslation = 'J4x ' . Text::_('COM_LANG4DEV_IMAGES_LIST_IS_EMPTY');
$keyTranslation = Text::_('COM_LANG4DEV_GALLERIES_AS_TREE_IS_EMPTY');
$keyTranslation = Text::_('COM_LANG4DEV_GALLERIES_AS_TREE_JUMP_TO_J3X_GALLERIES');
$keyTranslation = Text::_('COM_LANG4DEV_GALLERIES_AS_TREE_TABLE_EMPTY');
$label = !empty($fieldSet->label) ? $fieldSet->label : 'COM_LANG4DEV_' . $name . '_FIELDSET_LABEL';
$modalTitle    = Text::_('COM_LANG4DEV_CHANGE_GALLERY');
$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR') . " " . JText::_('COM_LANG4DEV_COMMENTING_IS_DISABLED');
$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR') . " " . JText::_('COM_LANG4DEV_VOTING_IS_DISABLED');
$msg     = $msg . JText::_('JERROR_ALERTNOAUTHOR') . " " . JText::_('COM_LANG4DEV_YOU_MUST_LOGIN_TO_COMMENT' . ' (B)');
$msg .= '<br><br>' . Text::_('COM_LANG4DEV_PURGED', true);
$msg .= Text::_('COM_LANG4DEV_GALLERIES_REBUILD_FAILURE') . ': ' . $model->getError();
$msg .= Text::_('COM_LANG4DEV_GALLERIES_REBUILD_SUCCESS');
$msg .= Text::_('COM_LANG4DEV_GALLERIES_TABLE_RESET_ERROR') . ': ' . $model->getError();
$msg .= Text::_('COM_LANG4DEV_GALLERIES_TABLE_RESET_SUCCESS');
$msg .= Text::_('COM_LANG4DEV_IMAGES_TABLE_RESET_ERROR') . ': ' . $model->getError();
$msg .= Text::_('COM_LANG4DEV_IMAGES_TABLE_RESET_ERROR') ;
$msg .= Text::_('COM_LANG4DEV_IMAGES_TABLE_RESET_SUCCESS');
$name = $name ? $name : Text::_('COM_LANG4DEV_GALLERY_ID_ERROR');
$options[0]->text      = Text::_('JGLOBAL_ROOT_PARENT');  // COM_LANG4DEV_NO_PARENT
$outdated_Zone = new zoneContainer(Text::_('COM_LANG4DEV_OUTDATED_ZONE'), Text::_('COM_LANG4DEV_OUTDATED_ZONE_DESC'), 'outdated', 'outdatedZone');
$placeholder = Text::_('COM_LANG4DEV_SEARCH_GALLERIES_IMAGES');
$rawDatabase_Zone = new zoneContainer(Text::_('COM_LANG4DEV_RAW_DB_ZONE'), Text::_('COM_LANG4DEV_RAW_DB_ZONE_DESCRIPTION'), 'rawDb', 'rawDbZone');
$repair_Zone = new zoneContainer(Text::_('COM_LANG4DEV_REPAIR_ZONE'), Text::_('COM_LANG4DEV_FUNCTIONS_MAY_CHANGE_DATA'), 'repair', 'repairZone');
$this->setError(Text::_('COM_LANG4DEV_BATCH_CANNOT_CREATE'));
$this->setError(Text::_('COM_LANG4DEV_BATCH_CANNOT_EDIT'));
$this->setError(Text::_('COM_LANG4DEV_ERROR_UNIQUE_ALIAS'));
$this->setMessage(Text::_('COM_LANG4DEV_NO_IMAGE_SELECTED'), 'warning');
$this->setMessage(Text::plural('COM_LANG4DEV_N_ITEMS_DELETED', count($cids)));
$title = Text::_('COM_LANG4DEV_ABOUT') . ' ' . $Rsg2Version;
$title = Text::_('COM_LANG4DEV_CHANGELOG');
$title = Text::_('COM_LANG4DEV_CREDITS');
$title = Text::_('COM_LANG4DEV_EXTERNAL_LICENSES');
$title = Text::_('COM_LANG4DEV_GALLERY_BASE_' . ($isNew ? 'ADD' : 'EDIT') . '_TITLE');
$title = Text::sprintf('COM_LANG4DEV_GALLERY_' . ($isNew ? 'ADD' : 'EDIT')
$title = empty($title) ? Text::_('COM_LANG4DEV_SELECT_A_GALLERY') : htmlspecialchars($title, ENT_QUOTES, 'UTF-8');
$upgrade_Zone = new zoneContainer(Text::_('COM_LANG4DEV_UPGRADE_ZONE'), $upgrade_ZoneInfo, 'upgrade', 'upgradeZone');
$upgrade_ZoneInfo = Text::_('COM_LANG4DEV_J3X_RSG2_TABLES_NOT_EXISTING');
$upgrade_ZoneInfo = Text::_('COM_LANG4DEV_UPGRADE_ZONE_DESCRIPTION');
'<del>' . Text::_('COM_LANG4DEV_SLIDESHOWS_CONFIGURATION_DESC') . '</del>',
'<del>' . Text::_('COM_LANG4DEV_SLIDESHOW_CONFIGURATION') . '</del>',
'<del>' . Text::_('COM_LANG4DEV_TEMPLATES_CONFIGURATION_DESC') . '</del>',
'<del>' . Text::_('COM_LANG4DEV_TEMPLATE_CONFIGURATION') . '</del>',
'<span class="sidebar-item-title">' . Text::_('COM_LANG4DEV_MENU_CONTROL_PANEL') . '</span>',
'<span class="sidebar-item-title">' . Text::_('COM_LANG4DEV_MENU_DEVELOP') . '</span>',
'<span class="sidebar-item-title">' . Text::_('COM_LANG4DEV_MENU_GALLERIES') . '</span>',
'<span class="sidebar-item-title">' . Text::_('COM_LANG4DEV_MENU_IMAGES') . '</span>',
'<span class="sidebar-item-title">' . Text::_('COM_LANG4DEV_MENU_MAINTENANCE') . '</span>',
'<span class="sidebar-item-title">' . Text::_('COM_LANG4DEV_MENU_UPLOAD') . '</span>',
'text'   => Text::_('COM_LANG4DEV_MAIN_CONFIGURATION'),
'text'   => Text::_('COM_LANG4DEV_MAIN_MAINTENANCE'),
'text'   => Text::_('COM_LANG4DEV_MAIN_MANAGE_GALLERIES'),
'text'   => Text::_('COM_LANG4DEV_MAIN_MANAGE_IMAGES'),
'text'   => Text::_('COM_LANG4DEV_MAIN_UPLOAD'),
'title'       => Text::_('COM_LANG4DEV_EDIT_GALLERY'),
'title'       => Text::_('COM_LANG4DEV_NEW_GALLERY'),
'title'  => Text::_('COM_LANG4DEV_GALLERY_BATCH_OPTIONS'),
'title'  => Text::_('COM_LANG4DEV_IMAGES_BATCH_OPTIONS'),
* Factory::getApplication()->enqueueMessage(Text::_('COM_LANG4DEV_ERROR_ALL_LANGUAGE_ASSOCIATED'), 'notice');
->text('COM_LANG4DEV_ROTATE')
. ' title="' . HTMLHelper::tooltipText('COM_LANG4DEV_CHANGE_GALLERY') . '">'
. ' title="' . HTMLHelper::tooltipText('COM_LANG4DEV_EDIT_GALLERY') . '">'
. ' title="' . HTMLHelper::tooltipText('COM_LANG4DEV_NEW_GALLERY') . '">'
. ' title="' . Text::_('COM_LANG4DEV_JUMP_TO_DOCUMENTATION') . '" >www.rsg.../documentation</a>';
. ' title="' . Text::_('COM_LANG4DEV_JUMP_TO_FORUM') . '" >' . $keyTranslation . '</a></h3>';;
. ' title="' . Text::_('COM_LANG4DEV_JUMP_TO_FORUM') . '" >www.forum.rsgallery2.org</a>';
. ' title="' . Text::_('COM_LANG4DEV_JUMP_TO_FORUM') . '" >www.rsgallery2.org</a>';
. ' title="' . Text::_('COM_LANG4DEV_JUMP_TO_GNU_ORG') . '" >GNU GPL</a>';
. '" data-text="' . htmlspecialchars(Text::_('COM_LANG4DEV_SELECT_A_GALLERY', true), ENT_COMPAT, 'UTF-8') . '" value="' . $value . '">';
. '* ?  COM_LANG4DEV_REMOVE_INSTALLATION_LEFT_OVERS <br>'
. '* ? COM_LANG4DEV_DEBUG_GALLERY_ORDER <br>'
. '* ? COM_LANG4DEV_UPDATE_COMMENTS_AND_VOTING <br>'
. ': ' . Text::_('COM_LANG4DEV_CONFIGURATION_RAW_EDIT'), 'screwdriver');
. ': ' . Text::_('COM_LANG4DEV_CONFIGURATION_RAW_VIEW'), 'screwdriver');
. ': ' . Text::_('COM_LANG4DEV_DEV_INSTALL_MSG_TEXT'), 'screwdriver');
. ': ' . Text::_('COM_LANG4DEV_GENERAL_INFO_VIEW'), 'screwdriver');
. ': ' . Text::_('COM_LANG4DEV_MANIFEST_INFO_VIEW'), 'screwdriver');
. Text::_('COM_LANG4DEV_MEGABYTES_SET_IN_PHPINI')
. Text::_('COM_LANG4DEV_MOVE_J3X_IMAGES_USE_DESC') . '.&nbsp'
. Text::_('COM_LANG4DEV_MOVE_J3X_IMAGES_USE_DESC_B');
. Text::sprintf('COM_LANG4DEV_POST_MAX_SIZE_IS', $PostMaxSize)
. Text::sprintf('COM_LANG4DEV_POST_MEMORY_LIMIT_IS', $MemoryLimit)
. Text::sprintf('COM_LANG4DEV_UPLOAD_LIMIT_IS', $UploadLimit)
//                                . ' title="' . Text::_('COM_LANG4DEV_JUMP_TO_FORUM') . '" >' . $keyTranslation . '</a></h3>';;
//                               class="control-label"><?php echo Text::_('COM_LANG4DEV_ONE_GALLERY_MUST_EXIST'); ?></label>
//                $toolbar->appendButton( 'Link', 'upload', 'COM_LANG4DEV_SAVE_AND_GOTO_UPLOAD', $link);
//                echo '<h3>J3x ' . Text::_('COM_LANG4DEV_RAW_IMAGES_TXT') . '</h3>';
//              echo HTMLHelper::_('image', 'com_rsgallery2/RSG2_logo.big.png', Text::_('COM_LANG4DEV_MAIN_LOGO_ALT_TEXT'), null, true);
//            $app->enqueueMessage(Text::plural('COM_LANG4DEV_N_ITEMS_DELETED', $imgDeletedCount), 'notice');
//            $html[] = '      title="' . HtmlHelper::tooltipText('COM_LANG4DEV_WATERMARK_IMAGE_FOUND') . '" ';
//            $html[] = '      title="' . HtmlHelper::tooltipText('COM_LANG4DEV_WATERMARK_IMAGE_NOT_FOUND') . '" ';
//        $html[] = Text::_('COM_LANG4DEV_WATERMARK_BR_FOLDER');
//      echo Text::_('COM_LANG4DEV_INSTALL_TEXT');
//    '<del>' . Text::_('COM_LANG4DEV_MAINT_CONSOLIDATE_IMAGES') . '<del>',
//    '<del>' . Text::_('COM_LANG4DEV_MAINT_CONSOLIDATE_IMAGES_TXT') . '<del>',
//    Text::_('COM_LANG4DEV_GALLERIES_AS_TREE'),
//    Text::_('COM_LANG4DEV_GALLERIES_AS_TREE_DESC'),
//    Text::_('COM_LANG4DEV_GALLERIES_TABLE_RESET'),
//    Text::_('COM_LANG4DEV_GALLERIES_TABLE_RESET_DESC'),
//    [description] => COM_LANG4DEV_XML_DESCRIPTION
//    echo '            <strong >' . Text::_('COM_LANG4DEV_PLEASE_GOTO_CONFIGURATION') . '</strong>';
//  ToolBarHelper::custom ('MaintenanceJ3x.copySelectedJ3xConfig2J4xOptions','copy','','COM_LANG4DEV_COPY_SELECTED_J3X_CONFIGURATION', true);
// . ': ' . Text::_('COM_LANG4DEV_MAINT_PREPARED_NOT_READY')
// ToolBarHelper::custom ('gallery.save2upload','upload','','COM_LANG4DEV_SAVE_AND_GOTO_UPLOAD', false);
// ToolBarHelper::custom('MaintenanceJ3x.moveSelectedJ3xImages2J4x', 'copy', '', 'COM_LANG4DEV_MOVE_SELECTED_J3X_IMAGES', false);
// ToolBarHelper::title(Text::_('COM_LANG4DEV_MANAGE_MAINTENANCE'), 'cogs'); // 'maintenance');
// [$isRemoved_acl,       $msgTmp] = $this->PurgeTable('#__rsg2_acl', Text::_('COM_LANG4DEV_PURGED_TABLE_RSGALLERY2_ACL')) . '<br>';
// [$isRemoved_comments,  $msgTmp] = $this->PurgeTable('#__rsg2_comments', Text::_('COM_LANG4DEV_PURGED_TABLE_RSGALLERY2_COMMENTS')) . '<br>';
// [description] => COM_LANG4DEV_XML_DESCRIPTION
// echo Text::_('COM_LANG4DEV_MAINT_CONSOLDB_NO_MISSING_ITEMS_TXT');
// echo Text::_('COM_LANG4DEV_MAINT_PREPARED_NOT_READY_DESC');
// echo Text::_('COM_LANG4DEV_MANIFEST_INFO_VIEW');
// echo Text::_('COM_LANG4DEV_NAME') . $this->item->name;
// echo Text::_('COM_LANG4DEV_NO_NEW_GALLERIES');
// echo Text::_('COM_LANG4DEV_NO_NEW_IMAGES');
// echo Text::_('COM_LANG4DEV_UPDATE_TEXT');
// throw new \Exception(Text::_('COM_LANG4DEV_ERROR_RSGALLERY2_NOT_FOUND'), 404);
//$app->enqueueMessage(Text::_('COM_LANG4DEV_INVALID_GALLERY_ID'), 'error');
//$title = Text::_('COM_LANG4DEV_ABOUT') . ' <strong>' . $Rsg2Version . ' </strong>';
////              echo HTMLHelper::_('image', 'com_rsgallery2/RSG2_logo.big.png', Text::_('COM_LANG4DEV_MAIN_LOGO_ALT_TEXT'), null, true);
////    echo '            <span class="badge badge-pill badge-info">' . Text::_('COM_LANG4DEV_PLEASE_GOTO_CONFIGURATION') . '</span>';
////    echo HTMLHelper::_('image', 'com_rsgallery2/RSG2_logoText.svg', Text::_('COM_LANG4DEV_MAIN_LOGO_ALT_TEXT'), null, true);
//Log::add(Text::_('COM_LANG4DEV_UPDATE_TEXT'), Log::INFO, 'rsg2');
//ToolBarHelper::custom ('MaintenanceJ3x.copySelectedJ3xGalleries2J4x','undo','','COM_LANG4DEV_COPY_SELECTED_J3X_GALLERIES', true);
//ToolBarHelper::custom ('MaintenanceJ3x.copySelectedJ3xImages2J4x','undo','','COM_LANG4DEV_COPY_SELECTED_J3X_IMAGES', false);
//ToolBarHelper::custom ('copyoldconfig.recompare','upload','','COM_LANG4DEV_OLD_CONFIGURATION_RECOMPARE', true);
//ToolBarHelper::custom('MaintenanceJ3x.moveJ3xImages2J4x', 'copy', '', 'COM_LANG4DEV_MOVE_ALL_J3X_IMAGES', false);
//[$isRemoved, $msgTmp] = $this->PurgeTable('#__rsgallery2_cats', Text::_('COM_LANG4DEV_PURGED_TABLE_RSGALLERY2_CATS')) . '<br>';
//echo HTMLHelper::_('searchtools.sort', 'COM_LANG4DEV_NAME', 'a.name', $listDirn, $listOrder);
//echo Text::_('COM_LANG4DEV_UNINSTALL_TEXT');
<!--                            title="--><?php //echo Text::_('COM_LANG4DEV_ADD_IMAGES_PROPERTIES_DESC'); ?><!--"-->
<!--                        --><?php //echo Text::_('COM_LANG4DEV_ADD_IMAGES_PROPERTIES'); ?>
<!-- <field name="jpegQuality_01" type="text" label="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE" description="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE_DESC" class=""></field>-->
<!-- <field name="jpegQuality_02" type="text" label="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE" description="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE_DESC" class=""></field>-->
<!-- <field name="jpegQuality_03" type="text" label="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE" description="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE_DESC" class=""></field>-->
<!-- <field name="jpegQuality_04" type="text" label="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE" description="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE_DESC" class=""></field>-->
<!-- <field name="jpegQuality_05" type="text" label="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE" description="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE_DESC" class=""></field>-->
<!-- <field name="jpegQuality_06" type="text" label="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE" description="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE_DESC" class=""></field>-->
<!-- <field name="jpegQuality_07" type="text" label="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE" description="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE_DESC" class=""></field>-->
<!-- <field name="jpegQuality_08" type="text" label="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE" description="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE_DESC" class=""></field>-->
<!-- <field name="jpegQuality_09" type="text" label="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE" description="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE_DESC" class=""></field>-->
<!-- <option value="a.modified ASC">COM_LANG4DEV_MODIFIED_ASC</option>-->
<!-- <option value="a.modified DESC">COM_LANG4DEV_MODIFIED_DESC</option>-->
<!-- <option value="a.modified_by ASC">COM_LANG4DEV_MODIFIED_BY_ASC</option>-->
<!-- <option value="a.modified_by DESC">COM_LANG4DEV_MODIFIED_BY_DESC</option>-->
<!-- description="COM_LANG4DEV_MENU_SELECT_GALLERY_DESC"-->
<!-- label class="control-label" for="description2[]" ><?php echo Text::_('COM_LANG4DEV_DESCRIPTION'); ?></label>
<!-- label="COM_LANG4DEV_MENU_SELECT_GALLERY_LABEL"-->
<!-- menu link="option=com_rsgallery2">COM_LANG4DEV_MENU</menu -->
<!--legend><?php echo Text::_('COM_LANG4DEV_REFRESH_TEXT'); ?>XXXXX</legend-->
<!--legend><?php echo Text::_('COM_LANG4DEV_UPLOAD_BY_DRAG_AND_DROP_LABEL'); ?></legend-->
<!--legend><strong><?php echo Text::_('COM_LANG4DEV_MOVE_J3X_IMAGES'); ?></strong></legend-->
<!--menu view="rsgallery2">COM_LANG4DEV_MENU</menu-->
<![CDATA[COM_LANG4DEV_GALLERIES_VIEW_DEFAULT_DESC]]>
<![CDATA[COM_LANG4DEV_GALLERY_VIEW_EDIT_DESC]]>
<![CDATA[COM_LANG4DEV_MENU_GALLERIES_J3X_LEGACY_VIEW_DESC]]>
<![CDATA[COM_LANG4DEV_MENU_GALLERY_J3X_LEGACY_VIEW_DESC]]>
<![CDATA[COM_LANG4DEV_MENU_J3X_LEGACY_VIEW_DESC]]>
<![CDATA[COM_LANG4DEV_MENU_VIEW_GALLERIES_DESC]]>
<![CDATA[COM_LANG4DEV_MENU_VIEW_GALLERY_DESC]]>
<![CDATA[COM_LANG4DEV_MENU_VIEW_IMAGES_DESC]]>
<![CDATA[COM_LANG4DEV_MENU_VIEW_IMAGES_LATEST_DESC]]>
<![CDATA[COM_LANG4DEV_MENU_VIEW_LATEST_GALLERIES_DESC]]>
<![CDATA[COM_LANG4DEV_MENU_VIEW_SLIDESHOW_DESC]]>
<![CDATA[COM_LANG4DEV_MENU_VIEW_SLIDESHOW_J3X_DESC]]>
<![CDATA[COM_LANG4DEV_MENU_VIEW_SLIDE_PAGE_J3X_DESC]]>
<![CDATA[COM_LANG4DEV_RSGALLERY2_VIEW_DEFAULT_DESC]]>
<?php
<?php //echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'DbCopyJ3xConfig', Text::_('COM_LANG4DEV_DB_COPY_J3X_CONFIG', true)); ?>
<?php //echo HTMLHelper::_('uitab.addTab', 'myTab', 'dragAndDrop', Text::_('COM_LANG4DEV_DO_UPLOAD')); ?>
<?php echo "???" . Text::_('COM_LANG4DEV_J3X_IMAGES_DESELECT_BY_GALLERY'); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'CommentsTab', Text::_('COM_LANG4DEV_COMMENTS', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'ConfigRawView', Text::_('COM_LANG4DEV_CONFIG_MINUS_RAW_EDIT', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'ConfigRawView', Text::_('COM_LANG4DEV_CONFIG_MINUS_RAW_VIEW', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'DBTransferJ3xGalleries', Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_GALLERIES', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'DBTransferJ3xGalleries', Text::_('COM_LANG4DEV_GALLERIES_AS_TREE', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'DbTransferJ3xImages', Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_IMAGES', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'DescriptionTab', Text::_('COM_LANG4DEV_DESCRIPTION', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'ExtensionInfoView', Text::_('COM_LANG4DEV_EXTENSION_INFO_VIEW', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'ExxifInfoTab', Text::_('COM_LANG4DEV_EXIF', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'InstallMessage', Text::_('COM_LANG4DEV_DEVELOP_INSTALL_MSG_TEST', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'MaintConsolidateDb', Text::_('COM_LANG4DEV_IMAGES_LOST_AND_FOUND_TITLE', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'ManifestInfoView', Text::_('COM_LANG4DEV_MANIFEST_INFO_VIEW', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'MoveJ3xImages', Text::_('COM_LANG4DEV_MOVE_J3X_IMAGES', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'PreparedButNotReady', Text::_('COM_LANG4DEV_MAINT_PREPARED_NOT_READY', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'Rsg2GeneralInfoView', Text::_('COM_LANG4DEV_GENERAL_INFO_VIEW', true)); ?>
<?php echo HTMLHelper::_('bootstrap.addTab', 'myTab', 'VotingTab', Text::_('COM_LANG4DEV_VOTING', true)); ?>
<?php echo HTMLHelper::_('searchtools.sort', 'COM_LANG4DEV_COMMENTS', 'a.comments', $listDirn, $listOrder); ?>
<?php echo HTMLHelper::_('searchtools.sort', 'COM_LANG4DEV_DATE_CREATED', 'a.created', $listDirn, $listOrder); ?>
<?php echo HTMLHelper::_('searchtools.sort', 'COM_LANG4DEV_GALLERY', 'gallery_name', $listDirn, $listOrder); ?>
<?php echo HTMLHelper::_('searchtools.sort', 'COM_LANG4DEV_HEADING_ASSOCIATION', 'association', $listDirn, $listOrder); ?>
<?php echo HTMLHelper::_('searchtools.sort', 'COM_LANG4DEV_IMAGES', 'image_count', $listDirn, $listOrder); ?>
<?php echo HTMLHelper::_('searchtools.sort', 'COM_LANG4DEV_NAME', 'a.name', $listDirn, $listOrder); ?>
<?php echo HTMLHelper::_('searchtools.sort', 'COM_LANG4DEV_RATING', 'a.rating', $listDirn, $listOrder); ?>
<?php echo HTMLHelper::_('searchtools.sort', 'COM_LANG4DEV_TITLE', 'a.title', $listDirn, $listOrder); ?>
<?php echo HTMLHelper::_('searchtools.sort', 'COM_LANG4DEV_VOTES', 'a.votes', $listDirn, $listOrder); ?>
<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'general', Text::_('COM_LANG4DEV_GENERAL')); ?>
<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'publishing', Text::_('COM_LANG4DEV_FIELDSET_PUBLISHING')); ?>
<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'upload_gallery_must_exist', Text::_('COM_LANG4DEV_DO_UPLOAD')); ?>
<?php echo JText::_('COM_LANG4DEV_ADD_IMAGE_PROPERTIES'); ?>
<?php echo JText::_('COM_LANG4DEV_COPY'); ?>
<?php echo JText::_('COM_LANG4DEV_MOVE'); ?>
<?php echo Text::_('COM_LANG4DEV_ADD_IMAGES_PROPERTIES'); ?>
<?php echo Text::_('COM_LANG4DEV_CANCEL'); ?>
<?php echo Text::_('COM_LANG4DEV_COPY_TO_CLIPBOARD'); ?>
<?php echo Text::_('COM_LANG4DEV_CREATE_GALLERY'); ?>
<?php echo Text::_('COM_LANG4DEV_DRAG_IMAGES_HERE'); ?>
<?php echo Text::_('COM_LANG4DEV_FTP_FOLDER_UPLOAD'); ?>
<?php echo Text::_('COM_LANG4DEV_IMAGE'); ?>
<?php echo Text::_('COM_LANG4DEV_J3X_ALL_IMAGES_MOVED'); ?>
<?php echo Text::_('COM_LANG4DEV_J3X_IMAGES_MOVE_BY_GALLERY'); ?>
<?php echo Text::_('COM_LANG4DEV_J3X_IMAGES_SELECT_NEXT_100_GALLERIES'); ?>
<?php echo Text::_('COM_LANG4DEV_J3X_IMAGES_SELECT_NEXT_10_GALLERIES'); ?>
<?php echo Text::_('COM_LANG4DEV_J3X_IMAGES_SELECT_NEXT_GALLERY'); ?>
<?php echo Text::_('COM_LANG4DEV_J3X_RSG2_TABLES_NOT_EXISTING'); // JGLOBAL_NO_MATCHING_RESULTS ?>
<?php echo Text::_('COM_LANG4DEV_MEGABYTES_SET_IN_PHPINI'); ?>
<?php echo Text::_('COM_LANG4DEV_MOVE_ALL_J3X_IMAGES'); ?>
<?php echo Text::_('COM_LANG4DEV_MOVE_J3X_IMAGES_BY_GALLERIES_CHECK'); ?>
<?php echo Text::_('COM_LANG4DEV_NO_GALLERY_CREATED'); // JGLOBAL_NO_MATCHING_RESULTS ?>
<?php echo Text::_('COM_LANG4DEV_NO_IMAGES_SELECTED_FOR_VIEW'); ?>
<?php echo Text::_('COM_LANG4DEV_NO_IMAGE_UPLOADED'); // JGLOBAL_NO_MATCHING_RESULTS ?>
<?php echo Text::_('COM_LANG4DEV_ORDER'); ?>
<?php echo Text::_('COM_LANG4DEV_OWNER') . ': ' . $gallery->author_name ?>
<?php echo Text::_('COM_LANG4DEV_PROPERTIES_UPLOADED_IMAGES'); ?>
<?php echo Text::_('COM_LANG4DEV_REPEAT_CHECKING'); ?>
<?php echo Text::_('COM_LANG4DEV_SELECT_FILES'); ?>
<?php echo Text::_('COM_LANG4DEV_SELECT_ZIP_FILE'); ?>
<?php echo Text::_('COM_LANG4DEV_SUBGALLERIES') . ': ' ?>
<?php echo Text::_('COM_LANG4DEV_TABLE_CAPTION'); ?>
<?php echo Text::_('COM_LANG4DEV_TABLE_CAPTION'); ?>, <?php echo Text::_('JGLOBAL_SORTED_BY'); ?>
<?php echo Text::_('COM_LANG4DEV_UPLOADED') . ': ' . $image->created; ?>
<?php echo Text::_('COM_LANG4DEV_UPLOAD_BY_DRAG_AND_DROP_LABEL'); ?>
<?php echo Text::sprintf('COM_LANG4DEV_POST_MAX_SIZE_IS', $PostMaxSize) . ' ' . Text::_('COM_LANG4DEV_MEGABYTES_SET_IN_PHPINI'); ?>
<?php echo Text::sprintf('COM_LANG4DEV_POST_MAX_SIZE_IS', $PostMaxSize); ?>
<?php echo Text::sprintf('COM_LANG4DEV_POST_MEMORY_LIMIT_IS', $MemoryLimit) . ' ' . Text::_('COM_LANG4DEV_MEGABYTES_SET_IN_PHPINI'); ?>
<?php echo Text::sprintf('COM_LANG4DEV_POST_MEMORY_LIMIT_IS', $MemoryLimit); ?>
<?php echo Text::sprintf('COM_LANG4DEV_UPLOAD_LIMIT_IS', $UploadLimit) . ' ' . Text::_('COM_LANG4DEV_MEGABYTES_SET_IN_PHPINI'); ?>
<?php echo Text::sprintf('COM_LANG4DEV_UPLOAD_LIMIT_IS', $UploadLimit); ?>
<a class="badge <?php echo ($item->count_archived > 0) ? 'badge-info' : 'badge-secondary'; ?>" title="<?php echo Text::_('COM_LANG4DEV_COUNT_ARCHIVED_ITEMS'); ?>" href="<?php echo Route::_('index.php?option=' . $component . ($section ? '&view=' . $section : '') . '&filter[gallery_id]=' . (int) $item->id . '&filter[published]=2' . '&filter[level]=1'); ?>">
<a class="badge <?php echo ($item->count_published > 0) ? 'badge-success' : 'badge-secondary'; ?>" title="<?php echo Text::_('COM_LANG4DEV_COUNT_PUBLISHED_ITEMS'); ?>" href="<?php echo Route::_('index.php?option=' . $component . ($section ? '&view=' . $section : '') . '&filter[gallery_id]=' . (int) $item->id . '&filter[published]=1' . '&filter[level]=1'); ?>">
<a class="badge <?php echo ($item->count_trashed > 0) ? 'badge-inverse' : 'badge-secondary'; ?>" title="<?php echo Text::_('COM_LANG4DEV_COUNT_TRASHED_ITEMS'); ?>" href="<?php echo Route::_('index.php?option=' . $component . ($section ? '&view=' . $section : '') . '&filter[gallery_id]=' . (int) $item->id . '&filter[published]=-2' . '&filter[level]=1'); ?>">
<a class="badge <?php echo ($item->count_unpublished > 0) ? 'badge-danger' : 'badge-secondary'; ?>" title="<?php echo Text::_('COM_LANG4DEV_COUNT_UNPUBLISHED_ITEMS'); ?>" href="<?php echo Route::_('index.php?option=' . $component . ($section ? '&view=' . $section : '') . '&filter[gallery_id]=' . (int) $item->id . '&filter[published]=0' . '&filter[level]=1'); ?>">
<a class="btn <?php echo ($imageCount > 0) ? 'btn-success' : 'btn-secondary'; ?>" title="<?php echo Text::_('COM_LANG4DEV_IMAGES_IN_GALLERY_COUNT_CLICK_TO_VIEW_THEM'); ?>" href="<?php echo $link; ?>">
<action name="core.create" title="JACTION_CREATE" description="COM_LANG4DEV_ACCESS_ITEM_CREATE_DESC" />
<dashboard title="COM_LANG4DEV_MENU_DASHBOARD_XML" icon="icon-imaages">users</dashboard>
<description>COM_LANG4DEV_XML_DESCRIPTION</description>
<div><?php echo Text::_('COM_LANG4DEV_CREATED') . ': ' . $gallery->created; ?></div>
<div><?php echo Text::_('COM_LANG4DEV_SIZE') . ': ' . $gallery->image_count ?></div>
<fields name="params" label="COM_LANG4DEV_FIELD_BASIC_LABEL">
<fieldset name="latest_images" label="COM_LANG4DEV_MENU_J3X_LATEST_IMAGES_TAB">
<fieldset name="layout" label="COM_LANG4DEV_FIELD_LAYOUT_LABEL">
<fieldset name="layout1" label="COM_LANG4DEV_FIELD_LAYOUT_LABEL">
<fieldset name="layout2" label="COM_LANG4DEV_FIELD_LAYOUT_LABEL">
<fieldset name="layout_thumbs" label="COM_LANG4DEV_VIEW_GALLERIES_LAYOUT_THUMBS">
<fieldset name="layout_thumbs" label="COM_LANG4DEV_VIEW_IMAGES_LAYOUT_THUMBS">
<fieldset name="random_images" label="COM_LANG4DEV_MENU_J3X_RANDOM_IMAGES_TAB">
<fieldset name="root_galleries" label="COM_LANG4DEV_MENU_J3X_ROOT_GALLERIES_TAB">
<fieldset name="voting" label="COM_LANG4DEV_FIELD_VOTING_LABEL">
<h3><?php echo Text::_('COM_LANG4DEV_J3X_GALLERIES_MOVE_IMAGES_LIST'); ?></h3>
<h3><?php echo Text::_('COM_LANG4DEV_J3X_GALLERY_LIST'); ?></h3>
<h3><?php echo Text::_('COM_LANG4DEV_J3X_IMAGES_LIST'); ?></h3>
<label class="control-label" for="description[]" ><?php echo Text::_('COM_LANG4DEV_DESCRIPTION'); ?></label>
<label class="control-label" for="galleryID[]" ><?php echo Text::_('COM_LANG4DEV_GALLERY'); ?></label>
<label class="control-label" for="title[]"><?php echo Text::_('COM_LANG4DEV_TITLE'); ?></label>
<label for="ftp_upload_directory"><?php echo Text::_('COM_LANG4DEV_PATH'); ?>: </label>
<layout title="COM_LANG4DEV_GALLERIES_VIEW_DEFAULT_TITLE">
<layout title="COM_LANG4DEV_GALLERY_VIEW_EDIT_TITLE">
<layout title="COM_LANG4DEV_IMAGE_VIEW_EDIT_TITLE">
<layout title="COM_LANG4DEV_MENU_GALLERIES_J3X_LEGACY_VIEW_TITLE">
<layout title="COM_LANG4DEV_MENU_GALLERY_J3X_LEGACY_VIEW_TITLE">
<layout title="COM_LANG4DEV_MENU_J3X_LEGACY_VIEW_TITLE">
<layout title="COM_LANG4DEV_MENU_VIEW_GALLERIES_TITLE">
<layout title="COM_LANG4DEV_MENU_VIEW_GALLERY_TITLE">
<layout title="COM_LANG4DEV_MENU_VIEW_IMAGES_LATEST_TITLE">
<layout title="COM_LANG4DEV_MENU_VIEW_IMAGES_RANDOM_TITLE">
<layout title="COM_LANG4DEV_MENU_VIEW_IMAGES_TITLE">
<layout title="COM_LANG4DEV_MENU_VIEW_LATEST_GALLERIES_TITLE">
<layout title="COM_LANG4DEV_MENU_VIEW_SLIDESHOW_J3X_TITLE">
<layout title="COM_LANG4DEV_MENU_VIEW_SLIDESHOW_TITLE">
<layout title="COM_LANG4DEV_MENU_VIEW_SLIDE_PAGE_J3X_TITLE">
<layout title="COM_LANG4DEV_RSGALLERY2_VIEW_DEFAULT_TITLE">
<legend><strong><?php echo Text::_('COM_LANG4DEV_COMPARE_AND_COPY_J3X_CONFIG'); ?></strong></legend>
<legend><strong><?php echo Text::_('COM_LANG4DEV_CONFIG_MINUS_RAW_EDIT_TXT'); ?></strong></legend>
<legend><strong><?php echo Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_GALLERIES'); ?></strong></legend>
<legend><strong><?php echo Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_IMAGES'); ?></strong></legend>
<legend><strong><?php echo Text::_('COM_LANG4DEV_GALLERIES_AS_TREE_DESC'); ?></strong></legend>
<legend><strong><?php echo Text::_('COM_LANG4DEV_MAINT_PREPARED_NOT_READY_DESC'); ?></strong></legend>
<menu view="COM_LANG4DEV_MENU_CONFIG"
<menu view="COM_LANG4DEV_MENU_CONTROL_PANEL"
<menu view="COM_LANG4DEV_MENU_GALLERIES"
<menu view="COM_LANG4DEV_MENU_IMAGES"
<menu view="COM_LANG4DEV_MENU_MAINTENANCE"
<menu view="COM_LANG4DEV_MENU_UPLOAD"
<menu view="rsgallery2">COM_LANG4DEV_MENU
<menu-quicktask-title>COM_LANG4DEV_DASHBOARD_ADD_GALLERY</menu-quicktask-title>
<name>COM_LANG4DEV</name>
<option value="0">COM_LANG4DEV_CONFIG_GALLERIES_COLUMN_TYPE_AUTO</option>
<option value="0">COM_LANG4DEV_CONFIG_GALLERIES_ROW_TYPE_AUTO</option>
<option value="0">COM_LANG4DEV_CONFIG_IMAGES_COLUMN_TYPE_AUTO</option>
<option value="0">COM_LANG4DEV_CONFIG_IMAGES_ROW_TYPE_AUTO</option>
<option value="0">COM_LANG4DEV_MINUS_RANDOM_THUMBNAIL_MINUS</option>
<option value="0">COM_LANG4DEV_NEVER</option>
<option value="0">COM_LANG4DEV_PROPORTIONAL</option>
<option value="1">COM_LANG4DEV_CONFIG_DESC_POS_ABOVE_THUMB</option>
<option value="1">COM_LANG4DEV_CONFIG_GALLERIES_COLUMN_TYPE_COUNT</option>
<option value="1">COM_LANG4DEV_CONFIG_GALLERIES_ROW_TYPE_ROW_COUNT</option>
<option value="1">COM_LANG4DEV_CONFIG_GALLERY_INTRO_ABOVE </option>
<option value="1">COM_LANG4DEV_CONFIG_IMAGES_COLUMN_TYPE_COUNT</option>
<option value="1">COM_LANG4DEV_CONFIG_IMAGES_ROW_TYPE_ROW_COUNT</option>
<option value="1">COM_LANG4DEV_CONFIG_TITLE_POS_ABOVE_THUMB</option>
<option value="1">COM_LANG4DEV_IF_MORE_GALLERIES_THAN_LIMIT</option>
<option value="1">COM_LANG4DEV_SQUARE</option>
<option value="2">COM_LANG4DEV_ALWAYS</option>
<option value="2">COM_LANG4DEV_CONFIG_DESC_POS_RIGHT_BESIDE_THUMB</option>
<option value="2">COM_LANG4DEV_CONFIG_GALLERIES_ROW_TYPE_MAX_COUNT</option>
<option value="2">COM_LANG4DEV_CONFIG_GALLERY_INTRO_RIGHT</option>
<option value="2">COM_LANG4DEV_CONFIG_IMAGES_ROW_TYPE_MAX_COUNT</option>
<option value="2">COM_LANG4DEV_CONFIG_TITLE_POS_BELOW_THUMB</option>
<option value="3">COM_LANG4DEV_CONFIG_DESC_POS_BELOW_THUMB</option>
<option value="3">COM_LANG4DEV_CONFIG_GALLERY_INTRO_BELOW</option>
<option value="3">COM_LANG4DEV_CONFIG_TITLE_POS_ABOVE_DESC</option>
<option value="4">COM_LANG4DEV_CONFIG_DESC_POS__BESIDE_THUMB</option>
<option value="4">COM_LANG4DEV_CONFIG_GALLERY_INTRO_LEFT</option>
<option value="4">COM_LANG4DEV_CONFIG_TITLE_POS_BELOW_DESC</option>
<option value="a.gallery_id ASC">COM_LANG4DEV_GALLERY_ID_ASC</option>
<option value="a.gallery_id DESC">COM_LANG4DEV_GALLERY_ID_DESC</option>
<option value="a.name ASC">COM_LANG4DEV_NAME_ASC</option>
<option value="a.name DESC">COM_LANG4DEV_NAME_DESC</option>
<option value="gallery_name ASC">COM_LANG4DEV_GALLERY_NAME_ASC</option>
<option value="gallery_name DESC">COM_LANG4DEV_GALLERY_NAME_ASC</option>
<option value="image_count ASC">COM_LANG4DEV_IMAGES_COUNT_ASC</option>
<option value="image_count DESC">COM_LANG4DEV_IMAGES_COUNT_DESC</option>
<p><h3><?php echo Text::_('COM_LANG4DEV_CONFIG_MINUS_RAW_VIEW'); ?></h3></p>
<p><h3><?php echo Text::_('COM_LANG4DEV_EXTENSION_INFO_VIEW'); ?></h3></p>
<p><h3><?php echo Text::_('COM_LANG4DEV_MANIFEST_INFO_VIEW'); ?></h3></p>
<span class="icon-archive hasTooltip" aria-hidden="true" title="<?php echo Text::_('COM_LANG4DEV_COUNT_ARCHIVED_ITEMS'); ?>"></span>
<span class="icon-publish hasTooltip" aria-hidden="true" title="<?php echo Text::_('COM_LANG4DEV_COUNT_PUBLISHED_ITEMS'); ?>"></span>
<span class="icon-trash hasTooltip" aria-hidden="true" title="<?php echo Text::_('COM_LANG4DEV_COUNT_TRASHED_ITEMS'); ?>"></span>
<span class="icon-unpublish hasTooltip" aria-hidden="true" title="<?php echo Text::_('COM_LANG4DEV_COUNT_UNPUBLISHED_ITEMS'); ?>"></span>
<span class="sr-only"><?php echo Text::_('COM_LANG4DEV_COUNT_ARCHIVED_ITEMS'); ?></span>
<span class="sr-only"><?php echo Text::_('COM_LANG4DEV_COUNT_PUBLISHED_ITEMS'); ?></span>
<span class="sr-only"><?php echo Text::_('COM_LANG4DEV_COUNT_TRASHED_ITEMS'); ?></span>
<span class="sr-only"><?php echo Text::_('COM_LANG4DEV_COUNT_UNPUBLISHED_ITEMS'); ?></span>
<strong><?php echo ' ' . Text::_('COM_LANG4DEV_HITS', true) . ' ' . $image->hits; ?></strong>
<strong><?php echo Text::_('COM_LANG4DEV_MAINT_CONSOLDB_TXT'); ?></strong>
Factory::getApplication()->enqueueMessage(Text::_('COM_LANG4DEV_ERROR_ALL_LANGUAGE_ASSOCIATED'), 'notice');
Log::add(Text::_('COM_LANG4DEV_INSTALLERSCRIPT_INSTALL'), Log::INFO, 'rsg2');
Log::add(Text::_('COM_LANG4DEV_INSTALLERSCRIPT_POSTFLIGHT') . ' >' . $type, Log::INFO, 'rsg2');
Log::add(Text::_('COM_LANG4DEV_INSTALLERSCRIPT_PREFLIGHT') . ' >' . $type, Log::INFO, 'rsg2');
Log::add(Text::_('COM_LANG4DEV_INSTALLERSCRIPT_UNINSTALL'), Log::INFO, 'rsg2');
Log::add(Text::_('COM_LANG4DEV_INSTALLERSCRIPT_UPDATE'), Log::INFO, 'rsg2');
Text::_('COM_LANG4DEV_ACLS_LIST'),
Text::_('COM_LANG4DEV_APPLY_EXISTING_J3X_DATA'),
Text::_('COM_LANG4DEV_APPLY_EXISTING_J3X_DATA_DESC'),
Text::_('COM_LANG4DEV_CHECK_IMAGE_PATHS'),
Text::_('COM_LANG4DEV_CHECK_IMAGE_PATHS_DESC'),
Text::_('COM_LANG4DEV_CHECK_IMAGE_PATHS_J3X'),
Text::_('COM_LANG4DEV_CHECK_IMAGE_PATHS_J3X_DESC'),
Text::_('COM_LANG4DEV_COLLECT_RSG2_INFO'),
Text::_('COM_LANG4DEV_COLLECT_RSG2_INFO_DESC'),
Text::_('COM_LANG4DEV_COMMENTS_LIST'),
Text::_('COM_LANG4DEV_CONFIGURATION_RAW_EDIT'),
Text::_('COM_LANG4DEV_CONFIGURATION_VARIABLES'),
Text::_('COM_LANG4DEV_CONFIG_MINUS_RAW_EDIT_TXT'),
Text::_('COM_LANG4DEV_CONFIG_MINUS_VIEW_TXT') . '                        ',
Text::_('COM_LANG4DEV_CONFIG_READ_FROM_FILE'),
Text::_('COM_LANG4DEV_CONFIG_READ_FROM_FILE_DESC'),
Text::_('COM_LANG4DEV_CONFIG_RESET_TO_DEFAULT') . '</del>',
Text::_('COM_LANG4DEV_CONFIG_RESET_TO_DEFAULT_DESC'),
Text::_('COM_LANG4DEV_CONFIG_SAVE_TO_FILE'),
Text::_('COM_LANG4DEV_CONFIG_SAVE_TO_FILE_DESC'),
Text::_('COM_LANG4DEV_DB_COPY_J3X_CONFIG'),
Text::_('COM_LANG4DEV_DB_COPY_J3X_CONFIG_DESC'),
Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_GALLERIES'),
Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_GALLERIES_DESC'),
Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_IMAGES'),
Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_IMAGES_DESC'),
Text::_('COM_LANG4DEV_DEVELOP_CREATE_GALLERIES'),
Text::_('COM_LANG4DEV_DEVELOP_CREATE_GALLERIES_DESC'),
Text::_('COM_LANG4DEV_DEVELOP_CREATE_IMAGES'),
Text::_('COM_LANG4DEV_DEVELOP_CREATE_IMAGES_DESC'),
Text::_('COM_LANG4DEV_DEVELOP_VIEW'),
Text::_('COM_LANG4DEV_DEVELOP_VIEW_DESC'),
Text::_('COM_LANG4DEV_GALLERIES_AS_TREE'),
Text::_('COM_LANG4DEV_GALLERIES_AS_TREE_DESC'),
Text::_('COM_LANG4DEV_GALLERIES_LIST'),
Text::_('COM_LANG4DEV_GALLERIES_TABLE_RESET'),
Text::_('COM_LANG4DEV_GALLERIES_TABLE_RESET_DESC'),
Text::_('COM_LANG4DEV_IMAGES_LIST'),
Text::_('COM_LANG4DEV_IMAGES_TABLE_RESET'),
Text::_('COM_LANG4DEV_IMAGES_TABLE_RESET_DESC'),
Text::_('COM_LANG4DEV_MAINT_CONSOLIDATE_IMAGES'),
Text::_('COM_LANG4DEV_MAINT_CONSOLIDATE_IMAGES_TXT'),
Text::_('COM_LANG4DEV_MANIFEST_INFO'),
Text::_('COM_LANG4DEV_MANIFEST_INFO_DESC'),
Text::_('COM_LANG4DEV_MENU_CONFIG'),
Text::_('COM_LANG4DEV_MOVE_J3X_IMAGES'),
Text::_('COM_LANG4DEV_MOVE_J3X_IMAGES_DESC'),
Text::_('COM_LANG4DEV_PREPARE_REMOVE_RSGALLERY2'),
Text::_('COM_LANG4DEV_PREPARE_REMOVE_RSGALLERY2_DESC'),
Text::_('COM_LANG4DEV_PURGE_DATA_AND_IMAGES'),
Text::_('COM_LANG4DEV_PURGE_DATA_AND_IMAGES_DESC'),
Text::_('COM_LANG4DEV_RAW_ACLS_TXT'),
Text::_('COM_LANG4DEV_RAW_COMMENTS_TXT'),
Text::_('COM_LANG4DEV_RAW_GALLERIES_TXT'),
Text::_('COM_LANG4DEV_RAW_IMAGES_TXT'),
Text::_('COM_LANG4DEV_REBUILD_GALLERY_ORDER'),
Text::_('COM_LANG4DEV_REBUILD_GALLERY_ORDER_DESC'),
Text::_('COM_LANG4DEV_REPAIR_IMAGE_PATHS'),
Text::_('COM_LANG4DEV_REPAIR_IMAGE_PATHS_DESC'),
Text::_('COM_LANG4DEV_REPAIR_IMAGE_PATHS_J3X'),
Text::_('COM_LANG4DEV_REPAIR_IMAGE_PATHS_J3X_DESC'),
Text::_('COM_LANG4DEV_UNDO_PREPARE_REMOVE_RSGALLERY2'),
Text::_('COM_LANG4DEV_UNDO_PREPARE_REMOVE_RSGALLERY2_DESC'),
Text::script('COM_LANG4DEV_PLEASE_CHOOSE_A_GALLERY_FIRST', true);
ToolBarHelper::custom ('gallery.save2upload','upload','','COM_LANG4DEV_SAVE_AND_GOTO_UPLOAD', false);
ToolBarHelper::custom('MaintConsolidateDb.assignParentGallery', 'images', '', 'COM_LANG4DEV_ASSIGN_SELECTED_GALLERY', true);
ToolBarHelper::custom('MaintConsolidateDb.createImageDbItems', 'database', '', 'COM_LANG4DEV_CREATE_DATABASE_ENTRIES', true);
ToolBarHelper::custom('MaintConsolidateDb.createMissingImages', 'image', '', 'COM_LANG4DEV_CREATE_MISSING_IMAGES', true);
ToolBarHelper::custom('MaintConsolidateDb.createWatermarkImages', 'scissors', '', 'COM_LANG4DEV_CREATE_MISSING_WATERMARKS', true);
ToolBarHelper::custom('MaintConsolidateDb.deleteRowItems', 'delete', '', 'COM_LANG4DEV_DELETE_SUPERFLOUS_ITEMS', true);
ToolBarHelper::custom('MaintConsolidateDb.repairAllIssuesItems', 'refresh', '', 'COM_LANG4DEV_REPAIR_ALL_ISSUES', true);
ToolBarHelper::custom('MaintenanceJ3x.copyDbJ3xGalleries2J4x', 'copy', '', 'COM_LANG4DEV_COPY_COMPLETE_J3X_GALLERIES', false);
ToolBarHelper::custom('MaintenanceJ3x.copyDbJ3xImages2J4x', 'copy', '', 'COM_LANG4DEV_COPY_ALL_J3X_IMAGES', false);
ToolBarHelper::custom('MaintenanceJ3x.copyJ3xConfig2J4xOptions', 'copy', '', 'COM_LANG4DEV_COPY_COMPLETE_J3X_CONFIGURATION', false);
ToolBarHelper::custom('MaintenanceJ3x.updateMovedJ3xImages2J4x', 'copy', '', 'COM_LANG4DEV_CHECK_MOVED_J3X_IMAGES', false);
ToolBarHelper::custom('imagesProperties.PropertiesView', 'next', 'next', 'COM_LANG4DEV_ADD_IMAGE_PROPERTIES', true);
ToolBarHelper::title(Text::_('COM_LANG4DEV_ADD_IMAGES_PROPERTIES', 'image'));
ToolBarHelper::title(Text::_('COM_LANG4DEV_DB_COPY_J3X_CONFIG'), 'screwdriver');
ToolBarHelper::title(Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_GALLERIES'), 'screwdriver');
ToolBarHelper::title(Text::_('COM_LANG4DEV_DB_TRANSFER_J3X_IMAGES'), 'screwdriver');
ToolBarHelper::title(Text::_('COM_LANG4DEV_DEVELOP')
ToolBarHelper::title(Text::_('COM_LANG4DEV_DEVELOP') . ' create galleries');
ToolBarHelper::title(Text::_('COM_LANG4DEV_DEVELOP') . ' create images');
ToolBarHelper::title(Text::_('COM_LANG4DEV_DEVELOP_VIEW'), 'cube'); // 'maintenance');
ToolBarHelper::title(Text::_('COM_LANG4DEV_DO_UPLOAD'), 'upload');
ToolBarHelper::title(Text::_('COM_LANG4DEV_EDIT_GALLERY', 'images'));
ToolBarHelper::title(Text::_('COM_LANG4DEV_EDIT_IMAGE', 'image'));
ToolBarHelper::title(Text::_('COM_LANG4DEV_GALLERIES_AS_TREE'), 'images');
ToolBarHelper::title(Text::_('COM_LANG4DEV_GALLERIES_VIEW_RAW_DATA'), 'images');
ToolBarHelper::title(Text::_('COM_LANG4DEV_IMAGES_VIEW_RAW_DATA'), 'image');
ToolBarHelper::title(Text::_('COM_LANG4DEV_MAINTENANCE')
ToolBarHelper::title(Text::_('COM_LANG4DEV_MAINT_CONSOLIDATE_IMAGE_DATABASE'), 'icon-database icon-checkbox-checked');
ToolBarHelper::title(Text::_('COM_LANG4DEV_MANAGE_GALLERIES'), 'images');
ToolBarHelper::title(Text::_('COM_LANG4DEV_MANAGE_IMAGES'), 'image');
ToolBarHelper::title(Text::_('COM_LANG4DEV_MANAGE_MAINTENANCE'), 'cogs'); // 'maintenance');
ToolBarHelper::title(Text::_('COM_LANG4DEV_MOVE_J3X_IMAGES'), 'screwdriver');
ToolBarHelper::title(Text::_('COM_LANG4DEV_SUBMENU_CONTROL_PANEL'), 'home-2');
[$isRemoved, $msgTmp] = $this->PurgeTable('#__rsgallery2_acl', Text::_('COM_LANG4DEV_PURGED_TABLE_RSGALLERY2_ACL')) . '<br>';
[$isRemoved, $msgTmp] = $this->PurgeTable('#__rsgallery2_comments', Text::_('COM_LANG4DEV_PURGED_TABLE_RSGALLERY2_COMMENTS')) . '<br>';
[$isRemoved, $msgTmp] = $this->PurgeTable('#__rsgallery2_config', Text::_('COM_LANG4DEV_PURGED_TABLE_RSGALLERY2_CONFIG')) . '<br>';
[$isRemoved, $msgTmp] = $this->PurgeTable('#__rsgallery2_files', Text::_('COM_LANG4DEV_PURGED_IMAGE_ENTRIES_FROM_DATABASE')) . '<br>';
[$isRemoved, $msgTmp] = $this->PurgeTable('#__rsgallery2_galleries', Text::_('COM_LANG4DEV_PURGED_GALLERIES_FROM_DATABASE')) . '<br>';
[$isRemoved_galleries, $msgTmp] = $this->PurgeTable('#__rsg2_galleries', Text::_('COM_LANG4DEV_PURGED_GALLERIES_FROM_DATABASE')) . '<br>';
[$isRemoved_images,    $msgTmp] = $this->PurgeTable('#__rsg2_images', Text::_('COM_LANG4DEV_PURGED_IMAGE_ENTRIES_FROM_DATABASE')) . '<br>';
array_unshift($options, HTMLHelper::_('select.option', '0', Text::_('COM_LANG4DEV_J3X_MENU_GALLERIES_OVERVIEW')));
array_unshift($options, HTMLHelper::_('select.option', '0', Text::_('COM_LANG4DEV_MENU_GALLERIES_OVERVIEW')));
array_unshift($options, HTMLHelper::_('select.option', '0', Text::_('COM_LANG4DEV_SELECT_GALLERY')));
class="control-label"><?php echo Text::_('COM_LANG4DEV_ONE_GALLERY_MUST_EXIST'); ?></label>
data-content="COM_LANG4DEV_COMMENTS_FIELD_COMMENT_DESC"
data-content="COM_LANG4DEV_YOUR_NAME_DESC"
description="COM_LANG4DEV_ACCESS_COMPONENT_COMMENT_DESC"/>
description="COM_LANG4DEV_ACCESS_COMPONENT_CREATE_OWN_DESC"/>
description="COM_LANG4DEV_ACCESS_COMPONENT_DELETE_OWN_DESC"/>
description="COM_LANG4DEV_ACCESS_COMPONENT_EDIT_STATE_OWN_DESC"/>
description="COM_LANG4DEV_ACCESS_COMPONENT_VOTE_DESC"/>
description="COM_LANG4DEV_ACCESS_GALLERY_COMMENT_DESC"/>
description="COM_LANG4DEV_ACCESS_GALLERY_CREATE_DESC"/>
description="COM_LANG4DEV_ACCESS_GALLERY_CREATE_OWN_DESC"/>
description="COM_LANG4DEV_ACCESS_GALLERY_DELETE_DESC"/>
description="COM_LANG4DEV_ACCESS_GALLERY_DELETE_OWN_DESC"/>
description="COM_LANG4DEV_ACCESS_GALLERY_EDITOWN_DESC"/>
description="COM_LANG4DEV_ACCESS_GALLERY_EDITSTATE_DESC"/>
description="COM_LANG4DEV_ACCESS_GALLERY_EDIT_DESC"/>
description="COM_LANG4DEV_ACCESS_GALLERY_EDIT_STATE_OWN_DESC"/>
description="COM_LANG4DEV_ACCESS_GALLERY_VOTE_DESC"/>
description="COM_LANG4DEV_ACCESS_ITEM_DELETE_DESC"/>
description="COM_LANG4DEV_ACCESS_ITEM_DELETE_OWN_DESC"/>
description="COM_LANG4DEV_ACCESS_ITEM_EDITOWN_DESC"/>
description="COM_LANG4DEV_ACCESS_ITEM_EDITSTATE_DESC"/>
description="COM_LANG4DEV_ACCESS_ITEM_EDIT_DESC"/>
description="COM_LANG4DEV_ACCESS_ITEM_EDIT_STATE_OWN_DESC"/>
description="COM_LANG4DEV_ADVANCED_SEF_DESC"
description="COM_LANG4DEV_ALLOWED_FILETYPES_DESC"
description="COM_LANG4DEV_CFG_IMAGES_LOCATION_DESC"
description="COM_LANG4DEV_CFG_IMAGE_MANIPULATION"
description="COM_LANG4DEV_CFG_ROOT_LOCATION_DESC"
description="COM_LANG4DEV_CFG_UPLOAD_GENERAL_DESC"
description="COM_LANG4DEV_CONFIG_GALLERIES_COLUMNS_DESC"
description="COM_LANG4DEV_CONFIG_GALLERIES_COLUMN_TYPE_DESC"
description="COM_LANG4DEV_CONFIG_GALLERIES_MAX_ROWS_DESC"
description="COM_LANG4DEV_CONFIG_GALLERIES_MAX_THUMBS_DESC"
description="COM_LANG4DEV_CONFIG_GALLERIES_ROW_TYPE_AUTO"
description="COM_LANG4DEV_CONFIG_GALLERIES_ROW_TYPE_DESC"
description="COM_LANG4DEV_CONFIG_GALLERIES_SHOW_DESC_DESC"
description="COM_LANG4DEV_CONFIG_GALLERIES_SHOW_SEARCH_DESC"
description="COM_LANG4DEV_CONFIG_GALLERIES_SHOW_TITLE_DESC"
description="COM_LANG4DEV_CONFIG_GALLERY_SHOW_INTRO_DESC"
description="COM_LANG4DEV_CONFIG_IMAGES_COLUMNS_DESC"
description="COM_LANG4DEV_CONFIG_IMAGES_COLUMN_TYPE_DESC"
description="COM_LANG4DEV_CONFIG_IMAGES_MAX_ROWS_DESC"
description="COM_LANG4DEV_CONFIG_IMAGES_MAX_THUMBS_DESC"
description="COM_LANG4DEV_CONFIG_IMAGES_ROW_TYPE_DESC"
description="COM_LANG4DEV_CONFIG_IMAGES_SHOW_DESC_DESC"
description="COM_LANG4DEV_CONFIG_IMAGES_SHOW_SEARCH_DESC"
description="COM_LANG4DEV_CONFIG_IMAGES_SHOW_TITLE_DESC"
description="COM_LANG4DEV_CONFIG_NOTE_GALLERIES_LAYOUT_DESC"
description="COM_LANG4DEV_CONFIG_NOTE_IMAGES_LAYOUT_DESC"
description="COM_LANG4DEV_CONFIG_TAB_DEBUG_DESC"
description="COM_LANG4DEV_CONFIG_TAB_GALLERIES_DESC"
description="COM_LANG4DEV_CONFIG_TAB_GENERAL_DESC"
description="COM_LANG4DEV_CONFIG_TAB_IMAGES_DESC"
description="COM_LANG4DEV_CONFIG_TAB_J3X_LEGACY_LAYOUT_DESC"
description="COM_LANG4DEV_CONFIG_TAB_UPLOAD_DESC"
description="COM_LANG4DEV_DEBUG_BACKEND_DESC"
description="COM_LANG4DEV_DEBUG_SITE_DESC"
description="COM_LANG4DEV_DEFAULT_NUMBER_OF_GALLERIES_ON_ROOT_VIEW_DESC"
description="COM_LANG4DEV_DEVELOP_DESC"
description="COM_LANG4DEV_DISPLAY_LATEST_DESC"
description="COM_LANG4DEV_DISPLAY_PICTURE_SIZE_DESC"
description="COM_LANG4DEV_DISPLAY_PICTURE_SIZE_J3X_DESC"
description="COM_LANG4DEV_DISPLAY_RANDOM_DESC"
description="COM_LANG4DEV_FIELD_PARENT_GALLERY_DESC"
description="COM_LANG4DEV_FIELD_SORT_ORDER_DESC"
description="COM_LANG4DEV_FILTER_GALLERY_NAME_DESC"
description="COM_LANG4DEV_FTP_PATH_DESC"
description="COM_LANG4DEV_GALLERIES_FILTER_SEARCH_DESC"
description="COM_LANG4DEV_GALLERIES_INTRODUCTION_DESC"
description="COM_LANG4DEV_GALLERY_FIELD_THUMBNAIL_DESC"
description="COM_LANG4DEV_IMAGES_FILTER_SEARCH_DESC"
description="COM_LANG4DEV_IMAGE_PATH_J3X_DEPRECATED_DESC"
description="COM_LANG4DEV_INTRODUCTION_DESC"
description="COM_LANG4DEV_J3X_DISPLAY_GALLERY_LIMITBOX_DESC"
description="COM_LANG4DEV_J3X_INTRODUCTION_DESC"
description="COM_LANG4DEV_J3X_NUMBER_OF_GALLERIES_ON_FRONTPAGE_DESC"
description="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE_DESC"
description="COM_LANG4DEV_KEEP_ORIGINAL_IMAGE_DESC"
description="COM_LANG4DEV_LIMIT_GALLERIES_THUMBS_DESC"
description="COM_LANG4DEV_LIMIT_IMAGES_IN_GALLERY_DESC"
description="COM_LANG4DEV_MENU_GALLERY_SHOW_SEARCH_DESC"
description="COM_LANG4DEV_MENU_IMAGES_SELECT_GALLERY_DESC"
description="COM_LANG4DEV_MENU_J3X_GALLERIES_DESCRIPTION_SIDE_DESC"
description="COM_LANG4DEV_MENU_J3X_GALLERIES_DISPLAY_SLIDESHOW_DESC"
description="COM_LANG4DEV_MENU_J3X_GALLERIES_SHOW_DATE_DESC"
description="COM_LANG4DEV_MENU_J3X_GALLERIES_SHOW_DESCRIPTION_DESC"
description="COM_LANG4DEV_MENU_J3X_GALLERIES_SHOW_OWNER_DESC"
description="COM_LANG4DEV_MENU_J3X_GALLERIES_SHOW_PRE_LABEL_DESC"
description="COM_LANG4DEV_MENU_J3X_GALLERIES_SHOW_SIZE_DESC"
description="COM_LANG4DEV_MENU_J3X_GALLERIES_SHOW_TITLE_DESC"
description="COM_LANG4DEV_MENU_J3X_GALLERY_DISPLAY_SLIDESHOW_DESC"
description="COM_LANG4DEV_MENU_J3X_GALLERY_SHOW_DESCRIPTION_DESC"
description="COM_LANG4DEV_MENU_J3X_GALLERY_SHOW_TITLE_DESC"
description="COM_LANG4DEV_MENU_J3X_SELECT_GALLERY_LABEL_DESC"
description="COM_LANG4DEV_MENU_ROOT_GALLERIES_SHOW_SEARCH_DESC"
description="COM_LANG4DEV_MENU_SELECT_GALLERY_DESC"
description="COM_LANG4DEV_ONE_GALLERY_FOR_ALL_IMAGES_DESC"
description="COM_LANG4DEV_PRESELECT_LATEST_GALLERY_DESC"
description="COM_LANG4DEV_PREVIOUS_VERSION_DESC"
description="COM_LANG4DEV_SPECIFY_GALLERY_DESC"
description="COM_LANG4DEV_SPECIFY_GALLERY_NAME_FOR_PARENT_GALLERY_DESC"
description="COM_LANG4DEV_THUMBNAIL_SIZE_DESC"
description="COM_LANG4DEV_THUMBNAIL_STYLE_DESC"
description="COM_LANG4DEV_USE_J3X_PATHS_DESC"
echo  Text::_("COM_LANG4DEV_SELECT_ALL");
echo '                        ' . Text::_('COM_LANG4DEV_GALLERIES');
echo '                        ' . Text::_('COM_LANG4DEV_IMAGES');
echo '            <th>' . Text::_('COM_LANG4DEV_DATE') . '</th>';
echo '            <th>' . Text::_('COM_LANG4DEV_FILENAME') . '</th>';
echo '            <th>' . Text::_('COM_LANG4DEV_GALLERY') . '</th>';
echo '            <th>' . Text::_('COM_LANG4DEV_ID') . '</th>';
echo '            <th>' . Text::_('COM_LANG4DEV_USER') . '</th>';
echo '       ' . Text::_('COM_LANG4DEV_CFG_J3X_ASSISTED');
echo '       ' . Text::_('COM_LANG4DEV_CFG_J3X_MERGE_1TO1');
echo '       ' . Text::_('COM_LANG4DEV_CFG_J3X_UNTOUCHED');
echo '       ' . Text::_('COM_LANG4DEV_CFG_J4X_UNTOUCHED');
echo '    <caption>' . Text::_('COM_LANG4DEV_MOST_RECENTLY_ADDED_GALLERIES') . '</caption>';
echo '    <caption>' . Text::_('COM_LANG4DEV_MOST_RECENTLY_ADDED_ITEMS') . '</caption>';
echo '<h3>' . 'J4x ' . Text::_('COM_LANG4DEV_GALLERIES_LIST') . '</h3>';
echo '<h3>' . 'J4x ' . Text::_('COM_LANG4DEV_IMAGES_LIST') . '</h3>';
echo '<h3>J3x ' . Text::_('COM_LANG4DEV_GALLERIES_AS_TREE') . '</h3>';
echo '<h3>J3x ' . Text::_('COM_LANG4DEV_RAW_GALLERIES_TXT') . '</h3>';
echo '<p><h3>' . Text::_('COM_LANG4DEV_CONFIGURATION_3x') . '</h3></p>';
echo '<p><h3>' . Text::_('COM_LANG4DEV_CONFIG_MINUS_RAW_VIEW') . '</h3></p>';
echo '<p><h3>' . Text::_('COM_LANG4DEV_MANIFEST_INFO_VIEW') . '</h3></p>';
echo HTMLHelper::_('image', 'com_rsgallery2/RSG2_logoText.svg', Text::_('COM_LANG4DEV_MAIN_LOGO_ALT_TEXT'), null, true);
echo Text::_('COM_LANG4DEV_MOVE_J3X_IMAGES_USE') . '.&nbsp'
echo Text::_('COM_LANG4DEV_NO_INCONSISTENCIES_IN_DATABASE');
label="*??COM_LANG4DEV_CFG_GALLERIES_VIEW_LABEL"
label="COM_LANG4DEV_ADVANCED_SEF"
label="COM_LANG4DEV_ALLOWED_FILETYPES"
label="COM_LANG4DEV_CFG_IMAGES_LOCATION"
label="COM_LANG4DEV_CFG_IMAGE_MANIPULATION"
label="COM_LANG4DEV_CFG_ROOT_LOCATION"
label="COM_LANG4DEV_CFG_UPLOAD_GENERAL"
label="COM_LANG4DEV_CHOOSE_COMPONENT_LABEL"
label="COM_LANG4DEV_CONFIG_GALLERIES_COLUMNS"
label="COM_LANG4DEV_CONFIG_GALLERIES_COLUMN_TYPE"
label="COM_LANG4DEV_CONFIG_GALLERIES_MAX_ROWS"
label="COM_LANG4DEV_CONFIG_GALLERIES_MAX_THUMBS"
label="COM_LANG4DEV_CONFIG_GALLERIES_ROW_TYPE"
label="COM_LANG4DEV_CONFIG_GALLERIES_ROW_TYPE_DESC"
label="COM_LANG4DEV_CONFIG_GALLERIES_SHOW_DESC"
label="COM_LANG4DEV_CONFIG_GALLERIES_SHOW_SEARCH"
label="COM_LANG4DEV_CONFIG_GALLERIES_SHOW_TITLE"
label="COM_LANG4DEV_CONFIG_GALLERY_SHOW_INTRO"
label="COM_LANG4DEV_CONFIG_IMAGES_COLUMNS"
label="COM_LANG4DEV_CONFIG_IMAGES_COLUMN_TYPE"
label="COM_LANG4DEV_CONFIG_IMAGES_MAX_ROWS"
label="COM_LANG4DEV_CONFIG_IMAGES_MAX_THUMBS"
label="COM_LANG4DEV_CONFIG_IMAGES_ROW_TYPE"
label="COM_LANG4DEV_CONFIG_IMAGES_SHOW_DESC"
label="COM_LANG4DEV_CONFIG_IMAGES_SHOW_SEARCH"
label="COM_LANG4DEV_CONFIG_IMAGES_SHOW_TITLE"
label="COM_LANG4DEV_CONFIG_NOTE_GALLERIES_LAYOUT"
label="COM_LANG4DEV_CONFIG_NOTE_IMAGES_LAYOUT"
label="COM_LANG4DEV_CONFIG_TAB_DEBUG"
label="COM_LANG4DEV_CONFIG_TAB_GALLERIES"
label="COM_LANG4DEV_CONFIG_TAB_GENERAL"
label="COM_LANG4DEV_CONFIG_TAB_IMAGES"
label="COM_LANG4DEV_CONFIG_TAB_J3X_LEGACY_LAYOUT"
label="COM_LANG4DEV_CONFIG_TAB_UPLOAD"
label="COM_LANG4DEV_COPY_OR_MOVE_TO_GALLERY"
label="COM_LANG4DEV_CREATED_BY_ALIAS_LABEL"
label="COM_LANG4DEV_DEBUG_BACKEND"
label="COM_LANG4DEV_DEBUG_SITE"
label="COM_LANG4DEV_DEFAULT_NUMBER_OF_GALLERIES_ON_ROOT_VIEW"
label="COM_LANG4DEV_DEVELOP"
label="COM_LANG4DEV_DISPLAY_IMAGE_PATH"
label="COM_LANG4DEV_DISPLAY_LATEST"
label="COM_LANG4DEV_DISPLAY_PICTURE_SIZE"
label="COM_LANG4DEV_DISPLAY_PICTURE_SIZE_J3X"
label="COM_LANG4DEV_DISPLAY_RANDOM"
label="COM_LANG4DEV_FIELD_IMAGE_ALT_LABEL"
label="COM_LANG4DEV_FIELD_IMAGE_LABEL"
label="COM_LANG4DEV_FIELD_NAME_LABEL"
label="COM_LANG4DEV_FIELD_NOTE_LABEL"
label="COM_LANG4DEV_FIELD_PARENT_GALLERY_LABEL"
label="COM_LANG4DEV_FIELD_SORT_ORDER_LABEL"
label="COM_LANG4DEV_FILENAME"
label="COM_LANG4DEV_FILTER_GALLERY_NAME"
label="COM_LANG4DEV_FTP_PATH"
label="COM_LANG4DEV_GALLERIES_FILTER_SEARCH_LABEL"
label="COM_LANG4DEV_GALLERIES_INTRODUCTION_TEXT"
label="COM_LANG4DEV_GALLERY_FIELD_THUMBNAIL_LABEL"
label="COM_LANG4DEV_IMAGES_FILTER_SEARCH_LABEL"
label="COM_LANG4DEV_IMAGE_PATH_J3X_DEPRECATED"
label="COM_LANG4DEV_INTRODUCTION_TEXT"
label="COM_LANG4DEV_J3X_DISPLAY_GALLERY_LIMITBOX"
label="COM_LANG4DEV_J3X_INTRODUCTION"
label="COM_LANG4DEV_J3X_NUMBER_OF_GALLERIES_ON_FRONTPAGE"
label="COM_LANG4DEV_JPEG_QUALITY_PERCENTAGE"
label="COM_LANG4DEV_KEEP_ORIGINAL_IMAGE"
label="COM_LANG4DEV_LIMIT_IMAGES_IN_GALLERY_LABEL"
label="COM_LANG4DEV_MENU_GALLERY_SHOW_SEARCH"
label="COM_LANG4DEV_MENU_J3X_GALLERIES_DESCRIPTION_SIDE"
label="COM_LANG4DEV_MENU_J3X_GALLERIES_DISPLAY_SLIDESHOW"
label="COM_LANG4DEV_MENU_J3X_GALLERIES_SHOW_DATE"
label="COM_LANG4DEV_MENU_J3X_GALLERIES_SHOW_DESCRIPTION"
label="COM_LANG4DEV_MENU_J3X_GALLERIES_SHOW_OWNER"
label="COM_LANG4DEV_MENU_J3X_GALLERIES_SHOW_PRE_LABEL"
label="COM_LANG4DEV_MENU_J3X_GALLERIES_SHOW_SIZE"
label="COM_LANG4DEV_MENU_J3X_GALLERIES_SHOW_TITLE"
label="COM_LANG4DEV_MENU_J3X_GALLERY_DISPLAY_SLIDESHOW"
label="COM_LANG4DEV_MENU_J3X_GALLERY_SHOW_DESCRIPTION"
label="COM_LANG4DEV_MENU_J3X_GALLERY_SHOW_TITLE"
label="COM_LANG4DEV_MENU_J3X_SELECT_GALLERY_LABEL"
label="COM_LANG4DEV_MENU_ROOT_GALLERIES_SHOW_SEARCH"
label="COM_LANG4DEV_MENU_SELECT_GALLERY_LABEL"
label="COM_LANG4DEV_ONE_GALLERY_FOR_ALL_IMAGES_LABEL"
label="COM_LANG4DEV_ORIGINAL_IMAGE_PATH"
label="COM_LANG4DEV_PATH_LABEL"
label="COM_LANG4DEV_PRESELECT_LATEST_GALLERY_LABEL"
label="COM_LANG4DEV_PREVIOUS_VERSION"
label="COM_LANG4DEV_SELECT_FOR_PARENT_GALLERY"
label="COM_LANG4DEV_SELECT_RSGALLERY2_LABEL"
label="COM_LANG4DEV_SPECIFY_GALLERY_LABEL"
label="COM_LANG4DEV_THUMBNAIL_SIZE"
label="COM_LANG4DEV_THUMBNAIL_STYLE"
label="COM_LANG4DEV_THUMB_PATH"
label="COM_LANG4DEV_USE_J3X_PATHS"
link="option=com_config&amp;view=component&amp;component=com_rsgallery2">COM_LANG4DEV_MENU_CONFIG
link="option=com_rsgallery2">COM_LANG4DEV_MENU_CONTROL_PANEL
link="option=com_rsgallery2&amp;view=galleries">COM_LANG4DEV_MENU_GALLERIES
link="option=com_rsgallery2&amp;view=images">COM_LANG4DEV_MENU_IMAGES
link="option=com_rsgallery2&amp;view=maintenance">COM_LANG4DEV_MENU_MAINTENANCE
link="option=com_rsgallery2&amp;view=upload">COM_LANG4DEV_MENU_UPLOAD
protected $text_prefix = 'COM_LANG4DEV';
quicktask-title="COM_LANG4DEV_DASHBOARD_ADD_GALLERY"
throw new \Exception(Text::_('COM_LANG4DEV_ERROR_RSGALLERY2_NOT_FOUND'), 404);
title="<?php echo "???" . Text::_('COM_LANG4DEV_J3X_IMAGES_DESELECT_BY_GALLERY_DEC'); ?>"
title="<?php echo Text::_('COM_LANG4DEV_COPY_TO_CLIPBOARD_DESC'); ?>"
title="<?php echo Text::_('COM_LANG4DEV_CREATE_GALLERY_DESC'); ?>"
title="<?php echo Text::_('COM_LANG4DEV_FTP_FOLDER_UPLOAD_DESC'); ?>"
title="<?php echo Text::_('COM_LANG4DEV_J3X_IMAGES_MOVE_BY_GALLERY_DEC'); ?>"
title="<?php echo Text::_('COM_LANG4DEV_J3X_IMAGES_SELECT_NEXT_100_GALLERY_DESC'); ?>"
title="<?php echo Text::_('COM_LANG4DEV_J3X_IMAGES_SELECT_NEXT_GALLERY_DESC'); ?>"
title="<?php echo Text::_('COM_LANG4DEV_MOVE_J3X_IMAGES_BY_GALLERIES_CHECK_DESC'); ?>"
title="<?php echo Text::_('COM_LANG4DEV_MOVE_SELECTED_J3X_IMAGES_DESC'); ?>"
title="<?php echo Text::_('COM_LANG4DEV_REPEAT_CHECKING_INCONSITENCIES_DESC'); ?>"
title="<?php echo Text::_('COM_LANG4DEV_SELECT_FILES_DESC'); ?>"
title="<?php echo Text::_('COM_LANG4DEV_SELECT_ZIP_FILE_DESC'); ?>"
title="COM_LANG4DEV_ACCESS_COMPONENT_COMMENT"
title="COM_LANG4DEV_ACCESS_COMPONENT_CREATE_OWN"
title="COM_LANG4DEV_ACCESS_COMPONENT_DELETE_OWN"
title="COM_LANG4DEV_ACCESS_COMPONENT_EDIT_STATE_OWN"
title="COM_LANG4DEV_ACCESS_COMPONENT_VOTE"
title="COM_LANG4DEV_ACCESS_GALLERY_COMMENT"
title="COM_LANG4DEV_ACCESS_GALLERY_CREATE_OWN"
title="COM_LANG4DEV_ACCESS_GALLERY_DELETE_OWN"
title="COM_LANG4DEV_ACCESS_GALLERY_EDIT_STATE_OWN"
title="COM_LANG4DEV_ACCESS_GALLERY_VOTE"
title="COM_LANG4DEV_ACCESS_ITEM_DELETE_OWN"
title="COM_LANG4DEV_ACCESS_ITEM_EDIT_STATE_OWN"
title="COM_LANG4DEV_MENU"
title="COM_LANG4DEV_MENU_CONFIG"
title="COM_LANG4DEV_MENU_CONTROL_PANEL"
title="COM_LANG4DEV_MENU_GALLERIES"
title="COM_LANG4DEV_MENU_IMAGES"
title="COM_LANG4DEV_MENU_MAINTENANCE"
title="COM_LANG4DEV_MENU_UPLOAD"
title="COM_LANG4DEV_SEND_COMMENT_DESC">
title="COM_LANG4DEV_SQL"
title="disabled<?php echo Text::_('COM_LANG4DEV_ADD_IMAGES_PROPERTIES_DESC'); ?>"