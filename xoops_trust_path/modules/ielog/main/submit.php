<?php
// ------------------------------------------------------------------------- //
//                      GNAVI - XOOPS area guide +  IELOG                         //
//                        <http://xoops.iko-ze.net/>                         //
//                 based on  myAlbum-P - XOOPS photo album                   //
//                        <http://www.peak.ne.jp/>                           //
// ------------------------------------------------------------------------- //

include dirname(dirname(__FILE__)).'/include/common_prepend.inc.php' ;
require_once dirname(dirname(__FILE__)).'/class/myuploader.php' ;
require_once dirname(dirname(__FILE__)).'/class/gnavi.textsanitizer.php' ;
require_once dirname(dirname(__FILE__)).'/class/gtickets.php' ;
$myts =& IElogTextSanitizer::getInstance() ;
$cattree = new XoopsTree( $table_cat , 'cid' , 'pid' ) ;

// check folders
ielog_check_folders();

// check Categories exist
$result = $xoopsDB->query( "SELECT count(cid) as count FROM $table_cat" ) ;
list( $count ) = $xoopsDB->fetchRow( $result ) ;
if( $count < 1 ) {
	redirect_header( XOOPS_URL."/modules/$mydirname/" , 2 , _MD_GNAV_MSG_MUSTADDCATFIRST ) ;
	exit ;
}


// check lid exists
if( !empty( $_POST['submit'] ) || !empty( $_POST['preview'] ) || !empty( $_POST['conf_delete'] )) {
	$lid   = empty( $_POST['lid'] ) ? 0 : intval( $_POST['lid'] ) ;
}else{
	$lid   = empty( $_GET['lid'] ) ? 0 : intval( @$_GET['lid'] ) ;
}

if($lid > 0){
	$whr_status = $isadmin ? '' : 'AND status>0' ;
	$result = $xoopsDB->query( "SELECT count(lid) AS count FROM $table_photos WHERE lid=$lid $whr_status" ) ;
	list( $count ) = $xoopsDB->fetchRow( $result ) ;
	$mode = $count > 0 ? G_UPDATE : G_INSERT ;
}else{
	$mode = G_INSERT ;
}

// check parmition
if($mode==G_INSERT){

	$submitter = $my_uid ;
	if( ! ( $global_perms & GNAV_GPERM_INSERTABLE ) ) {
		redirect_header( XOOPS_URL."/user.php" , 2 , _MD_GNAV_MSG_MUSTREGFIRST ) ;
		exit ;
	}

}else{
	//check lid owner
	$result = $xoopsDB->query( "SELECT submitter FROM $table_photos WHERE lid=$lid" ) ;
	list( $submitter ) = $xoopsDB->fetchRow( $result ) ;

	if( $global_perms & GNAV_GPERM_EDITABLE ) {
		if( $my_uid != $submitter && ! $isadmin ) {
			redirect_header( $mod_url.'/' , 3 , _NOPERM ) ;
			exit ;
		}
	} else {
		redirect_header( $mod_url.'/' , 3 , _NOPERM ) ;
		exit ;
	}
}

// Do Delete
if( ! empty( $_POST['do_delete'] ) ) {

	if( ! ( $global_perms & GNAV_GPERM_DELETABLE ) ) {
		redirect_header( $mod_url.'/' , 3 , _NOPERM ) ;
		exit ;
	}

	// Ticket Check
	if ( ! $xoopsGTicket->check() ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}

	// get and check lid is valid
	if( $lid < 1 ) die( "Invalid photo id." ) ;

	$whr = "lid=$lid" ;
	if( ! $isadmin ) $whr .= " AND submitter=$my_uid" ;

	ielog_delete_photos( $whr ) ;

	redirect_header( $mod_url.'/' , 3 , _MD_GNAV_SMT_DELETINGITEM ) ;
	exit ;
}


// POST variables
$p_lat  = empty( $_GET['lat']  ) ? 0 : floatval ( @$_GET['lat']  ) ;
$p_lng  = empty( $_GET['lng']  ) ? 0 : floatval ( @$_GET['lng']  ) ;
$p_zoom = empty( $_GET['z'] ) ? 0 : intval( @$_GET['z'] ) ;
$p_mtype = !in_array(@$_GET['mt'],$ielog_maptypes) ? "" : $_GET['mt'] ;
$p_cid  = empty( $_GET['cid']  ) ? 0 : intval( @$_GET['cid']  ) ;

$p_set_latlng = empty( $_POST['set_latlng'] ) ? 1 : 0 ;
$preview_name = empty( $_POST['preview_name'] ) ? '' : @$_POST['preview_name'] ;
$preview_name1 = empty( $_POST['preview_name1'] ) ? '' : @$_POST['preview_name1'] ;
$preview_name2 = empty( $_POST['preview_name2'] ) ? '' : @$_POST['preview_name2'] ;
$preview_name3 = empty( $_POST['preview_name3'] ) ? '' : @$_POST['preview_name3'] ;
$preview_name4 = empty( $_POST['preview_name4'] ) ? '' : @$_POST['preview_name4'] ;
$preview_name5 = empty( $_POST['preview_name5'] ) ? '' : @$_POST['preview_name5'] ;
$preview_name6 = empty( $_POST['preview_name6'] ) ? '' : @$_POST['preview_name6'] ;
$preview_name7 = empty( $_POST['preview_name7'] ) ? '' : @$_POST['preview_name7'] ;
$preview_name8 = empty( $_POST['preview_name8'] ) ? '' : @$_POST['preview_name8'] ;
$del_photo = empty( $_POST['del_photo'] ) ? 0 : intval( @$_POST['del_photo'] ) ;
$del_photo1 = empty( $_POST['del_photo1'] ) ? 0 : intval( @$_POST['del_photo1'] ) ;
$del_photo2 = empty( $_POST['del_photo2'] ) ? 0 : intval( @$_POST['del_photo2'] ) ;
$del_photo3 = empty( $_POST['del_photo3'] ) ? 0 : intval( @$_POST['del_photo3'] ) ;
$del_photo4 = empty( $_POST['del_photo4'] ) ? 0 : intval( @$_POST['del_photo4'] ) ;
$del_photo5 = empty( $_POST['del_photo5'] ) ? 0 : intval( @$_POST['del_photo5'] ) ;
$del_photo6 = empty( $_POST['del_photo6'] ) ? 0 : intval( @$_POST['del_photo6'] ) ;
$del_photo7 = empty( $_POST['del_photo7'] ) ? 0 : intval( @$_POST['del_photo7'] ) ;
$del_photo8 = empty( $_POST['del_photo8'] ) ? 0 : intval( @$_POST['del_photo8'] ) ;
$p_valid  = empty( $_POST['valid'] ) ? 0 : intval( $_POST['valid'] );
$p_status = empty( $_POST['old_status'] ) ? 0 : intval( $_POST['old_status'] );


if( ! empty( $_POST['submit'] ) || ! empty( $_POST['preview'] )) {

	$title = $myts->stripSlashesGPC( $_POST["title"] ) ;
	$cid = empty( $_POST['cid'] ) ? 0 : intval( $_POST['cid'] ) ;
	$cid1 = empty( $_POST['cid1'] ) ? 0 : intval( $_POST['cid1'] ) ;
	$cid2 = empty( $_POST['cid2'] ) ? 0 : intval( $_POST['cid2'] ) ;
	$cid3 = empty( $_POST['cid3'] ) ? 0 : intval( $_POST['cid3'] ) ;
	$cid4 = empty( $_POST['cid4'] ) ? 0 : intval( $_POST['cid4'] ) ;
	$icd = empty( $_POST['icd'] ) ? 0 : intval( @$_POST['icd'] ) ;

	$desc_text = $myts->stripSlashesGPC( $_POST["desc_text"] ) ;
	$body_html = empty( $_POST['body_html'] ) || !($global_perms & GNAV_GPERM_WYSIWYG) ? 0 : intval( $_POST['body_html'] ) ;
	$arrow_html = $body_html ? 1 : 0 ;
	$arrow_br =  $body_html ? 0 : 1 ;

	$caption  = $myts->stripSlashesGPC( $_POST["caption" ] ) ;
	$caption1 = $myts->stripSlashesGPC( $_POST["caption1"] ) ;
	$caption2 = $myts->stripSlashesGPC( $_POST["caption2"] ) ;
	$caption3 = $myts->stripSlashesGPC( $_POST["caption3"] ) ;
	$caption4 = $myts->stripSlashesGPC( $_POST["caption4"] ) ;
	$caption5 = $myts->stripSlashesGPC( $_POST["caption5"] ) ;
	$caption6 = $myts->stripSlashesGPC( $_POST["caption6"] ) ;
	$caption7 = $myts->stripSlashesGPC( $_POST["caption7"] ) ;
	$caption8 = $myts->stripSlashesGPC( $_POST["caption8"] ) ;
	$url = $myts->stripSlashesGPC( $_POST["url"] ) ;
//Œ³URL“ü—Í—“‚Ì“ü—Í§ŒÀƒJƒbƒg	$url= preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $url) ? $url :"";
	$rss = $myts->stripSlashesGPC( @$_POST["rss"] ) ;
	$rss= preg_match('/^(https?|ftp)(:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:\@&=+\$,%#]+)$/', $rss) ? $rss :"";

	$tel = $myts->stripSlashesGPC( $_POST["tel"] ) ;
	$fax = $myts->stripSlashesGPC( $_POST["fax"] ) ;
	$zip = $myts->stripSlashesGPC( $_POST["zip"] ) ;
	$other1 = $myts->stripSlashesGPC( $_POST["other1"] ) ;
	$other2 = $myts->stripSlashesGPC( $_POST["other2"] ) ;
	$other3 = $myts->stripSlashesGPC( $_POST["other3"] ) ;
	$other4 = $myts->stripSlashesGPC( $_POST["other4"] ) ;
	$other5 = $myts->stripSlashesGPC( $_POST["other5"] ) ;
	$other6 = $myts->stripSlashesGPC( $_POST["other6"] ) ;
	$other7 = $myts->stripSlashesGPC( $_POST["other7"] ) ;
	$other8 = $myts->stripSlashesGPC( $_POST["other8"] ) ;
	$other9 = $myts->stripSlashesGPC( $_POST["other9"] ) ;
	$other10 = $myts->stripSlashesGPC( $_POST["other10"] ) ;
	$other11 = $myts->stripSlashesGPC( $_POST["other11"] ) ;
	$other12 = $myts->stripSlashesGPC( $_POST["other12"] ) ;
	$other13 = $myts->stripSlashesGPC( $_POST["other13"] ) ;
	$other14 = $myts->stripSlashesGPC( $_POST["other14"] ) ;
	$other15 = $myts->stripSlashesGPC( $_POST["other15"] ) ;
	$other16 = $myts->stripSlashesGPC( $_POST["other16"] ) ;
	$other17 = $myts->stripSlashesGPC( $_POST["other17"] ) ;
	$other18 = $myts->stripSlashesGPC( $_POST["other18"] ) ;
	$other19 = $myts->stripSlashesGPC( $_POST["other19"] ) ;
	$other20 = $myts->stripSlashesGPC( $_POST["other20"] ) ;
	$address = $myts->stripSlashesGPC( $_POST["address"] ) ;
	$lat = floatval($myts->stripSlashesGPC( @$_POST["lat"] )) ;
	$lng = floatval($myts->stripSlashesGPC( @$_POST["lng"] )) ;
	$zoom = intval($myts->stripSlashesGPC( @$_POST["z"] )) ;
	$mtype = !in_array($myts->stripSlashesGPC( @$_POST["mt"] ),$ielog_maptypes) ? "" : $myts->stripSlashesGPC( @$_POST["mt"] ) ;	

	$addinfo = ielog_addinfo_reg($myts->stripSlashesGPC( @$_POST["addinfo"] ));

	// ken add postername
	$poster_name = empty( $_POST['poster_name'] ) ? '' : $myts->stripSlashesGPC( @$_POST['poster_name'] );
	if( trim( $poster_name ) == "" ) {
		$poster_name = _GNAV_CAPTION_GUESTNAME;
		$submitter=0;
	}
	if (!ielog_check_name_from_uid($submitter,$poster_name)){
		//if postername difference from uid then force guest witer 
		$submitter=0;
	}
}


