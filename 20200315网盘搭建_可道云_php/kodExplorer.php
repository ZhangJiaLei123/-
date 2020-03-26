// 去除 可道云 kodexplorer(kodcloud.com) 的版权和限制
// 只需要修改以下两个文件(一个PHP，一个JS)

// 第一步   替换 app/contoller/util.php
<?
define('ROJ_GROUP_PATH', '{groupPath}');
define('ROJ_GROUP_SHARE', '{groupShare}');
define('ROJ_USER_SELF', '{userSelf}');
define('ROJ_USER_SHARE', '{userShare}');
define('ROJ_USER_RECYCLE', '{userRecycle}');
define('ROJ_USER_FAV', '{userFav}');
define('ROJ_GROUP_ROOT_SELF', '{treeGroupSelf}');
define('ROJ_GROUP_ROOT_ALL', '{treeGroupAll}');

function _make_file_proxy($file){
    $config = $GLOBALS['config'];
    if (!file_exists($file)) {
        return '';
    }
    $safepassword = $config['settingSystem']['systemPassword'];
    $file_id = Mcrypt::encode($file, $safepassword, $config['settings']['downloadUrlTime']);
    $endoded_name = rawurlencode(iconv_app(get_path_this($file)));
    $system_url = APP_HOST.'index.php?';
    if (isset($config['settings']['paramRewrite']) && $config['settings']['paramRewrite'] == true) {
        $system_url = APP_HOST.'index.php/';
    }
    return $system_url.'user/publicLink&fid='.$file_id.'&file_name=/'.$endoded_name;
}

function _DIR_CLEAR($filepath){
    $filepath = str_replace('\\', '/', trim($filepath));
    $filepath = preg_replace('/\/+/', '/', $filepath);
    $xzv_15 = $filepath;
    if (isset($GLOBALS['isRoot']) && $GLOBALS['isRoot']) {
        return $filepath;
    }
    $xzv_47 = '/../';
    if (substr($filepath, 0, 3) == '../') {
        $filepath = substr($filepath, 3);
    }
    while (strstr($filepath, $xzv_47)) {
        $filepath = str_replace($xzv_47, '/', $filepath);
    }
    $filepath = preg_replace('/\/+/', '/', $filepath);
    return $filepath;
}

