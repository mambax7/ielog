<?php

// for older files
function ielog_header()
{
	global $mod_url , $mydirname ;

	$tpl = new XoopsTpl() ;
	$tpl->assign( array( 'mod_url' => $mod_url ) ) ;
	$tpl->display( "db:{$mydirname}_header.html" ) ;
}


// for older files
function ielog_footer()
{
	global $mod_copyright , $mydirname ;

	$tpl = new XoopsTpl() ;
	$tpl->assign( array( 'mod_copyright' => $mod_copyright ) ) ;
	$tpl->display( "db:{$mydirname}_footer.html" ) ;
}


// returns appropriate name from uid
function ielog_get_name_from_uid( $uid )
{
	global $ielog_nameoruname ;

	if( $uid > 0 ) {
		$member_handler =& xoops_gethandler( 'member' ) ;
		$poster =& $member_handler->getUser( $uid ) ;

		if( is_object( $poster ) ) {
			if( $ielog_nameoruname == 'uname' || trim( $poster->name() ) == '' ) {
				$name = htmlspecialchars( $poster->uname() , ENT_QUOTES ) ;
			} else {
				$name = htmlspecialchars( $poster->name() , ENT_QUOTES ) ;
			}
		} else {
			$name = _GNAV_CAPTION_GUESTNAME ;
		}

	} else {
		$name = _GNAV_CAPTION_GUESTNAME ;
	}

	return $name ;
}

// returns appropriate name from uid
function ielog_check_name_from_uid( $uid , $poster_name )
{
	global $ielog_nameoruname ;

	if( $uid > 0 ) {
		$member_handler =& xoops_gethandler( 'member' ) ;
		$poster =& $member_handler->getUser( $uid ) ;

		if( $poster_name == htmlspecialchars( $poster->uname() , ENT_QUOTES )||
			$poster_name == htmlspecialchars( $poster->name() , ENT_QUOTES )){
			return true;
		} else {
			return false;
		}
	} else {
		return false;
	}
}