// Do Modify
if( ! empty( $_POST['submit'] ) ) {

	// Ticket Check
	if ( ! $xoopsGTicket->check() ) {
		redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
	}

	if(!$ielog_usegooglemap || !$p_set_latlng || ($lat==$ielog_defaultlat && $lng==$ielog_defaultlng)){
		$lat = 0 ;
		$lng = 0 ;
		$zoom = 0 ;
		$mtype = '' ;
	}

	// Check if cid is valid
	if( $cid <= 0 ) {
		redirect_header( 'index.php?page=submit'.($lid ? '&lid='.$lid : '' ) , 2 , 'Category is not specified.' ) ;
		exit ;
	}

	//file uploads-------------------------------------------------------------------------------------------------

	if($mode==G_INSERT){

		$valid = ( $global_perms & GNAV_GPERM_SUPERINSERT ) ? 1 : 0 ;
		$p_ext=$p_ext1=$p_ext2=$p_ext3=$p_ext4=$p_ext5=$p_ext6=$p_ext7=$p_ext8='';

	}else{

		// status change
		if( $isadmin ) {
			$valid = empty( $_POST['valid'] ) ? 0 : intval( $_POST['valid'] ) ;
			if( $valid == 0 ){
				 $valid = 0 ;
			}else{
				if( empty( $_POST['old_status'] ) ) {
					$valid = 1 ;
				} else {
					$valid = 2 ;
				}
			}
		} else {
			$valid = 2 ;
		}

		$prs = $xoopsDB->query( "SELECT ext,ext1,ext2,ext3,ext4,ext5,ext6,ext7,ext8 FROM $table_photos WHERE lid=$lid") ;
		list($p_ext,$p_ext1,$p_ext2,$p_ext3,$p_ext4,$p_ext5,$p_ext6,$p_ext7,$p_ext8) = $xoopsDB->fetchRow( $prs ) ;
		if($preview_name  && $preview_name ==$lid.".".$p_ext   )$preview_name='' ;
		if($preview_name1 && $preview_name1==$lid."_1.".$p_ext1)$preview_name1='' ;
		if($preview_name2 && $preview_name2==$lid."_2.".$p_ext2)$preview_name2='' ;
		if($preview_name3 && $preview_name3==$lid."_3.".$p_ext3)$preview_name3='' ;
		if($preview_name4 && $preview_name4==$lid."_4.".$p_ext4)$preview_name4='' ;
		if($preview_name5 && $preview_name5==$lid."_5.".$p_ext5)$preview_name5='' ;
		if($preview_name6 && $preview_name6==$lid."_6.".$p_ext6)$preview_name6='' ;
		if($preview_name7 && $preview_name7==$lid."_7.".$p_ext7)$preview_name7='' ;
		if($preview_name8 && $preview_name8==$lid."_8.".$p_ext8)$preview_name8='' ;
	}

	$errmsg='';
	
	list($tmp_name ,$ext ,$errmsg) = ielog_submit_uploader(@$_POST["xoops_upload_file"][0] ,$del_photo ,$preview_name , 1, $errmsg);
	list($tmp_name1,$ext1,$errmsg) = ielog_submit_uploader(@$_POST["xoops_upload_file"][1] ,$del_photo1,$preview_name1, 2, $errmsg);
	list($tmp_name2,$ext2,$errmsg) = ielog_submit_uploader(@$_POST["xoops_upload_file"][2] ,$del_photo2,$preview_name2, 3, $errmsg);
	list($tmp_name3,$ext3,$errmsg) = ielog_submit_uploader(@$_POST["xoops_upload_file"][3] ,$del_photo3,$preview_name3, 4, $errmsg);
	list($tmp_name4,$ext4,$errmsg) = ielog_submit_uploader(@$_POST["xoops_upload_file"][4] ,$del_photo4,$preview_name4, 5, $errmsg);
	list($tmp_name5,$ext5,$errmsg) = ielog_submit_uploader(@$_POST["xoops_upload_file"][5] ,$del_photo5,$preview_name5, 6, $errmsg);
	list($tmp_name6,$ext6,$errmsg) = ielog_submit_uploader(@$_POST["xoops_upload_file"][6] ,$del_photo6,$preview_name6, 7, $errmsg);
	list($tmp_name7,$ext7,$errmsg) = ielog_submit_uploader(@$_POST["xoops_upload_file"][7] ,$del_photo7,$preview_name7, 8, $errmsg);
	list($tmp_name8,$ext8,$errmsg) = ielog_submit_uploader(@$_POST["xoops_upload_file"][8] ,$del_photo8,$preview_name8, 9, $errmsg);


	if($mode == G_INSERT && $ext=='' && !$ielog_allownoimage) {
		redirect_header( 'index.php?page=submit'.($lid ? '&lid='.$lid : '' ) , 2 , _MD_GNAV_MSG_NOIMAGESPECIFIED ) ;
		exit ;
	}
	if($errmsg) {
		if($tmp_name )@unlink($photos_dir/$tmp_name) ;
		if($tmp_name1)@unlink($photos_dir/$tmp_name1) ;
		if($tmp_name2)@unlink($photos_dir/$tmp_name2) ;
		if($tmp_name3)@unlink($photos_dir/$tmp_name3) ;
		if($tmp_name4)@unlink($photos_dir/$tmp_name4) ;
		if($tmp_name5)@unlink($photos_dir/$tmp_name5) ;
		if($tmp_name6)@unlink($photos_dir/$tmp_name6) ;
		if($tmp_name7)@unlink($photos_dir/$tmp_name7) ;
		if($tmp_name8)@unlink($photos_dir/$tmp_name8) ;
		redirect_header( 'index.php?page=submit'.($lid ? '&lid='.$lid : '' ) , 4 , $errmsg ) ;
		exit ;
	}

	$lid = ielog_update_item($mode,$lid,
						$title,$cid,$cid1,$cid2,$cid3,$cid4,
                       	$url,$tel,$fax,$zip,$other1,$other2,$other3,$other4,$other5,$other6,$other7,$other8,$other9,$other10,$other11,$other12,$other13,$other14,$other15,$other16,$other17,$other18,$other19,$other20,$address,$rss,$lat,$lng,$zoom,$mtype,$icd,
                       	$submitter,$poster_name,$valid ) ;

	//delete old files
	if( $p_ext){
		if($del_photo==1 || $ext){
				@unlink( "$photos_dir/$lid.$p_ext");
				@unlink( "$thumbs_dir/$lid.$p_ext");
				$p_ext='';
		}
	}
	if( $p_ext1){
		if($del_photo1==1 || $ext1){
				@unlink( $photos_dir."/".$lid."_1.".$p_ext1);
				$p_ext1='';
		}
	}
	if( $p_ext2){
		if($del_photo2==1 || $ext2){
				@unlink( $photos_dir."/".$lid."_2.".$p_ext2);
				$p_ext2='';
		}
	}
	if( $p_ext3){
		if($del_photo3==1 || $ext3){
				@unlink( $photos_dir."/".$lid."_3.".$p_ext3);
				$p_ext3='';
		}
	}
	if( $p_ext4){
		if($del_photo4==1 || $ext4){
				@unlink( $photos_dir."/".$lid."_4.".$p_ext4);
				$p_ext4='';
		}
	}
	if( $p_ext5){
		if($del_photo5==1 || $ext5){
				@unlink( $photos_dir."/".$lid."_5.".$p_ext5);
				$p_ext5='';
		}
	}
	if( $p_ext6){
		if($del_photo6==1 || $ext6){
				@unlink( $photos_dir."/".$lid."_6.".$p_ext6);
				$p_ext6='';
		}
	}
	if( $p_ext7){
		if($del_photo7==1 || $ext7){
				@unlink( $photos_dir."/".$lid."_7.".$p_ext7);
				$p_ext7='';
		}
	}
	if( $p_ext8){
		if($del_photo8==1 || $ext8){
				@unlink( $photos_dir."/".$lid."_8.".$p_ext8);
				$p_ext8='';
		}
	}

	if($ext){
		ielog_modify_photo( "$photos_dir/$tmp_name" , "$photos_dir/$lid.$ext" ) ;
		if(in_array( strtolower( $ext ) , $ielog_normal_exts )) {
			if( ! ielog_create_thumb( "$photos_dir/$lid.$ext" , $lid , $ext ) ) {
				$xoopsDB->query( "DELETE FROM $table_photos WHERE lid=$lid" ) ;
				redirect_header( 'index.php?page=submit'.($lid ? '&lid='.$lid : '' ) , 2 , _MD_GNAV_MSG_FILEREADERROR ) ;
				exit ;
			}
		}
	}else{
		$ext=$p_ext;
	}
	if($ext1){
		ielog_modify_photo( "$photos_dir/$tmp_name1" , $photos_dir."/".$lid."_1.".$ext1 ) ;
		if(in_array( strtolower( $ext1 ) , $ielog_normal_exts )) {
			if( ! ielog_create_thumb( $photos_dir."/".$lid."_1.".$ext1 , $lid."_1" , $ext1 ) ) {
				$xoopsDB->query( "DELETE FROM $table_photos WHERE lid=$lid" ) ;
				redirect_header( 'index.php?page=submit'.($lid ? '&lid='.$lid : '' ) , 2 , _MD_GNAV_MSG_FILEREADERROR ) ;
				exit ;
			}
		}
	}else{
		$ext1=$p_ext1;
	}
	if($ext2){
		ielog_modify_photo( "$photos_dir/$tmp_name2" , $photos_dir."/".$lid."_2.".$ext2 ) ;
		if(in_array( strtolower( $ext2 ) , $ielog_normal_exts )) {
			if( ! ielog_create_thumb( $photos_dir."/".$lid."_2.".$ext2 , $lid."_2" , $ext2 ) ) {
				$xoopsDB->query( "DELETE FROM $table_photos WHERE lid=$lid" ) ;
				redirect_header( 'index.php?page=submit'.($lid ? '&lid='.$lid : '' ) , 2 , _MD_GNAV_MSG_FILEREADERROR ) ;
				exit ;
			}
		}
	}else{
		$ext2=$p_ext2;
	}

	if($ext3){
		ielog_modify_photo( "$photos_dir/$tmp_name3" , $photos_dir."/".$lid."_3.".$ext3 ) ;
		if(in_array( strtolower( $ext3 ) , $ielog_normal_exts )) {
			if( ! ielog_create_thumb( $photos_dir."/".$lid."_3.".$ext3 , $lid."_3" , $ext3 ) ) {
				$xoopsDB->query( "DELETE FROM $table_photos WHERE lid=$lid" ) ;
				redirect_header( 'index.php?page=submit'.($lid ? '&lid='.$lid : '' ) , 2 , _MD_GNAV_MSG_FILEREADERROR ) ;
				exit ;
			}
		}
	}else{
		$ext3=$p_ext3;
	}

	if($ext4){
		ielog_modify_photo( "$photos_dir/$tmp_name4" , $photos_dir."/".$lid."_4.".$ext4 ) ;
		if(in_array( strtolower( $ext4 ) , $ielog_normal_exts )) {
			if( ! ielog_create_thumb( $photos_dir."/".$lid."_4.".$ext4 , $lid."_4" , $ext4 ) ) {
				$xoopsDB->query( "DELETE FROM $table_photos WHERE lid=$lid" ) ;
				redirect_header( 'index.php?page=submit'.($lid ? '&lid='.$lid : '' ) , 2 , _MD_GNAV_MSG_FILEREADERROR ) ;
				exit ;
			}
		}
	}else{
		$ext4=$p_ext4;
	}
	if($ext5){
		ielog_modify_photo( "$photos_dir/$tmp_name5" , $photos_dir."/".$lid."_5.".$ext5 ) ;
		if(in_array( strtolower( $ext5 ) , $ielog_normal_exts )) {
			if( ! ielog_create_thumb( $photos_dir."/".$lid."_5.".$ext5 , $lid."_5" , $ext5 ) ) {
				$xoopsDB->query( "DELETE FROM $table_photos WHERE lid=$lid" ) ;
				redirect_header( 'index.php?page=submit'.($lid ? '&lid='.$lid : '' ) , 2 , _MD_GNAV_MSG_FILEREADERROR ) ;
				exit ;
			}
		}
	}else{
		$ext5=$p_ext5;
	}
	if($ext6){
		ielog_modify_photo( "$photos_dir/$tmp_name6" , $photos_dir."/".$lid."_6.".$ext6 ) ;
		if(in_array( strtolower( $ext6 ) , $ielog_normal_exts )) {
			if( ! ielog_create_thumb( $photos_dir."/".$lid."_6.".$ext6 , $lid."_6" , $ext6 ) ) {
				$xoopsDB->query( "DELETE FROM $table_photos WHERE lid=$lid" ) ;
				redirect_header( 'index.php?page=submit'.($lid ? '&lid='.$lid : '' ) , 2 , _MD_GNAV_MSG_FILEREADERROR ) ;
				exit ;
			}
		}
	}else{
		$ext6=$p_ext6;
	}
	if($ext7){
		ielog_modify_photo( "$photos_dir/$tmp_name7" , $photos_dir."/".$lid."_7.".$ext7 ) ;
		if(in_array( strtolower( $ext7 ) , $ielog_normal_exts )) {
			if( ! ielog_create_thumb( $photos_dir."/".$lid."_7.".$ext7 , $lid."_7" , $ext7 ) ) {
				$xoopsDB->query( "DELETE FROM $table_photos WHERE lid=$lid" ) ;
				redirect_header( 'index.php?page=submit'.($lid ? '&lid='.$lid : '' ) , 2 , _MD_GNAV_MSG_FILEREADERROR ) ;
				exit ;
			}
		}
	}else{
		$ext7=$p_ext7;
	}
	if($ext8){
		ielog_modify_photo( "$photos_dir/$tmp_name8" , $photos_dir."/".$lid."_8.".$ext8 ) ;
		if(in_array( strtolower( $ext8 ) , $ielog_normal_exts )) {
			if( ! ielog_create_thumb( $photos_dir."/".$lid."_8.".$ext8 , $lid."_8" , $ext8 ) ) {
				$xoopsDB->query( "DELETE FROM $table_photos WHERE lid=$lid" ) ;
				redirect_header( 'index.php?page=submit'.($lid ? '&lid='.$lid : '' ) , 2 , _MD_GNAV_MSG_FILEREADERROR ) ;
				exit ;
			}
		}
	}else{
		$ext8=$p_ext8;
	}


	//get size

	$resx=0;
	$resx1=0;
	$resx2=0;
	$resx3=0;
	$resx4=0;
	$resx5=0;
	$resx6=0;
	$resx7=0;
	$resx8=0;
	$resy=0;
	$resy1=0;
	$resy2=0;
	$resy3=0;
	$resy4=0;
	$resy5=0;
	$resy6=0;
	$resy7=0;
	$resy8=0;

	if($ext && in_array( strtolower( $ext ) , $ielog_normal_exts )){
		$dim = GetImageSize( "$photos_dir/$lid.$ext" ) ;
		if( $dim ) {$resx=$dim[0];$resy=$dim[1];}
	}
	if($ext1 && in_array( strtolower( $ext1 ) , $ielog_normal_exts )){
		$dim = GetImageSize( $photos_dir."/".$lid."_1.".$ext1 ) ;
		if( $dim ) {$resx1=$dim[0];$resy1=$dim[1];}
	}
	if($ext2 && in_array( strtolower( $ext2 ) , $ielog_normal_exts )){
		$dim = GetImageSize( $photos_dir."/".$lid."_2.".$ext2 ) ;
		if( $dim ) {$resx2=$dim[0];$resy2=$dim[1];}
	}
	if($ext3 && in_array( strtolower( $ext3 ) , $ielog_normal_exts )){
		$dim = GetImageSize( $photos_dir."/".$lid."_3.".$ext3 ) ;
		if( $dim ) {$resx3=$dim[0];$resy3=$dim[1];}
	}
	if($ext4 && in_array( strtolower( $ext4 ) , $ielog_normal_exts )){
		$dim = GetImageSize( $photos_dir."/".$lid."_4.".$ext4 ) ;
		if( $dim ) {$resx4=$dim[0];$resy4=$dim[1];}
	}
	if($ext5 && in_array( strtolower( $ext5 ) , $ielog_normal_exts )){
		$dim = GetImageSize( $photos_dir."/".$lid."_5.".$ext5 ) ;
		if( $dim ) {$resx5=$dim[0];$resy5=$dim[1];}
	}
	if($ext6 && in_array( strtolower( $ext6 ) , $ielog_normal_exts )){
		$dim = GetImageSize( $photos_dir."/".$lid."_6.".$ext6 ) ;
		if( $dim ) {$resx6=$dim[0];$resy6=$dim[1];}
	}
	if($ext7 && in_array( strtolower( $ext7 ) , $ielog_normal_exts )){
		$dim = GetImageSize( $photos_dir."/".$lid."_7.".$ext7 ) ;
		if( $dim ) {$resx7=$dim[0];$resy7=$dim[1];}
	}
	if($ext8 && in_array( strtolower( $ext8 ) , $ielog_normal_exts )){
		$dim = GetImageSize( $photos_dir."/".$lid."_8.".$ext8 ) ;
		if( $dim ) {$resx8=$dim[0];$resy8=$dim[1];}
	}



	ielog_update_desc($mode,$lid,$cid,$title,$submitter,$valid,
							$ext,$ext1,$ext2,$ext3,$ext4,$ext5,$ext6,$ext7,$ext8,$resx,$resy,$resx1,$resy1,$resx2,$resy2,$resx3,$resy3,$resx4,$resy4,$resx5,$resy5,$resx6,$resy6,$resx7,$resy7,$resx8,$resy8,
							$caption,$caption1,$caption2,$caption3,$caption4,$caption5,$caption6,$caption7,$caption8,
                            $desc_text,$arrow_html,$addinfo);

	$redirect_uri = "index.php?lid=$lid" ;

	if( $mode == G_INSERT){
		ielog_clear_tmp_files( $photos_dir ) ;
		redirect_header( $redirect_uri , 2 , _MD_GNAV_MSG_RECEIVED ) ;
	}else{
		redirect_header( $redirect_uri , 2 , _MD_GNAV_MSG_DBUPDATED ) ;
	}

	exit ;

}

