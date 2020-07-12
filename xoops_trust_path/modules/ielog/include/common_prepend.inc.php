<?php

//include files
require_once dirname(dirname(__FILE__)).'/include/read_configs.php' ;
require_once dirname(dirname(__FILE__)).'/include/get_perms.php' ;
require_once dirname(dirname(__FILE__)).'/include/draw_functions.php' ;
require_once dirname(dirname(__FILE__)).'/include/common_functions.php' ;
require_once dirname(dirname(__FILE__)).'/include/common_javalang.inc.php' ;
require_once XOOPS_ROOT_PATH."/class/xoopstree.php" ;

// GET admin status
$userid = is_object( @$xoopsUser ) ? $xoopsUser->getVar('uid') : 0 ;
$isadmin = $userid > 0 ? $xoopsUser->isAdmin() : false ;

// init xoops_breadcrumbs. this is your Xoops Top
$xoops_breadcrumbs[] = array( 'url' => XOOPS_URL , 'name' => _MD_GNAV_WEBTOP );


// assing global strings
$ielog_assign_globals = array(
	'mod_url' => $mod_url ,
	'mod_copyright' => $mod_copyright ,
	'lang_submitter' => _MD_GNAV_C_SUBMITTER ,
	'lang_hitsc' => _MD_GNAV_C_RAT_HITSC ,
	'lang_commentsc' => _MD_GNAV_CMT_COMMENTSC ,
	'lang_new' => _MD_GNAV_C_NEW ,
	'lang_updated' => _MD_GNAV_C_UPDATED ,
	'lang_popular' => _MD_GNAV_C_POPULAR ,
	'lang_ratethisphoto' => _MD_GNAV_RAT_RATETHISPHOTO ,
	'lang_editthisphoto' => _MD_GNAV_SMT_EDITITEM ,
	'lang_guestname' => _GNAV_CAPTION_GUESTNAME ,
	'lang_category' => _GNAV_CAPTION_CATEGORY ,
	'lang_nomatch' => _MD_GNAV_MSG_NOMATCH ,
	'lang_directcatsel' => _MD_GNAV_CAT_DIRECTCATSEL ,
	'lang_markerlist' => _MD_GNAV_MAP_MARKERLIST ,
	'lang_loading' => _MD_GNAV_MAP_LOADING ,
	'lang_lat' => _MD_GNAV_MAP_LAT ,
	'lang_lng' => _MD_GNAV_MAP_LNG ,
	'lang_zoom' => _MD_GNAV_MAP_ZOOM ,
	'lang_movepid' => _MD_GNAV_CAT_MOVE_PARENT ,
	'photos_url' => $photos_url ,
	'thumbs_url' => $thumbs_url ,
	'thumbsize' => $ielog_thumbsize ,
	'colsoftableview' => $ielog_colsoftableview ,
	'colstbl_width' => ($ielog_colsoftableview ? 'width:'.intval(100/$ielog_colsoftableview).'%;' : '' ) ,
	'canrateview' => $global_perms & GNAV_GPERM_RATEVIEW ,
	'canratevote' => $global_perms & GNAV_GPERM_RATEVOTE ,
	'home' => _MD_GNAV_WEBTOP ,
	'canvote'  => $ielog_usevote,
	'comment_dirname' => $ielog_comment_dirname,
	'comment_forum_id' => $ielog_comment_forum_id ,
	'comment_view' => $ielog_comment_view,
	'mydirname' => $mydirname,
	'am_cat_edit' => ( $isadmin ? _MD_GNAV_CAT_EDIT : '' ) ,
	'lang_itemlist' => _MD_GNAV_CAT_ITEMLIST,
	'lang_url' => _MD_GNAV_ITM_URL,
	'lang_tel' => _MD_GNAV_ITM_TEL,
	'lang_fax' => _MD_GNAV_ITM_FAX,
	'lang_zip' => _MD_GNAV_ITM_ZIP,
	'lang_other1' => _MD_GNAV_ITM_other1,
	'lang_other2' => _MD_GNAV_ITM_other2,
	'lang_other3' => _MD_GNAV_ITM_other3,
	'lang_other4' => _MD_GNAV_ITM_other4,
	'lang_other5' => _MD_GNAV_ITM_other5,
	'lang_other6' => _MD_GNAV_ITM_other6,
	'lang_other7' => _MD_GNAV_ITM_other7,
	'lang_other8' => _MD_GNAV_ITM_other8,
	'lang_other9' => _MD_GNAV_ITM_other9,
	'lang_other10' => _MD_GNAV_ITM_other10,
	'lang_other11' => _MD_GNAV_ITM_other11,
	'lang_other12' => _MD_GNAV_ITM_other12,
	'lang_other13' => _MD_GNAV_ITM_other13,
	'lang_other14' => _MD_GNAV_ITM_other14,
	'lang_other15' => _MD_GNAV_ITM_other15,
	'lang_other16' => _MD_GNAV_ITM_other16,
	'lang_other17' => _MD_GNAV_ITM_other17,
	'lang_other18' => _MD_GNAV_ITM_other18,
	'lang_other19' => _MD_GNAV_ITM_other19,
	'lang_other20' => _MD_GNAV_ITM_other20,
	'lang_address' => _MD_GNAV_ITM_ADDRESS,
	'lang_map' => _MD_GNAV_MAP,
	'lang_readmore' => _MD_GNAV_NAV_READMORE,
	'lang_print' => _MD_GNAV_ITM_PRINT ,
	'lang_top_link' => sprintf( _MD_GNAV_MOBILE_TOP , $xoopsModule->getVar( 'name' ) ) ,
	'lang_bukken' => _MD_GNAV_ITM_BUKKEN,
	'lang_others' => _MD_GNAV_ITM_OTHERS,
	'lang_opendate' => _MD_GNAV_ITM_OPENDATE,
	'lang_askme' => _MD_GNAV_ITM_ASKME,
	'lang_emailadd' => _MD_GNAV_ITM_EMAILADD,
	'lang_asktel' => _MD_GNAV_ITM_ASKTEL,
	'lang_iwant' => _MD_GNAV_ITM_IWANT,
	'lang_askask' => _MD_GNAV_ITM_ASKASK,
	'lang_submitmail' => _MD_GNAV_ITM_SUBMITMAIL,
	'lang_desc' => _MD_GNAV_ITM_DESC,
	'lang_postername' => _MD_GNAV_ITM_POSTERNAME,
) ;