// Get photo's array to assign into template (heavy version)
function ielog_photo_assign($photo)
{

	global $ielog_middlepixel,$ielog_liquidimg,$ielog_normal_exts;
	// Middle size calculation
	$photo['width_height'] = '' ;
	$photo['width_height1'] = '' ;
	$photo['width_height2'] = '' ;
	$photo['width_height3'] = '' ;
	$photo['width_height4'] = '' ;
	$photo['width_height5'] = '' ;
	$photo['width_height6'] = '' ;
	$photo['width_height7'] = '' ;
	$photo['width_height8'] = '' ;
	
	$photo['flawidth'] =  $photo['res_x'] ;
	$photo['flaheight'] =  $photo['res_y'] ;
	$photo['flawidth1'] =  $photo['res_x1'] ;
	$photo['flaheight1'] =  $photo['res_y1'] ;
	$photo['flawidth2'] =  $photo['res_x2'] ;
	$photo['flaheight2'] =  $photo['res_y2'] ;
	$photo['flawidth3'] =  $photo['res_x3'] ;
	$photo['flaheight3'] =  $photo['res_y3'] ;
	$photo['flawidth4'] =  $photo['res_x4'] ;
	$photo['flaheight4'] =  $photo['res_y4'] ;
	$photo['flawidth5'] =  $photo['res_x5'] ;
	$photo['flaheight5'] =  $photo['res_y5'] ;
	$photo['flawidth6'] =  $photo['res_x6'] ;
	$photo['flaheight6'] =  $photo['res_y6'] ;
	$photo['flawidth7'] =  $photo['res_x7'] ;
	$photo['flaheight7'] =  $photo['res_y7'] ;
	$photo['flawidth8'] =  $photo['res_x8'] ;
	$photo['flaheight8'] =  $photo['res_y8'] ;

	list( $max_w , $max_h ) = explode( 'x' , $ielog_middlepixel ) ;

	//summary contents width  ( 4(margin)+ 1(border)+2(padding) ) * 2 =14 
	$cwidth=14;
	$maxw=$max_w;
	$cpic=0;
	if($ielog_liquidimg){
		//allimage
		if($photo['ext'])$cpic+=1;
		if($photo['ext1'])$cpic+=1;
		if($photo['ext2'])$cpic+=1;
		if($photo['ext3'])$cpic+=0;
		if($photo['ext4'])$cpic+=0;
		if($photo['ext5'])$cpic+=0;	
		if($photo['ext6'])$cpic+=0;
		if($photo['ext7'])$cpic+=0;
		if($photo['ext8'])$cpic+=0;
		if($cpic>1){
			$max_w=intval(($max_w+$cwidth-$cwidth*$cpic)/$cpic);
		}
	}

	//set caption width min
	$min_captionw= intval(($maxw+$cwidth-$cwidth*3)/3) ;


	$photo['img']  = 0;
	$photo['img1'] = 0;
	$photo['img2'] = 0;


	if( ! empty( $max_w ) && ! empty( $photo['res_x'] ) ) {
		if( empty( $max_h ) ) $max_h = $max_w ;
		if( $max_h / $max_w > $photo['res_y'] / $photo['res_x'] ) {
			if( $photo['res_x'] > $max_w ) {
				$photo['width_height'] = "width='$max_w'" ;
				$photo['flawidth'] = $max_w ;
				$photo['flaheight'] = round($photo['res_y'] / $photo['res_x'] * $max_w) ;
				$photo['img'] = 1;
			}else{
				$photo['img'] = 2;
			}
		} else {
			if( $photo['res_y'] > $max_h ){
				$photo['width_height'] = "height='$max_h'" ;
				$photo['flaheight'] = $max_h ;
				$photo['flawidth'] = round($photo['res_x'] / $photo['res_y'] * $max_h) ;
				$photo['img'] = 1;
			}else{
				$photo['img'] = 2;
			}
		}
	}

	if( ! empty( $max_w ) && ! empty( $photo['res_x1'] ) ) {
		if( empty( $max_h ) ) $max_h = $max_w ;
		if( $max_h / $max_w > $photo['res_y1'] / $photo['res_x1'] ) {
			if( $photo['res_x1'] > $max_w ){
				$photo['width_height1'] = "width='$max_w'" ;
				$photo['flawidth1'] = $max_w ;
				$photo['flaheight1'] = round($photo['res_y1'] / $photo['res_x1'] * $max_w) ;
				$photo['img1'] = 1;
			}else{
				$photo['img1'] = 2;
			}
		} else {
			if( $photo['res_y1'] > $max_h ){
				$photo['width_height1'] = "height='$max_h'" ;
				$photo['flaheight1'] = $max_h ;
				$photo['flawidth1'] = round($photo['res_x1'] / $photo['res_y1'] * $max_h) ;
				$photo['img1'] = 1;
			}else{
				$photo['img1'] = 2;
			}
		}
	}

	if( ! empty( $max_w ) && ! empty( $photo['res_x2'] ) ) {
		if( empty( $max_h ) ) $max_h = $max_w ;
		if( $max_h / $max_w > $photo['res_y2'] / $photo['res_x2'] ) {
			if( $photo['res_x2'] > $max_w ) {
				$photo['width_height2'] = "width='$max_w'" ;
				$photo['flawidth2'] = $max_w ;
				$photo['flaheight2'] = round($photo['res_y2'] / $photo['res_x2'] * $max_w) ;
				$photo['img2'] = 1;
			}else{
				$photo['img2'] = 2;
			}
		} else {
			if( $photo['res_y2'] > $max_h ){
				$photo['width_height2'] = "height='$max_h'" ;
				$photo['flaheight2'] = $max_h ;
				$photo['flawidth2'] = round($photo['res_x2'] / $photo['res_y2'] * $max_h) ;
				$photo['img2'] = 1;
			}else{
				$photo['img2'] = 2;
			}
		}
	}

	if( ! empty( $max_w ) && ! empty( $photo['res_x3'] ) ) {
		if( empty( $max_h ) ) $max_h = $max_w ;
		if( $max_h / $max_w > $photo['res_y3'] / $photo['res_x3'] ) {
			if( $photo['res_x3'] > $max_w ) {
				$photo['width_height3'] = "width='$max_w'" ;
				$photo['flawidth3'] = $max_w ;
				$photo['flaheight3'] = round($photo['res_y3'] / $photo['res_x3'] * $max_w) ;
				$photo['img3'] = 1;
			}else{
				$photo['img3'] = 2;
			}
		} else {
			if( $photo['res_y3'] > $max_h ){
				$photo['width_height3'] = "height='$max_h'" ;
				$photo['flaheight3'] = $max_h ;
				$photo['flawidth3'] = round($photo['res_x3'] / $photo['res_y3'] * $max_h) ;
				$photo['img3'] = 1;
			}else{
				$photo['img3'] = 2;
			}
		}
	}

	if( ! empty( $max_w ) && ! empty( $photo['res_x4'] ) ) {
		if( empty( $max_h ) ) $max_h = $max_w ;
		if( $max_h / $max_w > $photo['res_y4'] / $photo['res_x4'] ) {
			if( $photo['res_x4'] > $max_w ) {
				$photo['width_height4'] = "width='$max_w'" ;
				$photo['flawidth4'] = $max_w ;
				$photo['flaheight4'] = round($photo['res_y4'] / $photo['res_x4'] * $max_w) ;
				$photo['img4'] = 1;
			}else{
				$photo['img4'] = 2;
			}
		} else {
			if( $photo['res_y4'] > $max_h ){
				$photo['width_height4'] = "height='$max_h'" ;
				$photo['flaheight4'] = $max_h ;
				$photo['flawidth4'] = round($photo['res_x4'] / $photo['res_y4'] * $max_h) ;
				$photo['img4'] = 1;
			}else{
				$photo['img4'] = 2;
			}
		}
	}


	if( ! empty( $max_w ) && ! empty( $photo['res_x5'] ) ) {
		if( empty( $max_h ) ) $max_h = $max_w ;
		if( $max_h / $max_w > $photo['res_y5'] / $photo['res_x5'] ) {
			if( $photo['res_x5'] > $max_w ) {
				$photo['width_height5'] = "width='$max_w'" ;
				$photo['flawidth5'] = $max_w ;
				$photo['flaheight5'] = round($photo['res_y5'] / $photo['res_x5'] * $max_w) ;
				$photo['img5'] = 1;
			}else{
				$photo['img5'] = 2;
			}
		} else {
			if( $photo['res_y5'] > $max_h ){
				$photo['width_height5'] = "height='$max_h'" ;
				$photo['flaheight5'] = $max_h ;
				$photo['flawidth5'] = round($photo['res_x5'] / $photo['res_y5'] * $max_h) ;
				$photo['img5'] = 1;
			}else{
				$photo['img5'] = 2;
			}
		}
	}


	if( ! empty( $max_w ) && ! empty( $photo['res_x6'] ) ) {
		if( empty( $max_h ) ) $max_h = $max_w ;
		if( $max_h / $max_w > $photo['res_y6'] / $photo['res_x6'] ) {
			if( $photo['res_x6'] > $max_w ) {
				$photo['width_height6'] = "width='$max_w'" ;
				$photo['flawidth6'] = $max_w ;
				$photo['flaheight6'] = round($photo['res_y6'] / $photo['res_x6'] * $max_w) ;
				$photo['img6'] = 1;
			}else{
				$photo['img6'] = 2;
			}
		} else {
			if( $photo['res_y6'] > $max_h ){
				$photo['width_height6'] = "height='$max_h'" ;
				$photo['flaheight6'] = $max_h ;
				$photo['flawidth6'] = round($photo['res_x6'] / $photo['res_y6'] * $max_h) ;
				$photo['img6'] = 1;
			}else{
				$photo['img6'] = 2;
			}
		}
	}

	if( ! empty( $max_w ) && ! empty( $photo['res_x7'] ) ) {
		if( empty( $max_h ) ) $max_h = $max_w ;
		if( $max_h / $max_w > $photo['res_y7'] / $photo['res_x7'] ) {
			if( $photo['res_x7'] > $max_w ) {
				$photo['width_height7'] = "width='$max_w'" ;
				$photo['flawidth7'] = $max_w ;
				$photo['flaheight7'] = round($photo['res_y7'] / $photo['res_x7'] * $max_w) ;
				$photo['img7'] = 1;
			}else{
				$photo['img7'] = 2;
			}
		} else {
			if( $photo['res_y7'] > $max_h ){
				$photo['width_height7'] = "height='$max_h'" ;
				$photo['flaheight7'] = $max_h ;
				$photo['flawidth7'] = round($photo['res_x7'] / $photo['res_y7'] * $max_h) ;
				$photo['img7'] = 1;
			}else{
				$photo['img7'] = 2;
			}
		}
	}

	if( ! empty( $max_w ) && ! empty( $photo['res_x8'] ) ) {
		if( empty( $max_h ) ) $max_h = $max_w ;
		if( $max_h / $max_w > $photo['res_y8'] / $photo['res_x8'] ) {
			if( $photo['res_x8'] > $max_w ) {
				$photo['width_height8'] = "width='$max_w'" ;
				$photo['flawidth8'] = $max_w ;
				$photo['flaheight8'] = round($photo['res_y8'] / $photo['res_x8'] * $max_w) ;
				$photo['img8'] = 1;
			}else{
				$photo['img8'] = 2;
			}
		} else {
			if( $photo['res_y8'] > $max_h ){
				$photo['width_height8'] = "height='$max_h'" ;
				$photo['flaheight8'] = $max_h ;
				$photo['flawidth8'] = round($photo['res_x8'] / $photo['res_y8'] * $max_h) ;
				$photo['img8'] = 1;
			}else{
				$photo['img8'] = 2;
			}
		}
	}

	$photo['captionstyle']= "style='width:".( $min_captionw > $photo['flawidth'] ? $min_captionw : $photo['flawidth'] )."px;'" ;
	$photo['captionstyle1']= "style='width:".( $min_captionw > $photo['flawidth1'] ? $min_captionw : $photo['flawidth1'] )."px;'" ;
	$photo['captionstyle2']= "style='width:".( $min_captionw > $photo['flawidth2'] ? $min_captionw : $photo['flawidth2'] )."px;'" ;
	$photo['captionstyle3']= "style='width:".( $min_captionw > $photo['flawidth3'] ? $min_captionw : $photo['flawidth3'] )."px;'" ;
	$photo['captionstyle4']= "style='width:".( $min_captionw > $photo['flawidth4'] ? $min_captionw : $photo['flawidth4'] )."px;'" ;
	$photo['captionstyle5']= "style='width:".( $min_captionw > $photo['flawidth5'] ? $min_captionw : $photo['flawidth5'] )."px;'" ;
	$photo['captionstyle6']= "style='width:".( $min_captionw > $photo['flawidth6'] ? $min_captionw : $photo['flawidth6'] )."px;'" ;
	$photo['captionstyle7']= "style='width:".( $min_captionw > $photo['flawidth7'] ? $min_captionw : $photo['flawidth7'] )."px;'" ;
	$photo['captionstyle8']= "style='width:".( $min_captionw > $photo['flawidth8'] ? $min_captionw : $photo['flawidth8'] )."px;'" ;



	return $photo;

}