// Confirm Delete
if( ! empty( $_POST['conf_delete'] ) ) {



	if( ! ( $global_perms & GNAV_GPERM_DELETABLE ) ) {
		redirect_header( $mod_url.'/' , 3 , _NOPERM ) ;
		exit ;
	}

	include( XOOPS_ROOT_PATH."/include/cp_functions.php" ) ;
	include(XOOPS_ROOT_PATH."/header.php");

	echo "<h2>"._MD_GNAV_SMT_DELETE."</h2><hr />";

	$xoops_module_header="<link rel='stylesheet' type='text/css' href='css/gnavi.css'/>";
	$xoopsTpl->assign('xoops_module_header',$xoops_module_header);

	$result = $xoopsDB->query( "SELECT l.lid, l.cid,l.cid1,l.cid2,l.cid3,l.cid4, l.title,l.caption,l.caption1,l.caption2,l.caption3,l.caption4,l.caption5,l.caption6,l.caption7,l.caption8, l.poster_name,l.icd,l.url,l.tel,l.fax,l.zip,l.other1,l.other2,l.other3,l.other4,l.other5,l.other6,l.other7,l.other8,l.other9,l.other10,l.other11,l.other12,l.other13,l.other14,l.other15,l.other16,l.other17,l.other18,l.other19,l.other20,l.address,l.rss,l.lat,l.lng,l.zoom,l.mtype, l.ext,l.ext1,l.ext2,l.ext3,l.ext4,l.ext5,l.ext6,l.ext7,l.ext8, l.res_x, l.res_y,l.res_x1, l.res_y1,l.res_x2, l.res_y2,l.res_x3,l.res_y3,l.res_x4,l.res_y4,l.res_x5,l.res_y5,l.res_x6,l.res_y6,l.res_x7,l.res_y7,l.res_x8,l.res_y8, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.submitter, t.description,t.arrowhtml,t.addinfo FROM $table_photos l LEFT JOIN $table_text t ON l.lid=t.lid WHERE l.lid=$lid" ) ;
	$photo = $xoopsDB->fetchArray( $result ) ;
	// Display
	$photo = ielog_get_array_for_photo_assign( $photo ) ;
	$photo = ielog_photo_assign($photo);
	$tpl = new XoopsTpl() ;
	$tpl->assign( $ielog_assign_globals ) ;
	$tpl->assign( 'photo' , $photo ) ;

	$msg = "<form action='index.php?page=submit&lid=$lid' method='post'>
			".$xoopsGTicket->getTicketHtml( __LINE__ )."
			<table><tr><td><div style='font-size:15px;font-weight:bold;'>"._MD_GNAV_SMT_ASKDELETE."</div></td><td align='left'><input type='submit' name='do_delete' value='"._YES."' />&nbsp;<input type='submit' name='cancel_delete' value="._NO." /></td></tr></table>
		</form>" ;


	echo $msg."<hr />";
	$tpl->display( "db:{$mydirname}_itemheader.html" ) ;
	echo "<hr />".$msg;

	ielog_footer() ;
	include( XOOPS_ROOT_PATH . "/footer.php" ) ;
	exit ;
}



