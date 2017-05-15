<?php
/* 
* +--------------------------------------------------------------------------+
* | Copyright (c) ShemOtechnik Profitquery Team shemotechnik@profitquery.com |
* +--------------------------------------------------------------------------+
* | This program is free software;you can redistribute it and/or modify     |
* | it under the terms of the GNU General Public License as published by     |
* | the Free Software Foundation;either version 2 of the License, or        |
* | (at your option) any later version.                                      |
* |                                                                          |
* | This program is distributed in the hope that it will be useful,          |
* | but WITHOUT ANY WARRANTY;without even the implied warranty of           |
* | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
* | GNU General Public License for more details.                             |
* |                                                                          |
* | You should have received a copy of the GNU General Public License        |
* | along with this program;if not, write to the Free Software              |
* | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA |
* +--------------------------------------------------------------------------+
*/
/**
* Plugin Name:Grow Email List Widgets
* Plugin URI:http://profitquery.com/?utm=es_wp
* Description:There are free tools helping each website get more email subscribers. Many pre-designed themes, design options, 3 tool type with several shows up event can help to grow your visitors' email lists.The plugin includes Center Email subscribe popup, floating corner email subscribe popup and top level or bottom level bar
* Version:1.0.3
*
* Author:Profitquery Team <support@profitquery.com>
* Author URI:http://profitquery.com/?utm=es_wp
*/

require_once 'pq_es_class.php';
$PQ_ES_Class = new PQ_ES_Class();
// HOOK
register_deactivation_hook(__FILE__ ,array('PQ_ES_Class', 'pluginDeactivation'));
register_activation_hook(__FILE__ ,array('PQ_ES_Class', 'pluginActivation'));


$profitquery = get_option('profitquery');

//not isset later click
if(!isset($profitquery['settings']['pq_es_later_click_time'])){
	$profitquery['settings']['pq_es_later_click_time'] = time()+60*60*1;
	update_option('profitquery', $profitquery);
}

//not isset pq_es_later_update_click_time
if(!isset($profitquery['settings']['pq_es_later_update_click_time'])){
	$profitquery['settings']['pq_es_later_update_click_time'] = time()+60*60*7;
	update_option('profitquery', $profitquery);
}


if (!defined('PQ_ES_PAGE_NAME'))
	define('PQ_ES_PAGE_NAME', 'pq_es');

if (!defined('PQ_ES_ADMIN_CSS_PATH'))
	define('PQ_ES_ADMIN_CSS_PATH', 'css/');

if (!defined('PQ_ES_ADMIN_JS_PATH'))
	define('PQ_ES_ADMIN_JS_PATH', 'js/');

if (!defined('PQ_ES_ADMIN_IMG_PATH'))
	define('PQ_ES_ADMIN_IMG_PATH', 'i/');

if (!defined('PQ_ES_ADMIN_IMG_PREVIEW_PATH'))
	define('PQ_ES_ADMIN_IMG_PREVIEW_PATH', 'preview/');



add_action('init', 'PQ_ES_init');


function PQ_ES_init(){
	global $profitquery;
	global $PQ_ES_Class;	
	if ( !is_admin()){		
		add_action('wp_head', 'PQ_ES_code_injection');		
	}else if(current_user_can('editor') || current_user_can('administrator')){		
		add_action('admin_head', 'PQ_ES_RateUs');
	}
}