function ielog_get_array_for_photo_assign( $fetched_result_array , $summary = false )
{
	global $my_uid , $isadmin , $global_perms ;
	global $photos_url , $thumbs_url , $thumbs_dir , $mod_url , $mod_path ;
	global $ielog_makethumb , $ielog_thumbsize , $ielog_popular , $ielog_newdays , $ielog_normal_exts ,$ielog_gmap_exts;

	include_once dirname(dirname(__FILE__)).'/class/gnavi.textsanitizer.php' ;

	$myts =& IElogTextSanitizer::getInstance() ;

	extract( $fetched_result_array ) ;

	list($imgsrc_photo ,$ahref_photo ,$imgsrc_thumb ,$ahref_thumb,$is_normal_image ) = ielog_get_img_urls("$lid.$ext");
	list($imgsrc_photo1,$ahref_photo1,$imgsrc_thumb1,$ahref_thumb1,$is_normal_image1) = ielog_get_img_urls($lid."_1.".$ext1);
	list($imgsrc_photo2,$ahref_photo2,$imgsrc_thumb2,$ahref_thumb2,$is_normal_image2) = ielog_get_img_urls($lid."_2.".$ext2);
	list($imgsrc_photo3,$ahref_photo3,$imgsrc_thumb3,$ahref_thumb3,$is_normal_image3) = ielog_get_img_urls($lid."_3.".$ext3);
	list($imgsrc_photo4,$ahref_photo4,$imgsrc_thumb4,$ahref_thumb4,$is_normal_image4) = ielog_get_img_urls($lid."_4.".$ext4);
	list($imgsrc_photo5,$ahref_photo5,$imgsrc_thumb5,$ahref_thumb5,$is_normal_image5) = ielog_get_img_urls($lid."_5.".$ext5);
	list($imgsrc_photo6,$ahref_photo6,$imgsrc_thumb6,$ahref_thumb6,$is_normal_image6) = ielog_get_img_urls($lid."_6.".$ext6);
	list($imgsrc_photo7,$ahref_photo7,$imgsrc_thumb7,$ahref_thumb7,$is_normal_image7) = ielog_get_img_urls($lid."_7.".$ext7);
	list($imgsrc_photo8,$ahref_photo8,$imgsrc_thumb8,$ahref_thumb8,$is_normal_image8) = ielog_get_img_urls($lid."_8.".$ext8);

	$arrow_html = $arrowhtml ? 1 : 0 ;
	$arrow_br =  $arrowhtml ? 0 : 1 ;

	$addinfo_array = ielog_addinfo_array($addinfo,$myts);

	// Voting stats
	if( $rating > 0 ) {
		if( $votes == 1 ) {
			$votestring = _MD_GNAV_RAT_ONEVOTE ;
		} else {
			$votestring = sprintf( _MD_GNAV_RAT_NUMVOTES , $votes ) ;
		}
		$info_votes = number_format( $rating , 2 )." ($votestring)";
	} else {
		$info_votes = '0.00 ('.sprintf( _MD_GNAV_RAT_NUMVOTES , 0 ) . ')' ;
	}

	// Submitter's name

	if ($submitter>0){
		$submitter_name = ielog_get_name_from_uid( $submitter );
	}else{
		$submitter_name = $poster_name;
	}

	// Category's title
	$cat_title = empty( $cat_title ) ? '' : $cat_title ;

	// Summarize description
	if( $summary ) $description = $myts->extractSummary( $description ) ;
	
	//kml lists
	$mykmls='';
	if(in_array($ext,$ielog_gmap_exts)){
		if($mykmls)$mykmls.=',';
		$mykmls.="'".$photos_url."/".$lid.".".$ext."'";
	}
	if(in_array($ext1,$ielog_gmap_exts)){
		if($mykmls)$mykmls.=',';
		$mykmls.="'".$photos_url."/".$lid."_1.".$ext1."'";
	}
	if(in_array($ext2,$ielog_gmap_exts)){
		if($mykmls)$mykmls.=',';
		$mykmls.="'".$photos_url."/".$lid."_2.".$ext2."'";
	}
	if(in_array($ext3,$ielog_gmap_exts)){
		if($mykmls)$mykmls.=',';
		$mykmls.="'".$photos_url."/".$lid."_3.".$ext3."'";
	}
	if(in_array($ext4,$ielog_gmap_exts)){
		if($mykmls)$mykmls.=',';
		$mykmls.="'".$photos_url."/".$lid."_4.".$ext4."'";
	}
	if(in_array($ext5,$ielog_gmap_exts)){
		if($mykmls)$mykmls.=',';
		$mykmls.="'".$photos_url."/".$lid."_5.".$ext5."'";
	}
	if(in_array($ext6,$ielog_gmap_exts)){
		if($mykmls)$mykmls.=',';
		$mykmls.="'".$photos_url."/".$lid."_6.".$ext6."'";
	}	
	if(in_array($ext7,$ielog_gmap_exts)){
		if($mykmls)$mykmls.=',';
		$mykmls.="'".$photos_url."/".$lid."_7.".$ext7."'";
	}
	if(in_array($ext8,$ielog_gmap_exts)){
		if($mykmls)$mykmls.=',';
		$mykmls.="'".$photos_url."/".$lid."_8.".$ext8."'";
	}




	return array(
		'lid' => $lid ,
		'mycat' => ielog_get_mycat($cid,$cid1,$cid2,$cid3,$cid4) ,
		'cid' => $cid ,
		'cid1' => $cid1 ,
		'cid2' => $cid2 ,
		'cid3' => $cid3 ,
		'cid4' => $cid4 ,
		'icd' => $icd ,
		'ext' => $ext ,
		'ext1' => $ext1 ,
		'ext2' => $ext2 ,
		'ext3' => $ext3 ,
		'ext4' => $ext4 ,
		'ext5' => $ext5 ,
		'ext6' => $ext6 ,
		'ext7' => $ext7 ,
		'ext8' => $ext8 ,
		'mykmls' => $mykmls ,
		'res_x' => $res_x ,
		'res_y' => $res_y ,
		'window_x' => $res_x + 16 ,
		'window_y' => $res_y + 16 ,
		'res_x1' => $res_x1 ,
		'res_y1' => $res_y1 ,
		'window_x1' => $res_x1 + 16 ,
		'window_y1' => $res_y1 + 16 ,
		'res_x2' => $res_x2 ,
		'res_y2' => $res_y2 ,
		'window_x2' => $res_x2 + 16 ,
		'window_y2' => $res_y2 + 16 ,
		'res_x3' => $res_x3 ,
		'res_y3' => $res_y3 ,
		'window_x3' => $res_x3 + 16 ,
		'window_y3' => $res_y3 + 16 ,
		'res_x4' => $res_x4 ,
		'res_y4' => $res_y4 ,
		'window_x4' => $res_x4 + 16 ,
		'window_y4' => $res_y4 + 16 ,
		'res_x5' => $res_x5 ,
		'res_y5' => $res_y5 ,
		'window_x5' => $res_x5 + 16 ,
		'window_y5' => $res_y5 + 16 ,
		'res_x6' => $res_x6 ,
		'res_y6' => $res_y6 ,
		'window_x6' => $res_x6 + 16 ,
		'window_y6' => $res_y6 + 16 ,
		'res_x7' => $res_x7 ,
		'res_y7' => $res_y7 ,
		'window_x7' => $res_x7 + 16 ,
		'window_y7' => $res_y7 + 16 ,
		'res_x8' => $res_x8 ,
		'res_y8' => $res_y8 ,
		'window_x8' => $res_x8 + 16 ,
		'window_y8' => $res_y8 + 16 ,
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
		'datetime' => formatTimestamp( $date , 'm' ) ,
		'description' => $myts->displayTarea( $description , $arrow_html , 1 , 1 , 1 , $arrow_br , 1 ) ,
		'sdescription' => xoops_substr(strip_tags($myts->displayTarea( $description , $arrow_html , 1 , 1 , 1 , 1 , 1 )),0,512) ,
		'addinfo'=> $addinfo_array ,
		'ahref_thumb' => $ahref_thumb ,
		'ahref_thumb1' => $ahref_thumb1 ,
		'ahref_thumb2' => $ahref_thumb2 ,
		'ahref_thumb3' => $ahref_thumb3 ,
		'ahref_thumb4' => $ahref_thumb4 ,
		'ahref_thumb5' => $ahref_thumb5 ,
		'ahref_thumb6' => $ahref_thumb6 ,
		'ahref_thumb7' => $ahref_thumb7 ,
		'ahref_thumb8' => $ahref_thumb8 ,
		'imgsrc_thumb' => $imgsrc_thumb ,
		'imgsrc_thumb1' => $imgsrc_thumb1 ,
		'imgsrc_thumb2' => $imgsrc_thumb2 ,
		'imgsrc_thumb3' => $imgsrc_thumb3 ,
		'imgsrc_thumb4' => $imgsrc_thumb4 ,
		'imgsrc_thumb5' => $imgsrc_thumb5 ,
		'imgsrc_thumb6' => $imgsrc_thumb6 ,
		'imgsrc_thumb7' => $imgsrc_thumb7 ,
		'imgsrc_thumb8' => $imgsrc_thumb8 ,
		'imgsrc_photo' => $imgsrc_photo ,
		'imgsrc_photo1' => $imgsrc_photo1 ,
		'imgsrc_photo2' => $imgsrc_photo2 ,
		'imgsrc_photo3' => $imgsrc_photo3 ,
		'imgsrc_photo4' => $imgsrc_photo4 ,
		'imgsrc_photo5' => $imgsrc_photo5 ,
		'imgsrc_photo6' => $imgsrc_photo6 ,
		'imgsrc_photo7' => $imgsrc_photo7 ,
		'imgsrc_photo8' => $imgsrc_photo8 ,
		'ahref_photo' => $ahref_photo ,
		'ahref_photo1' => $ahref_photo1 ,
		'ahref_photo2' => $ahref_photo2 ,
		'ahref_photo3' => $ahref_photo3 ,
		'ahref_photo4' => $ahref_photo4 ,
		'ahref_photo5' => $ahref_photo5 ,
		'ahref_photo6' => $ahref_photo6 ,
		'ahref_photo7' => $ahref_photo7 ,
		'ahref_photo8' => $ahref_photo8 ,
		'can_edit' => ( ( $global_perms & GNAV_GPERM_EDITABLE ) && ( $my_uid == $submitter || $isadmin ) ) ,
		'submitter' => $submitter ,
		'submitter_name' => $myts->makeTboxData4Show($submitter_name) ,
		'poster_name' => $myts->makeTboxData4Show($poster_name) ,
		'hits' => $hits ,
		'status' => $status ,
		'rating' => $rating ,
		'rank' => floor( $rating - 0.001 ) ,
		'votes' => $votes ,
		'info_votes' => $info_votes ,
		'comments' => $comments ,
		'lat' => $lat ,
		'lng' => $lng ,
		'zoom' => $zoom ,
		'mtype' => $myts->makeTboxData4Show($mtype) ,
		'url' => $myts->makeTboxData4Show($url) ,
		'rss' => $myts->makeTboxData4Show($rss) ,
		'tel' => $myts->makeTboxData4Show($tel) ,
		'fax' => $myts->makeTboxData4Show($fax) ,
		'zip' => $myts->makeTboxData4Show($zip) ,
		'other1' => $myts->makeTboxData4Show($other1) ,
		'other2' => $myts->makeTboxData4Show($other2) ,
		'other3' => $myts->makeTboxData4Show($other3) ,
		'other4' => $myts->makeTboxData4Show($other4) ,
		'other5' => $myts->makeTboxData4Show($other5) ,
		'other6' => $myts->makeTboxData4Show($other6) ,
		'other7' => $myts->makeTboxData4Show($other7) ,
		'other8' => $myts->makeTboxData4Show($other8) ,
		'other9' => $myts->makeTboxData4Show($other9) ,
		'other10' => $myts->makeTboxData4Show($other10) ,
		'other11' => $myts->makeTboxData4Show($other11) ,
		'other12' => $myts->makeTboxData4Show($other12) ,
		'other13' => $myts->makeTboxData4Show($other13) ,
		'other14' => $myts->makeTboxData4Show($other14) ,
		'other15' => $myts->makeTboxData4Show($other15) ,
		'other16' => $myts->makeTboxData4Show($other16) ,
		'other17' => $myts->makeTboxData4Show($other17) ,
		'other18' => $myts->makeTboxData4Show($other18) ,
		'other19' => $myts->makeTboxData4Show($other19) ,
		'other20' => $myts->makeTboxData4Show($other20) ,
		'address' => $myts->makeTboxData4Show($address) ,
		'is_normal_image'=>$is_normal_image,
		'is_newphoto' => ( $date > time() - 86400 * $ielog_newdays && $status == 1 ) , 
		'is_updatedphoto' => ( $date > time() - 86400 * $ielog_newdays && $status == 2 ) , 
		'is_popularphoto' => ( $hits >= $ielog_popular ) 
	) ;
}