// Editing Display
include(XOOPS_ROOT_PATH."/header.php");
include_once( "../../class/xoopsformloader.php" ) ;
include_once( "../../include/xoopscodes.php" ) ;

echo "<h2>".($mode == G_INSERT ? _MD_GNAV_SMT_UPLOAD : _MD_GNAV_SMT_EDIT )."</h2><hr />";



// Preview
if(!empty( $_POST['preview'] ) || $mode==G_UPDATE) {

	if(!empty( $_POST['preview'] ) ) {

		if($mode==G_INSERT){
			$date=time();
			$hits=0;
			$status=1;
		}else{
			$result = $xoopsDB->query( "SELECT status,date,hits FROM $table_photos WHERE l.lid=$lid" ) ;
			list($status,$date,$hits) = $xoopsDB->fetchRow( $result ) ;
			$date = empty( $_POST['store_timestamp'] ) ? $date : time() ;
		}

		// Display Preview
		$photo = array(
			'cid' => $cid,
			'cid1' => $cid1,
			'cid2' => $cid2,
			'cid3' => $cid3,
			'cid4' => $cid4,
			'icd' => $icd,
			'submitter' => $submitter ,
			'title' => $myts->makeTboxData4Show( $title ) ,
			'caption' => $myts->makeTboxData4Show( $caption ) ,
			'caption1' => $myts->makeTboxData4Show( $caption1 ) ,
			'caption2' => $myts->makeTboxData4Show( $caption2 ) ,
			'caption3' => $myts->makeTboxData4Show( $caption3 ) ,
			'caption4' => $myts->makeTboxData4Show( $caption4 ) ,
			'caption5' => $myts->makeTboxData4Show( $caption5 ) ,
			'caption6' => $myts->makeTboxData4Show( $caption6 ) ,
			'caption7' => $myts->makeTboxData4Show( $caption7 ) ,
			'caption8' => $myts->makeTboxData4Show( $caption8 ) ,
			'description' => $myts->displayTarea( $desc_text , $arrow_html , 1 , 1 , 1 , $arrow_br  , 1) ,
			'addinfo' => ielog_addinfo_array($addinfo,$myts) ,
			'submitter_name' => $myts->makeTboxData4Show( $poster_name ) ,
			'poster_name' =>  $myts->makeTboxData4Show( $poster_name ) ,
			'url' => $myts->makeTboxData4Show( $url ) ,
			'tel' => $myts->makeTboxData4Show( $tel ) ,
			'fax' => $myts->makeTboxData4Show( $fax ) ,
			'zip' => $myts->makeTboxData4Show( $zip ) ,
			'other1' => $myts->makeTboxData4Show( $other1 ) ,
			'other2' => $myts->makeTboxData4Show( $other2 ) ,
			'other3' => $myts->makeTboxData4Show( $other3 ) ,
			'other4' => $myts->makeTboxData4Show( $other4 ) ,
			'other5' => $myts->makeTboxData4Show( $other5 ) ,
			'other6' => $myts->makeTboxData4Show( $other6 ) ,
			'other7' => $myts->makeTboxData4Show( $other7 ) ,
			'other8' => $myts->makeTboxData4Show( $other8 ) ,
			'other9' => $myts->makeTboxData4Show( $other9 ) ,
			'other10' => $myts->makeTboxData4Show( $other10 ) ,
			'other11' => $myts->makeTboxData4Show( $other11 ) ,
			'other12' => $myts->makeTboxData4Show( $other12 ) ,
			'other13' => $myts->makeTboxData4Show( $other13 ) ,
			'other14' => $myts->makeTboxData4Show( $other14 ) ,
			'other15' => $myts->makeTboxData4Show( $other15 ) ,
			'other16' => $myts->makeTboxData4Show( $other16 ) ,
			'other17' => $myts->makeTboxData4Show( $other17 ) ,
			'other18' => $myts->makeTboxData4Show( $other18 ) ,
			'other19' => $myts->makeTboxData4Show( $other19 ) ,
			'other20' => $myts->makeTboxData4Show( $other20 ) ,
			'address' => $myts->makeTboxData4Show( $address ) , 
			'rss' => $myts->makeTboxData4Show( $rss ) , 
			'lat' =>  $lat ,
			'lng' =>  $lng ,
			'zoom' =>  $zoom,
			'mtype' =>  $mtype,
			'datetime' => formatTimestamp( $date , 'm' ) ,
			'hits' => $hits,
			'status' => $status,
			'is_newphoto' => ( $date > time() - 86400 * $ielog_newdays && $status == 1 ) , 
			'is_updatedphoto' => ( $date > time() - 86400 * $ielog_newdays && $status == 2 ) , 
			'is_popularphoto' => ( $hits >= $ielog_popular ) 
		) ;

		$orgfile_name=$orgfile_name1=$orgfile_name2=$orgfile_name3=$orgfile_name4=$orgfile_name5=$orgfile_name6=$orgfile_name7=$orgfile_name8="";

		if($mode!=G_INSERT){
			$prs = $xoopsDB->query( "SELECT ext,ext1,ext2,ext3,ext4,ext5,ext6,ext7,ext8 FROM $table_photos WHERE lid=$lid") ;
			list($p_ext,$p_ext1,$p_ext2,$p_ext3,$p_ext3,$p_ext4,$p_ext5,$p_ext6,$p_ext7,$p_ext8) = $xoopsDB->fetchRow( $prs ) ;
			if($p_ext ) $orgfile_name =$lid.".".$p_ext ;
			if($p_ext1) $orgfile_name1=$lid."_1.".$p_ext1 ;
			if($p_ext2) $orgfile_name2=$lid."_2.".$p_ext2 ;
			if($p_ext3) $orgfile_name3=$lid."_3.".$p_ext3 ;
			if($p_ext4) $orgfile_name4=$lid."_4.".$p_ext4 ;
			if($p_ext5) $orgfile_name5=$lid."_5.".$p_ext5 ;
			if($p_ext6) $orgfile_name6=$lid."_6.".$p_ext6 ;
			if($p_ext7) $orgfile_name7=$lid."_7.".$p_ext7 ;
			if($p_ext8) $orgfile_name8=$lid."_8.".$p_ext8 ;
		}

		$preview_name  = ielog_submit_uploader_pre(@$_POST['xoops_upload_file'][0],$preview_name ,$del_photo ,$orgfile_name );
		$preview_name1 = ielog_submit_uploader_pre(@$_POST['xoops_upload_file'][1],$preview_name1,$del_photo1,$orgfile_name1);
		$preview_name2 = ielog_submit_uploader_pre(@$_POST['xoops_upload_file'][2],$preview_name2,$del_photo2,$orgfile_name2);
		$preview_name3 = ielog_submit_uploader_pre(@$_POST['xoops_upload_file'][3],$preview_name3,$del_photo3,$orgfile_name3);
		$preview_name4 = ielog_submit_uploader_pre(@$_POST['xoops_upload_file'][4],$preview_name4,$del_photo4,$orgfile_name4);
		$preview_name5 = ielog_submit_uploader_pre(@$_POST['xoops_upload_file'][5],$preview_name5,$del_photo5,$orgfile_name5);
		$preview_name6 = ielog_submit_uploader_pre(@$_POST['xoops_upload_file'][6],$preview_name6,$del_photo6,$orgfile_name6);
		$preview_name7 = ielog_submit_uploader_pre(@$_POST['xoops_upload_file'][7],$preview_name7,$del_photo7,$orgfile_name7);
		$preview_name8 = ielog_submit_uploader_pre(@$_POST['xoops_upload_file'][8],$preview_name8,$del_photo8,$orgfile_name8);

		$photo = ielog_get_img_attribs_for_preview($photo,$preview_name,$preview_name1,$preview_name2,$preview_name3,$preview_name4,$preview_name5,$preview_name6,$preview_name7,$preview_name8);

	}else{

		// Get the record
		$result = $xoopsDB->query( "SELECT l.lid, l.cid,l.cid1,l.cid2,l.cid3,l.cid4, l.title,l.caption,l.caption1,l.caption2,l.caption3,l.caption4,l.caption5,l.caption6,l.caption7,l.caption8,l.poster_name,l.icd,l.url,l.tel,l.fax,l.other1,l.other2,l.other3,l.other4,l.other5,l.other6,l.other7,l.other8,l.other9,l.other10,l.other11,l.other12,l.other13,l.other14,l.other15,l.other16,l.other17,l.other18,l.other19,l.other20,l.zip,l.address,l.rss,l.lat,l.lng,l.zoom,l.mtype, l.ext,l.ext1,l.ext2,l.ext3,l.ext4,l.ext5,l.ext6,l.ext7,l.ext8, l.res_x, l.res_y,l.res_x1, l.res_y1,l.res_x2, l.res_y2,l.res_x3,l.res_y3,l.res_x4,l.res_y4,l.res_x5,l.res_y5,l.res_x6,l.res_y6,l.res_x7,l.res_y7,l.res_x8,l.res_y8, l.status, l.date, l.hits, l.rating, l.votes, l.comments, l.submitter, t.description,t.arrowhtml,t.addinfo FROM $table_photos l LEFT JOIN $table_text t ON l.lid=t.lid WHERE l.lid=$lid" ) ;
		$photo = $xoopsDB->fetchArray( $result ) ;
		$photo = ielog_get_array_for_photo_assign( $photo ) ;

		if($photo['ext' ]){
			$preview_name = $lid.".".$photo['ext'];
		}
		if($photo['ext1']){
			$preview_name1 = $lid."_1.".$photo['ext1'];
		}
		if($photo['ext2']){
			$preview_name2 = $lid."_2.".$photo['ext2'];
		}
		if($photo['ext3']){
			$preview_name3 = $lid."_3.".$photo['ext3'];
		}
		if($photo['ext4']){
			$preview_name4 = $lid."_4.".$photo['ext4'];
		}
		if($photo['ext5']){
			$preview_name5 = $lid."_5.".$photo['ext5'];
		}
		if($photo['ext6']){
			$preview_name6 = $lid."_6.".$photo['ext6'];
		}
		if($photo['ext7']){
			$preview_name7 = $lid."_7.".$photo['ext7'];
		}
		if($photo['ext8']){
			$preview_name8 = $lid."_8.".$photo['ext8'];
		}

	}

	//photo assign
	$photo = ielog_photo_assign($photo);
	$photo['mycat'] = ielog_get_mycat($photo['cid'],$photo['cid1'],$photo['cid2'],$photo['cid3'],$photo['cid4']);
	$tpl = new XoopsTpl() ;
	$tpl->assign( $ielog_assign_globals ) ;
	$tpl->assign( 'photo' , $photo ) ;
	$tpl->display( "db:{$mydirname}_itemheader.html" ) ;

	$imgsrc_photo  = $photo['imgsrc_photo'] ;
	$imgsrc_photo1 = $photo['imgsrc_photo1'] ;
	$imgsrc_photo2 = $photo['imgsrc_photo2'] ;
	$imgsrc_photo3 = $photo['imgsrc_photo3'] ;
	$imgsrc_photo4 = $photo['imgsrc_photo4'] ;
	$imgsrc_photo5 = $photo['imgsrc_photo5'] ;
	$imgsrc_photo6 = $photo['imgsrc_photo6'] ;
	$imgsrc_photo7 = $photo['imgsrc_photo7'] ;
	$imgsrc_photo8 = $photo['imgsrc_photo8'] ;

}