function PQ_ES_RateUs(){
	global $profitquery;
	global $PQ_ES_Class;
	
	$PQ_ES_show_message = 0;		
	if(strstr($_SERVER[REQUEST_URI], 'wp-admin/plugins.php')){
		if((int)$profitquery[settings][pq_es_click_review] == 0 && (int)$profitquery[settings][pq_es_later_click_time] < time()) $PQ_ES_show_message = 1;
		if($PQ_ES_Class->getArrayAllFreeTools() && (int)$profitquery[settings][pq_es_later_update_click_time] < time()) $PQ_ES_show_message = 1;		
	}		
	if($PQ_ES_show_message){		
		$PQ_ES_Class->getDictionary();
	?>
	<div class="updated" id="PQPluginMessage" style="padding:0;margin:0;border:none;background:none;">	
	<style type="text/css">
	.updated {    padding: 0;     margin: 0;     border: none;     background: none; display:none}
	.wrap .updated {display:block}
	.pq_activate{padding:0;margin:15px 0;background-color:#feca16;position:relative;overflow:hidden;background-image:url(<?php echo  plugins_url( PQ_ES_ADMIN_IMG_PATH.'/banner.png', __FILE__ );?>);background-repeat:no-repeat;background-position:right center;background-size:contain}
	.pq_activate .aa_a{position:absolute;top:-5px;right:10px;font-size:140px;color:#769F33;font-family:Georgia, "Times New Roman", Times, serif;z-index:1}
	.pq_activate .aa_button{font-weight:normal;text-align:center;color:#FFF;background-color:#16bffe;position:absolute;right:0;top:0;bottom:0;line-height:2;margin:auto;height:28px;display:block;padding:0 25px;min-width:100px;font-family:arial;font-size:14px;cursor:pointer}
	.pq_activate .aa_button:hover{background-color:#00A6E4}
	.pq_activate .aa_button_border{}
	.pq_activate .aa_button_container{position:absolute;top:0;bottom:0;right:25px;width:auto}
	.pq_activate .aa_description{margin:0 0 20px 25px;color:rgb(63, 63, 63);font-size:13px;font-family:arial}
	.aa_header {font-size:20px;font-family:arial;margin:25px 0 5px 25px}

	</style>
				
		<form  id="PQ_ES_marketing_form" action="<?php echo admin_url("options-general.php?page=" . PQ_ES_PAGE_NAME);?>" method="POST"> 			
			<div class="pq_activate">  
				
				<div class="aa_button_container" onclick="document.getElementById('PQ_ES_marketing_form').submit();">  
					<div class="aa_button_border">          
						<div class="aa_button"><?php echo $PQ_ES_Class->_dictionary[new_message][button];?></div>  
					</div>  
				</div>
				<div class="aa_header"><?php echo $PQ_ES_Class->_dictionary[new_message][title];?></div>
				<div class="aa_description"><?php echo $PQ_ES_Class->_dictionary[new_message][desc];?></div>  
			</div>  
		</form>  
	</div>	
	<?php
	}
}

/* Adding action links on plugin list*/
function PQ_ES_admin_link($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    if ($file == $this_plugin) {
        $settings_link = '<a href="options-general.php?page='.PQ_ES_PAGE_NAME.'">Settings</a>';
        array_unshift($links, $settings_link);
    }

    return $links;
}

function PQ_ES_colorToHex($val){		
	$colorArray = array("indianred"=>"#cd5c5c","crimson"=>"#dc143c","lightpink"=>"#ffb6c1","pink"=>"#ffc0cb","palevioletred"=>"#D87093","hotpink"=>"#ff69b4","mediumvioletred"=>"#c71585","orchid"=>"#da70d6","plum"=>"#dda0dd","violet"=>"#ee82ee","magenta"=>"#ff00ff","purple"=>"#800080","mediumorchid"=>"#ba55d3","darkviolet"=>"#9400d3","darkorchid"=>"#9932cc","indigo"=>"#4b0082","blviolet"=>"#8a2be2","mediumpurple"=>"#9370db","darkslateblue"=>"#483d8b","mediumslateblue"=>"#7b68ee","slateblue"=>"#6a5acd","blue"=>"#0000ff","navy"=>"#000080","midnightblue"=>"#191970","royalblue"=>"#4169e1","cornflowerblue"=>"#6495ed","steelblue"=>"#4682b4","lightskyblue"=>"#87cefa","skyblue"=>"#87ceeb","deepskyblue"=>"#00bfff","lightblue"=>"#add8e6","powderblue"=>"#b0e0e6","darkturquoise"=>"#00ced1","cadetblue"=>"#5f9ea0","cyan"=>"#00ffff","teal"=>"#008080","mediumturquoise"=>"#48d1cc","lightseagreen"=>"#20b2aa","paleturquoise"=>"#afeeee","mediumspringgreen"=>"#00fa9a","springgreen"=>"#00ff7f","darkseagreen"=>"#8fbc8f","palegreen"=>"#98fb98","lmgreen"=>"#32cd32","forestgreen"=>"#228b22","darkgreen"=>"#006400","lawngreen"=>"#7cfc00","grnyellow"=>"#adff2f","darkolivegreen"=>"#556b2f","olvdrab"=>"#6b8e23","yellow"=>"#ffff00","olive"=>"#808000","darkkhaki"=>"#bdb76b","khaki"=>"#f0e68c","gold"=>"#ffd700","gldenrod"=>"#daa520","orange"=>"#ffa500","wheat"=>"#f5deb3","navajowhite"=>"#ffdead","burlywood"=>"#deb887","darkorange"=>"#ff8c00","sienna"=>"#a0522d","orngred"=>"#ff4500","tomato"=>"#ff6347","salmon"=>"#fa8072","brown"=>"#a52a2a","red"=>"#ff0000","black"=>"#000000","darkgrey"=>"#a9a9a9","dimgrey"=>"#696969","lightgrey"=>"#d3d3d3","slategrey"=>"#708090","lightslategrey"=>"#778899","silver"=>"#c0c0c0","whtsmoke"=>"#f5f5f5","white"=>"#ffffff");
	foreach((array)$colorArray as $k => $v){			
		if(strstr(trim($val), '_'.$k)){				
			return $v;
		}
	}
	return $val;
	
}

function PQ_ES_getNormalColorStructure($name, $val){
	$array = array(
		'background_button_block' => 'pq_btngbg_bgcolor_btngroup_PQCC',
		'background_text_block' => 'pq_bgtxt_bgcolor_bgtxt_PQCC',
		'background_form_block' => 'pq_formbg_bgcolor_formbg_PQCC',
		'background_soc_block' => 'pq_bgsocblock_bgcolor_bgsocblock_PQCC',
		'overlay' => 'pq_over_bgcolor_PQCC',
		'button_text_color' => 'pq_btn_color_btngroupbtn_PQCC',
		'button_color' => 'pq_btn_bg_bgcolor_btngroupbtn_PQCC',
		'head_color' => 'pq_h_color_h1_PQCC',
		'text_color' => 'pq_text_color_block_PQCC',
		'border_color' => 'pq_bd_bordercolor_PQCC',
		'bookmark_text_color' => 'pq_text_color_block_PQCC',
		'bookmark_background_color' => 'pq_bg_bgcolor_PQCC',
		'close_icon_color' => 'pq_x_color_pqclose_PQCC',
		'gallery_background_color' => 'pq_bg_bgcolor_PQCC',
		'gallery_button_text_color' => 'pq_btn_color_pqbtn_PQCC',
		'gallery_button_color' => 'pq_btn_bg_bgcolor_pqbtn_PQCC',		
		'gallery_head_color' => 'pq_h_color_h1_PQCC',
		'tblock_text_font_color' => 'pq_bgtxt_color_bgtxtp_PQCC',
		'background_mobile_block' => 'pq_mblock_bgcolor_bgmobblock_PQCC',
		'mblock_text_font_color' => 'pq_mblock_color_bgmobblockp_PQCC'
	);
	$ret = '';
	if(trim($val)){
		if(strstr(trim($val), '#')){
			$ret = $array[$name].str_replace('#','',trim($val));
		}else{
			$ret = $array[$name].str_replace('#','',trim(PQ_ES_colorToHex($val)));
		}
	}
	return $ret;
}

function PQ_ES_hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb);// returns the rgb values separated by commas
   return $rgb;// returns an array with the rgb values
}

function PQ_ES_RGBA_color($bg, $opacity, $prefix){
	$ret = '';	
	if($bg){		
		$rgb = PQ_ES_hex2rgb(str_replace('#','',trim(PQ_ES_colorToHex($bg))));		
		$ret = $prefix.(int)$rgb[0].'_'.(int)$rgb[1].'_'.(int)$rgb[2].'_'.(int)$opacity;		
	}
	return $ret;
}

function PQ_ES_getThankCode($data){
	$data = PQ_ES_checkIssetValue($data);
	$return = array();
	//enable
	if((string)$data['enable'] == 'on' || (int)$data['enable'] == 1) $return['enable'] = 1;else $return['enable'] = 0;
	//animation
	if($data['animation']) $return['animation'] = 'pq_animated '.$data['animation'];	
	
	//closeIconOption
	$return['closeIconOption']['animation'] = sanitize_title($data['close_icon']['animation']);
	$return['closeIconOption']['button_text'] = sanitize_title($data['close_icon']['button_text']);
	
	$return['contOptions'] = $return['animation'].' pq_open_and_fix';
	
	
	//designOptions	
	$return['designOptions'] = $data['typeWindow'].' '.$data['popup_form'].' '.PQ_ES_RGBA_color($data['background_color'], $data['background_opacity'], 'pq_bg_bgcolor_PQRGBA_').' '.$data['head_font'];
	$return['designOptions'] .= ' '.$data['head_size'].' '.PQ_ES_getNormalColorStructure('head_color', $data['head_color']).' '.$data['text_font'].' '.$data['font_size'].' '.PQ_ES_getNormalColorStructure('text_color', $data['text_color']);
	$return['designOptions'] .= ' '.PQ_ES_getNormalColorStructure('border_color', $data['border_color']).' '.$return['border_depth'].' '.$data['button_font'].' '.PQ_ES_getNormalColorStructure('button_text_color', $data['button_text_color']).' '.PQ_ES_getNormalColorStructure('button_color', $data['button_color']);
	$return['designOptions'] .= ' '.$data['close_icon']['form'].' '.PQ_ES_getNormalColorStructure('close_icon_color', $data['close_icon']['color']).' '.$data['button_font_size'];
	$return['designOptions'] .= ' '.PQ_ES_getNormalColorStructure('background_button_block', $data['background_button_block']).' '.PQ_ES_getNormalColorStructure('background_soc_block', $data['background_soc_block']);
		
	$return['designOptions'] .= ' '.$data['header_img_type'].' '.$data['close_text_font'];
	$return['designOptions'] .= ' '.$data['form_block_padding'].' '.$data['button_block_padding'];
	$return['designOptions'] .= ' '.$data['text_block_padding'].' '.$data['icon_block_padding'];
	$return['designOptions'] .= ' '.$data['button_form'].' '.$data['input_type'].' '.$data['button_type'];
	$return['designOptions'] .= ' '.$data['showup_animation'];
	$return['designOptions'] .= ' '.$data['ss_view_type'].' '.PQ_ES_RGBA_color($data['ss_color'], $data['ss_color_opacity'], 'pq_pro_color_socicons_PQRGBA_').' '.PQ_ES_RGBA_color($data['ss_background_color'], $data['ss_background_color_opacity'], 'pq_pro_bgcolor_socicons_PQRGBA_');
	
	//img
	$return['header_image_src'] = esc_url($data['header_image_src']);	
	$return['background_image_src'] = esc_url($data['background_image_src']);	
	
	
	//txt
	$return['title'] = wp_unslash($data['title']);
	$return['sub_title'] = wp_unslash($data['sub_title']);	
	
	if($data['socnet_block_type'] == 'follow' || $data['socnet_block_type'] == 'share'){		
		$return['designOptions'] .= ' '.$data['icon']['design'].' '.$data['icon']['form'].' '.$data['icon']['size'].' '.$data['icon']['space'];
		$return['socnetBlockType'] = $data['socnet_block_type'];
		$return['hoverAnimation'] = $data['icon']['animation'];		
		
		if($data['socnet_block_type'] == 'share'){
			foreach((array)$data['socnet_with_pos'] as $k => $v){
				if($v){
					$return['socnetShareBlock'][$v]['exactPageShare'] = 1;
				}
			}
		}
		//		
		if($data['socnet_block_type'] == 'follow'){
			foreach((array)$data['socnetIconsBlock'] as $k => $v){
				if($k && $v){
					if($k == 'FB' || $k == 'facebook') {
						$v = str_replace('https://facebook.com','',$v);
						$v = str_replace('http://facebook.com','',$v);
						$return['socnetFollowBlock']['facebook'] = esc_url('https://facebook.com/'.$v);
					}
					if($k == 'TW' || $k == 'twitter') {
						$v = str_replace('https://twitter.com','',$v);
						$v = str_replace('http://twitter.com','',$v);
						$return['socnetFollowBlock']['twitter'] = esc_url('https://twitter.com/'.$v);
					}
					if($k == 'GP' || $k == 'google-plus') {
						$v = str_replace('https://plus.google.com','',$v);
						$v = str_replace('http://plus.google.com','',$v);						
						$return['socnetFollowBlock']['google-plus'] = esc_url('https://plus.google.com/'.$v);
					}
					if($k == 'PI' || $k == 'pinterest') {
						$v = str_replace('https://pinterest.com','',$v);
						$v = str_replace('http://pinterest.com','',$v);
						$return['socnetFollowBlock']['pinterest'] = esc_url('https://pinterest.com/'.$v);
					}
					if($k == 'VK' || $k == 'vk') {
						$v = str_replace('https://vk.com/','',$v);
						$v = str_replace('http://vk.com/','',$v);
						$return['socnetFollowBlock']['vk'] = esc_url('http://vk.com/'.$v);
					}
					if($k == 'YT' || $k == 'youtube') {
						$v = str_replace('https://www.youtube.com/channel/','',$v);
						$v = str_replace('http://www.youtube.com/channel/','',$v);
						$return['socnetFollowBlock']['youtube'] = esc_url('https://www.youtube.com/channel/'.$v);
					}
					if($k == 'RSS') {
						$return['socnetFollowBlock']['RSS'] = esc_url($v);
					}
					if($k == 'IG' || $k == 'instagram') {
						$v = str_replace('https://instagram.com','',$v);
						$v = str_replace('http://instagram.com','',$v);
						$return['socnetFollowBlock']['instagram'] = esc_url('http://instagram.com/'.$v);
					}
					if($k == 'OD' || $k == 'odnoklassniki') {
						$v = str_replace('https://ok.ru','',$v);
						$v = str_replace('http://ok.ru','',$v);
						$return['socnetFollowBlock']['odnoklassniki'] = esc_url('http://ok.ru/'.$v);
					}
					if($k == 'LI' || $k == 'linkedin') {
						$v = str_replace('https://www.linkedin.com/','',$v);
						$v = str_replace('http://www.linkedin.com/','',$v);
						$return['socnetFollowBlock']['linkedin'] = esc_url('https://www.linkedin.com/'.$v);
					}
				}
			}
		}
	}
	if(isset($data['buttonBlock']['type'])){
		if($data['buttonBlock']['type'] == 'redirect'){
			$return['buttonActionBlock']['type'] = 'redirect';
			$return['buttonActionBlock']['redirect_url'] = esc_url($data['buttonBlock']['url']);
			$return['buttonActionBlock']['button_text'] = esc_url($data['buttonBlock']['button_text']);
		}
	}
		
	
	
	if($data['overlay']){
		$return['blackoutOption']['enable'] = 1;
		$return['blackoutOption']['style'] = PQ_ES_getNormalColorStructure('overlay', $data['overlay']).' '.$data['overlay_opacity'];
	}
	
	if($data['overlay_image_src']){
		$return['blackoutOption']['enable'] = 1;
		$return['blackoutOption']['background_image_src'] = esc_url($data['overlay_image_src']);
	}
	
	return $return;
}

function PQ_ES_checkIssetValue($array){
	$array['enable'] = (isset($array['enable']))?$array['enable']:'';
	$array['is_type'] = (isset($array['is_type']))?$array['is_type']:'';
	$array['ss_view_type'] = (isset($array['ss_view_type']))?$array['ss_view_type']:'';
	$array['ss_color'] = (isset($array['ss_color']))?$array['ss_color']:'';
	$array['ss_background_color'] = (isset($array['ss_background_color']))?$array['ss_background_color']:'';
	$array['ss_color_opacity'] = (isset($array['ss_color_opacity']))?$array['ss_color_opacity']:'';
	$array['ss_background_color_opacity'] = (isset($array['ss_background_color_opacity']))?$array['ss_background_color_opacity']:'';
	$array['overlay_opacity'] = (isset($array['overlay_opacity']))?$array['overlay_opacity']:'';
	$array['overlay'] = (isset($array['overlay']))?$array['overlay']:'';
	$array['background_color'] = (isset($array['background_color']))?$array['background_color']:'';
	$array['icon']['animation'] = (isset($array['icon']['animation']))?$array['icon']['animation']:'';
	$array['close_icon']['animation'] = (isset($array['close_icon']['animation']))?$array['close_icon']['animation']:'';
	$array['close_icon']['button_text'] = (isset($array['close_icon']['button_text']))?$array['close_icon']['button_text']:'';
	$array['position'] = (isset($array['position']))?$array['position']:'';
	$array['animation'] = (isset($array['animation']))?$array['animation']:'';
	$array['typeWindow'] = (isset($array['typeWindow']))?$array['typeWindow']:'';
	$array['bookmark_background'] = (isset($array['bookmark_background']))?$array['bookmark_background']:'';
	$array['bookmark_text_color'] = (isset($array['bookmark_text_color']))?$array['bookmark_text_color']:'';
	$array['bookmark_text_font'] = (isset($array['bookmark_text_font']))?$array['bookmark_text_font']:'';
	$array['bookmark_text_size'] = (isset($array['bookmark_text_size']))?$array['bookmark_text_size']:'';
	$array['themeClass'] = (isset($array['themeClass']))?$array['themeClass']:'';
	$array['icon']['design'] = (isset($array['icon']['design']))?$array['icon']['design']:'';
	$array['icon']['form'] = (isset($array['icon']['form']))?$array['icon']['form']:'';
	$array['icon']['size'] = (isset($array['icon']['size']))?$array['icon']['size']:'';
	$array['icon']['space'] = (isset($array['icon']['space']))?$array['icon']['space']:'';
	$array['icon']['shadow'] = (isset($array['icon']['shadow']))?$array['icon']['shadow']:'';
	$array['mobile_type'] = (isset($array['mobile_type']))?$array['mobile_type']:'';
	$array['mobile_position'] = (isset($array['mobile_position']))?$array['mobile_position']:'';
	$array['background_mobile_block'] = (isset($array['background_mobile_block']))?$array['background_mobile_block']:'';
	$array['mblock_text_font'] = (isset($array['mblock_text_font']))?$array['mblock_text_font']:'';
	$array['mblock_text_font_color'] = (isset($array['mblock_text_font_color']))?$array['mblock_text_font_color']:'';
	$array['mblock_text_font_size'] = (isset($array['mblock_text_font_size']))?$array['mblock_text_font_size']:'';
	$array['background_opacity'] = (isset($array['background_opacity']))?$array['background_opacity']:'';
	$array['text_font'] = (isset($array['text_font']))?$array['text_font']:'';
	$array['font_size'] = (isset($array['font_size']))?$array['font_size']:'';
	$array['text_color'] = (isset($array['text_color']))?$array['text_color']:'';
	$array['button_font'] = (isset($array['button_font']))?$array['button_font']:'';
	$array['button_text_color'] = (isset($array['button_text_color']))?$array['button_text_color']:'';
	$array['button_color'] = (isset($array['button_color']))?$array['button_color']:'';
	$array['button_font_size'] = (isset($array['button_font_size']))?$array['button_font_size']:'';
	$array['border_color'] = (isset($array['border_color']))?$array['border_color']:'';
	$array['popup_form'] = (isset($array['popup_form']))?$array['popup_form']:'';
	$array['head_font'] = (isset($array['head_font']))?$array['head_font']:'';
	$array['head_size'] = (isset($array['head_size']))?$array['head_size']:'';
	$array['head_color'] = (isset($array['head_color']))?$array['head_color']:'';
	$array['close_icon']['form'] = (isset($array['close_icon']['form']))?$array['close_icon']['form']:'';
	$array['close_icon']['color'] = (isset($array['close_icon']['color']))?$array['close_icon']['color']:'';
	$array['header_img_type'] = (isset($array['header_img_type']))?$array['header_img_type']:'';
	$array['close_text_font'] = (isset($array['close_text_font']))?$array['close_text_font']:'';
	$array['form_block_padding'] = (isset($array['form_block_padding']))?$array['form_block_padding']:'';
	$array['button_block_padding'] = (isset($array['button_block_padding']))?$array['button_block_padding']:'';
	$array['text_block_padding'] = (isset($array['text_block_padding']))?$array['text_block_padding']:'';
	$array['icon_block_padding'] = (isset($array['icon_block_padding']))?$array['icon_block_padding']:'';
	$array['button_form'] = (isset($array['button_form']))?$array['button_form']:'';
	$array['input_type'] = (isset($array['input_type']))?$array['input_type']:'';
	$array['button_type'] = (isset($array['button_type']))?$array['button_type']:'';
	$array['showup_animation'] = (isset($array['showup_animation']))?$array['showup_animation']:'';
	$array['background_button_block'] = (isset($array['background_button_block']))?$array['background_button_block']:'';
	$array['background_text_block'] = (isset($array['background_text_block']))?$array['background_text_block']:'';
	$array['background_form_block'] = (isset($array['background_form_block']))?$array['background_form_block']:'';
	$array['background_soc_block'] = (isset($array['background_soc_block']))?$array['background_soc_block']:'';
	$array['tblock_text_font_color'] = (isset($array['tblock_text_font_color']))?$array['tblock_text_font_color']:'';
	$array['tblock_text_font_size'] = (isset($array['tblock_text_font_size']))?$array['tblock_text_font_size']:'';
	$array['tblock_text_font'] = (isset($array['tblock_text_font']))?$array['tblock_text_font']:'';
	$array['icon']['position'] = (isset($array['icon']['position']))?$array['icon']['position']:'';
	$array['showup_animation'] = (isset($array['showup_animation']))?$array['showup_animation']:'';
	$array['galleryOption']['head_font'] = (isset($array['galleryOption']['head_font']))?$array['galleryOption']['head_font']:'';
	$array['galleryOption']['head_size'] = (isset($array['galleryOption']['head_size']))?$array['galleryOption']['head_size']:'';
	$array['galleryOption']['button_font'] = (isset($array['galleryOption']['button_font']))?$array['galleryOption']['button_font']:'';
	$array['galleryOption']['button_font_size'] = (isset($array['galleryOption']['button_font_size']))?$array['galleryOption']['button_font_size']:'';
	$array['galleryOption']['button_text_color'] = (isset($array['galleryOption']['button_text_color']))?$array['galleryOption']['button_text_color']:'';
	$array['galleryOption']['button_color'] = (isset($array['galleryOption']['button_color']))?$array['galleryOption']['button_color']:'';
	$array['galleryOption']['background_color'] = (isset($array['galleryOption']['background_color']))?$array['galleryOption']['background_color']:'';
	$array['galleryOption']['head_color'] = (isset($array['galleryOption']['head_color']))?$array['galleryOption']['head_color']:'';
	$array['galleryOption']['title'] = (isset($array['galleryOption']['title']))?$array['galleryOption']['title']:'';
	$array['galleryOption']['button_text'] = (isset($array['galleryOption']['button_text']))?$array['galleryOption']['button_text']:'';
	$array['galleryOption']['minWidth'] = (isset($array['galleryOption']['minWidth']))?$array['galleryOption']['minWidth']:'';
	$array['displayRules']['allowedExtensions'] = (isset($array['displayRules']['allowedExtensions']))?$array['displayRules']['allowedExtensions']:'';
	$array['displayRules']['display_on_main_page'] = (isset($array['displayRules']['display_on_main_page']))?$array['displayRules']['display_on_main_page']:'';
	$array['displayRules']['work_on_mobile'] = (isset($array['displayRules']['work_on_mobile']))?$array['displayRules']['work_on_mobile']:'';
	$array['displayRules']['allowedImageAddress'] = (isset($array['displayRules']['allowedImageAddress']))?$array['displayRules']['allowedImageAddress']:'';
	$array['galleryOption']['enable'] = (isset($array['galleryOption']['enable']))?$array['galleryOption']['enable']:'';
	$array['socnetOption'] = (isset($array['socnetOption']))?$array['socnetOption']:array();
	
	//rxr
	$array['title'] = (isset($array['title']))?$array['title']:'';
	$array['tblock_text'] = (isset($array['tblock_text']))?$array['tblock_text']:'';
	$array['sub_title'] = (isset($array['sub_title']))?$array['sub_title']:'';
	$array['mobile_title'] = (isset($array['mobile_title']))?$array['mobile_title']:'';
	$array['galleryOption']['title'] = (isset($array['galleryOption']['title']))?$array['galleryOption']['title']:'';
	$array['galleryOption']['button_text'] = (isset($array['galleryOption']['button_text']))?$array['galleryOption']['button_text']:'';
	$array['enter_email_text'] = (isset($array['enter_email_text']))?$array['enter_email_text']:'';
	$array['enter_name_text'] = (isset($array['enter_name_text']))?$array['enter_name_text']:'';
	$array['enter_phone_text'] = (isset($array['enter_phone_text']))?$array['enter_phone_text']:'';
	$array['enter_message_text'] = (isset($array['enter_message_text']))?$array['enter_message_text']:'';
	$array['enter_subject_text'] = (isset($array['enter_subject_text']))?$array['enter_subject_text']:'';
	$array['loader_text'] = (isset($array['loader_text']))?$array['loader_text']:'';
	$array['button_text'] = (isset($array['button_text']))?$array['button_text']:'';
	$array['close_icon']['button_text'] = (isset($array['close_icon']['button_text']))?$array['close_icon']['button_text']:'';
	$array['background_image_src'] = (isset($array['background_image_src']))?$array['background_image_src']:'';
	$array['header_image_src'] = (isset($array['header_image_src']))?$array['header_image_src']:'';
	$array['url'] = (isset($array['url']))?$array['url']:'';
	$array['iframe_src'] = (isset($array['iframe_src']))?$array['iframe_src']:'';
	$array['overlay_image_src'] = (isset($array['overlay_image_src']))?$array['overlay_image_src']:'';
	$array['mail_subject'] = (isset($array['mail_subject']))?$array['mail_subject']:'';
	return $array;
}

//Prepare output sctructure
function PQ_ES_prepareCode($data, $bookmark = 0){	
	$return = $data = PQ_ES_checkIssetValue($data);
	$return['contOptions'] = '';
	
	
	//enable
	if(isset($data['enable']) && (string)$data['enable'] == 'on' || (int)$data['enable'] == 1) $return['enable'] = 1;else $return['enable'] = 0;
	
	//hoverAnimation
	$return['hoverAnimation'] = $return['icon']['animation'];
	
	//closeIconOption
	
	$return['closeIconOption']['animation'] = wp_unslash($data['close_icon']['animation']);
	$return['closeIconOption']['button_text'] = wp_unslash($data['close_icon']['button_text']);
/************************************************GENERATE TYPE WINDOW********************************************************************************/
	//position
	if($data['position'] == 'BAR_TOP') $return['position'] = 'pq_top';
	if($data['position'] == 'BAR_BOTTOM') $return['position'] = 'pq_bottom';
	if($data['position'] == 'SIDE_LEFT_TOP') $return['position'] = 'pq_left pq_top';
	if($data['position'] == 'SIDE_LEFT_MIDDLE') $return['position'] = 'pq_left pq_middle';
	if($data['position'] == 'SIDE_LEFT_BOTTOM') $return['position'] = 'pq_left pq_bottom';
	if($data['position'] == 'SIDE_RIGHT_TOP') $return['position'] = 'pq_right pq_top';
	if($data['position'] == 'SIDE_RIGHT_MIDDLE') $return['position'] = 'pq_right pq_middle';
	if($data['position'] == 'SIDE_RIGHT_BOTTOM') $return['position'] = 'pq_right pq_bottom';
	if($data['position'] == 'CENTER') $return['position'] = 'pq_open_and_fix';
	if($data['position'] == 'FLOATING_LEFT_TOP') $return['position'] = 'pq_fixed pq_left pq_top';
	if($data['position'] == 'FLOATING_LEFT_BOTTOM') $return['position'] = 'pq_fixed pq_left pq_bottom';
	if($data['position'] == 'FLOATING_RIGHT_TOP') $return['position'] = 'pq_fixed pq_right pq_top';
	if($data['position'] == 'FLOATING_RIGHT_BOTTOM') $return['position'] = 'pq_fixed pq_right pq_bottom';
	
	if($data['position'] == 'IS_TOP_LEFT_INSIDE') $return['is_position'] = 'pq_is_top_left_inside';
	if($data['position'] == 'IS_TOP_LEFT_OUTSIDE') $return['is_position'] = 'pq_is_top_left_outside';
	if($data['position'] == 'IS_TOP_RIGHT_INSIDE') $return['is_position'] = 'pq_is_top_right_inside';
	if($data['position'] == 'IS_TOP_RIGHT_OUTSIDE') $return['is_position'] = 'pq_is_top_right_outside';
	if($data['position'] == 'IS_CENTER') $return['is_position'] = 'pq_is_center pq_inline';
	if($data['position'] == 'IS_BOTTOM_LEFT_INSIDE') $return['is_position'] = 'pq_is_bottom_left_inside pq_inline';
	if($data['position'] == 'IS_BOTTOM_CENTER_INSIDE') $return['is_position'] = 'pq_is_bottom_center_inside pq_inline';
	if($data['position'] == 'IS_BOTTOM_RIGHT_INSIDE') $return['is_position'] = 'pq_is_bottom_right_inside pq_inline';
	
	//animation
	if($data['animation']) $return['animation'] = 'pq_animated '.$data['animation'];	
	
	if((int)$bookmark == 1){
		$return['designOptions'] = $return['typeWindow'];
		$return['contOptions'] = 'pq_open_and_fix '.$return['animation'];
		$return['typeBookmarkWindow'] = ' '.PQ_ES_getNormalColorStructure('bookmark_background_color', $data['bookmark_background']).' '.PQ_ES_getNormalColorStructure('bookmark_text_color',$return['bookmark_text_color']).' '.$return['bookmark_text_font'].' '.$return['bookmark_text_size'];
		$return['typeBookmarkCont'] = $return['position'].' pq_fixed '.$return['animation'];
	}else{
		$return['designOptions'] = $return['typeWindow'];
		$return['contOptions'] = $return['animation'].' '.$return['position'];
	}	
	
	
	$return['designOptions'] .=' '.$return['themeClass'].' '.$return['icon']['design'].' '.$return['icon']['form'].' '.$return['icon']['size'].' '.$return['icon']['space'].' '.$return['icon']['shadow'];
	$return['designOptions'] .=' '.$return['ss_view_type'].' '.PQ_ES_RGBA_color($data['ss_color'], $data['ss_color_opacity'], 'pq_pro_color_socicons_PQRGBA_').' '.PQ_ES_RGBA_color($data['ss_background_color'], $data['ss_background_color_opacity'], 'pq_pro_bgcolor_socicons_PQRGBA_');
	$return['designOptions'] .=' '.$return['mobile_type'].' '.$return['mobile_position'];
	//new mobile
	$return['designOptions'] .=' '.PQ_ES_getNormalColorStructure('background_mobile_block',$return['background_mobile_block']).' '.$return['mblock_text_font'].' '.PQ_ES_getNormalColorStructure('mblock_text_font_color',$return['mblock_text_font_color']).' '.$return['mblock_text_font_size'];	
	$return['designOptions'] .=' '.PQ_ES_RGBA_color($data['background_color'], $data['background_opacity'], 'pq_bg_bgcolor_PQRGBA_').' '.$return['text_font'].' '.$return['font_size'].' '.PQ_ES_getNormalColorStructure('text_color',$return['text_color']).' '.$return['button_font'];
	$return['designOptions'] .=' '.PQ_ES_getNormalColorStructure('button_text_color',$return['button_text_color']).' '.PQ_ES_getNormalColorStructure('button_color',$return['button_color']).' '.$return['button_font_size'];
	$return['designOptions'] .=' '.PQ_ES_getNormalColorStructure('border_color',$return['border_color']).' '.$return['border_depth'].' '.$return['popup_form'].' '.$return['head_font'];
	$return['designOptions'] .=' '.$return['head_size'].' '.PQ_ES_getNormalColorStructure('head_color',$return['head_color']);	
	$return['designOptions'] .= ' '.$data['close_icon']['form'].' '.PQ_ES_getNormalColorStructure('close_icon_color', $data['close_icon']['color']);
	$return['designOptions'] .= ' '.$data['header_img_type'].' '.$data['close_text_font'];
	$return['designOptions'] .= ' '.$data['form_block_padding'].' '.$data['button_block_padding'];
	$return['designOptions'] .= ' '.$data['text_block_padding'].' '.$data['icon_block_padding'];
	$return['designOptions'] .= ' '.$data['button_form'].' '.$data['input_type'].' '.$data['button_type'];
	$return['designOptions'] .= ' '.$data['showup_animation'];
	
		
	
	//new
	$return['designOptions'] .= ' '.PQ_ES_getNormalColorStructure('background_button_block', $data['background_button_block']).' '.PQ_ES_getNormalColorStructure('background_text_block', $data['background_text_block']).' '.PQ_ES_getNormalColorStructure('background_form_block', $data['background_form_block']).' '.PQ_ES_getNormalColorStructure('background_soc_block', $data['background_soc_block']).' '.PQ_ES_getNormalColorStructure('tblock_text_font_color', $data['tblock_text_font_color']).' '.$data['tblock_text_font_size'].' '.$data['tblock_text_font'];
	
	$return['designOptions'] = str_replace('  ', ' ',$return['designOptions']);
	$return['designOptions'] = str_replace('  ', ' ',$return['designOptions']);
	
	$return['designOptions'] = wp_unslash($return['designOptions']);
	
	//type Design
	$return['typeDesign'] = $return['themeClass'].' '.$return['icon']['form'].' '.$return['icon']['size'].' '.$return['icon']['space'].' '.$return['icon']['shadow'].' '.$return['is_position'];
	$return['typeDesign'] .= ' '.PQ_ES_RGBA_color($data['ss_color'], $data['ss_color_opacity'], 'pq_pro_color_socicons_PQRGBA_').' '.PQ_ES_RGBA_color($data['ss_background_color'], $data['ss_background_color_opacity'], 'pq_pro_bgcolor_socicons_PQRGBA_');
	$return['typeDesign'] .= ' '.$data['is_type'];
	
	$return['typeDesign'] = wp_unslash($return['typeDesign']);
	
		
/*************************************************************************************************************************************************/	

	if(isset($data['displayRules'])){
		unset($return['displayRules']);
		if(isset($data['displayRules']['pageMask'])){
			foreach((array)$data['displayRules']['pageMask'] as $k => $v){
				if($v){
					if($data['displayRules']['pageMaskType'][$k] == 'enable'){
						$return['displayRules']['enableOnPage'][] = $v;
					}else{
						$return['displayRules']['disableOnPage'][] = $v;
					}
				}
			}
		}
		
		$return['displayRules']['display_on_main_page'] = ($data['displayRules']['display_on_main_page'] == 'on')?1:0;
		$return['displayRules']['work_on_mobile'] = ($data['displayRules']['work_on_mobile'] == 'on')?1:0;
		$return['displayRules']['allowedExtensions'] = $data['displayRules']['allowedExtensions'];
		$return['displayRules']['allowedImageAddress'] = $data['displayRules']['allowedImageAddress'];
		
	}else{
		$return['displayRules'] = array();
	}
	if(isset($data['thank'])){
		$return['thank'] = PQ_ES_getThankCode($data['thank']);
	}else{
		$return['thank'] = array();
	}
	
	

	if(isset($data['overlay']) && $data['overlay']){
		$return['blackoutOption']['enable'] = 1;
		$return['blackoutOption']['style'] = PQ_ES_getNormalColorStructure('overlay',$data['overlay']).' '.$data['overlay_opacity'];
	}
	
	if(isset($data['overlay_image_src']) && $data['overlay_image_src']){
		$return['blackoutOption']['enable'] = 1;
		$return['blackoutOption']['background_image_src'] = esc_url($data['overlay_image_src']);
	}
	
	
	
	
	if(isset($data['url'])){
		$return['buttonBlock']['action'] = 'redirect';		
		$return['buttonBlock']['redirect_url'] = esc_url($data['url']);
		$return['buttonBlock']['button_text'] = $data['button_text'];
	}
	
	
	//galerryOptions
	if((string)$data['galleryOption']['enable'] == 'on' || (int)$data['galleryOption']['enable'] == 1){
		unset($return['galleryOption']);
		$return['galleryOption']['enable'] = 1;
		$return['galleryOption']['designOptions'] = wp_unslash($data['galleryOption']['head_font'].' '.$data['galleryOption']['head_size'].' '.$data['galleryOption']['button_font'].' '.$data['galleryOption']['button_font_size'].' '. PQ_ES_getNormalColorStructure('gallery_button_text_color',$data['galleryOption']['button_text_color']).' '.PQ_ES_getNormalColorStructure('gallery_button_color',$data['galleryOption']['button_color']).' '.PQ_ES_getNormalColorStructure('gallery_background_color',$data['galleryOption']['background_color']).' '.PQ_ES_getNormalColorStructure('gallery_head_color',$data['galleryOption']['head_color']));
		$return['galleryOption']['title'] = wp_unslash($data['galleryOption']['title']);
		$return['galleryOption']['button_text'] = wp_unslash($data['galleryOption']['button_text']);		
		$return['galleryOption']['minWidth'] = (int)$data['galleryOption']['minWidth'];		
	}
	
	//Image Sharer socnet
	if(isset($data['socnet'])){
		unset($return['socnet']);
		foreach((array)$data['socnet'] as $k => $v){
			if($v){
				$return['socnet'][$k] = (isset($data['socnetOption'][$k]))?$data['socnetOption'][$k]:'';
				if(isset($data['socnetOption'][$k])){
					if($data['socnetOption'][$k]['type'] == 'pq'){
						$return['socnet'][$k]['exactPageShare'] = 0;
					} else {
						$return['socnet'][$k]['exactPageShare'] = 1;
					}
				}else{
					$return['socnet'][$k]['exactPageShare'] = 1;
				}
			}
		}
		
	}
		
	//socnet_with_pos
	if(isset($data['socnet_with_pos'])){
		unset($return['socnet']);
		foreach((array)$data['socnet_with_pos'] as $k => $v){
			if($v){
				$return['shareSocnet'][$v] = (isset($data['socnetOption'][$v]))?$data['socnetOption'][$v]:'';
				if(isset($data['socnetOption'][$v])){
					if($data['socnetOption'][$v]['type'] == 'pq'){
						$return['shareSocnet'][$v]['exactPageShare'] = 0;
					} else {
						$return['shareSocnet'][$v]['exactPageShare'] = 1;
					}
				}else{
					$return['shareSocnet'][$v]['exactPageShare'] = 1;
				}
			}
		}		
	}
	
	
	//followSocnet
	if(isset($data['socnetIconsBlock'])){
		foreach((array)$data['socnetIconsBlock'] as $k => $v){
			if($k && $v){
				if($k == 'FB' || $k == 'facebook') {
					$v = str_replace('https://facebook.com','',$v);
					$v = str_replace('http://facebook.com','',$v);
					$return['socnetFollowBlock']['facebook'] = esc_url('https://facebook.com/'.$v);
				}
				if($k == 'TW' || $k == 'twitter') {
					$v = str_replace('https://twitter.com','',$v);
					$v = str_replace('http://twitter.com','',$v);
					$return['socnetFollowBlock']['twitter'] = esc_url('https://twitter.com/'.$v);
				}
				if($k == 'GP' || $k == 'google-plus') {
					$v = str_replace('https://plus.google.com','',$v);
					$v = str_replace('http://plus.google.com','',$v);						
					$return['socnetFollowBlock']['google-plus'] = esc_url('https://plus.google.com/'.$v);
				}
				if($k == 'PI' || $k == 'pinterest') {
					$v = str_replace('https://pinterest.com','',$v);
					$v = str_replace('http://pinterest.com','',$v);
					$return['socnetFollowBlock']['pinterest'] = esc_url('https://pinterest.com/'.$v);
				}
				if($k == 'VK' || $k == 'vk') {
					$v = str_replace('https://vk.com/','',$v);
					$v = str_replace('http://vk.com/','',$v);
					$return['socnetFollowBlock']['vk'] = esc_url('http://vk.com/'.$v);
				}
				if($k == 'YT' || $k == 'youtube') {
					$v = str_replace('https://www.youtube.com/channel/','',$v);
					$v = str_replace('http://www.youtube.com/channel/','',$v);
					$return['socnetFollowBlock']['youtube'] = esc_url('https://www.youtube.com/channel/'.$v);
				}
				if($k == 'RSS') {
					$return['socnetFollowBlock']['RSS'] = esc_url($v);
				}
				if($k == 'IG' || $k == 'instagram') {
					$v = str_replace('https://instagram.com','',$v);
					$v = str_replace('http://instagram.com','',$v);
					$return['socnetFollowBlock']['instagram'] = esc_url('http://instagram.com/'.$v);
				}
				if($k == 'OD' || $k == 'odnoklassniki') {
					$v = str_replace('https://ok.ru','',$v);
					$v = str_replace('http://ok.ru','',$v);
					$return['socnetFollowBlock']['odnoklassniki'] = esc_url('http://ok.ru/'.$v);
				}
				if($k == 'LI' || $k == 'linkedin') {
					$v = str_replace('https://www.linkedin.com/','',$v);
					$v = str_replace('http://www.linkedin.com/','',$v);
					$return['socnetFollowBlock']['linkedin'] = esc_url('https://www.linkedin.com/'.$v);
				}
			}
		}		
	}	
	
	if(!isset($return['blackoutOption'])) $return['blackoutOption'] = array();
	if(!isset($return['eventHandler'])) $return['eventHandler'] = array();
	if(!isset($return['withCounter'])) $return['withCounter'] = '';
	if(!isset($return['provider'])) $return['provider'] = '';
	if(!isset($return['lockedMechanism'])) $return['lockedMechanism'] = array();
	if(!isset($return['providerOption'])) $return['providerOption'] = array();
	
	return $return;
}
function PQ_ES_code_injection(){
	global $profitquery;		
	$PQ_ES_code = array();	
	if((int)$profitquery['pq_es_widgets_loaded'] == 1){
		if((int)$profitquery['pq_aio_widgets_loaded'] == 0)
		{
			//sharingSidebar
			$preparedObject = PQ_ES_prepareCode($profitquery['sharingSidebar'], 0);
			$profitquery['sharingSidebar']['sendMailWindow']['position'] = 'CENTER';
			$sendMailWindow = PQ_ES_prepareCode($profitquery['sharingSidebar']['sendMailWindow'], 0, 1);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];		
			
			$PQ_ES_code['sharingSideBarOptions'] = array(
				'contOptions'=>'pq_fixed '.$preparedObject['contOptions'],
				'designOptions'=>'pq_icons '.$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],
				'socnetIconsBlock'=>$preparedObject['shareSocnet'],
				'withCounters'=>$preparedObject['withCounter'],
				'fake_counter'=>$preparedObject['fake_counter'],
				'mobile_title'=>wp_unslash($preparedObject['mobile_title']),		
				'hoverAnimation'=>wp_unslash($preparedObject['hoverAnimation']),		
				'eventHandler'=>$preparedObject['eventHandler'],
				'galleryOption'=>$preparedObject['galleryOption'],
				'displayRules'=>$preparedObject['displayRules'],		
				'sendMailWindow'=>$sendMailWindow,
				'thankPopup'=>$preparedObject['thank']
			);			
		//print_r($profitquery['sharingSidebar']);
		//die();
			//imageSharer
			$preparedObject = PQ_ES_prepareCode($profitquery['imageSharer'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];
			$profitquery['imageSharer']['sendMailWindow']['position'] = 'CENTER';
			$sendMailWindow = PQ_ES_prepareCode($profitquery['imageSharer']['sendMailWindow'], 0);
			$PQ_ES_code['imageSharer'] = array(
				'typeDesign'=>$preparedObject['typeDesign'],		
				'enable'=>(int)$preparedObject['enable'],
				'hoverAnimation'=>wp_unslash($preparedObject['hoverAnimation']),
				'minWidth'=>(int)$preparedObject['minWidth'],		
				'minHeight'=>(int)$preparedObject['minHeight'],		
				'activeSocnet'=>$preparedObject['socnet'],
				'sendMailWindow'=>$sendMailWindow,
				'displayRules'=>$preparedObject['displayRules'],	
				'thankPopup'=>$preparedObject['thank']
			);
			
			//emailListBuilderBar
			$preparedObject = PQ_ES_prepareCode($profitquery['emailListBuilderBar'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$PQ_ES_code['subscribeBarOptions'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>'pq_bar '.$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),
				'mobile_title'=>wp_unslash($preparedObject['mobile_title']),
				'closeIconOption'=>$preparedObject['closeIconOption'],
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'enter_email_text'=>wp_unslash($preparedObject['enter_email_text']),
				'enter_name_text'=>wp_unslash($preparedObject['enter_name_text']),
				'button_text'=>wp_unslash($preparedObject['button_text']),		
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],
				'subscribeProvider'=>wp_unslash($preparedObject['provider']),
				'subscribeProviderOption'=>$preparedObject['providerOption'],
				'thankPopup'=>$preparedObject['thank']
			);		
			
			//emailListBuilderPopup
			$preparedObject = PQ_ES_prepareCode($profitquery['emailListBuilderPopup'], 0);		
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$PQ_ES_code['subscribePopupOptions'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'closeIconOption'=>$preparedObject['closeIconOption'],
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'enter_email_text'=>wp_unslash($preparedObject['enter_email_text']),
				'enter_name_text'=>wp_unslash($preparedObject['enter_name_text']),		
				'button_text'=>wp_unslash($preparedObject['button_text']),
				'blackoutOption'=>$preparedObject['blackoutOption'],
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],
				'subscribeProvider'=>wp_unslash($preparedObject['provider']),
				'subscribeProviderOption'=>$preparedObject['providerOption'],
				'thankPopup'=>$preparedObject['thank']
			);
			
			//emailListBuilderFloating
			$preparedObject = PQ_ES_prepareCode($profitquery['emailListBuilderFloating'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$PQ_ES_code['subscribeFloatingOptions'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>'pq_fixed '.$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),		
				'closeIconOption'=>$preparedObject['closeIconOption'],
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'enter_email_text'=>wp_unslash($preparedObject['enter_email_text']),
				'enter_name_text'=>wp_unslash($preparedObject['enter_name_text']),		
				'button_text'=>wp_unslash($preparedObject['button_text']),		
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],
				'subscribeProvider'=>wp_unslash($preparedObject['provider']),
				'subscribeProviderOption'=>$preparedObject['providerOption'],
				'thankPopup'=>$preparedObject['thank']
			);
			
			//sharingPopup	
			$preparedObject = PQ_ES_prepareCode($profitquery['sharingPopup'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];			
			$profitquery['sharingPopup']['sendMailWindow']['position'] = 'CENTER';
			$sendMailWindow = PQ_ES_prepareCode($profitquery['sharingPopup']['sendMailWindow'], 0, 1);
			$PQ_ES_code['sharingPopup'] = array(		
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'fake_counter'=>$preparedObject['fake_counter'],
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'closeIconOption'=>$preparedObject['closeIconOption'],
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'shareSocnet'=>$preparedObject['shareSocnet'],
				'blackoutOption'=>$preparedObject['blackoutOption'],
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'sendMailWindow'=>$sendMailWindow,
				'thankPopup'=>$preparedObject['thank']
			);	
			
			//sharingBar
			$preparedObject = PQ_ES_prepareCode($profitquery['sharingBar'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];		
			$profitquery['sharingBar']['sendMailWindow']['position'] = 'CENTER';
			$sendMailWindow = PQ_ES_prepareCode($profitquery['sharingBar']['sendMailWindow'], 0, 1);
			$PQ_ES_code['sharingBar'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>'pq_bar '.$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'fake_counter'=>$preparedObject['fake_counter'],
				'closeIconOption'=>$preparedObject['closeIconOption'],
				'mobile_title'=>wp_unslash($preparedObject['mobile_title']),	
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'shareSocnet'=>$preparedObject['shareSocnet'],		
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'sendMailWindow'=>$sendMailWindow,
				'thankPopup'=>$preparedObject['thank']
			);
			
			//sharingFloating
			$preparedObject = PQ_ES_prepareCode($profitquery['sharingFloating'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$profitquery['sharingFloating']['sendMailWindow']['position'] = 'CENTER';
			$sendMailWindow = PQ_ES_prepareCode($profitquery['sharingFloating']['sendMailWindow'], 0, 1);
			$PQ_ES_code['sharingFloating'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>'pq_fixed '.$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'fake_counter'=>$preparedObject['fake_counter'],
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'closeIconOption'=>$preparedObject['closeIconOption'],
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'shareSocnet'=>$preparedObject['shareSocnet'],
				'blackoutOption'=>$preparedObject['blackoutOption'],
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'sendMailWindow'=>$sendMailWindow,
				'thankPopup'=>$preparedObject['thank']
			);
			
			//promotePopup
			$preparedObject = PQ_ES_prepareCode($profitquery['promotePopup'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$PQ_ES_code['promotePopup'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'closeIconOption'=>$preparedObject['closeIconOption'],		
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'buttonBlock'=>$preparedObject['buttonBlock'],
				'blackoutOption'=>$preparedObject['blackoutOption'],
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);
			
			//promoteBar
			$preparedObject = PQ_ES_prepareCode($profitquery['promoteBar'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];		
			$PQ_ES_code['promoteBar'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>'pq_bar '.$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'closeIconOption'=>$preparedObject['closeIconOption'],		
				'mobile_title'=>wp_unslash($preparedObject['mobile_title']),	
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'buttonBlock'=>$preparedObject['buttonBlock'],		
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);
			
			//promoteFloating
			$preparedObject = PQ_ES_prepareCode($profitquery['promoteFloating'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$PQ_ES_code['promoteFloating'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>'pq_fixed '.$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'closeIconOption'=>$preparedObject['closeIconOption'],		
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'buttonBlock'=>$preparedObject['buttonBlock'],
				'blackoutOption'=>$preparedObject['blackoutOption'],
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);
			
			
			//followPopup
			$preparedObject = PQ_ES_prepareCode($profitquery['followPopup'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];		
			$PQ_ES_code['followPopup'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'closeIconOption'=>$preparedObject['closeIconOption'],		
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'blackoutOption'=>$preparedObject['blackoutOption'],
				'followSocnet'=>$preparedObject['socnetFollowBlock'],
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);	
			
			//followBar
			$preparedObject = PQ_ES_prepareCode($profitquery['followBar'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];		
			$PQ_ES_code['followBar'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>'pq_bar '.$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'closeIconOption'=>$preparedObject['closeIconOption'],		
				'mobile_title'=>wp_unslash($preparedObject['mobile_title']),	
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'followSocnet'=>$preparedObject['socnetFollowBlock'],
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);
			
			//followFloating
			$preparedObject = PQ_ES_prepareCode($profitquery['followFloating'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];		
			$PQ_ES_code['followFloating'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>'pq_fixed '.$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'closeIconOption'=>$preparedObject['closeIconOption'],		
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'blackoutOption'=>$preparedObject['blackoutOption'],
				'followSocnet'=>$preparedObject['socnetFollowBlock'],
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);
			
			//callMePopup (bookmark)
			$preparedObject = PQ_ES_prepareCode($profitquery['callMePopup'], 1);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$preparedObject['to_email'] = $profitquery['settings']['email'];		
			$PQ_ES_code['callMePopup'] = array(	
				'typeBookmarkWindow'=>'pq_stick pq_call '.$preparedObject['typeBookmarkWindow'],
				'typeBookmarkCont'=>$preparedObject['typeBookmarkCont'],
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>$preparedObject['designOptions'],	
				'enable'=>(int)$preparedObject['enable'],		
				'to_email'=>wp_unslash($preparedObject['to_email']),
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'button_text'=>wp_unslash($preparedObject['button_text']),		
				'enter_name_text'=>wp_unslash($preparedObject['enter_name_text']),		
				'enter_phone_text'=>wp_unslash($preparedObject['enter_phone_text']),		
				'closeIconOption'=>$preparedObject['closeIconOption'],
				'mail_subject'=>wp_unslash($preparedObject['mail_subject']),		
				'bookmark_text'=>wp_unslash($preparedObject['loader_text']),		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),				
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'buttonBlock'=>$preparedObject['buttonBlock'],
				'blackoutOption'=>$preparedObject['blackoutOption'],		
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);	
			
			//callMeFloating
			$preparedObject = PQ_ES_prepareCode($profitquery['callMeFloating'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$preparedObject['to_email'] = $profitquery['settings']['email'];		
			$PQ_ES_code['callMeFloating'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>'pq_fixed '.$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'to_email'=>wp_unslash($preparedObject['to_email']),
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'button_text'=>wp_unslash($preparedObject['button_text']),		
				'enter_name_text'=>wp_unslash($preparedObject['enter_name_text']),		
				'enter_phone_text'=>wp_unslash($preparedObject['enter_phone_text']),		
				'mail_subject'=>wp_unslash($preparedObject['mail_subject']),		
				'closeIconOption'=>$preparedObject['closeIconOption'],
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),				
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'buttonBlock'=>$preparedObject['buttonBlock'],
				'blackoutOption'=>$preparedObject['blackoutOption'],		
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);
			
			//ContactFormCenter
			$preparedObject = PQ_ES_prepareCode($profitquery['contactFormCenter']);	
			//close_icon
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$preparedObject['to_email'] = $profitquery['settings']['email'];		
			$PQ_ES_code['contactFormCenter'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'to_email'=>wp_unslash($preparedObject['to_email']),
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'button_text'=>wp_unslash($preparedObject['button_text']),		
				'enter_name_text'=>wp_unslash($preparedObject['enter_name_text']),		
				'enter_email_text'=>wp_unslash($preparedObject['enter_email_text']),		
				'enter_message_text'=>wp_unslash($preparedObject['enter_message_text']),		
				'mail_subject'=>wp_unslash($preparedObject['mail_subject']),		
				'title'=>wp_unslash($preparedObject['title']),		
				'closeIconOption'=>$preparedObject['closeIconOption'],
				'sub_title'=>wp_unslash($preparedObject['sub_title']),				
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'buttonBlock'=>$preparedObject['buttonBlock'],
				'blackoutOption'=>$preparedObject['blackoutOption'],		
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);
			
			//ContactFormPopup (bookmark)
			$preparedObject = PQ_ES_prepareCode($profitquery['contactFormPopup'], 1);	
			//close_icon
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$preparedObject['to_email'] = $profitquery['settings']['email'];		
			$PQ_ES_code['contactFormPopup'] = array(
				'typeBookmarkWindow'=>'pq_stick pq_contact '.$preparedObject['typeBookmarkWindow'],
				'typeBookmarkCont'=>$preparedObject['typeBookmarkCont'],
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'to_email'=>wp_unslash($preparedObject['to_email']),
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'button_text'=>wp_unslash($preparedObject['button_text']),		
				'enter_name_text'=>wp_unslash($preparedObject['enter_name_text']),		
				'enter_email_text'=>wp_unslash($preparedObject['enter_email_text']),		
				'enter_message_text'=>wp_unslash($preparedObject['enter_message_text']),		
				'mail_subject'=>wp_unslash($preparedObject['mail_subject']),		
				'bookmark_text'=>wp_unslash($preparedObject['loader_text']),		
				'title'=>wp_unslash($preparedObject['title']),		
				'closeIconOption'=>$preparedObject['closeIconOption'],
				'sub_title'=>wp_unslash($preparedObject['sub_title']),				
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'buttonBlock'=>$preparedObject['buttonBlock'],
				'blackoutOption'=>$preparedObject['blackoutOption'],		
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);
			
			//contactFormFloating
			$preparedObject = PQ_ES_prepareCode($profitquery['contactFormFloating'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$preparedObject['to_email'] = $profitquery['settings']['email'];		
			
			$PQ_ES_code['contactFormFloating'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>'pq_fixed '.$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'to_email'=>wp_unslash($preparedObject['to_email']),
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'button_text'=>wp_unslash($preparedObject['button_text']),		
				'enter_name_text'=>wp_unslash($preparedObject['enter_name_text']),		
				'enter_email_text'=>wp_unslash($preparedObject['enter_email_text']),		
				'enter_message_text'=>wp_unslash($preparedObject['enter_message_text']),		
				'mail_subject'=>wp_unslash($preparedObject['mail_subject']),		
				'title'=>wp_unslash($preparedObject['title']),		
				'closeIconOption'=>$preparedObject['closeIconOption'],
				'sub_title'=>wp_unslash($preparedObject['sub_title']),				
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'buttonBlock'=>$preparedObject['buttonBlock'],
				'blackoutOption'=>$preparedObject['blackoutOption'],		
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);
			
			//youtubePopup
			$preparedObject = PQ_ES_prepareCode($profitquery['youtubePopup'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$PQ_ES_code['youtubePopup'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'closeIconOption'=>$preparedObject['closeIconOption'],		
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'iframe_src'=>esc_url($preparedObject['iframe_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'blackoutOption'=>$preparedObject['blackoutOption'],
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);

			//youtubeFloating
			$preparedObject = PQ_ES_prepareCode($profitquery['youtubeFloating'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$PQ_ES_code['youtubeFloating'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>'pq_fixed '.$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'closeIconOption'=>$preparedObject['closeIconOption'],		
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'iframe_src'=>esc_url($preparedObject['iframe_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);
			
			//iframePopup
			$preparedObject = PQ_ES_prepareCode($profitquery['iframePopup'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$PQ_ES_code['iframePopup'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'closeIconOption'=>$preparedObject['closeIconOption'],		
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'external_iframe_src'=>esc_url($preparedObject['iframe_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'blackoutOption'=>$preparedObject['blackoutOption'],
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);
			
			//iframeFloating
			$preparedObject = PQ_ES_prepareCode($profitquery['iframeFloating'], 0);	
			$preparedObject['displayRules']['main_page'] = $profitquery['settings']['mainPage'];	
			$PQ_ES_code['iframeFloating'] = array(
				'contOptions'=>$preparedObject['contOptions'],
				'designOptions'=>'pq_fixed '.$preparedObject['designOptions'],
				'enable'=>(int)$preparedObject['enable'],		
				'title'=>wp_unslash($preparedObject['title']),		
				'sub_title'=>wp_unslash($preparedObject['sub_title']),		
				'text_with_background'=>wp_unslash($preparedObject['tblock_text']),
				'closeIconOption'=>$preparedObject['closeIconOption'],		
				'header_image_src'=>esc_url($preparedObject['header_image_src']),
				'iframe_src'=>esc_url($preparedObject['iframe_src']),
				'background_image_src'=>esc_url($preparedObject['background_image_src']),
				'lockedMechanism'=>$preparedObject['lockedMechanism'],		
				'displayRules'=>$preparedObject['displayRules'],
				'eventHandler'=>$preparedObject['eventHandler'],		
				'thankPopup'=>$preparedObject['thank']
			);
			
			$additionalOptionText = '';
			if(isset($profitquery['settings'])){
				if(isset($profitquery['settings']['enableGA']) && (string)$profitquery['settings']['enableGA'] != 'on'){
					$additionalOptionText = 'profitquery.productOptions.disableGA = 1;';
				}
				if(isset($profitquery['settings']['from_right_to_left']) && $profitquery['settings']['from_right_to_left'] == 'on'){
					$additionalOptionText .= 'profitquery.productOptions.RTL = 1;';
				}
			}
			
			
			if($profitquery['settings']['apiKey']){		
				print "
				<script>
				(function () {			
						var PQ_ES_Init = function(){
							profitquery.loadFunc.callAfterPQInit(function(){					
								profitquery.loadFunc.callAfterPluginsInit(						
									function(){									
										PQ_ES_LoadTools();
									}
									, ['//profitquery-a.akamaihd.net/lib/plugins/aio.plugin.profitquery.v5.2.min.js']							
								);
							});
						};
						var s = document.createElement('script');
						var _isPQLibraryLoaded = false;
						s.type = 'text/javascript';
						s.async = true;			
						s.profitqueryAPIKey = '".wp_unslash($profitquery['settings']['apiKey'])."';
						s.profitqueryPROLoader = '".wp_unslash($profitquery['settings']['pro_loader_filename'])."';				
						s.profitqueryLang = 'en';				
						s.src = '//profitquery-a.akamaihd.net/lib/profitquery.v5.2.min.js';
						s.onload = function(){
							if ( !_isPQLibraryLoaded )
							{					
							  _isPQLibraryLoaded = true;				  
							  PQ_ES_Init();
							}
						}
						s.onreadystatechange = function() {								
							if ( !_isPQLibraryLoaded && (this.readyState == 'complete' || this.readyState == 'loaded') )
							{					
							  _isPQLibraryLoaded = true;				    
								
								PQ_ES_Init();					
							}
						};
						var x = document.getElementsByTagName('script')[0];						
						x.parentNode.insertBefore(s, x);			
					})();			
					function PQ_ES_LoadTools(){
						profitquery.loadFunc.callAfterPQInit(function(){
									if(profitquery.loadFunc.ifEmptyValue(profitquery._WPpluginAlreadyLoaded)){
										".$additionalOptionText."
										var PQ_ES_Structure = ".json_encode($PQ_ES_code).";							
										profitquery.widgets.smartWidgetsBox.init(PQ_ES_Structure);	
										profitquery._WPpluginAlreadyLoaded = 1;
									}							
								});
					}
				</script>	
				";		
			}
		}
	}
}


add_filter('plugin_action_links', 'PQ_ES_admin_link', 10, 2);