// get list of sub categories in header space
function ielog_get_sub_categories( $parent_id , $cattree ,$where="")
{
	global $xoopsDB , $table_cat ;

	$myts =& MyTextSanitizer::getInstance() ;

	$ret = array() ;

	$crs = $xoopsDB->query( "SELECT cid, title, imgurl,description FROM $table_cat WHERE pid=$parent_id ORDER BY weight,title") or die( "Error: Get Category." ) ;

	while( list( $cid , $title , $imgurl,$description ) = $xoopsDB->fetchRow( $crs ) ) {

		// Show first child of this category
		$subcat = array() ;
		$arr = $cattree->getFirstChild( $cid , "weight" ) ;
		foreach( $arr as $child ) {
			$subcat[] = array(
				'cid' => $child['cid'] ,
				'description' => $child['description'] ,
				'title' => $myts->makeTboxData4Show( $child['title'] ) ,
				'photo_small_sum' => ielog_get_photo_small_sum_from_cat( $child['cid'] , "status>0 ".$where ) ,
				'number_of_subcat' => sizeof( $cattree->getFirstChildId( $child['cid'] ) )
			) ;
		}

		// Category's banner default
		if( $imgurl == "http://" ) $imgurl = '' ;

		// Total sum of photos
		$cids = $cattree->getAllChildId( $cid ) ;
		array_push( $cids , $cid ) ;
		$photo_total_sum = ielog_get_photo_total_sum_from_cats( $cids , "status>0 ".$where ) ;

		$ret[] = array(
			'cid' => $cid ,
			'description' => $description,
			'imgurl' => $myts->makeTboxData4Edit( $imgurl ) ,
			'photo_small_sum' => ielog_get_photo_small_sum_from_cat( $cid , "status>0 ".$where ) ,
			'photo_total_sum' => $photo_total_sum ,
			'title' => $myts->makeTboxData4Show( $title ) ,
			'subcategories' => $subcat
		) ;
	}

	return $ret ;
}