//make forms data
if(!empty( $_POST['preview'] )){

	$photo = array(
		'lid'	=> $lid ,
		'title'	=> $title ,
		'cid'	=> $cid ,
		'cid1'	=> $cid1,
		'cid2'	=> $cid2,
		'cid3' 	=> $cid3,
		'cid4' 	=> $cid4,
		'icd' 	=> $icd,
		'ext' 	=> $photo['ext' ] ,
		'ext1' 	=> $photo['ext1'] ,
		'ext2' 	=> $photo['ext2'] ,
		'ext3' 	=> $photo['ext3'] ,
		'ext4' 	=> $photo['ext4'] ,
		'ext5' 	=> $photo['ext5'] ,
		'ext6' 	=> $photo['ext6'] ,
		'ext7' 	=> $photo['ext7'] ,
		'ext8' 	=> $photo['ext8'] ,
		'caption' 	=> $caption ,
		'caption1' 	=> $caption1 ,
		'caption2' 	=> $caption2 ,
		'caption3' 	=> $caption3 ,
		'caption4' 	=> $caption4 ,
		'caption5' 	=> $caption5 ,
		'caption6' 	=> $caption6 ,
		'caption7' 	=> $caption7 ,
		'caption8' 	=> $caption8 ,
		'url' 	=> $url ,
		'tel' 	=> $tel ,
		'fax' 	=> $fax ,
		'zip' 	=> $zip ,
		'other1' 	=> $other1 ,
		'other2' 	=> $other2 ,
		'other3' 	=> $other3 ,
		'other4' 	=> $other4 ,
		'other5' 	=> $other5 ,
		'other6' 	=> $other6 ,
		'other7' 	=> $other7 ,
		'other8' 	=> $other8 ,
		'other9' 	=> $other9 ,
		'other10' 	=> $other10 ,
		'other11' 	=> $other11 ,
		'other12' 	=> $other12 ,
		'other13' 	=> $other13 ,
		'other14' 	=> $other14 ,
		'other15' 	=> $other15 ,
		'other16' 	=> $other16 ,
		'other17' 	=> $other17 ,
		'other18' 	=> $other18 ,
		'other19' 	=> $other19 ,
		'other20' 	=> $other20 ,
		'address' 	=> $address ,
		'rss' 	=> $rss ,
		'lat' 	=>$lat ,
		'lng' 	=>$lng ,
		'zoom' 	=>$zoom,
		'mtype' 	=>$mtype,
		'submitter' => $submitter ,
		'poster_name' 	=> $poster_name,
		'description' 	=> $desc_text ,
		'arrowhtml' 	=> ( !($global_perms & GNAV_GPERM_WYSIWYG) ? 0 : $body_html) ,
		'addinfo' 	=> $addinfo ,
		'status' 	=> $p_status ,
		'valid' 	=> $p_valid ,
		'imgsrc_photo' 	=> $imgsrc_photo ,
		'imgsrc_photo1' 	=> $imgsrc_photo1 ,
		'imgsrc_photo2' 	=> $imgsrc_photo2 ,
		'imgsrc_photo3' 	=> $imgsrc_photo3 ,
		'imgsrc_photo4' 	=> $imgsrc_photo4 ,
		'imgsrc_photo5' 	=> $imgsrc_photo5 ,
		'imgsrc_photo6' 	=> $imgsrc_photo6 ,
		'imgsrc_photo7' 	=> $imgsrc_photo7 ,
		'imgsrc_photo8' 	=> $imgsrc_photo8 ,
	) ;

}else{

	if($mode==G_INSERT){

		$photo = array(
			'lid'	=> 0 ,
			'title'	=> '' ,
			'cid'	=> $p_cid ,
			'cid1'	=> 0,
			'cid2'	=> 0,
			'cid3' 	=> 0,
			'cid4' 	=> 0,
			'icd' 	=> 0,
			'ext' 	=> '' ,
			'ext1' 	=> '' ,
			'ext2' 	=> '' ,
			'ext3' 	=> '' ,
			'ext4' 	=> '' ,
			'ext5' 	=> '' ,
			'ext6' 	=> '' ,
			'ext7' 	=> '' ,
			'ext8' 	=> '' ,
			'caption' 	=> '' ,
			'caption1' 	=> '' ,
			'caption2' 	=> '' ,
			'caption3' 	=> '' ,
			'caption4' 	=> '' ,
			'caption5' 	=> '' ,
			'caption6' 	=> '' ,
			'caption7' 	=> '' ,
			'caption8' 	=> '' ,
			'url' 	=> '' ,
			'tel' 	=> '' ,
			'fax' 	=> '' ,
			'zip' 	=> '' ,
			'other1' 	=> '' ,
			'other2' 	=> '' ,
			'other3' 	=> '' ,
			'other4' 	=> '' ,
			'other5' 	=> '' ,
			'other6' 	=> '' ,
			'other7' 	=> '' ,
			'other8' 	=> '' ,
			'other9' 	=> '' ,
			'other10' 	=> '' ,
			'other11' 	=> '' ,
			'other12' 	=> '' ,
			'other13' 	=> '' ,
			'other14' 	=> '' ,
			'other15' 	=> '' ,
			'other16' 	=> '' ,
			'other17' 	=> '' ,
			'other18' 	=> '' ,
			'other19' 	=> '' ,
			'other20' 	=> '' ,
			'address' 	=> '' ,
			'rss' 	=> '' ,
			'lat' 	=> 0 ,
			'lng' 	=> 0 ,
			'zoom' 	=> 0 ,
			'mtype' 	=> '' ,
			'submitter' => $submitter ,
			'poster_name' 	=> ( $my_uid > 0 ? ielog_get_name_from_uid( $my_uid ) : '' ),
			'description' 	=> '' ,
			'addinfo' 	=> '' ,
			'status' 	=> 0 ,
			'valid' 	=> 0 ,
		) ;

	}else{

		$result = $xoopsDB->query( "SELECT l.lid,l.title,l.cid,l.cid1,l.cid2,l.cid3,l.cid4,l.ext,l.ext1,l.ext2,l.ext3,l.ext4,l.ext5,l.ext6,l.ext7,l.ext8,l.caption,l.caption1,l.caption2,l.caption3,l.caption4,l.caption5,l.caption6,l.caption7,l.caption8,l.url,l.tel,l.fax,l.zip,l.other1,l.other2,l.other3,l.other4,l.other5,l.other6,l.other7,l.other8,l.other9,l.other10,l.other11,l.other12,l.other13,l.other14,l.other15,l.other16,l.other17,l.other18,l.other19,l.other20,l.address,l.rss,l.lat,l.lng,l.zoom,l.mtype,l.icd,l.submitter,l.poster_name,l.status,t.description,t.arrowhtml,t.addinfo FROM $table_photos l LEFT JOIN $table_text t ON l.lid=t.lid WHERE l.lid=$lid" ) ;
		$photo = $xoopsDB->fetchArray( $result ) ;
		$photo['imgsrc_photo'] 	= $imgsrc_photo ;
		$photo['imgsrc_photo1'] = $imgsrc_photo1 ;
		$photo['imgsrc_photo2'] = $imgsrc_photo2 ;
		$photo['imgsrc_photo3'] = $imgsrc_photo3 ;
		$photo['imgsrc_photo4'] = $imgsrc_photo4 ;
		$photo['imgsrc_photo5'] = $imgsrc_photo5 ;
		$photo['imgsrc_photo6'] = $imgsrc_photo6 ;
		$photo['imgsrc_photo7'] = $imgsrc_photo7 ;
		$photo['imgsrc_photo8'] = $imgsrc_photo8 ;
		$photo['valid'] = $photo['status'] ;

		//map hidden
		if ($photo['lat']==0 && $photo['lng']==0) $p_set_latlng = 0 ;
		$p_cid = $photo['cid'] ;

	}

	//set map default
	if ($photo['lat']==0 && $photo['lng']==0){

		if ($p_cid) {
			$result = $xoopsDB->query( "SELECT lat,lng,zoom,mtype FROM $table_cat WHERE cid=$p_cid" ) ;
			list($lat,$lng,$zoom,$mtype) = $xoopsDB->fetchRow( $result ) ;
		}else{
			$lat=0;
			$lng=0;
			$zoom=0;
			$mtype='';
		}

		$photo['lat']  = $p_lat  ? $p_lat  : ($lat!=0  ? $lat  : $ielog_defaultlat);
		$photo['lng']  = $p_lng  ? $p_lng  : ($lng!=0  ? $lng  : $ielog_defaultlng);
		$photo['zoom'] = $p_zoom ? $p_zoom : ($zoom!=0 ? $zoom : $ielog_defaultzoom);
		$photo['mtype'] = $p_mtype ? $p_mtype : ($mtype ? $mtype : $ielog_defaultmtype);

	}
}


// Show the form
OpenTable() ;
$form = new XoopsThemeForm( ($mode == G_INSERT ? _MD_GNAV_SMT_UPLOAD : _MD_GNAV_SMT_EDIT ) , "uploadphoto", "index.php?page=submit" ) ;
$form->setExtra("enctype='multipart/form-data'");
$xoops_module_header="<link rel='stylesheet' type='text/css' href='css/gnavi.css'/>";

//each setting of insert or update; 