function _DIR($xzv_23)
{
    $xzv_20 = _DIR_CLEAR($xzv_23);
    $xzv_20 = iconv_system($xzv_20);
    $xzv_55 = array(
        ROJ_GROUP_PATH,
        ROJ_GROUP_SHARE,
        ROJ_USER_SELF,
        ROJ_GROUP_ROOT_SELF,
        ROJ_GROUP_ROOT_ALL,
        ROJ_USER_SHARE,
        ROJ_USER_RECYCLE,
        ROJ_USER_FAV,
    );
    $GLOBALS['rojPathType'] = '';
    $GLOBALS['rojPathPre'] = HOME;
    $GLOBALS['rojPathId'] = '';
    unset($GLOBALS['rojPathIdShare']);
    foreach ($xzv_55 as $xzv_49) {
        if (substr($xzv_20, 0, strlen($xzv_49)) == $xzv_49) {
            $GLOBALS['rojPathType'] = $xzv_49;
            $xzv_12 = explode('/', $xzv_20);
            $xzv_19 = $xzv_12[0];
            unset($xzv_12[0]);
            $xzv_13 = implode('/', $xzv_12);
            $xzv_26 = explode(':', $xzv_19);
            if (count($xzv_26) > 1) {
                $GLOBALS['rojPathId'] = trim($xzv_26[1]);
            }
            else {
                $GLOBALS['rojPathId'] = '';
            }
            break;
        }
    }
    switch ($GLOBALS['rojPathType']) {
        case '':
            $xzv_20 = iconv_system(HOME).$xzv_20;
            break;
        case ROJ_USER_RECYCLE:
            $GLOBALS['rojPathPre'] = trim(USER_RECYCLE, '/');
            $GLOBALS['rojPathId'] = '';
            return iconv_system(USER_RECYCLE).'/'.str_replace(ROJ_USER_RECYCLE,
                    '', $xzv_20);
        case ROJ_USER_SELF:
            $GLOBALS['rojPathPre'] = trim(HOME_PATH, '/');
            $GLOBALS['rojPathId'] = '';
            return iconv_system(HOME_PATH).'/'.str_replace(ROJ_USER_SELF, '',
                    $xzv_20);
        case ROJ_USER_FAV:
            $GLOBALS['rojPathPre'] = trim(ROJ_USER_FAV, '/');
            $GLOBALS['rojPathId'] = '';
            return ROJ_USER_FAV;
        case ROJ_GROUP_ROOT_SELF:
            $GLOBALS['rojPathPre'] = trim(ROJ_GROUP_ROOT_SELF, '/');
            $GLOBALS['rojPathId'] = '';
            return ROJ_GROUP_ROOT_SELF;
        case ROJ_GROUP_ROOT_ALL:
            $GLOBALS['rojPathPre'] = trim(ROJ_GROUP_ROOT_ALL, '/');
            $GLOBALS['rojPathId'] = '';
            return ROJ_GROUP_ROOT_ALL;
        case ROJ_GROUP_PATH:
            $xzv_14 = systemGroup::getInfo($GLOBALS['rojPathId']);
            if (!$GLOBALS['rojPathId'] || !$xzv_14)return false;
            owner_group_check($GLOBALS['rojPathId']);
            $GLOBALS['rojPathPre'] = group_home_path($xzv_14);
            $xzv_20 = iconv_system($GLOBALS['rojPathPre']).$xzv_13;
            break;
        case ROJ_GROUP_SHARE:
            $xzv_14 = systemGroup::getInfo($GLOBALS['rojPathId']);
            if (!$GLOBALS['rojPathId'] || !$xzv_14)return false;
            owner_group_check($GLOBALS['rojPathId']);
            $GLOBALS['rojPathPre'] = group_home_path($xzv_14).'share/';
            $xzv_20 = iconv_system($GLOBALS['rojPathPre']).$xzv_13;
            break;
        case ROJ_USER_SHARE:
            $xzv_14 = systemMember::getInfo($GLOBALS['rojPathId']);
            if (!$GLOBALS['rojPathId'] || !$xzv_14)return false;
            if ($GLOBALS['rojPathId'] != $_SESSION['rojUser']['userID']){
                $xzv_50 = $GLOBALS['config']['pathRoleGroupDefault']['1']['actions'];
                path_role_check($xzv_50);
            }
            $GLOBALS['rojPathPre'] = '';
            $GLOBALS['rojPathIdShare'] = $xzv_23;
            if ($xzv_13 == ''){
                return $xzv_20;
            }
            else {
                $xzv_40 = explode('/', $xzv_13);
                $xzv_40[0] = iconv_app($xzv_40[0]);
                $filepath0 = systemMember::userShareGet($GLOBALS['rojPathId'], $xzv_40[0]);
                $GLOBALS['rojShareInfo'] = $filepath0;
                $GLOBALS['rojPathIdShare'] = ROJ_USER_SHARE.':'.$GLOBALS['rojPathId']
                    . '/'.$xzv_40[0].'/';
                unset($xzv_40[0]);
                if (!$filepath0)return false;
                $xzv_44 = rtrim($filepath0['path'], '/').'/'.iconv_app(implode('/',
                        $xzv_40));
                if ($xzv_14['role'] != '1'){
                    $xzv_29 = user_home_path($xzv_14);
                    $GLOBALS['rojPathPre'] = $xzv_29.rtrim($filepath0['path'], '/')
                        . '/';
                    $xzv_20 = $xzv_29.$xzv_44;
                }
                else {
                    $GLOBALS['rojPathPre'] = $filepath0['path'];
                    $xzv_20 = $xzv_44;
                }
                if ($filepath0['type'] == 'file'){
                    $GLOBALS['rojPathIdShare'] = rtrim($GLOBALS['rojPathIdShare'],
                        '/');
                    $GLOBALS['rojPathPre'] = rtrim($GLOBALS['rojPathPre'], '/');
                }
                $xzv_20 = iconv_system($xzv_20);
            }
            break;
        default:
            break;
    }
    if ($xzv_20 != '/') {
        $xzv_20 = rtrim($xzv_20, '/');
        if (is_dir($xzv_20)) $xzv_20 = $xzv_20.'/';
    }
    return $xzv_20;
}