// for older files
function ielog_get_mycat($cid,$cid1,$cid2,$cid3,$cid4){
	global $xoopsDB;
	global $table_cat;
	$ret='';
	$myts =& MyTextSanitizer::getInstance() ;
	
	$where='';
	if($cid){
		if($where!='')$where.=' OR ';
		$where .= "cid = ".$cid;
	}
	if($cid1){
		if($where!='')$where.=' OR ';
		$where .= "cid = ".$cid1;
	}
	if($cid2){
		if($where!='')$where.=' OR ';
		$where .= "cid = ".$cid2;
	}
	if($cid3){
		if($where!='')$where.=' OR ';
		$where .= "cid = ".$cid3;
	}
	if($cid4){
		if($where!='')$where.=' OR ';
		$where .= "cid = ".$cid4;
	}
	if($where=='')return '';

	$sql="SELECT cid,title FROM $table_cat WHERE $where ORDER BY pid,weight";
	$crs = $xoopsDB->query($sql) ;
	while(list($cid,$title) = $xoopsDB->fetchRow( $crs )){
		$ret.="&nbsp;<a href='index.php?cid=$cid' >".$myts->makeTboxData4Show( $title )."</a>&nbsp;/";
	} 
	if($ret!='')$ret =substr($ret, 0, -1);
	return $ret ;
}