if($mode == G_INSERT){
	$canuse_editor = $global_perms & GNAV_GPERM_WYSIWYG ;
}else{
	$canuse_editor = $global_perms & GNAV_GPERM_WYSIWYG && ( $my_uid == $photo['submitter'] || $photo['arrowhtml'] ) ? 1 : 0 ;
	if(!$photo['arrowhtml']){
		if( $ielog_body_editor == 'common_fckeditor' && $canuse_editor ||
			$ielog_body_editor == 'common_spaw' && file_exists( XOOPS_ROOT_PATH.'/common/spaw/spaw_control.class.php' ) && $canuse_editor  ||
			$ielog_body_editor == 'pure_html' && $canuse_editor ){

			//if use editor with already inputed dhtml,change data for html

			$photo['description'] = $myts->displayTarea( $photo['description'] , 0 , 1 , 1 , 1 , 1 , 1) ;

		}
	}

	$status_hidden = new XoopsFormHidden( "old_status" , $photo['status'] ) ;
	$valid_or_not = $photo['valid'] ? 1 : 0 ;
	$valid_box = new XoopsFormCheckBox( _MD_GNAV_SMT_VALIDPHOTO , "valid" , array( $valid_or_not ) ) ;
	$valid_box->addOption( '1' , '&nbsp;' ) ;
	$storets_box = new XoopsFormCheckBox(_MD_GNAV_SMT_UPDATEDATE, "store_timestamp" , array( 0 ) ) ;
	$storets_box->addOption( '1' , '&nbsp;' ) ;

	if( $global_perms & GNAV_GPERM_DELETABLE ) {
		$del_tray = new XoopsFormElementTray(_MD_GNAV_SMT_DELETE) ;
		$delete_button = new XoopsFormButton( "" , "conf_delete" , _DELETE , "submit" ) ;
		$del_tray->addElement( $delete_button ) ;
	}
}

//labels
$pixels_text = "$ielog_width x $ielog_height" ;
if( $ielog_canresize ) $pixels_text .=_MD_GNAV_ITM_AUTORESIZE ;
$pixels_label = new XoopsFormLabel( _MD_GNAV_ITM_ABOUTFILE ,sprintf( _MD_GNAV_ITM_ABOUTFILEDESC,$pixels_text,strval(intval(intval($ielog_fsize)/1048576*10)/10))) ;
$cation_label = new XoopsFormLabel( _MD_GNAV_ITM_CAUTION , _MD_GNAV_ITM_ABOUTUPLOADS ) ;


$title_text = new XoopsFormText( _MD_GNAV_ITM_TITLE, "title" , 50 , 255 , $myts->makeTboxData4Edit( $photo['title'] ) ) ;
$caption_text  = new XoopsFormText(_MD_GNAV_ITM_CAPTION1, "caption" , 50 , 255 , $myts->makeTboxData4Edit( $photo['caption'] ) ) ;
$caption1_text = new XoopsFormText(_MD_GNAV_ITM_CAPTION2, "caption1" , 50 , 255 , $myts->makeTboxData4Edit( $photo['caption1'] ) ) ;
$caption2_text = new XoopsFormText(_MD_GNAV_ITM_CAPTION3, "caption2" , 50 , 255 , $myts->makeTboxData4Edit( $photo['caption2'] ) ) ;
$caption3_text = new XoopsFormText(_MD_GNAV_ITM_CAPTION4, "caption3" , 50 , 255 , $myts->makeTboxData4Edit( $photo['caption3'] ) ) ;
$caption4_text = new XoopsFormText(_MD_GNAV_ITM_CAPTION5, "caption4" , 50 , 255 , $myts->makeTboxData4Edit( $photo['caption4'] ) ) ;
$caption5_text = new XoopsFormText(_MD_GNAV_ITM_CAPTION6, "caption5" , 50 , 255 , $myts->makeTboxData4Edit( $photo['caption5'] ) ) ;
$caption6_text = new XoopsFormText(_MD_GNAV_ITM_CAPTION7, "caption6" , 50 , 255 , $myts->makeTboxData4Edit( $photo['caption6'] ) ) ;
$caption7_text = new XoopsFormText(_MD_GNAV_ITM_CAPTION8, "caption7" , 50 , 255 , $myts->makeTboxData4Edit( $photo['caption7'] ) ) ;
$caption8_text = new XoopsFormText(_MD_GNAV_ITM_CAPTION9, "caption8" , 50 , 255 , $myts->makeTboxData4Edit( $photo['caption8'] ) ) ;

//----------------------------editor-------------------------------------------
if( $ielog_body_editor == 'common_fckeditor' && $canuse_editor ) {

	// FCKeditor in common/fckeditor/
	$xoops_module_header .= '
		<script type="text/javascript" src="'.XOOPS_URL.'/common/fckeditor/fckeditor.js"></script>
		<script type="text/javascript"><!--
			function fckeditor_exec() {
				var oFCKeditor = new FCKeditor( "desc_text" , "100%" , "500" , "Default" );
				
				oFCKeditor.BasePath = "'.XOOPS_URL.'/common/fckeditor/";
				
				oFCKeditor.ReplaceTextarea();
			}
		// --></script>
	' ;
	$wysiwyg_body = '<textarea id="desc_text" name="desc_text">'.htmlspecialchars( $photo['description'] ,ENT_QUOTES).'</textarea><script>fckeditor_exec();</script>' ;
	$desc_tarea =  new XoopsFormLabel( _MD_GNAV_ITM_DESC , $wysiwyg_body ) ;
	$hidden_body_html = new XoopsFormHidden("body_html","1") ;

} else if( $ielog_body_editor == 'common_spaw' && file_exists( XOOPS_ROOT_PATH.'/common/spaw/spaw_control.class.php' ) && $canuse_editor ) {

	// older spaw in common/spaw/
	include XOOPS_ROOT_PATH.'/common/spaw/spaw_control.class.php' ;
	ob_start() ;
	$sw = new SPAW_Wysiwyg( "desc_text" ,  $photo['description']  ) ;
	$sw->show() ;
	$wysiwyg_body = ob_get_contents() ;
	ob_end_clean() ;
	$desc_tarea =  new XoopsFormLabel( _MD_GNAV_ITM_DESC , $wysiwyg_body ) ;
	$hidden_body_html = new XoopsFormHidden("body_html","1") ;

}else if ($ielog_body_editor == 'pure_html' && $canuse_editor ){
	$desc_tarea = new XoopsFormTextArea(_MD_GNAV_ITM_DESC, "desc_text" , $myts->makeTareaData4Edit( $photo['description'] ) , 20 , 60 ) ;
	$hidden_body_html = new XoopsFormHidden("body_html","1") ;
} else {
	$desc_tarea = new XoopsFormDhtmlTextArea(_MD_GNAV_ITM_DESC, "desc_text" , $myts->makeTareaData4Edit( $photo['description'] ) , 20 , 60 ) ;
	$hidden_body_html = new XoopsFormHidden("body_html","0") ;
}

//---------------------------------------------------------------------------------
$add_info_text = new XoopsFormTextArea(_MD_GNAV_ITM_ADDINFO, "addinfo" , $myts->makeTareaData4Edit( $photo['addinfo'] ) , 6 , 50 ) ;
$add_info_desc = new XoopsFormLabel( "" , _MD_GNAV_ITM_ADDINFODESC ) ;

if( ielog_get_anony_perms() & GNAV_GPERM_INSERTABLE) {
	$poster_name_text = new XoopsFormText(_MD_GNAV_ITM_POSTERNAME, "poster_name" , 30 , 60 , $myts->makeTboxData4Edit( $photo['poster_name'] ) ) ;
}else{
	$poster_name_text = new XoopsFormHidden("poster_name",$myts->makeTboxData4Edit( $photo['poster_name'] )) ;
}

//category
$cat_select = new XoopsFormSelect( _MD_GNAV_ITM_CATMAIN , "cid" , $photo['cid'] ) ;
if($mode == G_INSERT)$cat_select->addOption( '' , '----' ) ;
$cat_select1 = new XoopsFormSelect( _MD_GNAV_ITM_CAT1 , "cid1" , $photo['cid1'] ) ;
$cat_select1->addOption( '' , '----' ) ;
$cat_select2 = new XoopsFormSelect( _MD_GNAV_ITM_CAT2 , "cid2" , $photo['cid2'] ) ;
$cat_select2->addOption( '' , '----' ) ;
$cat_select3 = new XoopsFormSelect( _MD_GNAV_ITM_CAT3 , "cid3" , $photo['cid3'] ) ;
$cat_select3->addOption( '' , '----' ) ;
$cat_select4 = new XoopsFormSelect( _MD_GNAV_ITM_CAT4 , "cid4" , $photo['cid4'] ) ;
$cat_select4->addOption( '' , '----' ) ;
$tree = $cattree->getChildTreeArray( 0 , "weight,title" ) ;
foreach( $tree as $leaf ) {
	$leaf['prefix'] = substr( $leaf['prefix'] , 0 , -1 ) ;
	$leaf['prefix'] = str_replace( "." , "--" , $leaf['prefix'] ) ;
	$cat_select->addOption( $leaf['cid'] , $leaf['prefix'] . $leaf['title'] ) ;
	$cat_select1->addOption( $leaf['cid'] , $leaf['prefix'] . $leaf['title'] ) ;
	$cat_select2->addOption( $leaf['cid'] , $leaf['prefix'] . $leaf['title'] ) ;
	$cat_select3->addOption( $leaf['cid'] , $leaf['prefix'] . $leaf['title'] ) ;
	$cat_select4->addOption( $leaf['cid'] , $leaf['prefix'] . $leaf['title'] ) ;
}

//fileform
if($photo['ext']){
	$photoview = new XoopsFormLabel(_MD_GNAV_ITM_FILE1, "<img src='".$photo['imgsrc_photo']."' width='150' />" ) ;
	$file_form = new XoopsFormFile(_MD_GNAV_ITM_EDIT_FILE1, "photofile" , $ielog_fsize ) ;
	$del_box = new XoopsFormCheckBox( "&nbsp;" , "del_photo" , array( 0 ) ) ;
	$del_box->addOption( '1' ,_MD_GNAV_ITM_DEL_FILE1) ;
	$del_hidden = new XoopsFormHidden( "del_photo",$del_photo) ;
}else{
	$file_form = new XoopsFormFile(_MD_GNAV_ITM_FILE1, "photofile" , $ielog_fsize ) ;
	if(!$ielog_allownoimage){
		$form->setRequired( $file_form ) ;
	}
}
$file_form->setExtra( "size='70'" ) ;
if($photo['ext1']){
	$photoview1 = new XoopsFormLabel(_MD_GNAV_ITM_FILE2, "<img src='".$photo['imgsrc_photo1']."' width='150' />" ) ;
	$file_form1 = new XoopsFormFile(_MD_GNAV_ITM_EDIT_FILE2, "photofile1" , $ielog_fsize ) ;
	$del_box1 = new XoopsFormCheckBox( "&nbsp;" , "del_photo1" , array( 0 ) ) ;
	$del_box1->addOption( '1' ,_MD_GNAV_ITM_DEL_FILE2) ;
}else{
	$file_form1 = new XoopsFormFile(_MD_GNAV_ITM_FILE2, "photofile1" , $ielog_fsize ) ;
	$del_hidden1 = new XoopsFormHidden( "del_photo1",$del_photo1) ;
}
$file_form1->setExtra( "size='70'" ) ;