function _DIR_OUT($xzv_10)
{
    if (is_array($xzv_10)) {
        foreach ($xzv_10['fileList'] as $xzv_22 => &$filepath2) {
            $filepath2['path'] = preClear($filepath2['path']);
        }
        foreach ($xzv_10['folderList'] as $xzv_22 => &$filepath2) {
            $filepath2['path'] = preClear(rtrim($filepath2['path'], '/').'/');
        }
    }
    else {
        $xzv_10 = preClear($xzv_10);
    }
    return $xzv_10;
}

function preClear($xzv_28)
{
    $filepath1 = $GLOBALS['rojPathType'];
    $xzv_18 = rtrim($GLOBALS['rojPathPre'], '/');
    $xzv_17 = array(
        ROJ_USER_FAV,
        ROJ_GROUP_ROOT_SELF,
        ROJ_GROUP_ROOT_ALL
    );
    if (isset($GLOBALS['rojPathType']) && in_array($GLOBALS['rojPathType'],
            $xzv_17)) {
        return $xzv_28;
    }
    if (ST == 'share') {
        return str_replace($xzv_18, '', $xzv_28);
    }
    if ($GLOBALS['rojPathId'] != '') {
        $filepath1 .= ':'.$GLOBALS['rojPathId'].'/';
    }
    if (isset($GLOBALS['rojPathIdShare'])) {
        $filepath1 = $GLOBALS['rojPathIdShare'];
    }
    $xzv_21 = $filepath1.str_replace($xzv_18, '', $xzv_28);
    $xzv_21 = str_replace('//', '/', $xzv_21);
    return $xzv_21;
}

function owner_group_check($xzv_51)
{
    if (!$xzv_51) show_json(LNG('group_not_exist').$xzv_51, false);
    if ($GLOBALS['isRoot'] || (isset($GLOBALS['rojPathAuthCheck']) && $GLOBALS['rojPathAuthCheck'] === true)) {
        return;
    }
    $filepath9 = systemMember::userAuthGroup($xzv_51);
    if ($filepath9 == false) {
        if ($GLOBALS['rojPathType'] == ROJ_GROUP_PATH) {
            show_json(LNG('no_permission_group'), false);
        }
        else if ($GLOBALS['rojPathType'] == ROJ_GROUP_SHARE) {
            $filepath5 = $GLOBALS['config']['pathRoleGroupDefault']['1'];
        }
    }
    else {
        $filepath5 = $GLOBALS['config']['pathRoleGroup'][$filepath9];
    }
    path_role_check($filepath5['actions']);
}

function path_role_check($xzv_11)
{
    if ($GLOBALS['isRoot'] || (isset($GLOBALS['rojPathAuthCheck']) && $GLOBALS['rojPathAuthCheck'] === true)) {
        return;
    }
    $xzv_2 = role_permission_arr($xzv_11);
    $GLOBALS['rojPathRoleGroupAuth'] = $xzv_2;
    if (!isset($xzv_2[ST.'.'.ACT]) && ST != 'share') {
        show_json(LNG('no_permission_action'), false);
    }
}

function role_permission_arr($xzv_1)
{
    $xzv_41 = array();
    $xzv_16 = $GLOBALS['config']['pathRoleDefine'];
    foreach ($xzv_1 as $xzv_0 => $xzv_27) {
        if (!$xzv_27) continue;
        $xzv_4 = explode(':', $xzv_0);
        if (count($xzv_4) == 2 && is_array($xzv_16[$xzv_4[0]]) && is_array($xzv_16[$xzv_4[0]][$xzv_4[1]])) {
            $xzv_41 = array_merge($xzv_41, $xzv_16[$xzv_4[0]][$xzv_4[1]]);
        }
    }
    $xzv_24 = array();
    foreach ($xzv_41 as $xzv_27) {
        $xzv_24[$xzv_27] = '1';
    }
    return $xzv_24;
}

function check_file_writable_user($xzv_52)
{
    if (!isset($GLOBALS['rojPathType'])) {
        _DIR($xzv_52);
    }
    $xzv_53 = 'editor.fileSave';
    if ($GLOBALS['isRoot']) return @is_writable($xzv_52);
    if ($GLOBALS['auth'][$xzv_53] != '1') {
        return false;
    }
    if ($GLOBALS['rojPathType'] == ROJ_GROUP_PATH && is_array($GLOBALS['rojPathRoleGroupAuth'])
        && $GLOBALS['rojPathRoleGroupAuth'][$xzv_53] == '1') {
        return true;
    }
    if ($GLOBALS['rojPathType'] == '' || $GLOBALS['rojPathType'] == ROJ_USER_SELF) {
        return true;
    }
    return false;
}