if(!$ielog_usegooglemap)$ielog_indexpage=='category';
$page_map = $ielog_indexpage=='map' ? '' : 'page=map' ;
$page_cat = $ielog_indexpage=='map' ? 'page=category' : '' ;

//const values
define("G_UPDATE", 1);
define("G_INSERT", 0);
define("G_KML", "kml");
define("G_XML", "xml");
$ielog_gmap_exts=array('kml','kmz');
$ielog_ajaxzip_place="/include/ajaxzip2/";

$ielog_qrcode_path = XOOPS_TRUST_PATH."/libs/qrcode/qrcode_img.php" ;


//agent query

$IELOG_MOBILE=0;
$IELOG_MOBILE_MAP=0;

$ielog_assign_globals['agent']=@$_GET['agent']=='mobile' ? '&agent=mobile':'';
$ielog_assign_globals['_agent']=@$_GET['agent']=='mobile' ? 'mobile':'';
$agent = @$_SERVER['HTTP_USER_AGENT'];

if(preg_match($ielog_mobile_agent, $agent) || @$_GET['agent']=='mobile'){
	$IELOG_MOBILE=1;
	list( $ielog_mobile_mapsizex , $ielog_mobile_mapsizey ) = explode( 'x' , $ielog_mobile_mapsize ) ;
	$ielog_mobile_mapsizex=intval($ielog_mobile_mapsizex);
	$ielog_mobile_mapsizey=intval($ielog_mobile_mapsizey);
	if($ielog_mobile_mapsizex>0 && $ielog_mobile_mapsizey>0)$IELOG_MOBILE_MAP=1;
	$ielog_mobile_maptype='mobile';
}



?>