if($photo['ext2']){
	$photoview2 = new XoopsFormLabel(_MD_GNAV_ITM_FILE3, "<img src='".$photo['imgsrc_photo2']."' width='150' />" ) ;
	$file_form2 = new XoopsFormFile(_MD_GNAV_ITM_EDIT_FILE3, "photofile2" , $ielog_fsize ) ;
	$del_box2 = new XoopsFormCheckBox( "&nbsp;" , "del_photo2" , array( 0 ) ) ;
	$del_box2->addOption( '1' ,_MD_GNAV_ITM_DEL_FILE3) ;
}else{
	$file_form2 = new XoopsFormFile(_MD_GNAV_ITM_FILE3, "photofile2" , $ielog_fsize ) ;
	$del_hidden2 = new XoopsFormHidden( "del_photo2",$del_photo2) ;
}
$file_form2->setExtra( "size='70'" ) ;


if($photo['ext3']){
	$photoview3 = new XoopsFormLabel(_MD_GNAV_ITM_FILE4, "<img src='".$photo['imgsrc_photo3']."' width='150' />" ) ;
	$file_form3 = new XoopsFormFile(_MD_GNAV_ITM_EDIT_FILE4, "photofile3" , $ielog_fsize ) ;
	$del_box3 = new XoopsFormCheckBox( "&nbsp;" , "del_photo3" , array( 0 ) ) ;
	$del_box3->addOption( '1' ,_MD_GNAV_ITM_DEL_FILE4) ;
}else{
	$file_form3 = new XoopsFormFile(_MD_GNAV_ITM_FILE4, "photofile3" , $ielog_fsize ) ;
	$del_hidden3 = new XoopsFormHidden( "del_photo3",$del_photo3) ;
}
$file_form3->setExtra( "size='70'" ) ;
if($photo['ext4']){
	$photoview4 = new XoopsFormLabel(_MD_GNAV_ITM_FILE5, "<img src='".$photo['imgsrc_photo4']."' width='150' />" ) ;
	$file_form4 = new XoopsFormFile(_MD_GNAV_ITM_EDIT_FILE5, "photofile4" , $ielog_fsize ) ;
	$del_box4 = new XoopsFormCheckBox( "&nbsp;" , "del_photo4" , array( 0 ) ) ;
	$del_box4->addOption( '1' ,_MD_GNAV_ITM_DEL_FILE5) ;
}else{
	$file_form4 = new XoopsFormFile(_MD_GNAV_ITM_FILE5, "photofile4" , $ielog_fsize ) ;
	$del_hidden4 = new XoopsFormHidden( "del_photo4",$del_photo4) ;
}
$file_form4->setExtra( "size='70'" ) ;
if($photo['ext5']){
	$photoview5 = new XoopsFormLabel(_MD_GNAV_ITM_FILE6, "<img src='".$photo['imgsrc_photo5']."' width='150' />" ) ;
	$file_form5 = new XoopsFormFile(_MD_GNAV_ITM_EDIT_FILE6, "photofile5" , $ielog_fsize ) ;
	$del_box5 = new XoopsFormCheckBox( "&nbsp;" , "del_photo5" , array( 0 ) ) ;
	$del_box5->addOption( '1' ,_MD_GNAV_ITM_DEL_FILE6) ;
}else{
	$file_form5 = new XoopsFormFile(_MD_GNAV_ITM_FILE6, "photofile5" , $ielog_fsize ) ;
	$del_hidden5 = new XoopsFormHidden( "del_photo5",$del_photo5) ;
}
$file_form5->setExtra( "size='70'" ) ;
if($photo['ext6']){
	$photoview6 = new XoopsFormLabel(_MD_GNAV_ITM_FILE7, "<img src='".$photo['imgsrc_photo6']."' width='150' />" ) ;
	$file_form6 = new XoopsFormFile(_MD_GNAV_ITM_EDIT_FILE7, "photofile6" , $ielog_fsize ) ;
	$del_box6 = new XoopsFormCheckBox( "&nbsp;" , "del_photo6" , array( 0 ) ) ;
	$del_box6->addOption( '1' ,_MD_GNAV_ITM_DEL_FILE7) ;
}else{
	$file_form6 = new XoopsFormFile(_MD_GNAV_ITM_FILE7, "photofile6" , $ielog_fsize ) ;
	$del_hidden6 = new XoopsFormHidden( "del_photo6",$del_photo6) ;
}
$file_form6->setExtra( "size='70'" ) ;
if($photo['ext7']){
	$photoview7 = new XoopsFormLabel(_MD_GNAV_ITM_FILE8, "<img src='".$photo['imgsrc_photo7']."' width='150' />" ) ;
	$file_form7 = new XoopsFormFile(_MD_GNAV_ITM_EDIT_FILE8, "photofile7" , $ielog_fsize ) ;
	$del_box7 = new XoopsFormCheckBox( "&nbsp;" , "del_photo7" , array( 0 ) ) ;
	$del_box7->addOption( '1' ,_MD_GNAV_ITM_DEL_FILE8) ;
}else{
	$file_form7 = new XoopsFormFile(_MD_GNAV_ITM_FILE8, "photofile7" , $ielog_fsize ) ;
	$del_hidden7 = new XoopsFormHidden( "del_photo7",$del_photo7) ;
}
$file_form7->setExtra( "size='70'" ) ;
if($photo['ext8']){
	$photoview8 = new XoopsFormLabel(_MD_GNAV_ITM_FILE9, "<img src='".$photo['imgsrc_photo8']."' width='150' />" ) ;
	$file_form8 = new XoopsFormFile(_MD_GNAV_ITM_EDIT_FILE9, "photofile8" , $ielog_fsize ) ;
	$del_box8 = new XoopsFormCheckBox( "&nbsp;" , "del_photo8" , array( 0 ) ) ;
	$del_box8->addOption( '1' ,_MD_GNAV_ITM_DEL_FILE9) ;
}else{
	$file_form8 = new XoopsFormFile(_MD_GNAV_ITM_FILE9, "photofile8" , $ielog_fsize ) ;
	$del_hidden8 = new XoopsFormHidden( "del_photo8",$del_photo8) ;
}
$file_form8->setExtra( "size='70'" ) ;

//other
$op_hidden = new XoopsFormHidden( "op" , "submit" ) ;
$counter_hidden = new XoopsFormHidden( "fieldCounter" , 1 ) ;
$preview_hidden = new XoopsFormHidden( "preview_name" , htmlspecialchars( $preview_name ) , ENT_QUOTES ) ;
$preview1_hidden = new XoopsFormHidden( "preview_name1" , htmlspecialchars( $preview_name1 ) , ENT_QUOTES ) ;
$preview2_hidden = new XoopsFormHidden( "preview_name2" , htmlspecialchars( $preview_name2 ) , ENT_QUOTES ) ;
$preview3_hidden = new XoopsFormHidden( "preview_name3" , htmlspecialchars( $preview_name3 ) , ENT_QUOTES ) ;
$preview4_hidden = new XoopsFormHidden( "preview_name4" , htmlspecialchars( $preview_name4 ) , ENT_QUOTES ) ;
$preview5_hidden = new XoopsFormHidden( "preview_name5" , htmlspecialchars( $preview_name5 ) , ENT_QUOTES ) ;
$preview6_hidden = new XoopsFormHidden( "preview_name6" , htmlspecialchars( $preview_name6 ) , ENT_QUOTES ) ;
$preview7_hidden = new XoopsFormHidden( "preview_name7" , htmlspecialchars( $preview_name7 ) , ENT_QUOTES ) ;
$preview8_hidden = new XoopsFormHidden( "preview_name8" , htmlspecialchars( $preview_name8 ) , ENT_QUOTES ) ;

$submit_button = new XoopsFormButton( "" , "submit" , _SUBMIT , "submit" ) ;
$preview_button = new XoopsFormButton( "" , "preview" , _PREVIEW , "submit" ) ;
$reset_button = new XoopsFormButton( "" , "reset" , ($mode == G_INSERT ? _MD_GNAV_SMT_CLEAR : _CANCEL ) , "reset" ) ;
$submit_tray = new XoopsFormElementTray( '' ) ;
$submit_tray->addElement( $preview_button ) ;
$submit_tray->addElement( $submit_button ) ;
$submit_tray->addElement( $reset_button ) ;
$lid_hidden = new XoopsFormHidden( "lid",$photo['lid']) ;