function space_size_use_check(){
    if ($GLOBALS['isRoot'] == 1) return;
    if (isset($GLOBALS['rojBeforePathId']) && isset($GLOBALS['rojPathId'])
        && $GLOBALS['rojBeforePathId'] == $GLOBALS['rojPathId']) {
        return;
    }
    if ($GLOBALS['rojPathType'] == ROJ_GROUP_SHARE || $GLOBALS['rojPathType'] == ROJ_GROUP_PATH) {
        systemGroup::spaceCheck($GLOBALS['rojPathId']);
    }
    else {
        if (ST == 'share') {
            $xzv_56 = $GLOBALS['in']['user'];
        }
        else {
            $xzv_56 = $_SESSION['rojUser']['userID'];
        }
        systemMember::spaceCheck($xzv_56);
    }
}

function spaceSizeChange($filepath4, $filepath3 = true, $xzv_48 = false, $filepath6 = false)
{
    if ($xzv_48 === false) {
        $xzv_48 = $GLOBALS['rojPathType'];
        $filepath6 = $GLOBALS['rojPathId'];
    }
    $filepath3 = $filepath3 ? 1 : -1;
    if (is_file($filepath4)) {
        $filepath7 = get_filesize($filepath4);
    }
    else if (is_dir($filepath4)) {
        $xzv_5 = _path_info_more($filepath4);
        $filepath7 = $xzv_5['size'];
    }
    else {
        return;
    }
    if ($xzv_48 == ROJ_GROUP_SHARE || $xzv_48 == ROJ_GROUP_PATH) {
        systemGroup::spaceChange($filepath6, $filepath7 * $filepath3);
    }
    else {
        if (ST == 'share') {
            $xzv_54 = $GLOBALS['in']['user'];
        }
        else {
            $xzv_54 = $_SESSION['rojUser']['userID'];
        }
        systemMember::spaceChange($xzv_54, $filepath7 * $filepath3);
    }
}

function spaceSizeChange_move($xzv_57)
{
    if (isset($GLOBALS['rojBeforePathId']) && isset($GLOBALS['rojPathId'])) {
        if ($GLOBALS['rojBeforePathId'] == $GLOBALS['rojPathId']) {
            return;
        }
        else {
            spaceSizeChange($xzv_57);
            spaceSizeChange($xzv_57, false, $GLOBALS['beforePathType'], $GLOBALS['rojBeforePathId']);
        }
    }
    else {
        spaceSizeChange($xzv_57);
    }
}

function space_size_use_reset()
{
    $xzv_9 = isset($GLOBALS['rojPathType']) ? $GLOBALS['rojPathType'] : '';
    $xzv_8 = isset($GLOBALS['rojPathId']) ? $GLOBALS['rojPathId'] : '';
    if ($xzv_9 == ROJ_GROUP_SHARE || $xzv_9 == ROJ_GROUP_PATH) {
        systemGroup::spaceChange($xzv_8);
    }
    else {
        $xzv_25 = $_SESSION['rojUser']['userID'];
        systemMember::spaceChange($xzv_25);
    }
}

function init_space_size_hook()
{
    Hook::bind('uploadFileBefore', 'space_size_use_check');
    Hook::bind('uploadFileAfter', 'spaceSizeChange');
    Hook::bind('explorer.serverDownloadBefore', 'space_size_use_check');
    Hook::bind('explorer.unzipBefore', 'space_size_use_check');
    Hook::bind('explorer.zipBefore', 'space_size_use_check');
    Hook::bind('explorer.pathCopy', 'space_size_use_check');
    Hook::bind('explorer.mkfileBefore', 'space_size_use_check');
    Hook::bind('explorer.mkdirBefore', 'space_size_use_check');
    Hook::bind('explorer.pathMove', 'space_size_use_check');
    Hook::bind('explorer.mkfileAfter', 'spaceSizeChange');
    Hook::bind('explorer.pathCopyAfter', 'spaceSizeChange');
    Hook::bind('explorer.unzipAfter', 'spaceSizeChange');
    Hook::bind('explorer.serverDownloadAfter', 'spaceSizeChange');
    Hook::bind('explorer.pathMoveBefore', 'space_size_use_check');
    Hook::bind('explorer.pathMoveBfter', 'spaceSizeChange_move');
    Hook::bind('explorer.pathRemoveAfter', 'space_size_use_reset');
}