// get attributes of <img> for preview image
function ielog_get_img_attribs_for_preview($photo, $preview_name,$preview_name1,$preview_name2,$preview_name3,$preview_name4,$preview_name5,$preview_name6,$preview_name7,$preview_name8)
{
	global $photos_url , $mod_url , $mod_path , $ielog_normal_exts , $ielog_thumbsize,$photos_dir ;

	$photo['res_x']=0;
	$photo['res_y']=0;
	$photo['res_x1']=0;
	$photo['res_y1']=0;
	$photo['res_x2']=0;
	$photo['res_y2']=0;
	$photo['res_x3']=0;
	$photo['res_y3']=0;
	$photo['res_x4']=0;
	$photo['res_y4']=0;
	$photo['res_x5']=0;
	$photo['res_y5']=0;
	$photo['res_x6']=0;
	$photo['res_y6']=0;
	$photo['res_x7']=0;
	$photo['res_y7']=0;
	$photo['res_x8']=0;
	$photo['res_y8']=0;
	$photo['window_x']=0;
	$photo['window_y']=0;
	$photo['window_x1']=0;
	$photo['window_y1']=0;
	$photo['window_x2']=0;
	$photo['window_y2']=0;
	$photo['window_x3']=0;
	$photo['window_y3']=0;
	$photo['window_x4']=0;
	$photo['window_y4']=0;
	$photo['window_x5']=0;
	$photo['window_y5']=0;
	$photo['window_x6']=0;
	$photo['window_y6']=0;
	$photo['window_x7']=0;
	$photo['window_y7']=0;
	$photo['window_x8']=0;
	$photo['window_y8']=0;


	$photo['ext'] = substr( strrchr( $preview_name , '.' ) , 1 ) ;
	if( in_array( strtolower( $photo['ext'] ) , $ielog_normal_exts ) ) {
		$dim = GetImageSize( "$photos_dir/$preview_name" ) ;
		if( $dim ) {$photo['res_x']=$dim[0];$photo['res_y']=$dim[1];}
		$photo['window_x']=$photo['res_x']+16;
		$photo['window_y']=$photo['res_y']+16;
	}
	$photo['ext1'] = substr( strrchr( $preview_name1 , '.' ) , 1 ) ;
	if( in_array( strtolower( $photo['ext1'] ) , $ielog_normal_exts ) ) {
		$dim = GetImageSize( "$photos_dir/$preview_name1" ) ;
		if( $dim ) {$photo['res_x1']=$dim[0];$photo['res_y1']=$dim[1];}
		$photo['window_x1']=$photo['res_x1']+16;
		$photo['window_y1']=$photo['res_y1']+16;
	}
	$photo['ext2'] = substr( strrchr( $preview_name2 , '.' ) , 1 ) ;
	if( in_array( strtolower( $photo['ext2'] ) , $ielog_normal_exts ) ) {
		$dim = GetImageSize( "$photos_dir/$preview_name2" ) ;
		if( $dim ) {$photo['res_x2']=$dim[0];$photo['res_y2']=$dim[1];}
		$photo['window_x2']=$photo['res_x2']+16;
		$photo['window_y2']=$photo['res_y2']+16;
	}
	$photo['ext3'] = substr( strrchr( $preview_name3 , '.' ) , 1 ) ;
	if( in_array( strtolower( $photo['ext3'] ) , $ielog_normal_exts ) ) {
		$dim = GetImageSize( "$photos_dir/$preview_name3" ) ;
		if( $dim ) {$photo['res_x3']=$dim[0];$photo['res_y3']=$dim[1];}
		$photo['window_x3']=$photo['res_x3']+16;
		$photo['window_y3']=$photo['res_y3']+16;
	}
	$photo['ext4'] = substr( strrchr( $preview_name4 , '.' ) , 1 ) ;
	if( in_array( strtolower( $photo['ext4'] ) , $ielog_normal_exts ) ) {
		$dim = GetImageSize( "$photos_dir/$preview_name4" ) ;
		if( $dim ) {$photo['res_x4']=$dim[0];$photo['res_y4']=$dim[1];}
		$photo['window_x4']=$photo['res_x4']+16;
		$photo['window_y4']=$photo['res_y4']+16;
	}
	$photo['ext5'] = substr( strrchr( $preview_name5 , '.' ) , 1 ) ;
	if( in_array( strtolower( $photo['ext5'] ) , $ielog_normal_exts ) ) {
		$dim = GetImageSize( "$photos_dir/$preview_name5" ) ;
		if( $dim ) {$photo['res_x5']=$dim[0];$photo['res_y5']=$dim[1];}
		$photo['window_x5']=$photo['res_x5']+16;
		$photo['window_y5']=$photo['res_y5']+16;
	}
	$photo['ext6'] = substr( strrchr( $preview_name6 , '.' ) , 1 ) ;
	if( in_array( strtolower( $photo['ext6'] ) , $ielog_normal_exts ) ) {
		$dim = GetImageSize( "$photos_dir/$preview_name6" ) ;
		if( $dim ) {$photo['res_x6']=$dim[0];$photo['res_y6']=$dim[1];}
		$photo['window_x6']=$photo['res_x6']+16;
		$photo['window_y6']=$photo['res_y6']+16;
	}
	$photo['ext7'] = substr( strrchr( $preview_name7 , '.' ) , 1 ) ;
	if( in_array( strtolower( $photo['ext7'] ) , $ielog_normal_exts ) ) {
		$dim = GetImageSize( "$photos_dir/$preview_name7" ) ;
		if( $dim ) {$photo['res_x7']=$dim[0];$photo['res_y7']=$dim[1];}
		$photo['window_x7']=$photo['res_x7']+16;
		$photo['window_y7']=$photo['res_y7']+16;
	}
	$photo['ext8'] = substr( strrchr( $preview_name8 , '.' ) , 1 ) ;
	if( in_array( strtolower( $photo['ext8'] ) , $ielog_normal_exts ) ) {
		$dim = GetImageSize( "$photos_dir/$preview_name8" ) ;
		if( $dim ) {$photo['res_x8']=$dim[0];$photo['res_y8']=$dim[1];}
		$photo['window_x8']=$photo['res_x8']+16;
		$photo['window_y8']=$photo['res_y8']+16;
	}

	list($photo['imgsrc_photo'],$photo['ahref_photo']) = ielog_get_img_urls($preview_name);
	list($photo['imgsrc_photo1'],$photo['ahref_photo1']) = ielog_get_img_urls($preview_name1);
	list($photo['imgsrc_photo2'],$photo['ahref_photo2']) = ielog_get_img_urls($preview_name2);
	list($photo['imgsrc_photo3'],$photo['ahref_photo3']) = ielog_get_img_urls($preview_name3);
	list($photo['imgsrc_photo4'],$photo['ahref_photo4']) = ielog_get_img_urls($preview_name4);
	list($photo['imgsrc_photo5'],$photo['ahref_photo5']) = ielog_get_img_urls($preview_name5);
	list($photo['imgsrc_photo6'],$photo['ahref_photo6']) = ielog_get_img_urls($preview_name6);
	list($photo['imgsrc_photo7'],$photo['ahref_photo7']) = ielog_get_img_urls($preview_name7);
	list($photo['imgsrc_photo8'],$photo['ahref_photo8']) = ielog_get_img_urls($preview_name8);
	
	return $photo;

}