//moreinfo
$url_text = new XoopsFormText(_MD_GNAV_ITM_URL, "url" , 100 , 255 , $myts->makeTboxData4Edit( $photo['url'] ) ) ;
$tel_text = new XoopsFormText(_MD_GNAV_ITM_TEL, "tel" , 100 , 255 , $myts->makeTboxData4Edit( $photo['tel'] ) ) ;
$fax_text = new XoopsFormText(_MD_GNAV_ITM_FAX, "fax" , 100 , 255 , $myts->makeTboxData4Edit( $photo['fax'] ) ) ;
$zip_text = new XoopsFormText(_MD_GNAV_ITM_ZIP, "zip" , 100 , 255 , $myts->makeTboxData4Edit( $photo['zip'] ) ) ;
$other1_text = new XoopsFormText(_MD_GNAV_ITM_other1, "other1" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other1'] ) ) ;
$other2_text = new XoopsFormText(_MD_GNAV_ITM_other2, "other2" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other2'] ) ) ;
$other3_text = new XoopsFormText(_MD_GNAV_ITM_other3, "other3" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other3'] ) ) ;
$other4_text = new XoopsFormText(_MD_GNAV_ITM_other4, "other4" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other4'] ) ) ;
$other5_text = new XoopsFormText(_MD_GNAV_ITM_other5, "other5" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other5'] ) ) ;
$other6_text = new XoopsFormText(_MD_GNAV_ITM_other6, "other6" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other6'] ) ) ;
$other7_text = new XoopsFormText(_MD_GNAV_ITM_other7, "other7" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other7'] ) ) ;
$other8_text = new XoopsFormText(_MD_GNAV_ITM_other8, "other8" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other8'] ) ) ;
$other9_text = new XoopsFormText(_MD_GNAV_ITM_other9, "other9" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other9'] ) ) ;
$other10_text = new XoopsFormText(_MD_GNAV_ITM_other10, "other10" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other10'] ) ) ;
$other11_text = new XoopsFormText(_MD_GNAV_ITM_other11, "other11" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other11'] ) ) ;
$other12_text = new XoopsFormText(_MD_GNAV_ITM_other12, "other12" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other12'] ) ) ;
$other13_text = new XoopsFormText(_MD_GNAV_ITM_other13, "other13" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other13'] ) ) ;
$other14_text = new XoopsFormText(_MD_GNAV_ITM_other14, "other14" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other14'] ) ) ;
$other15_text = new XoopsFormText(_MD_GNAV_ITM_other15, "other15" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other15'] ) ) ;
$other16_text = new XoopsFormText(_MD_GNAV_ITM_other16, "other16" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other16'] ) ) ;
$other17_text = new XoopsFormText(_MD_GNAV_ITM_other17, "other17" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other17'] ) ) ;
$other18_text = new XoopsFormText(_MD_GNAV_ITM_other18, "other18" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other18'] ) ) ;
$other19_text = new XoopsFormText(_MD_GNAV_ITM_other19, "other19" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other19'] ) ) ;
$other20_text = new XoopsFormText(_MD_GNAV_ITM_other20, "other20" , 100 , 255 , $myts->makeTboxData4Edit( $photo['other20'] ) ) ;
$rss_text = new XoopsFormText(_MD_GNAV_ITM_RSS, "rss" , 100 , 255 , $myts->makeTboxData4Edit( $photo['rss'] ) ) ;



if($language=='japanese' || $language=='ja_utf8' ){
	if(file_exists(XOOPS_ROOT_PATH.$ielog_ajaxzip_place."ajaxzip2.js")){
$xoops_module_header .="
<script src='js/prototype.js' charset='UTF-8'></script>
<script src='".XOOPS_URL.$ielog_ajaxzip_place."ajaxzip2.js' charset='UTF-8'></script>
<script type='text/javascript'>
//<![CDATA[
	AjaxZip2.JSONDATA = '".XOOPS_URL.$ielog_ajaxzip_place."data';
//]]>
</script>
";
	$zip_text->setExtra("onKeyUp=\"AjaxZip2.zip2addr(this,'address','address');\"");
	}
}

$address_tray = new XoopsFormElementTray(_MD_GNAV_ITM_ADDRESS,'' );
$address_text = new XoopsFormText( "" , "address" , 50 , 255 , $myts->makeTboxData4Edit( $photo['address'] ) ) ;
$address_tray->addElement($address_text);
if($ielog_usegooglemap){
	$geo_button = new XoopsFormButton( "" , "geo" ,_MD_GNAV_MAP_SEARCH, "button" ) ;
	$geo_button->setExtra("onClick=\"showAddress(document.getElementById('address').value);\"");
	$address_tray->addElement($geo_button);
}

//Google Maps
if($ielog_usegooglemap){
$xoops_module_header .="<script src='".$ielog_googlemap_url."/maps?file=api&amp;v=2&amp;key=$ielog_googlemapapi_key' type='text/javascript' charset='utf-8'></script>
<script src='js/map.js' type='text/javascript' charset='utf-8'></script>
<script type='text/javascript'>
//<![CDATA[
	$ielog_lang_java
	window.onload = InputGMap;
//]]>
</script>";

if($p_set_latlng){
	$set_latlng_state = '' ;
}else{
	$set_latlng_state = ' checked ' ;
}

$gmap = new XoopsFormLabel(_MD_GNAV_MAP, "
<div style='margin-bottom:2px;'><input type='checkbox' name='set_latlng' id='set_latlng' value='1' onclick='ChangeMapArea(this)' $set_latlng_state/>&nbsp;"._MD_GNAV_MAP_UNINPUT."</div>
<div id='maparea'>
<div id='map' style='width:100%;height:400px;'></div>
<div id='gn_latlng'>"._MD_GNAV_MAP_LAT.":&nbsp;<span id='slat'>".$myts->makeTboxData4Edit($photo['lat'])."</span>&nbsp;&nbsp;&nbsp;"._MD_GNAV_MAP_LNG.":&nbsp;<span id='slng'>".$myts->makeTboxData4Edit($photo['lng'])."</span>&nbsp;&nbsp;&nbsp;"._MD_GNAV_MAP_ZOOM.":&nbsp;<span id='sz'>".$myts->makeTboxData4Edit($photo['zoom'])."</span></div>
<input type='hidden' name='lat' id='lat' size='20' value='".$myts->makeTboxData4Edit($photo['lat'])."' />
<input type='hidden' name='lng' id='lng' size='20' value='".$myts->makeTboxData4Edit($photo['lng'])."' />
<input type='hidden' name='z' id='z' size='20' value='".$myts->makeTboxData4Edit($photo['zoom'])."' />
<input type='hidden' name='mt' id='mt' size='30' value='".$myts->makeTboxData4Edit($photo['mtype'])."' />
</div>" ) ;
if($ielog_icon_by_lid){
	$icon_select = new XoopsFormSelect(_MD_GNAV_MAP_ICON, 'icd', $photo['icd'], 1, false);
	$sql = "SELECT icd, title FROM $table_icon ";
	$result = $xoopsDB->query($sql);
	$icons_array = array();
	$icons_array[0] = '---';
	while ($myrow = $xoopsDB->fetchArray($result)) {
		$icons_array[$myrow['icd']] = $myrow['title'];
	}
	$icon_select->addOptionArray($icons_array);
}
}


//----------form-Start--------------------------
$form->addElement( $title_text ) ;
$form->setRequired( $title_text ) ;
$form->addElement( $cat_select ) ;
if($mode == G_INSERT)$form->setRequired( $cat_select ) ;
$form->addElement( $cat_select1 ) ;
$form->addElement( $cat_select2 ) ;
$form->addElement( $cat_select3 ) ;
$form->addElement( $cat_select4 ) ;
$form->addElement( $desc_tarea ) ;
$form->addElement( $hidden_body_html ) ;

$form->insertBreak(_MD_GNAV_SMT_TITLE_FILE);

if($photo['ext']){
	$form->addElement( $photoview ) ;
	if($ielog_allownoimage){
		$form->addElement( $del_box ) ;
	}
}else{
	$form->addElement( $del_hidden ) ;
}
$form->addElement( $file_form ) ;
$form->addElement( $caption_text ) ;
if($photo['ext1']){
	$form->addElement( $photoview1 ) ;
	$form->addElement( $del_box1 ) ;
}else{
	$form->addElement( $del_hidden1 ) ;
}
$form->addElement( $file_form1 ) ;
$form->addElement( $caption1_text ) ;
if($photo['ext2']){
	$form->addElement( $photoview2 ) ;
	$form->addElement( $del_box2 ) ;
}else{
	$form->addElement( $del_hidden2 ) ;
}
$form->addElement( $file_form2 ) ;
$form->addElement( $caption2_text ) ;

if($photo['ext3']){
	$form->addElement( $photoview3 ) ;
	$form->addElement( $del_box3 ) ;
}else{
	$form->addElement( $del_hidden3 ) ;
}
$form->addElement( $file_form3 ) ;
$form->addElement( $caption3_text ) ;

if($photo['ext4']){
	$form->addElement( $photoview4 ) ;
	$form->addElement( $del_box4 ) ;
}else{
	$form->addElement( $del_hidden4 ) ;
}
$form->addElement( $file_form4 ) ;
$form->addElement( $caption4_text ) ;

if($photo['ext5']){
	$form->addElement( $photoview5 ) ;
	$form->addElement( $del_box5 ) ;
}else{
	$form->addElement( $del_hidden5 ) ;
}
$form->addElement( $file_form5 ) ;
$form->addElement( $caption5_text ) ;

if($photo['ext6']){
	$form->addElement( $photoview6 ) ;
	$form->addElement( $del_box6 ) ;
}else{
	$form->addElement( $del_hidden6 ) ;
}
$form->addElement( $file_form6 ) ;
$form->addElement( $caption6_text ) ;

if($photo['ext7']){
	$form->addElement( $photoview7 ) ;
	$form->addElement( $del_box7 ) ;
}else{
	$form->addElement( $del_hidden7 ) ;
}
$form->addElement( $file_form7 ) ;
$form->addElement( $caption7_text ) ;

if($photo['ext8']){
	$form->addElement( $photoview8 ) ;
	$form->addElement( $del_box8 ) ;
}else{
	$form->addElement( $del_hidden8 ) ;
}
$form->addElement( $file_form8 ) ;
$form->addElement( $caption8_text ) ;
$form->addElement( $pixels_label ) ;

$form->insertBreak(_MD_GNAV_SMT_TITLE_INFO);

$form->addElement( $url_text ) ;
$form->addElement( $tel_text ) ;
$form->addElement( $fax_text ) ;
$form->addElement( $zip_text ) ;
$form->addElement( $other1_text ) ;
$form->addElement( $other2_text ) ;
$form->addElement( $other3_text ) ;
$form->addElement( $other4_text ) ;
$form->addElement( $other5_text ) ;
$form->addElement( $other6_text ) ;
$form->addElement( $other7_text ) ;
$form->addElement( $other8_text ) ;
$form->addElement( $other9_text ) ;
$form->addElement( $other10_text ) ;
$form->addElement( $other11_text ) ;
$form->addElement( $other12_text ) ;
$form->addElement( $other13_text ) ;
$form->addElement( $other14_text ) ;
$form->addElement( $other15_text ) ;
$form->addElement( $other16_text ) ;
$form->addElement( $other17_text ) ;
$form->addElement( $other18_text ) ;
$form->addElement( $other19_text ) ;
$form->addElement( $other20_text ) ;
$form->addElement( $address_tray ) ;
if($ielog_use_rss){
	$form->addElement( $rss_text ) ;
}

if($ielog_usegooglemap){
	$form->addElement( $gmap ) ;
	if($ielog_icon_by_lid)$form -> addElement( $icon_select );
}

if($ielog_addinfo){
	$form->addElement( $add_info_text ) ;
	$form->addElement( $add_info_desc ) ;
}

$form->insertBreak(_MD_GNAV_SMT_TITLE_UPDT);


$form->addElement( $poster_name_text ) ;
$form->addElement( $preview_hidden ) ;
$form->addElement( $preview1_hidden ) ;
$form->addElement( $preview2_hidden ) ;
$form->addElement( $preview3_hidden ) ;
$form->addElement( $preview4_hidden ) ;
$form->addElement( $preview5_hidden ) ;
$form->addElement( $preview6_hidden ) ;
$form->addElement( $preview7_hidden ) ;
$form->addElement( $preview8_hidden ) ;
$form->addElement( $counter_hidden ) ;
$form->addElement( $op_hidden ) ;
$form->addElement( $lid_hidden ) ;

if($mode == G_UPDATE && $isadmin ) {
	$form->addElement( $valid_box ) ;
	$form->addElement( $storets_box ) ;
	$form->addElement( $status_hidden ) ;
}
$form->addElement( $submit_tray ) ;
$form->addElement( $cation_label ) ;

if($mode == G_UPDATE && ($global_perms & GNAV_GPERM_DELETABLE)) {
	$form->insertBreak(_MD_GNAV_SMT_TITLE_DELT);
	$form->addElement( $del_tray ) ;
}

$xoopsTpl->assign('xoops_module_header',$xoops_module_header);
//----------form-end--------------------------

// Ticket
$GLOBALS['xoopsGTicket']->addTicketXoopsFormElement( $form , __LINE__ ) ;

$form->display() ;
CloseTable() ;
ielog_footer() ;

include( XOOPS_ROOT_PATH . "/footer.php" ) ;


?>