function init_session()
{
    if (isset($_GET['accessToken'])) {
        access_token_check($_GET['accessToken']);
    }
    else if (isset($_GET['access_token'])) {
        access_token_check($_GET['access_token']);
    }
    else {
        @session_name(SESSION_ID);
    }
    $xzv_42 = @session_save_path();
    if (class_exists('SaeStorage') || defined('SAE_APPNAME') || defined('SESSION_PATH_DEFAULT')
        || @ini_get('session.save_handler') != 'files' || isset($_SERVER['HTTP_APPNAME'])) {
    }
    else {
        chmod_path(ROJ_SESSION, 0777);
        @session_save_path(ROJ_SESSION);
    }
    @session_start();
    $_SESSION['roj'] = 1;
    @session_write_close();
    unset($_SESSION);
    @session_start();
    if (!$_SESSION['roj']) {
        @session_save_path($xzv_42);
        @session_start();
        $_SESSION['roj'] = 1;
        @session_write_close();
        unset($_SESSION);
        @session_start();
    }
    if (!$_SESSION['roj']) {
        show_tips('服务器session写入失败! (session write error)<br/>'.'请检查php.ini相关配置,查看磁盘是否已满,或咨询服务商<br/><br/>'
            . 'session.save_path='.$xzv_42.'<br/>'.'session.save_handler='.@ini_get('session.save_handler')
            . '<br/>');
    }
}

function access_token_check($filepath8)
{
    $xzv_7 = $GLOBALS['config']['settingSystem']['systemPassword'];
    $xzv_7 = substr(md5('rojExplorer_'.$xzv_7), 0, 15);
    $xzv_45 = Mcrypt::decode($filepath8, $xzv_7);
    if (!$xzv_45) {
        show_tips('accessToken error!');
    }
    session_id($xzv_45);
}

function access_token_get()
{
    $xzv_46 = session_id();
    $xzv_6 = $GLOBALS['config']['settingSystem']['systemPassword'];
    $xzv_6 = substr(md5('rojExplorer_'.$xzv_6), 0, 15);
    $xzv_43 = Mcrypt::encode($xzv_46, $xzv_6, 3600*24);
    return $xzv_43;
}

function init_config()
{
    init_setting();
    init_session();
    init_space_size_hook();
}

function user_home_path($user_name){
    $path = USER_PATH.$user_name['path'].'/home/';
    if (isset($user_name['homePath']) && file_exists(iconv_system($user_name['homePath']))) {
        $path = $user_name['homePath'];
    }
    return $path;
}

function group_home_path($group_name){
    $path = GROUP_PATH.$group_name['path'].'/home/';
    if (isset($group_name['homePath']) && file_exists(iconv_system($group_name['homePath']))) {
        $path = $group_name['homePath'];
    }
    return $path;
}
?>


////////////////////////////////////////////////////////////////////
// 第二步替换js文件  app/src/*/main.js


// 把define("app/common/core.tools" .....})替换为（同样也加密了,还要在文件开头加上var system_version_isvip="A";要版权就填A(版权可以改)，不要就填别的）
// define("app/common/core.tools",[],function(e){i=function(){return system_version_isvip;},d=function(){i=="A"&&$("body")["addClass"]("support-space-not"),$(".menu-system-about,.menu-left #about")["remove"](),$("#programs .setting_about,#programs .setting_homepage,#programs .home_page")["remove"]()},p=function(){core["icon"]=function(e,a){return e["substr"](0,4)=="http"?core["iconSrc"](e):"<i class='x-item-file x-"+e+(a?" small":"")+"'></i>";},core["iconSmall"]=function(e){return core["icon"](e,!0)},core["iconSrc"]=iconSrc=function(e){return"<img src='"+e+"' draggable='false' ondragstart='return false;'>"},core["versionType"]=i,core["versionUpdateVip"]=""+G["lang"],d()},u=function(e){return i=="A"&&-1==e["toLowerCase"]()["search"](s)?!1:!0},f=function(e,a){return e["data"];},h={init:p,about:u,systemData:f};return h})