function ielog_get_img_urls($file_name){

	global $ielog_normal_exts,$mod_url,$photos_url,$mod_path,$thumbs_url,$ielog_makethumb;
	$ext = substr( strrchr( $file_name , '.' ) , 1 ) ;
	if ($ext){
		if( in_array( strtolower( $ext ) , $ielog_normal_exts ) ) {
			$is_normal_image=1;
			$imgsrc_photo = "$photos_url/$file_name" ;
			if($ielog_makethumb){
				$imgsrc_thumb = "$thumbs_url/$file_name" ;
			}else{
				$imgsrc_thumb = $imgsrc_photo ;
			}
		} else {
			$is_normal_image=0;
			if(file_exists( "$mod_path/icons/$ext.gif" )){
				$imgsrc_photo = "$mod_url/icons/$ext.gif" ;
				$imgsrc_thumb = "$mod_url/icons/$ext.gif" ;
			}else{
				$imgsrc_photo = "$mod_url/icons/all.gif" ;
				$imgsrc_thumb = "$mod_url/icons/all.gif" ;
			}
		}
		$ahref_photo = "$photos_url/$file_name" ;
		$ahref_thumb = "$thumbs_url/$file_name" ;
	}else{
		$is_normal_image=0;
		$ahref_thumb ="";
		$imgsrc_thumb = "";
		$imgsrc_photo = "" ;
		$ahref_photo ="" ;
	}
	return array($imgsrc_photo,$ahref_photo,$imgsrc_thumb,$ahref_thumb,$is_normal_image);
}



?>