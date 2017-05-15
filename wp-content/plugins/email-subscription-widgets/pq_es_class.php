<?php
/* 
* +--------------------------------------------------------------------------+
* | Copyright (c) ShemOtechnik Profitquery Team shemotechnik@profitquery.com |
* +--------------------------------------------------------------------------+
* | This program is free software; you can redistribute it and/or modify     |
* | it under the terms of the GNU General Public License as published by     |
* | the Free Software Foundation; either version 2 of the License, or        |
* | (at your option) any later version.                                      |
* |                                                                          |
* | This program is distributed in the hope that it will be useful,          |
* | but WITHOUT ANY WARRANTY; without even the implied warranty of           |
* | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            |
* | GNU General Public License for more details.                             |
* |                                                                          |
* | You should have received a copy of the GNU General Public License        |
* | along with this program; if not, write to the Free Software              |
* | Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA |
* +--------------------------------------------------------------------------+
*/
/**
* @category Class
* @package  Wordpress_Plugin
* @author   ShemOtechnik Profitquery Team <support@profitquery.com>
* @license  http://www.php.net/license/3_01.txt  PHP License 3.01
* @version  SVN: 1.0.3
*/



class PQ_ES_Class
{
	/** Profitquery Settings **/
    var $_options;
	var $_themes;
	var $_themes_text;
	var $_default_themes;
	var $_pro_options;
	var $_plugin_settings;
	var $_dictionary;
	var $_plugin_review_url = 'https://wordpress.org/support/plugin/email-subscription-widgets/reviews/#new-post';
	var $_plugin_support_url = 'https://wordpress.org/support/plugin/email-subscription-widgets';
	var $_toolsName = array('emailListBuilderPopup', 'emailListBuilderBar', 'emailListBuilderFloating');
	/**
     * Initializes the plugin.
     *
     * @param null     
     * @return null
     * */
    function __construct()
    {		
		$this->_options = $this->getSettings();				
        add_action('admin_menu', array($this, 'PQ_ES_Menu'));

		wp_register_style('PQ_ES_Style', plugins_url( 'css/pq_es.css', __FILE__ ));
		wp_register_style('PQ_ES_fontsStyle', plugins_url( 'css/fonts.css', __FILE__ ));		
    }
	
	/*
		IsPLuginPage
		return boolean
	*/
	function isPluginPage(){
		$ret = false;
		if(strstr($_SERVER[REQUEST_URI], 'wp-admin/plugins.php')){
			$ret = true;
		}		
		return $ret;
	}
	
	
	/**
     * Functions to execute on plugin activation
     * 
     * @return null
     */
    public function pluginActivation()
    {
		$pq = get_option('profitquery');
		$pq['pq_es_widgets_loaded'] = 1;		
		update_option('profitquery', $pq);
    }
	
	
	 /**
     * Functions to execute on plugin deactivation
     * 
     * @return null
     */
    public function pluginDeactivation()
    {
		$pq = get_option('profitquery');
        if ($pq) {			
			$pq['pq_es_widgets_loaded'] = 0;
			$pq['emailListBuilderPopup']['enable'] = '';
			$pq['emailListBuilderBar']['enable'] = '';
			$pq['emailListBuilderFloating']['enable'] = '';
			
			update_option('profitquery', $pq);
        }
    }
	
	/**
     * Adds sub menu page to the WP settings menu
     * 
     * @return null
     */
    function PQ_ES_Menu()
    {
		$page = add_menu_page( 'Profitquery', 'PQ Grow Email List', 'manage_options', PQ_ES_PAGE_NAME, array($this, 'PQ_ES_Options'), plugins_url( 'i/pq_icon_2.png', __FILE__ ), 107.791 );        
		add_action('admin_print_styles-' . $page, array($this, 'PQ_ES_STYLES'));
		 add_options_page(
            'Profitquery', 'PQ Grow Email List',
            'manage_options', PQ_ES_PAGE_NAME,
            array($this, 'PQ_ES_Options')
        );
    }
	
	function PQ_ES_STYLES(){		
		wp_enqueue_style('PQ_ES_Style');
		wp_enqueue_style('PQ_ES_fontsStyle');
		wp_enqueue_style('wp-color-picker');		
	}
	 /**
     * Get the plugin's settings page url
     * 
     * @return string
     */
    function getSettingsPageUrl()
    {
        return admin_url("options-general.php?page=" . PQ_ES_PAGE_NAME);
    }
	
	function setDefaultProductData(){		
		//Other default params
		$thankPopup = Array(			
			'typeWindow' =>'pq_medium',
			'popup_form' =>'pq_br_sq',
			'background_color' =>'#ffffff',
			'background_opacity' =>'10',
			'border_type' =>'',
			'border_depth' =>'',
			'border_color' =>'',
			'animation' =>'pq_anim_bounceInDown',
			'overlay' =>'#969696',
			'overlay_opacity' =>'pq_overlay_60',
			'title' =>'Thank You',
			'head_font' =>'pq_h_font_h1_montserrat_700',
			'head_size' =>'pq_head_size28',
			'head_color' =>'#4c4c4c',
			'sub_title' =>'Thank you for taking action',
			'text_font' =>'pq_text_font_pqtext_arial',
			'font_size' =>'pq_text_size16',
			'text_color' =>'#939393',
			'socnet_block_type' =>'follow',
			'socnetIconsBlock' => Array
				(
					'FB' =>'profitquery',
					'TW' =>'profitquery',
					'GP' =>'',
					'PI' =>'profitquery',
					'YT' =>'',
					'LI' =>'',
					'VK' =>'',
					'OD' =>'',
					'IG' =>'',
					'RSS' =>''
				),
			'socnet_with_pos' => Array
				(
					'0' =>'FB',
					'1' =>'',
					'2' =>'',
					'3' =>'',
					'4' =>'',
					'5' =>'',
					'6' =>'',
					'7' =>'',
					'8' =>'',
					'9' =>'',
				),
			'background_soc_block' =>'',
			'icon_block_padding' =>'',
			'icon' => Array
				(
					'design' =>'c1',
					'form' =>'pq_square',
					'size' =>'x40',
					'space' =>'pq_step2',
					'animation' =>'pq_hvr_push'
				),
			'buttonBlock' => Array
				(
					'type' =>'redirect'
				),
			'url' =>'http://profitquery.com/',
			'button_text' =>'Learn More',
			'button_font' =>'pq_btn_font_btngroupbtn_montserrat_400',
			'button_font_size' =>'pq_btn_size18',
			'button_text_color' =>'#ffffff',
			'button_type' => 'pq_btn_type10',
			'button_color' =>'#0f52ba',
			'background_button_block' =>'',
			'button_block_padding' =>'',
			'close_icon' => Array
				(
					'form' =>'pq_x_type1',
					'button_text' =>'No, Thanks',
					'color' =>'#7a7a7b',
					'animation' =>'pq_hvr_grow'
				),
			'close_text_font' =>'pq_x_font_pqclose_montserrat_400'
		);
		
		$sendMailWindow = Array(
			'enable' =>'on',
			'typeWindow' =>'pq_medium',
			'popup_form' =>'pq_br_sq',
			'background_color' =>'#ffffff',
			'background_opacity' =>'10',
			'animation' =>'pq_anim_bounceInDown',
			'overlay' =>'#969696',
			'overlay_opacity' =>'',
			'title' =>'Send Email',
			'head_font' =>'pq_h_font_h1_montserrat_700',
			'head_size' =>'pq_head_size26',
			'head_color' =>'#444444',
			'sub_title' =>'Please fill in the form below to send a mail',
			'text_font' =>'pq_text_font_pqtext_arial',
			'font_size' =>'pq_text_size16',
			'text_color' =>'#a9a9a7',
			'enter_email_text' =>'Recipient Email',
			'enter_name_text' =>'Your Name',
			'enter_subject_text' =>'Subject',
			'enter_message_text' =>'Your Message',
			'background_form_block' =>'',
			'form_block_padding' =>'',
			'button_text' =>'SEND',
			'button_font' =>'pq_btn_font_btngroupbtn_montserrat_700',
			'button_font_size' =>'pq_btn_size16',
			'button_text_color' =>'#ffffff',
			'button_color' =>'#0f52ba',
			'button_type' => 'pq_btn_type10',
			'background_button_block' =>'',
			'button_block_padding' =>'',
			'close_icon' =>Array
				(
					'form' =>'pq_x_type1',
					'button_text' =>'CLOSE',
					'color' =>'#696969',
					'animation' =>''
				),
			'close_text_font' =>'pq_x_font_pqclose_arial'
		);
		
		
		if(!$this->_options[emailListBuilderBar]){    
		$this->_options['emailListBuilderBar'] = Array
			(
				'position' => 'BAR_TOP',
				'theme' => 'emaillistbuilderbar_1',
				'themeClass' => 'pq_t_emaillistbuilderbar_1',				
				'title' => 'Join Our Newsletter',				
				'enter_email_text' => 'Enter your email',
				'enter_name_text' => 'Enter your name',
				'button_text' => 'Subscribe',				
				'background' => 'pq_bg_backgroundimage_whtsmoke',
				'head_font' => 'pq_h_font_h1_helvetica',
				'head_size' => 'pq_head_size20',
				'head_color' => 'pq_h_color_h1_dimgrey',				
				'button_font' => 'pq_btn_font_btngroupbtn_helvetica',
				'button_text_color' => 'pq_btn_color_btngroupbtn_white',
				'button_color' => 'pq_btn_bg_bgcolor_btngroupbtn_mediumspringgreen',
				'close_icon' => Array
					(
						'form' => 'pq_x_type1',
						'button_text' => '',
						'color' => 'pq_x_color_pqclose_dimgrey',
						'animation' => 'pq_hvr_rotate'
					),
				'animation' => 'pq_anim_bounceInDown',				
				'mobile_position' => 'pq_mobile_top',
				'displayRules' => Array(
					'work_on_mobile' => 'on',
					'display_on_main_page'
				),
				'mobile_title' => 'Join Our Newsletter'
			);
			$this->_options['emailListBuilderBar']['thank'] = $thankPopup;
		}
		if(!$this->_options[emailListBuilderFloating]){ 
		$this->_options[emailListBuilderFloating] = Array
			(
				'position' => 'FLOATING_RIGHT_BOTTOM',
				'theme' => 'emaillistbuilderfloating_1',
				'themeClass' => 'pq_t_emaillistbuilderfloating_1',
				'typeWindow' => 'pq_medium',
				'popup_form' => 'pq_br_sq',
				'background' => 'pq_bg_backgroundimage_whtsmoke',
				'background_image_src' => '',
				'title' => 'Join Our Newsletter',
				'sub_title' => 'Signup today for free and be the first to get this exclusive report',
				'head_font' => 'pq_h_font_h1_helvetica',
				'head_size' => 'pq_head_size28',
				'head_color' => 'pq_h_color_h1_dimgrey',
				'sub_title' => '',
				'text_font' => 'pq_text_font_pqtext_helvetica',
				'font_size' => 'pq_text_size16',
				'text_color' => 'pq_text_color_p_darkgrey',
				'enter_email_text' => 'Enter your email',
				'enter_name_text' => 'Enter your name',
				'button_text' => 'Subscribe',
				'button_font' => 'pq_btn_font_btngroupbtn_helvetica',
				'button_text_color' => 'pq_btn_color_btngroupbtn_white',
				'button_color' => 'pq_btn_bg_bgcolor_btngroupbtn_mediumspringgreen',
				'close_icon' => Array
					(
						'form' => 'pq_x_type1',
						'button_text' => '',
						'color' => 'pq_x_color_pqclose_dimgrey',
						'animation' => 'pq_hvr_rotate'
					),
				'animation' => 'pq_anim_bounceInUp',				
				'displayRules' => Array(
					'work_on_mobile' => 'on',
					'display_on_main_page'
				),
				'enable' => ''
			);
			$this->_options['emailListBuilderFloating']['thank'] = $thankPopup;
		}
		
		if(!$this->_options[emailListBuilderPopup]){
		$this->_options[emailListBuilderPopup] = Array
			(
				'position' => 'CENTER',
				'theme' => 'emaillistbuilderpopup_1',
				'themeClass' => 'pq_t_emaillistbuilderpopup_1',
				'typeWindow' => 'pq_medium',
				'popup_form' => 'pq_br_sq',
				'background' => 'pq_bg_backgroundimage_whtsmoke',
				'title' => 'Join Our Newsletter',
				'sub_title' => 'Signup today for free and be the first to get this exclusive report',
				'head_font' => 'pq_h_font_h1_helvetica',
				'head_size' => 'pq_head_size28',
				'head_color' => 'pq_h_color_h1_dimgrey',
				'sub_title' => 'Signup today  and be the first to get notified on new updates',
				'text_font' => 'pq_text_font_pqtext_helvetica',
				'font_size' => 'pq_text_size16',
				'text_color' => 'pq_text_color_p_darkgrey',
				'enter_email_text' => 'Enter your email',
				'enter_name_text' => 'Enter your name',
				'button_text' => 'Subscribe',
				'button_font' => 'pq_btn_font_btngroupbtn_helvetica',
				'button_text_color' => 'pq_btn_color_btngroupbtn_white',
				'button_color' => 'pq_btn_bg_bgcolor_btngroupbtn_mediumspringgreen',
				'close_icon' => Array
					(
						'form' => 'pq_x_type1',
						'button_text' => '',
						'color' => 'pq_x_color_pqclose_dimgrey',
						'animation' => 'pq_hvr_rotate'
					),
				'animation' => 'pq_anim_bounceInDown',
				'overlay' => 'pq_over_bgcolor_lightgrey',				
				'displayRules' => Array(
					'work_on_mobile' => 'on',
					'display_on_main_page'=>'on'
				),
				'enable' => ''
			);
			$this->_options['emailListBuilderPopup']['thank'] = $thankPopup;
		}
		
		update_option('profitquery', $this->_options);
	}	
		
	
	
	
	
	/**
     *  Get LitePQ Share Image settings array
     * 
     *  @return string
     */
    function getSettings()
    {
        return get_option('profitquery');
    }
	
	function printr($array){
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}
	
	/*
	 *	parseSubscribeProviderForm
	 *	
	 *	@return array
	 */
	function parseSubscribeProviderForm($provider, $formCode)
	{		
		if($provider == 'mailchimp'){
			$return = $this->_parseMailchimpForm($formCode);
		}
		
		if($provider == 'acampaign'){
			$return = $this->_parseACampaignForm($formCode);
		}
		if($provider == 'aweber'){
			$return = $this->_parseAweberForm($formCode);
		}
		if($provider == 'newsletter2go'){
			$return = $this->_parseNewsLetter2goForm($formCode);
		}
		if($provider == 'madmini'){
			$return = $this->_parseMadMiniForm($formCode);
		}
		if($provider == 'getresponse'){
			$return = $this->_parseGetResponseForm($formCode);
		}
		if($provider == 'klickmail'){
			$return = $this->_parseGetKlickmailForm($formCode);
		}
		
		return $return;
	}
	
	
	function _parseMailchimpForm($code)
	{
		$txt = trim(wp_specialchars_decode($code));	
		$array = array();
		$matches = array();
		if($txt){
			$txt = str_replace("\t", ' ', $txt);
			$txt = str_replace("\r", '', $txt);
			$txt = str_replace("\n", '', $txt);
			$txt = str_replace("  ", " ", $txt);
			$txt = str_replace("  ", " ", $txt);			
			preg_match_all('/(\<)(.*)(form)(.*)(action=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(\>)/Ui', $txt, $matches);			
			$array[formAction] = trim($matches[8][0]);
			$array[formAction] = str_replace('&quot;','',$array[formAction]);
			$array[formAction] = str_replace('&#039;','',$array[formAction]);	
			
			if(!strstr($array[formAction], 'list-manage.com')){
				$array[formAction] = '';
				$array[is_error] = 1;
			}			
		}
		return $array;
	}
	
	function _parseNewsLetter2goForm($code)
	{
		$txt = trim(wp_specialchars_decode($code));		
		$array = array();
		$matches = array();
		if($txt){
			$txt = str_replace("\t", ' ', $txt);
			$txt = str_replace("\r", '', $txt);
			$txt = str_replace("\n", '', $txt);
			$txt = str_replace("  ", " ", $txt);
			$txt = str_replace("  ", " ", $txt);			
			preg_match_all('/(\<)(.*)(form)(.*)(action=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(\>)/Ui', $txt, $matches);
			$array[formAction] = trim($matches[8][0]);
			$array[formAction] = str_replace('&quot;','',$array[formAction]);
			$array[formAction] = str_replace('&#039;','',$array[formAction]);
			if(!strstr($array[formAction], 'newsletter2go.com')){
				$array[formAction] = '';
				$array[is_error] = 1;
			} else {
				preg_match_all('/(\<)(.*)(input)(.*)(hidden)(.*)(name=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(value=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(\>)/Ui', $txt, $matches);				
				foreach((array)$matches[10] as $k => $v){
					$v = str_replace('&quot;','',$v);
					$v = str_replace('&#039;','',$v);
					$val = $matches[16][$k];
					$val = str_replace('&quot;','',$val);
					$val = str_replace('&#039;','',$val);
					$hiddenField[$v] = $val;
				}
				if($hiddenField['nl2go--key']){
					$array[hidden] = $hiddenField;
				} else {
					$array[formAction] = '';
					$array[is_error] = 1;
				}
			}		
		}
		return $array;
	}
	
	
	function _parseMadMiniForm($code)
	{
		$txt = trim(wp_specialchars_decode($code));			
		$array = array();
		$matches = array();
		$hiddenField = array();		
		if($txt){
			$txt = str_replace("\t", ' ', $txt);
			$txt = str_replace("\r", '', $txt);
			$txt = str_replace("\n", '', $txt);
			$txt = str_replace("  ", " ", $txt);
			$txt = str_replace("  ", " ", $txt);			
			preg_match_all('/(\<)(.*)(form)(.*)(action=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(\>)/Ui', $txt, $matches);
			$array[formAction] = trim($matches[8][0]);
			$array[formAction] = str_replace('&quot;','',$array[formAction]);
			$array[formAction] = str_replace('&#039;','',$array[formAction]);
			if(!strstr($array[formAction], 'madmimi.com/signups/')){
				$array[formAction] = '';
				$array[is_error] = 1;
			} else {
				preg_match_all('/(\<)(.*)(input)(.*)(hidden)(.*)(name=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(value=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(\>)/Ui', $txt, $matches);				
				foreach((array)$matches[10] as $k => $v){
					$v = str_replace('&quot;','',$v);
					$v = str_replace('&#039;','',$v);
					$val = $matches[16][$k];
					$val = str_replace('&quot;','',$val);
					$val = str_replace('&#039;','',$val);
					$hiddenField[$v] = $val;
				}
			}
		}		
		return $array;
	}
	
	function _parseGetKlickmailForm($code)
	{
		$txt = trim(wp_specialchars_decode($code));		
		$array = array();
		$matches = array();
		$hiddenField = array();		
		if($txt){
			$txt = str_replace("\t", ' ', $txt);
			$txt = str_replace("\r", '', $txt);
			$txt = str_replace("\n", '', $txt);
			$txt = str_replace("  ", " ", $txt);
			$txt = str_replace("  ", " ", $txt);			
			preg_match_all('/(\<)(.*)(form)(.*)(action=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(\>)/Ui', $txt, $matches);			
			$array[formAction] = trim($matches[8][0]);
			$array[formAction] = str_replace('&quot;','',$array[formAction]);
			$array[formAction] = str_replace('&#039;','',$array[formAction]);
			if(!strstr($array[formAction], 'klickmail.com.br')){
				$array[formAction] = '';
				$array[is_error] = 1;
			} else {
				preg_match_all('/(\<)(.*)(input)(.*)(hidden)(.*)(name=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(value=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(\>)/Ui', $txt, $matches);				
				foreach((array)$matches[10] as $k => $v){
					$v = str_replace('&quot;','',$v);
					$v = str_replace('&#039;','',$v);
					$val = $matches[16][$k];
					$val = str_replace('&quot;','',$val);
					$val = str_replace('&#039;','',$val);
					$hiddenField[$v] = $val;
				}				
				if($hiddenField[FormValue_FormID]){
					$array[hidden] = $hiddenField;
				} else {
					$array[formAction] = '';
					$array[is_error] = 1;
				}
			}
		}		
		return $array;
	}
	
	function _parseGetResponseForm($code)
	{
		$txt = trim(wp_specialchars_decode($code));		
		$array = array();
		$matches = array();
		$hiddenField = array();		
		if($txt){			
			$txt = str_replace("\t", ' ', $txt);
			$txt = str_replace("\r", '', $txt);
			$txt = str_replace("\n", '', $txt);
			$txt = str_replace("  ", " ", $txt);
			$txt = str_replace("  ", " ", $txt);			
			preg_match_all('/(\<)(.*)(form)(.*)(action=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(\>)/Ui', $txt, $matches);
			$array[formAction] = trim($matches[8][0]);			
			$array[formAction] = str_replace('&quot;','',$array[formAction]);
			$array[formAction] = str_replace('&#039;','',$array[formAction]);
			if(!strstr($array[formAction], 'getresponse.com')){
				$array[formAction] = '';
				$array[is_error] = 1;
			} else {
				preg_match_all('/(\<)(.*)(input)(.*)(hidden)(.*)(name=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(value=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(\>)/Ui', $txt, $matches);				
				foreach((array)$matches[10] as $k => $v){
					$v = str_replace('&quot;','',$v);
					$v = str_replace('&#039;','',$v);
					$val = $matches[16][$k];
					$val = str_replace('&quot;','',$val);
					$val = str_replace('&#039;','',$val);
					$hiddenField[$v] = $val;
				}						
				if($hiddenField[campaign_token]){
					$array[hidden] = $hiddenField;
				} else {
					$array[formAction] = '';
					$array[is_error] = 1;
				}
			}			
		}		
		return $array;
	}
	
	function _parseACampaignForm($code)
	{
		$txt = trim(wp_specialchars_decode($code));		
		$array = array();
		$matches = array();
		$hiddenField = array();		
		if($txt){
			$txt = str_replace("\t", ' ', $txt);
			$txt = str_replace("\r", '', $txt);
			$txt = str_replace("\n", '', $txt);
			$txt = str_replace("  ", " ", $txt);
			$txt = str_replace("  ", " ", $txt);			
			preg_match_all('/(\<)(.*)(form)(.*)(action=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(\>)/Ui', $txt, $matches);
			$array[formAction] = trim($matches[8][0]);
			$array[formAction] = str_replace('&#039;','',$array[formAction]);
			$array[formAction] = str_replace('&quot;','',$array[formAction]);			
			if(!strstr($array[formAction], 'activehosted.com')){
				$array[formAction] = '';
				$array[is_error] = 1;
			} else {
				preg_match_all('/(\<)(.*)(input)(.*)(hidden)(.*)(name=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(value=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(\>)/Ui', $txt, $matches);				
				foreach((array)$matches[10] as $k => $v){
					$v = str_replace('&quot;','',$v);
					$v = str_replace('&#039;','',$v);
					$val = $matches[16][$k];
					$val = str_replace('&quot;','',$val);
					$val = str_replace('&#039;','',$val);
					$hiddenField[$v] = $val;
				}				
				if($hiddenField[act]){
					$array[hidden] = $hiddenField;
				} else {
					$array[formAction] = '';
					$array[is_error] = 1;
				}
			}
		}		
		return $array;
	}
	
	function _parseAweberForm($code)
	{
		$txt = trim(wp_specialchars_decode($code));
		$array = array();
		$matches = array();
		$hiddenField = array();
		if($txt){
			$txt = str_replace("\t", ' ', $txt);
			$txt = str_replace("\r", '', $txt);
			$txt = str_replace("\n", '', $txt);
			$txt = str_replace("  ", " ", $txt);
			$txt = str_replace("  ", " ", $txt);			
			preg_match_all('/(\<)(.*)(form)(.*)(action=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(\>)/Ui', $txt, $matches);
			$array[formAction] = trim($matches[8][0]);
			$array[formAction] = str_replace('&quot;','',$array[formAction]);
			$array[formAction] = str_replace('&#039;','',$array[formAction]);
			if(!strstr($array[formAction], 'aweber.com')){
				$array[formAction] = '';
				$array[is_error] = 1;
			} else {
				preg_match_all('/(\<)(.*)(input)(.*)(hidden)(.*)(name=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(value=)(.*)([\\\"\'])(.*)([\\\"\'])(.*)(\>)/Ui', $txt, $matches);
				foreach((array)$matches[10] as $k => $v){
					$v = str_replace('&quot;','',$v);
					$v = str_replace('&#039;','',$v);
					$val = $matches[16][$k];
					$val = str_replace('&quot;','',$val);
					$val = str_replace('&#039;','',$val);
					$hiddenField[$v] = $val;
				}
				if($hiddenField[meta_web_form_id]){
					$array[hidden] = $hiddenField;
				} else {
					$array[formAction] = '';
					$array[is_error] = 1;
				}
			}
		}
		return $array;
	}
		
	
	
	function sharingIconsSelect($object, $scructure){
		$ret = '';
		$sharingCnt = 10;
		
	}
	
	function generateInput($nameTool, $title, $id, $nameField, $val){
		$id = $this->prepareIdName($id);
		$jsFunc = str_replace('[', '',$nameTool);
		$jsFunc = str_replace(']', '',$jsFunc);
		$jsFunc = str_replace('buttonBlock','',$jsFunc);
		$jsFunc = str_replace('moreShareWindow','',$jsFunc);
		$ret = '
			<div id="'.$id.'_div">
			<p>'.$title.'</p>
					<input class="" type="text" id="'.$id.'" onKeyUp="'.$jsFunc.'Preview();" name="'.$nameField.'" value="'.stripslashes($val).'">
				<br>
			</div>
		';
		return $ret;
	}
	
	function prepareIdName($id){
		$id = str_replace('[', '_', $id);
		$id = str_replace(']', '_', $id);
		$id = str_replace('__', '_', $id);
		$id = str_replace('__', '_', $id);		
		return $id;
	}
	
	function getFontSelect($tool, $name, $text, $valArray, $id, $prefixForValue){
		$id = $this->prepareIdName($id);
		$ret = '';
		$jsFunc = str_replace('[', '',$tool);
		$jsFunc = str_replace(']', '',$jsFunc);		
		$jsFunc = str_replace('moreShareWindow','',$jsFunc);
		$jsFunc = str_replace('buttonBlock','',$jsFunc);
		
		$ret = '
		<div id="'.$id.'_div">
		<p>'.$text.'</p>
		<select name="'.$name.'" id="'.$id.'" onchange="'.$jsFunc.'Preview()">
		
		<optgroup label="Latin">
		<option value="" selected></option>
		<option value="'.$prefixForValue.'aguafina_script" '.sprintf("%s", (($valArray == $prefixForValue.'aguafina_script' ) ? "selected" : "")).'  style="font-family: \'Aguafina Script\', cursive;" >Aguafina Script</option>
		<option value="'.$prefixForValue.'alex_brush" '.sprintf("%s", (($valArray == $prefixForValue.'alex_brush' ) ? "selected" : "")).'  style="font-family: \'Alex Brush\', cursive;" >Alex Brush</option>
		<option value="'.$prefixForValue.'amita" '.sprintf("%s", (($valArray == $prefixForValue.'amita' ) ? "selected" : "")).'  style="font-family: \'Amita\', cursive;" >Amita</option>
		<option value="'.$prefixForValue.'anonymous_pro" '.sprintf("%s", (($valArray == $prefixForValue.'anonymous_pro' ) ? "selected" : "")).'  style="font-family: \'Anonymous Pro\', ;" >Anonymous Pro</option>
		<option value="'.$prefixForValue.'anonymous_pr_w" '.sprintf("%s", (($valArray == $prefixForValue.'anonymous_pr_w' ) ? "selected" : "")).'  style="font-family: \'Anonymous Pro\', font-weight: 700;" >Anonymous Pro Weight</option>
		<option value="'.$prefixForValue.'abril_fatface" '.sprintf("%s", (($valArray == $prefixForValue.'abril_fatface' ) ? "selected" : "")).'  style="font-family:\'Abril Fatface\', cursive; font-weight: 400!important;" >Abril Fatface</option>
		<option value="'.$prefixForValue.'arial" '.sprintf("%s", (($valArray == $prefixForValue.'arial' ) ? "selected" : "")).'  style="font-family: \'Arial\', Helvetica, sans-serif;" >Arial</option>
		<option value="'.$prefixForValue.'ar_w" '.sprintf("%s", (($valArray == $prefixForValue.'ar_w' ) ? "selected" : "")).'  style="font-family: \'Arial\', Helvetica, sans-serif, font-weight: 700;" >Arial Weight</option>
		<option value="'.$prefixForValue.'arimo" '.sprintf("%s", (($valArray == $prefixForValue.'arimo' ) ? "selected" : "")).'  style="font-family: \'Arimo\', sans-serif;" >Arimo</option>
		<option value="'.$prefixForValue.'armo_w" '.sprintf("%s", (($valArray == $prefixForValue.'armo_w' ) ? "selected" : "")).'  style="font-family: \'Arimo\', sans-serif, font-weight: 700;;" >Arimo Weight</option>
		<option value="'.$prefixForValue.'averia_gruesa_libre" '.sprintf("%s", (($valArray == $prefixForValue.'averia_gruesa_libre' ) ? "selected" : "")).'  style="font-family: \'Averia Gruesa Libre\', cursive;" >Averia Gruesa Libre</option>
		<option value="'.$prefixForValue.'berkshire_swash" '.sprintf("%s", (($valArray == $prefixForValue.'berkshire_swash' ) ? "selected" : "")).'  style="font-family: \'Berkshire Swash\', cursive;" >Berkshire Swash</option>
		<option value="'.$prefixForValue.'bitter" '.sprintf("%s", (($valArray == $prefixForValue.'bitter' ) ? "selected" : "")).'  style="font-family: \'Bitter\', serif;" >Bitter</option>
		<option value="'.$prefixForValue.'bittr_w" '.sprintf("%s", (($valArray == $prefixForValue.'bittr_w' ) ? "selected" : "")).'  style="font-family: \'Bitter\', serif, font-weight: 700;" >Bitter Weight</option>
		<option value="'.$prefixForValue.'black_ops_one" '.sprintf("%s", (($valArray == $prefixForValue.'black_ops_one' ) ? "selected" : "")).'  style="font-family: \'Black Ops One\', cursive;" >Black Ops One</option>
		<option value="'.$prefixForValue.'butterfly_kids" '.sprintf("%s", (($valArray == $prefixForValue.'butterfly_kids' ) ? "selected" : "")).'  style="font-family: \'Butterfly Kids\', cursive;" >Butterfly Kids</option>
		<option value="'.$prefixForValue.'cantata_one" '.sprintf("%s", (($valArray == $prefixForValue.'cantata_one' ) ? "selected" : "")).'  style="font-family: \'Cantata One\', serif;" >Cantata One</option>
		<option value="'.$prefixForValue.'chango" '.sprintf("%s", (($valArray == $prefixForValue.'chango' ) ? "selected" : "")).'  style="font-family: \'Chango\', cursive;" >Chango</option>
		<option value="'.$prefixForValue.'chela_one" '.sprintf("%s", (($valArray == $prefixForValue.'chela_one' ) ? "selected" : "")).'  style="font-family: \'Chela One\', cursive;" >Chela One</option>
		<option value="'.$prefixForValue.'chicle" '.sprintf("%s", (($valArray == $prefixForValue.'chicle' ) ? "selected" : "")).'  style="font-family: \'Chicle\', cursive;" >Chicle</option>
		<option value="'.$prefixForValue.'clicker_script" '.sprintf("%s", (($valArray == $prefixForValue.'clicker_script' ) ? "selected" : "")).'  style="font-family: \'Clicker Script\', cursive;" >Clicker Script</option>
		<option value="'.$prefixForValue.'codystar" '.sprintf("%s", (($valArray == $prefixForValue.'codystar' ) ? "selected" : "")).'  style="font-family: \'Codystar\', cursive;" >Codystar</option>
		<option value="'.$prefixForValue.'combo" '.sprintf("%s", (($valArray == $prefixForValue.'combo' ) ? "selected" : "")).'  style="font-family: \'Combo\', cursive;" >Combo</option>
		<option value="'.$prefixForValue.'comfortaa" '.sprintf("%s", (($valArray == $prefixForValue.'comfortaa' ) ? "selected" : "")).'  style="font-family: \'Comfortaa\', cursive;" >Comfortaa</option>
		<option value="'.$prefixForValue.'comfort_w" '.sprintf("%s", (($valArray == $prefixForValue.'comfort_w' ) ? "selected" : "")).'  style="font-family: \'Comfortaa\', cursive, font-weight: 700;" >Comfortaa Weight</option>
		<option value="'.$prefixForValue.'courier_new" '.sprintf("%s", (($valArray == $prefixForValue.'courier_new' ) ? "selected" : "")).'  style="font-family: \'courier_new\', \'times new roman\', sans-serif;" >Courier New</option>
		<option value="'.$prefixForValue.'croissant_one" '.sprintf("%s", (($valArray == $prefixForValue.'croissant_one' ) ? "selected" : "")).'  style="font-family: \'Croissant One\', cursive;" >Croissant One</option>
		<option value="'.$prefixForValue.'dynalight" '.sprintf("%s", (($valArray == $prefixForValue.'dynalight' ) ? "selected" : "")).'  style="font-family: \'Dynalight\', cursive;" >Dynalight</option>
		<option value="'.$prefixForValue.'eagle_lake" '.sprintf("%s", (($valArray == $prefixForValue.'eagle_lake' ) ? "selected" : "")).'  style="font-family: \'Eagle Lake\', cursive;" >Eagle Lake</option>
		<option value="'.$prefixForValue.'elsie" '.sprintf("%s", (($valArray == $prefixForValue.'elsie' ) ? "selected" : "")).'  style="font-family: \'Elsie\', cursive;" >Elsie</option>
		<option value="'.$prefixForValue.'els_w" '.sprintf("%s", (($valArray == $prefixForValue.'els_w' ) ? "selected" : "")).'  style="font-family: \'Elsie\', cursive, font-weight: 900;" >Elsie Weight</option>
		<option value="'.$prefixForValue.'emilys_candy" '.sprintf("%s", (($valArray == $prefixForValue.'emilys_candy' ) ? "selected" : "")).'  style="font-family: \'Emilys Candy\', cursive;" >Emilys Candy</option>
		<option value="'.$prefixForValue.'esteban" '.sprintf("%s", (($valArray == $prefixForValue.'esteban' ) ? "selected" : "")).'  style="font-family: \'Esteban\', serif;" >Esteban</option>
		<option value="'.$prefixForValue.'euphoria_script" '.sprintf("%s", (($valArray == $prefixForValue.'euphoria_script' ) ? "selected" : "")).'  style="font-family: \'Euphoria Script\', cursive;" >Euphoria Script</option>
		<option value="'.$prefixForValue.'exo" '.sprintf("%s", (($valArray == $prefixForValue.'exo' ) ? "selected" : "")).'  style="font-family: \'Exo\', sans-serif;" >Exo</option>
		<option value="'.$prefixForValue.'ekso_200" '.sprintf("%s", (($valArray == $prefixForValue.'ekso_200' ) ? "selected" : "")).'  style="font-family: \'Exo\', font-weight: 200;" >Exo Extra Light</option>
		<option value="'.$prefixForValue.'ekso_900" '.sprintf("%s", (($valArray == $prefixForValue.'ekso_900' ) ? "selected" : "")).'  style="font-family: \'Exo\', font-weight: 900;" >Exo Ultra-Bold</option>
		<option value="'.$prefixForValue.'ex_w" '.sprintf("%s", (($valArray == $prefixForValue.'ex_w' ) ? "selected" : "")).'  style="font-family: \'Exo\', font-weight: 700;" >Exo Weight</option>
		<option value="'.$prefixForValue.'grand_hotel" '.sprintf("%s", (($valArray == $prefixForValue.'grand_hotel' ) ? "selected" : "")).'  style="font-family: \'Grand Hotel\', cursive;" >Grand Hotel</option>
		<option value="'.$prefixForValue.'georgia" '.sprintf("%s", (($valArray == $prefixForValue.'georgia' ) ? "selected" : "")).'  style="font-family: \'georgia\', \'times new roman\', Arial, sans-serif;" >Georgia</option>
		<option value="'.$prefixForValue.'great_vibes" '.sprintf("%s", (($valArray == $prefixForValue.'great_vibes' ) ? "selected" : "")).'  style="font-family: \'Great Vibes\', cursive;" >Great Vibes</option>
		<option value="'.$prefixForValue.'gruppo" '.sprintf("%s", (($valArray == $prefixForValue.'gruppo' ) ? "selected" : "")).'  style="font-family: \'Gruppo\', cursive;" >Gruppo</option>
		<option value="'.$prefixForValue.'happy_monkey" '.sprintf("%s", (($valArray == $prefixForValue.'happy_monkey' ) ? "selected" : "")).'  style="font-family: \'Happy Monkey\', cursive;" >Happy Monkey</option>
		<option value="'.$prefixForValue.'helvetica" '.sprintf("%s", (($valArray == $prefixForValue.'helvetica' ) ? "selected" : "")).'  style="font-family: \'helvetica\', arial, \'open sans\', sans-serif;" >Helvetica</option>
		<option value="'.$prefixForValue.'helvetic_w" '.sprintf("%s", (($valArray == $prefixForValue.'helvetic_w' ) ? "selected" : "")).'  style="font-family: \'helvetica\', arial, \'open sans\', sans-serif, font-weight: 700;" >Helvetica Weight</option>
		<option value="'.$prefixForValue.'julius_sans_one" '.sprintf("%s", (($valArray == $prefixForValue.'julius_sans_one' ) ? "selected" : "")).'  style="font-family: \'Julius Sans One\', sans-serif;" >Julius Sans One</option>
		<option value="'.$prefixForValue.'kavoon" '.sprintf("%s", (($valArray == $prefixForValue.'kavoon' ) ? "selected" : "")).'  style="font-family: \'Kavoon\', cursive;" >Kavoon</option>
		<option value="'.$prefixForValue.'lancelot" '.sprintf("%s", (($valArray == $prefixForValue.'lancelot' ) ? "selected" : "")).'  style="font-family: \'Lancelot\', cursive;" >Lancelot</option>
		<option value="'.$prefixForValue.'lato" '.sprintf("%s", (($valArray == $prefixForValue.'lato' ) ? "selected" : "")).'  style="font-family: \'Lato\', sans-serif;" >Lato</option>
		<option value="'.$prefixForValue.'lat_w" '.sprintf("%s", (($valArray == $prefixForValue.'lat_w' ) ? "selected" : "")).'  style="font-family: \'Lato\', sans-serif, font-weight: 700;" >Lato Weight</option>
		<option value="'.$prefixForValue.'life_savers" '.sprintf("%s", (($valArray == $prefixForValue.'life_savers' ) ? "selected" : "")).'  style="font-family: \'Life Savers\', cursive;" >Life Savers</option>
		<option value="'.$prefixForValue.'life_svrs_w" '.sprintf("%s", (($valArray == $prefixForValue.'life_svrs_w' ) ? "selected" : "")).'  style="font-family: \'Life Savers\', cursive, font-weight: 700;" >Life Savers Weight</option>
		<option value="'.$prefixForValue.'lilita_one" '.sprintf("%s", (($valArray == $prefixForValue.'lilita_one' ) ? "selected" : "")).'  style="font-family: \'Lilita One\', cursive;" >Lilita One</option>
		<option value="'.$prefixForValue.'lily_script_one" '.sprintf("%s", (($valArray == $prefixForValue.'lily_script_one' ) ? "selected" : "")).'  style="font-family: \'Lily Script One\', cursive;" >Lilita One Weight</option>
		<option value="'.$prefixForValue.'limelight" '.sprintf("%s", (($valArray == $prefixForValue.'limelight' ) ? "selected" : "")).'  style="font-family: \'Limelight\', cursive;" >Limelight</option>
		<option value="'.$prefixForValue.'lobster" '.sprintf("%s", (($valArray == $prefixForValue.'lobster' ) ? "selected" : "")).'  style="font-family: \'Lobster Two\', cursive;" >Lobster Two</option>
		<option value="'.$prefixForValue.'lobstr_w" '.sprintf("%s", (($valArray == $prefixForValue.'lobstr_w' ) ? "selected" : "")).'  style="font-family: \'Lobster Two\', cursive, font-weight: 700;" >Lobster Two Weight</option>
		<option value="'.$prefixForValue.'mclaren" '.sprintf("%s", (($valArray == $prefixForValue.'mclaren' ) ? "selected" : "")).'  style="font-family: \'McLaren\', cursive;" >McLaren</option>
		<option value="'.$prefixForValue.'metamorphous" '.sprintf("%s", (($valArray == $prefixForValue.'metamorphous' ) ? "selected" : "")).'  style="font-family: \'Metamorphous\', cursive;" >Metamorphous</option>
		<option value="'.$prefixForValue.'milonga" '.sprintf("%s", (($valArray == $prefixForValue.'milonga' ) ? "selected" : "")).'  style="font-family: \'Milonga\', cursive;" >Milonga</option>
		<option value="'.$prefixForValue.'modak" '.sprintf("%s", (($valArray == $prefixForValue.'modak' ) ? "selected" : "")).'  style="font-family: \'Modak\', cursive;" >Modak</option>
		<option value="'.$prefixForValue.'molle" '.sprintf("%s", (($valArray == $prefixForValue.'molle' ) ? "selected" : "")).'  style="font-family: \'Molle\', cursive;" >Molle</option>
		<option value="'.$prefixForValue.'montserrat_400" '.sprintf("%s", (($valArray == $prefixForValue.'montserrat_400' ) ? "selected" : "")).'  style="font-family:\'Montserrat\', sans-serif; font-weight: 400!important;" >Montserrat 400</option>
		<option value="'.$prefixForValue.'montserrat_700" '.sprintf("%s", (($valArray == $prefixForValue.'montserrat_700' ) ? "selected" : "")).'  style="font-family:\'Montserrat\', sans-serif; font-weight: 700!important;" >Montserrat 700</option>
		<option value="'.$prefixForValue.'mystery_quest" '.sprintf("%s", (($valArray == $prefixForValue.'mystery_quest' ) ? "selected" : "")).'  style="font-family: \'Mystery Quest\', cursive;" >Mystery Quest</option>
		<option value="'.$prefixForValue.'neuton" '.sprintf("%s", (($valArray == $prefixForValue.'neuton' ) ? "selected" : "")).'  style="font-family: \'Neuton\', serif;" >Neuton</option>
		<option value="'.$prefixForValue.'neutn_w" '.sprintf("%s", (($valArray == $prefixForValue.'neutn_w' ) ? "selected" : "")).'  style="font-family: \'Neuton\', serif, font-weight: 700;" >Neuton Weight</option>
		<option value="'.$prefixForValue.'nosifer" '.sprintf("%s", (($valArray == $prefixForValue.'nosifer' ) ? "selected" : "")).'  style="font-family: \'Nosifer\', cursive;" >Nosifer</option>
		<option value="'.$prefixForValue.'oleo_script_swash_caps" '.sprintf("%s", (($valArray == $prefixForValue.'oleo_script_swash_caps' ) ? "selected" : "")).'  style="font-family: \'Oleo Script Swash Caps\', cursive;" >Oleo Script Swash Caps</option>
		<option value="'.$prefixForValue.'oleo_script_swash_cps_w" '.sprintf("%s", (($valArray == $prefixForValue.'oleo_script_swash_cps_w' ) ? "selected" : "")).'  style="font-family: \'Oleo Script Swash Caps\', cursive, font-weight: 700;" >Oleo Script Swash Caps Weight</option>
		<option value="'.$prefixForValue.'open_sans" '.sprintf("%s", (($valArray == $prefixForValue.'open_sans' ) ? "selected" : "")).'  style="font-family: \'Open Sans\', sans-serif;" >Open Sans</option>
		<option value="'.$prefixForValue.'open_sns_w" '.sprintf("%s", (($valArray == $prefixForValue.'open_sns_w' ) ? "selected" : "")).'  style="font-family: \'Open Sans\', sans-serif, font-weight: 700;" >Open Sans Weight</option>
		<option value="'.$prefixForValue.'oswald" '.sprintf("%s", (($valArray == $prefixForValue.'oswald' ) ? "selected" : "")).'  style="font-family: \'Oswald\', sans-serif;" >Oswald</option>
		<option value="'.$prefixForValue.'oswld_300" '.sprintf("%s", (($valArray == $prefixForValue.'oswld_300' ) ? "selected" : "")).'  style="font-family: \'Oswald\', sans-serif, font-weight: 300;" >Oswald Light</option>
		<option value="'.$prefixForValue.'oswld_w" '.sprintf("%s", (($valArray == $prefixForValue.'oswld_w' ) ? "selected" : "")).'  style="font-family: \'Oswald\', sans-serif, font-weight: 700;" >Oswald Weight</option>
		<option value="'.$prefixForValue.'overlock" '.sprintf("%s", (($valArray == $prefixForValue.'overlock' ) ? "selected" : "")).'  style="font-family: \'Overlock\', cursive;" >Overlock</option>
		<option value="'.$prefixForValue.'overlck_w" '.sprintf("%s", (($valArray == $prefixForValue.'overlck_w' ) ? "selected" : "")).'  style="font-family: \'Overlock\', cursive, font-weight: 900;" >Overlock Weight</option>
		<option value="'.$prefixForValue.'passion_one" '.sprintf("%s", (($valArray == $prefixForValue.'passion_one' ) ? "selected" : "")).'  style="font-family: \'Passion One\', cursive;" >Passion One</option>
		<option value="'.$prefixForValue.'passion_on_w" '.sprintf("%s", (($valArray == $prefixForValue.'passion_on_w' ) ? "selected" : "")).'  style="font-family: \'Passion One\', cursive, font-weight: 700;" >Passion One Weight</option>
		<option value="'.$prefixForValue.'pathway_gothic_one" '.sprintf("%s", (($valArray == $prefixForValue.'pathway_gothic_one' ) ? "selected" : "")).'  style="font-family: \'Pathway Gothic One\', sans-serif;" >Pathway Gothic One</option>
		<option value="'.$prefixForValue.'petit_formal_script" '.sprintf("%s", (($valArray == $prefixForValue.'petit_formal_script' ) ? "selected" : "")).'  style="font-family: \'Petit Formal Script\', cursive;" >Petit Formal Script</option>
		<option value="'.$prefixForValue.'playball" '.sprintf("%s", (($valArray == $prefixForValue.'playball' ) ? "selected" : "")).'  style="font-family: \'Playball\', cursive;" >Playball</option>
		<option value="'.$prefixForValue.'playfair_display" '.sprintf("%s", (($valArray == $prefixForValue.'playfair_display' ) ? "selected" : "")).'  style="font-family: \'Playfair Display\', serif;" >Playfair Display</option>
		<option value="'.$prefixForValue.'playfair_disply_700" '.sprintf("%s", (($valArray == $prefixForValue.'playfair_disply_700' ) ? "selected" : "")).'  style="font-family: \'Playfair Display\', serif, font-weight: 700;" >Playfair Display Bold</option>
		<option value="'.$prefixForValue.'playfair_disply_w" '.sprintf("%s", (($valArray == $prefixForValue.'playfair_disply_w' ) ? "selected" : "")).'  style="font-family: \'Playfair Display\', serif, font-weight: 900;" >Playfair Display Ultra Bold</option>
		<option value="'.$prefixForValue.'poiret_one" '.sprintf("%s", (($valArray == $prefixForValue.'poiret_one' ) ? "selected" : "")).'  style="font-family: \'Poiret One\', cursive;" >Poiret One</option>
		<option value="'.$prefixForValue.'pragati_narrow" '.sprintf("%s", (($valArray == $prefixForValue.'pragati_narrow' ) ? "selected" : "")).'  style="font-family: \'Pragati Narrow\', sans-serif;" >Pragati Narrow</option>
		<option value="'.$prefixForValue.'pragati_narrw_w" '.sprintf("%s", (($valArray == $prefixForValue.'pragati_narrw_w' ) ? "selected" : "")).'  style="font-family: \'Pragati Narrow\', sans-serif, font-weight: 700;" >Pragati Narrow Weight</option>
		<option value="'.$prefixForValue.'princess_sofia" '.sprintf("%s", (($valArray == $prefixForValue.'princess_sofia' ) ? "selected" : "")).'  style="font-family: \'Princess Sofia\', cursive;" >Princess Sofia</option>
		<option value="'.$prefixForValue.'prosto_one" '.sprintf("%s", (($valArray == $prefixForValue.'prosto_one' ) ? "selected" : "")).'  style="font-family: \'Prosto One\', cursive;" >Prosto One</option>
		<option value="'.$prefixForValue.'pt_sans" '.sprintf("%s", (($valArray == $prefixForValue.'pt_sans' ) ? "selected" : "")).'  style="font-family: \'PT Sans\', sans-serif;" >PT Sans</option>
		<option value="'.$prefixForValue.'pt_sns_w" '.sprintf("%s", (($valArray == $prefixForValue.'pt_sns_w' ) ? "selected" : "")).'  style="font-family: \'PT Sans\', sans-serif, font-weight: 700;" >PT Sans Weight</option>
		<option value="'.$prefixForValue.'pt_sns_nrrow" '.sprintf("%s", (($valArray == $prefixForValue.'pt_sns_nrrow' ) ? "selected" : "")).'  style="font-family: \'PT Sans Narrow\', sans-serif;" >PT Sans Narrow</option>
		<option value="'.$prefixForValue.'pt_sns_nrrw_w" '.sprintf("%s", (($valArray == $prefixForValue.'pt_sns_nrrw_w' ) ? "selected" : "")).'  style="font-family: \'PT Sans Narrow\', font-weight: 700;" >PT Sans Narrow Weight</option>
		<option value="'.$prefixForValue.'purple_purse" '.sprintf("%s", (($valArray == $prefixForValue.'purple_purse' ) ? "selected" : "")).'  style="font-family: \'Purple Purse\', cursive;" >Purple Purse</option>
		<option value="'.$prefixForValue.'quattrocento" '.sprintf("%s", (($valArray == $prefixForValue.'quattrocento' ) ? "selected" : "")).'  style="font-family: \'Quattrocento Sans\', sans-serif;" >Quattrocento Sans</option>
		<option value="'.$prefixForValue.'quattrocent_w" '.sprintf("%s", (($valArray == $prefixForValue.'quattrocent_w' ) ? "selected" : "")).'  style="font-family: \'Quattrocento Sans\', sans-serif, font-weight: 700;" >Quattrocento Sans Weight</option>
		<option value="'.$prefixForValue.'quintessential" '.sprintf("%s", (($valArray == $prefixForValue.'quintessential' ) ? "selected" : "")).'  style="font-family: \'Quintessential\', cursive;" >Quintessential</option>
		<option value="'.$prefixForValue.'racing_sans_one" '.sprintf("%s", (($valArray == $prefixForValue.'racing_sans_one' ) ? "selected" : "")).'  style="font-family: \'Racing Sans One\', cursive;" >Racing Sans One</option>
		<option value="'.$prefixForValue.'raleway_200" '.sprintf("%s", (($valArray == $prefixForValue.'raleway_200' ) ? "selected" : "")).'  style="font-family:\'Raleway\', sans-serif; font-weight: 200!important;" >Raleway 200</option>
		<option value="'.$prefixForValue.'raleway_400" '.sprintf("%s", (($valArray == $prefixForValue.'raleway_400' ) ? "selected" : "")).'  style="font-family:\'Raleway\', sans-serif; font-weight: 400!important;" >Raleway 400</option>
		<option value="'.$prefixForValue.'raleway_500" '.sprintf("%s", (($valArray == $prefixForValue.'raleway_500' ) ? "selected" : "")).'  style="font-family:\'Raleway\', sans-serif; font-weight: 500!important;" >Raleway 500</option>
		<option value="'.$prefixForValue.'raleway_800" '.sprintf("%s", (($valArray == $prefixForValue.'raleway_800' ) ? "selected" : "")).'  style="font-family:\'Raleway\', sans-serif; font-weight: 800!important;" >Raleway 800</option>
		<option value="'.$prefixForValue.'righteous" '.sprintf("%s", (($valArray == $prefixForValue.'righteous' ) ? "selected" : "")).'  style="font-family: \'Righteous\', cursive;" >Righteous</option>
		<option value="'.$prefixForValue.'roboto" '.sprintf("%s", (($valArray == $prefixForValue.'roboto' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif;" >Roboto</option>
		<option value="'.$prefixForValue.'robot_300" '.sprintf("%s", (($valArray == $prefixForValue.'robot_300' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif, font-weight: 300;" >Roboto Light</option>
		<option value="'.$prefixForValue.'robot_w" '.sprintf("%s", (($valArray == $prefixForValue.'robot_w' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif, font-weight: 700;" >Roboto Bold</option>
		<option value="'.$prefixForValue.'robt_ww" '.sprintf("%s", (($valArray == $prefixForValue.'robt_ww' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif, font-weight: 900;" >Roboto Ultra Bold</option>
		<option value="'.$prefixForValue.'rbt_slab_300" '.sprintf("%s", (($valArray == $prefixForValue.'rbt_slab_300' ) ? "selected" : "")).'  style="font-family:\'Roboto Slab\', serif; font-weight: 300!important;" >Roboto Slab 300</option>
		<option value="'.$prefixForValue.'rbt_slab_400" '.sprintf("%s", (($valArray == $prefixForValue.'rbt_slab_400' ) ? "selected" : "")).'  style="font-family:\'Roboto Slab\', serif; font-weight: 400!important;" >Roboto Slab 400</option>
		<option value="'.$prefixForValue.'rbt_slab_700" '.sprintf("%s", (($valArray == $prefixForValue.'rbt_slab_700' ) ? "selected" : "")).'  style="font-family:\'Roboto Slab\', serif; font-weight: 700!important;" >Roboto Slab 700</option>
		<option value="'.$prefixForValue.'rbt_condensd_300" '.sprintf("%s", (($valArray == $prefixForValue.'rbt_condensd_300' ) ? "selected" : "")).'  style="font-family: \'Roboto Condensed\', sans-serif, font-weight: 300;" >Roboto Condensed Light</option>
		<option value="'.$prefixForValue.'rbt_condensed" '.sprintf("%s", (($valArray == $prefixForValue.'rbt_condensed' ) ? "selected" : "")).'  style="font-family: \'Roboto Condensed\', sans-serif, font-weight: 400;" >Roboto Condensed</option>
		<option value="'.$prefixForValue.'rbt_condensd_700" '.sprintf("%s", (($valArray == $prefixForValue.'rbt_condensd_700' ) ? "selected" : "")).'  style="font-family: \'Roboto Condensed\', sans-serif, font-weight: 700;" >Roboto Condensed Bold</option>
		<option value="'.$prefixForValue.'rubik_mono_one" '.sprintf("%s", (($valArray == $prefixForValue.'rubik_mono_one' ) ? "selected" : "")).'  style="font-family: \'Rubik Mono One\', sans-serif;" >Rubik Mono One</option>
		<option value="'.$prefixForValue.'sacramento" '.sprintf("%s", (($valArray == $prefixForValue.'sacramento' ) ? "selected" : "")).'  style="font-family: \'Sacramento\', cursive;" >Sacramento</option>
		<option value="'.$prefixForValue.'shadows_into_light_two" '.sprintf("%s", (($valArray == $prefixForValue.'shadows_into_light_two' ) ? "selected" : "")).'  style="font-family: \'Shadows Into Light Two\', cursive;" >Shadows Into Light Two</option>
		<option value="'.$prefixForValue.'signika" '.sprintf("%s", (($valArray == $prefixForValue.'signika' ) ? "selected" : "")).'  style="font-family: \'Signika\', sans-serif;" >Signika</option>
		<option value="'.$prefixForValue.'signik_w" '.sprintf("%s", (($valArray == $prefixForValue.'signik_w' ) ? "selected" : "")).'  style="font-family: \'Signika\', sans-serif, font-weight: 700;" >Signika Weight</option>
		<option value="'.$prefixForValue.'simonetta" '.sprintf("%s", (($valArray == $prefixForValue.'simonetta' ) ? "selected" : "")).'  style="font-family: \'Simonetta\', cursive;" >Simonetta</option>
		<option value="'.$prefixForValue.'simonett_w" '.sprintf("%s", (($valArray == $prefixForValue.'simonett_w' ) ? "selected" : "")).'  style="font-family: \'Simonetta\', cursive, font-weight: 900;" >Simonetta Weight</option>
		<option value="'.$prefixForValue.'sniglet" '.sprintf("%s", (($valArray == $prefixForValue.'sniglet' ) ? "selected" : "")).'  style="font-family: \'Sniglet\', cursive;" >Sniglet</option>
		<option value="'.$prefixForValue.'sniglt_w" '.sprintf("%s", (($valArray == $prefixForValue.'sniglt_w' ) ? "selected" : "")).'  style="font-family: \'Sniglet\', cursive, font-weight: 800;" >Sniglet Weight</option>
		<option value="'.$prefixForValue.'source_sans_pro" '.sprintf("%s", (($valArray == $prefixForValue.'source_sans_pro' ) ? "selected" : "")).'  style="font-family: \'Source Sans Pro\', sans-serif;" >Source Sans Pro</option>
		<option value="'.$prefixForValue.'source_sans_pr_w" '.sprintf("%s", (($valArray == $prefixForValue.'source_sans_pr_w' ) ? "selected" : "")).'  style="font-family: \'Source Sans Pro\', sans-serif, font-weight: 700;" >Source Sans Pro Weight</option>
		<option value="'.$prefixForValue.'stint_ultra_expanded" '.sprintf("%s", (($valArray == $prefixForValue.'stint_ultra_expanded' ) ? "selected" : "")).'  style="font-family: \'Stint Ultra Expanded\', cursive;" >Stint Ultra Expanded</option>
		<option value="'.$prefixForValue.'teko" '.sprintf("%s", (($valArray == $prefixForValue.'teko' ) ? "selected" : "")).'  style="font-family: \'Teko\', sans-serif;" >Teko</option>
		<option value="'.$prefixForValue.'tek_w" '.sprintf("%s", (($valArray == $prefixForValue.'tek_w' ) ? "selected" : "")).'  style="font-family: \'Teko\', sans-serif, font-weight: 700;" >Teko Weight</option>
		<option value="'.$prefixForValue.'text_me_one" '.sprintf("%s", (($valArray == $prefixForValue.'text_me_one' ) ? "selected" : "")).'  style="font-family: \'Text Me One\', sans-serif;" >Text Me One</option>
		<option value="'.$prefixForValue.'times_new_roman" '.sprintf("%s", (($valArray == $prefixForValue.'times_new_roman' ) ? "selected" : "")).'  style="font-family: \'times new roman\', Arial, sans-serif;" >Times new roman</option>
		<option value="'.$prefixForValue.'titan_one" '.sprintf("%s", (($valArray == $prefixForValue.'titan_one' ) ? "selected" : "")).'  style="font-family: \'Titan One\', cursive;" >Titan One</option>
		<option value="'.$prefixForValue.'trocchi" '.sprintf("%s", (($valArray == $prefixForValue.'trocchi' ) ? "selected" : "")).'  style="font-family: \'Trocchi\', serif;" >Trocchi</option>
		<option value="'.$prefixForValue.'ubuntu" '.sprintf("%s", (($valArray == $prefixForValue.'ubuntu' ) ? "selected" : "")).'  style="font-family: \'Ubuntu\', sans-serif;" >Ubuntu</option>
		<option value="'.$prefixForValue.'ubunt_w" '.sprintf("%s", (($valArray == $prefixForValue.'ubunt_w' ) ? "selected" : "")).'  style="font-family: \'Ubuntu\', sans-serif, font-weight: 700;" >Ubuntu Weight</option>
		<option value="'.$prefixForValue.'unica_one" '.sprintf("%s", (($valArray == $prefixForValue.'unica_one' ) ? "selected" : "")).'  style="font-family: \'Unica One\', cursive;" >Unica One</option>
		<option value="'.$prefixForValue.'varela" '.sprintf("%s", (($valArray == $prefixForValue.'varela' ) ? "selected" : "")).'  style="font-family: \'Varela\', sans-serif;" >Varela</option>
		<option value="'.$prefixForValue.'verdana" '.sprintf("%s", (($valArray == $prefixForValue.'verdana' ) ? "selected" : "")).'  style="font-family: \'Verdana\', Arial, sans-serif;" >Verdana</option>
		<option value="'.$prefixForValue.'viga" '.sprintf("%s", (($valArray == $prefixForValue.'viga' ) ? "selected" : "")).'  style="font-family: \'Viga\', sans-serif;" >Viga</option>
		<option value="'.$prefixForValue.'warnes" '.sprintf("%s", (($valArray == $prefixForValue.'warnes' ) ? "selected" : "")).'  style="font-family: \'Warnes\', cursive;" >Warnes</option>
		</optgroup>
		
		<optgroup label="Cyrillic">
		<option value="'.$prefixForValue.'andika" '.sprintf("%s", (($valArray == $prefixForValue.'andika' ) ? "selected" : "")).'  style="font-family: \'Andika\', sans-serif;" >Andika</option>
		<option value="'.$prefixForValue.'arial" '.sprintf("%s", (($valArray == $prefixForValue.'arial' ) ? "selected" : "")).'  style="font-family: \'Arial\', Helvetica, sans-serif;" >Arial</option>
		<option value="'.$prefixForValue.'ar_w" '.sprintf("%s", (($valArray == $prefixForValue.'ar_w' ) ? "selected" : "")).'  style="font-family: \'Arial\', Helvetica, sans-serif, font-weight: 700;" >Arial Weight</option>
		<option value="'.$prefixForValue.'comfortaa" '.sprintf("%s", (($valArray == $prefixForValue.'comfortaa' ) ? "selected" : "")).'  style="font-family: \'Comfortaa\', cursive;" >Comfortaa</option>
		<option value="'.$prefixForValue.'comfort_w" '.sprintf("%s", (($valArray == $prefixForValue.'comfort_w' ) ? "selected" : "")).'  style="font-family: \'Comfortaa\', cursive, font-weight: 700;" >Comfortaa Weight</option>
		<option value="'.$prefixForValue.'courier_new" '.sprintf("%s", (($valArray == $prefixForValue.'courier_new' ) ? "selected" : "")).'  style="font-family: \'courier_new\', \'times new roman\', sans-serif;" >Courier New</option>
		<option value="'.$prefixForValue.'cousine" '.sprintf("%s", (($valArray == $prefixForValue.'cousine' ) ? "selected" : "")).'  style="font-family: \'Cousine\', ;" >Cousine</option>
		<option value="'.$prefixForValue.'cousin_w" '.sprintf("%s", (($valArray == $prefixForValue.'cousin_w' ) ? "selected" : "")).'  style="font-family: \'Cousine\', font-weight: 700;" >Cousine Weight</option>
		<option value="'.$prefixForValue.'didact_gothic" '.sprintf("%s", (($valArray == $prefixForValue.'didact_gothic' ) ? "selected" : "")).'  style="font-family: \'Didact Gothic\', sans-serif;" >Didact Gothic</option>
		<option value="'.$prefixForValue.'didact_gothc_w" '.sprintf("%s", (($valArray == $prefixForValue.'didact_gothc_w' ) ? "selected" : "")).'  style="font-family: \'Didact Gothic\', sans-serif, font-weight: 700;" >Didact Gothic Weight</option>
		<option value="'.$prefixForValue.'eb_garamond" '.sprintf("%s", (($valArray == $prefixForValue.'eb_garamond' ) ? "selected" : "")).'  style="font-family: \'EB Garamond\', serif;" >EB Garamond</option>
		<option value="'.$prefixForValue.'eb_garamnd_w" '.sprintf("%s", (($valArray == $prefixForValue.'eb_garamnd_w' ) ? "selected" : "")).'  style="font-family: \'EB Garamond\', serif, font-weight: 700;" >EB Garamond Weight</option>
		<option value="'.$prefixForValue.'exx2" '.sprintf("%s", (($valArray == $prefixForValue.'exx2' ) ? "selected" : "")).'  style="font-family: \'Exo 2\', sans-serif;" >Exo 2</option>
		<option value="'.$prefixForValue.'exxx2_w" '.sprintf("%s", (($valArray == $prefixForValue.'exxx2_w' ) ? "selected" : "")).'  style="font-family: \'Exo 2\', sans-serif, font-weight: 700;" >Exo 2 Weight</option>
		<option value="'.$prefixForValue.'fira_sans" '.sprintf("%s", (($valArray == $prefixForValue.'fira_sans' ) ? "selected" : "")).'  style="font-family: \'Fira Sans\', sans-serif;" >Fira Sans</option>
		<option value="'.$prefixForValue.'fira_sns_w" '.sprintf("%s", (($valArray == $prefixForValue.'fira_sns_w' ) ? "selected" : "")).'  style="font-family: \'Fira Sans\', sans-serif, font-weight: 700;" >Fira Sans Weight</option>
		<option value="'.$prefixForValue.'georgia" '.sprintf("%s", (($valArray == $prefixForValue.'georgia' ) ? "selected" : "")).'  style="font-family: \'georgia\', \'times new roman\', Arial, sans-serif;" >Georgia</option>
		<option value="'.$prefixForValue.'helvetica" '.sprintf("%s", (($valArray == $prefixForValue.'helvetica' ) ? "selected" : "")).'  style="font-family: \'helvetica\', arial, \'open sans\', sans-serif;" >Helvetica</option>
		<option value="'.$prefixForValue.'helvetic_w" '.sprintf("%s", (($valArray == $prefixForValue.'helvetic_w' ) ? "selected" : "")).'  style="font-family: \'helvetica\', arial, \'open sans\', sans-serif, font-weight: 700;" >Helvetica Weight</option>
		<option value="'.$prefixForValue.'jura" '.sprintf("%s", (($valArray == $prefixForValue.'jura' ) ? "selected" : "")).'  style="font-family: \'Jura\', sans-serif;" >Jura</option>
		<option value="'.$prefixForValue.'jur_w" '.sprintf("%s", (($valArray == $prefixForValue.'jur_w' ) ? "selected" : "")).'  style="font-family: \'Jura\', sans-serif, font-weight: 500;" >Jura Weight</option>
		<option value="'.$prefixForValue.'marmelad" '.sprintf("%s", (($valArray == $prefixForValue.'marmelad' ) ? "selected" : "")).'  style="font-family: \'Marmelad\', sans-serif;" >Marmelad</option>
		<option value="'.$prefixForValue.'marmeld_w" '.sprintf("%s", (($valArray == $prefixForValue.'marmeld_w' ) ? "selected" : "")).'  style="font-family: \'Marmelad\', sans-serif, font-weight: 700;" >Marmelad Weight</option>
		<option value="'.$prefixForValue.'neucha" '.sprintf("%s", (($valArray == $prefixForValue.'neucha' ) ? "selected" : "")).'  style="font-family: \'Neucha\', cursive;" >Neucha</option>
		<option value="'.$prefixForValue.'noto_serif" '.sprintf("%s", (($valArray == $prefixForValue.'noto_serif' ) ? "selected" : "")).'  style="font-family: \'Noto Serif\', serif;" >Noto Serif</option>
		<option value="'.$prefixForValue.'noto_serf_w" '.sprintf("%s", (($valArray == $prefixForValue.'noto_serf_w' ) ? "selected" : "")).'  style="font-family: \'Noto Serif\', serif, font-weight: 700;" >Noto Serif Weight</option>
		<option value="'.$prefixForValue.'open_sans" '.sprintf("%s", (($valArray == $prefixForValue.'open_sans' ) ? "selected" : "")).'  style="font-family: \'Open Sans\', sans-serif;" >Open Sans</option>
		<option value="'.$prefixForValue.'open_sns_w" '.sprintf("%s", (($valArray == $prefixForValue.'open_sns_w' ) ? "selected" : "")).'  style="font-family: \'Open Sans\', sans-serif, font-weight: 700;" >Open Sans Weight</option>
		<option value="'.$prefixForValue.'oranienbaum" '.sprintf("%s", (($valArray == $prefixForValue.'oranienbaum' ) ? "selected" : "")).'  style="font-family: \'Oranienbaum\', serif;" >Oranienbaum</option>
		<option value="'.$prefixForValue.'oranienbam_w" '.sprintf("%s", (($valArray == $prefixForValue.'oranienbam_w' ) ? "selected" : "")).'  style="font-family: \'Oranienbaum\', serif;" >Oranienbaum Weight</option>
		<option value="'.$prefixForValue.'philosopher" '.sprintf("%s", (($valArray == $prefixForValue.'philosopher' ) ? "selected" : "")).'  style="font-family: \'Philosopher\', sans-serif;" >Philosopher</option>
		<option value="'.$prefixForValue.'philosophr_w" '.sprintf("%s", (($valArray == $prefixForValue.'philosophr_w' ) ? "selected" : "")).'  style="font-family: \'Philosopher\', font-weight: 700;" >Philosopher Weight</option>
		<option value="'.$prefixForValue.'ply" '.sprintf("%s", (($valArray == $prefixForValue.'ply' ) ? "selected" : "")).'  style="font-family: \'Play\', sans-serif;" >Play</option>
		<option value="'.$prefixForValue.'pl_y_w" '.sprintf("%s", (($valArray == $prefixForValue.'pl_y_w' ) ? "selected" : "")).'  style="font-family: \'Play\', sans-serif, font-weight: 700;" >Play Weight</option>
		<option value="'.$prefixForValue.'playfair_disply_700" '.sprintf("%s", (($valArray == $prefixForValue.'playfair_disply_700' ) ? "selected" : "")).'  style="font-family: \'Playfair Display\', serif, font-weight: 700;" >Playfair Display Bold</option>
		<option value="'.$prefixForValue.'pt_mono" '.sprintf("%s", (($valArray == $prefixForValue.'pt_mono' ) ? "selected" : "")).'  style="font-family: \'PT Mono\', ;" >PT Mono</option>
		<option value="'.$prefixForValue.'pt_sans" '.sprintf("%s", (($valArray == $prefixForValue.'pt_sans' ) ? "selected" : "")).'  style="font-family: \'PT Sans\', sans-serif;" >PT Sans</option>
		<option value="'.$prefixForValue.'pt_sns_nrrow" '.sprintf("%s", (($valArray == $prefixForValue.'pt_sns_nrrow' ) ? "selected" : "")).'  style="font-family: \'PT Sans Narrow\', sans-serif;" >PT Sans Narrow</option>
		<option value="'.$prefixForValue.'pt_sns_nrrw_w" '.sprintf("%s", (($valArray == $prefixForValue.'pt_sns_nrrw_w' ) ? "selected" : "")).'  style="font-family: \'PT Sans Narrow\', font-weight: 700;" >PT Sans Narrow Weight</option>
		<option value="'.$prefixForValue.'pt_sns_w" '.sprintf("%s", (($valArray == $prefixForValue.'pt_sns_w' ) ? "selected" : "")).'  style="font-family: \'PT Sans\', sans-serif, font-weight: 700;" >PT Sans Weight</option>
		<option value="'.$prefixForValue.'pt_serif_caption" '.sprintf("%s", (($valArray == $prefixForValue.'pt_serif_caption' ) ? "selected" : "")).'  style="font-family: \'PT Serif Caption\', serif;" >PT Serif Caption</option>
		<option value="'.$prefixForValue.'roboto" '.sprintf("%s", (($valArray == $prefixForValue.'roboto' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif;" >Roboto</option>
		<option value="'.$prefixForValue.'robot_300" '.sprintf("%s", (($valArray == $prefixForValue.'robot_300' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif, font-weight: 300;" >Roboto Light</option>
		<option value="'.$prefixForValue.'robot_w" '.sprintf("%s", (($valArray == $prefixForValue.'robot_w' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif, font-weight: 700;" >Roboto Weight</option>
		<option value="'.$prefixForValue.'robt_ww" '.sprintf("%s", (($valArray == $prefixForValue.'robt_ww' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif, font-weight: 900;" >Roboto Weight+</option>
		<option value="'.$prefixForValue.'rbt_condensd_300" '.sprintf("%s", (($valArray == $prefixForValue.'rbt_condensd_300' ) ? "selected" : "")).'  style="font-family: \'Roboto Condensed\', sans-serif, font-weight: 300;" >Roboto Condensed Light</option>
		<option value="'.$prefixForValue.'rbt_condensed" '.sprintf("%s", (($valArray == $prefixForValue.'rbt_condensed' ) ? "selected" : "")).'  style="font-family: \'Roboto Condensed\', sans-serif, font-weight: 400;" >Roboto Condensed</option>
		<option value="'.$prefixForValue.'rbt_condensd_700" '.sprintf("%s", (($valArray == $prefixForValue.'rbt_condensd_700' ) ? "selected" : "")).'  style="font-family: \'Roboto Condensed\', sans-serif, font-weight: 700;" >Roboto Condensed Bold</option>
		<option value="'.$prefixForValue.'rubik_one" '.sprintf("%s", (($valArray == $prefixForValue.'rubik_one' ) ? "selected" : "")).'  style="font-family: \'Rubik One\', sans-serif;" >Rubik One</option>
		<option value="'.$prefixForValue.'ruslan_display" '.sprintf("%s", (($valArray == $prefixForValue.'ruslan_display' ) ? "selected" : "")).'  style="font-family: \'Ruslan Display\', cursive;" >Ruslan Display</option>
		<option value="'.$prefixForValue.'russo_one" '.sprintf("%s", (($valArray == $prefixForValue.'russo_one' ) ? "selected" : "")).'  style="font-family: \'Russo One\', sans-serif;" >Russo One</option>
		<option value="'.$prefixForValue.'times_new_roman" '.sprintf("%s", (($valArray == $prefixForValue.'times_new_roman' ) ? "selected" : "")).'  style="font-family: \'times new roman\', Arial, sans-serif;" >Times new roman</option>
		<option value="'.$prefixForValue.'tinos" '.sprintf("%s", (($valArray == $prefixForValue.'tinos' ) ? "selected" : "")).'  style="font-family: \'Tinos\', serif;" >Tinos</option>
		<option value="'.$prefixForValue.'tins_w" '.sprintf("%s", (($valArray == $prefixForValue.'tins_w' ) ? "selected" : "")).'  style="font-family: \'Tinos\', serif, font-weight: 700;" >Tinos Weight</option>
		<option value="'.$prefixForValue.'ubuntu" '.sprintf("%s", (($valArray == $prefixForValue.'ubuntu' ) ? "selected" : "")).'  style="font-family: \'Ubuntu\', sans-serif;" >Ubuntu</option>
		<option value="'.$prefixForValue.'ubunt_mono" '.sprintf("%s", (($valArray == $prefixForValue.'ubunt_mono' ) ? "selected" : "")).'  style="font-family: \'Ubuntu Mono\', ;" >Ubuntu Mono</option>
		<option value="'.$prefixForValue.'ubunt_mon_w" '.sprintf("%s", (($valArray == $prefixForValue.'ubunt_mon_w' ) ? "selected" : "")).'  style="font-family: \'Ubuntu Mono\', font-weight: 700;" >Ubuntu Mono Weight</option>
		<option value="'.$prefixForValue.'ubunt_w" '.sprintf("%s", (($valArray == $prefixForValue.'ubunt_w' ) ? "selected" : "")).'  style="font-family: \'Ubuntu\', sans-serif, font-weight: 700;" >Ubuntu Weight</option>
		<option value="'.$prefixForValue.'verdana" '.sprintf("%s", (($valArray == $prefixForValue.'verdana' ) ? "selected" : "")).'  style="font-family: \'Verdana\', Arial, sans-serif;" >Verdana</option>
		<option value="'.$prefixForValue.'yeseva_one" '.sprintf("%s", (($valArray == $prefixForValue.'yeseva_one' ) ? "selected" : "")).'  style="font-family: \'Yeseva One\', cursive;" >Yeseva One</option>
		<option value="'.$prefixForValue.'yeseva_on_w" '.sprintf("%s", (($valArray == $prefixForValue.'yeseva_on_w' ) ? "selected" : "")).'  style="font-family: \'Yeseva One\', cursive, font-weight: 700;" >Yeseva One Weight</option>
		</optgroup>
		
		<optgroup label="Arabic">
		<option value="'.$prefixForValue.'amiri" '.sprintf("%s", (($valArray == $prefixForValue.'amiri' ) ? "selected" : "")).'  style="font-family: \'Amiri\', serif;" >Amiri</option>
		<option value="'.$prefixForValue.'amir_w" '.sprintf("%s", (($valArray == $prefixForValue.'amir_w' ) ? "selected" : "")).'  style="font-family: \'Amiri\', serif, font-weight: 700;" >Amiri Weight</option>
		<option value="'.$prefixForValue.'lateef" '.sprintf("%s", (($valArray == $prefixForValue.'lateef' ) ? "selected" : "")).'  style="font-family: \'Lateef\', cursive;" >Lateef</option>
		<option value="'.$prefixForValue.'scheherazade" '.sprintf("%s", (($valArray == $prefixForValue.'scheherazade' ) ? "selected" : "")).'  style="font-family: \'Scheherazade\', serif;" >Scheherazade</option>
		<option value="'.$prefixForValue.'scheherazad_w" '.sprintf("%s", (($valArray == $prefixForValue.'scheherazad_w' ) ? "selected" : "")).'  style="font-family: \'Scheherazade\', serif, font-weight: 700;" >Scheherazade Weight</option>
		</optgroup>
		
		<optgroup label="Devanagari">
		<option value="'.$prefixForValue.'amiri" '.sprintf("%s", (($valArray == $prefixForValue.'amiri' ) ? "selected" : "")).'  style="font-family: \'Amiri\', serif;" >Amiri</option>
		<option value="'.$prefixForValue.'amir_w" '.sprintf("%s", (($valArray == $prefixForValue.'amir_w' ) ? "selected" : "")).'  style="font-family: \'Amiri\', serif, font-weight: 700;" >Amiri Weight</option>
		<option value="'.$prefixForValue.'arya" '.sprintf("%s", (($valArray == $prefixForValue.'arya' ) ? "selected" : "")).'  style="font-family: \'Arya\', sans-serif;" >Arya</option>
		<option value="'.$prefixForValue.'ary_w" '.sprintf("%s", (($valArray == $prefixForValue.'ary_w' ) ? "selected" : "")).'  style="font-family: \'Arya\', sans-serif, font-weight: 700;" >Arya Weight</option>
		<option value="'.$prefixForValue.'biryani" '.sprintf("%s", (($valArray == $prefixForValue.'biryani' ) ? "selected" : "")).'  style="font-family: \'Biryani\', sans-serif;" >Biryani</option>
		<option value="'.$prefixForValue.'biryan_w" '.sprintf("%s", (($valArray == $prefixForValue.'biryan_w' ) ? "selected" : "")).'  style="font-family: \'Biryani\', sans-serif, font-weight: 700;" >Biryani Weight</option>
		<option value="'.$prefixForValue.'cambay" '.sprintf("%s", (($valArray == $prefixForValue.'cambay' ) ? "selected" : "")).'  style="font-family: \'Cambay\', sans-serif;" >Cambay</option>
		<option value="'.$prefixForValue.'camby_w" '.sprintf("%s", (($valArray == $prefixForValue.'camby_w' ) ? "selected" : "")).'  style="font-family: \'Cambay\', sans-serif, font-weight: 700;" >Cambay Weight</option>
		<option value="'.$prefixForValue.'dekko" '.sprintf("%s", (($valArray == $prefixForValue.'dekko' ) ? "selected" : "")).'  style="font-family: \'Dekko\', cursive;" >Dekko</option>
		<option value="'.$prefixForValue.'ekmukta" '.sprintf("%s", (($valArray == $prefixForValue.'ekmukta' ) ? "selected" : "")).'  style="font-family: \'Ek Mukta\', sans-serif;" >Ek Mukta</option>
		<option value="'.$prefixForValue.'ekmukt_w" '.sprintf("%s", (($valArray == $prefixForValue.'ekmukt_w' ) ? "selected" : "")).'  style="font-family: \'Ek Mukta\', sans-serif, font-weight: 700;" >Ek Mukta Weight</option>
		<option value="'.$prefixForValue.'glegoo" '.sprintf("%s", (($valArray == $prefixForValue.'glegoo' ) ? "selected" : "")).'  style="font-family: \'Glegoo\', serif;" >Glegoo</option>
		<option value="'.$prefixForValue.'gleg_w" '.sprintf("%s", (($valArray == $prefixForValue.'gleg_w' ) ? "selected" : "")).'  style="font-family: \'Glegoo\', serif, font-weight: 700;" >Glegoo Weight</option>
		<option value="'.$prefixForValue.'halant" '.sprintf("%s", (($valArray == $prefixForValue.'halant' ) ? "selected" : "")).'  style="font-family: \'Halant\', serif;" >Halant</option>
		<option value="'.$prefixForValue.'halnt_w" '.sprintf("%s", (($valArray == $prefixForValue.'halnt_w' ) ? "selected" : "")).'  style="font-family: \'Halant\', serif, font-weight: 700;" >Halant Weight</option>
		<option value="'.$prefixForValue.'hind" '.sprintf("%s", (($valArray == $prefixForValue.'hind' ) ? "selected" : "")).'  style="font-family: \'Hind\', sans-serif;" >Hind</option>
		<option value="'.$prefixForValue.'hnd_w" '.sprintf("%s", (($valArray == $prefixForValue.'hnd_w' ) ? "selected" : "")).'  style="font-family: \'Hind\', sans-serif, font-weight: 700;" >Hind Weight</option>
		<option value="'.$prefixForValue.'inknut_antiqua" '.sprintf("%s", (($valArray == $prefixForValue.'inknut_antiqua' ) ? "selected" : "")).'  style="font-family: \'Inknut Antiqua\', serif;" >Inknut Antiqua</option>
		<option value="'.$prefixForValue.'inknut_antiq_w" '.sprintf("%s", (($valArray == $prefixForValue.'inknut_antiq_w' ) ? "selected" : "")).'  style="font-family: \'Inknut Antiqua\', serif, font-weight: 700;" >Inknut Antiqua Weight</option>
		<option value="'.$prefixForValue.'karma" '.sprintf("%s", (($valArray == $prefixForValue.'karma' ) ? "selected" : "")).'  style="font-family: \'Karma\', serif;" >Karma</option>
		<option value="'.$prefixForValue.'karm_w" '.sprintf("%s", (($valArray == $prefixForValue.'karm_w' ) ? "selected" : "")).'  style="font-family: \'Karma\', serif, font-weight: 700;" >Karma Weight</option>
		<option value="'.$prefixForValue.'khand" '.sprintf("%s", (($valArray == $prefixForValue.'khand' ) ? "selected" : "")).'  style="font-family: \'Khand\', sans-serif;" >Khand</option>
		<option value="'.$prefixForValue.'khnd_w" '.sprintf("%s", (($valArray == $prefixForValue.'khnd_w' ) ? "selected" : "")).'  style="font-family: \'Khand\', sans-serif, font-weight: 700;" >Khand Weight</option>
		<option value="'.$prefixForValue.'khula" '.sprintf("%s", (($valArray == $prefixForValue.'khula' ) ? "selected" : "")).'  style="font-family: \'Khula\', sans-serif;" >Khula</option>
		<option value="'.$prefixForValue.'khul_w" '.sprintf("%s", (($valArray == $prefixForValue.'khul_w' ) ? "selected" : "")).'  style="font-family: \'Khula\', sans-serif, font-weight: 700;" >Khula Weight</option>
		<option value="'.$prefixForValue.'kurale" '.sprintf("%s", (($valArray == $prefixForValue.'kurale' ) ? "selected" : "")).'  style="font-family: \'Kurale\', serif;" >Kurale</option>
		<option value="'.$prefixForValue.'laila" '.sprintf("%s", (($valArray == $prefixForValue.'laila' ) ? "selected" : "")).'  style="font-family: \'Laila\', serif;" >Laila</option>
		<option value="'.$prefixForValue.'lail_w" '.sprintf("%s", (($valArray == $prefixForValue.'lail_w' ) ? "selected" : "")).'  style="font-family: \'Laila\', serif, font-weight: 700;" >Laila Weight</option>
		<option value="'.$prefixForValue.'lateef" '.sprintf("%s", (($valArray == $prefixForValue.'lateef' ) ? "selected" : "")).'  style="font-family: \'Lateef\', cursive;" >Lateef</option>
		<option value="'.$prefixForValue.'martel" '.sprintf("%s", (($valArray == $prefixForValue.'martel' ) ? "selected" : "")).'  style="font-family: \'Martel\', serif;" >Martel</option>
		<option value="'.$prefixForValue.'martl_w" '.sprintf("%s", (($valArray == $prefixForValue.'martl_w' ) ? "selected" : "")).'  style="font-family: \'Martel\', serif, font-weight: 700;" >Martel Weight</option>
		<option value="'.$prefixForValue.'noto_sans" '.sprintf("%s", (($valArray == $prefixForValue.'noto_sans' ) ? "selected" : "")).'  style="font-family: \'Noto Sans\', sans-serif;" >Noto Sans</option>
		<option value="'.$prefixForValue.'noto_sns_w" '.sprintf("%s", (($valArray == $prefixForValue.'noto_sns_w' ) ? "selected" : "")).'  style="font-family: \'Noto Sans\', sans-serif, font-weight: 700;" >Noto Sans Weight</option>
		<option value="'.$prefixForValue.'palanquin_dark" '.sprintf("%s", (($valArray == $prefixForValue.'palanquin_dark' ) ? "selected" : "")).'  style="font-family: \'Palanquin Dark\', sans-serif;" >Palanquin Dark</option>
		<option value="'.$prefixForValue.'palanquin_drk_w" '.sprintf("%s", (($valArray == $prefixForValue.'palanquin_drk_w' ) ? "selected" : "")).'  style="font-family: \'Palanquin Dark\', sans-serif, font-weight: 700;" >Palanquin Dark Weight</option>
		<option value="'.$prefixForValue.'rajdhani" '.sprintf("%s", (($valArray == $prefixForValue.'rajdhani' ) ? "selected" : "")).'  style="font-family: \'Rajdhani\', sans-serif;" >Rajdhani</option>
		<option value="'.$prefixForValue.'rajdhan_w" '.sprintf("%s", (($valArray == $prefixForValue.'rajdhan_w' ) ? "selected" : "")).'  style="font-family: \'Rajdhani\', sans-serif, font-weight: 700;" >Rajdhani Weight</option>
		<option value="'.$prefixForValue.'rozha_one" '.sprintf("%s", (($valArray == $prefixForValue.'rozha_one' ) ? "selected" : "")).'  style="font-family: \'Rozha One\', serif;" >Rozha One</option>
		<option value="'.$prefixForValue.'sahitya" '.sprintf("%s", (($valArray == $prefixForValue.'sahitya' ) ? "selected" : "")).'  style="font-family: \'Sahitya\', serif;" >Sahitya</option>
		<option value="'.$prefixForValue.'sahit_w" '.sprintf("%s", (($valArray == $prefixForValue.'sahit_w' ) ? "selected" : "")).'  style="font-family: \'Sahitya\', serif, font-weight: 700;" >Sahitya Weight</option>
		<option value="'.$prefixForValue.'sarpanch" '.sprintf("%s", (($valArray == $prefixForValue.'sarpanch' ) ? "selected" : "")).'  style="font-family: \'Sarpanch\', sans-serif;" >Sarpanch</option>
		<option value="'.$prefixForValue.'sarpnch_w" '.sprintf("%s", (($valArray == $prefixForValue.'sarpnch_w' ) ? "selected" : "")).'  style="font-family: \'Sarpanch\', sans-serif, font-weight: 700;" >Sarpanch Weight</option>
		<option value="'.$prefixForValue.'scheherazade" '.sprintf("%s", (($valArray == $prefixForValue.'scheherazade' ) ? "selected" : "")).'  style="font-family: \'Scheherazade\', serif;" >Scheherazade</option>
		<option value="'.$prefixForValue.'scheherazad_w" '.sprintf("%s", (($valArray == $prefixForValue.'scheherazad_w' ) ? "selected" : "")).'  style="font-family: \'Scheherazade\', serif, font-weight: 700;" >Scheherazade Weight</option>
		<option value="'.$prefixForValue.'tillana" '.sprintf("%s", (($valArray == $prefixForValue.'tillana' ) ? "selected" : "")).'  style="font-family: \'Tillana\', cursive;" >Tillana</option>
		<option value="'.$prefixForValue.'tillan_w" '.sprintf("%s", (($valArray == $prefixForValue.'tillan_w' ) ? "selected" : "")).'  style="font-family: \'Tillana\', cursive, font-weight: 700;" >Tillana Weight</option>
		<option value="'.$prefixForValue.'vesper_libre" '.sprintf("%s", (($valArray == $prefixForValue.'vesper_libre' ) ? "selected" : "")).'  style="font-family: \'Vesper Libre\', serif;" >Vesper Libre</option>
		<option value="'.$prefixForValue.'vesper_libr_w" '.sprintf("%s", (($valArray == $prefixForValue.'vesper_libr_w' ) ? "selected" : "")).'  style="font-family: \'Vesper Libre\', serif, font-weight: 700;" >Vesper Libre Weight</option>
		<option value="'.$prefixForValue.'yantramanav" '.sprintf("%s", (($valArray == $prefixForValue.'yantramanav' ) ? "selected" : "")).'  style="font-family: \'Yantramanav\', sans-serif;" >Yantramanav</option>
		<option value="'.$prefixForValue.'yantramanv_w" '.sprintf("%s", (($valArray == $prefixForValue.'yantramanv_w' ) ? "selected" : "")).'  style="font-family: \'Yantramanav\', sans-serif, font-weight: 700;" >Yantramanav Weight</option>
		</optgroup>
		
		<optgroup label="Greek">
		<option value="'.$prefixForValue.'advent_pro" '.sprintf("%s", (($valArray == $prefixForValue.'advent_pro' ) ? "selected" : "")).'  style="font-family: \'Advent Pro\', sans-serif;" >Advent Pro</option>
		<option value="'.$prefixForValue.'advent_pr_w" '.sprintf("%s", (($valArray == $prefixForValue.'advent_pr_w' ) ? "selected" : "")).'  style="font-family: \'Advent Pro\', sans-serif, font-weight: 700;" >Advent Pro Weight</option>
		<option value="'.$prefixForValue.'arimo" '.sprintf("%s", (($valArray == $prefixForValue.'arimo' ) ? "selected" : "")).'  style="font-family: \'Arimo\', sans-serif;" >Arimo</option>
		<option value="'.$prefixForValue.'armo_w" '.sprintf("%s", (($valArray == $prefixForValue.'armo_w' ) ? "selected" : "")).'  style="font-family: \'Arimo\', sans-serif, font-weight: 700;;" >Arimo Weight</option>
		<option value="'.$prefixForValue.'cardo" '.sprintf("%s", (($valArray == $prefixForValue.'cardo' ) ? "selected" : "")).'  style="font-family: \'Cardo\', serif;" >Cardo</option>
		<option value="'.$prefixForValue.'card_w" '.sprintf("%s", (($valArray == $prefixForValue.'card_w' ) ? "selected" : "")).'  style="font-family: \'Cardo\', font-weight: 700;" >Cardo Weight</option>
		<option value="'.$prefixForValue.'caudex" '.sprintf("%s", (($valArray == $prefixForValue.'caudex' ) ? "selected" : "")).'  style="font-family: \'Caudex\', serif;" >Caudex</option>
		<option value="'.$prefixForValue.'caudx_w" '.sprintf("%s", (($valArray == $prefixForValue.'caudx_w' ) ? "selected" : "")).'  style="font-family: \'Caudex\', serif, font-weight: 700;" >Caudex Weight</option>
		<option value="'.$prefixForValue.'comfortaa" '.sprintf("%s", (($valArray == $prefixForValue.'comfortaa' ) ? "selected" : "")).'  style="font-family: \'Comfortaa\', cursive;" >Comfortaa</option>
		<option value="'.$prefixForValue.'comfort_w" '.sprintf("%s", (($valArray == $prefixForValue.'comfort_w' ) ? "selected" : "")).'  style="font-family: \'Comfortaa\', cursive, font-weight: 700;" >Comfortaa Weight</option>
		<option value="'.$prefixForValue.'didact_gothic" '.sprintf("%s", (($valArray == $prefixForValue.'didact_gothic' ) ? "selected" : "")).'  style="font-family: \'Didact Gothic\', sans-serif;" >Didact Gothic</option>
		<option value="'.$prefixForValue.'didact_gothc_w" '.sprintf("%s", (($valArray == $prefixForValue.'didact_gothc_w' ) ? "selected" : "")).'  style="font-family: \'Didact Gothic\', sans-serif, font-weight: 700;" >Didact Gothic Weight</option>
		<option value="'.$prefixForValue.'fira_mono" '.sprintf("%s", (($valArray == $prefixForValue.'fira_mono' ) ? "selected" : "")).'  style="font-family: \'Fira Mono\', ;" >Fira Mono</option>
		<option value="'.$prefixForValue.'fira_mon_w" '.sprintf("%s", (($valArray == $prefixForValue.'fira_mon_w' ) ? "selected" : "")).'  style="font-family: \'Fira Mono\', font-weight: 700;" >Fira Mono Weight</option>
		<option value="'.$prefixForValue.'fira_sans" '.sprintf("%s", (($valArray == $prefixForValue.'fira_sans' ) ? "selected" : "")).'  style="font-family: \'Fira Sans\', sans-serif;" >Fira Sans</option>
		<option value="'.$prefixForValue.'fira_sns_w" '.sprintf("%s", (($valArray == $prefixForValue.'fira_sns_w' ) ? "selected" : "")).'  style="font-family: \'Fira Sans\', sans-serif, font-weight: 700;" >Fira Sans Weight</option>
		<option value="'.$prefixForValue.'nova_mono" '.sprintf("%s", (($valArray == $prefixForValue.'nova_mono' ) ? "selected" : "")).'  style="font-family: \'Nova Mono\', ;" >Nova Mono</option>
		<option value="'.$prefixForValue.'open_sans" '.sprintf("%s", (($valArray == $prefixForValue.'open_sans' ) ? "selected" : "")).'  style="font-family: \'Open Sans\', sans-serif;" >Open Sans</option>
		<option value="'.$prefixForValue.'open_sans_condensed" '.sprintf("%s", (($valArray == $prefixForValue.'open_sans_condensed' ) ? "selected" : "")).'  style="font-family: \'Open Sans Condensed\', sans-serif;" >Open Sans Condensed</option>
		<option value="'.$prefixForValue.'open_sans_condensd_w" '.sprintf("%s", (($valArray == $prefixForValue.'open_sans_condensd_w' ) ? "selected" : "")).'  style="font-family: \'Open Sans Condensed\', sans-serif, font-weight: 700;" >Open Sans Condensed Weight</option>
		<option value="'.$prefixForValue.'open_sns_w" '.sprintf("%s", (($valArray == $prefixForValue.'open_sns_w' ) ? "selected" : "")).'  style="font-family: \'Open Sans\', sans-serif, font-weight: 700;" >Open Sans Weight</option>
		<option value="'.$prefixForValue.'ply" '.sprintf("%s", (($valArray == $prefixForValue.'ply' ) ? "selected" : "")).'  style="font-family: \'Play\', sans-serif;" >Play</option>
		<option value="'.$prefixForValue.'pl_y_w" '.sprintf("%s", (($valArray == $prefixForValue.'pl_y_w' ) ? "selected" : "")).'  style="font-family: \'Play\', sans-serif, font-weight: 700;" >Play Weight</option>
		<option value="'.$prefixForValue.'roboto" '.sprintf("%s", (($valArray == $prefixForValue.'roboto' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif;" >Roboto</option>
		<option value="'.$prefixForValue.'roboto_condensed" '.sprintf("%s", (($valArray == $prefixForValue.'roboto_condensed' ) ? "selected" : "")).'  style="font-family: \'Roboto Condensed\', sans-serif;" >Roboto Condensed</option>
		<option value="'.$prefixForValue.'roboto_condensd_w" '.sprintf("%s", (($valArray == $prefixForValue.'roboto_condensd_w' ) ? "selected" : "")).'  style="font-family: \'Roboto Condensed\', sans-serif, font-weight: 700;" >Roboto Condensed Weight</option>
		<option value="'.$prefixForValue.'robot_w" '.sprintf("%s", (($valArray == $prefixForValue.'robot_w' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif, font-weight: 700;" >Roboto Weight</option>
		<option value="'.$prefixForValue.'robt_ww" '.sprintf("%s", (($valArray == $prefixForValue.'robt_ww' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif, font-weight: 900;" >Roboto Weight+</option>
		<option value="'.$prefixForValue.'tinos" '.sprintf("%s", (($valArray == $prefixForValue.'tinos' ) ? "selected" : "")).'  style="font-family: \'Tinos\', serif;" >Tinos</option>
		<option value="'.$prefixForValue.'tins_w" '.sprintf("%s", (($valArray == $prefixForValue.'tins_w' ) ? "selected" : "")).'  style="font-family: \'Tinos\', serif, font-weight: 700;" >Tinos Weight</option>
		<option value="'.$prefixForValue.'ubuntu" '.sprintf("%s", (($valArray == $prefixForValue.'ubuntu' ) ? "selected" : "")).'  style="font-family: \'Ubuntu\', sans-serif;" >Ubuntu</option>
		<option value="'.$prefixForValue.'ubunt_mono" '.sprintf("%s", (($valArray == $prefixForValue.'ubunt_mono' ) ? "selected" : "")).'  style="font-family: \'Ubuntu Mono\', ;" >Ubuntu Mono</option>
		<option value="'.$prefixForValue.'ubunt_mon_w" '.sprintf("%s", (($valArray == $prefixForValue.'ubunt_mon_w' ) ? "selected" : "")).'  style="font-family: \'Ubuntu Mono\', font-weight: 700;" >Ubuntu Mono Weight</option>
		<option value="'.$prefixForValue.'ubunt_w" '.sprintf("%s", (($valArray == $prefixForValue.'ubunt_w' ) ? "selected" : "")).'  style="font-family: \'Ubuntu\', sans-serif, font-weight: 700;" >Ubuntu Weight</option>
		</optgroup>
		
		<optgroup label="Hebrew">
		<option value="'.$prefixForValue.'alef" '.sprintf("%s", (($valArray == $prefixForValue.'alef' ) ? "selected" : "")).'  style="font-family: \'Alef\', sans-serif;" >Alef</option>
		<option value="'.$prefixForValue.'alf_w" '.sprintf("%s", (($valArray == $prefixForValue.'alf_w' ) ? "selected" : "")).'  style="font-family: \'Alef\', sans-serif, font-weight: 700;" >Alef Weight</option>
		<option value="'.$prefixForValue.'arimo" '.sprintf("%s", (($valArray == $prefixForValue.'arimo' ) ? "selected" : "")).'  style="font-family: \'Arimo\', sans-serif;" >Arimo</option>
		<option value="'.$prefixForValue.'armo_w" '.sprintf("%s", (($valArray == $prefixForValue.'armo_w' ) ? "selected" : "")).'  style="font-family: \'Arimo\', sans-serif, font-weight: 700;;" >Arimo Weight</option>
		<option value="'.$prefixForValue.'cousine" '.sprintf("%s", (($valArray == $prefixForValue.'cousine' ) ? "selected" : "")).'  style="font-family: \'Cousine\', ;" >Cousine</option>
		<option value="'.$prefixForValue.'cousin_w" '.sprintf("%s", (($valArray == $prefixForValue.'cousin_w' ) ? "selected" : "")).'  style="font-family: \'Cousine\', font-weight: 700;" >Cousine Weight</option>
		<option value="'.$prefixForValue.'tinos" '.sprintf("%s", (($valArray == $prefixForValue.'tinos' ) ? "selected" : "")).'  style="font-family: \'Tinos\', serif;" >Tinos</option>
		<option value="'.$prefixForValue.'tins_w" '.sprintf("%s", (($valArray == $prefixForValue.'tins_w' ) ? "selected" : "")).'  style="font-family: \'Tinos\', serif, font-weight: 700;" >Tinos Weight</option>
		</optgroup>
		
		<optgroup label="Khmer">
		<option value="'.$prefixForValue.'angkor" '.sprintf("%s", (($valArray == $prefixForValue.'angkor' ) ? "selected" : "")).'  style="font-family: \'Angkor\', cursive;" >Angkor</option>
		<option value="'.$prefixForValue.'battambang" '.sprintf("%s", (($valArray == $prefixForValue.'battambang' ) ? "selected" : "")).'  style="font-family: \'Battambang\', cursive;" >Battambang</option>
		<option value="'.$prefixForValue.'battambng_w" '.sprintf("%s", (($valArray == $prefixForValue.'battambng_w' ) ? "selected" : "")).'  style="font-family: \'Battambang\', cursive, font-weight: 700;" >Battambang Weight</option>
		<option value="'.$prefixForValue.'bokor" '.sprintf("%s", (($valArray == $prefixForValue.'bokor' ) ? "selected" : "")).'  style="font-family: \'Bokor\', cursive;" >Bokor</option>
		<option value="'.$prefixForValue.'dangrek" '.sprintf("%s", (($valArray == $prefixForValue.'dangrek' ) ? "selected" : "")).'  style="font-family: \'Dangrek\', cursive;" >Dangrek</option>
		<option value="'.$prefixForValue.'freehand" '.sprintf("%s", (($valArray == $prefixForValue.'freehand' ) ? "selected" : "")).'  style="font-family: \'Freehand\', cursive;" >Freehand</option>
		<option value="'.$prefixForValue.'nokora" '.sprintf("%s", (($valArray == $prefixForValue.'nokora' ) ? "selected" : "")).'  style="font-family: \'Nokora\', serif;" >Nokora</option>
		<option value="'.$prefixForValue.'nokor_w" '.sprintf("%s", (($valArray == $prefixForValue.'nokor_w' ) ? "selected" : "")).'  style="font-family: \'Nokora\', serif, font-weight: 700;" >Nokora Weight</option>
		<option value="'.$prefixForValue.'siemreap" '.sprintf("%s", (($valArray == $prefixForValue.'siemreap' ) ? "selected" : "")).'  style="font-family: \'Siemreap\', cursive;" >Siemreap</option>
		</optgroup>
		
		<optgroup label="Tamil">
		<option value="'.$prefixForValue.'catamaran" '.sprintf("%s", (($valArray == $prefixForValue.'catamaran' ) ? "selected" : "")).'  style="font-family: \'Catamaran\', sans-serif;" >Catamaran</option>
		<option value="'.$prefixForValue.'catamarn_w" '.sprintf("%s", (($valArray == $prefixForValue.'catamarn_w' ) ? "selected" : "")).'  style="font-family: \'Catamaran\', sans-serif, font-weight: 700;" >Catamaran Weight</option>
		</optgroup>
		
		<optgroup label="Telugu">
		<option value="'.$prefixForValue.'mallanna" '.sprintf("%s", (($valArray == $prefixForValue.'mallanna' ) ? "selected" : "")).'  style="font-family: \'Mallanna\', sans-serif;" >Mallanna</option>
		<option value="'.$prefixForValue.'ramabhadra" '.sprintf("%s", (($valArray == $prefixForValue.'ramabhadra' ) ? "selected" : "")).'  style="font-family: \'Ramabhadra\', sans-serif;" >Ramabhadra</option>
		<option value="'.$prefixForValue.'ramaraja" '.sprintf("%s", (($valArray == $prefixForValue.'ramaraja' ) ? "selected" : "")).'  style="font-family: \'Ramaraja\', serif;" >Ramaraja</option>
		<option value="'.$prefixForValue.'timmana" '.sprintf("%s", (($valArray == $prefixForValue.'timmana' ) ? "selected" : "")).'  style="font-family: \'Timmana\', sans-serif;" >Timmana</option>
		</optgroup>
		
		<optgroup label="Thai">
		<option value="'.$prefixForValue.'chonburi" '.sprintf("%s", (($valArray == $prefixForValue.'chonburi' ) ? "selected" : "")).'  style="font-family: \'Chonburi\', cursive;" >Chonburi</option>
		<option value="'.$prefixForValue.'itim" '.sprintf("%s", (($valArray == $prefixForValue.'itim' ) ? "selected" : "")).'  style="font-family: \'Itim\', cursive;" >Itim</option>
		</optgroup>
		
		<optgroup label="Vietnamese">
		<option value="'.$prefixForValue.'arimo" '.sprintf("%s", (($valArray == $prefixForValue.'arimo' ) ? "selected" : "")).'  style="font-family: \'Arimo\', sans-serif;" >Arimo</option>
		<option value="'.$prefixForValue.'armo_w" '.sprintf("%s", (($valArray == $prefixForValue.'armo_w' ) ? "selected" : "")).'  style="font-family: \'Arimo\', sans-serif, font-weight: 700;;" >Arimo Weight</option>
		<option value="'.$prefixForValue.'chonburi" '.sprintf("%s", (($valArray == $prefixForValue.'chonburi' ) ? "selected" : "")).'  style="font-family: \'Chonburi\', cursive;" >Chonburi</option>
		<option value="'.$prefixForValue.'eb_garamond" '.sprintf("%s", (($valArray == $prefixForValue.'eb_garamond' ) ? "selected" : "")).'  style="font-family: \'EB Garamond\', serif;" >EB Garamond</option>
		<option value="'.$prefixForValue.'eb_garamnd_w" '.sprintf("%s", (($valArray == $prefixForValue.'eb_garamnd_w' ) ? "selected" : "")).'  style="font-family: \'EB Garamond\', serif, font-weight: 700;" >EB Garamond Weight</option>
		<option value="'.$prefixForValue.'itim" '.sprintf("%s", (($valArray == $prefixForValue.'itim' ) ? "selected" : "")).'  style="font-family: \'Itim\', cursive;" >Itim</option>
		<option value="'.$prefixForValue.'lobster" '.sprintf("%s", (($valArray == $prefixForValue.'lobster' ) ? "selected" : "")).'  style="font-family: \'Lobster Two\', cursive;" >Lobster Two</option>
		<option value="'.$prefixForValue.'lobstr_w" '.sprintf("%s", (($valArray == $prefixForValue.'lobstr_w' ) ? "selected" : "")).'  style="font-family: \'Lobster Two\', cursive, font-weight: 700;" >Lobster Two Weight</option>
		<option value="'.$prefixForValue.'open_sans_condensed" '.sprintf("%s", (($valArray == $prefixForValue.'open_sans_condensed' ) ? "selected" : "")).'  style="font-family: \'Open Sans Condensed\', sans-serif;" >Open Sans Condensed</option>
		<option value="'.$prefixForValue.'open_sans_condensd_w" '.sprintf("%s", (($valArray == $prefixForValue.'open_sans_condensd_w' ) ? "selected" : "")).'  style="font-family: \'Open Sans Condensed\', sans-serif, font-weight: 700;" >Open Sans Condensed Weight</option>
		<option value="'.$prefixForValue.'roboto" '.sprintf("%s", (($valArray == $prefixForValue.'roboto' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif;" >Roboto</option>
		<option value="'.$prefixForValue.'robot_w" '.sprintf("%s", (($valArray == $prefixForValue.'robot_w' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif, font-weight: 700;" >Roboto Weight</option>
		<option value="'.$prefixForValue.'robt_ww" '.sprintf("%s", (($valArray == $prefixForValue.'robt_ww' ) ? "selected" : "")).'  style="font-family: \'Roboto\', sans-serif, font-weight: 900;" >Roboto Weight+</option>
		<option value="'.$prefixForValue.'source_sans_pro" '.sprintf("%s", (($valArray == $prefixForValue.'source_sans_pro' ) ? "selected" : "")).'  style="font-family: \'Source Sans Pro\', sans-serif;" >Source Sans Pro</option>
		<option value="'.$prefixForValue.'source_sans_pr_w" '.sprintf("%s", (($valArray == $prefixForValue.'source_sans_pr_w' ) ? "selected" : "")).'  style="font-family: \'Source Sans Pro\', sans-serif, font-weight: 700;" >Source Sans Pro Weight</option>
		<option value="'.$prefixForValue.'tinos" '.sprintf("%s", (($valArray == $prefixForValue.'tinos' ) ? "selected" : "")).'  style="font-family: \'Tinos\', serif;" >Tinos</option>
		<option value="'.$prefixForValue.'tins_w" '.sprintf("%s", (($valArray == $prefixForValue.'tins_w' ) ? "selected" : "")).'  style="font-family: \'Tinos\', serif, font-weight: 700;" >Tinos Weight</option>
		</optgroup>
		</select>
		</div>
		';
		return $ret;
	}
	
	function generateSelect($nameTool, $nameSelect, $title, $optionArray, $valueArray, $classArray, $id, $disableDefault){
		$id = $this->prepareIdName($id);
		$ret = '';
		$jsFunc = str_replace('[', '',$nameTool);
		$jsFunc = str_replace(']', '',$jsFunc);		
		$jsFunc = str_replace('moreShareWindow','',$jsFunc);
		$jsFunc = str_replace('buttonBlock','',$jsFunc);
		if($optionArray){
			$ret = '
				<div id="'.$id.'_div">
				<p>'.$title.'</p>
				<select name="'.$nameSelect.'" id="'.$id.'" onchange="'.$jsFunc.'Preview()">';
				if((int)$disableDefault == 0){
					$ret.='<option value="" selected></option>';
				}				
				foreach((array)$optionArray as $k => $v){
					$ret .= '<option value="'.$k.'" class="'.$classArray[$k].'" '.sprintf("%s", (($valueArray == $k ) ? "selected" : "")).' >'.$v.'</option>';
				}
			$ret .= '
				</select>
				</div>
			';
		}
		return $ret;
	}
	
	function getOpenNewTabBlock($name, $arrayValue){
		$ret = '			
			<label style="width: 100%; background: #E0E5E8; padding-top: 20px; padding-bottom: 5px; text-align: left; margin: 20px 0 0; padding-left: 20px;"><p>'.$this->_dictionary[settingBlock][promote_link_new_tab].'</p>
			<input class="pq_switch" type="checkbox" id="'.$name.'_open_link_new_tab" name="'.$name.'[promote_link_new_tab]" '.sprintf("%s", ($arrayValue[promote_link_new_tab] == 'on' || !isset($arrayValue[promote_link_new_tab])) ? "checked" : "").' />
			<label for="'.$name.'_open_link_new_tab" class="pq_switch_label"></label></label>
			';		
		return $ret;
	}
	
	function getLockBlock($name, $arrayValue){
		$ret = '		
		<h2>'.$this->_dictionary[lockBlock][title].' <a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#lock_block" target="_settings_info">?</a></h2>
		<p>'.$this->_dictionary[lockBlock][description].'</p>
		<div class="pq_clear"></div>
		<label class="pq_after_action"><p>'.$this->_dictionary[lockBlock][after_proceed].'</p><input type="text" name="'.$name.'[lockedMechanism][afterProceed]" value="'.(float)$arrayValue[lockedMechanism][afterProceed].'"></label>
		<label class="pq_after_action"><p>'.$this->_dictionary[lockBlock][after_close].'</p><input type="text" name="'.$name.'[lockedMechanism][afterClose]" value="'.(float)$arrayValue[lockedMechanism][afterClose].'"></label>
		';
		return $ret;
	}
	
	function getProviderBlock($name, $arrayValue){
		$id = $this->prepareIdName($name);
		$jsFunc = str_replace('[', '',$name);
		$jsFunc = str_replace(']', '',$jsFunc);			
		$ret = '		
		<h2>'.$this->_dictionary[selectProviderBlock][title].' <a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#provider_block" target="_settings_info">?</a></h2>
		<p>'.$this->_dictionary[selectProviderBlock][description].'</p>
		<div class="pq_clear"></div>
		<label class="pq_rule pq_provider" onclick="selectSubscribeProvider(\''.$name.'\', \'mailchimp\');checkNameFieldsByProvider(\''.$name.'\');">
			<input type="radio" id="'.$id.'_provider_mailchimp" name="'.$name.'[provider]" '.sprintf("%s", (($arrayValue[provider] == "mailchimp") ? "checked" : "")).' value="mailchimp">
			<div>
				<img src="'.plugins_url("i/provider_mailchimp.png", __FILE__).'" />
				<img src="'.plugins_url("i/select.png", __FILE__).'" class="pq_active" />
				<p>MailChimp</p>
			</div>
		</label>
		<label class="pq_rule pq_provider" onclick="selectSubscribeProvider(\''.$name.'\', \'getresponse\');checkNameFieldsByProvider(\''.$name.'\');">
			<input type="radio" id="'.$id.'_provider_getresponse" name="'.$name.'[provider]" '.sprintf("%s", (($arrayValue[provider] == "getresponse") ? "checked" : "")).' value="getresponse">
			<div>
				<img src="'.plugins_url("i/provider_getresponse.png", __FILE__).'" />
				<img src="'.plugins_url("i/select.png", __FILE__).'" class="pq_active" />
				<p>GetResponse</p>
			</div>
		</label>
		<label class="pq_rule pq_provider" onclick="selectSubscribeProvider(\''.$name.'\', \'aweber\');checkNameFieldsByProvider(\''.$name.'\');">
			<input type="radio" id="'.$id.'_provider_aweber" name="'.$name.'[provider]" '.sprintf("%s", (($arrayValue[provider] == "aweber") ? "checked" : "")).' value="aweber">
			<div>
				<img src="'.plugins_url("i/provider_aweber.png", __FILE__).'" />
				<img src="'.plugins_url("i/select.png", __FILE__).'" class="pq_active" />
				<p>Aweber</p>
			</div>
		</label>
		<!--label class="pq_rule pq_provider" onclick="selectSubscribeProvider(\''.$name.'\', \'newsletter2go\');checkNameFieldsByProvider(\''.$name.'\');">
			<input type="radio" id="'.$id.'_provider_newsletter2go" name="'.$name.'[provider]" '.sprintf("%s", (($arrayValue[provider] == "newsletter2go") ? "checked" : "")).' value="newsletter2go">
			<div>
				<img src="'.plugins_url("i/provider_newsletter2go.png", __FILE__).'" />
				<img src="'.plugins_url("i/select.png", __FILE__).'" class="pq_active" />
				<p>Newsletter2Go</p>
			</div>
		</label--!>
		
		<label class="pq_rule pq_provider pq_4n" onclick="selectSubscribeProvider(\''.$name.'\', \'madmini\');checkNameFieldsByProvider(\''.$name.'\');">
			<input type="radio" id="'.$id.'_provider_madmini" name="'.$name.'[provider]" '.sprintf("%s", (($arrayValue[provider] == "madmini") ? "checked" : "")).' value="madmini">
			<div>
				<img src="'.plugins_url("i/provider_madmimi.png", __FILE__).'" />
				<img src="'.plugins_url("i/select.png", __FILE__).'" class="pq_active" />
				<p>Mad Mimi</p>
			</div>
		</label>
		<label class="pq_rule pq_provider" onclick="selectSubscribeProvider(\''.$name.'\', \'acampaign\');checkNameFieldsByProvider(\''.$name.'\');">
			<input type="radio" id="'.$id.'_provider_acampaign" name="'.$name.'[provider]" '.sprintf("%s", (($arrayValue[provider] == "acampaign") ? "checked" : "")).' value="acampaign">
			<div>
				<img src="'.plugins_url("i/provider_activecampaign.png", __FILE__).'" />
				<img src="'.plugins_url("i/select.png", __FILE__).'" class="pq_active" />
				<p>ActiveCompaign</p>
			</div>
		</label>
		<label class="pq_rule pq_provider" onclick="selectSubscribeProvider(\''.$name.'\', \'klickmail\');checkNameFieldsByProvider(\''.$name.'\');">
			<input type="radio" id="'.$id.'_provider_klickmail" name="'.$name.'[provider]" '.sprintf("%s", (($arrayValue[provider] == "klickmail") ? "checked" : "")).' value="klickmail">
			<div>
				<img src="'.plugins_url("i/provider_klickmail.png", __FILE__).'" />
				<img src="'.plugins_url("i/select.png", __FILE__).'" class="pq_active" />
				<p>Klick Mail</p>
			</div>
		</label>
		<label class="pq_rule pq_provider" >
			<div>
				<a href="http://profitquery.com/blog/2015/12/add-new-subscribe-provider-for-wordpress/" target="_another_signin_provider">
				<img src="'.plugins_url("i/provider_any.png", __FILE__).'" />
				<p>Another Provider</p>
				</a>
			</div>
		</label>
		<label class="pq_rule pq_provider pq_4n" >
			<div>
				<a href="http://profitquery.com/blog/2015/12/generete-csv-with-subscriptions/" target="_provider_csv">
				<img src="'.plugins_url("i/provider_csv.png", __FILE__).'" />
				<p>Your CSV File</p>
				</a>
			</div>
		</label>
		<div class="pq_clear"></div>
		<div id="'.$name.'_ProviderCodeContainer" style="max-width: 100%;margin: 30px auto 0; font-size: 16px;'.sprintf("%s", (((int)$arrayValue[providerOption][is_error] == 1) ? "display:block;" : "display:none;")).'" '.sprintf("%s", (((int)$arrayValue[providerOption][is_error] == 1) ? "class='pq_error'" : "")).'>
			<h2 style="margin: 0px;" id="'.$name.'_provier_title"></h2>
			<p>'.$this->_dictionary[selectProviderBlock][paste_code].'
			<a id="'.$name.'_provier_help_url" target="_provider_help_url">'.$this->_dictionary[selectProviderBlock][how_get_code].'</a></p>
		<textarea name="'.$name.'[providerForm]" rows="5">'.stripslashes($arrayValue[providerForm]).'</textarea>
		<div style="display:'.sprintf("%s", (((int)$arrayValue[providerOption][is_error] == 1) ? "block" : "none")).';" id="'.$name.'_providerErrorBlock" class="pq_attr"><p>'.$this->_dictionary[selectProviderBlock][error_provider].'</p><a class="pq_close" onclick="document.getElementById(\''.$name.'_providerErrorBlock\').style.display=\'none\';"></a></div>
		</div>
		<script>
			selectSubscribeProvider(\''.$name.'\', \''.$arrayValue[provider].'\');
			checkNameFieldsByProvider(\''.$name.'\');
		</script>
		';		
		return $ret;
	}
	
	
	function getGadgetRules($name, $arrayValue){
		$ret = '
			<label class="pq_page_settings">
				<p>'.$this->_dictionary[gadgetRules][on_mobile].' <a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#gadget_settings" target="_settings_info">?</a></p>
				<input class="pq_switch" type="checkbox" id="'.$name.'_work_on_mobile" name="'.$name.'[displayRules][work_on_mobile]" '.sprintf("%s", ($arrayValue[displayRules][work_on_mobile] == 'on' || !isset($arrayValue[displayRules][work_on_mobile])) ? "checked" : "").' />
				<label for="'.$name.'_work_on_mobile" class="pq_switch_label"></label>
			</label>
		';
		return $ret;
	}
	
	function getPageOptions($name, $arrayValue){
		$ret = '			
			<div class="pq_desable_url">
				<h2>'.$this->_dictionary[pageSettings][url_mask_title].' <a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#display_rules" target="_settings_info">?</a></h2>
				<div class="pq_clear"></div>
				<select name="'.$name.'[displayRules][pageMaskType][0]">
					<option value="enable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][0] == 'enable' || !isset($arrayValue[displayRules][pageMaskType][0])) ? "selected" : "").'>'.$this->_dictionary[pageSettings][show].'</option>
					<option value="disable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][0] == 'disable') ? "selected" : "").'>'.$this->_dictionary[pageSettings][dont_show].'</option>
				</select>
				<input type="text" name="'.$name.'[displayRules][pageMask][0]" value="'.stripslashes($arrayValue[displayRules][pageMask][0]).'">
				
				<select name="'.$name.'[displayRules][pageMaskType][1]">
					<option value="enable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][1] == 'enable' || !isset($arrayValue[displayRules][pageMaskType][1])) ? "selected" : "").'>'.$this->_dictionary[pageSettings][show].'</option>
					<option value="disable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][1] == 'disable') ? "selected" : "").'>'.$this->_dictionary[pageSettings][dont_show].'</option>
				</select>
				<input type="text" name="'.$name.'[displayRules][pageMask][1]" value="'.stripslashes($arrayValue[displayRules][pageMask][1]).'">
				
				<select name="'.$name.'[displayRules][pageMaskType][2]">
					<option value="enable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][2] == 'enable' || !isset($arrayValue[displayRules][pageMaskType][2])) ? "selected" : "").'>'.$this->_dictionary[pageSettings][show].'</option>
					<option value="disable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][2] == 'disable') ? "selected" : "").'>'.$this->_dictionary[pageSettings][dont_show].'</option>
				</select>
				<input type="text" name="'.$name.'[displayRules][pageMask][2]" value="'.stripslashes($arrayValue[displayRules][pageMask][2]).'">
				
				<select name="'.$name.'[displayRules][pageMaskType][3]">
					<option value="enable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][3] == 'enable' || !isset($arrayValue[displayRules][pageMaskType][3])) ? "selected" : "").'>'.$this->_dictionary[pageSettings][show].'</option>
					<option value="disable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][3] == 'disable') ? "selected" : "").'>'.$this->_dictionary[pageSettings][dont_show].'</option>
				</select>
				<input type="text" name="'.$name.'[displayRules][pageMask][3]" value="'.stripslashes($arrayValue[displayRules][pageMask][3]).'">
				
				<select name="'.$name.'[displayRules][pageMaskType][4]">
					<option value="enable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][4] == 'enable' || !isset($arrayValue[displayRules][pageMaskType][4])) ? "selected" : "").'>'.$this->_dictionary[pageSettings][show].'</option>
					<option value="disable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][4] == 'disable') ? "selected" : "").'>'.$this->_dictionary[pageSettings][dont_show].'</option>
				</select>
				<input type="text" name="'.$name.'[displayRules][pageMask][4]" value="'.stripslashes($arrayValue[displayRules][pageMask][4]).'">
				
				<input type="button" id="'.$name.'_displayRules_pageMaskType_Button_More" value="'.$this->_dictionary[pageSettings][more].'" onclick="document.getElementById(\''.$name.'_displayRules_pageMaskType_More\').style.display=\'block\';document.getElementById(\''.$name.'_displayRules_pageMaskType_Button_More\').style.display=\'none\';">
				
				<div id="'.$name.'_displayRules_pageMaskType_More" style="display:none">
					<select name="'.$name.'[displayRules][pageMaskType][5]">
						<option value="enable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][5] == 'enable' || !isset($arrayValue[displayRules][pageMaskType][5])) ? "selected" : "").'>'.$this->_dictionary[pageSettings][show].'</option>
						<option value="disable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][5] == 'disable') ? "selected" : "").'>'.$this->_dictionary[pageSettings][dont_show].'</option>
					</select>
					<input type="text" name="'.$name.'[displayRules][pageMask][5]" value="'.stripslashes($arrayValue[displayRules][pageMask][5]).'">
					
					<select name="'.$name.'[displayRules][pageMaskType][6]">
						<option value="enable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][6] == 'enable' || !isset($arrayValue[displayRules][pageMaskType][6])) ? "selected" : "").'>'.$this->_dictionary[pageSettings][show].'</option>
						<option value="disable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][6] == 'disable') ? "selected" : "").'>'.$this->_dictionary[pageSettings][dont_show].'</option>
					</select>
					<input type="text" name="'.$name.'[displayRules][pageMask][6]" value="'.stripslashes($arrayValue[displayRules][pageMask][6]).'">
					
					<select name="'.$name.'[displayRules][pageMaskType][7]">
						<option value="enable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][7] == 'enable' || !isset($arrayValue[displayRules][pageMaskType][7])) ? "selected" : "").'>'.$this->_dictionary[pageSettings][show].'</option>
						<option value="disable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][7] == 'disable') ? "selected" : "").'>'.$this->_dictionary[pageSettings][dont_show].'</option>
					</select>
					<input type="text" name="'.$name.'[displayRules][pageMask][7]" value="'.stripslashes($arrayValue[displayRules][pageMask][7]).'">
					
					<select name="'.$name.'[displayRules][pageMaskType][8]">
						<option value="enable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][8] == 'enable' || !isset($arrayValue[displayRules][pageMaskType][8])) ? "selected" : "").'>'.$this->_dictionary[pageSettings][show].'</option>
						<option value="disable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][8] == 'disable') ? "selected" : "").'>'.$this->_dictionary[pageSettings][dont_show].'</option>
					</select>
					<input type="text" name="'.$name.'[displayRules][pageMask][8]" value="'.stripslashes($arrayValue[displayRules][pageMask][8]).'">
					
					<select name="'.$name.'[displayRules][pageMaskType][9]">
						<option value="enable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][9] == 'enable' || !isset($arrayValue[displayRules][pageMaskType][9])) ? "selected" : "").'>'.$this->_dictionary[pageSettings][show].'</option>
						<option value="disable" '.sprintf("%s", ($arrayValue[displayRules][pageMaskType][9] == 'disable') ? "selected" : "").'>'.$this->_dictionary[pageSettings][dont_show].'</option>
					</select>
					<input type="text" name="'.$name.'[displayRules][pageMask][9]" value="'.stripslashes($arrayValue[displayRules][pageMask][9]).'">
				</div>
			</div>
			
			
			<div class="pq_clear"></div>
			
			<label class="pq_page_settings">
			<p>'.$this->_dictionary[pageSettings][display_main_page].' <a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#main_page_setting" target="_settings_info">?</a></p>
			<p>&nbsp;'.stripslashes($this->_options[settings][mainPage]).'</p>
			<input class="pq_switch" type="checkbox" id="'.$name.'_displayRules_mainPageWork" name="'.$name.'[displayRules][display_on_main_page]" '.sprintf("%s", ($arrayValue[displayRules][display_on_main_page] == 'on' || !isset($arrayValue[displayRules][display_on_main_page])) ? "checked" : "").' />
			<label for="'.$name.'_displayRules_mainPageWork" class="pq_switch_label"></label>
			</label>
			<p>'.$this->_dictionary[pageSettings][mp_setting_info].'</p>
		';
		return $ret;
	}
	
	function getEventHandlerBlock($name, $structure, $arrayValue){
		$ret = '';		
		$ret = '
		<h2>'.$this->_dictionary[eventHandlerBlock][title].' <a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#event_handler" target="_settings_info">?</a></h2>
		<p>'.$this->_dictionary[eventHandlerBlock][description].'</p>
		';
		foreach((array)$structure as $k => $v){
			$keyStructure[$v] = 1;
		}
		
		$array = array('delay', 'exit', 'scrolling');
		
		if(!$arrayValue[eventHandler][type]) $arrayValue[eventHandler][type] = 'delay';
		
		foreach((array)$array as $k => $v){
			if($v == $arrayValue[eventHandler][type]) $checked = 'checked'; else $checked = '';
			
			if($v == 'delay'){
				
				$ret .= '
					<label class="pq_rule" onclick="setEHBlockActive(\''.$name.'\', \'delay\')">
						<input type="radio" '.sprintf("%s", ((int)$keyStructure[$v] == 1) ? "" : "disabled").' id="'.$name.'_eventHandler_delay" name="'.$name.'[eventHandler][type]" '.$checked.'  value="'.$v.'">
						<div>
							<img src="'.plugins_url("i/display_time.png", __FILE__).'" />
							<p>'.$this->_dictionary[eventHandlerBlock][delay_text].'</p>
							<input type="text" name="'.$name.'[eventHandler][delay_value]" '.sprintf("%s", ((int)$keyStructure[$v] == 1) ? "" : "disabled").'  value="'.(int)$arrayValue[eventHandler][delay_value].'"><span>'.$this->_dictionary[eventHandlerBlock][delay_unit].'</span>
							<img src="'.plugins_url("i/select.png", __FILE__).'" class="pq_active" />
						</div>
					</label>
				';			
			}
			//disabled need
			if($v == 'scrolling'){
				$ret .= '
					<label class="pq_rule" style="margin-right:0!important;" onclick="setEHBlockActive(\''.$name.'\', \'scrolling\')">
						<input type="radio" '.sprintf("%s", ((int)$keyStructure[$v] == 1) ? "" : "disabled").' id="'.$name.'_eventHandler_scrolling" name="'.$name.'[eventHandler][type]" '.$checked.'  value="'.$v.'">
						<div>
							<img src="'.plugins_url("i/display_scroll.png", __FILE__).'" />
							<p>'.$this->_dictionary[eventHandlerBlock][scrolling_text].'</p>
							<input type="text" '.sprintf("%s", ((int)$keyStructure[$v] == 1) ? "" : "disabled").' name="'.$name.'[eventHandler][scrolling_value]" value="'.(int)$arrayValue[eventHandler][scrolling_value].'"><span>%</span>
							<img src="'.plugins_url("i/select.png", __FILE__).'" class="pq_active" />
						</div>
					</label>
				';			
			}
			if($v == 'exit'){
				$ret .= '
					<label class="pq_rule" onclick="setEHBlockActive(\''.$name.'\', \'exit\')">
						<input type="radio" '.sprintf("%s", ((int)$keyStructure[$v] == 1) ? "" : "disabled").' id="'.$name.'_eventHandler_exit" name="'.$name.'[eventHandler][type]" '.$checked.'  value="'.$v.'">
						<div>
							<img src="'.plugins_url("i/display_exit.png", __FILE__).'" />
							<p>'.$this->_dictionary[eventHandlerBlock][exit_text].'</p>
							<img src="'.plugins_url("i/select.png", __FILE__).'" class="pq_active" />
						</div>
					</label>
				';			
			}
		}		
		return $ret;						
	}
	
	function getImageSharingIcons($name, $valArray){
		$ret = '		
		<label class="sm">
			<input type="checkbox" id="'.$name.'_provider_FB" onclick="'.$name.'Preview()"  name="'.$name.'[socnet][FB]" '.sprintf("%s", ($valArray[socnet][FB] == 'on') ? "checked" : "").' />
			<div class="fb pq_checkbox"></div>
			<select name="'.$name.'[socnetOption][FB][type]" class="sm">
				<option value="app" '.sprintf("%s", ($valArray[socnetOption][FB][type] == "app") ? "selected" : "").'>'.$this->_dictionary[imageSharerBlock][fb_app].'</option>
				<option value="" '.sprintf("%s", ($valArray[socnetOption][FB][type] == "") ? "selected" : "").'>'.sprintf($this->_dictionary[imageSharerBlock][default_share], "Facebook").'</option>
				<option value="pq" '.sprintf("%s", ($valArray[socnetOption][FB][type] == "pq") ? "selected" : "").'>'.$this->_dictionary[imageSharerBlock][pq_share].'</option>
			</select>
		</label>
		
		<label class="sm">
			<a class="pq_question" href="http://profitquery.com/blog/faq/2016/01/how-to-create-facebook-app/" target="_blank">?</a>
			<input type="text" name="'.$name.'[socnetOption][FB][app_id]" value="'.sprintf("%s", ($valArray[socnetOption][FB][app_id] != "") ? stripslashes($valArray[socnetOption][FB][app_id]) : "").'" placeholder="FaceBook APP ID"/>
		</label>
		<label class="sm">
			<input type="checkbox" id="'.$name.'_provider_TW" onclick="'.$name.'Preview()" name="'.$name.'[socnet][TW]" '.sprintf("%s", ($valArray[socnet][TW] == 'on') ? "checked" : "").' />
			<div class="tw pq_checkbox"></div>
			<select name="'.$name.'[socnetOption][TW][type]" class="sm">
				<option value="" '.sprintf("%s", ($valArray[socnetOption][TW][type] == "") ? "selected" : "").'>'.sprintf($this->_dictionary[imageSharerBlock][default_share], "Twitter").'</option>
				<option value="pq" '.sprintf("%s", ($valArray[socnetOption][TW][type] == "pq") ? "selected" : "").'>'.$this->_dictionary[imageSharerBlock][pq_share].'</option>
			</select>
		</label>
		<label class="sm">
			<input type="checkbox" id="'.$name.'_provider_GP" onclick="'.$name.'Preview()" name="'.$name.'[socnet][GP]" '.sprintf("%s", ($valArray[socnet][GP] == 'on') ? "checked" : "").'/>
			<div class="gp pq_checkbox" ></div>
			<select class="sm" name="'.$name.'[socnetOption][GP][type]" >
				<option value="" '.sprintf("%s", ($valArray[socnetOption][GP][type] == "") ? "selected" : "").'>'.sprintf($this->_dictionary[imageSharerBlock][default_share], "Google Plus").'</option>
				<option value="pq" '.sprintf("%s", ($valArray[socnetOption][GP][type] == "pq") ? "selected" : "").'>'.$this->_dictionary[imageSharerBlock][pq_share].'</option>
				
			</select>
		</label>
		<label class="sm">
			<input type="checkbox" id="'.$name.'_provider_PI" onclick="'.$name.'Preview()" name="'.$name.'[socnet][PI]" '.sprintf("%s", ($valArray[socnet][PI] == 'on') ? "checked" : "").' />
			<div class="pi pq_checkbox"></div>
			<select class="sm" name="'.$name.'[socnetOption][PI][type]">
				<option value="" '.sprintf("%s", ($valArray[socnetOption][PI][type] == "") ? "selected" : "").'>'.sprintf($this->_dictionary[imageSharerBlock][default_share], "Pinterest").'</option>
				<option value="pq" '.sprintf("%s", ($valArray[socnetOption][PI][type] == "pq") ? "selected" : "").'>'.$this->_dictionary[imageSharerBlock][pq_share].'</option>
				
			</select>
		</label>
		<label class="sm">
			<input type="checkbox" id="'.$name.'_provider_TR" onclick="'.$name.'Preview()" name="'.$name.'[socnet][TR]" '.sprintf("%s", ($valArray[socnet][TR] == 'on') ? "checked" : "").' />
			<div class="tr pq_checkbox"></div>
			<select class="sm" name="'.$name.'[socnetOption][TR][type]" >
				<option value="" '.sprintf("%s", ($valArray[socnetOption][TR][type] == "") ? "selected" : "").'>'.sprintf($this->_dictionary[imageSharerBlock][default_share], "Tumblr").'</option>
				<option value="pq" '.sprintf("%s", ($valArray[socnetOption][TR][type] == "pq") ? "selected" : "").'>'.$this->_dictionary[imageSharerBlock][pq_share].'</option>
			</select>
		</label>
		<label class="sm">
			<input type="checkbox" id="'.$name.'_provider_VK" onclick="'.$name.'Preview()" name="'.$name.'[socnet][VK]" '.sprintf("%s", ($valArray[socnet][VK] == 'on') ? "checked" : "").' />
			<div class="vk pq_checkbox"></div>
			<select class="sm" name="'.$name.'[socnetOption][VK][type]" >
				<option value="" '.sprintf("%s", ($valArray[socnetOption][VK][type] == "") ? "selected" : "").'>'.sprintf($this->_dictionary[imageSharerBlock][default_share], "Vkontakte").'</option>
				<option value="pq" '.sprintf("%s", ($valArray[socnetOption][VK][type] == "pq") ? "selected" : "").'>'.$this->_dictionary[imageSharerBlock][pq_share].'</option>
			</select>
		</label>
		<label class="sm">
			<input type="checkbox" id="'.$name.'_provider_OD" onclick="'.$name.'Preview()" name="'.$name.'[socnet][OD]" '.sprintf("%s", ($valArray[socnet][OD] == 'on') ? "checked" : "").' />
			<div class="od pq_checkbox"></div>
			<select class="sm" name="'.$name.'[socnetOption][OD][type]" >
				<option value="" '.sprintf("%s", ($valArray[socnetOption][OD][type] == "") ? "selected" : "").'>'.sprintf($this->_dictionary[imageSharerBlock][default_share], "Odnoklassniki").'</option>
				<option value="pq" '.sprintf("%s", ($valArray[socnetOption][OD][type] == "pq") ? "selected" : "").'>'.$this->_dictionary[imageSharerBlock][pq_share].'</option>
			</select>
		</label>
		<label class="sm">
			<input type="checkbox" id="'.$name.'_provider_WU" onclick="'.$name.'Preview()" name="'.$name.'[socnet][WU]" '.sprintf("%s", ($valArray[socnet][WU] == 'on') ? "checked" : "").' />
			<div class="wu pq_checkbox"></div>
			<select class="sm" disabled="disabled"></select>
		</label>
		<label class="sm">
			<input type="checkbox" id="'.$name.'_provider_Mail" onclick="'.$name.'Preview()" name="'.$name.'[socnet][Mail]" '.sprintf("%s", ($valArray[socnet][Mail] == 'on') ? "checked" : "").' />
			<div class="em pq_checkbox"></div>
			<select class="sm" disabled="disabled"></select>
		</label>';
		
		return $ret;
	}
	
	function getSharingIcons($name, $valArray, $additionalArrays, $disableMail = 0, $disableMore = 0){
		$ret = '';
		$sharingCnt = 10;
		$jsFunc = str_replace('[', '',$name);
		$jsFunc = str_replace(']', '',$jsFunc);
		$id = $this->prepareIdName($name).'_sharing_icon';
		$id = str_replace('__', '_', $id);
		
		for($i=0; $i<$sharingCnt; $i++)
		{
			$error_classname = '';
			if(isset($additionalArrays[socnet_with_pos_error][error_socnet])){
				if((int)$additionalArrays[socnet_with_pos_error][error_socnet][$valArray[$i]] == 1){
					$error_classname = 'pq_error';
				}
			}
			$ret .= '
			<select onchange="'.$jsFunc.'Preview()" id="'.$id.'_'.$i.'" name="'.$name.'[socnet_with_pos]['.$i.']"  class="'.$error_classname.'">
				<option value="" selected></option>
				<option value="FB" '.sprintf("%s", (($valArray[$i] == "FB") ? "selected" : "")).' >Facebook</option>
				<option value="TW" '.sprintf("%s", (($valArray[$i] == "TW") ? "selected" : "")).'>Twitter</option>
				<option value="GP" '.sprintf("%s", (($valArray[$i] == "GP") ? "selected" : "")).'>Google plus</option>
				<option value="RD" '.sprintf("%s", (($valArray[$i] == "RD") ? "selected" : "")).'>Reddit</option>
				<option value="PI" '.sprintf("%s", (($valArray[$i] == "PI") ? "selected" : "")).'>Pinterest</option>
				<option value="VK" '.sprintf("%s", (($valArray[$i] == "VK") ? "selected" : "")).'>Vkontakte</option>
				<option value="OD" '.sprintf("%s", (($valArray[$i] == "OD") ? "selected" : "")).'>Odnoklassniki</option>
				<option value="LJ" '.sprintf("%s", (($valArray[$i] == "LJ") ? "selected" : "")).'>Live Journal</option>
				<option value="TR" '.sprintf("%s", (($valArray[$i] == "TR") ? "selected" : "")).'>Tumblr</option>
				<option value="LI" '.sprintf("%s", (($valArray[$i] == "LI") ? "selected" : "")).'>LinkedIn</option>
				<option value="SU" '.sprintf("%s", (($valArray[$i] == "SU") ? "selected" : "")).'>StumbleUpon</option>
				<option value="DG" '.sprintf("%s", (($valArray[$i] == "DG") ? "selected" : "")).'>Digg</option>
				<option value="DL" '.sprintf("%s", (($valArray[$i] == "DL") ? "selected" : "")).'>Delicious</option>
				<option value="WU" '.sprintf("%s", (($valArray[$i] == "WU") ? "selected" : "")).'>WhatsApp</option>
				<option value="BR" '.sprintf("%s", (($valArray[$i] == "BR") ? "selected" : "")).'>Blogger</option>
				<option value="RR" '.sprintf("%s", (($valArray[$i] == "RR") ? "selected" : "")).'>Renren</option>
				<option value="WB" '.sprintf("%s", (($valArray[$i] == "WB") ? "selected" : "")).'>Weibo</option>
				<option value="MW" '.sprintf("%s", (($valArray[$i] == "MW") ? "selected" : "")).'>My World</option>
				<option value="EN" '.sprintf("%s", (($valArray[$i] == "EN") ? "selected" : "")).'>Evernote</option>
				<option value="PO" '.sprintf("%s", (($valArray[$i] == "PO") ? "selected" : "")).'>Pocket</option>
				<option value="AK" '.sprintf("%s", (($valArray[$i] == "AK") ? "selected" : "")).'>Kindle</option>
				<option value="FL" '.sprintf("%s", (($valArray[$i] == "FL") ? "selected" : "")).'>Flipboard</option>
				<option value="Print" '.sprintf("%s", (($valArray[$i] == "Print") ? "selected" : "")).'>Print</option>';				
			if((int)$disableMail == 0){
				$ret .='<option value="Mail" '.sprintf("%s", (($valArray[$i] == "Mail") ? "selected" : "")).'>Email</option>';
			}
			if((int)$disableMore == 0){
				$ret .='<option value="More" '.sprintf("%s", (($valArray[$i] == "More") ? "selected" : "")).'>More</option>';
			}
			$ret .='
			</select>
			<br>
			';
		}
		return $ret;
	}
	

	function getFollowIcons($name, $valueArray)
	{
		$jsFunc = str_replace('[', '',$name);
		$jsFunc = str_replace(']', '',$jsFunc);
		$id = $this->prepareIdName($name).'_follow_icon';
		$id = str_replace('__', '_', $id);
		$ret = '';
		$ret = '
			<p>facebook.com</p>
				<input type="text" onkeyup="'.$jsFunc.'Preview()" id="'.$id.'_FB" name="'.$name.'[socnetIconsBlock][FB]" value="'.stripslashes($valueArray[socnetIconsBlock][FB]).'">
			<br>
			<p>twitter.com</p>
				<input type="text" onkeyup="'.$jsFunc.'Preview()" id="'.$id.'_TW"  name="'.$name.'[socnetIconsBlock][TW]" value="'.stripslashes($valueArray[socnetIconsBlock][TW]).'">
			<br>
			<p>plus.google.com</p>
				<input type="text" onkeyup="'.$jsFunc.'Preview()" id="'.$id.'_GP" name="'.$name.'[socnetIconsBlock][GP]" value="'.stripslashes($valueArray[socnetIconsBlock][GP]).'">
			<br>
			<p>pinterest.com</p>
				<input type="text" onkeyup="'.$jsFunc.'Preview()" id="'.$id.'_PI" name="'.$name.'[socnetIconsBlock][PI]" value="'.stripslashes($valueArray[socnetIconsBlock][PI]).'">
			<br>
			<p>www.youtube.com/channel/</p>
				<input type="text" onkeyup="'.$jsFunc.'Preview()" id="'.$id.'_YT" name="'.$name.'[socnetIconsBlock][YT]" value="'.stripslashes($valueArray[socnetIconsBlock][YT]).'">
			<br>
			<p>www.linkedin.com/...</p>
				<input type="text" onkeyup="'.$jsFunc.'Preview()" id="'.$id.'_LI" name="'.$name.'[socnetIconsBlock][LI]" value="'.stripslashes($valueArray[socnetIconsBlock][LI]).'">
			<br>
			<p>vk.com</p>
				<input type="text" onkeyup="'.$jsFunc.'Preview()" id="'.$id.'_VK" name="'.$name.'[socnetIconsBlock][VK]" value="'.stripslashes($valueArray[socnetIconsBlock][VK]).'">
			<br>
			<p>odnoklassniki</p>
				<input type="text" onkeyup="'.$jsFunc.'Preview()" id="'.$id.'_OD" name="'.$name.'[socnetIconsBlock][OD]" value="'.stripslashes($valueArray[socnetIconsBlock][OD]).'">
			<br>
			<p>instagram</p>
				<input type="text" onkeyup="'.$jsFunc.'Preview()" id="'.$id.'_IG" name="'.$name.'[socnetIconsBlock][IG]" value="'.stripslashes($valueArray[socnetIconsBlock][IG]).'">
			<br>
			<p>RSS feed</p>
				<input type="text" onkeyup="'.$jsFunc.'Preview()" id="'.$id.'_RSS" name="'.$name.'[socnetIconsBlock][RSS]" value="'.stripslashes($valueArray[socnetIconsBlock][RSS]).'">
		';
		return $ret;
	}
		
	
	function generateColorPickInput($nameTool, $nameInput, $title, $valueArray, $id){
		$id = $this->prepareIdName($id);
		$ret = '';
		$jsFunc = str_replace('[', '',$nameTool);
		$jsFunc = str_replace(']', '',$jsFunc);		
		$jsFunc = str_replace('moreShareWindow','',$jsFunc);
		$jsFunc = str_replace('buttonBlock','',$jsFunc);
				
		$ret = '
			<div id="'.$id.'_div">
			<p>'.$title.'</p>
			<input class="" type="text" name="'.$nameInput.'" id="'.$id.'" onchange="'.$jsFunc.'Preview();" onclick="openColorPickContainer(\''.$id.'_colorPickContainer\');" style="background-color:'.sprintf("%s", (( strstr($valueArray, '#') ) ? $valueArray : "#".$valueArray)).';" value="'.$valueArray.'">
			</div>
			<script>
			jQuery(document).ready(function($){
				$("#'.$id.'").iris({					
					hide: true,					
					border: false,
					target: "#'.$id.'_colorPickContainer",
					width: 300,
					palettes: true,
					change: function(event, ui) {						
					   $(this).css( "background", ui.color.toString());					   
					   var rgb=this.style.backgroundColor;
					   rgb = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
					   this.style.backgroundColor = "#"+this.toString();
					   this.style.color =
						  0.213 * rgb[1] +
						  0.715 * rgb[2] +
						  0.072 * rgb[3]
						  < 130 ? "#FFF" : "#000";						  						 
					}							
				});
			});
			</script>
		';
		return $ret;
	}
	
	function colorToHex($val){		
		$colorArray = array("indianred"=>"#cd5c5c","crimson"=>"#dc143c","lightpink"=>"#ffb6c1","pink"=>"#ffc0cb","palevioletred"=>"#D87093","hotpink"=>"#ff69b4","mediumvioletred"=>"#c71585","orchid"=>"#da70d6","plum"=>"#dda0dd","violet"=>"#ee82ee","magenta"=>"#ff00ff","purple"=>"#800080","mediumorchid"=>"#ba55d3","darkviolet"=>"#9400d3","darkorchid"=>"#9932cc","indigo"=>"#4b0082","blviolet"=>"#8a2be2","mediumpurple"=>"#9370db","darkslateblue"=>"#483d8b","mediumslateblue"=>"#7b68ee","slateblue"=>"#6a5acd","blue"=>"#0000ff","navy"=>"#000080","midnightblue"=>"#191970","royalblue"=>"#4169e1","cornflowerblue"=>"#6495ed","steelblue"=>"#4682b4","lightskyblue"=>"#87cefa","skyblue"=>"#87ceeb","deepskyblue"=>"#00bfff","lightblue"=>"#add8e6","powderblue"=>"#b0e0e6","darkturquoise"=>"#00ced1","cadetblue"=>"#5f9ea0","cyan"=>"#00ffff","teal"=>"#008080","mediumturquoise"=>"#48d1cc","lightseagreen"=>"#20b2aa","paleturquoise"=>"#afeeee","mediumspringgreen"=>"#00fa9a","springgreen"=>"#00ff7f","darkseagreen"=>"#8fbc8f","palegreen"=>"#98fb98","lmgreen"=>"#32cd32","forestgreen"=>"#228b22","darkgreen"=>"#006400","lawngreen"=>"#7cfc00","grnyellow"=>"#adff2f","darkolivegreen"=>"#556b2f","olvdrab"=>"#6b8e23","yellow"=>"#ffff00","olive"=>"#808000","darkkhaki"=>"#bdb76b","khaki"=>"#f0e68c","gold"=>"#ffd700","gldenrod"=>"#daa520","orange"=>"#ffa500","wheat"=>"#f5deb3","navajowhite"=>"#ffdead","burlywood"=>"#deb887","darkorange"=>"#ff8c00","sienna"=>"#a0522d","orngred"=>"#ff4500","tomato"=>"#ff6347","salmon"=>"#fa8072","brown"=>"#a52a2a","red"=>"#ff0000","black"=>"#000000","darkgrey"=>"#a9a9a9","dimgrey"=>"#696969","lightgrey"=>"#d3d3d3","slategrey"=>"#708090","lightslategrey"=>"#778899","silver"=>"#c0c0c0","whtsmoke"=>"#f5f5f5","white"=>"#ffffff");
		foreach((array)$colorArray as $k => $v){			
			if(strstr(trim($val), '_'.$k)){				
				return $v;
			}
		}
		return $val;
		
	}
	
	function blockStructureMap($scructureMap){		
		if(
			is_array($scructureMap[general_block]) ||
			is_array($scructureMap[bookmark_block]) ||
			is_array($scructureMap[head_block]) ||
			is_array($scructureMap[text_block]) ||
			is_array($scructureMap[promo_block]) ||
			is_array($scructureMap[form_block]) ||
			is_array($scructureMap[button_block]) ||
			is_array($scructureMap[close_icon_block]) ||
			is_array($scructureMap[social_icon_block])
		){
			return true;
		}else{
			return false;
		}
	}
	
	function getBlockHtmlContent($b_name, $b_key){
		$ret = '';
		if($b_name && $b_key){			
			$ret = '
				<div id="'.$b_name.'_'.$b_key.'_content" >
					[%BLOCK_CONTENT%]
				</div>
			';
		}
		return $ret;
	}
	
	function generateCheckbox($nameTool, $title, $id, $nameField, $val){
		$id = $this->prepareIdName($id);
		$jsFunc = str_replace('[', '',$nameTool);
		$jsFunc = str_replace(']', '',$jsFunc);
		$jsFunc = str_replace('buttonBlock','',$jsFunc);
		$jsFunc = str_replace('moreShareWindow','',$jsFunc);
		$ret = '			
			<div class="pq_switch_for">
			'.$title.'
			<input type="checkbox" class="pq_switch" name="'.$nameField.'" onclick="'.$jsFunc.'Preview();" id="'.$id.'" '.sprintf("%s", (($val == "on") ? "checked" : "")).'>
			<label for="'.$id.'" class="pq_switch_label"></label>
			</div>
		';
		return $ret;
	}
	
	function getFormCodeForTool($scructureMap, $name, $valArray, $onlyFields=0, $additionalArray=array(), $isMobile=0)
	{
		
		$allFormElements = array();
		if(!isset($additionalArray)) $additionalArray = array();		
		if(!isset($additionalArray['socnetValues'])) $additionalArray['socnetValues'] = array();		
		if(!isset($additionalArray['socnet_with_pos_error'])) $additionalArray['socnet_with_pos_error'] = array();		
		
		$allFormElements['sharingIcons'] = $this->getSharingIcons($name, (array)$additionalArray['socnetValues'], array('socnet_with_pos_error' => (array)$additionalArray['socnet_with_pos_error']));
		$allFormElements['imageSharerIcons'] = $this->getImageSharingIcons('imageSharer', $this->_options[imageSharer]);
		$allFormElements['imageShareType'] = $this->generateSelect($name, $name.'[is_type]', $this->_dictionary[designOptions][_is_type], array("pq_is_type_iconbar"=>"Icons Bar","pq_is_type_single"=>"Share Icon"), $valArray[is_type], array(), $name.'_is_type', 1);
		$allFormElements['followIcons'] = $this->getFollowIcons($name, $this->_options[$name]);
		$allFormElements['counter_enable'] = $this->generateCheckbox($name, $this->_dictionary[designOptions][_counters], $name.'_counters', $name.'[withCounters]', $valArray[withCounters]);
		$allFormElements['gallery_enable'] = $this->generateCheckbox($name, $this->_dictionary[designOptions][_galerryOption_enable], $name.'_g_enable', $name.'[galleryOption][enable]', $valArray[galleryOption][enable]);
		$allFormElements['new_tab'] = $this->generateCheckbox($name, $this->_dictionary[designOptions][_new_tab], $name.'_new_tab', $name.'[new_tab]', $valArray[new_tab]);
		
		//ShowUp Animation
		$allFormElements['showup_animation'] = $this->generateSelect($name, $name.'[showup_animation]', $this->_dictionary[designOptions][_showup_animation], array("pq_pro_display_animation_random"=>"Random PRO Animation","pq_pro_display_animation_1"=>"Animation 1","pq_pro_display_animation_2"=>"Animation 2","pq_pro_display_animation_3"=>"Animation 3","pq_pro_display_animation_4"=>"Animation 4","pq_pro_display_animation_5"=>"Animation 5","pq_pro_display_animation_6"=>"Animation 6","pq_pro_display_animation_7"=>"Animation 7","pq_pro_display_animation_8"=>"Animation 8","pq_pro_display_animation_9"=>"Animation 9","pq_pro_display_animation_10"=>"Animation 10","pq_pro_display_animation_11"=>"Animation 11","pq_pro_display_animation_12"=>"Animation 12","pq_pro_display_animation_13"=>"Animation 13"), $valArray[showup_animation], array(), $name.'_showup_animation', 0);
		
		//BACKGROUND COLOR		
		$allFormElements['background_color'] = $this->generateColorPickInput($name, $name.'[background_color]', $this->_dictionary[designOptions][_background], $this->colorToHex($valArray[background_color]), $name.'_background_color');				
		//BACKGROUND BUTTON BLOCK
		$allFormElements['background_button_block'] = $this->generateColorPickInput($name, $name.'[background_button_block]', $this->_dictionary[designOptions][_background_button_block], $this->colorToHex($valArray[background_button_block]), $name.'_background_button_block');		
		//BACKGROUND TEXT BLOCK
		$allFormElements['background_text_block'] = $this->generateColorPickInput($name, $name.'[background_text_block]', $this->_dictionary[designOptions][_background_text_block], $this->colorToHex($valArray[background_text_block]), $name.'_background_text_block');		
		//BACKGROUND FORM BLOCK
		$allFormElements['background_form_block'] = $this->generateColorPickInput($name, $name.'[background_form_block]', $this->_dictionary[designOptions][_background_form_block], $this->colorToHex($valArray[background_form_block]), $name.'_background_form_block');		
		//BACKGROUND SOC BLOCK
		$allFormElements['background_soc_block'] = $this->generateColorPickInput($name, $name.'[background_soc_block]', $this->_dictionary[designOptions][_background_soc_block], $this->colorToHex($valArray[background_soc_block]), $name.'_background_soc_block');		
		//OVERLAY COLOR
		$allFormElements['overlay_color'] = $this->generateColorPickInput($name, $name.'[overlay]', $this->_dictionary[designOptions][_overlay], $this->colorToHex($valArray[overlay]), $name.'_overlay_color');
		//OVERLAY_OPACITY
		$allFormElements['overlay_opacity'] = $this->generateSelect($name, $name.'[overlay_opacity]', $this->_dictionary[designOptions][_overlay_opacity], array("pq_overlay_0"=>"Opacity 0%","pq_overlay_10"=>"Opacity 10%","pq_overlay_20"=>"Opacity 20%","pq_overlay_30"=>"Opacity 30%","pq_overlay_40"=>"Opacity 40%","pq_overlay_50"=>"Opacity 50%","pq_overlay_60"=>"Opacity 60%","pq_overlay_70"=>"Opacity 70%","pq_overlay_80"=>"Opacity 80%","pq_overlay_90"=>"Opacity 90%","pq_overlay_100"=>"Opacity 100%"), $valArray[overlay_opacity], array(), $name.'_overlay_opacity', 0);
		
		
		/*'ss_view_type'
		'ss_color',
		'ss_background_color'
		SHARING SIDEBAR & IMAGE SHARER
		*/
		$allFormElements['ss_view_type'] = $this->generateSelect($name, $name.'[ss_view_type]', $this->_dictionary[designOptions][_ss_view_type], array("pq_pro_ss_with_linear_counter"=>"Type 1","pq_pro_ss_with_bottom_counter"=>"Type 2"), $valArray[ss_view_type], array(), $name.'_ss_view_type', 0);
		$allFormElements['ss_color'] = $this->generateColorPickInput($name, $name.'[ss_color]', $this->_dictionary[designOptions][_ss_color], $this->colorToHex($valArray[ss_color]), $name.'_ss_color');
		$allFormElements['ss_background_color'] = $this->generateColorPickInput($name, $name.'[ss_background_color]', $this->_dictionary[designOptions][_ss_background_color], $this->colorToHex($valArray[ss_background_color]), $name.'_ss_background_color');
		$allFormElements['ss_color_opacity'] = $this->generateSelect($name, $name.'[ss_color_opacity]', $this->_dictionary[designOptions][_ss_color_opacity], array("1"=>"Symbol Opacity 10%","2"=>"Symbol Opacity 20%","3"=>"Symbol Opacity 30%","4"=>"Symbol Opacity 40%","5"=>"Symbol Opacity 50%","6"=>"Symbol Opacity 60%","7"=>"Symbol Opacity 70%","8"=>"Symbol Opacity 80%","9"=>"Symbol Opacity 90%","10"=>"Symbol Opacity 100%"), $valArray[ss_color_opacity], array(), $name.'_ss_color_opacity', 0);
		$allFormElements['ss_background_color_opacity'] = $this->generateSelect($name, $name.'[ss_background_color_opacity]', $this->_dictionary[designOptions][_ss_background_color_opacity], array("1"=>"Background Opacity 10%","2"=>"Background Opacity 20%","3"=>"Background Opacity 30%","4"=>"Background Opacity 40%","5"=>"Background Opacity 50%","6"=>"Background Opacity 60%","7"=>"Background Opacity 70%","8"=>"Background Opacity 80%","9"=>"Background Opacity 90%","10"=>"Background Opacity 100%"), $valArray[ss_background_color_opacity], array(), $name.'_ss_background_color_opacity', 0);
		
		//BUTTON TEXT_COLOR
		$allFormElements['button_text_color'] = $this->generateColorPickInput($name, $name.'[button_text_color]', $this->_dictionary[designOptions][_button_text_color], $this->colorToHex($valArray[button_text_color]), $name.'_button_text_color');
		//BUTTON COLOR
		$allFormElements['button_color'] = $this->generateColorPickInput($name, $name.'[button_color]', $this->_dictionary[designOptions][_button_color], $this->colorToHex($valArray[button_color]), $name.'_button_color');
		//HEAD COLOR
		$allFormElements['head_color'] = $this->generateColorPickInput($name, $name.'[head_color]', $this->_dictionary[designOptions][_head_color], $this->colorToHex($valArray[head_color]), $name.'_head_color');
		//Text COLOR
		$allFormElements['text_color'] = $this->generateColorPickInput($name, $name.'[text_color]', $this->_dictionary[designOptions][_text_color], $this->colorToHex($valArray[text_color]), $name.'_text_color');		
		//Border Color
		$allFormElements['border_color'] = $this->generateColorPickInput($name, $name.'[border_color]', $this->_dictionary[designOptions][_border_color], $this->colorToHex($valArray[border_color]), $name.'_border_color');
		//close_icon_color
		$allFormElements['close_icon_color'] = $this->generateColorPickInput($name, $name.'[close_icon][color]', $this->_dictionary[designOptions][_close_icon_color], $this->colorToHex($valArray[close_icon][color]), $name.'_close_icon_color');
		//GALLERY BACKGROUND COLOR
		$allFormElements['gallery_background_color'] = $this->generateColorPickInput($name, $name.'[galleryOption][background_color]', $this->_dictionary[designOptions][_galleryOption_background_color], $this->colorToHex($valArray[galleryOption][background_color]), $name.'_galleryOption_background_color');
		//GALLERY BUTTON TEXT COLOR
		$allFormElements['gallery_button_text_color'] = $this->generateColorPickInput($name, $name.'[galleryOption][button_text_color]', $this->_dictionary[designOptions][_galleryOption_button_text_color], $this->colorToHex($valArray[galleryOption][button_text_color]), $name.'_galleryOption_button_text_color');
		//GALLERY BUTTON COLOR
		$allFormElements['gallery_button_color'] = $this->generateColorPickInput($name, $name.'[galleryOption][button_color]', $this->_dictionary[designOptions][_galleryOption_button_color], $this->colorToHex($valArray[galleryOption][button_color]), $name.'_galleryOption_button_color');
		//GALLERY HEAD COLOR
		$allFormElements['gallery_head_color'] = $this->generateColorPickInput($name, $name.'[galleryOption][head_color]', $this->_dictionary[designOptions][_galleryOption_head_color], $this->colorToHex($valArray[galleryOption][head_color]), $name.'_galleryOption_head_color', 0);		
		//tblock_text_font_color
		$allFormElements['tblock_text_font_color'] = $this->generateColorPickInput($name, $name.'[tblock_text_font_color]', $this->_dictionary[designOptions][_tblock_text_font_color], $this->colorToHex($valArray[tblock_text_font_color]), $name.'_tblock_text_font_color', 0);
		//background_mobile_block
		$allFormElements['background_mobile_block'] = $this->generateColorPickInput($name, $name.'[background_mobile_block]', $this->_dictionary[designOptions][_background_mobile_block], $this->colorToHex($valArray[background_mobile_block]), $name.'_background_mobile_block', 0);
		//mblock_text_font_color
		$allFormElements['mblock_text_font_color'] = $this->generateColorPickInput($name, $name.'[mblock_text_font_color]', $this->_dictionary[designOptions][_mblock_text_font_color], $this->colorToHex($valArray[mblock_text_font_color]), $name.'_mblock_text_font_color', 0);
		
		//BOOKMARK
		//bookmark TEXT SIZE
		$allFormElements['bookmark_text_size'] = $this->generateSelect($name, $name.'[bookmark_text_size]', $this->_dictionary[designOptions][_bookmark_text_size], array("pq_bm_text_size12" => "Size - 12","pq_bm_text_size16" => "Size - 16","pq_bm_text_size18" => "Size - 18","pq_bm_text_size20" => "Size - 20","pq_bm_text_size24" => "Size - 24","pq_bm_text_size28" => "Size - 28","pq_bm_text_size30" => "Size - 30","pq_bm_text_size32" => "Size - 32","pq_bm_text_size34" => "Size - 34","pq_bm_text_size36" => "Size - 36","pq_bm_text_size42" => "Size - 42","pq_bm_text_size48" => "Size - 48"), $valArray[bookmark_text_size], array(), $name.'_bookmark_text_size', 0);
		//Bookmark Text Font
		$allFormElements['bookmark_text_font'] = $this->getFontSelect($name, $name.'[bookmark_text_font]', $this->_dictionary[designOptions][_bookmark_text_font], $valArray[bookmark_text_font], $name.'_bookmark_text_font', 'pq_bm_text_font_bmsticktext_');		
		//bookmark_text_color
		$allFormElements['bookmark_text_color'] = $this->generateColorPickInput($name, $name.'[bookmark_text_color]', $this->_dictionary[designOptions][_bookmark_text_color], $this->colorToHex($valArray[bookmark_text_color]), $name.'_bookmark_text_color');
		//bookmark_background
		$allFormElements['bookmark_background_color'] = $this->generateColorPickInput($name, $name.'[bookmark_background]', $this->_dictionary[designOptions][_bookmark_background], $this->colorToHex($valArray[bookmark_background]), $name.'_bookmark_background_color');		
		
		
		
		
		//SIZE WINDOW
		$allFormElements['size_window'] = $this->generateSelect($name, $name.'[typeWindow]', $this->_dictionary[designOptions][_typeWindow], array('pq_large'=>'Size L','pq_medium'=>'Size M', 'pq_mini'=>'Size S'), $valArray[typeWindow], array('pq_large'=>'123','pq_medium'=>'222', 'pq_mini'=>'333'), $name.'_typeWindow', 1);		
		//ANIMATION
		$allFormElements['animation'] = $this->generateSelect($name, $name.'[animation]', $this->_dictionary[designOptions][_animation], array("pq_anim_flipInY" => "Flip In Y","pq_anim_flipInX" => "Flip In X","pq_anim_zoomIn" => "Zoom In","pq_anim_zoomInUp" => "Zoom In Up","pq_anim_zoomInDown" => "Zoom In Down","pq_anim_zoomInLeft" => "Zoom In Left","pq_anim_zoomInRight" => "Zoom In Right","pq_anim_fadeIn" => "Fade In","pq_anim_fadeInUp" => "Fade In Up","pq_anim_fadeInDown" => "Fade In Down","pq_anim_fadeInLeft" => "Fade In Left","pq_anim_fadeInRight" => "Fade In Right","pq_anim_swingIn" => "Swing","pq_anim_rubberBandIn" => "RubberBand","pq_anim_shakeIn" => "Shake","pq_anim_wobbleIn" => "Worbble","pq_anim_jelloIn" => "Jello","pq_anim_tadaIn" => "Tada","pq_anim_bounceInUp" => "Bounce In Up","pq_anim_bounceInDown" => "Bounce In Down","pq_anim_bounceInLeft" => "Bounce In Left","pq_anim_bounceInRight" => "Bounce In Right","pq_anim_lightSpeedIn" => "Light Speed In","pq_anim_rotateIn" => "Rotate In","pq_anim_rotateInDownLeft" => "Rotate In Down Left"), $valArray[animation], array(), $name.'_animation', 0);
		
		//DESIGN ICON
		$allFormElements['design_icons'] = $this->generateSelect($name, $name.'[icon][design]', $this->_dictionary[designOptions][_icon_design], array("c1"=>"Type 1","c2"=>"Type 2","c3"=>"Type 3","c4"=>"Type 4","c5"=>"Type 5","c6"=>"Type 6","c7"=>"Type 7","c8"=>"Type 8","c9"=>"Type 9","c10"=>"Type 10","c11"=>"Type 11", "c12"=>"Type 12"), $valArray[icon][design], array(), $name.'_icon_design', 1);
		//FORM ICON
		$allFormElements['form_icons'] = $this->generateSelect($name, $name.'[icon][form]', $this->_dictionary[designOptions][_icon_form], array('pq_square'=>'Square', 'pq_rounded'=>'Rounded', 'pq_circle'=>'Circle', 'pq_tv'=>'TV style'), $valArray[icon][form], array(), $name.'_icon_form', 0);
		//SIZE ICON
		$allFormElements['size_icons'] = $this->generateSelect($name, $name.'[icon][size]', $this->_dictionary[designOptions][_icon_size], array('x20'=>'Size S', 'x30'=>'Size M', 'x40'=>'Size M+', 'x50'=>'Size L', 'x70'=>'Size XL'), $valArray[icon][size], array(), $name.'_icon_size', 0);
		//SPACE ICON
		$allFormElements['space_icons'] = $this->generateSelect($name, $name.'[icon][space]', $this->_dictionary[designOptions][_icon_space], array('pq_step1'=>'1px','pq_step2'=>'2px','pq_step3'=>'3px','pq_step4'=>'4px','pq_step5'=>'5px','pq_step6'=>'6px','pq_step7'=>'7px','pq_step8'=>'8px','pq_step9'=>'9px','pq_step10'=>'10px'), $valArray[icon][space], array(), $name.'_icon_space', 0);
		//SHADOW ICON
		$allFormElements['shadow_icons'] = $this->generateSelect($name, $name.'[icon][shadow]', $this->_dictionary[designOptions][_icon_shadow], array('sh1'=>'Shadow №1','sh2'=>'Shadow №2','sh3'=>'Shadow №3','sh4'=>'Shadow №4','sh5'=>'Shadow №5','sh6'=>'Shadow №6'), $valArray[icon][shadow], array(), $name.'_icon_shadow', 0);
		//HOVER ICON
		$allFormElements['animation_icons'] = $this->generateSelect($name, $name.'[icon][animation]', $this->_dictionary[designOptions][_icon_animation], array("pq_hvr_grow" => "Grow","pq_hvr_shrink" => "Shrink","pq_hvr_pulse" => "Pulse","pq_hvr_push" => "Push","pq_hvr_float" => "Float","pq_hvr_sink" => "Sink","pq_hvr_hang" => "Hang","pq_hvr_buzz" => "Buzz","pq_hvr_border_fade" => "Bdr Fade","pq_hvr_hollow" => "Hollow","pq_hvr_glow" => "Glow","pq_hvr_grow_shadow" => "Grow Shadow"), $valArray[icon][animation], array(), $name.'_icon_animation', 0);		
		//MOBILE TYPE
		$allFormElements['mobile_view_type'] = $this->generateSelect($name, $name.'[mobile_type]', $this->_dictionary[designOptions][_mobile_type], array("pq_default" => "Default", "pq_mosaic" => "Mosaic", "pq_coin" => "Coin"), $valArray[mobile_type], array(), $name.'_mobile_type', 1);
		//MOBILE POSITION
		$allFormElements['mobile_position'] = $this->generateSelect($name, $name.'[mobile_position]', $this->_dictionary[designOptions][_mobile_position], array("pq_mobile_top" => "Top", "pq_mobile_bottom" => "Bottom"), $valArray[mobile_position], array(), $name.'_mobile_position', 1);		
		//Min Width
		$allFormElements['min_width'] = $this->generateSelect($name, $name.'[minWidth]', $this->_dictionary[designOptions][_minWidth], array(100 => "100px and more", 200 => "200px and more", 300 => "300px and more", 400 => "400px and more", 500 => "500px and more", 600 => "600px and more",), $valArray[minWidth], array(), $name.'_minWidth', 0);		
		//Min Height
		$allFormElements['min_height'] = $this->generateSelect($name, $name.'[minHeight]', $this->_dictionary[designOptions][_minHeight], array(0 => "0px and more", 100 => "100px and more", 200 => "200px and more", 300 => "300px and more", 400 => "400px and more", 500 => "500px and more", 600 => "600px and more",), $valArray[minHeight], array(), $name.'_minHeight', 0);						
		//HEAD FONT SIZE
		$allFormElements['head_font_size'] = $this->generateSelect($name, $name.'[head_size]', $this->_dictionary[designOptions][_head_size], array("pq_head_size12" => "Size - 12","pq_head_size14" => "Size - 14","pq_head_size16" => "Size - 16","pq_head_size18" => "Size - 18","pq_head_size20" => "Size - 20","pq_head_size22" => "Size - 22","pq_head_size24" => "Size - 24","pq_head_size26" => "Size - 26","pq_head_size28" => "Size - 28","pq_head_size30" => "Size - 30","pq_head_size32" => "Size - 32","pq_head_size34" => "Size - 34","pq_head_size36" => "Size - 36","pq_head_size42" => "Size - 42","pq_head_size48" => "Size - 48","pq_head_size52" => "Size - 52","pq_head_size56" => "Size - 56","pq_head_size60" => "Size - 60","pq_head_size64" => "Size - 64","pq_head_size72" => "Size - 72","pq_head_size82" => "Size - 82","pq_head_size90" => "Size - 90","pq_head_size110" => "Size - 110","pq_head_size120" => "Size - 120","pq_head_size130" => "Size - 130"), $valArray[head_size], array(), $name.'_head_font_size', 0);
		//TEXT FONT SIZE
		$allFormElements['text_font_size'] = $this->generateSelect($name, $name.'[text_size]', $this->_dictionary[designOptions][_text_size], array("pq_text_size12" => "Size - 12","pq_text_size14" => "Size - 14","pq_text_size16" => "Size - 16","pq_text_size18" => "Size - 18","pq_text_size20" => "Size - 20","pq_text_size24" => "Size - 24","pq_text_size28" => "Size - 28","pq_text_size30" => "Size - 30","pq_text_size32" => "Size - 32","pq_text_size34" => "Size - 34","pq_text_size36" => "Size - 36","pq_text_size42" => "Size - 42","pq_text_size48" => "Size - 48","pq_text_size52" => "Size - 52","pq_text_size56" => "Size - 56","pq_text_size60" => "Size - 60","pq_text_size64" => "Size - 64","pq_text_size72" => "Size - 72","pq_text_size82" => "Size - 82","pq_text_size90" => "Size - 90","pq_text_size110" => "Size - 110","pq_text_size120" => "Size - 120","pq_text_size130" => "Size - 130"), $valArray[text_size], array(), $name.'_text_font_size', 0);
		
		
		//PADDING
		$allFormElements['form_block_padding'] = $this->generateSelect($name, $name.'[form_block_padding]', $this->_dictionary[designOptions][_form_block_padding], array("pq_formbg_bg_top_s"=>"Padding S","pq_formbg_bg_top_m"=>"Padding M","pq_formbg_bg_top_l"=>"Padding L","pq_formbg_bg_top_xl"=>"Padding XL"), $valArray[form_block_padding], array(), $name.'_form_block_padding', 0);
		$allFormElements['button_block_padding'] = $this->generateSelect($name, $name.'[button_block_padding]', $this->_dictionary[designOptions][_button_block_padding], array("pq_btngbg_bg_top_s"=>"Padding S","pq_btngbg_bg_top_m"=>"Padding M","pq_btngbg_bg_top_l"=>"Padding L","pq_btngbg_bg_top_xl"=>"Padding XL"), $valArray[button_block_padding], array(), $name.'_button_block_padding', 0);
		$allFormElements['text_block_padding'] = $this->generateSelect($name, $name.'[text_block_padding]', $this->_dictionary[designOptions][_text_block_padding], array("pq_txtbg_bg_top_s"=>"Padding S","pq_txtbg_bg_top_m"=>"Padding M","pq_txtbg_bg_top_l"=>"Padding L","pq_txtbg_bg_top_xl"=>"Padding XL"), $valArray[text_block_padding], array(), $name.'_text_block_padding', 0);
		$allFormElements['icon_block_padding'] = $this->generateSelect($name, $name.'[icon_block_padding]', $this->_dictionary[designOptions][_icon_block_padding], array("pq_icobg_bg_top_s"=>"Padding S","pq_icobg_bg_top_m"=>"Padding M","pq_icobg_bg_top_l"=>"Padding L","pq_icobg_bg_top_xl"=>"Padding XL"), $valArray[icon_block_padding], array(), $name.'_icon_block_padding', 0);
		
		
		//Popup Form
		$allFormElements['popup_form'] = $this->generateSelect($name, $name.'[popup_form]', $this->_dictionary[designOptions][_popup_form], array("pq_br_sq" => "Square",	"pq_br_cr" => "Rounded"), $valArray[popup_form], array(), $name.'_popup_form', 0);		
		
		
		//mblock_text_font_size
		$allFormElements['mblock_text_font_size'] = $this->generateSelect($name, $name.'[mblock_text_font_size]', $this->_dictionary[designOptions][_mblock_text_font_size], array("pq_mblock_size16"=>"Size 16","pq_mblock_size20"=>"Size 20","pq_mblock_size24"=>"Size 24","pq_mblock_size28"=>"Size 28","pq_mblock_size36"=>"Size 36","pq_mblock_size48"=>"Size 48"), $valArray[mblock_text_font_size], array(), $name.'_mblock_text_font_size', 0);		
		
		//font_size
		$allFormElements['font_size'] = $this->generateSelect($name, $name.'[font_size]', $this->_dictionary[designOptions][_font_size], array("pq_text_size12" => "Size - 12","pq_text_size14" => "Size - 14","pq_text_size16" => "Size - 16","pq_text_size18" => "Size - 18","pq_text_size20" => "Size - 20","pq_text_size24" => "Size - 24","pq_text_size28" => "Size - 28","pq_text_size30" => "Size - 30","pq_text_size32" => "Size - 32","pq_text_size34" => "Size - 34","pq_text_size36" => "Size - 36","pq_text_size42" => "Size - 42","pq_text_size48" => "Size - 48","pq_text_size52" => "Size - 52","pq_text_size56" => "Size - 56","pq_text_size60" => "Size - 60","pq_text_size64" => "Size - 64","pq_text_size72" => "Size - 72","pq_text_size82" => "Size - 82","pq_text_size90" => "Size - 90","pq_text_size110" => "Size - 110","pq_text_size120" => "Size - 120","pq_text_size130" => "Size - 130"), $valArray[font_size], array(), $name.'_font_size', 0);		
		//border_type
		$allFormElements['border_type'] = $this->generateSelect($name, $name.'[border_type]', $this->_dictionary[designOptions][_border_type], array("pq_bs_dotted"=>"Border 1","pq_bs_dashed"=>"Border 2","pq_bs_double"=>"Border 3","pq_bs_post"=>"Border 4"), $valArray[border_type], array(), $name.'_border_type', 0);
		//border_depth
		$allFormElements['border_depth'] = $this->generateSelect($name, $name.'[border_depth]', $this->_dictionary[designOptions][_border_depth], array("pq_bd1"=>"Type 1","pq_bd2"=>"Type 2","pq_bd3"=>"Type 3","pq_bd4"=>"Type 4","pq_bd5"=>"Type 5","pq_bd6"=>"Type 6","pq_bd7"=>"Type 7","pq_bd8"=>"Type 8","pq_bd9"=>"Type 9","pq_bd10"=>"Type 10"), $valArray[border_depth], array(), $name.'_border_depth', 0);
		//button_font_size
		$allFormElements['button_font_size'] = $this->generateSelect($name, $name.'[button_font_size]', $this->_dictionary[designOptions][_button_font_size], array("pq_btn_size12"=>"Size 12","pq_btn_size14"=>"Size 14","pq_btn_size16"=>"Size 16","pq_btn_size18"=>"Size 18","pq_btn_size20"=>"Size 20","pq_btn_size22"=>"Size 22","pq_btn_size24"=>"Size 24","pq_btn_size26"=>"Size 26","pq_btn_size28"=>"Size 28","pq_btn_size30"=>"Size 30","pq_btn_size32"=>"Size 32","pq_btn_size34"=>"Size 34","pq_btn_size36"=>"Size 36","pq_btn_size42"=>"Size 42","pq_btn_size48"=>"Size 48","pq_btn_size52"=>"Size 52","pq_btn_size56"=>"Size 56","pq_btn_size60"=>"Size 60","pq_btn_size64"=>"Size 64","pq_btn_size72"=>"Size 72","pq_btn_size82"=>"Size 82","pq_btn_size90"=>"Size 90"), $valArray[button_font_size], array(), $name.'_button_font_size', 0);		
		//text block font size
		$allFormElements['tblock_text_font_size'] = $this->generateSelect($name, $name.'[tblock_text_font_size]', $this->_dictionary[designOptions][_tblock_text_font_size], array("pq_tblock_size12"=>"Size 12","pq_tblock_size14"=>"Size 14","pq_tblock_size16"=>"Size 16","pq_tblock_size18"=>"Size 18","pq_tblock_size20"=>"Size 20","pq_tblock_size22"=>"Size 22","pq_tblock_size24"=>"Size 24","pq_tblock_size26"=>"Size 26","pq_tblock_size28"=>"Size 28","pq_tblock_size36"=>"Size 36","pq_tblock_size48"=>"Size 48"), $valArray[tblock_text_font_size], array(), $name.'_tblock_text_font_size', 0);		
		
		
		//Head Font
		$allFormElements['head_font'] = $this->getFontSelect($name, $name.'[head_font]', $this->_dictionary[designOptions][_head_font], $valArray[head_font], $name.'_head_font', 'pq_h_font_h1_');
		//Font
		$allFormElements['text_font'] = $this->getFontSelect($name, $name.'[text_font]', $this->_dictionary[designOptions][_text_font], $valArray[text_font], $name.'_text_font', 'pq_text_font_pqtext_');		
		//mblock_text_font
		$allFormElements['mblock_text_font'] = $this->getFontSelect($name, $name.'[mblock_text_font]', $this->_dictionary[designOptions][_mblock_text_font], $valArray[mblock_text_font], $name.'_mblock_text_font', 'pq_mblock_font_bgmobblock_');		
		
		//button_font
		$allFormElements['button_font'] = $this->getFontSelect($name, $name.'[button_font]', $this->_dictionary[designOptions][_button_font],$valArray[button_font], $name.'_button_font', 'pq_btn_font_btngroupbtn_');
		//Text block text font
		$allFormElements['tblock_text_font'] = $this->getFontSelect($name, $name.'[tblock_text_font]', $this->_dictionary[designOptions][_tblock_text_font], $valArray[tblock_text_font], $name.'_tblock_text_font', 'pq_bgtxt_block_font_bgtxtp_');
		//close_text_font
		$allFormElements['close_text_font'] = $this->getFontSelect($name, $name.'[close_text_font]', $this->_dictionary[designOptions][_close_text_font],$valArray[close_text_font], $name.'_close_text_font', 'pq_x_font_pqclose_');
		
	
		//Button Form
		$allFormElements['button_form'] = $this->generateSelect($name, $name.'[button_form]', $this->_dictionary[designOptions][_button_form], array("pq_btn_cr"=>"Button Form 1", "pq_btn_sq"=>"Button Form 2"), $valArray[button_form], array(), $name.'_button_form', 0);
		//Input type
		$allFormElements['input_type'] = $this->generateSelect($name, $name.'[input_type]', $this->_dictionary[designOptions][_input_type], array("pq_input_type1"=>"Input Type 1","pq_input_type2"=>"Input Type 2","pq_input_type3"=>"Input Type 3","pq_input_type4"=>"Input Type 4","pq_input_type5"=>"Input Type 5","pq_input_type6"=>"Input Type 6","pq_input_type7"=>"Input Type 7","pq_input_type8"=>"Input Type 8","pq_input_type9"=>"Input Type 9","pq_input_type10"=>"Input Type 10","pq_input_type11"=>"Input Type 11","pq_input_type12"=>"Input Type 12","pq_input_type13"=>"Input Type 13","pq_input_type14"=>"Input Type 14","pq_input_type15"=>"Input Type 15","pq_input_type16"=>"Input Type 16"), $valArray[input_type], array(), $name.'_input_type', 0);
		//Button Type
		$allFormElements['button_type'] = $this->generateSelect($name, $name.'[button_type]', $this->_dictionary[designOptions][_button_type], array("pq_btn_type1"=>"Button Type 1","pq_btn_type2"=>"Button Type 2","pq_btn_type3"=>"Button Type 3","pq_btn_type4"=>"Button Type 4","pq_btn_type5"=>"Button Type 5","pq_btn_type6"=>"Button Type 6","pq_btn_type7"=>"Button Type 7","pq_btn_type8"=>"Button Type 8","pq_btn_type9"=>"Button Type 9","pq_btn_type10"=>"Button Type 10"), $valArray[button_type], array(), $name.'_button_type', 0);
		//IMG type
		$allFormElements['header_img_type'] = $this->generateSelect($name, $name.'[header_img_type]', $this->_dictionary[designOptions][_header_img_type], array("pq_pro_img_type1"=>"Header Img Type 1","pq_pro_img_type2"=>"Header Img Type 2","pq_pro_img_type3"=>"Header Img Type 3","pq_pro_img_type4"=>"Header Img Type 4","pq_pro_img_type5"=>"Header Img Type 5","pq_pro_img_type6"=>"Header Img Type 6","pq_pro_img_type7"=>"Header Img Type 7","pq_pro_img_type8"=>"Header Img Type 8","pq_pro_img_type9"=>"Header Img Type 9","pq_pro_img_type10"=>"Header Img Type 10","pq_pro_img_type11"=>"Header Img Type 11","pq_pro_img_type12"=>"Header Img Type 12","pq_pro_img_type13"=>"Header Img Type 13","pq_pro_img_type14"=>"Header Img Type 14","pq_pro_img_type15"=>"Header Img Type 15","pq_pro_img_type16"=>"Header Img Type 16"), $valArray[header_img_type], array(), $name.'_header_img_type', 0);
		//background opacity
		$allFormElements['background_opacity'] = $this->generateSelect($name, $name.'[background_opacity]', $this->_dictionary[designOptions][_background_opacity], array("0"=>"Background Opacity 0%","1"=>"Background Opacity 10%","2"=>"Background Opacity 20%","3"=>"Background Opacity 30%","4"=>"Background Opacity 40%","5"=>"Background Opacity 50%","6"=>"Background Opacity 60%","7"=>"Background Opacity 70%","8"=>"Background Opacity 80%","9"=>"Background Opacity 90%","10"=>"Background Opacity 100%"), $valArray[background_opacity], array(), $name.'_background_opacity', 0);
		
		
		//HOVER CLOSE ICON
		$allFormElements['close_icon_animation'] = $this->generateSelect($name, $name.'[close_icon][animation]', $this->_dictionary[designOptions][_animation_close_icon], array("pq_hvr_rotate"=>"Rotate","pq_hvr_grow" => "Grow","pq_hvr_shrink" => "Shrink","pq_hvr_pulse" => "Pulse","pq_hvr_push" => "Push","pq_hvr_float" => "Float","pq_hvr_sink" => "Sink","pq_hvr_hang" => "Hang","pq_hvr_buzz" => "Buzz","pq_hvr_border_fade" => "Bdr Fade","pq_hvr_hollow" => "Hollow","pq_hvr_glow" => "Glow","pq_hvr_grow_shadow" => "Grow Shadow"), $valArray[close_icon][animation], array(), $name.'_animation_close_icon', 0);		
		//close_icon_type
		$allFormElements['close_icon_type'] = $this->generateSelect($name, $name.'[close_icon][form]', $this->_dictionary[designOptions][_close_icon_type], array("pq_x_type1"=>"Close Icon 1","pq_x_type2"=>"Close Icon 2","pq_x_type3"=>"Close Icon 3","pq_x_type4"=>"Close Icon 4","pq_x_type5"=>"Close Icon 5","pq_x_type9"=>"Close Icon 6","pq_x_type6"=>"Close Text 1","pq_x_type7"=>"Close Text 2","pq_x_type8"=>"Close Text 3","pq_x_type10"=>"Close Text 4"), $valArray[close_icon][form], array(), $name.'_close_icon_type', 0);		
		
		
		
		
		//GALLERY button_font		
		$allFormElements['gallery_button_font'] = $this->getFontSelect($name, $name.'[galleryOption][button_font]', $this->_dictionary[designOptions][_galleryOption_button_font], $valArray[galleryOption][button_font], $name.'_galleryOption_button_font', 'pq_btn_font_pqbtn_');
		//GALLERY button_font_size
		$allFormElements['gallery_button_font_size'] = $this->generateSelect($name, $name.'[galleryOption][button_font_size]', $this->_dictionary[designOptions][_gallery_button_font_size], array("pq_btn_size12"=>"Size 12","pq_btn_size14"=>"Size 14","pq_btn_size16"=>"Size 16","pq_btn_size18"=>"Size 18","pq_btn_size20"=>"Size 20","pq_btn_size22"=>"Size 22","pq_btn_size24"=>"Size 24","pq_btn_size26"=>"Size 26","pq_btn_size28"=>"Size 28","pq_btn_size30"=>"Size 30","pq_btn_size32"=>"Size 32","pq_btn_size34"=>"Size 34","pq_btn_size36"=>"Size 36","pq_btn_size42"=>"Size 42","pq_btn_size48"=>"Size 48","pq_btn_size52"=>"Size 52","pq_btn_size56"=>"Size 56","pq_btn_size60"=>"Size 60","pq_btn_size64"=>"Size 64","pq_btn_size72"=>"Size 72","pq_btn_size82"=>"Size 82","pq_btn_size90"=>"Size 90"), $valArray[galleryOption][button_font_size], array(), $name.'_galleryOption_button_font_size', 0);		
		
		//GALLERY HEAD FONT SIZE
		$allFormElements['gallery_head_font_size'] = $this->generateSelect($name, $name.'[galleryOption][head_size]', $this->_dictionary[designOptions][_galleryOption_head_size], array("pq_head_size12" => "Size - 12","pq_head_size14" => "Size - 14","pq_head_size16" => "Size - 16","pq_head_size18" => "Size - 18","pq_head_size20" => "Size - 20","pq_head_size22" => "Size - 22","pq_head_size24" => "Size - 24","pq_head_size26" => "Size - 26","pq_head_size28" => "Size - 28","pq_head_size30" => "Size - 30","pq_head_size32" => "Size - 32","pq_head_size34" => "Size - 34","pq_head_size36" => "Size - 36","pq_head_size42" => "Size - 42","pq_head_size48" => "Size - 48","pq_head_size52" => "Size - 52","pq_head_size56" => "Size - 56","pq_head_size60" => "Size - 60","pq_head_size64" => "Size - 64","pq_head_size72" => "Size - 72","pq_head_size82" => "Size - 82","pq_head_size90" => "Size - 90","pq_head_size110" => "Size - 110","pq_head_size120" => "Size - 120","pq_head_size130" => "Size - 130"), $valArray[galleryOption][head_size], array(), $name.'_galleryOption_head_font_size', 0);		
		//Gallery Option
		$allFormElements['gallery_enable_option'] = $this->generateSelect($name, $name.'[galleryOption][enable]', $this->_dictionary[designOptions][_galerryOption_enable], array(1 => "Enable", 2=>"Disable"), $valArray[galleryOption][enable], array(), $name.'_galerryOption_enable', 0);
		//Gallery Min Width
		$allFormElements['gallery_min_width'] = $this->generateSelect($name, $name.'[galleryOption][minWidth]', $this->_dictionary[designOptions][_galerryOption_minWidth], array(100 => "100px and more", 200 => "200px and more", 300 => "300px and more", 400 => "400px and more", 500 => "500px and more", 600 => "600px and more",), $valArray[galleryOption][minWidth], array(), $name.'_galerryOption_minWidth', 0);
		
		
		//GALLERY Head Font
		$allFormElements['gallery_head_font'] = $this->getFontSelect($name, $name.'[galleryOption][head_font]', $this->_dictionary[designOptions][_galleryOption_head_font], $valArray[galleryOption][head_font], $name.'_galleryOption_head_font', 'pq_h_font_h1_');
		
			
			
/*********************************************************************INPUT FIELDS*********************************************************************************/				
		$allFormElements['title'] = $this->generateInput($name, $this->_dictionary[designOptions][_title], $name.'_title', $name.'[title]', $valArray[title]);
		$allFormElements['tblock_text'] = $this->generateInput($name, $this->_dictionary[designOptions][_tblock_text], $name.'_tblock_text', $name.'[tblock_text]', $valArray[tblock_text]);
		$allFormElements['fake_counter'] = $this->generateInput($name, $this->_dictionary[designOptions][_fake_counter], $name.'_fake_counter', $name.'[fake_counter]', $valArray[fake_counter]);
		
		$allFormElements['sub_title'] = $this->generateInput($name, $this->_dictionary[designOptions][_subtitle], $name.'_subtitle', $name.'[sub_title]', $valArray[sub_title]);
		$allFormElements['mobile_title'] = $this->generateInput($name, $this->_dictionary[designOptions][_m_title], $name.'_m_title', $name.'[mobile_title]', $valArray[mobile_title]);			
		$allFormElements['gallery_title'] = $this->generateInput($name, $this->_dictionary[designOptions][_g_title], $name.'_g_title', $name.'[galleryOption][title]', $valArray[galleryOption][title]);
		$allFormElements['gallery_button_text'] = $this->generateInput($name, $this->_dictionary[designOptions][_g_button_text], $name.'_g_button_text', $name.'[galleryOption][button_text]', $valArray[galleryOption][button_text]);
		
		
		$allFormElements['enter_email_text'] = $this->generateInput($name, $this->_dictionary[designOptions][_text_email], $name.'_text_email', $name.'[enter_email_text]', $valArray[enter_email_text]);
		$allFormElements['enter_name_text'] = $this->generateInput($name, $this->_dictionary[designOptions][_text_name], $name.'_text_name', $name.'[enter_name_text]', $valArray[enter_name_text]);
		$allFormElements['enter_phone_text'] = $this->generateInput($name, $this->_dictionary[designOptions][_text_phone], $name.'_text_phone', $name.'[enter_phone_text]', $valArray[enter_phone_text]);
		$allFormElements['enter_message_text'] = $this->generateInput($name, $this->_dictionary[designOptions][_text_message], $name.'_text_message', $name.'[enter_message_text]', $valArray[enter_message_text]);
		$allFormElements['enter_subject_text'] = $this->generateInput($name, $this->_dictionary[designOptions][_text_subject], $name.'_text_subject', $name.'[enter_subject_text]', $valArray[enter_subject_text]);
		
		$allFormElements['loader_text'] = $this->generateInput($name, $this->_dictionary[designOptions][_l_text], $name.'_l_text', $name.'[loader_text]', $valArray[loader_text]);
		$allFormElements['button_text'] = $this->generateInput($name, $this->_dictionary[designOptions][_button_text], $name.'_button_text', $name.'[button_text]', $valArray[button_text]);
		
		$allFormElements['close_text'] = $this->generateInput($name, $this->_dictionary[designOptions][_close_text], $name.'_close_text', $name.'[close_icon][button_text]', $valArray[close_icon][button_text]);
		
		$allFormElements['background_image_src'] = $this->generateInput($name, $this->_dictionary[designOptions][_b_image], $name.'_background_image_src', $name.'[background_image_src]', $valArray[background_image_src]);
		$allFormElements['header_image_src'] = $this->generateInput($name, $this->_dictionary[designOptions][_h_image], $name.'_header_image_src', $name.'[header_image_src]', $valArray[header_image_src]);
		$allFormElements['url'] = $this->generateInput($name, $this->_dictionary[designOptions][_url_address], $name.'_url_address', $name.'[url]', $valArray[url]);
		$allFormElements['iframe_src'] = $this->generateInput($name, $this->_dictionary[designOptions][_iframe_src], $name.'_iframe_src', $name.'[iframe_src]', $valArray[iframe_src]);
		$allFormElements['overlay_image_src'] = $this->generateInput($name, $this->_dictionary[designOptions][_overlay_image_src], $name.'_overlay_image_src', $name.'[overlay_image_src]', $valArray[overlay_image_src]);
		
		
		
/******************************************************************GENERATE RETURN CODE***************************************************************************/		
		if($onlyFields){
			$ret = '';
			foreach((array)$scructureMap as $k => $v){
				$ret .= $allFormElements[$v];
			}
		}else{			
			$ret = '
			<div class="pq_sett_all">
				<div class="pq_icons_block">
					{%ICON_BLOCK%}
				';
				
				if($isMobile){
						$ret.='</div>
							<div class="pq_settings_block" id="'.$name.'_mobile_form_block" style="display:none;">
								
								{%ELEMENTS_BLOCK%}				
							</div>
						</div>
						';
				}else{
					$ret.='</div>
							<div class="pq_settings_block" id="'.$name.'_form_block" style="display:none;">
								
								{%ELEMENTS_BLOCK%}				
							</div>
						</div>
						';
				}
				
					
			$icon_block = '';
			$elements_block = '';
			$array_keys = implode(',',array_keys($scructureMap));		
			foreach((array)$scructureMap as $key => $data){
				$icon_block .= '<div class="pq_icon" id="'.$name.'_'.$key.'_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$name.'\', \''.$key.'\', \''.$array_keys.'\')"><img src="'.$data[img].'"><span>'.$data[text].'</span></div>';
				$elements_block .= '<div class="pq_design_settings" id="'.$name.'_'.$key.'_design_options_block" style="display:none"><h3>'.$data[toolName].'</h3> <div><h4>'.$data[text].' </h4><a href="'.$data[read_more_link].'" target="_settings_info">?</a> </div> <a class="pq_settings_close" onclick="closeDesignForm(\''.$name.'\')"></a>';
				
					if(is_array($data[elements])){
						$elements_block .= $this->getBlockHtmlContent($name,$key);
						$contentPart = '';					
						foreach((array)$data[elements] as $k => $v){
							$contentPart .= $allFormElements[$v];
						}
						if($contentPart){
							$elements_block = str_replace('[%BLOCK_CONTENT%]', $contentPart, $elements_block);
						}
					}
				
				$elements_block .= '</div>';
			}
			$ret = str_replace('{%ICON_BLOCK%}', $icon_block, $ret);
			$ret = str_replace('{%ELEMENTS_BLOCK%}', $elements_block, $ret);
		}
		
				
		return $ret;
	}
	
	
	function generateMailHTML($toolName, $toolID){
		$ret = '
		<div id="'.$toolID.'_form_email" style="display:none;" class="pq_design">
			<div class="pq_desktop_mobile">
				<div id="'.$toolID.'_email_DesignToolSwitch_desktop" onclick="addClassToSwitcher(\''.$toolID.'_email\', \'desktop\', \'pq_active\',[\'desktop\', \'mobile\']);'.$toolName.'sendMailWindowPreview();" class="pq_active" >'.$this->_dictionary[navigation][desktop].'<img src="'.plugins_url('i/ico/desktop.png', __FILE__).'"></div>
				<div id="'.$toolID.'_email_DesignToolSwitch_mobile" onclick="addClassToSwitcher(\''.$toolID.'_email\', \'mobile\', \'pq_active\',[\'desktop\', \'mobile\']);'.$toolName.'sendMailWindowPreview();">'.$this->_dictionary[navigation][mobile].'<img src="'.plugins_url('i/ico/mobile.png', __FILE__).'"></div>
			</div>
			<div class="pq_sett_all">
			<div id="'.$toolID.'_form_email_proceed">
				<div>
					<div class="pq_icons_block">
						<div class="pq_icon">
							<div class="pq_on_off"><p>'.$this->_dictionary[navigation][enable_sendmail].'</p><a href="">?</a>
								<input type="checkbox" class="pq_switch" name="'.$toolName.'[sendMailWindow][enable]" id="'.$toolName.'_email_enable" '.sprintf("%s", (($this->_options[$toolName][sendMailWindow][enable] == "on") ? "checked" : "")).'  onclick="'.$toolName.'sendMailWindowPreview();">
								<label for="'.$toolName.'_email_enable" class="pq_switch_label"></label>
							</div>
						</div>
						<div class="pq_icon" id="'.$toolName.'_sendMail_general_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_sendMail\', \'general\', \'general,heading,text,form,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_general.png', __FILE__).'"><span>general</span></div>
						<div class="pq_icon" id="'.$toolName.'_sendMail_heading_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_sendMail\', \'heading\', \'general,heading,text,form,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_title.png', __FILE__).'"><span>heading</span></div>
						<div class="pq_icon" id="'.$toolName.'_sendMail_text_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_sendMail\', \'text\', \'general,heading,text,form,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_subtitle.png', __FILE__).'"><span>text</span></div>
						<div class="pq_icon" id="'.$toolName.'_sendMail_form_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_sendMail\', \'form\', \'general,heading,text,form,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_form.png', __FILE__).'"><span>form</span></div>
						<div class="pq_icon" id="'.$toolName.'_sendMail_button_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_sendMail\', \'button\', \'general,heading,text,form,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_button.png', __FILE__).'"><span>button</span></div>
						<div class="pq_icon" id="'.$toolName.'_sendMail_close_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_sendMail\', \'close\', \'general,heading,text,form,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_close.png', __FILE__).'"><span>close</span></div>
						<div class="pq_icon" id="'.$toolName.'_sendMail_pro_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_sendMail\', \'pro\', \'general,heading,text,form,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_pro.png', __FILE__).'"><span>pro</span></div>
					</div>
					<div class="pq_settings_block" id="'.$toolName.'_sendMail_form_block">
						<div class="pq_design_settings" id="'.$toolName.'_sendMail_general_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][sendmail_popup].'</h3> <div><h4>'.$this->_dictionary[design_block_name][general].'</h4><a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#general_block" target="_settings_info">?</a></div><a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_sendMail\')"></a>
							'.$this->getFormCodeForTool(
									array(										  
										  "size_window",
										  "popup_form",
										  "background_color",
										  "background_opacity",
										  "animation",
										  "overlay_color",
										  "overlay_opacity"
										), $toolName.'[sendMailWindow]', $this->_options[$toolName][sendMailWindow], 1).'
						</div>
						<div class="pq_design_settings" id="'.$toolName.'_sendMail_heading_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][sendmail_popup].'</h3> <div><h4>'.$this->_dictionary[design_block_name][heading].' </h4><a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#heading_block" target="_settings_info">?</a></div><a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_sendMail\')"></a>
							'.$this->getFormCodeForTool(
									array(										  
										  "title",
										  "head_font",
										  "head_font_size",
										  "head_color"
										), $toolName.'[sendMailWindow]', $this->_options[$toolName][sendMailWindow], 1).'
						</div>
						<div class="pq_design_settings" id="'.$toolName.'_sendMail_text_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][sendmail_popup].'</h3> <div><h4>'.$this->_dictionary[design_block_name][text_block].' </h4><a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#text_block" target="_settings_info">?</a></div><a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_sendMail\')"></a>
							'.$this->getFormCodeForTool(
									array(										  
										  "sub_title",
										  "text_font",
										  "font_size",
										  "text_color"
										), $toolName.'[sendMailWindow]', $this->_options[$toolName][sendMailWindow], 1).'
						</div>
						<div class="pq_design_settings" id="'.$toolName.'_sendMail_form_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][sendmail_popup].'</h3> <div><h4>'.$this->_dictionary[design_block_name][form_block].' </h4><a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#form_block" target="_settings_info">?</a></div><a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_sendMail\')"></a>
							'.$this->getFormCodeForTool(
									array(										  
										  "enter_email_text",
										  "enter_name_text",  										  
										  "enter_subject_text",  
										  "enter_message_text",
										  "background_form_block",
										  "form_block_padding"
										), $toolName.'[sendMailWindow]', $this->_options[$toolName][sendMailWindow], 1).'
						</div>
						<div class="pq_design_settings" id="'.$toolName.'_sendMail_button_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][sendmail_popup].'</h3><div><h4>'.$this->_dictionary[design_block_name][button_block].' </h4><a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#button_block" target="_settings_info">?</a></div> <a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_sendMail\')"></a>
							'.$this->getFormCodeForTool(
									array(
										  "button_type",
										  "button_text",
										  "button_font",
										  "button_font_size",
										  "button_text_color",
										  "button_color",		
										  "background_button_block",
										  "button_block_padding"
										), $toolName.'[sendMailWindow]', $this->_options[$toolName][sendMailWindow], 1).'
						</div>
						<div class="pq_design_settings" id="'.$toolName.'_sendMail_close_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][sendmail_popup].'</h3><div><h4>'.$this->_dictionary[design_block_name][close_block].' </h4><a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#close_block" target="_settings_info">?</a></div><a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_sendMail\')"></a>
							'.$this->getFormCodeForTool(
									array(														  
										  "close_icon_type", 
										  "close_text",
										  "close_text_font",
										  "close_icon_color",
										  "close_icon_animation"
										), $toolName.'[sendMailWindow]', $this->_options[$toolName][sendMailWindow], 1).'
						</div>																							
						
						<div class="pq_design_settings" id="'.$toolName.'_sendMail_pro_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][sendmail_popup].'</h3><div><h4>'.$this->_dictionary[design_block_name][pro_block].' </h4><a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#pro_block" target="_settings_info">?</a></div><a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_sendMail\')"></a>
							'.$this->getFormCodeForTool(
									array(
										'header_image_src',
										 'header_img_type',												
										'background_image_src',
										'overlay_image_src',
										'showup_animation'
										), $toolName.'[sendMailWindow]', $this->_options[$toolName][sendMailWindow], 1).'
						</div>
					</div>
				</div>
			</div>
			</div>
		</div>
		';		
		return $ret;
	}
	
	function generateThankHTML($toolName, $toolID){
		$ret = '		
			<div id="'.$toolID.'_form_thank" style="display:none;" class="pq_design">
			<div class="pq_desktop_mobile">
				<div id="'.$toolID.'_thank_DesignToolSwitch_desktop" onclick="addClassToSwitcher(\''.$toolID.'_thank\', \'desktop\', \'pq_active\',[\'desktop\', \'mobile\']);'.$toolName.'thankPreview();" class="pq_active" >'.$this->_dictionary[navigation][desktop].'<img src="'.plugins_url('i/ico/desktop.png', __FILE__).'"></div>
				<div id="'.$toolID.'_thank_DesignToolSwitch_mobile" onclick="addClassToSwitcher(\''.$toolID.'_thank\', \'mobile\', \'pq_active\',[\'desktop\', \'mobile\']);'.$toolName.'thankPreview();">'.$this->_dictionary[navigation][mobile].'<img src="'.plugins_url('i/ico/mobile.png', __FILE__).'"></div>
			</div>
			<div class="pq_sett_all">
				<div id="'.$toolID.'_form_thank_proceed">
					<div>
												
						<div class="pq_icons_block">
							<div class="pq_icon">
								<div class="pq_on_off"><p>'.$this->_dictionary[navigation][enable_thank].'</p>
									<input type="checkbox" class="pq_switch" name="'.$toolName.'[thank][enable]" id="'.$toolName.'_thank_enable" '.sprintf("%s", (($this->_options[$toolName][thank][enable] == "on") ? "checked" : "")).' onclick="'.$toolName.'thankPreview()">
									<label for="'.$toolName.'_thank_enable" class="pq_switch_label"></label>
								</div>
							</div>
							<div class="pq_icon" id="'.$toolName.'_thank_general_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_thank\', \'general\', \'general,heading,text,icons,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_general.png', __FILE__).'"><span>general</span></div>
							<div class="pq_icon" id="'.$toolName.'_thank_heading_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_thank\', \'heading\', \'general,heading,text,icons,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_title.png', __FILE__).'"><span>heading</span></div>
							<div class="pq_icon" id="'.$toolName.'_thank_text_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_thank\', \'text\', \'general,heading,text,icons,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_subtitle.png', __FILE__).'"><span>text</span></div>
							<div class="pq_icon" id="'.$toolName.'_thank_icons_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_thank\', \'icons\', \'general,heading,text,icons,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_icon.png', __FILE__).'"><span>icons</span></div>
							<div class="pq_icon" id="'.$toolName.'_thank_button_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_thank\', \'button\', \'general,heading,text,icons,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_button.png', __FILE__).'"><span>button</span></div>
							<div class="pq_icon" id="'.$toolName.'_thank_close_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_thank\', \'close\', \'general,heading,text,icons,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_close.png', __FILE__).'"><span>close</span></div>
							<div class="pq_icon" id="'.$toolName.'_thank_pro_design_icons_block" onclick="enableDesignFormByBlockClick(\''.$toolName.'_thank\', \'pro\', \'general,heading,text,icons,button,close,pro\')"><img src="'.plugins_url('i/ico/setting_pro.png', __FILE__).'"><span>PRO</span></div>
							
						</div>
						<div class="pq_settings_block" id="'.$toolName.'_thank_form_block">
								<div class="pq_design_settings" id="'.$toolName.'_thank_general_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][success_tool].'</h3><div><h4>'.$this->_dictionary[design_block_name][general].' </h4><a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#general_block" target="_settings_info">?</a></div><a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_thank\')"></a>
									'.$this->getFormCodeForTool(array("size_window","popup_form","background_color","background_opacity","border_type","border_depth","border_color","animation","overlay_color","overlay_opacity"), $toolName.'[thank]', $this->_options[$toolName][thank], 1).'
								</div>
								<div class="pq_design_settings" id="'.$toolName.'_thank_heading_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][success_tool].'</h3><div><h4>'.$this->_dictionary[design_block_name][heading].' </h4><a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#heading_block" target="_settings_info">?</a></div><a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_thank\')"></a>
									'.$this->getFormCodeForTool(array("title","head_font","head_font_size","head_color"), $toolName.'[thank]', $this->_options[$toolName][thank], 1).'
								</div>
								<div class="pq_design_settings" id="'.$toolName.'_thank_text_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][success_tool].'</h3> <div><h4>'.$this->_dictionary[design_block_name][text_block].' </h4><a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#text_block" target="_settings_info">?</a> </div><a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_thank\')"></a>
									'.$this->getFormCodeForTool(array("sub_title","text_font","font_size","text_color"), $toolName.'[thank]', $this->_options[$toolName][thank], 1).'
								</div>
								<div class="pq_design_settings" id="'.$toolName.'_thank_icons_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][success_tool].'</h3><div><h4>'.$this->_dictionary[design_block_name][soc_icons_block].' </h4><a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#icons_block" target="_settings_info">?</a></div><a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_thank\')"></a>
									<div>
									<p>'.$this->_dictionary[thankPopupSettings][social_icon_type].'</p>
									<select name="'.$toolName.'[thank][socnet_block_type]" id="'.$toolName.'_thank_socnet_block_type" onchange="enableAdditionalBlock(this.value, \''.$toolID.'_thank_socnetIconsBlock_\',[\'follow\', \'share\']);">
										<option value="" selected>None</option>
										<option value="follow" '.sprintf("%s", (($this->_options[$toolName][thank][socnet_block_type] == "follow" ) ? "selected" : "")).'>Follow action</option>
										<option value="share"  '.sprintf("%s", (($this->_options[$toolName][thank][socnet_block_type] == "share" ) ? "selected" : "")).'>Share action</option>
									</select>
									</div>
									
									<div id="'.$toolID.'_thank_socnetIconsBlock_follow" style="display:none">
										'.$this->getFollowIcons($toolName.'[thank]', $this->_options[$toolName][thank]).'
									</div>
									<br>
									<div id="'.$toolID.'_thank_socnetIconsBlock_share" style="display:none">
										'.$this->getSharingIcons($toolName.'[thank]', $this->_options[$toolName][thank][socnet_with_pos], array("socnet_with_pos_error" => $sharingSocnetErrorArray[$toolName][thank]), 1).'
									</div>
									'.$this->getFormCodeForTool(array("background_soc_block","icon_block_padding","design_icons","form_icons","size_icons","space_icons","animation_icons"), $toolName.'[thank]', $this->_options[$toolName][thank], 1).'
									<script>
										enableAdditionalBlock(\''.$this->_options[$toolName][thank][socnet_block_type].'\', \''.$toolID.'_thank_socnetIconsBlock_\',[\'follow\', \'share\']);
									</script>
								</div>
								<div class="pq_design_settings" id="'.$toolName.'_thank_button_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][success_tool].'</h3><div><h4>'.$this->_dictionary[design_block_name][button_block].' </h4><a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#button_block" target="_settings_info">?</a></div><a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_thank\')"></a>
									<p>'.$this->_dictionary[thankPopupSettings][button_action_type].'</p>
									<select name="'.$toolName.'[thank][buttonBlock][type]" id="'.$toolName.'_thank_type" onchange="enableAdditionalBlock(this.value, \''.$toolID.'_thank_\', [\'redirect\']);'.$toolName.'thankPreview();">
										<option value="" selected>'.$this->_dictionary[thankPopupSettings][button_action_type_0].'</option>
										<option value="redirect" '.sprintf("%s", (($this->_options[$toolName][thank][buttonBlock][type] == "redirect" ) ? "selected" : "")).' >'.$this->_dictionary[thankPopupSettings][button_action_type_1].'</option>
									</select>
									<br>
									<div id="'.$toolID.'_thank_redirect" style="display:none">
										'.$this->getFormCodeForTool(array("url","button_type","button_text","button_font","button_font_size","button_text_color","button_color", "background_button_block", "button_block_padding"), $toolName.'[thank]', $this->_options[$toolName][thank], 1).'
									</div>
									<script>
										enableAdditionalBlock(\''.$this->_options[$toolName][thank][buttonBlock][type].'\', \''.$toolID.'_thank_\', [\'redirect\']);
									</script>
								</div>
								<div class="pq_design_settings" id="'.$toolName.'_thank_close_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][success_tool].'</h3><div><h4>'.$this->_dictionary[design_block_name][close_block].'</h4> <a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#close_block" target="_settings_info">?</a></div><a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_thank\')"></a>
									'.$this->getFormCodeForTool(array("close_icon_type", "close_text","close_text_font","close_icon_color",	"close_icon_animation"), $toolName.'[thank]', $this->_options[$toolName][thank], 1).'									
								</div>
								<div class="pq_design_settings" id="'.$toolName.'_thank_pro_design_options_block" style="display:none;"><h3>'.$this->_dictionary[navigation][success_tool].'</h3><div><h4>'.$this->_dictionary[design_block_name][pro_block].'</h4> <a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#pro_block" target="_settings_info">?</a></div><a class="pq_settings_close" onclick="closeDesignForm(\''.$toolName.'_thank\')"></a>
									'.$this->getFormCodeForTool(array('header_image_src',
												'header_img_type',												
												'background_image_src',												
												'overlay_image_src',
												'showup_animation'), $toolName.'[thank]', $this->_options[$toolName][thank], 1).'									
								</div>
							</div>
						</div>
					</div>
				</div>
										
		</div>';
		return $ret;
	}
	
	
	function activatePluginVersion(){
		$this->_options[pluginRegistration] = 1;
		
		if(!isset($this->_options[settings][pro_loader_filename])){
			$this->_options[settings][pro_loader_filename] = $this->getDomain().'.pq_pro_loader';
		}
		if(!isset($this->_options[settings][mainPage])){
			if($_SERVER[HTTPS] == 'on'){
				$this->_options[settings][mainPage] = 'https://'.$this->getFullDomain();
			}else{
				$this->_options[settings][mainPage] = 'http://'.$this->getFullDomain();;
			}
		}
		if(!isset($this->_options[settings][email])){
			$this->_options[settings][email] = get_settings('admin_email');
		}
		if(!isset($this->_options[settings][enableGA])){
			$this->_options[settings][enableGA] = 'on';
		}
				

		update_option('profitquery', $this->_options);
	}
	
	function getDefaultToolPosition(){
		$ret = array(
			"all" => array("BAR_TOP"=>1,"BAR_BOTTOM"=>1,"SIDE_LEFT_TOP"=>1,"SIDE_LEFT_MIDDLE"=>1,"SIDE_LEFT_BOTTOM"=>1,"SIDE_RIGHT_TOP"=>1,"SIDE_RIGHT_MIDDLE"=>1,"SIDE_RIGHT_BOTTOM"=>1,"CENTER"=>1,"FLOATING_LEFT_TOP"=>1,"FLOATING_LEFT_BOTTOM"=>1,"FLOATING_RIGHT_TOP"=>1,"FLOATING_RIGHT_BOTTOM"=>1),
			"sharingSidebar" => array("SIDE_LEFT_TOP"=>1,"SIDE_LEFT_MIDDLE"=>1,"SIDE_LEFT_BOTTOM"=>1,"SIDE_RIGHT_TOP"=>1,"SIDE_RIGHT_MIDDLE"=>1,"SIDE_RIGHT_BOTTOM"=>1),
			"emailListBuilderPopup" => array("CENTER"=>1),
			"emailListBuilderFloating" => array("FLOATING_LEFT_TOP"=>1,"FLOATING_LEFT_BOTTOM"=>1,"FLOATING_RIGHT_TOP"=>1,"FLOATING_RIGHT_BOTTOM"=>1),
			"emailListBuilderBar" => array("BAR_TOP"=>1,"BAR_BOTTOM"=>1),
			"sharingBar" => array("BAR_TOP"=>1,"BAR_BOTTOM"=>1),
			"sharingPopup" => array("CENTER"=>1),
			"contactFormCenter" => array("CENTER"=>1),
			"contactFormPopup" => array("SIDE_LEFT_TOP"=>1,"SIDE_LEFT_MIDDLE"=>1,"SIDE_LEFT_BOTTOM"=>1,"SIDE_RIGHT_TOP"=>1,"SIDE_RIGHT_MIDDLE"=>1,"SIDE_RIGHT_BOTTOM"=>1),
			"contactFormFloating" => array("FLOATING_LEFT_TOP"=>1,"FLOATING_LEFT_BOTTOM"=>1,"FLOATING_RIGHT_TOP"=>1,"FLOATING_RIGHT_BOTTOM"=>1),
			"sharingFloating" => array("FLOATING_LEFT_TOP"=>1,"FLOATING_LEFT_BOTTOM"=>1,"FLOATING_RIGHT_TOP"=>1,"FLOATING_RIGHT_BOTTOM"=>1),
			"promotePopup" => array("CENTER"=>1),
			"promoteBar" => array("BAR_TOP"=>1,"BAR_BOTTOM"=>1),
			"promoteFloating" => array("FLOATING_LEFT_TOP"=>1,"FLOATING_LEFT_BOTTOM"=>1,"FLOATING_RIGHT_TOP"=>1,"FLOATING_RIGHT_BOTTOM"=>1),
			"callMePopup" => array("SIDE_LEFT_TOP"=>1,"SIDE_LEFT_MIDDLE"=>1,"SIDE_LEFT_BOTTOM"=>1,"SIDE_RIGHT_TOP"=>1,"SIDE_RIGHT_MIDDLE"=>1,"SIDE_RIGHT_BOTTOM"=>1),
			"callMeFloating" => array("FLOATING_LEFT_TOP"=>1,"FLOATING_LEFT_BOTTOM"=>1,"FLOATING_RIGHT_TOP"=>1,"FLOATING_RIGHT_BOTTOM"=>1),
			"followPopup" => array("CENTER"=>1),
			"sharingFloating" => array("FLOATING_LEFT_TOP"=>1,"FLOATING_LEFT_BOTTOM"=>1,"FLOATING_RIGHT_TOP"=>1,"FLOATING_RIGHT_BOTTOM"=>1),
			"followBar" => array("BAR_TOP"=>1,"BAR_BOTTOM"=>1),
			"followFloating" => array("FLOATING_LEFT_TOP"=>1,"FLOATING_LEFT_BOTTOM"=>1,"FLOATING_RIGHT_TOP"=>1,"FLOATING_RIGHT_BOTTOM"=>1),
			"iframeFloating" => array("FLOATING_LEFT_TOP"=>1,"FLOATING_LEFT_BOTTOM"=>1,"FLOATING_RIGHT_TOP"=>1,"FLOATING_RIGHT_BOTTOM"=>1),
			"youtubeFloating" => array("FLOATING_LEFT_TOP"=>1,"FLOATING_LEFT_BOTTOM"=>1,"FLOATING_RIGHT_TOP"=>1,"FLOATING_RIGHT_BOTTOM"=>1),
			"iframePopup" => array("CENTER"=>1),
			"youtubePopup" => array("CENTER"=>1)
		);
		return $ret;
	}
	
	function getArrayPosition($array){
		$ret = array();		
		foreach((array)$this->_options as $k => $v){
			if((string)$v[enable] == 'on')
			{
				if($v[position]){
					$ret[desktop][$v[position]][] = array('code'=>$k,'info'=>$array[$k][name], 'eventHandler'=>$array[$k][eventHandler], 'position'=>$v[position]);
				}
				
				/**************MOBILE********************/
				//if work on mobile				
				if((string)$v[displayRules][work_on_mobile] == 'on'){					
					if($v[position] == 'CENTER'){
						$ret[mobile]['CENTER'][] = array('code'=>$k,'info'=>$array[$k][name], 'eventHandler'=>$array[$k][eventHandler], 'position'=>$v[position]);
					}elseif(strstr($v[position], 'FLOATING')){
						if(strstr($v[mobile_position], 'TOP')){
							$ret[mobile]['FLOATING_TOP'][] = array('code'=>$k,'info'=>$array[$k][name], 'eventHandler'=>$array[$k][eventHandler], 'position'=>$v[position]);
						}else{
							$ret[mobile]['FLOATING_BOTTOM'][] = array('code'=>$k,'info'=>$array[$k][name], 'eventHandler'=>$array[$k][eventHandler], 'position'=>$v[position]);
						}
					}else{
						if(strstr($k,'Bar') || strstr($k,'bar')){
							if(strstr($v[mobile_position], '_bottom')){
								$ret[mobile]['BAR_BOTTOM'][] = array('code'=>$k,'info'=>$array[$k][name], 'eventHandler'=>$array[$k][eventHandler], 'position'=>$v[position]);							
							}else{
								$ret[mobile]['BAR_TOP'][] = array('code'=>$k,'info'=>$array[$k][name], 'eventHandler'=>$array[$k][eventHandler], 'position'=>$v[position]);
							}
						}						
					}
				}
			}
		}
		return $ret;
	}
	
	function _getToolProOptions($name){
		$ret = '';		
		if((float)$this->_themes[$name][$this->_options[$name][theme]][price] > 0){			
			$ret[pro] = 1;
			$ret[price] = $this->_plugin_settings[price][pro_12]/12;			
		}		
		return $ret;
	}
	
	function _getToolName($name){
		$ret = '';
		if($name == 'sharingSidebar'){
			$ret[name] = $this->_dictionary[toolsGroup][share_buttons];
			$ret[type] = $this->_dictionary[toolNameShort][sidebar];
			$ret[icon] = 'ico_sharing_sidebar.png';
		}
		if($name == 'imageSharer'){
			$ret[name] = $this->_dictionary[toolsGroup][share_buttons];
			$ret[type] = $this->_dictionary[toolNameShort][image_sharer];
			$ret[icon] = 'ico_image_sharer.png';
		}

		if($name == 'sharingPopup'){
			$ret[name] = $this->_dictionary[toolsGroup][share_buttons];
			$ret[type] = $this->_dictionary[toolNameShort][center_popup];
			$ret[icon] = 'ico_sharing_popup.png';
		}

		if($name == 'sharingBar'){
			$ret[name] = $this->_dictionary[toolsGroup][share_buttons];
			$ret[type] = $this->_dictionary[toolNameShort][bar];
			$ret[icon] = 'ico_sharing_bar.png';
		}

		if($name == 'sharingFloating'){
			$ret[name] = $this->_dictionary[toolsGroup][share_buttons];
			$ret[type] = $this->_dictionary[toolNameShort][corner_popup];
			$ret[icon] = 'ico_sharing_floating.png';
		}

		if($name == 'emailListBuilderPopup'){
			$ret[name] = $this->_dictionary[toolsGroup][email_list_builder];
			$ret[type] = $this->_dictionary[toolNameShort][center_popup];
			$ret[icon] = 'ico_collect_email_popup.png';
		}
		

		if($name == 'emailListBuilderBar'){
			$ret[name] = $this->_dictionary[toolsGroup][email_list_builder];
			$ret[type] = $this->_dictionary[toolNameShort][bar];
			$ret[icon] = 'ico_collect_email_bar.png';
		}

		if($name == 'emailListBuilderFloating'){
			$ret[name] = $this->_dictionary[toolsGroup][email_list_builder];
			$ret[type] = $this->_dictionary[toolNameShort][corner_popup];
			$ret[icon] = 'ico_collect_email_floating.png';
		}

		if($name == 'contactFormPopup'){
			$ret[name] = $this->_dictionary[toolsGroup][contact_form];
			$ret[type] = $this->_dictionary[toolNameShort][bookmark_popup];
			$ret[icon] = 'ico_contact_form_popup.png';
		}
		
		if($name == 'contactFormCenter'){
			$ret[name] = $this->_dictionary[toolsGroup][contact_form];
			$ret[type] = $this->_dictionary[toolNameShort][center_popup];
			$ret[icon] = 'ico_contact_form_center.png';
		}

		if($name == 'contactFormFloating'){
			$ret[name] = $this->_dictionary[toolsGroup][contact_form];
			$ret[type] = $this->_dictionary[toolNameShort][corner_popup];
			$ret[icon] = 'ico_contact_form_floating.png';
		}

		if($name == 'promotePopup'){
			$ret[name] = $this->_dictionary[toolsGroup][promotion_tools];
			$ret[type] = $this->_dictionary[toolNameShort][center_popup];
			$ret[icon] = 'ico_promote_popup.png';
		}

		if($name == 'promoteBar'){
			$ret[name] = $this->_dictionary[toolsGroup][promotion_tools];
			$ret[type] = $this->_dictionary[toolNameShort][bar];
			$ret[icon] = 'ico_promote_bar.png';
		}
		
		

		if($name == 'promoteFloating'){
			$ret[name] = $this->_dictionary[toolsGroup][promotion_tools];
			$ret[type] = $this->_dictionary[toolNameShort][corner_popup];
			$ret[icon] = 'ico_promote_floating.png';
		}

		if($name == 'callMePopup'){
			$ret[name] = $this->_dictionary[toolsGroup][call_me];
			$ret[type] = $this->_dictionary[toolNameShort][bookmark_popup];
			$ret[icon] = 'ico_call_me_popup.png';
		}

		if($name == 'callMeFloating'){
			$ret[name] = $this->_dictionary[toolsGroup][call_me];
			$ret[type] = $this->_dictionary[toolNameShort][corner_popup];
			$ret[icon] = 'ico_call_me_floating.png';
		}

		if($name == 'followPopup'){
			$ret[name] = $this->_dictionary[toolsGroup][follow_buttons];
			$ret[type] = $this->_dictionary[toolNameShort][center_popup];
			$ret[icon] = 'ico_follow_popup.png';
		}

		if($name == 'followBar'){
			$ret[name] = $this->_dictionary[toolsGroup][follow_buttons];
			$ret[type] = $this->_dictionary[toolNameShort][bar];
			$ret[icon] = 'ico_follow_bar.png';
		}

		if($name == 'followFloating'){
			$ret[name] = $this->_dictionary[toolsGroup][follow_buttons];
			$ret[type] = $this->_dictionary[toolNameShort][corner_popup];
			$ret[icon] = 'ico_follow_floating.png';
		}

		if($name == 'iframePopup'){
			$ret[name] = $this->_dictionary[toolsGroup][iframe_embed];
			$ret[type] = $this->_dictionary[toolNameShort][center_popup];
			$ret[icon] = 'ico_iframe_popup.png';
		}
		if($name == 'iframeFloating'){
			$ret[name] = $this->_dictionary[toolsGroup][iframe_embed];
			$ret[type] = $this->_dictionary[toolNameShort][corner_popup];
			$ret[icon] = 'ico_iframe_floating.png';
		}

		if($name == 'youtubePopup'){
			$ret[name] = $this->_dictionary[toolsGroup][youtube_embed];
			$ret[type] = $this->_dictionary[toolNameShort][center_popup];
			$ret[icon] = 'ico_youtube_popup.png';
		}
		
		if($name == 'youtubeFloating'){
			$ret[name] = $this->_dictionary[toolsGroup][youtube_embed];
			$ret[type] = $this->_dictionary[toolNameShort][corner_popup];
			$ret[icon] = 'ico_youtube_floating.png';
		}
		return $ret;
	}
	
	function _getToolEH($name){
		$ret = '';
		if($this->_options[$name][eventHandler][type] == 'delay'){
			$ret[name] = $this->_dictionary[eventHandlerBlock][delay_text];
			$ret[type] = 'delay';
			$ret[value] = $this->_options[$name][eventHandler][delay_value]." ".$this->_dictionary[eventHandlerBlock][delay_unit];
		}
		if($this->_options[$name][eventHandler][type] == 'scrolling'){
			$ret[name] = $this->_dictionary[eventHandlerBlock][scrolling_text];
			$ret[type] = 'scrolling';
			$ret[value] = $this->_options[$name][eventHandler][scrolling_value]." %";
		}
		if($this->_options[$name][eventHandler][type] == 'exit'){
			$ret[name] = $this->_dictionary[eventHandlerBlock][exit_text];			
			$ret[type] = 'exit';
		}
		return $ret;
	}
	
	function _getTimestampDiff($time){
		$ret = '';		
		$days = floor(($time)/(60*60*24));
		$hours = floor(($time-$month*60*60*24*30-$days*60*60*24)/(60*60));
				
		$ret[days] = $days;
		$ret[hours] = $hours;
		$ret[tstamp] = $time;
		return $ret;
	}
	
	function _existProOptions($name){
		$ret[status] = 0;
		
		if($this->_pro_options[pro]){			
			$ret[status] = 1;
			$ret[diff] = $this->_getTimestampDiff(strtotime($this->_pro_options[pro][till]) - time());
			$ret[dend] = strtotime($this->_pro_options[pro][till]);			
		}		
			
		return $ret;
	}
	
	function _trialOptions(){
		$ret[status] = 0;		
		if($this->_pro_options[trial][till]){			
			if(strtotime($this->_pro_options[trial][till]) > time()){
				$ret[diff] = $this->_getTimestampDiff(strtotime($this->_pro_options[trial][till]) - time());
				$ret[dend] = strtotime($this->_pro_options[trial][till]);
				$ret[status] = 1;				
			}
		}		
		return $ret;
	}
	
	function _proToolsOptions(){
		$ret[status] = 0;
		
		if($this->_pro_options[pro][till]){			
			if(strtotime($this->_pro_options[pro][till]) > time()){
				$ret[diff] = $this->_getTimestampDiff(strtotime($this->_pro_options[pro][till]) - time());
				$ret[dend] = strtotime($this->_pro_options[pro][till]);
				$ret[status] = 1;				
			}
		}		
		return $ret;
	}
	
	function _affiliateOptions(){
		$ret[status] = 0;		
		if($this->_pro_options[affiliate][till]){			
			if(strtotime($this->_pro_options[affiliate][till]) > time()){
				$ret[diff] = $this->_getTimestampDiff(strtotime($this->_pro_options[affiliate][till]) - time());
				$ret[dend] = strtotime($this->_pro_options[affiliate][till]);
				$ret[status] = 1;				
			}
		}		
		return $ret;
	}
	
	function _getAttentionBlock($diff){
		$ret = '';
		if((int)$diff[days] > 3){
			$ret = 'green';
		}else if((int)$diff[days]>=0 && (int)$diff[days]<=3 && (int)$diff[tstamp] > 0){
			$ret = 'yellow';
		}else{
			$ret = 'red';
		}
		return $ret;
	}
	
	function checkSelectedProOptions($name){
		$flag = 0;
		$trialOptions = $this->_trialOptions();
		$affiliateOptions = $this->_affiliateOptions();		
		$existProOptions = $this->_existProOptions($name);		
		$ret='';		
		
		if($existProOptions[status] && $existProOptions[diff][tstamp]){
			$array[status]='paid';
			$array[status_text]=$this->_dictionary[toolsStatus][paid];
			$array[diff]=$existProOptions[diff];
			$array[dend]=$existProOptions[dend];
			$array[price]=$this->_plugin_settings[price][pro_12];
			$array[attentionBlock] = $this->_getAttentionBlock($existProOptions[diff]);
		}else if($affiliateOptions[status] && $affiliateOptions[diff][tstamp]){
			$array[status]='affiliate';
			$array[status_text]=$this->_dictionary[toolsStatus][affiliate];
			$array[diff]=$affiliateOptions[diff];
			$array[dend]=$affiliateOptions[dend];
			$array[price]=$this->_plugin_settings[price][pro_12];
			$array[attentionBlock] = $this->_getAttentionBlock($affiliateOptions[diff]);
		//check trial
		}else if($trialOptions[status] && $trialOptions[diff][tstamp]){
			$array[status]='trial';
			$array[status_text]=$this->_dictionary[toolsStatus][trial];
			$array[diff]=$trialOptions[diff];
			$array[dend]=$trialOptions[dend];
			$array[price]=$this->_plugin_settings[price][pro_12];
			$array[attentionBlock] = $this->_getAttentionBlock($trialOptions[diff]);
		}else{
			$array[status]='paused';
			$array[status_text]=$this->_dictionary[toolsStatus][paused];
			$array[price]=$this->_plugin_settings[price][pro_12];
		}
				
		//get result		
		if($array[status] == 'paused'){
			$ret[status] = 'paused';
			$ret[status_text] = $this->_dictionary[toolsStatus][paused];
			$ret[dateColumn] = '-';
			$ret[styleBlock] = 'red';
			$ret[action] = 'activate';			
		}
		if($array[status] == 'trial'){
			$ret[status] = 'trial';
			$ret[status_text] = $this->_dictionary[toolsStatus][trial];
			$ret[dateColumn] = $array[diff][days].' '.$this->_dictionary[other][left];
			$ret[styleBlock] = $this->_getAttentionBlock($array[diff]);
			$ret[action] = 'activate';			
		}
		if($array[status] == 'affiliate'){
			$ret[status] = 'affiliate';
			$ret[status_text] = $this->_dictionary[toolsStatus][affiliate];
			$ret[dateColumn] = $array[diff][days].' '.$this->_dictionary[other][left];
			$ret[styleBlock] = $this->_getAttentionBlock($array[diff]);
			$ret[action] = 'activate';			
		}			
		if($array[status] == 'paid'){																
			$ret[styleBlock] = $this->_getAttentionBlock($array[diff]);			
			if($ret[styleBlock] == 'red'){
				$ret[status] = 'paused';
				$ret[status_text] = $this->_dictionary[toolsStatus][paused];
				$ret[action] = 'activate';
				$ret[dateColumn] = '';
			}else if($ret[styleBlock] == 'yellow'){
				$ret[status] = 'active';
				$ret[status_text] = $this->_dictionary[toolsStatus][active];
				$ret[action] = 'extend';
				if((int)$array[diff][days] > 0){
					$ret[dateColumn] = $array[diff][days].' D left';
				}else{
					$ret[dateColumn] = $array[diff][hours].' H left';
				}
			}else{
				$ret[status] = 'active';
				$ret[status_text] = $this->_dictionary[toolsStatus][active];
				$ret[action] = '';
				$ret[dateColumn] = date('d/m', $array[dend]);
			}			
		}
		
		
		$return[status] = $ret;
		$return[proOptionsDetail] = $array;					
		return $return;
	}
	
	function _getToolStatus($name, $proExist){			
		//Free		
		if(!$proExist){
			$ret[status][status] = 'free';
			$ret[status][status_text] = $this->_dictionary[toolsStatus][active];			
			$ret[status][dateColumn] = '-';		
			$ret[status][styleBlock] = 'green';		
			$ret[status][action] = '';		
			$ret[status][proOptionsDetail] = array();
		}else{						
			$ret = $this->checkSelectedProOptions($name);			
		}			
		return $ret;
	}
		
	
	function getToolInfo($name){
		$ret = '';				
		$ret[name] =  $this->_getToolName($name);		
		$ret[eventHandler] = (array)$this->_getToolEH($name);
		$toolsStatus = $this->_getToolStatus($name, $this->_getToolProOptions($name));
		$ret[status] = $toolsStatus[status];
		$ret[proOptionsDetail] = (array)$toolsStatus[proOptionsDetail];		
				
		return $ret;
	}	
		
	
	function getToolsArray(){
		$ret = array();
		
		foreach((array)$this->_toolsName as $k => $v){				
			if((string)$this->_options[$v][enable] == 'on'){				
				$ret[$v][enable] = 1;				
			}else{				
				$ret[$v][enable] = 0;	
			}
		}
		
		//get tool settings
		foreach((array)$ret as $name => $data){
			$ret[$name] = $this->getToolInfo($name);
			$ret[$name][enable] = (int)$data[enable];
			if($this->_options[$name][thank][enable] == 'on'){
				$ret[$name][thank] = 1;
			}else{
				$ret[$name][thank] = 0;
			}
			
		}		
		return $ret;
	}
	
	function getDefaultThemes(){
		foreach((array)$this->_themes as $tool => $array){
			foreach((array)$array as $theme => $data){
				if(!$this->_default_themes[$tool] && (int)$data[price] == 0){
					$this->_default_themes[$tool]['theme'] = $theme;
					$this->_default_themes[$tool]['addClass'] = $data['addClass'];
					break;
				}
			}
		}				
	}
	
	function _getColor($str){
		$ret = '';
		if(strstr($str, 'PQCC')){
			$arr = explode('PQCC', trim($str));
			$ret = $arr[1];
		}		
		return $ret;
	}	
	
	function rgb2hex($rgb) {
	   $hex = "";
	   $hex .= str_pad(dechex($rgb[0]), 2, "0", STR_PAD_LEFT);
	   $hex .= str_pad(dechex($rgb[1]), 2, "0", STR_PAD_LEFT);
	   $hex .= str_pad(dechex($rgb[2]), 2, "0", STR_PAD_LEFT);

	   return $hex;
	}
	
	function _getTextThemesParams($str){		
		$textGroupName = '';
		$ret = array();		
		foreach((array)$this->_themes_text as $name => $data){			
			if(strstr($str, $name)){
				$textGroupName = $name;
				break;
			}
		}
		if($textGroupName){
			$str = str_replace($textGroupName, '', $str);
			$arr = explode('_', $str);			
			foreach((array)$arr as $k => $v){
				if(trim($v)){
					if($v == 't' && $this->_themes_text[$textGroupName]['_title']) $ret['_title'] = $this->_themes_text[$textGroupName]['_title'];
					if($v == 'st' && $this->_themes_text[$textGroupName]['_subtitle']) $ret['_subtitle'] = $this->_themes_text[$textGroupName]['_subtitle'];
					if($v == 'pt' && $this->_themes_text[$textGroupName]['_tblock_text']) $ret['_tblock_text'] = $this->_themes_text[$textGroupName]['_tblock_text'];
					if($v == 'bt' && $this->_themes_text[$textGroupName]['_button_text']) $ret['_button_text'] = $this->_themes_text[$textGroupName]['_button_text'];
					if($v == 'ct' && $this->_themes_text[$textGroupName]['_close_text']) $ret['_close_text'] = $this->_themes_text[$textGroupName]['_close_text'];
				}
			}			
		}
		return $ret;
	}
	
	function _parseDesignOptions($str, $type){
		$ret = array();
		$str = str_replace("  "," ", $str);
		$str = str_replace("  "," ", $str);
		$array = explode(' ', $str);		
		
		
		$structure['main']['_background_color'] = "pq_bg_bgcolor_PQCC";
		$structure['main']['_bookmark_background_color'] = "pq_bmbgcolor_PQCC";
		$structure['main']['_bookmark_text_color'] = "pq_bmtextcolor_PQCC";
		$structure['main']['_bookmark_text_font'] = "pq_bmtextfont_h_";
		$structure['main']['_bookmark_text_size'] = array("pq_bm_text_size12" => "Size - 12","pq_bm_text_size16" => "Size - 16","pq_bm_text_size18" => "Size - 18","pq_bm_text_size20" => "Size - 20","pq_bm_text_size24" => "Size - 24","pq_bm_text_size28" => "Size - 28","pq_bm_text_size30" => "Size - 30","pq_bm_text_size32" => "Size - 32","pq_bm_text_size34" => "Size - 34","pq_bm_text_size36" => "Size - 36","pq_bm_text_size42" => "Size - 42","pq_bm_text_size48" => "Size - 48");
		$structure['main']['_background_color_rgba'] = "pq_bg_bgcolor_PQRGBA_";
		$structure['main']['_background_button_block'] = "pq_btngbg_bgcolor_btngroup_PQCC";
		$structure['main']['_background_text_block'] = "pq_bgtxt_bgcolor_bgtxt_PQCC";
		$structure['main']['_background_form_block'] = "pq_formbg_bgcolor_formbg_PQCC";
		$structure['main']['_background_soc_block'] = "pq_bgsocblock_bgcolor_bgsocblock_PQCC";
		$structure['main']['_overlay_color'] = "pq_over_bgcolor_PQCC";
		$structure['main']['_overlay_opacity'] = array("pq_overlay_0"=>"Opacity 0%","pq_overlay_10"=>"Opacity 10%","pq_overlay_20"=>"Opacity 20%","pq_overlay_30"=>"Opacity 30%","pq_overlay_40"=>"Opacity 40%","pq_overlay_50"=>"Opacity 50%","pq_overlay_60"=>"Opacity 60%","pq_overlay_70"=>"Opacity 70%","pq_overlay_80"=>"Opacity 80%","pq_overlay_90"=>"Opacity 90%","pq_overlay_100"=>"Opacity 100%");
		$structure['main']['_button_text_color'] = "pq_btn_color_btngroupbtn_PQCC";
		$structure['main']['_button_color'] = "pq_btn_bg_bgcolor_btngroupbtn_PQCC";
		$structure['main']['_head_color'] = "pq_h_color_h1_PQCC";
		$structure['main']['_text_color'] = "pq_text_color_block_PQCC";
		$structure['main']['_border_color'] = "pq_bd_bordercolor_PQCC";		
		$structure['main']['_close_icon_color'] = "pq_x_color_pqclose_PQCC";
		$structure['main']['_tblock_text_font_color'] = "pq_bgtxt_color_bgtxtp_PQCC";
		$structure['main']['_background_mobile_block'] = "pq_mblock_bgcolor_bgmobblock_PQCC";
		$structure['main']['_mblock_text_font_color'] = "pq_mblock_color_bgmobblockp_PQCC";
		
		//new 10.2016
		$structure['main']['_ss_color_rgba'] = "pq_pro_color_socicons_PQRGBA_";
		$structure['main']['_ss_background_color_rgba'] = "pq_pro_bgcolor_socicons_PQRGBA_";
		$structure['main']['_ss_view_type'] = array("pq_pro_ss_with_linear_counter"=>"Type 1","pq_pro_ss_with_bottom_counter"=>"Type 2");
		
		$structure['main']['_typeWindow'] = array('pq_large'=>'Size L','pq_medium'=>'Size M', 'pq_mini'=>'Size S');
		$structure['main']['_animation'] = array("pq_anim_flipInY" => "Flip In Y","pq_anim_flipInX" => "Flip In X","pq_anim_zoomIn" => "Zoom In","pq_anim_zoomInUp" => "Zoom In Up","pq_anim_zoomInDown" => "Zoom In Down","pq_anim_zoomInLeft" => "Zoom In Left","pq_anim_zoomInRight" => "Zoom In Right","pq_anim_fadeIn" => "Fade In","pq_anim_fadeInUp" => "Fade In Up","pq_anim_fadeInDown" => "Fade In Down","pq_anim_fadeInLeft" => "Fade In Left","pq_anim_fadeInRight" => "Fade In Right","pq_anim_swingIn" => "Swing","pq_anim_rubberBandIn" => "RubberBand","pq_anim_shakeIn" => "Shake","pq_anim_wobbleIn" => "Worbble","pq_anim_jelloIn" => "Jello","pq_anim_tadaIn" => "Tada","pq_anim_bounceInUp" => "Bounce In Up","pq_anim_bounceInDown" => "Bounce In Down","pq_anim_bounceInLeft" => "Bounce In Left","pq_anim_bounceInRight" => "Bounce In Right","pq_anim_lightSpeedIn" => "Light Speed In","pq_anim_rotateIn" => "Rotate In","pq_anim_rotateInDownLeft" => "Rotate In Down Left");
		$structure['main']['_icon_design'] = array("c1"=>"Type 1","c2"=>"Type 2","c3"=>"Type 3","c4"=>"Type 4","c5"=>"Type 5","c6"=>"Type 6","c7"=>"Type 7","c8"=>"Type 8","c9"=>"Type 9","c10"=>"Type 10","c11"=>"Type 11","c12"=>"Type 12");
		$structure['main']['_icon_form'] = array('pq_square'=>'Square', 'pq_rounded'=>'Rounded', 'pq_circle'=>'Circle', 'pq_tv'=>'TV style');
		$structure['main']['_icon_size'] = array('x20'=>'Size S', 'x30'=>'Size M', 'x40'=>'Size M+', 'x50'=>'Size L', 'x70'=>'Size XL');
		$structure['main']['_icon_space'] = array('pq_step1'=>'1px','pq_step2'=>'2px','pq_step3'=>'3px','pq_step4'=>'4px','pq_step5'=>'5px','pq_step6'=>'6px','pq_step7'=>'7px','pq_step8'=>'8px','pq_step9'=>'9px','pq_step10'=>'10px');
		$structure['main']['_icon_shadow'] = array('sh1'=>'Shadow №1','sh2'=>'Shadow №2','sh3'=>'Shadow №3','sh4'=>'Shadow №4','sh5'=>'Shadow №5','sh6'=>'Shadow №6');
		$structure['main']['_icon_position'] = array('pq_inline'=>'Inline');
		$structure['main']['_icon_animation'] = array("pq_hvr_grow" => "Grow","pq_hvr_shrink" => "Shrink","pq_hvr_pulse" => "Pulse","pq_hvr_push" => "Push","pq_hvr_float" => "Float","pq_hvr_sink" => "Sink","pq_hvr_hang" => "Hang","pq_hvr_buzz" => "Buzz","pq_hvr_border_fade" => "Bdr Fade","pq_hvr_hollow" => "Hollow","pq_hvr_glow" => "Glow","pq_hvr_grow_shadow" => "Grow Shadow");
		$structure['main']['_mobile_type'] = array("pq_default" => "Default", "pq_mosaic" => "Mosaic", "pq_coin" => "Coin");
		$structure['main']['_mobile_position'] = array("pq_mobile_top" => "Top", "pq_mobile_bottom" => "Bottom");
		$structure['main']['_head_font_size'] = array("pq_head_size12" => "Size - 12","pq_head_size14" => "Size - 14","pq_head_size16" => "Size - 16","pq_head_size18" => "Size - 18","pq_head_size20" => "Size - 20","pq_head_size22" => "Size - 22","pq_head_size24" => "Size - 24","pq_head_size26" => "Size - 26","pq_head_size28" => "Size - 28","pq_head_size30" => "Size - 30","pq_head_size32" => "Size - 32","pq_head_size34" => "Size - 34","pq_head_size36" => "Size - 36","pq_head_size42" => "Size - 42","pq_head_size48" => "Size - 48","pq_head_size52" => "Size - 52","pq_head_size56" => "Size - 56","pq_head_size60" => "Size - 60","pq_head_size64" => "Size - 64","pq_head_size72" => "Size - 72","pq_head_size82" => "Size - 82","pq_head_size90" => "Size - 90","pq_head_size110" => "Size - 110","pq_head_size120" => "Size - 120","pq_head_size130" => "Size - 130");
		$structure['main']['_text_font_size'] = array("pq_text_size12" => "Size - 12","pq_text_size14" => "Size - 14","pq_text_size16" => "Size - 16","pq_text_size18" => "Size - 18","pq_text_size20" => "Size - 20","pq_text_size24" => "Size - 24","pq_text_size28" => "Size - 28","pq_text_size30" => "Size - 30","pq_text_size32" => "Size - 32","pq_text_size34" => "Size - 34","pq_text_size36" => "Size - 36","pq_text_size42" => "Size - 42","pq_text_size48" => "Size - 48","pq_text_size52" => "Size - 52","pq_text_size56" => "Size - 56","pq_text_size60" => "Size - 60","pq_text_size64" => "Size - 64","pq_text_size72" => "Size - 72","pq_text_size82" => "Size - 82","pq_text_size90" => "Size - 90","pq_text_size110" => "Size - 110","pq_text_size120" => "Size - 120","pq_text_size130" => "Size - 130");
		$structure['main']['_popup_form'] = array("pq_br_sq" => "Square",	"pq_br_cr" => "Rounded");
		$structure['main']['_mblock_text_font_size'] = array("pq_mblock_size16"=>"Size 16","pq_mblock_size20"=>"Size 20","pq_mblock_size24"=>"Size 24","pq_mblock_size28"=>"Size 28","pq_mblock_size36"=>"Size 36","pq_mblock_size48"=>"Size 48");
		$structure['main']['_font_size'] = array("pq_text_size12" => "Size - 12","pq_text_size14" => "Size - 14","pq_text_size16" => "Size - 16","pq_text_size18" => "Size - 18","pq_text_size20" => "Size - 20","pq_text_size24" => "Size - 24","pq_text_size28" => "Size - 28","pq_text_size30" => "Size - 30","pq_text_size32" => "Size - 32","pq_text_size34" => "Size - 34","pq_text_size36" => "Size - 36","pq_text_size42" => "Size - 42","pq_text_size48" => "Size - 48","pq_text_size52" => "Size - 52","pq_text_size56" => "Size - 56","pq_text_size60" => "Size - 60","pq_text_size64" => "Size - 64","pq_text_size72" => "Size - 72","pq_text_size82" => "Size - 82","pq_text_size90" => "Size - 90","pq_text_size110" => "Size - 110","pq_text_size120" => "Size - 120","pq_text_size130" => "Size - 130");
		$structure['main']['_border_type'] = array("pq_bs_dotted"=>"Border 1","pq_bs_dashed"=>"Border 2","pq_bs_double"=>"Border 3","pq_bs_post"=>"Border 4");
		$structure['main']['_border_depth'] = array("pq_bd1"=>"Type 1","pq_bd2"=>"Type 2","pq_bd3"=>"Type 3","pq_bd4"=>"Type 4","pq_bd5"=>"Type 5","pq_bd6"=>"Type 6","pq_bd7"=>"Type 7","pq_bd8"=>"Type 8","pq_bd9"=>"Type 9","pq_bd10"=>"Type 10");
		$structure['main']['_button_font_size'] = array("pq_btn_size12"=>"Size 12","pq_btn_size14"=>"Size 14","pq_btn_size16"=>"Size 16","pq_btn_size18"=>"Size 18","pq_btn_size20"=>"Size 20","pq_btn_size22"=>"Size 22","pq_btn_size24"=>"Size 24","pq_btn_size26"=>"Size 26","pq_btn_size28"=>"Size 28","pq_btn_size30"=>"Size 30","pq_btn_size32"=>"Size 32","pq_btn_size34"=>"Size 34","pq_btn_size36"=>"Size 36","pq_btn_size42"=>"Size 42","pq_btn_size48"=>"Size 48","pq_btn_size52"=>"Size 52","pq_btn_size56"=>"Size 56","pq_btn_size60"=>"Size 60","pq_btn_size64"=>"Size 64","pq_btn_size72"=>"Size 72","pq_btn_size82"=>"Size 82","pq_btn_size90"=>"Size 90");
		$structure['main']['_tblock_text_font_size'] = array("pq_tblock_size12"=>"Size 12","pq_tblock_size14"=>"Size 14","pq_tblock_size16"=>"Size 16","pq_tblock_size18"=>"Size 18","pq_tblock_size20"=>"Size 20","pq_tblock_size22"=>"Size 22","pq_tblock_size24"=>"Size 24","pq_tblock_size26"=>"Size 26","pq_tblock_size28"=>"Size 28","pq_tblock_size36"=>"Size 36","pq_tblock_size48"=>"Size 48");		
		$structure['main']['_head_font'] = "pq_h_font_h1_";
		$structure['main']['_text_font'] = "pq_text_font_pqtext_";
		$structure['main']['_mblock_text_font'] = "pq_mblock_font_bgmobblock_";
		$structure['main']['_button_font'] ="pq_btn_font_btngroupbtn_";
		$structure['main']['_tblock_text_font'] = "pq_bgtxt_block_font_bgtxtp_";
		$structure['main']['_close_text_font'] = "pq_x_font_pqclose_";
		$structure['main']['_button_form'] = array("pq_btn_cr"=>"Button Form 1", "pq_btn_sq"=>"Button Form 2");
		$structure['main']['_input_type'] = array("pq_input_type1"=>"Input Type 1","pq_input_type2"=>"Input Type 2","pq_input_type3"=>"Input Type 3","pq_input_type4"=>"Input Type 4","pq_input_type5"=>"Input Type 5","pq_input_type6"=>"Input Type 6","pq_input_type7"=>"Input Type 7","pq_input_type8"=>"Input Type 8","pq_input_type9"=>"Input Type 9","pq_input_type10"=>"Input Type 10","pq_input_type11"=>"Input Type 11","pq_input_type12"=>"Input Type 12","pq_input_type13"=>"Input Type 13","pq_input_type14"=>"Input Type 14","pq_input_type15"=>"Input Type 15","pq_input_type16"=>"Input Type 16");
		$structure['main']['_button_type'] = array("pq_btn_type1"=>"Button Type 1","pq_btn_type2"=>"Button Type 2","pq_btn_type3"=>"Button Type 3","pq_btn_type4"=>"Button Type 4","pq_btn_type5"=>"Button Type 5","pq_btn_type6"=>"Button Type 6","pq_btn_type7"=>"Button Type 7","pq_btn_type8"=>"Button Type 8","pq_btn_type9"=>"Button Type 9","pq_btn_type10"=>"Button Type 10");
		$structure['main']['_header_img_type'] = array("pq_pro_img_type1"=>"Header Img Type 1","pq_pro_img_type2"=>"Header Img Type 2","pq_pro_img_type3"=>"Header Img Type 3","pq_pro_img_type4"=>"Header Img Type 4","pq_pro_img_type5"=>"Header Img Type 5","pq_pro_img_type6"=>"Header Img Type 6","pq_pro_img_type7"=>"Header Img Type 7","pq_pro_img_type8"=>"Header Img Type 8","pq_pro_img_type9"=>"Header Img Type 9","pq_pro_img_type10"=>"Header Img Type 10","pq_pro_img_type11"=>"Header Img Type 11","pq_pro_img_type12"=>"Header Img Type 12","pq_pro_img_type13"=>"Header Img Type 13","pq_pro_img_type14"=>"Header Img Type 14","pq_pro_img_type15"=>"Header Img Type 15","pq_pro_img_type16"=>"Header Img Type 16");
		$structure['main']['_background_opacity'] = array("0"=>"Background Opacity 0%","1"=>"Background Opacity 10%","2"=>"Background Opacity 20%","3"=>"Background Opacity 30%","4"=>"Background Opacity 40%","5"=>"Background Opacity 50%","6"=>"Background Opacity 60%","7"=>"Background Opacity 70%","8"=>"Background Opacity 80%","9"=>"Background Opacity 90%","10"=>"Background Opacity 100%");
		$structure['main']['_close_icon_animation'] = array("pq_hvr_rotate"=>"Rotate","pq_hvr_grow" => "Grow","pq_hvr_shrink" => "Shrink","pq_hvr_pulse" => "Pulse","pq_hvr_push" => "Push","pq_hvr_float" => "Float","pq_hvr_sink" => "Sink","pq_hvr_hang" => "Hang","pq_hvr_buzz" => "Buzz","pq_hvr_border_fade" => "Bdr Fade","pq_hvr_hollow" => "Hollow","pq_hvr_glow" => "Glow","pq_hvr_grow_shadow" => "Grow Shadow");
		$structure['main']['_close_icon_type'] = array("pq_x_type1"=>"Close Icon 1","pq_x_type2"=>"Close Icon 2","pq_x_type3"=>"Close Icon 3","pq_x_type4"=>"Close Icon 4","pq_x_type5"=>"Close Icon 5","pq_x_type9"=>"Close Icon 6","pq_x_type6"=>"Close Text 1","pq_x_type7"=>"Close Text 2","pq_x_type8"=>"Close Text 3","pq_x_type10"=>"Close Text 4");		
		$structure['main']['_showup_animation'] = array("pq_pro_display_animation_random"=>"Random PRO Animation", "pq_pro_display_animation_1"=>"Animation 1","pq_pro_display_animation_2"=>"Animation 2","pq_pro_display_animation_3"=>"Animation 3","pq_pro_display_animation_4"=>"Animation 4","pq_pro_display_animation_5"=>"Animation 5","pq_pro_display_animation_6"=>"Animation 6","pq_pro_display_animation_7"=>"Animation 7","pq_pro_display_animation_8"=>"Animation 8","pq_pro_display_animation_9"=>"Animation 9","pq_pro_display_animation_10"=>"Animation 10","pq_pro_display_animation_11"=>"Animation 11","pq_pro_display_animation_12"=>"Animation 12","pq_pro_display_animation_13"=>"Animation 13");		
		$structure['main']['_form_block_padding'] = array("pq_formbg_bg_top_s"=>"Padding S","pq_formbg_bg_top_m"=>"Padding M","pq_formbg_bg_top_l"=>"Padding L","pq_formbg_bg_top_xl"=>"Padding XL");		
		$structure['main']['_button_block_padding'] = array("pq_btngbg_bg_top_s"=>"Padding S","pq_btngbg_bg_top_m"=>"Padding M","pq_btngbg_bg_top_l"=>"Padding L","pq_btngbg_bg_top_xl"=>"Padding XL");		
		$structure['main']['_text_block_padding'] = array("pq_txtbg_bg_top_s"=>"Padding S","pq_txtbg_bg_top_m"=>"Padding M","pq_txtbg_bg_top_l"=>"Padding L","pq_txtbg_bg_top_xl"=>"Padding XL");		
		$structure['main']['_icon_block_padding'] = array("pq_icobg_bg_top_s"=>"Padding S","pq_icobg_bg_top_m"=>"Padding M","pq_icobg_bg_top_l"=>"Padding L","pq_icobg_bg_top_xl"=>"Padding XL");		
		
		$itog = array();
		$unknown = array();
		foreach((array)$array as $k => $v){
			if(trim($v)){
				$flag = false;
				foreach((array)$structure[$type] as $key => $val){
					if(is_array($val)){
						if($val[$v]){
							$itog[$key] = $v;
							$flag = true;
						}
					}else{
						if(strstr($v, $val)){
							$itog[$key] = $v;
							$flag = true;
						}
					}
				}
				if(strstr($v, 'pq_themes_text_')){						
					$textArr = $this->_getTextThemesParams($v);					
					foreach((array)$textArr as $key => $val){
						$itog[$key] = $val;
					}
					$flag = true;
				}
				if(!$flag){									
					$unknown[$v]=1;
				}
			}
		}
		foreach((array)$itog as $k => $v){
			$ret[$k] = $v;
			if($k == '_ss_color_rgba'){
				unset($ret['_ss_color_rgba']);
				$temp = explode('_PQRGBA_',$v);
				$temp = explode('_', $temp[1]);
				
				$ret['_ss_color_opacity'] = $temp[3];								
				$ret['_ss_color'] = $this->rgb2hex($temp);
			}else if($k == '_ss_background_color_rgba'){
				unset($ret['_ss_background_color_rgba']);
				$temp = explode('_PQRGBA_',$v);
				$temp = explode('_', $temp[1]);
				
				$ret['_ss_background_color_opacity'] = $temp[3];								
				$ret['_ss_background_color'] = $this->rgb2hex($temp);
			}else if($k == '_background_color_rgba'){
				unset($ret['_background_color_rgba']);
				$temp = explode('_PQRGBA_',$v);
				$temp = explode('_', $temp[1]);
				
				$ret['_background_opacity'] = $temp[3];								
				$ret['_background_color'] = $this->rgb2hex($temp);
			}else{
				if($this->_getColor($v)){
					$ret[$k] = $this->_getColor($v);					
				}				
			}
		}
				
		return $ret;
	}
	
	function prepareThemeStructure($array){
		$ret = array();
		foreach((array)$array as $toolGroup => $themesArray){
			foreach((array)$themesArray as $themeName => $data){
				$ret[$toolGroup][$themeName] = $data;
				$ret[$toolGroup][$themeName][preview_image_small] = '//profitquery-a.akamaihd.net/lib/'.$data[preview_image_small];
				$ret[$toolGroup][$themeName][preview_image_big] = '//profitquery-a.akamaihd.net/lib/'.$data[preview_image_big];
				
				unset($ret[$toolGroup][$themeName][designOptions]);
				foreach((array)$data[designOptions] as $k => $v)
				{
					if(($k == 'fb' || $k == 'main' ) && $v){
						if(!$ret[$toolGroup][$themeName][designOptions]){
							$ret[$toolGroup][$themeName][designOptions] = $this->_parseDesignOptions($v, $k);
						}else{
							$dOpt = $this->_parseDesignOptions($v, $k);
							foreach((array)$dOpt as $key => $value){
								$ret[$toolGroup][$themeName][designOptions][$key] = $value;
							}
						}
					}else if(
						$k == '_overlay_image_src' ||						
						$k == '_header_image_src' ||
						$k == '_background_image_src'
					){								
						$ret[$toolGroup][$themeName][designOptions][$k] = '//profitquery-a.akamaihd.net/lib/'.$v;
					}else if($k == '_iframe_src'){
						$ret[$toolGroup][$themeName][designOptions][$k] = $v;						
					}
				}
			}
		}		
		return $ret;
	}
	
	function getModifTime($file){		
		$ret = get_headers($file);		
		foreach((array)$ret as $k => $v){			
			if(strstr($v,'Last-Modified')){
				$v = str_replace('Last-Modified:','',$v);
				if(strtotime($v)){
					return strtotime($v);
				}
			}
		}
		return -1;		
	}
	
	function getExternalFileContent($url, $withoutHalt = 0){
		$response = '';
		if (ini_get('allow_url_fopen') == '1') {
			$response = @file_get_contents($url);
		}else{
			$ch = curl_init(); 
			curl_setopt($ch, CURLOPT_URL, $url); 
			curl_setopt($ch, CURLOPT_HEADER, 0); 
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
			curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.5) Gecko/20041107 Firefox/1.0'); 
			$response = curl_exec($ch);
			curl_close($ch); 
		}		
		if(!trim($response) && !$withoutHalt) {			
			echo '<br><br><strong>Oops. Profitquery plugin need to load some external setting (language, themes). For use Profitquery plugin you need to enable "allow_url_fopen" flag in the php.ini or enable CURL. Failed loading '.$url.'</strong>';
			die();
		}
		return $response;
	}
	
	function prepareThemeText($array){
		$ret = '';
		foreach((array)$array as $name => $data){
			foreach((array)$data as $k => $v){
				if($k == 't') $ret[$name]['_title'] = $v;
				if($k == 'st') $ret[$name]['_subtitle'] = $v;
				if($k == 'pt') $ret[$name]['_tblock_text'] = $v;
				if($k == 'bt') $ret[$name]['_button_text'] = $v;
				if($k == 'ct') $ret[$name]['_close_text'] = $v;
				
			}
		}
		return $ret;
	}
	
	function parseThemesText(){		
		if($_SERVER[HTTPS]){
			$url  = 'https://profitquery-a.akamaihd.net/lib/themes/v5/themes.text.v5.2.json';        
		}else{
			$url  = 'http://profitquery-a.akamaihd.net/lib/themes/v5/themes.text.v5.2.json';        
		}		
        $LastModified = $this->getModifTime($url);
		if($this->_options[themes_text_last_modified] == $LastModified && $this->_options[themes_text]){
			$this->_themes_text = $this->_options[themes_text];			
		}else{							
		
			$response = $this->getExternalFileContent($url);			
			$response = str_replace("\r", " ",$response);
			$response = str_replace("\n", " ",$response);
			$response = str_replace("\t", " ",$response);
			$response = str_replace("  ", " ",$response);
			$response = str_replace("  ", " ",$response);
			
			
			$response = str_replace("var PQThemesText = ", "",$response);
			$response = str_replace("var PQThemesText=", "",$response);
			
			$this->_themes_text = json_decode(trim($response), true);						
			$this->_themes_text = $this->prepareThemeText($this->_themes_text);						
			
			$this->_options[themes_text] = $this->_themes_text;
			$this->_options[themes_text_last_modified] = $LastModified;
			update_option('profitquery', $this->_options);			
		}		
		//echo $response;		
	}
	
	function parseThemes(){		
		if($_SERVER[HTTPS]){
			$url  = 'https://profitquery-a.akamaihd.net/lib/themes/v5/index.v5.2.json';        
		}else{
			$url  = 'http://profitquery-a.akamaihd.net/lib/themes/v5/index.v5.2.json';        
		}				
		
        $LastModified = $this->getModifTime($url);
		if($this->_options[themes_last_modified] == $LastModified && $this->_options[themes]){
			$this->_themes = $this->_options[themes];			
		}else{		
			$response = $this->getExternalFileContent($url);
		
			$response = str_replace("\r", " ",$response);
			$response = str_replace("\n", " ",$response);
			$response = str_replace("\t", " ",$response);
			$response = str_replace("  ", " ",$response);
			$response = str_replace("  ", " ",$response);
			
			
			$response = str_replace("var PQThemesStructure = ", "",$response);
			$response = str_replace("var PQThemesStructure=", "",$response);				
			$this->_themes = json_decode(trim($response), true);
			$this->_themes = $this->prepareThemeStructure($this->_themes);			
			$this->_options[themes] = $this->_themes;
			$this->_options[themes_last_modified] = $LastModified;
			update_option('profitquery', $this->_options);			
		}	
		$this->getDefaultThemes();		
	}
	
	function getPluginSettings(){		
		if($_SERVER[HTTPS]){
			$url  = 'https://profitquery-a.akamaihd.net/lib/plugins/aio.settings.v5.2.json';        
		}else{
			$url  = 'http://profitquery-a.akamaihd.net/lib/plugins/aio.settings.v5.2.json';        
		}		
		 $LastModified = $this->getModifTime($url);
		if($this->_options[plugin_setting_last_modified] == $LastModified && $this->_options[plugin_setting_file]){
			$this->_plugin_settings = $this->_options[plugin_setting_file];			
		}else{					
			$response = $this->getExternalFileContent($url);
			
			$response = str_replace("\r", " ",$response);
			$response = str_replace("\n", " ",$response);
			$response = str_replace("\t", " ",$response);
			$response = str_replace("  ", " ",$response);
			$response = str_replace("  ", " ",$response);		
			$this->_plugin_settings = json_decode(trim($response), true);			
			
			$this->_options[plugin_setting_file] = $this->_plugin_settings;
			$this->_options[plugin_setting_last_modified] = $LastModified;
			update_option('profitquery', $this->_options);
		}
	}
	
	
	
	function _getRealName($name){
		$ret = '';
		if($name == 'sharingsidebar'){
			$ret = 'sharingSidebar';
		}
		if($name == 'imagesharer'){
			$ret = 'imageSharer';
		}
		if($name == 'sharingpopup'){
			$ret = 'sharingPopup';
		}
		if($name == 'sharingbar'){
			$ret = 'sharingBar';
		}
		if($name == 'sharingfloating'){
			$ret = 'sharingFloating';
		}
		if($name == 'emaillistbuilderpopup'){
			$ret = 'emailListBuilderPopup';
		}
		
		if($name == 'emaillistbuilderbar'){
			$ret = 'emailListBuilderBar';
		}
		if($name == 'emaillistbuilderfloating'){
			$ret = 'emailListBuilderFloating';
		}
		if($name == 'contactformpopup' || $name == 'contactuspopup'){
			$ret = 'contactFormPopup';
		}
		if($name == 'contactformcenter'){
			$ret = 'contactFormCenter';
		}
		if($name == 'contactformfloating' || $name == 'contactusfloating'){
			$ret = 'contactFormFloating';
		}
		if($name == 'promotepopup'){
			$ret = 'promotePopup';
		}
		if($name == 'promotebar'){
			$ret = 'promoteBar';
		}
		
		if($name == 'promotefloating'){
			$ret = 'promoteFloating';
		}
		if($name == 'callmepopup'){
			$ret = 'callMePopup';
		}
		if($name == 'callmefloating'){
			$ret = 'callMeFloating';
		}
		if($name == 'followpopup'){
			$ret = 'followPopup';
		}
		if($name == 'followbar'){
			$ret = 'followBar';
		}
		if($name == 'followfloating'){
			$ret = 'followFloating';
		}
		if($name == 'iframepopup'){
			$ret = 'iframePopup';
		}
		if($name == 'iframefloating'){
			$ret = 'iframeFloating';
		}
		if($name == 'youtubepopup'){
			$ret = 'youtubePopup';
		}
		
		if($name == 'youtubefloating'){
			$ret = 'youtubeFloating';
		}
		
		return $ret;
	}
	
	function prepareProOptions($array){
		$ret = '';				
		
		if($array[tools]){
			foreach((array)$array[tools] as $name => $data){				
				$ret[$this->_getRealName($name)] = $data;
			}
		}
		
		if($array[isPRO]){
			$ret[isPRO] = $array[isPRO];
		}
		if($array[affiliate]){
			$ret[affiliate] = $array[affiliate];
		}
		if($array[pro]){
			$ret[pro] = $array[pro];
		}
		if($array[trial]){
			$ret[trial] = $array[trial];
		}
		return $ret;
	}
	
	function parseProLoader(){		
		$this->_pro_options = array();
		
		
		if($_SERVER[HTTPS]){
			$url  = 'https://profitquery-a.akamaihd.net/lib/pro-loaders/'.$this->_options[settings][pro_loader_filename].'.js';        
		}else{
			$url  = 'http://profitquery-a.akamaihd.net/lib/pro-loaders/'.$this->_options[settings][pro_loader_filename].'.js';
		}
			
		$response = $this->getExternalFileContent($url, 1);		
		
		$response = str_replace("\r", " ",$response);
		$response = str_replace("\n", " ",$response);
		$response = str_replace("\t", " ",$response);
		$response = str_replace("  ", " ",$response);
		$response = str_replace("  ", " ",$response);						
		
		preg_match_all('/(profitquery\.o\.p)(.*)(\=)(.*)(\}else\{)(.*)(profitquery\.o\.p)(.*)/Ui', $response, $matches);			
		if(json_decode(trim($matches[4][0]), true)){
			$this->_pro_options = $this->prepareProOptions(json_decode(trim($matches[4][0]), true));			
		}
		
	}
	
	function getDictionary(){
		if(!$this->_options[settings][lang]) $this->_options[settings][lang] = 'en';
			
		if($_SERVER[HTTPS]){
			$url  = 'https://profitquery-a.akamaihd.net/lib/plugins/lang/'.$this->_options[settings][lang].'.aio.plugin.v5.json';        
		}else{
			$url  = 'http://profitquery-a.akamaihd.net/lib/plugins/lang/'.$this->_options[settings][lang].'.aio.plugin.v5.json';        
		}
						
		$LastModified = $this->getModifTime($url);		
		if($this->_options['plugin_lang_last_modified'.$this->_options[settings][lang]] == $LastModified && $this->_options['plugin_dictionary_'.$this->_options[settings][lang]]){
			$this->_dictionary = $this->_options['plugin_dictionary_'.$this->_options[settings][lang]];			
		}else{						
			$response = $this->getExternalFileContent($url);			
			$response = str_replace("\r", " ",$response);
			$response = str_replace("\n", " ",$response);
			$response = str_replace("\t", " ",$response);
			$response = str_replace("  ", " ",$response);
			$response = str_replace("  ", " ",$response);
					
				
			$this->_dictionary = json_decode(trim($response), true);						
			$this->_options['plugin_dictionary_'.$this->_options[settings][lang]] = $this->_dictionary;
			$this->_options['plugin_lang_last_modified'.$this->_options[settings][lang]] = $LastModified;
			update_option('profitquery', $this->_options);
		}		
	}
	
	function getArrayAllFreeTools(){
		$ret = '';
		foreach((array)$this->_toolsName as $k => $name){
			if($name != 'sharingSidebar' && $name != 'imageSharer'){
				$proOptions = $this->_getToolProOptions($name);			
				if(!$proOptions){
					$toolInfo = $this->getToolInfo($name);
					if($toolInfo[name]){
						$ret[$name][toolInfo] = $toolInfo[name];
					}
				}			
			}			
		}
		return $ret;
	}

function getArrayProOptionsPaid(){
		$ret = false;			
		foreach((array)$this->_pro_options as $key => $data){
			if($key == 'pro' && $data[till]){
				$ret = true;
			}			
		}		
		return $ret;
	}
	
	function getArrayProOptionsSelected($array){
		$ret = array();		
		foreach((array)$array as $tool => $data){			
			$proOptions = $this->_getToolProOptions($tool);
			if($proOptions){
				$ret[$tool][name] = $data[name];			
				$ret[$tool][pro_options] = $proOptions;
			}
		}		
		return $ret;
	}
	
	function _proceedSharingSocnetError($array){
		$ret = $temp = array();
		foreach((array)$array as $k => $v){
			if($v){
				if(!$temp[$v]){
					$temp[$v] = 1;
				}else{
					$ret[$v] = 1;
				}
			}
		}
		return $ret;
	}
	
	function getSharingSocnetErrorArray(){
		$ret = array();
		foreach((array)$this->_options as $toolName => $array){
			if(isset($this->_options[$toolName]['socnet_with_pos'])){				
				$ret[$toolName]['error_socnet'] = $this->_proceedSharingSocnetError($this->_options[$toolName]['socnet_with_pos']);
			}
			if(isset($this->_options[$toolName]['thank']['socnet_with_pos'])){
				$ret[$toolName]['thank']['error_socnet'] = $this->_proceedSharingSocnetError($this->_options[$toolName]['thank']['socnet_with_pos']);
			}
		}
		return $ret;
	}
	
	function _getCleanYoutubeAddress($link){
		preg_match_all('@((https?://)?([-\\w]+\\.[-\\w\\.]+)+\\w(:\\d+)?(/([-\\w/_\\.]*(\\?\\S+)?)?)*)@',$link,$matches);
		return $matches[0][0];
	}
	
	function prepareYoutubeLink($link){
		$ret = '';
		$link = $this->_getCleanYoutubeAddress($link);		
		if(strstr($link, 'youtube.com/embed/')){
			$ret = addslashes($link);
		}else if(strstr($link, 'youtu.be/')){
			$temp = explode('youtu.be/', $link);
			$ret = 'https://www.youtube.com/embed/'.$temp[1];			
		}else{
			$ret = addslashes(str_replace('watch?v=', 'embed/', stripslashes($link)));	
		}		
		return $ret;
	}
	
	function prepareCommonDesignSettings($array){
		$ret = array();

		//sharing counters
		if(isset($array['fake_counter'])) $ret['fake_counter'] = sanitize_text_field($array['fake_counter']);
		if($array['withCounters'] == 'on') $ret['withCounters'] = 'on';
		
		//icons
		if(isset($array['icon']['form'])) $ret['icon']['form'] = sanitize_text_field($array['icon']['form']);
		if(isset($array['icon']['design'])) $ret['icon']['design'] = sanitize_text_field($array['icon']['design']);
		if(isset($array['icon']['size'])) $ret['icon']['size'] = sanitize_text_field($array['icon']['size']);
		if(isset($array['icon']['space'])) $ret['icon']['space'] = sanitize_text_field($array['icon']['space']);
		if(isset($array['icon']['animation'])) $ret['icon']['animation'] = sanitize_text_field($array['icon']['animation']);
		if(isset($array['icon']['shadow'])) $ret['icon']['shadow'] = sanitize_text_field($array['icon']['shadow']);
		
		
		//gallery options
		if($array['galleryOption']['enable'] == 'on') $ret['galleryOption']['enable'] = 'on'; else $ret['galleryOption']['enable'] = '';
		if(isset($array['galleryOption']['minWidth'])) $ret['galleryOption']['minWidth'] = (int)$array['galleryOption']['minWidth'];
		if(isset($array['galleryOption']['title'])) $ret['galleryOption']['title'] = sanitize_text_field($array['galleryOption']['title']);
		if(isset($array['galleryOption']['head_font'])) $ret['galleryOption']['head_font'] = sanitize_text_field($array['galleryOption']['head_font']);
		if(isset($array['galleryOption']['head_size'])) $ret['galleryOption']['head_size'] = sanitize_text_field($array['galleryOption']['head_size']);
		if(isset($array['galleryOption']['head_color'])) $ret['galleryOption']['head_color'] = sanitize_text_field($array['galleryOption']['head_color']);
		if(isset($array['galleryOption']['button_text'])) $ret['galleryOption']['button_text'] = sanitize_text_field($array['galleryOption']['button_text']);
		if(isset($array['galleryOption']['button_font'])) $ret['galleryOption']['button_font'] = sanitize_text_field($array['galleryOption']['button_font']);
		if(isset($array['galleryOption']['button_font_size'])) $ret['galleryOption']['button_font_size'] = sanitize_text_field($array['galleryOption']['button_font_size']);
		if(isset($array['galleryOption']['button_text_color'])) $ret['galleryOption']['button_text_color'] = sanitize_text_field($array['galleryOption']['button_text_color']);
		if(isset($array['galleryOption']['button_color'])) $ret['galleryOption']['button_color'] = sanitize_text_field($array['galleryOption']['button_color']);
		if(isset($array['galleryOption']['background_color'])) $ret['galleryOption']['background_color'] = sanitize_text_field($array['galleryOption']['background_color']);
		
		//pro options
		if(isset($array['ss_view_type'])) $ret['ss_view_type'] = sanitize_text_field($array['ss_view_type']);
		if(isset($array['ss_color'])) $ret['ss_color'] = sanitize_text_field($array['ss_color']);
		if(isset($array['ss_color_opacity'])) $ret['ss_color_opacity'] = sanitize_text_field($array['ss_color_opacity']);
		if(isset($array['ss_background_color'])) $ret['ss_background_color'] = sanitize_text_field($array['ss_background_color']);
		if(isset($array['ss_background_color_opacity'])) $ret['ss_background_color_opacity'] = sanitize_text_field($array['ss_background_color_opacity']);
		if(isset($array['header_img_type'])) $ret['header_img_type'] = sanitize_text_field($array['header_img_type']);
		if(isset($array['showup_animation'])) $ret['showup_animation'] = sanitize_text_field($array['showup_animation']);
		if(isset($array['header_image_src'])) $ret['header_image_src'] = esc_url($array['header_image_src']);
		if(isset($array['background_image_src'])) $ret['background_image_src'] = esc_url($array['background_image_src']);
		if(isset($array['overlay_image_src'])) $ret['overlay_image_src'] = esc_url($array['overlay_image_src']);
		
		
		//mobile options
		if(isset($array['mobile_type'])) $ret['mobile_type'] = sanitize_text_field($array['mobile_type']);
		if(isset($array['mobile_position'])) $ret['mobile_position'] = sanitize_text_field($array['mobile_position']);
		if(isset($array['background_mobile_block'])) $ret['background_mobile_block'] = sanitize_text_field($array['background_mobile_block']);
		if(isset($array['mobile_title'])) $ret['mobile_title'] = sanitize_text_field($array['mobile_title']);
		if(isset($array['mblock_text_font'])) $ret['mblock_text_font'] = sanitize_text_field($array['mblock_text_font']);
		if(isset($array['mblock_text_font_color'])) $ret['mblock_text_font_color'] = sanitize_text_field($array['mblock_text_font_color']);
		if(isset($array['mblock_text_font_size'])) $ret['mblock_text_font_size'] = sanitize_text_field($array['mblock_text_font_size']);	
			   
    
		//others		
		if(isset($array['enter_phone_text'])) $ret['enter_phone_text'] = sanitize_text_field($array['enter_phone_text']);
		if(isset($array['loader_text'])) $ret['loader_text'] = sanitize_text_field($array['loader_text']);
		if(isset($array['bookmark_text_color'])) $ret['bookmark_text_color'] = sanitize_text_field($array['bookmark_text_color']);
		if(isset($array['bookmark_background'])) $ret['bookmark_background'] = sanitize_text_field($array['bookmark_background']);
		if(isset($array['bookmark_text_size'])) $ret['bookmark_text_size'] = sanitize_text_field($array['bookmark_text_size']);
		if(isset($array['bookmark_text_font'])) $ret['bookmark_text_font'] = sanitize_text_field($array['bookmark_text_font']);
		if(isset($array['theme'])) $ret['theme'] = sanitize_text_field($array['theme']);
		if(isset($array['position'])) $ret['position'] = sanitize_text_field($array['position']);
		if(isset($array['animation'])) $ret['animation'] = sanitize_text_field($array['animation']);	
		if(isset($array['typeWindow'])) $ret['typeWindow'] = sanitize_text_field($array['typeWindow']);	
		if(isset($array['popup_form'])) $ret['popup_form'] = sanitize_text_field($array['popup_form']);	
		if(isset($array['background_color'])) $ret['background_color'] = sanitize_text_field($array['background_color']);	
		if(isset($array['background_opacity'])) $ret['background_opacity'] = (int)$array['background_opacity'];	
		if(isset($array['overlay'])) $ret['overlay'] = sanitize_text_field($array['overlay']);	
		if(isset($array['overlay_opacity'])) $ret['overlay_opacity'] = sanitize_text_field($array['overlay_opacity']);	
		if(isset($array['title'])) $ret['title'] = sanitize_text_field($array['title']);	
		if(isset($array['head_font'])) $ret['head_font'] = sanitize_text_field($array['head_font']);	
		if(isset($array['head_size'])) $ret['head_size'] = sanitize_text_field($array['head_size']);	
		if(isset($array['head_color'])) $ret['head_color'] = sanitize_text_field($array['head_color']);	
		if(isset($array['sub_title'])) $ret['sub_title'] = sanitize_text_field($array['sub_title']);	
		if(isset($array['text_font'])) $ret['text_font'] = sanitize_text_field($array['text_font']);	
		if(isset($array['font_size'])) $ret['font_size'] = sanitize_text_field($array['font_size']);	
		if(isset($array['text_color'])) $ret['text_color'] = sanitize_text_field($array['text_color']);	
		if(isset($array['enter_email_text'])) $ret['enter_email_text'] = sanitize_text_field($array['enter_email_text']);	
		if(isset($array['enter_name_text'])) $ret['enter_name_text'] = sanitize_text_field($array['enter_name_text']);	
		if(isset($array['enter_subject_text'])) $ret['enter_subject_text'] = sanitize_text_field($array['enter_subject_text']);	
		if(isset($array['enter_message_text'])) $ret['enter_message_text'] = sanitize_text_field($array['enter_message_text']);	
		if(isset($array['background_form_block'])) $ret['background_form_block'] = sanitize_text_field($array['background_form_block']);	
		if(isset($array['form_block_padding'])) $ret['form_block_padding'] = sanitize_text_field($array['form_block_padding']);	
		if(isset($array['button_text'])) $ret['button_text'] = sanitize_text_field($array['button_text']);	
		if(isset($array['button_font'])) $ret['button_font'] = sanitize_text_field($array['button_font']);	
		if(isset($array['button_font_size'])) $ret['button_font_size'] = sanitize_text_field($array['button_font_size']);	
		if(isset($array['button_text_color'])) $ret['button_text_color'] = sanitize_text_field($array['button_text_color']);	
		if(isset($array['button_color'])) $ret['button_color'] = sanitize_text_field($array['button_color']);	
		if(isset($array['background_button_block'])) $ret['background_button_block'] = sanitize_text_field($array['background_button_block']);	
		if(isset($array['button_block_padding'])) $ret['button_block_padding'] = sanitize_text_field($array['button_block_padding']);	
		if($array['new_tab'] == 'on') $ret['new_tab'] = 'on'; else $ret['new_tab'] = '';
		if($array['promote_link_new_tab'] == 'on') $ret['promote_link_new_tab'] = 'on';	else $ret['promote_link_new_tab'] = '';
		if(isset($array['close_text_font'])) $ret['close_text_font'] = sanitize_text_field($array['close_text_font']);	
		if(isset($array['border_type'])) $ret['border_type'] = sanitize_text_field($array['border_type']);	
		if(isset($array['border_depth'])) $ret['border_depth'] = sanitize_text_field($array['border_depth']);	
		if(isset($array['border_color'])) $ret['border_color'] = sanitize_text_field($array['border_color']);	
		if(isset($array['buttonBlock']['type'])) $ret['buttonBlock']['type'] = sanitize_text_field($array['buttonBlock']['type']);	
		if(isset($array['is_type'])) $ret['is_type'] = sanitize_text_field($array['is_type']);	
		if(isset($array['tblock_text'])) $ret['tblock_text'] = sanitize_text_field($array['tblock_text']);	
		if(isset($array['background_text_block'])) $ret['background_text_block'] = sanitize_text_field($array['background_text_block']);	
		if(isset($array['text_block_padding'])) $ret['text_block_padding'] = sanitize_text_field($array['text_block_padding']);	
		if(isset($array['tblock_text_font'])) $ret['tblock_text_font'] = sanitize_text_field($array['tblock_text_font']);	
		if(isset($array['tblock_text_font_size'])) $ret['tblock_text_font_size'] = sanitize_text_field($array['tblock_text_font_size']);	
		if(isset($array['tblock_text_font_color'])) $ret['tblock_text_font_color'] = sanitize_text_field($array['tblock_text_font_color']);	
		if(isset($array['minWidth'])) $ret['minWidth'] = (int)($array['minWidth']);	
		if(isset($array['minHeight'])) $ret['minHeight'] = (int)($array['minHeight']);	
		if(isset($array['url'])) $ret['url'] = esc_url($array['url']);	
		if(isset($array['iframe_src'])) $ret['iframe_src'] = esc_url($array['iframe_src']);	
		if(isset($array['socnet_block_type'])) $ret['socnet_block_type'] = sanitize_text_field($array['socnet_block_type']);
		if(isset($array['background_soc_block'])) $ret['background_soc_block'] = sanitize_text_field($array['background_soc_block']);
		if(isset($array['icon_block_padding'])) $ret['icon_block_padding'] = sanitize_text_field($array['icon_block_padding']);
		if(isset($array['input_type'])) $ret['input_type'] = sanitize_text_field($array['input_type']);
		if(isset($array['button_form'])) $ret['button_form'] = sanitize_text_field($array['button_form']);
		if(isset($array['button_type'])) $ret['button_type'] = sanitize_text_field($array['button_type']);
		if(isset($array['background_button_block'])) $ret['background_button_block'] = sanitize_text_field($array['background_button_block']);
		if(isset($array['button_block_padding'])) $ret['button_block_padding'] = sanitize_text_field($array['button_block_padding']);
		//subscribers
		if(isset($array['provider'])) $ret['provider'] = sanitize_text_field($array['provider']);		 
		if(isset($array['providerForm'])) $ret['providerForm'] = sanitize_text_field(wp_specialchars($array['providerForm']));				
    
		
		//socnet_with_pos
		foreach((array)$array['socnet_with_pos'] as $k => $v){
			$ret['socnet_with_pos'][$k] = sanitize_text_field($v);
		}
		//socnet icons block
		foreach((array)$array['socnetIconsBlock'] as $k => $v){
			$ret['socnetIconsBlock'][$k] = sanitize_text_field($v);
		}
		//socnet
		foreach((array)$array['socnet'] as $k => $v){
			$ret['socnet'][$k] = sanitize_text_field($v);
		}
		//socnetOption
		foreach((array)$array['socnetOption'] as $k => $v){			
			$ret['socnetOption'][$k]['type'] = sanitize_text_field($v['type']);
			if(isset($array['socnetOption'][$k]['app_id'])) $ret['socnetOption'][$k]['app_id'] = sanitize_text_field($v['app_id']);
		}
        if(isset($array['close_icon'])){
			if(isset($array['close_icon']['form'])) $ret['close_icon']['form'] = sanitize_text_field($array['close_icon']['form']);	
			if(isset($array['close_icon']['button_text'])) $ret['close_icon']['button_text'] = sanitize_text_field($array['close_icon']['button_text']);	
			if(isset($array['close_icon']['color'])) $ret['close_icon']['color'] = sanitize_text_field($array['close_icon']['color']);	
			if(isset($array['close_icon']['animation'])) $ret['close_icon']['animation'] = sanitize_text_field($array['close_icon']['animation']);	
		}
		
		return $ret;
	}
	
	function prepareUserDataToSave($array){
		$ret = $this->prepareCommonDesignSettings($array);
		$ret[enable] = 'on';
						
		//display rules
		if(isset($array['displayRules'])){
			if($array['displayRules']['display_on_main_page'] == 'on') $ret['displayRules']['display_on_main_page'] = 'on'; else $ret['displayRules']['display_on_main_page'] = '';
			if($array['displayRules']['work_on_mobile'] == 'on') $ret['displayRules']['work_on_mobile'] = 'on'; else $ret['displayRules']['work_on_mobile'] = '';
			
			foreach((array)$array['displayRules']['pageMaskType'] as $k => $v){
				$ret['displayRules']['pageMaskType'][$k] = sanitize_text_field($v);
			}
			foreach((array)$array['displayRules']['pageMask'] as $k => $v){
				$ret['displayRules']['pageMask'][$k] = sanitize_text_field($v);
			}
			foreach((array)$array['displayRules']['allowedExtensions'] as $k => $v){
				$ret['displayRules']['allowedExtensions'][$k] = sanitize_text_field($v);
			}
			foreach((array)$array['displayRules']['allowedImageAddress'] as $k => $v){
				$ret['displayRules']['allowedImageAddress'][$k] = esc_url($v);
			}
		}
		
		//lockedMechanism
		if(isset($array['lockedMechanism'])){
			if(isset($array['lockedMechanism']['afterProceed'])) $ret['lockedMechanism']['afterProceed'] = (float)$array['lockedMechanism']['afterProceed'];
			if(isset($array['lockedMechanism']['afterProceed'])) $ret['lockedMechanism']['afterClose'] = (float)$array['lockedMechanism']['afterClose'];
		}
		
		//event handler
		if(isset($array['eventHandler'])){
			if(isset($array['eventHandler']['type'])) $ret['eventHandler']['type'] = sanitize_text_field($array['eventHandler']['type']);
			if((float)$array['eventHandler']['delay_value'] > 0) $ret['eventHandler']['delay_value'] = (float)$array['eventHandler']['delay_value']; else $ret['eventHandler']['delay_value']=0;
			if((float)$array['eventHandler']['scrolling_value'] > 0) $ret['eventHandler']['scrolling_value'] = (float)$array['eventHandler']['scrolling_value']; else $ret['eventHandler']['scrolling_value']=0;
		
		}		
		//send mail window
		if(isset($array['sendMailWindow'])){
			$ret['sendMailWindow'] = $this->prepareCommonDesignSettings($array['sendMailWindow']);
			if($array['sendMailWindow']['enable'] == 'on') $ret['sendMailWindow']['enable'] = 'on'; else $ret['sendMailWindow']['enable'] = '';
		}else{
			$ret['sendMailWindow']['enable'] = '';
		}
		//thank popup
		if(isset($array['thank'])){
			$ret['thank'] = $this->prepareCommonDesignSettings($array['thank']);
			if($array['thank']['enable'] == 'on') $ret['thank']['enable'] = 'on'; else $ret['thank']['enable'] = '';
		}else{
			$ret['thank']['enable'] = '';
		}
		
		return $ret;
	}
	
	
	 /**
     * Manages the WP settings page
     * 
     * @return null
     */
    function PQ_ES_Options()
    {
          if ((!current_user_can('editor') && !current_user_can('administrator'))) {
            wp_die(
                __('You do not have sufficient permissions to access this page.')
            );
        }
		
		wp_enqueue_script('wp-color-picker'); 
		
		if(WP_DEBUG === true){		
			wp_die(
                __('You need to seup WP_DEBUG parameters to false in wp-config.php file')
            );
		}
		
		
		
		echo "			
		<noscript>
				<p>Please enable JavaScript in your browser.</p>
		</noscript>
		";	
		
		if($_GET[act] == 'set_pro_loader'){			
			$this->_options[settings][pro_loader_filename] = $this->getDomain().'.pq_pro_loader';
		}
		
		if($_GET[act] == 'saveApiKey' && trim($_GET[apiKey])){
			if(!$this->_options[settings][apiKey]){							
				$this->activatePluginVersion();
				$this->setDefaultProductData();
			}
			$this->_options[settings][apiKey] = sanitize_text_field($_GET[apiKey]);
			update_option('profitquery', $this->_options);
		}
		
		if($_GET[act] == 'clearXXXFFFF'){
			$aio_loaded = (int)$this->_options['pq_es_widgets_loaded'];			
			$this->_options = array();		
			
			$this->_options['pq_es_widgets_loaded'] = $aio_loaded;
			update_option('profitquery', $this->_options);			
		}		
		
		$canStart = 0;
		if(trim($this->_options[settings][apiKey])){
			$canStart = 1;
		}				
		
		$this->getPluginSettings();
		//Start Main Plugin
		if($canStart)
		{
			
			
			$successSendEmail = 0;
			if($_POST[action] == 'needHelp'){
				if(trim($_POST[message])){
					$message .= "Massge : ".sanitize_text_field($_POST[message])."\n";
					$message .= "From : ".sanitize_email($_POST[from])."\n";
					$message .= "Domain : ".sanitize_text_field($this->getFullDomain())."\n";
					wp_mail( 'support@profitquery.com', '[PQ_ES_WP]'.esc_url($this->getFullDomain()).' Support', $message );
					$successSendEmail = 1;
				}
			}
			
						
			/*********************SAVE***********************/
			if($_POST[action] == "setLang"){				
				if($this->_plugin_settings[lang][$_POST[lang]]){
					$this->_options[settings][lang] = sanitize_text_field($_POST[lang]);
				}
				update_option('profitquery', $this->_options);
			}
			
			if($_POST[action] == 'settingsSave'){
				//
				$this->_options[settings][apiKey] = sanitize_text_field($_POST[settings][apiKey]);
				$this->_options[settings][pro_loader_filename] = sanitize_text_field($_POST[settings][pro_loader_filename]);
				$this->_options[settings][mainPage] = sanitize_text_field($_POST[settings][mainPage]);
				$this->_options[settings][email] = sanitize_email($_POST[settings][email]);
				$this->_options[settings][code] = sanitize_text_field($_POST[settings][code]);
				if($_POST[settings][enableGA] == 'on') $this->_options[settings][enableGA] = 'on'; else $this->_options[settings][enableGA] = '';
				if($_POST[settings][from_right_to_left] == 'on') $this->_options[settings][from_right_to_left] = 'on'; else $this->_options[settings][from_right_to_left] = '';				
				
				update_option('profitquery', $this->_options);
			}
			
			
			//REVIEW BLOCK
			if($_GET[act] == 'review'){
				$this->_options[settings][pq_es_click_review] = 1;
				update_option('profitquery', $this->_options);								
				echo '<script>location.href="'.$this->_plugin_review_url.'";</script>';
				exit;				
			}
			if($_GET[act] == 'later'){
				$this->_options[settings][pq_es_later_click_time] = time()+60*60*24*3;
				update_option('profitquery', $this->_options);												
			}
			
			
			if((int)$this->_options[settings][pq_es_click_review] == 0 && (int)$this->_options[settings][pq_es_later_click_time] < time()){
				$disableReview = 0;
			}else{
				$disableReview = 1;
			}
			
			//echo $disableReview;
			//die();
			
			/*******************Get APi Key******************/
			if($_GET[apiKey]){
				$this->_options[settings][apiKey] = sanitize_text_field($_GET[apiKey]);
				update_option('profitquery', $this->_options);
			}
					
			/**********************SAVE TOOLS**************************/
			$this->parseThemesText();
			$this->parseThemes();
			
			
			$providerRedirectByError = '';
			
			//emailListBuilderPopup
			if($_POST[action] == 'emailListBuilderPopupSave'){
				$this->_options[emailListBuilderPopup] = $this->prepareUserDataToSave($_POST[emailListBuilderPopup]);					
				$this->_options[emailListBuilderPopup][themeClass] = $this->_themes[emailListBuilderPopup][$_POST[emailListBuilderPopup][theme]][addClass];
				
				if($this->_options[emailListBuilderPopup][providerForm]){
					$data = $this->parseSubscribeProviderForm(trim($this->_options[emailListBuilderPopup][provider]), $this->_options[emailListBuilderPopup][providerForm]);
					if((int)$data[is_error]){
						$this->_options[emailListBuilderPopup][enable] = '';
						$providerRedirectByError = 'emailListBuilderPopup';
						$this->_options[emailListBuilderPopup][providerOption][is_error] = 1;
					}else{					
						$this->_options[emailListBuilderPopup][providerOption] = $data;
						$this->_options[emailListBuilderPopup][themeClass] = $this->_themes[emailListBuilderPopup][$_POST[emailListBuilderPopup][theme]][addClass];
						$this->_options[emailListBuilderPopup][enable] = 'on';
					}
				}else{
					$providerRedirectByError = 'emailListBuilderPopup';
					$this->_options[emailListBuilderPopup][enable] = '';
					$this->_options[emailListBuilderPopup][providerOption][is_error] = 1;
				}	
				update_option('profitquery', $this->_options);
						
			}
			
			

			//emailListBuilderBar
			if($_POST[action] == 'emailListBuilderBarSave'){
				$this->_options[emailListBuilderBar] = $this->prepareUserDataToSave($_POST[emailListBuilderBar]);					
				$this->_options[emailListBuilderBar][themeClass] = $this->_themes[emailListBuilderBar][$_POST[emailListBuilderBar][theme]][addClass];
				
				//autoupdate
				$this->_options[emailListBuilderBar][background_opacity] = 10;
				
				if($this->_options[emailListBuilderBar][providerForm]){
					$data = $this->parseSubscribeProviderForm(trim($this->_options[emailListBuilderBar][provider]), $this->_options[emailListBuilderBar][providerForm]);
					if((int)$data[is_error]){
						$this->_options[emailListBuilderBar][enable] = '';
						$providerRedirectByError = 'emailListBuilderBar';
						$this->_options[emailListBuilderBar][providerOption][is_error] = 1;
					}else{					
						$this->_options[emailListBuilderBar][providerOption] = $data;
						$this->_options[emailListBuilderBar][themeClass] = $this->_themes[emailListBuilderBar][$_POST[emailListBuilderBar][theme]][addClass];
						$this->_options[emailListBuilderBar][enable] = 'on';
					}
				}else{
					$providerRedirectByError = 'emailListBuilderBar';
					$this->_options[emailListBuilderBar][enable] = '';
					$this->_options[emailListBuilderBar][providerOption][is_error] = 1;
				}	
				update_option('profitquery', $this->_options);								
			}

			//emailListBuilderFloating
			if($_POST[action] == 'emailListBuilderFloatingSave'){
				$this->_options[emailListBuilderFloating] = $this->prepareUserDataToSave($_POST[emailListBuilderFloating]);					
				$this->_options[emailListBuilderFloating][themeClass] = $this->_themes[emailListBuilderFloating][$_POST[emailListBuilderFloating][theme]][addClass];
				
				if($this->_options[emailListBuilderFloating][providerForm]){
					$data = $this->parseSubscribeProviderForm(trim($this->_options[emailListBuilderFloating][provider]), $this->_options[emailListBuilderFloating][providerForm]);
					if((int)$data[is_error]){
						$this->_options[emailListBuilderFloating][enable] = '';
						$providerRedirectByError = 'emailListBuilderFloating';
						$this->_options[emailListBuilderFloating][providerOption][is_error] = 1;
					}else{					
						$this->_options[emailListBuilderFloating][providerOption] = $data;
						$this->_options[emailListBuilderFloating][themeClass] = $this->_themes[emailListBuilderFloating][$_POST[emailListBuilderFloating][theme]][addClass];
						$this->_options[emailListBuilderFloating][enable] = 'on';
					}
				}else{
					$providerRedirectByError = 'emailListBuilderFloating';
					$this->_options[emailListBuilderFloating][enable] = '';
					$this->_options[emailListBuilderFloating][providerOption][is_error] = 1;
				}	
				update_option('profitquery', $this->_options);					
			}
			
						
			$this->parseProLoader();			
			$this->getDictionary();
			
			/***********************DISABLE OPTION*****************************/
			$needOpenActivatePopup = 0;
			$needOpenProInfoPopup = 0;
			if($_POST[action] == 'disableOption'){				
				if($_POST[tool]){					
					foreach((array)$this->_toolsName as $k => $v){
						$this->_options[$v][theme] = $this->_default_themes[$v][theme];
						$this->_options[$v][themeClass] = $this->_options[$v][addClass] = $this->_default_themes[$v][addClass];
						
						$this->_options[$v]['header_image_src']='';
						$this->_options[$v]['header_img_type']='';
						$this->_options[$v]['background_image_src']='';
						$this->_options[$v]['overlay_image_src']='';
						$this->_options[$v]['showup_animation']='';
						
						$this->_options[$v]['ss_view_type']='';
						$this->_options[$v]['ss_color']='';
						$this->_options[$v]['ss_background_color']='';
						$this->_options[$v]['ss_color_opacity']='';
						$this->_options[$v]['ss_background_color_opacity']='';
						
					}
					
					
					
										
					update_option('profitquery', $this->_options);
				}
				if($_POST[currentLocation] == 'activate_popup'){
					$needOpenActivatePopup = 1;
				}
							
				if($_POST[currentLocation] == 'pro_info'){
					$needOpenProInfoPopup = 1;
				}
			}									
			
			//$this->printr($this->_options[$_POST[tool]]);
			//die();
			
			//disable Tools
			if($_POST[action] == 'disable' && $_POST[toolsID]){
				$this->_options[$_POST[toolsID]][enable] = 0;
				update_option('profitquery', $this->_options);	
			}
			
			//delete Tools
			if($_POST[action] == 'delete' && $_POST[toolsID]){
				unset($this->_options[$_POST[toolsID]]);
				update_option('profitquery', $this->_options);	
			}
			
			//enable Tools
			if($_POST[action] == 'enable' && $_POST[toolsID]){				
				$this->_options[$_POST[toolsID]][enable] = 'on';
				update_option('profitquery', $this->_options);	
			}
			
									
			/*Check for dublicate sharingSidebar socnet_with_pos*/		
			$sharingSocnetErrorArray = $this->getSharingSocnetErrorArray();		
			
			$toolsArray = $this->getToolsArray();					
			//$this->printr($this->_options[contactFormPopup]);
			//printr($this->_themes);
			//die();
			
			$positionArray = $this->getArrayPosition($toolsArray);			
			$selectedProOptions = $this->getArrayProOptionsSelected($toolsArray);			
			$paidProOptions = $this->getArrayProOptionsPaid();
			$getAllFreeTools = $this->getArrayAllFreeTools();
			
			
			//$this->printr($getAllFreeTools);
			//die();
			//$this->printr($paidProOptions);
			//die();
			$trialOptions = $this->_trialOptions();
			$affiliateOptions = $this->_affiliateOptions();			
			$proToolsOptions = $this->_proToolsOptions();
			//$this->printr($trialOptions);
			//$this->printr($affiliateOptions);
			//die();
		//	printr($selectedProOptions);
		//	die();
			
			$defaultToolPosition = $this->getDefaultToolPosition();			
			
			/****************UPGRADE TOOL POPUP************************/			
			if($_GET[act] == 'later_upgrade'){
				$this->_options[settings][pq_es_later_update_click_time] = time()+60*60*24*7;
				update_option('profitquery', $this->_options);
			}
			//$this->_options[settings][pq_es_later_update_click_time] = 0;
			//update_option('profitquery', $this->_options);
			$upgradeToolPopup = 0;
			if($_GET[act] == 'upgrade' && $_GET[tool] == 'pro'){
				$upgradeToolPopup = 1;				
				$this->_options[settings][pq_es_later_update_click_time] = time()+60*60*24*14;
				update_option('profitquery', $this->_options);
			}
			
			// Need Upgrade Popup
			if((int)$proToolsOptions[status]==0 && (int)$this->_options[settings][pq_es_later_update_click_time] < time()){
				$disableNeedUpgradePopup = 0;
			}else{
				$disableNeedUpgradePopup = 1;
			}
						
			//$disableNeedUpgradePopup = 0;
	?>
			
	<script>	
	
	function hexToRgb(hex) {
		var result = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i.exec(hex);
		return result ? {
			r: parseInt(result[1], 16),
			g: parseInt(result[2], 16),
			b: parseInt(result[3], 16)
		} : null;
	}
	
	function enableBlockByCheckboxClick(t, id){
		if(t){
			document.getElementById(id).style.display = 'block';
		}else{
			document.getElementById(id).style.display = 'none';
		}
	}

	function enableAdditionalBlock(val, id_to_block, arr){
		for(var i in arr){
			try{		
				document.getElementById(id_to_block+arr[i]).style.display = 'none';		
			}catch(err){};
		}
		try{			
			if(val != "") document.getElementById(id_to_block+val).style.display = 'block';
		}catch(err){};
	}

	function PQdeserialize (data) {
		data === "" && (data = '""');
		try {
			eval("var __tempPQRetData");
			eval("__tempPQRetData=" + data)
		} catch (b) {}
		return __tempPQRetData;
	}


	function clearClassName(str){
		return str.split(' ')[0];
	}

	function setEHBlockActive(id, type){
		try{
			if(!document.getElementById(id+'_eventHandler_'+type).disabled){
				document.getElementById(id+'_eventHandler_'+type).checked = true;	
			}
		}catch(err){};
	}

	function changeStatusToRadio(partId, current, arr){
		for(var i in arr){		
			try{document.getElementById(partId+arr[i]).checked = false;}catch(err){};			
		}	
		try{
			document.getElementById(partId+current).checked = true;			
			}catch(err){};	
	}
	var PQ_RTL = '<?php if($this->_options[settings][from_right_to_left] == 'on') echo 1; else echo 0;?>';	
	var PQThemes = PQdeserialize('<?php echo str_replace("'", "\'",json_encode($this->_themes));?>');
	var PQDefaultPosition = PQdeserialize('<?php echo str_replace("'", "\'",json_encode($defaultToolPosition));?>');
	var PQToolsPosition = PQdeserialize('<?php echo str_replace("'", "\'",json_encode($positionArray));?>');
	var PQPluginSettings = PQdeserialize('<?php echo str_replace("'", "\'",json_encode($this->_plugin_settings));?>');	
	var PQPluginDict = PQdeserialize('<?php echo str_replace("'", "\'",json_encode($this->_dictionary));?>');	
	var PQPositionValues = {
			"BAR_TOP":'pq_top',
			"BAR_BOTTOM":'pq_bottom',
			"SIDE_LEFT_TOP":'pq_left pq_top',
			"SIDE_LEFT_MIDDLE":'pq_left pq_middle',
			"SIDE_LEFT_BOTTOM":'pq_left pq_bottom',
			"SIDE_RIGHT_TOP":'pq_right pq_top',
			"SIDE_RIGHT_MIDDLE":"pq_right pq_middle",
			"SIDE_RIGHT_BOTTOM":'pq_right pq_bottom',
			"CENTER":'',
			"FLOATING_LEFT_TOP":'pq_left pq_top',
			"FLOATING_LEFT_BOTTOM":'pq_left pq_bottom',
			"FLOATING_RIGHT_TOP":'pq_right pq_top',
			"FLOATING_RIGHT_BOTTOM":'pq_right pq_bottom'
	}
	
	function strstr( haystack, needle, bool ) {
		var pos = 0;
		try{
			pos = haystack.indexOf( needle );
			if( pos == -1 ){
				return false;
			} else{
				if( bool ){
					return haystack.substr( 0, pos );
				} else{
					return haystack.slice( pos );
				}
			}
		}catch(err){return false;}
	}
	
	function str_replace(search, replace, subject) {
		var _PQReturnData = subject;
		try{
			_PQReturnData = subject.split(search).join(replace);
		} catch(err){};
		return _PQReturnData;
	}
	
	function getNameById(id){
		var ret = '';		
		if(id == 'PQSharingSidebar'){ret = 'sharingSidebar'; }
		if(id == 'PQImageSharer'){ret = 'imageSharer'; }
		if(id == 'PQSharingPopup'){ret = 'sharingPopup'; }
		if(id == 'PQSharingBar'){ret = 'sharingBar'; }
		if(id == 'PQSharingFloating'){ret = 'sharingFloating'; }
		if(id == 'PQEmailListBuilderPopup'){ret = 'emailListBuilderPopup'; }
		if(id == 'PQEmailListBuilderBar'){ret = 'emailListBuilderBar'; }
		if(id == 'PQEmailListBuilderFloating'){ret = 'emailListBuilderFloating'; }
		if(id == 'PQcontactFormPopup'){ret = 'contactFormPopup'; }
		if(id == 'PQcontactFormCenter'){ret = 'contactFormCenter'; }
		if(id == 'PQcontactFormFloating'){ret = 'contactFormFloating'; }
		if(id == 'PQPromotePopup'){ret = 'promotePopup'; }
		if(id == 'PQPromoteBar'){ret = 'promoteBar'; }
		if(id == 'PQPromoteFloating'){ret = 'promoteFloating'; }
		if(id == 'PQCallMePopup'){ret = 'callMePopup'; }
		if(id == 'PQCallMeFloating'){ret = 'callMeFloating'; }
		if(id == 'PQFollowPopup'){ret = 'followPopup'; }
		if(id == 'PQFollowBar'){ret = 'followBar'; }
		if(id == 'PQFollowFloating'){ret = 'followFloating'; }
		if(id == 'PQiframePopup'){ret = 'iframePopup'; }
		if(id == 'PQiframeFloating'){ret = 'iframeFloating'; }
		if(id == 'PQyoutubePopup'){ret = 'youtubePopup'; }
		if(id == 'PQyoutubeFloating'){ret = 'youtubeFloating'; }
		return ret;
		
	}
	
	function getIdByName(name){
		var ret = '';
		if(name == 'sharingSidebar_sendMail'){ ret = 'PQSharingSidebar'; }
		if(name == 'sharingSidebar_thank'){ ret = 'PQSharingSidebar'; }
		if(name == 'sharingSidebar'){ ret = 'PQSharingSidebar'; }
		if(name == 'imageSharer'){ ret = 'PQImageSharer'; }
		if(name == 'imageSharer_sendMail'){ ret = 'PQImageSharer'; }
		if(name == 'imageSharer_thank'){ ret = 'PQImageSharer'; }
		if(name == 'sharingPopup'){ ret = 'PQSharingPopup'; }
		if(name == 'sharingPopup_thank'){ ret = 'PQSharingPopup'; }
		if(name == 'sharingBar'){ ret = 'PQSharingBar'; }
		if(name == 'sharingBar_thank'){ ret = 'PQSharingBar'; }
		if(name == 'sharingFloating'){ ret = 'PQSharingFloating'; }
		if(name == 'sharingFloating_thank'){ ret = 'PQSharingFloating'; }
		if(name == 'emailListBuilderPopup'){ ret = 'PQEmailListBuilderPopup'; }
		if(name == 'emailListBuilderPopup_thank'){ ret = 'PQEmailListBuilderPopup'; }
		if(name == 'emailListBuilderBar'){ ret = 'PQEmailListBuilderBar'; }
		if(name == 'emailListBuilderBar_thank'){ ret = 'PQEmailListBuilderBar'; }
		if(name == 'emailListBuilderFloating'){ ret = 'PQEmailListBuilderFloating'; }
		if(name == 'emailListBuilderFloating_thank'){ ret = 'PQEmailListBuilderFloating'; }
		if(name == 'contactFormPopup'){ ret = 'PQcontactFormPopup'; }
		if(name == 'contactFormPopup_thank'){ ret = 'PQcontactFormPopup'; }
		if(name == 'contactFormCenter'){ ret = 'PQcontactFormCenter'; }
		if(name == 'contactFormCenter_thank'){ ret = 'PQcontactFormCenter'; }
		if(name == 'contactFormFloating'){ ret = 'PQcontactFormFloating'; }
		if(name == 'contactFormFloating_thank'){ ret = 'PQcontactFormFloating'; }
		if(name == 'promotePopup'){ ret = 'PQPromotePopup'; }
		if(name == 'promotePopup_thank'){ ret = 'PQPromotePopup'; }
		if(name == 'promoteBar'){ ret = 'PQPromoteBar'; }
		if(name == 'promoteBar_thank'){ ret = 'PQPromoteBar'; }
		if(name == 'promoteFloating'){ ret = 'PQPromoteFloating'; }
		if(name == 'promoteFloating_thank'){ ret = 'PQPromoteFloating'; }
		if(name == 'callMePopup'){ ret = 'PQCallMePopup'; }
		if(name == 'callMePopup_thank'){ ret = 'PQCallMePopup'; }
		if(name == 'callMeFloating'){ ret = 'PQCallMeFloating'; }
		if(name == 'callMeFloating_thank'){ ret = 'PQCallMeFloating'; }
		if(name == 'followPopup'){ ret = 'PQFollowPopup'; }
		if(name == 'followPopup_thank'){ ret = 'PQFollowPopup'; }
		if(name == 'followBar'){ ret = 'PQFollowBar'; }
		if(name == 'followBar_thank'){ ret = 'PQFollowBar'; }
		if(name == 'followFloating'){ ret = 'PQFollowFloating'; }
		if(name == 'followFloating_thank'){ ret = 'PQFollowFloating'; }
		if(name == 'iframePopup'){ ret = 'PQiframePopup'; }
		if(name == 'iframePopup_thank'){ ret = 'PQiframePopup'; }
		if(name == 'iframeFloating'){ ret = 'PQiframeFloating'; }
		if(name == 'iframeFloating_thank'){ ret = 'PQiframeFloating'; }
		if(name == 'youtubePopup'){ ret = 'PQyoutubePopup'; }
		if(name == 'youtubePopup_thank'){ ret = 'PQyoutubePopup'; }
		if(name == 'youtubeFloating'){ ret = 'PQyoutubeFloating'; }
		if(name == 'youtubeFloating_thank'){ ret = 'PQyoutubeFloating'; }
		return ret;
	}
	
	function closeDesignForm(name){
		try{			
			document.getElementById(name+'_form_block').style.display='none';			
		}catch(err){console.log(err)};
		try{
			document.getElementById(getIdByName(name)+'_PQIframePreviewBlock').className = str_replace('pq_min', '', document.getElementById(getIdByName(name)+'_PQIframePreviewBlock').className);
		}catch(err){console.log(err)};
			
		
		
	}
	
	function enableDesignFormByBlockClick(toolName, blockKey, arr){		
		try{
			var arr = arr.split(',');
		}catch(err){}
		for(var i in arr){				
			try{
				document.getElementById(toolName+'_'+arr[i]+'_design_options_block').style.display = 'none';
				document.getElementById(toolName+'_'+arr[i]+'_design_icons_block').className = 'pq_icon';				
			}catch(err){}
		}
		
		
		
		try{
			document.getElementById(getIdByName(toolName)+'_PQIframePreviewBlock').className  += ' pq_min';
		}catch(err){}
		
		if(strstr(blockKey, 'mobile_')){
			try{
				document.getElementById(toolName+'_form_block').style.display = 'none';
			}catch(err){}
			try{
				document.getElementById(toolName+'_mobile_form_block').style.display = 'block';
			}catch(err){}
		}else{
			try{
				document.getElementById(toolName+'_form_block').style.display = 'block';
			}catch(err){}
			try{
				document.getElementById(toolName+'_mobile_form_block').style.display = 'none';
			}catch(err){}
		}
		
		
		
		
		
		try{
			document.getElementById(toolName+'_'+blockKey+'_design_options_block').style.display = 'block';
		}catch(err){}
		try{
			document.getElementById(toolName+'_'+blockKey+'_design_icons_block').className = 'pq_icon pq_selected';
		}catch(err){}			
	}
	
	//****************************************COLORPICK SCRIPTS
	function closeAllColorPickContainer(){
		try{document.getElementById("sharingSidebar_ss_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_ss_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_galleryOption_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_galleryOption_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_galleryOption_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_galleryOption_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_moreShareWindow_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_moreShareWindow_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_moreShareWindow_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_moreShareWindow_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_background_mobile_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_mblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		
		try{document.getElementById("imageSharer_ss_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_ss_background_color_colorPickContainer").style.display='none';}catch(err){};

		try{document.getElementById("sharingSidebar_thank_background_color_colorPickContainer").style.display='none';}catch(err){};		
		try{document.getElementById("sharingSidebar_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("sharingSidebar_sendMailWindow_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_sendMailWindow_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_sendMailWindow_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_sendMailWindow_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_sendMailWindow_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_sendMailWindow_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_sendMailWindow_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_sendMailWindow_background_form_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingSidebar_sendMailWindow_background_button_block_colorPickContainer").style.display='none';}catch(err){};



		try{document.getElementById("imageSharer_sendMailWindow_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_sendMailWindow_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_sendMailWindow_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_sendMailWindow_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_sendMailWindow_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_sendMailWindow_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_sendMailWindow_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_sendMailWindow_background_form_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_sendMailWindow_background_button_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("imageSharer_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("imageSharer_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("sharingPopup_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};



		try{document.getElementById("sharingBar_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("sharingFloating_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("emailListBuilderPopup_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};

		
		try{document.getElementById("emailListBuilderBar_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("emailListBuilderFloating_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("contactFormPopup_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};

		try{document.getElementById("contactFormCenter_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};

		try{document.getElementById("contactFormFloating_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("promotePopup_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("promoteBar_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};
		
		

		try{document.getElementById("promoteFloating_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("callMePopup_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("callMeFloating_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("followPopup_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("followBar_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("followFloating_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("iframePopup_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};
		
		try{document.getElementById("iframeFloating_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("youtubePopup_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};
		
		try{document.getElementById("youtubeFloating_thank_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_thank_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_thank_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_thank_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_thank_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_thank_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_thank_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_thank_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_thank_background_soc_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("sharingPopup_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingPopup_background_soc_block_colorPickContainer").style.display='none';}catch(err){};



		try{document.getElementById("sharingBar_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingBar_background_soc_block_colorPickContainer").style.display='none';}catch(err){};



		try{document.getElementById("sharingFloating_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_background_soc_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("sharingFloating_background_soc_block_colorPickContainer").style.display='none';}catch(err){};





		try{document.getElementById("emailListBuilderPopup_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderPopup_button_color_colorPickContainer").style.display='none';}catch(err){};		
		try{document.getElementById("emailListBuilderPopup_background_text_block_colorPickContainer").style.display='none';}catch(err){};		
		try{document.getElementById("emailListBuilderPopup_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};		
		try{document.getElementById("emailListBuilderPopup_background_form_block_colorPickContainer").style.display='none';}catch(err){};		
		try{document.getElementById("emailListBuilderPopup_background_button_block_colorPickContainer").style.display='none';}catch(err){};		

		
		
		

		try{document.getElementById("emailListBuilderBar_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_background_form_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderBar_background_button_block_colorPickContainer").style.display='none';}catch(err){};



		try{document.getElementById("emailListBuilderFloating_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_background_form_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("emailListBuilderFloating_background_button_block_colorPickContainer").style.display='none';}catch(err){};



		try{document.getElementById("contactFormPopup_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_background_form_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_background_button_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_bookmark_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormPopup_bookmark_background_color_colorPickContainer").style.display='none';}catch(err){};

		try{document.getElementById("contactFormCenter_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_background_form_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_background_button_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_bookmark_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormCenter_bookmark_background_color_colorPickContainer").style.display='none';}catch(err){};

		try{document.getElementById("contactFormFloating_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_background_form_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("contactFormFloating_background_button_block_colorPickContainer").style.display='none';}catch(err){};


		try{document.getElementById("promotePopup_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promotePopup_background_button_block_colorPickContainer").style.display='none';}catch(err){};



		try{document.getElementById("promoteBar_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteBar_background_button_block_colorPickContainer").style.display='none';}catch(err){};
		
		

		try{document.getElementById("promoteFloating_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("promoteFloating_background_button_block_colorPickContainer").style.display='none';}catch(err){};



		try{document.getElementById("callMePopup_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_background_button_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_bookmark_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_bookmark_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMePopup_background_form_block_colorPickContainer").style.display='none';}catch(err){};



		try{document.getElementById("callMeFloating_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_background_button_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("callMeFloating_background_form_block_colorPickContainer").style.display='none';}catch(err){};



		try{document.getElementById("followPopup_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followPopup_background_soc_block_colorPickContainer").style.display='none';}catch(err){};



		try{document.getElementById("followBar_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followBar_background_soc_block_colorPickContainer").style.display='none';}catch(err){};



		try{document.getElementById("followFloating_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("followFloating_background_soc_block_colorPickContainer").style.display='none';}catch(err){};



		try{document.getElementById("iframePopup_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframePopup_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};

		try{document.getElementById("iframeFloating_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("iframeFloating_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};

		try{document.getElementById("youtubePopup_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubePopup_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
		
		try{document.getElementById("youtubeFloating_background_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_head_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_close_icon_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_border_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_overlay_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_button_text_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_button_color_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_background_text_block_colorPickContainer").style.display='none';}catch(err){};
		try{document.getElementById("youtubeFloating_tblock_text_font_color_colorPickContainer").style.display='none';}catch(err){};
	}
	
	function openColorPickContainer(id){
		closeAllColorPickContainer();		
		document.getElementById(id).style.display = 'block';
	}	
	
	
	//*************************************CHECKOUT SCRIPTS
	//TO EXTEND
	function changeExtendCheckoutPeriod(){
		try{
			var val = document.getElementById('PQExtendPopup_ExtendPeriod').value;	
			var summ = 0;			
			var summs = PQPluginSettings.price['pro_'+val]||0;			
			var arrayWorkedID = [];
			var ifMailAlreadyChecked = false;
			var mailSave = 0;
			var save = 0;
			var allSumm = 0;			
			
			allSumm = Math.round(PQPluginSettings.price['pro_1']*val);			
			save = allSumm-summs;
			
			if(save > 0){
				document.getElementById('PQExtendPopup_Price_Text').innerHTML = '<?php echo $this->_dictionary[activatePopup][total];?> '+summs+'$';
				document.getElementById('PQExtendPopup_Price_Text').style.display = 'block';
				document.getElementById('PQExtendPopup_Submit').value = '<?php echo $this->_dictionary[activatePopup][get_pro_and_save];?> $'+save;
			}else{
				document.getElementById('PQExtendPopup_Price_Text').innerHTML = '';
				document.getElementById('PQExtendPopup_Price_Text').style.display = 'none';
				document.getElementById('PQExtendPopup_Submit').value = '<?php echo $this->_dictionary[activatePopup][get_pro];?>';
			}
			
			if(summs > 0){
				document.getElementById('PQExtendPopup_Submit').disabled=false;
			}else{
				document.getElementById('PQExtendPopup_Submit').disabled=true;
			}
		}catch(err){console.log(err)};
	}
	//TO CHECKOUT
	function changeActivateCheckoutPeriod(){		
		try{
			var val = document.getElementById('PQActivatePopup_Period').value;	
			var summ = 0;			
			var summs = PQPluginSettings.price['pro_'+val]||0;			
			var arrayWorkedID = [];
			var ifMailAlreadyChecked = false;
			var mailSave = 0;
			var save = 0;
			var allSumm = 0;			
			
			allSumm = Math.round(PQPluginSettings.price['pro_1']*val);			
			save = allSumm-summs;
			
			if(save > 0){
				document.getElementById('PQActivatePopup_Price_Text').innerHTML = '<?php echo $this->_dictionary[activatePopup][total];?> '+summs+'$';
				document.getElementById('PQActivatePopup_Price_Text').style.display = 'block';
				document.getElementById('PQActivateForm_Submit').value = '<?php echo $this->_dictionary[activatePopup][get_pro_and_save];?> $'+save;
			}else{
				document.getElementById('PQActivatePopup_Price_Text').innerHTML = '';
				document.getElementById('PQActivatePopup_Price_Text').style.display = 'none';
				document.getElementById('PQActivateForm_Submit').value = '<?php echo $this->_dictionary[activatePopup][get_pro];?>';
			}
			
			if(summs > 0){
				document.getElementById('PQActivateForm_Submit').disabled=false;
			}else{
				document.getElementById('PQActivateForm_Submit').disabled=true;
			}
		}catch(err){console.log(err)};
	}






	//**************************************PreviewThemeScript
	function getNextElemFromObject(obj, current){
		var ret = {};
		var _flagFindCurrent = false;
		for(var i in obj){
			if(_flagFindCurrent){
				return {theme:i, obj:obj[i]};
			}else if(i == current){
				_flagFindCurrent = true;
			}
		}
		return 0;
	}

	function getPrevElemFromObject(obj, current){
		var ret = 0;
		var _flagFindCurrent = false;
		for(var i in obj){				
			if(i == current){
				return ret;
			}else{
				ret = {theme:i, obj:obj[i]};
			}
		}
		return 0;
	}

	function getPrevArrow(themeObjectId, theme){
		if(getPrevElemFromObject(PQThemes[themeObjectId], theme) == 0){
			document.getElementById('Popup_ThemePreview_Prev').innerHTML = '';			
		}else{
			document.getElementById('Popup_ThemePreview_Prev').innerHTML = '<img src="<?php echo plugins_url('i/scroll_left.png', __FILE__);?>" />';
		}
	}

	function getNextArrow(themeObjectId, theme){
		if(getNextElemFromObject(PQThemes[themeObjectId], theme) == 0){
			document.getElementById('Popup_ThemePreview_Next').innerHTML = '';			
		}else{
			document.getElementById('Popup_ThemePreview_Next').innerHTML = '<img src="<?php echo plugins_url('i/scroll_right.png', __FILE__);?>" />';
		}
	}

	function setThemePreviewData(obj){
		document.getElementById('PQThemeSelectPreviewPopup').style.display = 'block';
		document.getElementById('Popup_ThemePreview_big_src').src = obj.src
		document.getElementById(document.getElementById('PQThemeSelectCurrentToolID').value+'_CurrentThemeForPreview').value = obj.theme
		document.getElementById('Popup_ThemePreview_Title').innerHTML = obj.title
		document.getElementById('Popup_ThemePreview_Description').innerHTML = obj.description
		document.getElementById('Popup_ThemePreview_DIV').className = clearClassName(document.getElementById('Popup_ThemePreview_DIV').className);	
		if(Number(obj.price)>0){
			document.getElementById('Popup_ThemePreview_DIV').className += ' pq_premium';
		}
	}

	function previewThemeNext(){	
		var currentTheme = document.getElementById(document.getElementById('PQThemeSelectCurrentToolID').value+'_CurrentThemeForPreview').value;	
		var themeObjectId = document.getElementById('PQThemeSelectCurrentID').value;
		var nextObj = getNextElemFromObject(PQThemes[themeObjectId], currentTheme);	
		if(nextObj != 0){
			setThemePreviewData({src:nextObj.obj.preview_image_big, title:nextObj.obj.title, description:nextObj.obj.description, theme:nextObj.theme, price:nextObj.obj.price});
			
			document.getElementById(document.getElementById('PQThemeSelectCurrentToolID').value+'_CurrentThemeForPreview').value = nextObj.theme;
			
			getNextArrow(themeObjectId, nextObj.theme);
			document.getElementById('Popup_ThemePreview_Prev').innerHTML = '<img src="<?php echo plugins_url('i/scroll_left.png', __FILE__);?>" />';
		}
	}



	function previewThemePrev(){
		var currentTheme = document.getElementById(document.getElementById('PQThemeSelectCurrentToolID').value+'_CurrentThemeForPreview').value;
		var themeObjectId = document.getElementById('PQThemeSelectCurrentID').value;
		var prevObject = getPrevElemFromObject(PQThemes[themeObjectId], currentTheme);	
		if(prevObject != 0){
			setThemePreviewData({src:prevObject.obj.preview_image_big, title:prevObject.obj.title, description:prevObject.obj.description, theme:prevObject.theme, price:prevObject.obj.price});
			document.getElementById(document.getElementById('PQThemeSelectCurrentToolID').value+'_CurrentThemeForPreview').value = prevObject.theme;
			
			getPrevArrow(themeObjectId, prevObject.theme)
			document.getElementById('Popup_ThemePreview_Next').innerHTML = '<img src="<?php echo plugins_url('i/scroll_right.png', __FILE__);?>" />';
		}
	}

	function previewThemeSelect(tool, themeObjectId, theme){
		document.getElementById('PQThemeSelectCurrentToolID').value=tool;
		document.getElementById('PQThemeSelectCurrentID').value=themeObjectId;
		setThemePreviewData({src:PQThemes[themeObjectId][theme].preview_image_big, title:PQThemes[themeObjectId][theme].title, description:PQThemes[themeObjectId][theme].description, theme:theme, price:PQThemes[themeObjectId][theme].price});
		
		getPrevArrow(themeObjectId, theme);
		getNextArrow(themeObjectId, theme);
	}
	
	function checkTheme(id, toolID){
		try{
			var toolTheme = document.getElementById(toolID+'_Current_Theme').value;		
			if(typeof PQThemes[id][toolTheme] != 'undefined'){
				if(Number(PQThemes[id][toolTheme].price) == 0){
					lockProOptionFromForm(id, toolID);
				}else{
					unlockProOptionFromForm(id, toolID);
				}
			}else{
				lockProOptionFromForm(id, toolID);
			}
		}catch(err){};
	}
	
	function lockProOptionFromForm(id, toolID){
		try{document.getElementById(id+'_whitelabel').value=0;}catch(err){};
		try{document.getElementById(toolID+'_pro_status').className='pq_name_status';}catch(err){};
		try{document.getElementById(toolID+'_pro_text').innerHTML=PQPluginDict.toolsStatus.free;}catch(err){};
		try{document.getElementById(toolID+'_pro_status').onclick=function(){};}catch(err){};
		try{document.getElementById(toolID+'_pro_question').innerHTML='&nbsp;';}catch(err){};
		
		try{document.getElementById(id+'_header_image_src').disabled=true;}catch(err){};
		try{document.getElementById(id+'_header_img_type').disabled=true;}catch(err){};
		try{document.getElementById(id+'_background_image_src').disabled=true;}catch(err){};
		try{document.getElementById(id+'_overlay_image_src').disabled=true;}catch(err){};
		try{document.getElementById(id+'_showup_animation').disabled=true;}catch(err){};
		
		try{document.getElementById(id+'_thank_header_image_src').disabled=true;}catch(err){};
		try{document.getElementById(id+'_thank_header_img_type').disabled=true;}catch(err){};
		try{document.getElementById(id+'_thank_background_image_src').disabled=true;}catch(err){};
		try{document.getElementById(id+'_thank_overlay_image_src').disabled=true;}catch(err){};
		try{document.getElementById(id+'_thank_showup_animation').disabled=true;}catch(err){};
		
		
		try{document.getElementById(id+'_sendMailWindow_header_image_src').disabled=true;}catch(err){};
		try{document.getElementById(id+'_sendMailWindow_header_img_type').disabled=true;}catch(err){};
		try{document.getElementById(id+'_sendMailWindow_background_image_src').disabled=true;}catch(err){};
		try{document.getElementById(id+'_sendMailWindow_overlay_image_src').disabled=true;}catch(err){};
		try{document.getElementById(id+'_sendMailWindow_showup_animation').disabled=true;}catch(err){};
		
		//new
		try{document.getElementById(id+'_ss_view_type').disabled=true;}catch(err){};		
		try{document.getElementById(id+'_ss_color').disabled=true;}catch(err){};		
		try{document.getElementById(id+'_ss_background_color').disabled=true;}catch(err){};		
		try{document.getElementById(id+'_ss_color_opacity').disabled=true;}catch(err){};		
		try{document.getElementById(id+'_ss_background_color_opacity').disabled=true;}catch(err){};
	}
	
	function unlockProOptionFromForm(id, toolID){		
		try{document.getElementById(id+'_whitelabel').value=1;}catch(err){};
		
		try{document.getElementById(toolID+'_pro_status').className='pq_name_status pq_name_status_pro';}catch(err){};			
		try{document.getElementById(toolID+'_pro_text').innerHTML=PQPluginDict.toolsStatus.pro;}catch(err){};
		try{document.getElementById(toolID+'_pro_status').onclick=function(){openChooseProPopup(toolID)};}catch(err){};
		try{document.getElementById(toolID+'_pro_question').innerHTML='?';}catch(err){};
		
		try{document.getElementById(id+'_header_image_src').disabled=false;}catch(err){};
		try{document.getElementById(id+'_header_img_type').disabled=false;}catch(err){};
		try{document.getElementById(id+'_background_image_src').disabled=false;}catch(err){};
		try{document.getElementById(id+'_overlay_image_src').disabled=false;}catch(err){};
		try{document.getElementById(id+'_showup_animation').disabled=false;}catch(err){};
		
		try{document.getElementById(id+'_thank_header_image_src').disabled=false;}catch(err){};
		try{document.getElementById(id+'_thank_header_img_type').disabled=false;}catch(err){};
		try{document.getElementById(id+'_thank_background_image_src').disabled=false;}catch(err){};
		try{document.getElementById(id+'_thank_overlay_image_src').disabled=false;}catch(err){};
		try{document.getElementById(id+'_thank_showup_animation').disabled=false;}catch(err){};
		
		try{document.getElementById(id+'_sendMailWindow_header_image_src').disabled=false;}catch(err){};
		try{document.getElementById(id+'_sendMailWindow_header_img_type').disabled=false;}catch(err){};
		try{document.getElementById(id+'_sendMailWindow_background_image_src').disabled=false;}catch(err){};
		try{document.getElementById(id+'_sendMailWindow_overlay_image_src').disabled=false;}catch(err){};
		try{document.getElementById(id+'_sendMailWindow_showup_animation').disabled=false;}catch(err){};
		
		//new
		try{document.getElementById(id+'_ss_view_type').disabled=false;}catch(err){};		
		try{document.getElementById(id+'_ss_color').disabled=false;}catch(err){};		
		try{document.getElementById(id+'_ss_background_color').disabled=false;}catch(err){};		
		try{document.getElementById(id+'_ss_color_opacity').disabled=false;}catch(err){};		
		try{document.getElementById(id+'_ss_background_color_opacity').disabled=false;}catch(err){};
	}
	
	function disableAllDesignOpt(id){
		try{document.getElementById(id+'_background_color').value='';}catch(err){};
		try{document.getElementById(id+'_bookmark_background_color').value='';}catch(err){};
		try{document.getElementById(id+'_background_button_block').value='';}catch(err){};
		try{document.getElementById(id+'_form_block_padding').value='';}catch(err){};
		try{document.getElementById(id+'_button_block_padding').value='';}catch(err){};
		try{document.getElementById(id+'_text_block_padding').value='';}catch(err){};
		try{document.getElementById(id+'_icon_block_padding').value='';}catch(err){};
		try{document.getElementById(id+'_background_text_block').value='';}catch(err){};
		try{document.getElementById(id+'_background_form_block').value='';}catch(err){};
		try{document.getElementById(id+'_background_soc_block').value='';}catch(err){};
		try{document.getElementById(id+'_overlay_color').value='';}catch(err){};
		try{document.getElementById(id+'_overlay_opacity').value='';}catch(err){};
		try{document.getElementById(id+'_button_text_color').value='';}catch(err){};
		try{document.getElementById(id+'_button_color').value='';}catch(err){};
		try{document.getElementById(id+'_head_color').value='';}catch(err){};
		try{document.getElementById(id+'_text_color').value='';}catch(err){};
		try{document.getElementById(id+'_border_color').value='';}catch(err){};		
		try{document.getElementById(id+'_close_icon_color').value='';}catch(err){};
		try{document.getElementById(id+'_tblock_text_font_color').value='';}catch(err){};
		try{document.getElementById(id+'_background_mobile_block').value='';}catch(err){};
		try{document.getElementById(id+'_mblock_text_font_color').value='';}catch(err){};
		try{document.getElementById(id+'_typeWindow').value='';}catch(err){};
		try{document.getElementById(id+'_animation').value='';}catch(err){};
		try{document.getElementById(id+'_icon_design').value='';}catch(err){};
		try{document.getElementById(id+'_icon_form').value='';}catch(err){};
		try{document.getElementById(id+'_icon_size').value='';}catch(err){};
		try{document.getElementById(id+'_icon_space').value='';}catch(err){};
		try{document.getElementById(id+'_icon_shadow').value='';}catch(err){};
		try{document.getElementById(id+'_icon_position').value='';}catch(err){};
		try{document.getElementById(id+'_icon_animation').value='';}catch(err){};
		try{document.getElementById(id+'_mobile_type').value='';}catch(err){};
		try{document.getElementById(id+'_mobile_position').value='';}catch(err){};
		try{document.getElementById(id+'_head_font_size').value='';}catch(err){};
		try{document.getElementById(id+'_text_font_size').value='';}catch(err){};
		try{document.getElementById(id+'_popup_form').value='';}catch(err){};
		try{document.getElementById(id+'_mblock_text_font_size').value='';}catch(err){};
		try{document.getElementById(id+'_font_size').value='';}catch(err){};
		try{document.getElementById(id+'_border_type').value='';}catch(err){};
		try{document.getElementById(id+'_border_depth').value='';}catch(err){};
		try{document.getElementById(id+'_button_font_size').value='';}catch(err){};
		try{document.getElementById(id+'_tblock_text_font_size').value='';}catch(err){};
		try{document.getElementById(id+'_head_font').value='';}catch(err){};
		try{document.getElementById(id+'_text_font').value='';}catch(err){};
		try{document.getElementById(id+'_mblock_text_font').value='';}catch(err){};
		try{document.getElementById(id+'_button_font').value='';}catch(err){};
		try{document.getElementById(id+'_tblock_text_font').value='';}catch(err){};
		try{document.getElementById(id+'_button_form').value='';}catch(err){};
		try{document.getElementById(id+'_input_type').value='';}catch(err){};
		try{document.getElementById(id+'_button_type').value='';}catch(err){};
		try{document.getElementById(id+'_header_img_type').value='';}catch(err){};
		try{document.getElementById(id+'_background_opacity').value='';}catch(err){};
		try{document.getElementById(id+'_close_icon_animation').value='';}catch(err){};
		try{document.getElementById(id+'_close_icon_type').value='';}catch(err){};			
		try{document.getElementById(id+'_overlay_image_src').value='';}catch(err){};			
		try{document.getElementById(id+'_header_image_src').value='';}catch(err){};			
		try{document.getElementById(id+'_background_image_src').value='';}catch(err){};
		try{document.getElementById(id+'_ss_view_type').value='';}catch(err){};
		try{document.getElementById(id+'_ss_color').value='';}catch(err){};
		try{document.getElementById(id+'_ss_color_opacity').value='';}catch(err){};
		try{document.getElementById(id+'_ss_background_color').value='';}catch(err){};
		try{document.getElementById(id+'_ss_background_color_opacity').value='';}catch(err){};

		//text
		try{document.getElementById(id+'_title').value='';}catch(err){};	
		try{document.getElementById(id+'_subtitle').value='';}catch(err){};	
		try{document.getElementById(id+'_tblock_text').value='';}catch(err){};	
		try{document.getElementById(id+'_button_text').value='';}catch(err){};	
		try{document.getElementById(id+'_close_text').value='';}catch(err){};	
	}
	
	function setValueTOFormElements(id, obj, toolID){		
		disableAllDesignOpt(id);
		for(var i in obj){				
			try{
				if(obj[i] == 'pq_checked'){
					document.getElementById(id+i).checked = true;
				}else{					
					if(i.indexOf('color') == -1){
						document.getElementById(id+i).value=obj[i];
					}else{
						document.getElementById(id+i).value=obj[i];
						document.getElementById(id+i).style.backgroundColor = '#'+obj[i];						
					}
				}
			}catch(err){}		
		}
	}	
	function setThemeSettings(toolID, id){	
		var theme = document.getElementById(toolID+'_Current_Theme').value = document.getElementById(toolID+'_CurrentThemeForPreview').value;			
		if(theme && PQThemes[id][theme].designOptions){			
			setValueTOFormElements(id, PQThemes[id][theme].designOptions, toolID);
			if(Number(PQThemes[id][theme].price) == 0){
				lockProOptionFromForm(id, toolID);
			}else{
				unlockProOptionFromForm(id, toolID);
			}
		}
	}

	/**********************************************************************/
	
	function openChooseProPopup(toolID){
		document.getElementById('PQProSelectCurrentToolID').value = toolID;		
		document.getElementById('PQChooseProPopup').style.display = 'block';
	}
	
	function goToThemesStep(){
		var toolID = document.getElementById('PQProSelectCurrentToolID').value;		
		
		document.getElementById('PQChooseProPopup').style.display = 'none';
		selectStep(toolID, 1);		
	}
	
	/***********************************************************************/
	function selectSubscribeProvider(partId, val){
		var url = '';
		var title = '';
		if(val == 'aweber'){
			url = 'http://profitquery.com/blog/2015/11/generate-aweber-sign-in-form-for-wordpress/';
			title = 'Aweber';
		}
		
		if(val == 'mailchimp'){
			url = 'http://profitquery.com/blog/2015/11/generate-mailchimp-sign-in-form-for-wordpress/';
			title = 'Mailchimp';
		}
		
		
		if(val == 'newsletter2go'){
			//url = 'newsletter2go.html';
			//title = 'Newsletter2go';
		}
	
		if(val == 'madmini'){
			url = 'http://profitquery.com/blog/2015/11/generate-mad-mimi-sign-in-form-for-wordpress/';
			title = 'Mad Mimi';
		}
		if(val == 'acampaign'){
			url = 'http://profitquery.com/blog/2015/11/generate-active-campaign-sign-in-form-for-wordpress/';
			title = 'Active Campaign';
		}
		if(val == 'getresponse'){
			url = 'http://profitquery.com/blog/2015/11/generate-getresponse-sign-in-form-for-wordpress/';
			title = 'GetResponse';
		}
		if(val == 'klickmail'){
			url = 'http://profitquery.com/blog/2015/11/generate-klick-mail-sign-in-form-for-wordpress/';
			title = 'Klick Mmail';
		}	
		
		try{
			if(url){
				document.getElementById(partId+'_provier_help_url').href=url;
				document.getElementById(partId+'_provier_title').innerHTML = title;
				document.getElementById(partId+'_ProviderCodeContainer').style.display="block";
				
			}
		}catch(err){};	
	}


	function _proceedDisableAllTools(name){		
		try{
			for (var i = 1; i <= 10; i++) {			
				document.getElementById('SelectTool_'+name+'_'+i).style.display = 'none';
			}
		}catch(err){};
	}

	function enableToolsByName(name){
		document.getElementById('PQSelectToolStep').style.display = 'block';
		try{
			for (var i = 1; i <= 10; i++) {
				document.getElementById('SelectTool_'+name+'_'+i).style.display = 'inline-block';
			}
		}catch(err){};
	}

	function enableAllTools(){
		enableToolsByName('Sharing');
		enableToolsByName('EmailListBuilder');
		enableToolsByName('ContactForm');
		enableToolsByName('Promote');
		enableToolsByName('CallMe');
		enableToolsByName('Follow');
		enableToolsByName('Any');
		enableToolsByName('Iframe');
		enableToolsByName('Youtube');
	}
	
	function selectGoalsStep(){
		document.getElementById('PQSelectToolStep').style.display = 'none';
		document.getElementById('PQSelectToolStep').style.display = 'block';
	}
	
	function disableAllTools(){	
		document.getElementById('PQSelectToolStep').style.display = 'none';
		document.getElementById('PQSelectGoalStep').style.display = 'none';
		_proceedDisableAllTools('Sharing');
		_proceedDisableAllTools('EmailListBuilder');
		_proceedDisableAllTools('ContactForm');
		_proceedDisableAllTools('Promote');
		_proceedDisableAllTools('CallMe');
		_proceedDisableAllTools('Follow');
		_proceedDisableAllTools('Any');	
		_proceedDisableAllTools('Iframe');
		_proceedDisableAllTools('Youtube');
	}
	function sortTools(val){
		if(val == ''){		
			enableAllTools();
		}else{
			disableAllTools();
			enableToolsByName(val);
		}
	}

	
	function enableDisableCurrentTool(checked, id, name, src){
		if(checked == false){
			openDisableDialog(checked, id, name, src)
		}else{
			enableCurrentTool(id, name, src)
		}
	}
	
	function openDisableDialog(checked, id, name, src){
		if(checked == false){
			disableCurrentTool(id, name, src);
		}		
	}
	
	function deleteCurrentTool(id, name, src){
		document.getElementById('PQ_Delete_Popup').style.display = 'block';
		var title = '<?php echo $this->_dictionary[deleteDialog][title];?>';	
		
		document.getElementById('PQ_Delete_Popup_title').innerHTML = title.replace(new RegExp("(\%s)",'g'),name);	
		document.getElementById('PQ_Delete_Popup_toolsID').value = id;	
		document.getElementById('PQ_Delete_Popup_src').src = src;	
		document.getElementById('PQ_Delete_Popup_cancel').onclick = function(){
			document.getElementById('PQ_Delete_Popup').style.display='none';
			
		}
	}
	
	function disableCurrentTool(id, name, src){
		document.getElementById('PQ_Disable_Popup').style.display = 'block';
		var title = '<?php echo $this->_dictionary[disableDialog][title];?>';	
		
		document.getElementById('PQ_Disable_Popup_title').innerHTML = title.replace(new RegExp("(\%s)",'g'),name);	
		document.getElementById('PQ_Disable_Popup_toolsID').value = id;	
		document.getElementById('PQ_Disable_Popup_src').src = src;	
		document.getElementById('PQ_Disable_Popup_cancel').onclick = function(){
			document.getElementById('PQ_Disable_Popup').style.display='none';
			document.getElementById('tool_switch_enable_'+id).checked=true;			
		}
	}
	
	function enableCurrentTool(id, name, src){
		document.getElementById('PQ_Enable_Popup').style.display = 'block';
		var title = '<?php echo $this->_dictionary[disableDialog][title_enable];?>';	
		
		document.getElementById('PQ_Enable_Popup_title').innerHTML = title.replace(new RegExp("(\%s)",'g'),name);	
		document.getElementById('PQ_Enable_Popup_toolsID').value = id;	
		document.getElementById('PQ_Enable_Popup_src').src = src;	
		document.getElementById('PQ_Enable_Popup_cancel').onclick = function(){
			document.getElementById('PQ_Enable_Popup').style.display='none';
			document.getElementById('tool_switch_enable_'+id).checked=false;			
		}
	}



	/**********************************PREVIEW SCRIPT**********************************************/
	
	function getPreviewByActiveForm(toolID, id){		
		var thank = 0;
		var email = 0;
		var tool = 0;
		var func = '';		
		try{if(document.getElementById(id+'_DesignToolSwitch_main').className.indexOf('pq_active') != -1) tool = 1;}catch(err){};
		try{if(document.getElementById(id+'_DesignToolSwitch_thank').className.indexOf('pq_active') != -1) thank = 1;}catch(err){};
		try{if(document.getElementById(id+'_DesignToolSwitch_email').className.indexOf('pq_active') != -1) email = 1;}catch(err){};
		if(tool == 1){
			func = toolID+'Preview()';
		}
		if(email == 1){
			func = toolID+'sendMailWindowPreview("'+id+'")';
		}
		if(thank == 1){
			func = toolID+'thankPreview("'+id+'")';
		}
		if(func) eval(func);							
	}					
	
	function deleteHash(val){
		return val.toString().replace("#", "");		
	}
	
	function deleteHashAndPasteCode(prefix, val){
		if(deleteHash(val)){
			return prefix+deleteHash(val);
		}else{
			return '';
		}
	}
	
	
	function thankPreview(id, container_id, desktop, whitelabel){		
		var design = '';
		var contOptions = 'pq_open_and_fix pq_animated '+document.getElementById(id+'_thank_animation').value;
		design += ' '+document.getElementById(id+'_thank_typeWindow').value+' '+document.getElementById(id+'_thank_popup_form').value;
		design += ' '+document.getElementById(id+'_thank_head_font').value+' '+document.getElementById(id+'_thank_head_font_size').value;
		design += ' '+deleteHashAndPasteCode('pq_h_color_h1_PQCC', document.getElementById(id+'_thank_head_color').value)+' '+document.getElementById(id+'_thank_text_font').value+' '+document.getElementById(id+'_thank_font_size').value+' '+deleteHashAndPasteCode('pq_text_color_block_PQCC', document.getElementById(id+'_thank_text_color').value);		
		design += ' '+document.getElementById(id+'_thank_close_icon_type').value;
		design += ' '+deleteHashAndPasteCode('pq_x_color_pqclose_PQCC',document.getElementById(id+'_thank_close_icon_color').value);
		design += ' '+document.getElementById(id+'_thank_border_type').value+' '+document.getElementById(id+'_thank_border_depth').value;
		design += ' '+deleteHashAndPasteCode('pq_bd_bordercolor_PQCC',document.getElementById(id+'_thank_border_color').value);
		design += ' '+document.getElementById(id+'_thank_close_text_font').value;
		design += ' '+deleteHashAndPasteCode('pq_btngbg_bgcolor_btngroup_PQCC', document.getElementById(id+'_thank_background_button_block').value);
		design += ' '+deleteHashAndPasteCode('pq_bgsocblock_bgcolor_bgsocblock_PQCC',document.getElementById(id+'_thank_background_soc_block').value);
		design += ' '+document.getElementById(id+'_thank_icon_block_padding').value;
		design += ' '+document.getElementById(id+'_thank_button_block_padding').value;
		design += ' '+document.getElementById(id+'_thank_button_type').value;
		
		var bg_opacity = 10;		
		try{bg_opacity = Number(document.getElementById(id+'_thank_background_opacity').value)||0;}catch(err){};
		//rgba background
		try{
			if(document.getElementById(id+'_thank_background_color').value){
				var bgRGB = hexToRgb(document.getElementById(id+'_thank_background_color').value);			
				if(typeof bgRGB == 'object'){
					design += ' pq_bg_bgcolor_PQRGBA_'+bgRGB.r+'_'+bgRGB.g+'_'+bgRGB.b+'_'+bg_opacity;
				}			
			}
		}catch(err){};		
		
		var overlay = deleteHashAndPasteCode('pq_over_bgcolor_PQCC',document.getElementById(id+'_thank_overlay_color').value)||'';
		var overlay_opacity = document.getElementById(id+'_thank_overlay_opacity').value||'';
		var close_animation = document.getElementById(id+'_thank_animation_close_icon').value||'';		
		var close_text = encodeURIComponent(document.getElementById(id+'_thank_close_text').value)||'';
		var sub_title = encodeURIComponent(document.getElementById(id+'_thank_subtitle').value)||'';
		var title = encodeURIComponent(document.getElementById(id+'_thank_title').value)||'';		
		
		var header_i = encodeURIComponent(document.getElementById(id+'_thank_header_image_src').value)||'';
		var background_i = encodeURIComponent(document.getElementById(id+'_thank_background_image_src').value)||'';
		var overlay_image_src = encodeURIComponent(document.getElementById(id+'_thank_overlay_image_src').value)||'';		
		design += ' '+document.getElementById(id+'_thank_showup_animation').value;
		design += ' '+document.getElementById(id+'_thank_header_img_type').value;
										
		//ICONS			
		design += ' '+document.getElementById(id+'_thank_icon_design').value||'';
		design += ' '+document.getElementById(id+'_thank_icon_form').value||'';
		design += ' '+document.getElementById(id+'_thank_icon_size').value||'';
		design += ' '+document.getElementById(id+'_thank_icon_space').value||'';
		var icon_animation = document.getElementById(id+'_thank_icon_animation').value||'';
		
		//socnet
		var socnetIcons = '';
		var socnet_type = '';
		if(document.getElementById(id+'_thank_socnet_block_type').value != ''){
			if(document.getElementById(id+'_thank_socnet_block_type').value == 'follow'){
				socnet_type = 'follow';
				if(document.getElementById(id+'_thank_follow_icon_FB').value) socnetIcons += 'facebook|';
				if(document.getElementById(id+'_thank_follow_icon_TW').value) socnetIcons += 'twitter|';
				if(document.getElementById(id+'_thank_follow_icon_GP').value) socnetIcons += 'google-plus|';
				if(document.getElementById(id+'_thank_follow_icon_PI').value) socnetIcons += 'pinterest|';
				if(document.getElementById(id+'_thank_follow_icon_YT').value) socnetIcons += 'youtube|';
				if(document.getElementById(id+'_thank_follow_icon_LI').value) socnetIcons += 'linkedin|';				
				if(document.getElementById(id+'_thank_follow_icon_VK').value) socnetIcons += 'vk|';
				if(document.getElementById(id+'_thank_follow_icon_OD').value) socnetIcons += 'odnoklassniki|';
				if(document.getElementById(id+'_thank_follow_icon_IG').value) socnetIcons += 'instagram|';
				if(document.getElementById(id+'_thank_follow_icon_RSS').value) socnetIcons += 'RSS|';
			}
			if(document.getElementById(id+'_thank_socnet_block_type').value == 'share'){				
				socnet_type = 'share';
				for(var i=0; i<=9; i++){
					if(document.getElementById(id+'_thank_sharing_icon_'+i).value != ''){
						socnetIcons += document.getElementById(id+'_thank_sharing_icon_'+i).value+'|';
					}
				}
			}
		}		
		//BUTTON
		var button_text = '';
		var button_action = '';
		var button_url = '';
		if(document.getElementById(id+'_thank_type').value=='redirect'){
			button_action = 'redirect';
			button_url = encodeURIComponent(document.getElementById(id+'_thank_url_address').value);
			design += ' '+document.getElementById(id+'_thank_button_font').value+' '+document.getElementById(id+'_thank_button_font_size').value+' '+deleteHashAndPasteCode('pq_btn_color_btngroupbtn_PQCC',document.getElementById(id+'_thank_button_text_color').value)+' '+deleteHashAndPasteCode('pq_btn_bg_bgcolor_btngroupbtn_PQCC',document.getElementById(id+'_thank_button_color').value)
			var button_text = encodeURIComponent(document.getElementById(id+'_thank_button_text').value)||'';
		}
		if(strstr(document.getElementById(container_id+'_PQIframePreviewBlock').className, 'pq_min')){
			var pq_mini = ' pq_min';
		}else{
			var pq_mini = '';
		}
		if(document.getElementById(id+'_thank_enable').checked ){
			if(desktop == '1'){
				document.getElementById(container_id+'_PQPreviewID').src = '//profitquery.com/preview/iframe_demo_v5.2.html?tool=thank&design='+design+'&whitelabel='+whitelabel+'&overlay='+
					overlay+'&close_animation='+close_animation+'&close_text='+close_text+'&sub_title='+sub_title+'&title='+
					title+'&button_text='+button_text+'&icon_animation='+icon_animation+'&socnet='+socnetIcons+'&socnet_type='+socnet_type+'&button_action='+button_action+'&button_text='+button_text+'&button_url='+button_url+'&overlay_opacity='+
					overlay_opacity+'&overlay_image_src='+overlay_image_src+'&header_i='+header_i+'&background_i='+background_i+'&rtl='+PQ_RTL+'&contOptions='+contOptions;
				document.getElementById(container_id+'_PQPreviewID').className='pq_desktop_preview';
				document.getElementById(container_id+'_PQIframePreviewBlock').className='frame pq_desktop_preview'+pq_mini;
			}else{
				document.getElementById(container_id+'_PQPreviewID').src = '//profitquery.com/preview/iframe_demo_v5.2.html?tool=thank&design='+design+'&whitelabel='+whitelabel+'&overlay='+
					overlay+'&close_animation='+close_animation+'&close_text='+close_text+'&sub_title='+sub_title+'&title='+
					title+'&button_text='+button_text+'&icon_animation='+icon_animation+'&socnet='+socnetIcons+'&socnet_type='+socnet_type+'&button_action='+button_action+'&button_text='+button_text+'&button_url='+button_url+'&overlay_opacity='+
					overlay_opacity+'&overlay_image_src='+overlay_image_src+'&header_i='+header_i+'&background_i='+background_i+'&rtl='+PQ_RTL+'&contOptions='+contOptions;
				document.getElementById(container_id+'_PQPreviewID').className='pq_mobile_preview';
				document.getElementById(container_id+'_PQIframePreviewBlock').className='frame pq_mobile_preview'+pq_mini;
			}
		}else{
			if(desktop == '1'){
				document.getElementById(container_id+'_PQPreviewID').src = 'about:blank';
				document.getElementById(container_id+'_PQPreviewID').className='';
				document.getElementById(container_id+'_PQIframePreviewBlock').className='frame'+pq_mini;
			}else{
				document.getElementById(container_id+'_PQPreviewID').src = 'about:blank';
				document.getElementById(container_id+'_PQPreviewID').className='';
				document.getElementById(container_id+'_PQIframePreviewBlock').className='frame pq_mobile_preview'+pq_mini;
			}
		}
		
	}
	
	
	function sharingSidebarthankPreview(){
		var desktopView = 0;		
		if(document.getElementById('PQSharingSidebar_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
		if(document.getElementById('PQSharingSidebar_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
		var whitelabel = document.getElementById('sharingSidebar_whitelabel').value;
		
		
		thankPreview('sharingSidebar', 'PQSharingSidebar', desktopView, whitelabel);		
	}
	function imageSharerthankPreview(){
		var desktopView = 0;		
		if(document.getElementById('PQImageSharer_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
		if(document.getElementById('PQImageSharer_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
		var whitelabel = document.getElementById('imageSharer_whitelabel').value;
		
		thankPreview('imageSharer', 'PQImageSharer', desktopView, whitelabel);		
	}
	function sharingPopupthankPreview(){
		var desktopView = 0;		
		if(document.getElementById('PQSharingPopup_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
		if(document.getElementById('PQSharingPopup_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
		var whitelabel = document.getElementById('sharingPopup_whitelabel').value;
		
		
		thankPreview('sharingPopup', 'PQSharingPopup',desktopView, whitelabel);		
	}
	function sharingBarthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQSharingBar_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQSharingBar_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('sharingBar_whitelabel').value;
			
			
			thankPreview('sharingBar', 'PQSharingBar',desktopView, whitelabel);		
		}
	function sharingFloatingthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQSharingFloating_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQSharingFloating_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('sharingFloating_whitelabel').value;
			
			
			thankPreview('sharingFloating','PQSharingFloating', desktopView, whitelabel);		
		}
	function emailListBuilderPopupthankPreview(){			
			var desktopView = 0;		
			if(document.getElementById('PQEmailListBuilderPopup_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQEmailListBuilderPopup_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('emailListBuilderPopup_whitelabel').value;
			
			
			thankPreview('emailListBuilderPopup','PQEmailListBuilderPopup', desktopView, whitelabel);		
		}
	
	function emailListBuilderBarthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQEmailListBuilderBar_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQEmailListBuilderBar_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('emailListBuilderBar_whitelabel').value;
			
			
			thankPreview('emailListBuilderBar','PQEmailListBuilderBar', desktopView, whitelabel);		
		}
	function emailListBuilderFloatingthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQEmailListBuilderFloating_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQEmailListBuilderFloating_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('emailListBuilderFloating_whitelabel').value;
			
			
			thankPreview('emailListBuilderFloating','PQEmailListBuilderFloating', desktopView, whitelabel);		
		}
	function contactFormPopupthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQcontactFormPopup_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQcontactFormPopup_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('contactFormPopup_whitelabel').value;
			
			
			thankPreview('contactFormPopup','PQcontactFormPopup', desktopView, whitelabel);		
		}
	function contactFormCenterthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQcontactFormCenter_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQcontactFormCenter_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('contactFormCenter_whitelabel').value;
			
			
			thankPreview('contactFormCenter','PQcontactFormCenter', desktopView, whitelabel);		
		}
	function contactFormFloatingthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQcontactFormFloating_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQcontactFormFloating_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('contactFormFloating_whitelabel').value;
			
			
			thankPreview('contactFormFloating','PQcontactFormFloating', desktopView, whitelabel);		
		}
	function promotePopupthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQPromotePopup_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQPromotePopup_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('promotePopup_whitelabel').value;
			
				
			thankPreview('promotePopup', 'PQPromotePopup',desktopView, whitelabel);		
		}
	function promoteBarthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQPromoteBar_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQPromoteBar_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('promoteBar_whitelabel').value;
			
			
			thankPreview('promoteBar', 'PQPromoteBar',desktopView, whitelabel);		
		}
	
	function promoteFloatingthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQPromoteFloating_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQPromoteFloating_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('promoteFloating_whitelabel').value;
			
			
			thankPreview('promoteFloating', 'PQPromoteFloating',desktopView, whitelabel);		
		}
	function callMePopupthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQCallMePopup_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQCallMePopup_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('callMePopup_whitelabel').value;
			
			
			thankPreview('callMePopup', 'PQCallMePopup',desktopView, whitelabel);		
		}
	function callMeFloatingthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQCallMeFloating_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQCallMeFloating_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('callMeFloating_whitelabel').value;
			
			
			thankPreview('callMeFloating', 'PQCallMeFloating',desktopView, whitelabel);		
		}
	function followPopupthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQFollowPopup_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQFollowPopup_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('followPopup_whitelabel').value;
			
			
			thankPreview('followPopup', 'PQFollowPopup',desktopView, whitelabel);		
		}
	function followBarthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQFollowBar_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQFollowBar_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('followBar_whitelabel').value;
			
			
			thankPreview('followBar', 'PQFollowBar',desktopView, whitelabel);		
		}
	function followFloatingthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQFollowFloating_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQFollowFloating_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('followFloating_whitelabel').value;
			
			
			thankPreview('followFloating', 'PQFollowFloating',desktopView, whitelabel);		
		}
	function iframePopupthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQiframePopup_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQiframePopup_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('iframePopup_whitelabel').value;
			
			
			thankPreview('iframePopup', 'PQiframePopup',desktopView, whitelabel);		
		}
	function iframeFloatingthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQiframeFloating_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQiframeFloating_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('iframeFloating_whitelabel').value;
			
			
			thankPreview('iframeFloating', 'PQiframeFloating',desktopView, whitelabel);		
		}
	function youtubePopupthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQyoutubePopup_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQyoutubePopup_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('youtubePopup_whitelabel').value;
			
			
			thankPreview('youtubePopup', 'PQyoutubePopup',desktopView, whitelabel);		
		}
	function youtubeFloatingthankPreview(){
			var desktopView = 0;		
			if(document.getElementById('PQyoutubeFloating_thank_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
			if(document.getElementById('PQyoutubeFloating_thank_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
			var whitelabel = document.getElementById('youtubeFloating_whitelabel').value;
			
			
			thankPreview('youtubeFloating', 'PQyoutubeFloating',desktopView, whitelabel);		
		}
	
	function sendMailWindowPreview(id, container_id, desktop, whitelabel){
		var contOptions = 'pq_open_and_fix pq_animated '+document.getElementById(id+'_sendMailWindow_animation').value||'';
		
		var design = '';
		
		design += ' '+document.getElementById(id+'_sendMailWindow_typeWindow').value+' '+document.getElementById(id+'_sendMailWindow_popup_form').value;
		design += ' '+document.getElementById(id+'_sendMailWindow_head_font').value+' '+document.getElementById(id+'_sendMailWindow_head_font_size').value;
		design += ' '+deleteHashAndPasteCode('pq_h_color_h1_PQCC',document.getElementById(id+'_sendMailWindow_head_color').value)+' '+document.getElementById(id+'_sendMailWindow_text_font').value+' '+document.getElementById(id+'_sendMailWindow_font_size').value+' '+deleteHashAndPasteCode('pq_text_color_block_PQCC',document.getElementById(id+'_sendMailWindow_text_color').value);
		design += ' '+document.getElementById(id+'_sendMailWindow_button_font').value+' '+deleteHashAndPasteCode('pq_btn_color_btngroupbtn_PQCC',document.getElementById(id+'_sendMailWindow_button_text_color').value)+' '+deleteHashAndPasteCode('pq_btn_bg_bgcolor_btngroupbtn_PQCC',document.getElementById(id+'_sendMailWindow_button_color').value)
		design += ' '+document.getElementById(id+'_sendMailWindow_button_font_size').value;
		design += ' '+document.getElementById(id+'_sendMailWindow_close_icon_type').value;
		design += ' '+deleteHashAndPasteCode('pq_x_color_pqclose_PQCC',document.getElementById(id+'_sendMailWindow_close_icon_color').value);		
		design += ' '+document.getElementById(id+'_sendMailWindow_close_text_font').value;		
		design += ' '+document.getElementById(id+'_sendMailWindow_button_block_padding').value;
		design += ' '+document.getElementById(id+'_sendMailWindow_form_block_padding').value;
		design += ' '+deleteHashAndPasteCode('pq_formbg_bgcolor_formbg_PQCC', document.getElementById(id+'_sendMailWindow_background_form_block').value);
		design += ' '+deleteHashAndPasteCode('pq_btngbg_bgcolor_btngroup_PQCC', document.getElementById(id+'_sendMailWindow_background_button_block').value);
		
		var header_i = encodeURIComponent(document.getElementById(id+'_sendMailWindow_header_image_src').value)||'';
		var background_i = encodeURIComponent(document.getElementById(id+'_sendMailWindow_background_image_src').value)||'';
		var overlay_image_src = encodeURIComponent(document.getElementById(id+'_sendMailWindow_overlay_image_src').value)||'';		
		design += ' '+document.getElementById(id+'_sendMailWindow_showup_animation').value;
		design += ' '+document.getElementById(id+'_sendMailWindow_header_img_type').value;		
		design += ' '+document.getElementById(id+'_sendMailWindow_button_type').value;
		
		
		var overlay = deleteHashAndPasteCode('pq_over_bgcolor_PQCC',document.getElementById(id+'_sendMailWindow_overlay_color').value)||'';
		var overlay_opacity = document.getElementById(id+'_sendMailWindow_overlay_opacity').value||'';
		var close_animation = document.getElementById(id+'_sendMailWindow_animation_close_icon').value||'';		
		var close_text = encodeURIComponent(document.getElementById(id+'_sendMailWindow_close_text').value)||'';
		//var header_i = encodeURIComponent(document.getElementById(id+'_sendMailWindow_header_image_src').value)||'';		
		var sub_title = encodeURIComponent(document.getElementById(id+'_sendMailWindow_subtitle').value)||'';
		var title = encodeURIComponent(document.getElementById(id+'_sendMailWindow_title').value)||'';
		var button_text = encodeURIComponent(document.getElementById(id+'_sendMailWindow_button_text').value)||'';
		var name_text = encodeURIComponent(document.getElementById(id+'_sendMailWindow_text_name').value)||'';
		var email_text = encodeURIComponent(document.getElementById(id+'_sendMailWindow_text_email').value)||'';
		var subject_text = encodeURIComponent(document.getElementById(id+'_sendMailWindow_text_subject').value)||'';
		var message_text = encodeURIComponent(document.getElementById(id+'_sendMailWindow_text_message').value)||'';
		
		var bg_opacity = 10;		
		try{bg_opacity = Number(document.getElementById(id+'_sendMailWindow_background_opacity').value)||0;}catch(err){};
		//rgba background
		try{
			if(document.getElementById(id+'_sendMailWindow_background_color').value){
				var bgRGB = hexToRgb(document.getElementById(id+'_sendMailWindow_background_color').value);			
				if(typeof bgRGB == 'object'){
					design += ' pq_bg_bgcolor_PQRGBA_'+bgRGB.r+'_'+bgRGB.g+'_'+bgRGB.b+'_'+bg_opacity;
				}			
			}
		}catch(err){};
		
		
		if(strstr(document.getElementById(container_id+'_PQIframePreviewBlock').className, 'pq_min')){
			var pq_mini = ' pq_min';
		}else{
			var pq_mini = '';
		}
		if(document.getElementById(id+'_email_enable').checked){
			if(desktop == '1'){
				document.getElementById(container_id+'_PQPreviewID').src = '//profitquery.com/preview/iframe_demo_v5.2.html?tool=sendMail&design='+design+'&whitelabel='+whitelabel+'&overlay='+
					overlay+'&close_animation='+close_animation+'&close_text='+close_text+'&sub_title='+sub_title+'&title='+
					title+'&button_text='+button_text+'&name_text='+name_text+'&email_text='+email_text+'&subject_text='+subject_text+'&message_text='+message_text+'&overlay_opacity='+
					overlay_opacity+'&overlay_image_src='+overlay_image_src+'&header_i='+header_i+'&background_i='+background_i+'&rtl='+PQ_RTL+'&contOptions='+contOptions;
					document.getElementById(container_id+'_PQPreviewID').className='pq_desktop_preview';
					document.getElementById(container_id+'_PQIframePreviewBlock').className='frame pq_desktop_preview'+pq_mini;
			}else{			
				document.getElementById(container_id+'_PQPreviewID').src = '//profitquery.com/preview/iframe_demo_v5.2.html?tool=sendMail&design='+design+'&whitelabel='+whitelabel+'&overlay='+
					overlay+'&close_animation='+close_animation+'&close_text='+close_text+'&sub_title='+sub_title+'&title='+
					title+'&button_text='+button_text+'&name_text='+name_text+'&email_text='+email_text+'&subject_text='+subject_text+'&message_text='+message_text+'&overlay_opacity='+
					overlay_opacity+'&overlay_image_src='+overlay_image_src+'&header_i='+header_i+'&background_i='+background_i+'&rtl='+PQ_RTL+'&contOptions='+contOptions;
				document.getElementById(container_id+'_PQPreviewID').className='pq_mobile_preview';
				document.getElementById(container_id+'_PQIframePreviewBlock').className='frame pq_mobile_preview'+pq_mini;
			}
		}else{			
			if(desktop == '1'){
				document.getElementById(container_id+'_PQPreviewID').src = 'about:blank';
				document.getElementById(container_id+'_PQPreviewID').className='';
				document.getElementById(container_id+'_PQIframePreviewBlock').className='frame'+pq_mini;
			}else{
				document.getElementById(container_id+'_PQPreviewID').src = 'about:blank';
				document.getElementById(container_id+'_PQPreviewID').className='';
				document.getElementById(container_id+'_PQIframePreviewBlock').className='frame pq_mobile_preview'+pq_mini;
			}
			
		}
	}
	
	function imageSharersendMailWindowPreview(){
		var desktopView = 0;		
		if(document.getElementById('PQImageSharer_email_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
		if(document.getElementById('PQImageSharer_email_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
		var whitelabel = document.getElementById('imageSharer_whitelabel').value;
		
		sendMailWindowPreview('imageSharer','PQImageSharer',desktopView, whitelabel);
	}
	
	function sharingSidebarsendMailWindowPreview(){
		var desktopView = 0;		
		if(document.getElementById('PQSharingSidebar_email_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
		if(document.getElementById('PQSharingSidebar_email_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
		var whitelabel = document.getElementById('sharingSidebar_whitelabel').value;
		
		sendMailWindowPreview('sharingSidebar','PQSharingSidebar',desktopView, whitelabel);
	}

	function sharingPopupsendMailWindowPreview(){
		var desktopView = 0;		
		if(document.getElementById('PQSharingPopup_email_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
		if(document.getElementById('PQSharingPopup_email_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
		var whitelabel = document.getElementById('sharingPopup_whitelabel').value;
		
		sendMailWindowPreview('sharingPopup','PQSharingPopup',desktopView, whitelabel);
	}
	
	function sharingFloatingsendMailWindowPreview(){
		var desktopView = 0;		
		if(document.getElementById('PQSharingFloating_email_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
		if(document.getElementById('PQSharingFloating_email_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
		var whitelabel = document.getElementById('sharingFloating_whitelabel').value;
		
		sendMailWindowPreview('sharingFloating','PQSharingFloating',desktopView, whitelabel);
	}
	
	function sharingBarsendMailWindowPreview(){
		var desktopView = 0;		
		if(document.getElementById('PQSharingBar_email_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
		if(document.getElementById('PQSharingBar_email_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
		var whitelabel = document.getElementById('sharingBar_whitelabel').value;
		
		sendMailWindowPreview('sharingBar','PQSharingBar',desktopView, whitelabel);
	}	
	
	function emailListBuilderBarPreview(){
		var toolView = 0;
		var desktopView = 0;		
		if(document.getElementById('PQEmailListBuilderBar_main_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
		if(document.getElementById('PQEmailListBuilderBar_main_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
		
		var design = position = theme = themeAddClass = '';
		
		try{position = PQPositionValues[document.getElementById('PQEmailListBuilderBar_position').value]||'';}catch(err){};
		try{theme = document.getElementById('PQEmailListBuilderBar_Current_Theme').value||'';}catch(err){};
		try{themeAddClass = PQThemes['emailListBuilderBar'][theme].addClass||'';}catch(err){};
		var contOptions = position+' pq_fixed pq_animated '+document.getElementById('emailListBuilderBar_animation').value||'';
		design = 'pq_bar '+theme+' '+themeAddClass;
		
		
		design += ' '+document.getElementById('emailListBuilderBar_mobile_position').value;
		design += ' '+document.getElementById('emailListBuilderBar_head_font').value+' '+document.getElementById('emailListBuilderBar_head_font_size').value;
		design += ' '+deleteHashAndPasteCode('pq_h_color_h1_PQCC',document.getElementById('emailListBuilderBar_head_color').value);		
		design += ' '+document.getElementById('emailListBuilderBar_close_icon_type').value;
		design += ' '+deleteHashAndPasteCode('pq_x_color_pqclose_PQCC',document.getElementById('emailListBuilderBar_close_icon_color').value);
		design += ' '+document.getElementById('emailListBuilderBar_border_type').value+' '+document.getElementById('emailListBuilderBar_border_depth').value;
		design += ' '+deleteHashAndPasteCode('pq_bd_bordercolor_PQCC',document.getElementById('emailListBuilderBar_border_color').value);
		
		//new
		design += ' '+deleteHashAndPasteCode('pq_formbg_bgcolor_formbg_PQCC',document.getElementById('emailListBuilderBar_background_form_block').value);
		design += ' '+document.getElementById('emailListBuilderBar_form_block_padding').value;
		design += ' '+deleteHashAndPasteCode('pq_btngbg_bgcolor_btngroup_PQCC',document.getElementById('emailListBuilderBar_background_button_block').value);
		design += ' '+document.getElementById('emailListBuilderBar_button_block_padding').value;
		design += ' '+document.getElementById('emailListBuilderBar_showup_animation').value;
		
		//new for mobile		
		design += ' '+deleteHashAndPasteCode('pq_mblock_color_bgmobblockp_PQCC',document.getElementById('emailListBuilderBar_mblock_text_font_color').value);
		design += ' '+document.getElementById('emailListBuilderBar_mblock_text_font').value||'';
		design += ' '+document.getElementById('emailListBuilderBar_mblock_text_font_size').value||'';
		
		var bg_opacity = 10;
		design += ' '+document.getElementById('emailListBuilderBar_header_img_type').value;
		design += ' '+document.getElementById('emailListBuilderBar_button_form').value;
		design += ' '+document.getElementById('emailListBuilderBar_button_type').value;
		design += ' '+document.getElementById('emailListBuilderBar_input_type').value;
		
		
		//rgba background
		try{
			if(document.getElementById('emailListBuilderBar_background_color').value){
				var bgRGB = hexToRgb(document.getElementById('emailListBuilderBar_background_color').value);			
				if(typeof bgRGB == 'object'){
					design += ' pq_bg_bgcolor_PQRGBA_'+bgRGB.r+'_'+bgRGB.g+'_'+bgRGB.b+'_'+bg_opacity;
				}			
			}
		}catch(err){};
		
		
		var close_animation = document.getElementById('emailListBuilderBar_animation_close_icon').value||'';		
		var close_text = encodeURIComponent(document.getElementById('emailListBuilderBar_close_text').value)||'';
		var header_i = encodeURIComponent(document.getElementById('emailListBuilderBar_header_image_src').value)||'';
		var m_title = encodeURIComponent(document.getElementById('emailListBuilderBar_m_title').value)||'';
		
		var title = encodeURIComponent(document.getElementById('emailListBuilderBar_title').value)||'';		
		var text_email = encodeURIComponent(document.getElementById('emailListBuilderBar_text_email').value)||'';		
		var text_name = encodeURIComponent(document.getElementById('emailListBuilderBar_text_name').value)||'';		
		
		var provider = 'mailchimp';
		if(document.getElementById('emailListBuilderBar_provider_mailchimp').checked) provider = 'mailchimp';
		if(document.getElementById('emailListBuilderBar_provider_getresponse').checked) provider = 'getresponse';
		if(document.getElementById('emailListBuilderBar_provider_aweber').checked) provider = 'aweber';
		//if(document.getElementById('emailListBuilderBar_provider_newsletter2go').checked) provider = 'newsletter2go';
		if(document.getElementById('emailListBuilderBar_provider_madmini').checked) provider = 'madmini';
		if(document.getElementById('emailListBuilderBar_provider_acampaign').checked) provider = 'acampaign';
		if(document.getElementById('emailListBuilderBar_provider_klickmail').checked) provider = 'klickmail';		
		
		
		var whitelabel = document.getElementById('emailListBuilderBar_whitelabel').value;
		
		design += ' '+document.getElementById('emailListBuilderBar_button_font').value+' '+document.getElementById('emailListBuilderBar_button_font_size').value+' '+deleteHashAndPasteCode('pq_btn_color_btngroupbtn_PQCC',document.getElementById('emailListBuilderBar_button_text_color').value)+' '+deleteHashAndPasteCode('pq_btn_bg_bgcolor_btngroupbtn_PQCC',document.getElementById('emailListBuilderBar_button_color').value)
		var button_text = encodeURIComponent(document.getElementById('emailListBuilderBar_button_text').value)||'';		
		if(strstr(document.getElementById('PQEmailListBuilderBar_PQIframePreviewBlock').className, 'pq_min')){
			var pq_mini = ' pq_min';
		}else{
			var pq_mini = '';
		}
		if(desktopView == '1'){
				document.getElementById('PQEmailListBuilderBar_PQPreviewID').src = '//profitquery.com/preview/iframe_demo_v5.2.html?tool=emailListBuilderBar&design='+design+'&whitelabel='+whitelabel+'&close_animation='+
					close_animation+'&close_text='+close_text+'&header_i='+header_i+'&title='+
					title+'&button_text='+button_text+'&text_email='+text_email+'&text_name='+text_name+'&provider='+provider+'&mobile_title='+m_title+'&rtl='+PQ_RTL+'&contOptions='+contOptions;
				document.getElementById('PQEmailListBuilderBar_PQPreviewID').className='pq_desktop_preview';
				document.getElementById('PQEmailListBuilderBar_PQIframePreviewBlock').className='frame pq_bar_iframe pq_desktop_preview'+pq_mini;
			}else{
				document.getElementById('PQEmailListBuilderBar_PQPreviewID').src = '//profitquery.com/preview/iframe_demo_v5.2.html?tool=emailListBuilderBar&design='+design+'&whitelabel='+whitelabel+'&close_animation='+
					close_animation+'&close_text='+close_text+'&header_i='+header_i+'&title='+
					title+'&button_text='+button_text+'&text_email='+text_email+'&text_name='+text_name+'&provider='+provider+'&mobile_title='+m_title+'&rtl='+PQ_RTL+'&contOptions='+contOptions;
				document.getElementById('PQEmailListBuilderBar_PQPreviewID').className='pq_mobile_preview';
				document.getElementById('PQEmailListBuilderBar_PQIframePreviewBlock').className='frame pq_bar_iframe pq_mobile_preview'+pq_mini;
			}
		
	}
	
	
	function emailListBuilderFloatingPreview(){
		var toolView = 0;
		var desktopView = 0;		
		if(document.getElementById('PQEmailListBuilderFloating_main_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
		if(document.getElementById('PQEmailListBuilderFloating_main_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
		
		var design = position = theme = themeAddClass = '';
		
		try{position = PQPositionValues[document.getElementById('PQEmailListBuilderFloating_position').value]||'';}catch(err){};
		try{theme = document.getElementById('PQEmailListBuilderFloating_Current_Theme').value||'';}catch(err){};
		try{themeAddClass = PQThemes['emailListBuilderFloating'][theme].addClass||'';}catch(err){};
		
		design = ' '+theme+' '+themeAddClass;
		var contOptions = position+' pq_fixed pq_animated '+document.getElementById('emailListBuilderFloating_animation').value||'';
		
		design += ' '+document.getElementById('emailListBuilderFloating_typeWindow').value+' '+document.getElementById('emailListBuilderFloating_popup_form').value;
		design += ' '+document.getElementById('emailListBuilderFloating_head_font').value+' '+document.getElementById('emailListBuilderFloating_head_font_size').value;
		design += ' '+deleteHashAndPasteCode('pq_h_color_h1_PQCC',document.getElementById('emailListBuilderFloating_head_color').value)+' '+document.getElementById('emailListBuilderFloating_text_font').value+' '+document.getElementById('emailListBuilderFloating_font_size').value+' '+deleteHashAndPasteCode('pq_text_color_block_PQCC',document.getElementById('emailListBuilderFloating_text_color').value);		
		design += ' '+document.getElementById('emailListBuilderFloating_close_icon_type').value;
		design += ' '+deleteHashAndPasteCode('pq_x_color_pqclose_PQCC',document.getElementById('emailListBuilderFloating_close_icon_color').value);
		design += ' '+document.getElementById('emailListBuilderFloating_border_type').value+' '+document.getElementById('emailListBuilderFloating_border_depth').value;
		design += ' '+deleteHashAndPasteCode('pq_bd_bordercolor_PQCC',document.getElementById('emailListBuilderFloating_border_color').value);
		
		//new
		design += ' '+deleteHashAndPasteCode('pq_bgtxt_bgcolor_bgtxt_PQCC',document.getElementById('emailListBuilderFloating_background_text_block').value);
		design += ' '+document.getElementById('emailListBuilderFloating_text_block_padding').value;
		design += ' '+deleteHashAndPasteCode('pq_formbg_bgcolor_formbg_PQCC',document.getElementById('emailListBuilderFloating_background_form_block').value);
		design += ' '+document.getElementById('emailListBuilderFloating_form_block_padding').value;
		design += ' '+deleteHashAndPasteCode('pq_btngbg_bgcolor_btngroup_PQCC',document.getElementById('emailListBuilderFloating_background_button_block').value);
		design += ' '+document.getElementById('emailListBuilderFloating_button_block_padding').value;
		design += ' '+document.getElementById('emailListBuilderFloating_tblock_text_font').value;
		design += ' '+document.getElementById('emailListBuilderFloating_tblock_text_font_size').value;
		design += ' '+deleteHashAndPasteCode('pq_bgtxt_color_bgtxtp_PQCC',document.getElementById('emailListBuilderFloating_tblock_text_font_color').value);
		design += ' '+document.getElementById('emailListBuilderFloating_showup_animation').value;
				
		var bg_opacity = 10;		
		try{bg_opacity = Number(document.getElementById('emailListBuilderFloating_background_opacity').value)||0;}catch(err){};
		design += ' '+document.getElementById('emailListBuilderFloating_header_img_type').value;
		design += ' '+document.getElementById('emailListBuilderFloating_button_form').value;
		design += ' '+document.getElementById('emailListBuilderFloating_button_type').value;
		design += ' '+document.getElementById('emailListBuilderFloating_input_type').value;
		design += ' '+document.getElementById('emailListBuilderFloating_close_text_font').value;
		
		//rgba background
		try{
			if(document.getElementById('emailListBuilderFloating_background_color').value){
				var bgRGB = hexToRgb(document.getElementById('emailListBuilderFloating_background_color').value);			
				if(typeof bgRGB == 'object'){
					design += ' pq_bg_bgcolor_PQRGBA_'+bgRGB.r+'_'+bgRGB.g+'_'+bgRGB.b+'_'+bg_opacity;
				}			
			}
		}catch(err){};
										  
		//new
		var tblock_text = encodeURIComponent(document.getElementById('emailListBuilderFloating_tblock_text').value)||'';
		
		
		var overlay = '';
		var close_animation = document.getElementById('emailListBuilderFloating_animation_close_icon').value||'';		
		var close_text = encodeURIComponent(document.getElementById('emailListBuilderFloating_close_text').value)||'';
		var header_i = encodeURIComponent(document.getElementById('emailListBuilderFloating_header_image_src').value)||'';
		var background_i = encodeURIComponent(document.getElementById('emailListBuilderFloating_background_image_src').value)||'';
		var sub_title = encodeURIComponent(document.getElementById('emailListBuilderFloating_subtitle').value)||'';
		var title = encodeURIComponent(document.getElementById('emailListBuilderFloating_title').value)||'';		
		var text_email = encodeURIComponent(document.getElementById('emailListBuilderFloating_text_email').value)||'';		
		var text_name = encodeURIComponent(document.getElementById('emailListBuilderFloating_text_name').value)||'';		
		
		var provider = 'mailchimp';
		if(document.getElementById('emailListBuilderFloating_provider_mailchimp').checked) provider = 'mailchimp';
		if(document.getElementById('emailListBuilderFloating_provider_getresponse').checked) provider = 'getresponse';
		if(document.getElementById('emailListBuilderFloating_provider_aweber').checked) provider = 'aweber';
		//if(document.getElementById('emailListBuilderFloating_provider_newsletter2go').checked) provider = 'newsletter2go';
		if(document.getElementById('emailListBuilderFloating_provider_madmini').checked) provider = 'madmini';
		if(document.getElementById('emailListBuilderFloating_provider_acampaign').checked) provider = 'acampaign';
		if(document.getElementById('emailListBuilderFloating_provider_klickmail').checked) provider = 'klickmail';		
		
		
		var whitelabel = document.getElementById('emailListBuilderFloating_whitelabel').value;
		
		design += ' '+document.getElementById('emailListBuilderFloating_button_font').value+' '+document.getElementById('emailListBuilderFloating_button_font_size').value+' '+deleteHashAndPasteCode('pq_btn_color_btngroupbtn_PQCC',document.getElementById('emailListBuilderFloating_button_text_color').value)+' '+deleteHashAndPasteCode('pq_btn_bg_bgcolor_btngroupbtn_PQCC',document.getElementById('emailListBuilderFloating_button_color').value)
		var button_text = encodeURIComponent(document.getElementById('emailListBuilderFloating_button_text').value)||'';		
		if(strstr(document.getElementById('PQEmailListBuilderFloating_PQIframePreviewBlock').className, 'pq_min')){
			var pq_mini = ' pq_min';
		}else{
			var pq_mini = '';
		}
		if(desktopView == '1'){
				document.getElementById('PQEmailListBuilderFloating_PQPreviewID').src = '//profitquery.com/preview/iframe_demo_v5.2.html?tool=emailListBuilderFloating&design='+design+'&whitelabel='+whitelabel+'&overlay='+
					overlay+'&close_animation='+close_animation+'&close_text='+close_text+'&header_i='+header_i+'&background_i='+background_i+'&sub_title='+sub_title+'&title='+
					title+'&button_text='+button_text+'&text_email='+text_email+'&text_name='+text_name+'&provider='+provider+'&tblock_text='+tblock_text+'&rtl='+PQ_RTL+'&contOptions='+contOptions;
				document.getElementById('PQEmailListBuilderFloating_PQPreviewID').className='pq_desktop_preview';
				document.getElementById('PQEmailListBuilderFloating_PQIframePreviewBlock').className='frame pq_desktop_preview'+pq_mini;
			}else{
				document.getElementById('PQEmailListBuilderFloating_PQPreviewID').src = '//profitquery.com/preview/iframe_demo_v5.2.html?tool=emailListBuilderFloating&design='+design+'&whitelabel='+whitelabel+'&overlay='+
					overlay+'&close_animation='+close_animation+'&close_text='+close_text+'&header_i='+header_i+'&background_i='+background_i+'&sub_title='+sub_title+'&title='+
					title+'&button_text='+button_text+'&text_email='+text_email+'&text_name='+text_name+'&provider='+provider+'&tblock_text='+tblock_text+'&rtl='+PQ_RTL+'&contOptions='+contOptions;
				document.getElementById('PQEmailListBuilderFloating_PQPreviewID').className='pq_mobile_preview';
				document.getElementById('PQEmailListBuilderFloating_PQIframePreviewBlock').className='frame pq_mobile_preview'+pq_mini;
			}
		
	}
	
	function emailListBuilderPopupPreview(){		
		var toolView = 0;
		var desktopView = 0;		
		if(document.getElementById('PQEmailListBuilderPopup_main_DesignToolSwitch_desktop').className.indexOf('pq_active') != -1) desktopView = 1;
		if(document.getElementById('PQEmailListBuilderPopup_main_DesignToolSwitch_mobile').className.indexOf('pq_active') != -1) desktopView = 0;
		
		var design = position = theme = themeAddClass = '';
		
		try{position = PQPositionValues[document.getElementById('PQEmailListBuilderPopup_position').value]||'';}catch(err){};
		try{theme = document.getElementById('PQEmailListBuilderPopup_Current_Theme').value||'';}catch(err){};
		try{themeAddClass = PQThemes['emailListBuilderPopup'][theme].addClass||'';}catch(err){};
		
		design = theme+' '+themeAddClass;
		var contOptions = position+' pq_open_and_fix pq_animated '+document.getElementById('emailListBuilderPopup_animation').value||'';
		
		design += ' '+document.getElementById('emailListBuilderPopup_typeWindow').value+' '+document.getElementById('emailListBuilderPopup_popup_form').value;
		design += ' '+document.getElementById('emailListBuilderPopup_head_font').value+' '+document.getElementById('emailListBuilderPopup_head_font_size').value;
		design += ' '+deleteHashAndPasteCode('pq_h_color_h1_PQCC',document.getElementById('emailListBuilderPopup_head_color').value)+' '+document.getElementById('emailListBuilderPopup_text_font').value+' '+document.getElementById('emailListBuilderPopup_font_size').value+' '+deleteHashAndPasteCode('pq_text_color_block_PQCC',document.getElementById('emailListBuilderPopup_text_color').value);		
		design += ' '+document.getElementById('emailListBuilderPopup_close_icon_type').value;
		design += ' '+deleteHashAndPasteCode('pq_x_color_pqclose_PQCC',document.getElementById('emailListBuilderPopup_close_icon_color').value);
		design += ' '+document.getElementById('emailListBuilderPopup_border_type').value+' '+document.getElementById('emailListBuilderPopup_border_depth').value;
		design += ' '+deleteHashAndPasteCode('pq_bd_bordercolor_PQCC',document.getElementById('emailListBuilderPopup_border_color').value);				
		
		
		//new
		design += ' '+deleteHashAndPasteCode('pq_bgtxt_bgcolor_bgtxt_PQCC',document.getElementById('emailListBuilderPopup_background_text_block').value);
		design += ' '+document.getElementById('emailListBuilderPopup_text_block_padding').value;
		design += ' '+deleteHashAndPasteCode('pq_formbg_bgcolor_formbg_PQCC',document.getElementById('emailListBuilderPopup_background_form_block').value);
		design += ' '+document.getElementById('emailListBuilderPopup_form_block_padding').value;
		design += ' '+deleteHashAndPasteCode('pq_btngbg_bgcolor_btngroup_PQCC',document.getElementById('emailListBuilderPopup_background_button_block').value);
		design += ' '+document.getElementById('emailListBuilderPopup_button_block_padding').value;
		
		design += ' '+document.getElementById('emailListBuilderPopup_tblock_text_font').value;
		design += ' '+document.getElementById('emailListBuilderPopup_tblock_text_font_size').value;
		design += ' '+deleteHashAndPasteCode('pq_bgtxt_color_bgtxtp_PQCC',document.getElementById('emailListBuilderPopup_tblock_text_font_color').value);
		design += ' '+document.getElementById('emailListBuilderPopup_showup_animation').value;
				
		var bg_opacity = 10;		
		try{bg_opacity = Number(document.getElementById('emailListBuilderPopup_background_opacity').value)||0;}catch(err){};
		design += ' '+document.getElementById('emailListBuilderPopup_header_img_type').value;
		design += ' '+document.getElementById('emailListBuilderPopup_button_form').value;
		design += ' '+document.getElementById('emailListBuilderPopup_button_type').value;
		design += ' '+document.getElementById('emailListBuilderPopup_input_type').value;
		design += ' '+document.getElementById('emailListBuilderPopup_close_text_font').value;
		
		
		//rgba background
		try{
			if(document.getElementById('emailListBuilderPopup_background_color').value){
				var bgRGB = hexToRgb(document.getElementById('emailListBuilderPopup_background_color').value);			
				if(typeof bgRGB == 'object'){
					design += ' pq_bg_bgcolor_PQRGBA_'+bgRGB.r+'_'+bgRGB.g+'_'+bgRGB.b+'_'+bg_opacity;
				}			
			}
		}catch(err){};
			
				
		//new
		var tblock_text = encodeURIComponent(document.getElementById('emailListBuilderPopup_tblock_text').value)||'';
		var overlay_image_src = encodeURIComponent(document.getElementById('emailListBuilderPopup_overlay_image_src').value)||'';
		
		
		var overlay = ''+deleteHashAndPasteCode('pq_over_bgcolor_PQCC',document.getElementById('emailListBuilderPopup_overlay_color').value)||'';
		var overlay_opacity = document.getElementById('emailListBuilderPopup_overlay_opacity').value||'';
		var close_animation = document.getElementById('emailListBuilderPopup_animation_close_icon').value||'';		
		var close_text = encodeURIComponent(document.getElementById('emailListBuilderPopup_close_text').value)||'';
		var header_i = encodeURIComponent(document.getElementById('emailListBuilderPopup_header_image_src').value)||'';
		var background_i = encodeURIComponent(document.getElementById('emailListBuilderPopup_background_image_src').value)||'';
		var sub_title = encodeURIComponent(document.getElementById('emailListBuilderPopup_subtitle').value)||'';
		var title = encodeURIComponent(document.getElementById('emailListBuilderPopup_title').value)||'';		
		var text_email = encodeURIComponent(document.getElementById('emailListBuilderPopup_text_email').value)||'';		
		var text_name = encodeURIComponent(document.getElementById('emailListBuilderPopup_text_name').value)||'';		
		
		var provider = 'mailchimp';
		if(document.getElementById('emailListBuilderPopup_provider_mailchimp').checked) provider = 'mailchimp';
		if(document.getElementById('emailListBuilderPopup_provider_getresponse').checked) provider = 'getresponse';
		if(document.getElementById('emailListBuilderPopup_provider_aweber').checked) provider = 'aweber';
		//if(document.getElementById('emailListBuilderPopup_provider_newsletter2go').checked) provider = 'newsletter2go';
		if(document.getElementById('emailListBuilderPopup_provider_madmini').checked) provider = 'madmini';
		if(document.getElementById('emailListBuilderPopup_provider_acampaign').checked) provider = 'acampaign';
		if(document.getElementById('emailListBuilderPopup_provider_klickmail').checked) provider = 'klickmail';		
		
		
		var whitelabel = document.getElementById('emailListBuilderPopup_whitelabel').value;
		
		
		design += ' '+document.getElementById('emailListBuilderPopup_button_font').value+' '+document.getElementById('emailListBuilderPopup_button_font_size').value+' '+deleteHashAndPasteCode('pq_btn_color_btngroupbtn_PQCC',document.getElementById('emailListBuilderPopup_button_text_color').value)+' '+deleteHashAndPasteCode('pq_btn_bg_bgcolor_btngroupbtn_PQCC',document.getElementById('emailListBuilderPopup_button_color').value)
		var button_text = encodeURIComponent(document.getElementById('emailListBuilderPopup_button_text').value)||'';		
		if(strstr(document.getElementById('PQEmailListBuilderPopup_PQIframePreviewBlock').className, 'pq_min')){
			var pq_mini = ' pq_min';
		}else{
			var pq_mini = '';
		}
		if(desktopView == '1'){
				document.getElementById('PQEmailListBuilderPopup_PQPreviewID').src = '//profitquery.com/preview/iframe_demo_v5.2.html?tool=emailListBuilderPopup&design='+design+'&whitelabel='+whitelabel+'&overlay='+
					overlay+'&close_animation='+close_animation+'&close_text='+close_text+'&header_i='+header_i+'&background_i='+background_i+'&sub_title='+sub_title+'&title='+
					title+'&button_text='+button_text+'&text_email='+text_email+'&text_name='+text_name+'&provider='+provider+'&tblock_text='+tblock_text+'&overlay_image_src='+overlay_image_src+'&overlay_opacity='+overlay_opacity+'&rtl='+PQ_RTL+'&contOptions='+contOptions;
				document.getElementById('PQEmailListBuilderPopup_PQPreviewID').className='pq_desktop_preview';
				document.getElementById('PQEmailListBuilderPopup_PQIframePreviewBlock').className='frame pq_desktop_preview'+pq_mini;
			}else{
				document.getElementById('PQEmailListBuilderPopup_PQPreviewID').src = '//profitquery.com/preview/iframe_demo_v5.2.html?tool=emailListBuilderPopup&design='+design+'&whitelabel='+whitelabel+'&overlay='+
					overlay+'&close_animation='+close_animation+'&close_text='+close_text+'&header_i='+header_i+'&background_i='+background_i+'&sub_title='+sub_title+'&title='+
					title+'&button_text='+button_text+'&text_email='+text_email+'&text_name='+text_name+'&provider='+provider+'&tblock_text='+tblock_text+'&overlay_image_src='+overlay_image_src+'&overlay_opacity='+overlay_opacity+'&rtl='+PQ_RTL+'&contOptions='+contOptions;
				document.getElementById('PQEmailListBuilderPopup_PQPreviewID').className='pq_mobile_preview';
				document.getElementById('PQEmailListBuilderPopup_PQIframePreviewBlock').className='frame pq_mobile_preview'+pq_mini;
			}
		
	}
	
	
	
	function clickToBlockStructureElements(id){
		if(document.getElementById(id+'_content').style.display == 'none'){
			document.getElementById(id+'_content').style.display = 'block';
		}else{
			document.getElementById(id+'_content').style.display = 'none';
		}
	
	}
	</script>
	<div id="profitquery">
		<div id="PQLoader" style="position: absolute;width: 32px;height: 32px;padding: 30px;background-color: white;left: 0;right: 0;top: 20%;margin: 0 auto;box-shadow: 0 0 1px 0px #9C9C9D;z-index: 12;display:none;"><img src="<?php echo plugins_url('i/loader.gif', __FILE__);?>"></div>
	<?php
		if($_GET[s_t]){
			echo "<script>document.getElementById('PQLoader').style.display='block';</script>";
		}
	?>
		<div class="pq2">
		<!-- COLOR PICKER -->
		<!-- emailListBuilderPopup_ thank cp -->
		<div id="emailListBuilderPopup_thank_background_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_thank_background_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_thank_head_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_thank_head_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_thank_text_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_thank_text_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_thank_close_icon_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_thank_close_icon_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_thank_border_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_thank_border_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_thank_overlay_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_thank_overlay_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_thank_button_text_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_thank_button_text_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_thank_button_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_thank_button_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_thank_background_button_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_thank_background_button_block_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_thank_background_soc_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_thank_background_soc_block_colorPickContainer').style.display='none';">close</span></div>
		
		
		<!-- emailListBuilderBar_ thank cp -->
		<div id="emailListBuilderBar_thank_background_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_thank_background_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_thank_head_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_thank_head_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_thank_text_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_thank_text_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_thank_close_icon_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_thank_close_icon_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_thank_border_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_thank_border_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_thank_overlay_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_thank_overlay_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_thank_button_text_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_thank_button_text_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_thank_button_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_thank_button_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_thank_background_button_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_thank_background_button_block_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_thank_background_soc_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_thank_background_soc_block_colorPickContainer').style.display='none';">close</span></div>

		<!-- emailListBuilderFloating_ thank cp -->
		<div id="emailListBuilderFloating_thank_background_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_thank_background_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_thank_head_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_thank_head_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_thank_text_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_thank_text_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_thank_close_icon_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_thank_close_icon_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_thank_border_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_thank_border_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_thank_overlay_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_thank_overlay_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_thank_button_text_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_thank_button_text_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_thank_button_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_thank_button_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_thank_background_button_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_thank_background_button_block_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_thank_background_soc_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingthankPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_thank_background_soc_block_colorPickContainer').style.display='none';">close</span></div>


		
<!-- emailListBuilderPopup_ cp -->
		<div id="emailListBuilderPopup_background_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_background_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_head_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_head_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_text_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_text_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_close_icon_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_close_icon_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_border_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_border_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_overlay_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_overlay_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_button_text_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_button_text_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_button_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_button_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_background_text_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_background_text_block_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_tblock_text_font_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_tblock_text_font_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_background_form_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_background_form_block_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderPopup_background_button_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderPopupPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderPopup_background_button_block_colorPickContainer').style.display='none';">close</span></div>

		
		
<!-- emailListBuilderBar_ cp -->
		<div id="emailListBuilderBar_background_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_background_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_head_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_head_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_text_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_text_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_close_icon_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_close_icon_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_border_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_border_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_overlay_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_overlay_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_button_text_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_button_text_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_button_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_button_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_background_form_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_background_form_block_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_background_button_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_background_button_block_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_background_mobile_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_background_mobile_block_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderBar_mblock_text_font_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderBarPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderBar_mblock_text_font_color_colorPickContainer').style.display='none';">close</span></div>
		

<!-- emailListBuilderFloating_ cp -->
		<div id="emailListBuilderFloating_background_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_background_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_head_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_head_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_text_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_text_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_close_icon_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_close_icon_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_border_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_border_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_overlay_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_overlay_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_button_text_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_button_text_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_button_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_button_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_background_text_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_background_text_block_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_tblock_text_font_color_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_tblock_text_font_color_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_background_form_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_background_form_block_colorPickContainer').style.display='none';">close</span></div>
		<div id="emailListBuilderFloating_background_button_block_colorPickContainer" class="pq_colors" style="display:none;"><span onclick="emailListBuilderFloatingPreview();">Apply</span><span onclick="document.getElementById('emailListBuilderFloating_background_button_block_colorPickContainer').style.display='none';">close</span></div>
<!--    END COLOR PICK TOOLS      --->
		
		<div class="pq1" id="PQmainHeaderBlock">
			<a href="http://profitquery.com/?utm-campaign=wp_aio_widgets_logo" target="_blank"> <img src="<?php echo plugins_url('i/profitquery_logo.png', __FILE__);?>"></a>
			<a class="pq_adds" href="javascript:void(0)" onclick="document.getElementById('PQ_lang').style.display='block';"><?php if(!$this->_options[settings][lang]) echo 'en'; else echo $this->_options[settings][lang];?></a>
			<a class="pro" type="button" onclick="document.getElementById('PQSettingsPopup').style.display='block';"><img src="<?php echo plugins_url('i/settings_site.png', __FILE__);?>" /></a>
			<a class="pq_info" href="javascript:void(0)" onclick="document.getElementById('PQ_info_popup').style.display='block';"><span>10</span><img src="<?php echo plugins_url('i/info.png', __FILE__);?>"></a>
			<a class="pq_your_pro" href="javascript:void(0)" onclick="document.getElementById('PQ_pro_info').style.display='block';"><?php echo $this->_dictionary[navigation][pro_options];?></a>
		</div>
			<div class="f_wrapper pq_li1" id="PQMainTable">
				<h1><?php echo $this->_dictionary[mainPage][title];?></h1>
				<div class="pq_help">
					<input type="button" value="<?php echo $this->_dictionary[mainPage][need_help];?>" onclick="document.getElementById('PQNeedHelpPopup').style.display='block';" class="need_help">
					<input type="button" value="<?php echo $this->_dictionary[mainPage][add_new_tool];?>" onclick="document.getElementById('PQMainTable').style.display='none';document.getElementById('PQmainHeaderBlock').style.display='none';document.getElementById('PQSelectTools').style.display='block';" class="add_new">
					<!--input type="button" value="<?php echo $this->_dictionary[mainPage][tools_map];?>" onclick="document.getElementById('View_tools_map').style.display='block';" class="view_tools_maps"-->
				</div>
				<div class="clear"></div>
				<table style="">
					<tbody>
					<?php					
						if($toolsArray){				
						foreach((array)$toolsArray as $k => $v)
						{
					?>
						<tr class="<?php if((int)$v[enable] == 0) echo 'pq_disabled'; elseif($v[status][styleBlock] == 'red') echo 'pq_paused'; elseif($v[status][styleBlock] == 'yellow') echo 'pq_attantion';?>">
							<td><span class="<?php
								if((int)$v[enable] == 0){
									echo 'pq_disabled';
								}elseif($v[status][status] == 'trial'){
									echo 'pq_trial';
								}elseif($v[status][status] == 'affiliate'){
									echo 'pq_affiliate';
								}elseif($v[status][status] == 'active'){
									echo 'pq_active';
								}else{
									echo 'pq_paused';
								}
								//if($v[status][status] == 'active'){ echo 'pq_active'; } else {} echo 'pq_paused';
							?>" title="<?php echo $v[status][status_text];?>" alt="<?php echo $v[status][status_text];?>"></span></td>
							<td>
								<?php
									if($v[proOptionsDetail]){
										echo '<img src="'.plugins_url('i/ico/main_pro.png', __FILE__).'" alt="'.$this->_dictionary[toolsStatus][pro].'" title="'.$this->_dictionary[toolsStatus][pro].'">';
									}else{
										echo '<img src="'.plugins_url('i/ico/main_free.png', __FILE__).'" alt="'.$this->_dictionary[toolsStatus][free].'" title="'.$this->_dictionary[toolsStatus][free].'">';
									}
								?>								
							</td>
							<td><img src="<?php echo plugins_url('i/'.$v[name][icon], __FILE__);?>"></td>
							<td><p><?php echo $v[name][name];?></p></td>
							<td><p><?php echo $v[name][type];?></p></td>
							<td>
								<?php
									if($v['eventHandler']['type'] == 'delay'){
										echo '<img src="'.plugins_url('i/ico/main_delay.png', __FILE__).'" alt="'.$v['eventHandler']['name'].' '.$v['eventHandler']['value'].'" title="'.$v['eventHandler']['name'].' '.$v['eventHandler']['value'].'">';
									}
									if($v['eventHandler']['type'] == 'scrolling'){
										echo '<img src="'.plugins_url('i/ico/main_upon.png', __FILE__).'" alt="'.$v['eventHandler']['name'].' '.$v['eventHandler']['value'].'" title="'.$v['eventHandler']['name'].' '.$v['eventHandler']['value'].'">';
									}
									if($v['eventHandler']['type'] == 'exit'){
										echo '<img src="'.plugins_url('i/ico/main_exit.png', __FILE__).'" alt="'.$v['eventHandler']['name'].' '.$v['eventHandler']['value'].'" title="'.$v['eventHandler']['name'].' '.$v['eventHandler']['value'].'">';
									}
								?>								
							</td>
							<td><?php if((int)$v[thank] == '1'){
									echo '<img src="'.plugins_url('i/ico/main_thank_popup.png', __FILE__).'" alt="'.$this->_dictionary[navigation][success_tool].'" title="'.$this->_dictionary[navigation][success_tool].'"> <a class="pq_question" href="http://profitquery.com/blog/faq/2016/01/customize-thank-popup-for-wordpress-plugin/" target="_blank">?</a>';									
								}else{
									echo '<img src="'.plugins_url('i/ico/main_no_thank_popup.png', __FILE__).'" alt="'.$this->_dictionary[navigation][success_tool].'" title="'.$this->_dictionary[navigation][success_tool].'"> <a class="pq_question" href="http://profitquery.com/blog/faq/2016/01/customize-thank-popup-for-wordpress-plugin/" target="_blank">?</a>';
								}
								?>								
							</td>
							
							<td><p><?php echo $v[status][dateColumn];?></p></td>
							
							<td>
								<?php
									if((int)$v[enable] == 1){
										if($v[status][action] == 'activate'){									
											echo '<a href="javascript:void(0);" onclick="document.getElementById(\'PQHowGetPro\').style.display=\'block\';"><img src="'.plugins_url('i/ico/main_activate.png', __FILE__).'" title="'.$this->_dictionary[action][activate].'" alt="'.$this->_dictionary[action][activate].'"></a> | <a href="javascript:void(0);" onclick="disableCurrentOption(\'pro\', \''.$v[name][name].' '.$v[name][type].'\', \''.plugins_url('i/disable_pro.png', __FILE__).'\', \'pro_info\');"><img src="'.plugins_url('i/ico/main_use_free.png', __FILE__).'" alt="'.$this->_dictionary[action][use_free].'" title="'.$this->_dictionary[action][use_free].'"></a>';
										}else if($v[status][action] == 'extend'){									
											echo '<a href="javascript:void(0);" onclick="document.getElementById(\'PQExtendPopup\').style.display=\'block\';"><img src="'.plugins_url('i/ico/main_extend.png', __FILE__).'" title="'.$this->_dictionary[action][extend].'" alt="'.$this->_dictionary[action][extend].'"></a> | <a href="javascript:void(0);" onclick="disableCurrentOption(\'pro\', \''.$v[name][name].' '.$v[name][type].'\', \''.plugins_url('i/disable_pro.png', __FILE__).'\', \'pro_info\');"><img src="'.plugins_url('i/ico/main_use_free.png', __FILE__).'" alt="'.$this->_dictionary[action][use_free].'" title="'.$this->_dictionary[action][use_free].'"></a>';
										}else{
											echo '&nbsp;';
										}
									}else{
										echo '&nbsp;';
									}
								?>
							</td>
							<td><a href="<?php echo $this->getSettingsPageUrl();?>&s_t=<?php echo $k;?>&pos=<?php echo stripslashes($this->_options[$k][position]);?>"><img src="<?php echo plugins_url('i/settings_tool.png', __FILE__);?>" alt="<?php echo $this->_dictionary[mainPage][settings_link];?>" title="<?php echo $this->_dictionary[mainPage][settings_link];?>"></a></td>
							<td>
								<input class="pq_switch" type="checkbox" id="tool_switch_enable_<?php echo $k;?>" name="" onclick="enableDisableCurrentTool(this.checked,'<?php echo $k?>', '<?php echo $v[name][name]." ".$v[name][type];?>', '<?php echo plugins_url('i/'.$v[name][icon], __FILE__);?>')" <?php if((int)$v[enable] == 1) echo 'checked';?> alt="<?php echo $this->_dictionary[mainPage][disable_tool];?>" title="<?php echo $this->_dictionary[mainPage][disable_tool];?>" />
								<label for="tool_switch_enable_<?php echo $k;?>" class="pq_switch_label"></label>
								</label>
							</td>
							<td><a href="javascript:void(0)" onclick="deleteCurrentTool('<?php echo $k?>', '<?php echo $v[name][name]." ".$v[name][type];?>', '<?php echo plugins_url('i/'.$v[name][icon], __FILE__);?>');"><img src="<?php echo plugins_url('i/disable_tool.png', __FILE__);?>" alt="<?php echo $this->_dictionary[mainPage][delete_tool];?>" title="<?php echo $this->_dictionary[mainPage][delete_tool];?>"></a></td>
						</tr>
					<?php
						}
					?>
					<?php
						}else{
					?>
						<tr>
							<td><p><?php echo $this->_dictionary[mainPage][no_tool];?></p></td>
						</tr>
					<?php
						}
					?>
					</tbody>
				</table>
				<input type="button" class="add_new_big" onclick="document.getElementById('PQMainTable').style.display='none';document.getElementById('PQmainHeaderBlock').style.display='none';document.getElementById('PQSelectTools').style.display='block';" value="<?php echo $this->_dictionary[mainPage][add_new_tool];?>">
				<!--input type="button" name="clr" value="Clear All" onclick="location.href='<?php echo $this->getSettingsPageUrl();?>&act=clear'" style="display: block;    margin: 0 auto; border: 0; background-color: transparent; color: #707679; cursor: pointer;"-->
				
							
				<!-- VIEW TOOL MAP -->
				<div class="pq_footer_center"><a href="<?php echo $this->_plugin_review_url;?>" target="_blank"><img src="<?php echo plugins_url('i/rating.png', __FILE__);?>"></a><p><?php printf($this->_dictionary[other][footer_text], 'href="'.$this->_plugin_review_url.'" target="review"');?>
				<br>
				<?php echo $this->_dictionary[proOptionsInfo][start_earning];?><a href="mailto:support@profitquery.com?subject=[PQ_ES_WP <?php echo $this->getDomain();?>] Partner program">support@profitquery.com</a>
				</p></div>
				<div class="pq_clear"></div>
				<div class="pq_footer_left">
					<div id="fb-root"></div>
				<script>(function(d, s, id) {
				  var js, fjs = d.getElementsByTagName(s)[0];
				  if (d.getElementById(id)) return;
				  js = d.createElement(s); js.id = id;
				  js.src = "//connect.facebook.net/en_EN/sdk.js#xfbml=1&version=v2.4&appId=580260312066700";
				  fjs.parentNode.insertBefore(js, fjs);
				}(document, 'script', 'facebook-jssdk'));</script>
				<div class="fb-like" data-href="https://www.facebook.com/profitquery/" data-layout="button" data-action="like" data-show-faces="false" data-share="false"></div>
				<p><a href="javascript:void(0)" onclick="document.getElementById('PQNeedHelpPopup').style.display='block';"><?php echo $this->_dictionary[footerBlock][support];?></a> | <a href="http://profitquery.com/privacy.html" target="_blank"><?php echo $this->_dictionary[footerBlock][privacy];?></a> | <a href="http://profitquery.com/terms.html" target="_blank"><?php echo $this->_dictionary[footerBlock][terms];?></a>
				</div>
				<div class="pq_footer_right"><p><a href="http://profitquery.com/blog/2016/12/free-website-growth-strategy-for-wordpress-plugin-version-5-2/" target="_blank"><?php echo $this->_dictionary[footerBlock][new_5_2_version];?></a></p><p><a href="http://profitquery.com/blog/2015/12/how-to-get-pro-for-free/" target="_blank"><img src="<?php echo plugins_url('i/free_.png', __FILE__);?>"><?php echo $this->_dictionary[footerBlock][pro_for_free];?></a> </p></div>
				
				
			</div>
			
			<!-- Select Tools Div -->
			<div id="PQSelectTools" style="display:none;" class="f_wrapper pq_li2">
				<div class="pq_nav">
				<div class="pq_status_menu">
					<span onclick="location.href='<?php echo $this->getSettingsPageUrl();?>';"><?php echo $this->_dictionary[navigation][index];?></span>
					<span class="active"><?php echo $this->_dictionary[navigation][your_tools];?></span>
					<input type="button" value="<?php echo $this->_dictionary[selectToolsDialog][button_help];?>" onclick="document.getElementById('PQNeedHelpPopup').style.display='block';" class="need_help">
				</div>
				</div>
				<div id="PQSelectGoalStep" style="display:block;">
					<div id="PQDownloadAll" style="display:block;">
						<h1><?php echo $this->_dictionary[selectToolsDialog][download_aio_title];?></h1>
						<p><?php echo $this->_dictionary[selectToolsDialog][description];?></p>
						<img src="<?php echo plugins_url('i/download_all.jpg', __FILE__);?>">
						<a href="https://wordpress.org/plugins/share-subscribe-contact-aio-widget/" target="download"><input type="button" class="add_new_big" value="<?php echo $this->_dictionary[selectToolsDialog][download_button];?>"></a>
					</div>
					<h1><?php echo $this->_dictionary[selectToolsDialog][select_goals];?></h1>
					<p style="margin-bottom: 25px;"><?php echo $this->_dictionary[selectToolsDialog][description];?></p>
					
					<div class="pq_nav">
							
							<div class="pq_goal" id="PQGoalsSharing" onclick="sortTools('Sharing')"><img src="<?php echo plugins_url('i/ico/ico_socialsharing_b.png', __FILE__);?>"><p><?php echo $this->_dictionary[toolsGroup][share_buttons];?></p></div>
							<div class="pq_goal" id="PQGoalsEmailListBuilder" onclick="sortTools('EmailListBuilder')"><img src="<?php echo plugins_url('i/ico/ico_emaillist_b.png', __FILE__);?>"><p><?php echo $this->_dictionary[toolsGroup][email_list_builder];?></p></div>
							<div class="pq_goal" id="PQGoalsContactForm" onclick="sortTools('ContactForm')"><img src="<?php echo plugins_url('i/ico/ico_contactus_b.png', __FILE__);?>"><p><?php echo $this->_dictionary[toolsGroup][contact_form];?></p></div>
							<div class="pq_goal" id="PQGoalsPromote" onclick="sortTools('Promote')"><img src="<?php echo plugins_url('i/ico/ico_promotelink_b.png', __FILE__);?>"><p><?php echo $this->_dictionary[toolsGroup][promotion_tools];?></p></div>
							<div class="pq_goal" id="PQGoalsCallMe" onclick="sortTools('CallMe')"><img src="<?php echo plugins_url('i/ico/ico_callmenow_b.png', __FILE__);?>"><p><?php echo $this->_dictionary[toolsGroup][call_me];?></p></div>
							<div class="pq_goal" id="PQGoalsFollow" onclick="sortTools('Follow')"><img src="<?php echo plugins_url('i/ico/ico_follow_b.png', __FILE__);?>"><p><?php echo $this->_dictionary[toolsGroup][follow_buttons];?></p></div>
							<div class="pq_goal" id="PQGoalsIframe" onclick="sortTools('Iframe')"><img src="<?php echo plugins_url('i/ico/ico_iframe_b.png', __FILE__);?>"><p><?php echo $this->_dictionary[toolsGroup][iframe_embed];?></p></div>
							<div class="pq_goal" id="PQGoalsYoutube" onclick="sortTools('Youtube')"><img src="<?php echo plugins_url('i/ico/ico_youtube_b.png', __FILE__);?>"><p><?php echo $this->_dictionary[toolsGroup][youtube_embed];?></p></div>
											
						
					</div>
				</div>
				<div class="pq_clear"></div>
				<div id="PQSelectToolStep" style="display:none;">
					
					<h1><?php echo $this->_dictionary[selectToolsDialog][title];?> </h1>
					<p><?php echo $this->_dictionary[selectToolsDialog][choose_tool_type];?></p>
					<a href="javascript:void(0)" onclick="selectGoalsStep()" class="pq_back"></a>
					<a href="javascript:void(0)" onclick="selectGoalsStep()" class="pq_all"><?php echo $this->_dictionary[other][back_to_goals];?></a>
					<a class="pq_any_tool" onclick="document.getElementById('PQNeedHelpPopup').style.display='block';"><?php echo $this->_dictionary[other][need_more_tool];?></a>
					<div style="overflow-y: scroll; overflow-x: hidden; position: absolute; bottom: 0; top: 230px; left: 30px; right: 0; text-align: left; padding-right: 15px; box-sizing: border-box;">
					<a class="pq_label" id="SelectTool_Sharing_1" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=sharingSidebar&pos=<?php echo $this->_options[sharingSidebar][position];?>" >
						<div><img src="<?php echo plugins_url('i/tool_sharing_sidebar.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_sharing_sidebar.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][sharing_sidebar];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_sharing_sidebar.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Sharing_2" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=imageSharer" >
						<div><img src="<?php echo plugins_url('i/tool_image_sharer.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_image_sharer.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][image_sharer];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_image_sharer.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Sharing_3" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=sharingPopup" >
						<div><img src="<?php echo plugins_url('i/tool_sharing_popup.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_sharing_popup.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][sharing_popup];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_sharing_popup.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Sharing_4" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=sharingBar" >
						<div><img src="<?php echo plugins_url('i/tool_sharing_bar.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_sharing_bar.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][sharing_bar];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_sharing_bar.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Sharing_5" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=sharingFloating" >
						<div><img src="<?php echo plugins_url('i/tool_sharing_floating.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_sharing_floating.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][sharing_floating_popup];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_sharing_floating.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_EmailListBuilder_1" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=emailListBuilderPopup" >
						<div><img src="<?php echo plugins_url('i/tool_collect_email_popup.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_collect_email_popup.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][email_list_builder_popup];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_collect_email_popup.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_EmailListBuilder_2" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=emailListBuilderBar" >
						<div><img src="<?php echo plugins_url('i/tool_collect_email_bar.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_collect_email_bar.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][email_list_builder_bar];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_collect_email_bar.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_EmailListBuilder_3" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=emailListBuilderFloating" >
						<div><img src="<?php echo plugins_url('i/tool_collect_email_floating.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_collect_email_floating.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][email_list_builder_floating];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_collect_email_floating.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_ContactForm_1" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=contactFormCenter" >
						<div><img src="<?php echo plugins_url('i/tool_contact_form_center.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_contact_form_center.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][contact_form_center];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_contact_form_center.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_ContactForm_2" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=contactFormPopup" >
						<div><img src="<?php echo plugins_url('i/tool_contact_form_popup.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_contact_form_popup.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][contact_form_bookmark];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_contact_form_popup.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_ContactForm_3" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=contactFormFloating" >
						<div><img src="<?php echo plugins_url('i/tool_contact_form_floating.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_contact_form_floating.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][contact_form_floating_popup];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_contact_form_floating.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Promote_1" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=promotePopup" >
						<div><img src="<?php echo plugins_url('i/tool_promote_popup.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_promote_popup.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][promotion_popup];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_promote_popup.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Promote_2" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=promoteBar" >
						<div><img src="<?php echo plugins_url('i/tool_promote_bar.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_promote_bar.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][promotion_bar];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_promote_bar.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Promote_3" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=promoteFloating" >
						<div><img src="<?php echo plugins_url('i/tool_promote_floating.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_promote_floating.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][promotion_floating_popup];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_promote_floating.png', __FILE__);?>" class="pq_ico">
					</a>					
					<a class="pq_label" id="SelectTool_CallMe_1" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=callMePopup" >
						<div><img src="<?php echo plugins_url('i/tool_call_me_popup.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_call_me_popup.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][call_me_bookmark];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_call_me_popup.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_CallMe_2" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=callMeFloating" >
						<div><img src="<?php echo plugins_url('i/tool_call_me_floating.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_call_me_floating.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][call_me_floating_popup];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_call_me_floating.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Follow_1" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=followPopup" >
						<div><img src="<?php echo plugins_url('i/tool_follow_popup.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_follow_popup.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][follow_popup];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_follow_popup.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Follow_2" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=followBar" >
						<div><img src="<?php echo plugins_url('i/tool_follow_bar.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_follow_bar.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][follow_bar];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_follow_bar.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Follow_3" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=followFloating" >
						<div><img src="<?php echo plugins_url('i/tool_follow_floating.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_follow_floating.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][follow_floating_popup];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_follow_floating.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Iframe_1" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=iframePopup" >
						<div><img src="<?php echo plugins_url('i/tool_iframe_popup.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_iframe_popup.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][iframe_popup];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_iframe_popup.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Iframe_2" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=iframeFloating" >
						<div><img src="<?php echo plugins_url('i/tool_iframe_floating.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_iframe_floating.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][iframe_floating_popup];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_iframe_floating.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Youtube_1" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=youtubePopup" >
						<div><img src="<?php echo plugins_url('i/tool_youtube_popup.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_youtube_popup.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][youtube_popup];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_youtube_popup.png', __FILE__);?>" class="pq_ico">
					</a>
					<a class="pq_label" id="SelectTool_Youtube_2" style="display:none;" href="<?php echo $this->getSettingsPageUrl();?>&s_t=youtubeFloating" >
						<div><img src="<?php echo plugins_url('i/tool_youtube_floating.png', __FILE__);?>"><img src="<?php echo plugins_url('i/tool_youtube_floating.gif', __FILE__);?>" class="pq_label_hover"></div>
						<p><?php echo $this->_dictionary[toolName][youtube_floating_popup];?></p>
						<span class="pq_enable"><?php echo $this->_dictionary[selectToolsDialog][settings];?></span>
						<img src="<?php echo plugins_url('i/tools/ico_youtube_floating.png', __FILE__);?>" class="pq_ico">
					</a>
					</div>
				</div>
			</div>
			<!-- End Select Tools Div -->
			
			
			
			
	<!-- ***********************************************************************START TOOLS*********************************************************************************** -->
	<script>



	function clearLastChangesProceed(){		
		location.href='<?php echo $this->getSettingsPageUrl();?>&act=clearLastChanges&s_t='+document.getElementById('PQCLearLastChangesTool').value;
	}

	function clearLastChanges(id){	
		document.getElementById('PQClearLastChangesPopup').style.display='block';	
		document.getElementById('PQCLearLastChangesTool').value=id;	
		
	}

	function returnToProviderStep(id){
		disableAllDialog();			
		if(id == 'emailListBuilderPopup'){			
			startTool('PQEmailListBuilderPopup');
			selectStep('PQEmailListBuilderPopup', 4);					
		}
		
		
		
		if(id == 'emailListBuilderFloating'){
			startTool('PQEmailListBuilderFloating');
			selectStep('PQEmailListBuilderFloating', 4);
		}
		
		if(id == 'emailListBuilderBar'){
			startTool('PQEmailListBuilderBar');
			selectStep('PQEmailListBuilderBar', 4);
		}
	}

	function returnToDesignStep(id){	
		disableAllDialog();
		if(id == 'sharingSidebar'){		
			startTool('PQSharingSidebar');
			selectStep('PQSharingSidebar', 2);
			enablePreviewIframe('PQSharingSidebar');
			selectForm('PQSharingSidebar', 'main');
			//sharingSidebarPreview();
		}
		if(id == 'imageSharer'){
			startTool('PQImageSharer');
			selectStep('PQImageSharer', 2);
			enablePreviewIframe('PQImageSharer');
			selectForm('PQImageSharer', 'main');
			//imageSharerPreview();
		}

		if(id == 'sharingPopup'){
			startTool('PQSharingPopup');
			selectStep('PQSharingPopup', 2);
			enablePreviewIframe('PQSharingPopup');
			selectForm('PQSharingPopup', 'main');
			//sharingPopupPreview();
		}

		if(id == 'sharingBar'){
			startTool('PQSharingBar');
			selectStep('PQSharingBar', 2);
			enablePreviewIframe('PQSharingBar');
			selectForm('PQSharingBar', 'main');
			//sharingBarPreview();
		}

		if(id == 'sharingFloating'){
			startTool('PQSharingFloating');
			selectStep('PQSharingFloating', 2);
			enablePreviewIframe('PQSharingFloating');
			selectForm('PQSharingFloating', 'main');
			//sharingFloatingPreview();
		}

		if(id == 'emailListBuilderPopup'){
			startTool('PQEmailListBuilderPopup');
			selectStep('PQEmailListBuilderPopup', 2);
			enablePreviewIframe('PQEmailListBuilderPopup');
			selectForm('PQEmailListBuilderPopup', 'main');
			//emailListBuilderPopupPreview();
		}
		
		

		if(id == 'emailListBuilderBar'){
			startTool('PQEmailListBuilderBar');
			selectStep('PQEmailListBuilderBar', 2);
			enablePreviewIframe('PQEmailListBuilderBar');
			selectForm('PQEmailListBuilderBar', 'main');
			//emailListBuilderBarPreview();
		}

		if(id == 'emailListBuilderFloating'){
			startTool('PQEmailListBuilderFloating');
			selectStep('PQEmailListBuilderFloating', 2);
			enablePreviewIframe('PQEmailListBuilderFloating');
			selectForm('PQEmailListBuilderFloating', 'main');
			//emailListBuilderFloatingPreview();
		}

		if(id == 'contactFormCenter'){
			startTool('PQcontactFormCenter');
			selectStep('PQcontactFormCenter', 2);
			enablePreviewIframe('PQcontactFormCenter');
			selectForm('PQcontactFormCenter', 'main');
			//contactFormCenterPreview();
		}
		
		if(id == 'contactFormPopup'){
			startTool('PQcontactFormPopup');
			selectStep('PQcontactFormPopup', 2);
			enablePreviewIframe('PQcontactFormPopup');
			selectForm('PQcontactFormPopup', 'main');
			//contactFormPopupPreview();
		}

		if(id == 'contactFormFloating'){
			startTool('PQcontactFormFloating');
			selectStep('PQcontactFormFloating', 2);
			enablePreviewIframe('PQcontactFormFloating');
			selectForm('PQcontactFormFloating', 'main');
			//contactFormFloatingPreview();
		}

		if(id == 'promotePopup'){
			startTool('PQPromotePopup');
			selectStep('PQPromotePopup', 2);
			enablePreviewIframe('PQPromotePopup');
			selectForm('PQPromotePopup', 'main');
			//promotePopupPreview();
		}

		if(id == 'promoteBar'){
			startTool('PQPromoteBar');
			selectStep('PQPromoteBar', 2);
			enablePreviewIframe('PQPromoteBar');
			selectForm('PQPromoteBar', 'main');
			//promoteBarPreview();
		}
		
		

		if(id == 'promoteFloating'){
			startTool('PQPromoteFloating');
			selectStep('PQPromoteFloating', 2);
			enablePreviewIframe('PQPromoteFloating');
			selectForm('PQPromoteFloating', 'main');
			//promoteFloatingPreview();
		}

		if(id == 'callMePopup'){
			startTool('PQCallMePopup');
			selectStep('PQCallMePopup', 2);
			enablePreviewIframe('PQCallMePopup');
			selectForm('PQCallMePopup', 'main');
			//callMePopupPreview();
		}

		if(id == 'callMeFloating'){
			startTool('PQCallMeFloating');
			selectStep('PQCallMeFloating', 2);
			enablePreviewIframe('PQCallMeFloating');
			selectForm('PQCallMeFloating', 'main');
			//callMeFloatingPreview();
		}

		if(id == 'followPopup'){
			startTool('PQFollowPopup');
			selectStep('PQFollowPopup', 2);
			enablePreviewIframe('PQFollowPopup');
			selectForm('PQFollowPopup', 'main');
			//followPopupPreview();
		}

		if(id == 'followBar'){
			startTool('PQFollowBar');
			selectStep('PQFollowBar', 2);
			enablePreviewIframe('PQFollowBar');
			selectForm('PQFollowBar', 'main');
			//followBarPreview();
		}

		if(id == 'followFloating'){
			startTool('PQFollowFloating');
			selectStep('PQFollowFloating', 2);
			enablePreviewIframe('PQFollowFloating');
			selectForm('PQFollowFloating', 'main');
			//followFloatingPreview();
		}

		if(id == 'iframePopup'){
			startTool('PQiframePopup');
			selectStep('PQiframePopup', 2);
			enablePreviewIframe('PQiframePopup');
			selectForm('PQiframePopup', 'main');
			//iframePopupPreview();
		}
		
		if(id == 'iframeFloating'){
			startTool('PQiframeFloating');
			selectStep('PQiframeFloating', 2);
			enablePreviewIframe('PQiframeFloating');
			selectForm('PQiframeFloating', 'main');
			//iframeFloatingPreview();
		}

		if(id == 'youtubePopup'){
			startTool('PQyoutubePopup');
			selectStep('PQyoutubePopup', 2);
			enablePreviewIframe('PQyoutubePopup');
			selectForm('PQyoutubePopup', 'main');
			//youtubePopupPreview();
		}
		
		if(id == 'youtubeFloating'){
			startTool('PQyoutubeFloating');
			selectStep('PQyoutubeFloating', 2);
			enablePreviewIframe('PQyoutubeFloating');
			selectForm('PQyoutubeFloating', 'main');
			//youtubeFloatingPreview();
		}
	}


	function selectSubForm(id, type, subtype){	
		try{
			document.getElementById(id+'_form_main_mobile').style.display = 'none';
		}catch(err){};
		try{
			document.getElementById(id+'_form_main_desktop').style.display = 'none';
		}catch(err){};
		try{
			document.getElementById(id+'_form_thank_mobile').style.display = 'none';
		}catch(err){};
		try{
			document.getElementById(id+'_form_thank_desktop').style.display = 'none';
		}catch(err){};
		try{
			document.getElementById(id+'_form_email_mobile').style.display = 'none';
		}catch(err){};
		try{
			document.getElementById(id+'_form_email_desktop').style.display = 'none';
		}catch(err){};
		try{
			document.getElementById(id+'_form_'+type+'_'+subtype).style.display = 'block';
		}catch(err){};
	}

	function selectForm(id, type){		
		try{
			document.getElementById(id+'_form_main').style.display = 'none';
		}catch(err){};
		try{
			document.getElementById(id+'_form_email').style.display = 'none';
		}catch(err){};
		try{
			document.getElementById(id+'_form_thank').style.display = 'none';
		}catch(err){};
		try{
			document.getElementById(id+'_form_'+type).style.display = 'block';
		}catch(err){};
	}

	function disableAllDialog(){
		closeAllColorPickContainer();
		try{document.getElementById('PQMainTable').style.display='none';}catch(err){};
		try{document.getElementById('PQSelectTools').style.display='none';}catch(err){};	
		
		try{document.getElementById('PQ_Disable_Popup').style.display='none';}catch(err){};	
		try{document.getElementById('PQ_Delete_Popup').style.display='none';}catch(err){};	
		try{document.getElementById('PQ_Enable_Popup').style.display='none';}catch(err){};	
		try{}catch(err){};
		try{}catch(err){};
		try{document.getElementById('PQPositionBlock').style.display='none';}catch(err){};
		try{document.getElementById('PQISPositionBlock').style.display='none';}catch(err){};
		try{document.getElementById('PQThemeSelectPreviewPopup').style.display='none';}catch(err){};
		try{document.getElementById('PQChooseProPopup').style.display='none';}catch(err){};
		
		try{document.getElementById('PQmainHeaderBlock').style.display='none';}catch(err){};		
		
		try{document.getElementById('PQContinueCheckoutForm').style.display='none';}catch(err){};
		try{document.getElementById('PQExtendPopup').style.display='none';}catch(err){};
		try{document.getElementById('PQUpgradePopup').style.display='none';}catch(err){};
		try{document.getElementById('PQActivatePopup').style.display='none';}catch(err){};
		
		//tools
		try{document.getElementById('PQSharingSidebar').style.display='none';}catch(err){};
		try{document.getElementById('PQImageSharer').style.display='none';}catch(err){};
		try{document.getElementById('PQSharingPopup').style.display='none';}catch(err){};
		try{document.getElementById('PQSharingBar').style.display='none';}catch(err){};
		try{document.getElementById('PQSharingFloating').style.display='none';}catch(err){};
		try{document.getElementById('PQEmailListBuilderPopup').style.display='none';}catch(err){};
		try{document.getElementById('PQEmailListBuilderBar').style.display='none';}catch(err){};
		try{document.getElementById('PQEmailListBuilderFloating').style.display='none';}catch(err){};
		try{document.getElementById('PQcontactFormPopup').style.display='none';}catch(err){};
		try{document.getElementById('PQcontactFormFloating').style.display='none';}catch(err){};
		try{document.getElementById('PQPromotePopup').style.display='none';}catch(err){};
		try{document.getElementById('PQPromoteBar').style.display='none';}catch(err){};
		try{document.getElementById('PQPromoteFloating').style.display='none';}catch(err){};
		try{document.getElementById('PQCallMePopup').style.display='none';}catch(err){};
		try{document.getElementById('PQCallMeFloating').style.display='none';}catch(err){};
		try{document.getElementById('PQFollowPopup').style.display='none';}catch(err){};
		try{document.getElementById('PQFollowBar').style.display='none';}catch(err){};
		try{document.getElementById('PQFollowFloating').style.display='none';}catch(err){};
		try{document.getElementById('PQiframePopup').style.display='none';}catch(err){};
		try{document.getElementById('PQiframeFloating').style.display='none';}catch(err){};
		try{document.getElementById('PQyoutubePopup').style.display='none';}catch(err){};
		try{document.getElementById('PQyoutubeFloating').style.display='none';}catch(err){};		
	}

	function mainPage(){
		disableAllDialog();
		document.getElementById('PQMainTable').style.display='block';		
		document.getElementById('PQmainHeaderBlock').style.display='block';		
	}

	function enablePreviewIframe(id){		
		document.getElementById(id+'_PQForMobilePaper').style.display='block';	
		document.getElementById(id+'_PQIframePreviewBlock').style.display='block';	
		document.getElementById(id+'_PQPreviewID').style.display='block';
		
		selectForm(id, 'main', ['desktop', 'mobile']);
		addClassToSwitcher(id, 'main', 'pq_active',['main', 'thank']);
		
		if(id == 'PQSharingSidebar'){sharingSidebarPreview();}
		if(id == 'PQImageSharer'){imageSharerPreview();}
		if(id == 'PQSharingPopup'){sharingPopupPreview();}
		if(id == 'PQSharingBar'){sharingBarPreview();}
		if(id == 'PQSharingFloating'){sharingFloatingPreview();}
		if(id == 'PQEmailListBuilderPopup'){
			
			emailListBuilderPopupPreview();
		}
		if(id == 'PQEmailListBuilderBar'){emailListBuilderBarPreview();}
		if(id == 'PQEmailListBuilderFloating'){emailListBuilderFloatingPreview();}
		if(id == 'PQcontactFormCenter'){contactFormCenterPreview();}
		if(id == 'PQcontactFormPopup'){contactFormPopupPreview();}
		if(id == 'PQcontactFormFloating'){contactFormFloatingPreview();}
		if(id == 'PQPromotePopup'){promotePopupPreview();}
		if(id == 'PQPromoteBar'){promoteBarPreview();}
		if(id == 'PQPromoteFloating'){promoteFloatingPreview();}
		if(id == 'PQCallMePopup'){callMePopupPreview();}
		if(id == 'PQCallMeFloating'){callMeFloatingPreview();}
		if(id == 'PQFollowPopup'){followPopupPreview();}
		if(id == 'PQFollowBar'){followBarPreview();}
		if(id == 'PQFollowFloating'){followFloatingPreview();}
		if(id == 'PQiframePopup'){iframePopupPreview();}
		if(id == 'PQiframeFloating'){iframeFloatingPreview();}
		if(id == 'PQyoutubePopup'){youtubePopupPreview();}
		if(id == 'PQyoutubeFloating'){youtubeFloatingPreview();}
		
		
	}

	function disableAllToolsStep(id){
		try{
			for (var i = 1; i <= 10; i++) {
				document.getElementById(id+'_step_'+i).style.display = 'none';
				document.getElementById(id+'_nav_step_'+i).className = '';
			}
		}catch(err){};
	}

	function selectStep(id, step){	
		closeAllColorPickContainer();
		disableAllToolsStep(id);			
		try{
			document.getElementById(id+'_step_'+step).style.display = 'block';
			document.getElementById(id+'_nav_step_'+step).className = 'active';
		}catch(err){};
		
		if(step>=2){
			document.getElementById(id+'_next_button').style.display = 'block';
			document.getElementById(id+'_next_button').onclick=function(){
				selectStep(id, (step+1));
			}
		}
		
		document.getElementById(id+'_submit_button').style.display = 'none';
		document.getElementById(id+'_next_button').style.display = 'none';
		
				
		if(step == 4){
			document.getElementById(id+'_submit_button').style.display = 'block';
			document.getElementById(id+'_next_button').style.display = 'none';
		}else{
			document.getElementById(id+'_submit_button').style.display = 'none';			
			document.getElementById(id+'_next_button').style.display = 'block';
		}
		
		if(id == 'PQSharingSidebar'){
			if(step == 3){
				document.getElementById(id+'_submit_button').style.display = 'block';
				document.getElementById(id+'_next_button').style.display = 'none';
			}else{
				document.getElementById(id+'_submit_button').style.display = 'none';			
				document.getElementById(id+'_next_button').style.display = 'block';
			}
		}
		
		if(step == 1){
			document.getElementById(id+'_next_button').style.display = 'none';
		}
		if(step==2){			
			setToolFirstDesignStep(getNameById(id), id, '')			
		}
	}
	
	function setToolFirstDesignStep(toolName, toolId, type){
		if(type == 'sendMail'){
			enableDesignFormByBlockClick(toolName+'_sendMail', 'general', 'general,heading,text,form,button,close,pro');
		}
		if(type == 'thank'){
			enableDesignFormByBlockClick(toolName+'_thank', 'general', 'general,heading,text,icons,button,close,pro');
		}
		if(type == ''){
			enableDesignFormByBlockClick(toolName, 'general', 'heading,text_block,promo_block,soc_icons_block,close_block,form_block,button_block,bookmark,mobile_text,mobile_general,gallery,pro_block');
		}
		
		selectSubForm(toolId, 'main', 'desktop');
		addClassToSwitcher(toolId+'_main', 'desktop', 'pq_active',['desktop', 'mobile']);	
	}							

	function enableFirstStepForMobileDesktop(toolName, block_name){		
		enableDesignFormByBlockClick(toolName, block_name, 'heading,text_block,promo_block,soc_icons_block,close_block,form_block,button_block,bookmark,mobile_text,mobile_general,gallery,pro_block');
	}

	function disableAllPositionExept(structureName){	
		for(var i in PQDefaultPosition.all){		
			document.getElementById(i).className="";
			if(PQDefaultPosition[structureName][i]){			
				document.getElementById(i).disabled=false;			
			}else{
				document.getElementById(i).disabled=true;			
			}
		}
	}
	
	function nextStepFromThemeIS(){
		startTool('PQImageSharer');
	}
	
	function nextStepFromTheme(){	
		startTool(document.getElementById('CURRENT_TOOL').value);
	}

	function selectPosition(tool, structureName){	
		disableAllPositionExept(structureName);
			
		document.getElementById('PQPositionBlock').style.display='block';			
		document.getElementById('CURRENT_TOOL').value=tool;
	}

	function setISPosition(t){		
		setToolPosition('PQImageSharer', t.id)
		startTool('PQImageSharer');	
	}
	
	function setPosition(t){		
		setToolPosition(document.getElementById('CURRENT_TOOL').value, t.id)
		startTool(document.getElementById('CURRENT_TOOL').value);	
	}

	function setToolPosition(id, position){
		try{		
			document.getElementById(id+'_position').value=position;
		}catch(err){};	
	}

	function startTool(id){	
		document.getElementById('PQPositionBlock').style.display='none';		
		document.getElementById('PQISPositionBlock').style.display='none';		
		//start view
		document.getElementById(id).style.display='block';
		selectStep(id, 1);
	}

	function addClassToSwitcher(id, current, clsN, arr){
		closeAllColorPickContainer();
		try{
			for(var i in arr){
				document.getElementById(id+'_DesignToolSwitch_'+arr[i]).className = '';
			}
		}catch(err){};	
		document.getElementById(id+'_DesignToolSwitch_'+current).className = clsN;	
	}


	/****************************TOOLS LOADER JS********************************/
	
	function disableToolDesignSteps(id){
		try{document.getElementById(''+id+'_step_1').style.display = 'none';}catch(err){}
		try{document.getElementById(''+id+'_step_2').style.display = 'none';}catch(err){}
		try{document.getElementById(''+id+'_step_3').style.display = 'none';}catch(err){}
		try{document.getElementById(''+id+'_step_4').style.display = 'none';}catch(err){}
		try{document.getElementById(''+id+'_step_5').style.display = 'none';}catch(err){}
	}
	
	//SHARING SIDEBAR
	function sharingSidebarSettings(pos){		
		disableAllDialog();		
		selectPosition('PQSharingSidebar', 'sharingSidebar');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQSharingSidebar');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//EMAIl LIST BUILDER POPUP
	function emailListBuilderPopupSettings(pos){		
		disableAllDialog();		
		selectPosition('PQEmailListBuilderPopup', 'emailListBuilderPopup');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQEmailListBuilderPopup');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//EMAIl LIST BUILDER FLOATING
	function emailListBuilderFloatingSettings(pos){		
		disableAllDialog();		
		selectPosition('PQEmailListBuilderFloating', 'emailListBuilderFloating');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQEmailListBuilderFloating');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//EMAIl LIST BUILDER BAR
	function emailListBuilderBarSettings(pos){		
		disableAllDialog();		
		selectPosition('PQEmailListBuilderBar', 'emailListBuilderBar');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQEmailListBuilderBar');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}
	
	

	//SHARING BAR
	function sharingBarSettings(pos){		
		disableAllDialog();		
		selectPosition('PQSharingBar', 'sharingBar');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQSharingBar');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//SHARING POPUP
	function sharingPopupSettings(pos){		
		disableAllDialog();		
		selectPosition('PQSharingPopup', 'sharingPopup');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQSharingPopup');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//SHARING FLOATING
	function sharingFloatingSettings(pos){		
		disableAllDialog();		
		selectPosition('PQSharingFloating', 'sharingFloating');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQSharingFloating');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}
	
	//CONTACT Form Center
	function contactFormCenterSettings(pos){		
		disableAllDialog();		
		selectPosition('PQcontactFormCenter', 'contactFormCenter');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQcontactFormCenter');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}
	
	//CONTACT Form POPUP
	function contactFormPopupSettings(pos){		
		disableAllDialog();		
		selectPosition('PQcontactFormPopup', 'contactFormPopup');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQcontactFormPopup');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//CONTACT FORM FLOATING FLOATING
	function contactFormFloatingSettings(pos){		
		disableAllDialog();		
		selectPosition('PQcontactFormFloating', 'contactFormFloating');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQcontactFormFloating');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//PROMOTE POPUP
	function promotePopupSettings(pos){		
		disableAllDialog();		
		selectPosition('PQPromotePopup', 'promotePopup');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQPromotePopup');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//PROMOTE BAR
	function promoteBarSettings(pos){		
		disableAllDialog();		
		selectPosition('PQPromoteBar', 'promoteBar');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQPromoteBar');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}
	
	

	//PROMOTE FLOATING
	function promoteFloatingSettings(pos){		
		disableAllDialog();		
		selectPosition('PQPromoteFloating', 'promoteFloating');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQPromoteFloating');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//CALL ME POPUP
	function callMePopupSettings(pos){		
		disableAllDialog();		
		selectPosition('PQCallMePopup', 'callMePopup');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQCallMePopup');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//CALL ME FLOATING
	function callMeFloatingSettings(pos){		
		disableAllDialog();		
		selectPosition('PQCallMeFloating', 'callMeFloating');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQCallMeFloating');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//FOLLOW POPUP
	function followPopupSettings(pos){		
		disableAllDialog();		
		selectPosition('PQFollowPopup', 'followPopup');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQFollowPopup');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//FOLLOW BAR
	function followBarSettings(pos){		
		disableAllDialog();		
		selectPosition('PQFollowBar', 'followBar');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQFollowBar');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//SHARING FLOATING
	function followFloatingSettings(pos){		
		disableAllDialog();		
		selectPosition('PQFollowFloating', 'followFloating');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQFollowFloating');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//IFRAME POPUP
	function iframePopupSettings(pos){		
		disableAllDialog();		
		selectPosition('PQiframePopup', 'iframePopup');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQiframePopup');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}
	
	function iframeFloatingSettings(pos){		
		disableAllDialog();		
		selectPosition('PQiframeFloating', 'iframeFloating');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQiframeFloating');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}

	//YOUTUBE POPUP
	function youtubePopupSettings(pos){		
		disableAllDialog();		
		selectPosition('PQyoutubePopup', 'youtubePopup');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQyoutubePopup');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}
	
	function youtubeFloatingSettings(pos){		
		disableAllDialog();		
		selectPosition('PQyoutubeFloating', 'youtubeFloating');
		document.getElementById('PQPositionBlockSkipButton').disabled = true;
		disableToolDesignSteps('PQyoutubeFloating');
		if(typeof pos != 'undefined'){
			document.getElementById('PQPositionBlockSkipButton').disabled = false;
			
			if(pos == 'BAR_TOP') document.getElementById('BAR_TOP').className = 'pq_checked';		
			if(pos == 'BAR_BOTTOM') document.getElementById('BAR_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_TOP') document.getElementById('SIDE_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_MIDDLE') document.getElementById('SIDE_LEFT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_LEFT_BOTTOM') document.getElementById('SIDE_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_TOP') document.getElementById('SIDE_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_MIDDLE') document.getElementById('SIDE_RIGHT_MIDDLE').className = 'pq_checked';		
			if(pos == 'SIDE_RIGHT_BOTTOM') document.getElementById('SIDE_RIGHT_BOTTOM').className = 'pq_checked';		
			if(pos == 'CENTER') document.getElementById('CENTER').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_TOP') document.getElementById('FLOATING_LEFT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_LEFT_BOTTOM') document.getElementById('FLOATING_LEFT_BOTTOM').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_TOP') document.getElementById('FLOATING_RIGHT_TOP').className = 'pq_checked';		
			if(pos == 'FLOATING_RIGHT_BOTTOM') document.getElementById('FLOATING_RIGHT_BOTTOM').className = 'pq_checked';
		}
	}


	//IMAGE SHARER
	function imageSharerSettings(pos){		
		disableAllDialog();
		disableToolDesignSteps('PQImageSharer');
		document.getElementById('PQISPositionBlockSkipButton').disabled = true;		
		document.getElementById('PQISPositionBlock').style.display='block';
		
		document.getElementById('IS_TOP_LEFT_INSIDE').className = '';
		document.getElementById('IS_TOP_LEFT_OUTSIDE').className = '';
		document.getElementById('IS_TOP_RIGHT_INSIDE').className = '';
		document.getElementById('IS_TOP_RIGHT_OUTSIDE').className = '';
		document.getElementById('IS_CENTER').className = '';
		document.getElementById('IS_BOTTOM_LEFT_INSIDE').className = '';
		document.getElementById('IS_BOTTOM_CENTER_INSIDE').className = '';
		document.getElementById('IS_BOTTOM_RIGHT_INSIDE').className = '';
		
		if(typeof pos != 'undefined'){
			document.getElementById('PQISPositionBlockSkipButton').disabled = false;
					
			if(pos == 'IS_TOP_LEFT_INSIDE') document.getElementById('IS_TOP_LEFT_INSIDE').className = 'pq_checked';		
			if(pos == 'IS_TOP_LEFT_OUTSIDE') document.getElementById('IS_TOP_LEFT_OUTSIDE').className = 'pq_checked';		
			if(pos == 'IS_TOP_RIGHT_INSIDE') document.getElementById('IS_TOP_RIGHT_INSIDE').className = 'pq_checked';		
			if(pos == 'IS_TOP_RIGHT_OUTSIDE') document.getElementById('IS_TOP_RIGHT_OUTSIDE').className = 'pq_checked';		
			if(pos == 'IS_CENTER') document.getElementById('IS_CENTER').className = 'pq_checked';		
			if(pos == 'IS_BOTTOM_LEFT_INSIDE') document.getElementById('IS_BOTTOM_LEFT_INSIDE').className = 'pq_checked';		
			if(pos == 'IS_BOTTOM_CENTER_INSIDE') document.getElementById('IS_BOTTOM_CENTER_INSIDE').className = 'pq_checked';		
			if(pos == 'IS_BOTTOM_RIGHT_INSIDE') document.getElementById('IS_BOTTOM_RIGHT_INSIDE').className = 'pq_checked';					
		}
	}
	/****************************END TOOLS LOADER JS****************************/
	function checkNameFieldsByProvider(id){	
		if(id){
			var provider = 'mailchimp';
			try{if(document.getElementById(id+'_provider_mailchimp').checked) provider = 'mailchimp';}catch(err){}
			try{if(document.getElementById(id+'_provider_getresponse').checked) provider = 'getresponse';}catch(err){}
			try{if(document.getElementById(id+'_provider_aweber').checked) provider = 'aweber';}catch(err){}
			try{if(document.getElementById(id+'_provider_madmini').checked) provider = 'madmini';}catch(err){}
			try{if(document.getElementById(id+'_provider_acampaign').checked) provider = 'acampaign';}catch(err){}
			try{if(document.getElementById(id+'_provider_klickmail').checked) provider = 'klickmail';}catch(err){}
			
			try{
				if(provider == 'aweber' || provider == 'getresponse' || provider == 'acampaign' || provider == 'klickmail'){
					document.getElementById(id+'_text_name').disabled = false;
				}else{
					document.getElementById(id+'_text_name').disabled = true;
				}
			}catch(err){}
		}		
	}
	</script>
	
	<!-- Choose PRO Popup -->
	
	
	<!-- Preview Theme Popup -->
			<div id="PQThemeSelectPreviewPopup" style="display:none;">
			<input type="hidden" id="PQThemeSelectCurrentToolID" value="">
			<input type="hidden" id="PQThemeSelectCurrentID" value="">
				<p id="Popup_ThemePreview_Title"></p>
				<div class="pq_clear"></div>
				<div style="display: block; position: absolute; top: 0px; bottom: 0; left: 0; right: 0; overflow-y: auto;">
				<div class="pq_preview_full" id="Popup_ThemePreview_DIV">
					<p><?php echo $this->_dictionary[toolsStatus][free];?></p>
					<p class="pq_premium_theme"><?php echo $this->_dictionary[toolsStatus][premium];?></p>
					<a class="pq_question" href="">?</a>
					<span id="Popup_ThemePreview_Prev" onclick="previewThemePrev();"></span>
					<div style="position: absolute; top: 80px; bottom: 120px; left: 0; right: 0; margin: 0;"><img id="Popup_ThemePreview_big_src" src="" /></div>
					<span id="Popup_ThemePreview_Next" onclick="previewThemeNext();"></span>
				<div class="pq_clear"></div>
				<div><p id="Popup_ThemePreview_Description"></p>
					<!--"pq_pro_tool" for input if you need-->
					<input class="" type="button" value="<?php echo $this->_dictionary[choseTheme][choose_button];?>" onclick="document.getElementById('PQThemeSelectPreviewPopup').style.display='none';selectStep(document.getElementById('PQThemeSelectCurrentToolID').value, 2);setThemeSettings(document.getElementById('PQThemeSelectCurrentToolID').value,document.getElementById('PQThemeSelectCurrentID').value);enablePreviewIframe(document.getElementById('PQThemeSelectCurrentToolID').value);">
					<a class="pq_question" href="">?</a>
					<a class="pq_already" onclick="document.getElementById('PQThemeSelectPreviewPopup').style.display='none';selectStep(document.getElementById('PQThemeSelectCurrentToolID').value, 2);enablePreviewIframe(document.getElementById('PQThemeSelectCurrentToolID').value);"><?php echo $this->_dictionary[navigation][use_already_saved];?></a>
				</div>
				<a class="pq_close" onclick="document.getElementById('PQThemeSelectPreviewPopup').style.display='none';"><?php echo $this->_dictionary[action][close];?></a>
				</div>
				</div>
			</div>
			
			<!-- Image Sharer Position -->
			<div id="PQISPositionBlock" style="display:none">
				<div class="pq_nav" >
				<div class="pq_status_menu">
					<span onclick="location.href='<?php echo $this->getSettingsPageUrl();?>';"><?php echo $this->_dictionary[navigation][index];?></span>
					<span class="active" ><?php echo $this->_dictionary[navigation][location];?></span>
					<input type="button" disabled style="line-height: 14px; " id="PQISPositionBlockSkipButton"  name="" onclick="nextStepFromThemeIS()" value="<?php echo $this->_dictionary[navigation][next_step];?>">
				</div>
				</div>
				<h1><?php echo $this->_dictionary[positionsBlock][title];?></h1>
				<p><?php echo $this->_dictionary[positionsBlock][description];?></p>
				<div class="pq_location">
				<div style="overflow: hidden; display: inline-block; float: none; position: relative; max-width: 100%; box-shadow: 0 10px 15px 0px rgba(0, 0, 0, 0.12);">
					<img src="<?php echo plugins_url('i/tools_map.png', __FILE__);?>"  style="max-width: 100%;" />
				<div class="pq_clear"></div>
				<div style="background: url(<?php echo plugins_url( PQ_ES_ADMIN_IMG_PATH.'/image.png', __FILE__ );?>);     position: absolute;     top: 39px;     bottom: 0;     left: 0;     right: 0;     width: 589px;     height: 293px;     margin: auto; max-width: 75%;     max-height: 80%;     background-size: cover;     background-position: 50%;box-shadow: 50px 0 0 0 white, -50px 0 0 0 white;">
					<label class="pq_is_top_left_inside" ><input type="checkbox" id="IS_TOP_LEFT_INSIDE" name="position"  onclick="setISPosition(this)">TOP LEFT INSIDE<div></div></label>					
					<label class="pq_is_top_left_outside" ><input type="checkbox" id="IS_TOP_LEFT_OUTSIDE" name="position"  onclick="setISPosition(this)">TOP LEFT OUTSIDE<div></div></label>
					<label class="pq_is_top_right_inside" ><input type="checkbox" id="IS_TOP_RIGHT_INSIDE" name="position"  onclick="setISPosition(this)">TOP RIGHT INSIDE<div></div></label>					
					<label class="pq_is_top_right_outside" ><input type="checkbox" id="IS_TOP_RIGHT_OUTSIDE" name="position"  onclick="setISPosition(this)">TOP RIGHT OUTSIDE<div></div></label>
					<label class="pq_is_center" ><input type="checkbox" id="IS_CENTER" name="position"  onclick="setISPosition(this)">CENTER<div></div></label>
					<label class="pq_is_bottom_left_inside" ><input type="checkbox" id="IS_BOTTOM_LEFT_INSIDE" name="position"  onclick="setISPosition(this)">BOTTOM LEFT INSIDE<div></div></label>
					<label class="pq_is_bottom_center_inside" ><input type="checkbox" id="IS_BOTTOM_CENTER_INSIDE" name="position"  onclick="setISPosition(this)">BOTTOM CENTER INSIDE<div></div></label>
					<label class="pq_is_bottom_right_inside" ><input type="checkbox" id="IS_BOTTOM_RIGHT_INSIDE" name="position"  onclick="setISPosition(this)">BOTTOM RIGHT INSIDE<div></div></label>
				</div>
				</div>
				</div>
			</div>
			
			<!-- Position -->
			<div id="PQPositionBlock" style="display:none">
				<div class="pq_nav" >
				<div class="pq_status_menu">
					<span onclick="location.href='<?php echo $this->getSettingsPageUrl();?>';"><?php echo $this->_dictionary[navigation][index];?></span>
					<span class="active" ><?php echo $this->_dictionary[navigation][location];?></span>
					<input type="button" disabled style="line-height: 14px; " id="PQPositionBlockSkipButton"  name="" onclick="nextStepFromTheme()" value="<?php echo $this->_dictionary[navigation][next_step];?>">
				</div>
				</div>
				<h1><?php echo $this->_dictionary[positionsBlock][title];?></h1>
				<p><?php echo $this->_dictionary[positionsBlock][description];?></p>
				<div class="pq_location">
				<div style="overflow: hidden; display: inline-block; float: none; position: relative; max-width: 100%; box-shadow: 0 10px 15px 0px rgba(0, 0, 0, 0.12);"><img src="<?php echo plugins_url('i/tools_map.png', __FILE__);?>"  style="max-width: 100%;" />
				<div class="pq_clear"></div>
					<input type="hidden" id="CURRENT_TOOL" name="current_tool" value="">
					<label class="bar_top" ><input type="checkbox" id="BAR_TOP" name="position"  onclick="setPosition(this)">BAR TOP<div></div></label>
					<label class="bar_bottom" ><input type="checkbox" id="BAR_BOTTOM" name="position"  onclick="setPosition(this)">BAR BOTTOM<div></div></label>
					<label class="side_left_top" ><input type="checkbox" id="SIDE_LEFT_TOP" name="position"  onclick="setPosition(this)">SIDE_LEFT_TOP<div></div></label>
					<label class="side_left_middle" ><input type="checkbox" id="SIDE_LEFT_MIDDLE" name="position"  onclick="setPosition(this)">SIDE_LEFT_MIDDLE<div></div></label>
					<label class="side_left_bottom" ><input type="checkbox" id="SIDE_LEFT_BOTTOM" name="position"  onclick="setPosition(this)">SIDE_LEFT_BOTTOM<div></div></label>
					<label class="side_right_top" ><input type="checkbox" id="SIDE_RIGHT_TOP" name="position"  onclick="setPosition(this)">SIDE_RIGHT_TOP<div></div></label>
					<label class="side_right_middle" ><input type="checkbox" id="SIDE_RIGHT_MIDDLE" name="position"  onclick="setPosition(this)">SIDE_RIGHT_MIDDLE<div></div></label>
					<label class="side_right_bottom" ><input type="checkbox" id="SIDE_RIGHT_BOTTOM" name="position"  onclick="setPosition(this)">SIDE_RIGHT_BOTTOM<div></div></label>
					<label class="popup_center" ><input type="checkbox" id="CENTER" name="position"  onclick="setPosition(this)">CENTER<div></div></label>
					<label class="floating_left_top" ><input type="checkbox" id="FLOATING_LEFT_TOP" name="position" onclick="setPosition(this)">FLOATING_LEFT_TOP<div></div></label>
					<label class="floating_left_bottom" ><input type="checkbox" id="FLOATING_LEFT_BOTTOM" name="position" onclick="setPosition(this)">FLOATING_LEFT_BOTTOM<div></div></label>
					<label class="floating_right_top" ><input type="checkbox" disabled id="FLOATING_RIGHT_TOP" name="position"  onclick="setPosition(this)">FLOATING_RIGHT_TOP<div></div></label>
					<label class="floating_right_bottom" ><input type="checkbox" checked="checked" disabled id="FLOATING_RIGHT_BOTTOM" name="position" onclick="setPosition(this)">FLOATING_RIGHT_BOTTOM<div></div></label>
				</div>
				</div>
			</div>

	<?php
		if($_GET[s_t] == 'emailListBuilderPopup' || trim($providerRedirectByError)){
				?>
				<!-- ********* Email List Builder Div -->
			<div id="PQEmailListBuilderPopup" style="display:none;" class="f_wrapper pq_li3">
			<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
			<input type="hidden" name="action" value="emailListBuilderPopupSave">
			<input type="hidden" id="PQEmailListBuilderPopup_position" name="emailListBuilderPopup[position]" value="<?php echo stripslashes($this->_options[emailListBuilderPopup][position]);?>">
			<input type="hidden" id="emailListBuilderPopup_whitelabel" value="">
				<div class="pq_nav">						
						<a class="pq_name_status" id="PQEmailListBuilderPopup_pro_status" href="javascript:void(0)" onclick="openChooseProPopup('PQEmailListBuilderPopup')">							
							<img src="<?php echo plugins_url('i/ico_collect_email_popup.png', __FILE__);?>"> 
							<span><?php echo $this->_dictionary[toolName][email_list_builder_popup];?></span>
							<span id="PQEmailListBuilderPopup_pro_question">?</span>
							<p >&nbsp;</p>
							<p id="PQEmailListBuilderPopup_pro_text" class=""></p>							
						</a>
						<div class="pq_status_menu">
							<span onclick="location.href='<?php echo $this->getSettingsPageUrl();?>';"><?php echo $this->_dictionary[navigation][index];?></span>
							
							<span  onclick="emailListBuilderPopupSettings('<?php echo stripslashes($this->_options[emailListBuilderPopup][position]);?>');"><?php echo $this->_dictionary[navigation][location];?></span>
							<span id="PQEmailListBuilderPopup_nav_step_1" onclick="selectStep('PQEmailListBuilderPopup', 1);"><?php echo $this->_dictionary[navigation][themes];?></span>
							<span id="PQEmailListBuilderPopup_nav_step_2" onclick="selectStep('PQEmailListBuilderPopup', 2);enablePreviewIframe('PQEmailListBuilderPopup');selectForm('PQEmailListBuilderPopup', 'main');document.getElementById('PQThemeSelectPreviewPopup').style.display = 'none';"><?php echo $this->_dictionary[navigation][design];?></span>
							<span id="PQEmailListBuilderPopup_nav_step_3" onclick="selectStep('PQEmailListBuilderPopup', 3);document.getElementById('PQThemeSelectPreviewPopup').style.display = 'none';"><?php echo $this->_dictionary[navigation][display];?></span>
							<span id="PQEmailListBuilderPopup_nav_step_4" onclick="selectStep('PQEmailListBuilderPopup', 4);document.getElementById('PQThemeSelectPreviewPopup').style.display = 'none';"><?php echo $this->_dictionary[navigation][settings];?></span>
							<input type="button" id="PQEmailListBuilderPopup_next_button" style="display:none" name="" value="<?php echo $this->_dictionary[navigation][next_step];?>">
							<input type="submit" id="PQEmailListBuilderPopup_submit_button" style="display:none" name="" value="<?php echo $this->_dictionary[navigation][save_button];?>">
						</div>
				</div>
				<div id="PQEmailListBuilderPopup_step_1" style="display:none;" class="pq_themes">
				<?php
					if(!$this->_options[emailListBuilderPopup][theme]) $this->_options[emailListBuilderPopup][theme] = $this->_default_themes[emailListBuilderPopup][theme];
				?>
				<input type="hidden" id="PQEmailListBuilderPopup_Current_Theme" name="emailListBuilderPopup[theme]" value="<?php echo stripslashes($this->_options[emailListBuilderPopup][theme]);?>">
				<input type="hidden" id="PQEmailListBuilderPopup_CurrentThemeForPreview" value="">
				<div class="pq_navigation">
					
					<input type="button" value="<?php echo $this->_dictionary[navigation][skip_step];?>" onclick="selectStep('PQEmailListBuilderPopup', 2);enablePreviewIframe('PQEmailListBuilderPopup');selectForm('PQEmailListBuilderPopup', 'main');document.getElementById('PQThemeSelectPreviewPopup').style.display = 'none';">
				</div>
						<h1><?php echo $this->_dictionary[choseTheme][title];?></h1>
						<p><?php echo $this->_dictionary[choseTheme][description];?> </p>
						<div class="pq_grid">
						<?php foreach((array)$this->_themes[emailListBuilderPopup] as $k => $v){
								$isActive = '';
								if($k == stripslashes($this->_options[emailListBuilderPopup][theme])){
									$isActive = 'pq_active';
								}
						?>
							<div <?php echo 'class="'.$isActive.'"';?> onclick="
								<?php
											echo "previewThemeSelect('PQEmailListBuilderPopup','emailListBuilderPopup','".$k."', 'current');";
									?>
							"/>
								<img src="<?php if((float)$v[price] > 0) echo plugins_url('i/pro.png', __FILE__); else echo plugins_url('i/free.png', __FILE__);?>" />
								<img  src="<?php echo $v[preview_image_small];?>" />
								<span class="pq_preview_button"><?php echo $this->_dictionary[choseTheme][preview_button];?></span>
									<span class="pq_customize_button"><?php echo $this->_dictionary[choseTheme][chosen_button];?></span>
							</div>
						<?php }?>
						</div>
					<div class="pq_close"></div>
				</div>
				<div id="PQEmailListBuilderPopup_step_2" style="display:block;" class="design_settings">
					<a id="PQEmailListBuilderPopup_youusepro" class="pq_pro_theme" href="javascript:void(0)" onclick="openChooseProPopup('PQEmailListBuilderPopup')"><?php echo $this->_dictionary[navigation][pro_theme_selected];?></a>
					<div class="pq_thank_select">
						<span id="PQEmailListBuilderPopup_DesignToolSwitch_main" onclick="selectForm('PQEmailListBuilderPopup', 'main', ['desktop', 'mobile']);addClassToSwitcher('PQEmailListBuilderPopup', 'main', 'pq_active',['main', 'thank']);setToolFirstDesignStep('emailListBuilderPopup','PQEmailListBuilderPopup','');emailListBuilderPopupPreview();" class="pq_active"><?php echo $this->_dictionary[navigation][your_tool];?></span>
						<span id="PQEmailListBuilderPopup_DesignToolSwitch_thank" onclick="selectForm('PQEmailListBuilderPopup', 'thank', ['desktop', 'mobile']);addClassToSwitcher('PQEmailListBuilderPopup', 'thank', 'pq_active',['main', 'thank']);setToolFirstDesignStep('emailListBuilderPopup','PQEmailListBuilderPopup','thank');emailListBuilderPopupthankPreview();"><?php echo $this->_dictionary[navigation][success_tool];?></span>
						<span class="pq_disabled"><?php echo $this->_dictionary[navigation][email];?></span>
					</div>
					
					<!-- Preview PQEmailListBuilderPopup Iframe -->
			
					<div class="frame" id="PQEmailListBuilderPopup_PQIframePreviewBlock" style="display:none">
						<iframe src="about:blank" id="PQEmailListBuilderPopup_PQPreviewID" width="100%" height="100%" class="pq_if"></iframe>
					</div>
					<div id="PQEmailListBuilderPopup_PQForMobilePaper" class="pq_paper"></div>
					<!-- End Preview Iframe -->
					<div id="PQEmailListBuilderPopup_form_main" style="display:block;" class="pq_design">
						<div id="PQEmailListBuilderPopup_form_main_desktop" style="display:block;">
						<div class="pq_sett_all">
							<div class="pq_desktop_mobile">
								<div id="PQEmailListBuilderPopup_main_DesignToolSwitch_desktop" onclick="addClassToSwitcher('PQEmailListBuilderPopup_main', 'desktop', 'pq_active',['desktop', 'mobile']);emailListBuilderPopupPreview();" class="pq_active" ><img src="<?php echo plugins_url('i/ico/desktop.png', __FILE__);?>"><?php echo $this->_dictionary[navigation][desktop];?></div>
								<div id="PQEmailListBuilderPopup_main_DesignToolSwitch_mobile" onclick="addClassToSwitcher('PQEmailListBuilderPopup_main', 'mobile', 'pq_active',['desktop', 'mobile']);emailListBuilderPopupPreview();"><img src="<?php echo plugins_url('i/ico/mobile.png', __FILE__);?>"><?php echo $this->_dictionary[navigation][mobile];?></div>
							</div>
						
							<?php
								echo $this->getFormCodeForTool(
								array(
										'general'=>array(											
											'text'=>$this->_dictionary[design_block_name][general],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_popup],
											'img'=>plugins_url('i/ico/setting_general.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#general_block',
											'elements'=>array(											
												'size_window',
												'popup_form',
												'background_color',
												'background_opacity',
												'border_type',
												'border_depth', 
												'border_color',
												'animation',
												'overlay_color',
												'overlay_opacity'
											)
										),
										'heading'=>array(											
											'text'=>$this->_dictionary[design_block_name][heading],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_popup],
											'img'=>plugins_url('i/ico/setting_title.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#heading_block',
											'elements'=>array(
												'title',
												'head_font',
												'head_font_size',
												'head_color'												
											)
										),										
										'text_block'=>array(
											'text'=>$this->_dictionary[design_block_name][text_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_popup],
											'img'=>plugins_url('i/ico/setting_subtitle.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#text_block',
											'elements'=>array(
												'sub_title',
												'text_font',
												'font_size',
												'text_color'												
											)
										),
										'promo_block'=>array(
											'text'=>$this->_dictionary[design_block_name][promo_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_popup],
											'img'=>plugins_url('i/ico/setting_promo.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#promo_block',
											'elements'=>array(																																				
												'tblock_text',
												'background_text_block',
												'text_block_padding',
												'tblock_text_font',
												'tblock_text_font_size',
												'tblock_text_font_color'
											)
										),
										'form_block'=>array(
											'text'=>$this->_dictionary[design_block_name][form_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_popup],
											'img'=>plugins_url('i/ico/setting_form.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#form_block',
											'elements'=>array(
												'background_form_block',
												'form_block_padding',
												'input_type',
												'enter_email_text',  
												'enter_name_text'																											
											)
										),
										'button_block'=>array(
											'text'=>$this->_dictionary[design_block_name][button_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_popup],
											'img'=>plugins_url('i/ico/setting_button.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#button_block',
											'elements'=>array(
												'button_form',
												'button_type',
												'background_button_block',
												'button_block_padding',
												'button_text',
												'button_font',
												'button_font_size',
												'button_text_color',
												'button_color'																												
											)
										),
										'close_block'=>array(
											'text'=>$this->_dictionary[design_block_name][close_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_popup],
											'img'=>plugins_url('i/ico/setting_close.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#close_block',
											'elements'=>array(
												'close_icon_type', 
												'close_text',
												'close_text_font',
												'close_icon_color',
												'close_icon_animation'																											
											)
										),										
										'pro_block'=>array(
											'text'=>$this->_dictionary[design_block_name][pro_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_popup],
											'img'=>plugins_url('i/ico/setting_pro.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#pro_block',
											'elements'=>array(
												'header_image_src',
												'header_img_type',												
												'background_image_src',												
												'overlay_image_src',
												'showup_animation'
											)
										)
									), 'emailListBuilderPopup', $this->_options[emailListBuilderPopup]);		
							?>
						</div>
						<!-- MOBILE -->
						<div id="PQEmailListBuilderPopup_form_main_mobile" style="display:none;"></div>
					</div>
					</div>
					<div class="pq_design_buttons">
						<input type="button" value="<?php echo $this->_dictionary[navigation][next];?>" onclick="selectStep('PQEmailListBuilderPopup', 3);">
						<a onclick="clearLastChanges('emailListBuilderPopup');" <?php if(trim($this->_options[emailListBuilderPopup][position])=='') echo 'style="display:none;"';?>><?php echo $this->_dictionary[navigation][clear_changes];?></a>
					</div>
					<?php
						echo $this->generateThankHTML('emailListBuilderPopup', 'PQEmailListBuilderPopup');
					?>
					
				</div>
				<div id="PQEmailListBuilderPopup_step_3" style="display:none;" class="pq_rules">
					<div class="pq_navigation">
						
						<p><?php echo $this->_dictionary[displayRules][description];?></p>
						<input type="button" value="<?php echo $this->_dictionary[navigation][next_step];?>" onclick="selectStep('PQEmailListBuilderPopup', 4);">
					</div>
					<h1><?php echo $this->_dictionary[displayRules][title];?> <a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#display_rules" target="_settings_info">?</a></h1>
					<p></p>
					<?php
						echo $this->getEventHandlerBlock('emailListBuilderPopup', array('delay', 'exit','scrolling'),  $this->_options[emailListBuilderPopup]);
					?>
					<?php
						echo $this->getPageOptions('emailListBuilderPopup', $this->_options[emailListBuilderPopup]);
					?>
					<?php
						echo $this->getGadgetRules('emailListBuilderPopup', $this->_options[emailListBuilderPopup]);
					?>
					<div style="visibility:hidden">
					<input type="button" value="<?php echo $this->_dictionary[navigation][next_step];?>" onclick="selectStep('PQEmailListBuilderPopup', 4);">
					<input type="submit" name="" value="<?php echo $this->_dictionary[navigation][save_button];?>">
					</div>
				</div>
				<div id="PQEmailListBuilderPopup_step_4" style="display:none;" class="provider_settings">
					<div class="pq_navigation">
						
					</div>
					<h1><?php echo $this->_dictionary[providerSettings][title];?></h1>
					<p><?php echo $this->_dictionary[providerSettings][description];?></p>
					<?php
						echo $this->getProviderBlock('emailListBuilderPopup', $this->_options[emailListBuilderPopup]);
					?>
					<?php
						echo $this->getLockBlock('emailListBuilderPopup', $this->_options[emailListBuilderPopup]);
					?>
					<div style="visibility:hidden">
					<input type="submit" value="<?php echo $this->_dictionary[navigation][save_button];?>">
					</div>
				</div>
			</form>
			</div>
	<!-- ********* End Email List Builder Div -->
				<script>
					<?php
						if((int)$_GET[step]){
							echo '
								disableAllDialog();	
								startTool(\'PQEmailListBuilderPopup\');
								selectStep(\'PQEmailListBuilderPopup\', '.(int)$_GET[step].');
							';
						}else{
							echo '
								emailListBuilderPopupSettings(\''.stripslashes(urldecode($_GET[pos])).'\');
							';
						}
					?>
					checkTheme('emailListBuilderPopup', 'PQEmailListBuilderPopup');
					document.getElementById('PQLoader').style.display='none';
				</script>
				<?php
			}
			
			
			
			
			if($_GET[s_t] == 'emailListBuilderBar' || trim($providerRedirectByError)){
				?>
				<!-- ********* Email List Builder Bar Div -->
			<div id="PQEmailListBuilderBar" style="display:none;" class="f_wrapper pq_li3">
			<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
			<input type="hidden" name="action" value="emailListBuilderBarSave">
			<input type="hidden" id="PQEmailListBuilderBar_position" name="emailListBuilderBar[position]" value="<?php echo stripslashes($this->_options[emailListBuilderBar][position]);?>">
			<input type="hidden" id="emailListBuilderBar_whitelabel" value="">
				<div class="pq_nav">						
						<a class="pq_name_status" id="PQEmailListBuilderBar_pro_status" href="javascript:void(0)" onclick="openChooseProPopup('PQEmailListBuilderBar')">							
							<img src="<?php echo plugins_url('i/ico_collect_email_bar.png', __FILE__);?>"> 
							<span><?php echo $this->_dictionary[toolName][email_list_builder_bar];?></span>
							<span id="PQEmailListBuilderBar_pro_question">?</span>
							<p >&nbsp;</p>
							<p id="PQEmailListBuilderBar_pro_text" class=""></p>							
						</a>
						<div class="pq_status_menu">
							<span onclick="location.href='<?php echo $this->getSettingsPageUrl();?>';"><?php echo $this->_dictionary[navigation][index];?></span>
							
							<span  onclick="emailListBuilderBarSettings('<?php echo stripslashes($this->_options[emailListBuilderBar][position]);?>');"><?php echo $this->_dictionary[navigation][location];?></span>
							<span id="PQEmailListBuilderBar_nav_step_1" onclick="selectStep('PQEmailListBuilderBar', 1);"><?php echo $this->_dictionary[navigation][themes];?></span>
							<span id="PQEmailListBuilderBar_nav_step_2" onclick="selectStep('PQEmailListBuilderBar', 2);enablePreviewIframe('PQEmailListBuilderBar');selectForm('PQEmailListBuilderBar', 'main');document.getElementById('PQThemeSelectPreviewPopup').style.display = 'none';"><?php echo $this->_dictionary[navigation][design];?></span>
							<span id="PQEmailListBuilderBar_nav_step_3" onclick="selectStep('PQEmailListBuilderBar', 3);document.getElementById('PQThemeSelectPreviewPopup').style.display = 'none';"><?php echo $this->_dictionary[navigation][display];?></span>
							<span id="PQEmailListBuilderBar_nav_step_4" onclick="selectStep('PQEmailListBuilderBar', 4);document.getElementById('PQThemeSelectPreviewPopup').style.display = 'none';"><?php echo $this->_dictionary[navigation][settings];?></span>
							<input type="button" id="PQEmailListBuilderBar_next_button" style="display:none" name="" value="<?php echo $this->_dictionary[navigation][next_step];?>">
							<input type="submit" id="PQEmailListBuilderBar_submit_button" style="display:none" name="" value="<?php echo $this->_dictionary[navigation][save_button];?>">
						</div>
				</div>
				<div id="PQEmailListBuilderBar_step_1" style="display:none;" class="pq_themes">
				<?php
					if(!$this->_options[emailListBuilderBar][theme]) $this->_options[emailListBuilderBar][theme] = $this->_default_themes[emailListBuilderBar][theme];
				?>
				<input type="hidden" id="PQEmailListBuilderBar_Current_Theme" name="emailListBuilderBar[theme]" value="<?php echo stripslashes($this->_options[emailListBuilderBar][theme]);?>">
				<input type="hidden" id="PQEmailListBuilderBar_CurrentThemeForPreview" value="">
				<div class="pq_navigation">
					
					<input type="button" value="<?php echo $this->_dictionary[navigation][skip_step];?>" onclick="selectStep('PQEmailListBuilderBar', 2);enablePreviewIframe('PQEmailListBuilderBar');selectForm('PQEmailListBuilderBar', 'main');document.getElementById('PQThemeSelectPreviewPopup').style.display = 'none';">
				</div>
						<h1><?php echo $this->_dictionary[choseTheme][title];?></h1>
						<p><?php echo $this->_dictionary[choseTheme][description];?> </p>
						<div class="pq_grid">
						<?php foreach((array)$this->_themes[emailListBuilderBar] as $k => $v){
								$isActive = '';
								if($k == stripslashes($this->_options[emailListBuilderBar][theme])){
									$isActive = 'pq_active';
								}
						?>
							<div <?php echo 'class="'.$isActive.'"';?> onclick="
								<?php
											echo "previewThemeSelect('PQEmailListBuilderBar','emailListBuilderBar','".$k."', 'current');";
									?>
							"/>
								<img src="<?php if((float)$v[price] > 0) echo plugins_url('i/pro.png', __FILE__); else echo plugins_url('i/free.png', __FILE__);?>" />
								<img  src="<?php echo $v[preview_image_small];?>" />
								<span class="pq_preview_button"><?php echo $this->_dictionary[choseTheme][preview_button];?></span>
									<span class="pq_customize_button"><?php echo $this->_dictionary[choseTheme][chosen_button];?></span>
							</div>
						<?php }?>
						</div>
					<div class="pq_close"></div>
				</div>
				<div id="PQEmailListBuilderBar_step_2" style="display:block;" class="design_settings">
					<a id="PQEmailListBuilderBar_youusepro" class="pq_pro_theme" href="javascript:void(0)" onclick="openChooseProPopup('PQEmailListBuilderBar')"><?php echo $this->_dictionary[navigation][pro_theme_selected];?></a>
					<div class="pq_thank_select">
						<span id="PQEmailListBuilderBar_DesignToolSwitch_main" onclick="selectForm('PQEmailListBuilderBar', 'main', ['desktop', 'mobile']);addClassToSwitcher('PQEmailListBuilderBar', 'main', 'pq_active',['main', 'thank']);setToolFirstDesignStep('emailListBuilderBar','PQEmailListBuilderBar','');emailListBuilderBarPreview();" class="pq_active"><?php echo $this->_dictionary[navigation][your_tool];?></span>
						<span id="PQEmailListBuilderBar_DesignToolSwitch_thank" onclick="selectForm('PQEmailListBuilderBar', 'thank', ['desktop', 'mobile']);addClassToSwitcher('PQEmailListBuilderBar', 'thank', 'pq_active',['main', 'thank']);setToolFirstDesignStep('emailListBuilderBar','PQEmailListBuilderBar','thank');emailListBuilderBarthankPreview();"><?php echo $this->_dictionary[navigation][success_tool];?></span>
						<span class="pq_disabled"><?php echo $this->_dictionary[navigation][email];?></span>
					</div>
					<!-- Preview PQSharingBar Iframe -->
			
						<div class="frame" id="PQEmailListBuilderBar_PQIframePreviewBlock" style="display:none">
							<iframe src="about:blank" id="PQEmailListBuilderBar_PQPreviewID" width="100%" height="100%" class="pq_if"></iframe>
						</div>
						<div id="PQEmailListBuilderBar_PQForMobilePaper" class="pq_paper"></div>
						<!-- End Preview Iframe -->
					<div id="PQEmailListBuilderBar_form_main" style="display:block;" class="pq_design">
					<div class="pq_sett_all">
						<div class="pq_desktop_mobile">
							<div id="PQEmailListBuilderBar_main_DesignToolSwitch_desktop" onclick="selectSubForm('PQEmailListBuilderBar', 'main', 'desktop');enableFirstStepForMobileDesktop('emailListBuilderBar', 'general');addClassToSwitcher('PQEmailListBuilderBar_main', 'desktop', 'pq_active',['desktop', 'mobile']);emailListBuilderBarPreview();" class="pq_active" ><?php echo $this->_dictionary[navigation][desktop];?><img src="<?php echo plugins_url('i/ico/desktop.png', __FILE__);?>"></div>
							<div id="PQEmailListBuilderBar_main_DesignToolSwitch_mobile" onclick="selectSubForm('PQEmailListBuilderBar', 'main', 'mobile');enableFirstStepForMobileDesktop('emailListBuilderBar', 'mobile_general');addClassToSwitcher('PQEmailListBuilderBar_main', 'mobile', 'pq_active',['desktop', 'mobile']);emailListBuilderBarPreview();"><?php echo $this->_dictionary[navigation][mobile];?><img src="<?php echo plugins_url('i/ico/mobile.png', __FILE__);?>"></div>
						</div>
										
						<!-- DESKTOP -->
						<div id="PQEmailListBuilderBar_form_main_desktop" style="display:block;">
							<?php
								echo $this->getFormCodeForTool(
								array(
										'general'=>array(											
											'text'=>$this->_dictionary[design_block_name][general],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_bar],
											'img'=>plugins_url('i/ico/setting_general.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#general_block',
											'elements'=>array(											
												'background_color',												
												'border_type',
												'border_depth', 
												'border_color',
												'animation'
											)
										),
										'heading'=>array(											
											'text'=>$this->_dictionary[design_block_name][heading],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_bar],
											'img'=>plugins_url('i/ico/setting_title.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#heading_block',
											'elements'=>array(
												'title',
												'head_font',
												'head_font_size',
												'head_color'												
											)
										),																				
										'form_block'=>array(
											'text'=>$this->_dictionary[design_block_name][form_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_bar],
											'img'=>plugins_url('i/ico/setting_form.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#form_block',
											'elements'=>array(
												'background_form_block',
												'form_block_padding',
												'input_type',
												'enter_email_text',  
												'enter_name_text'																											
											)
										),
										'button_block'=>array(
											'text'=>$this->_dictionary[design_block_name][button_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_bar],
											'img'=>plugins_url('i/ico/setting_button.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#button_block',
											'elements'=>array(
												'button_form',
												'button_type',
												'background_button_block',
												'button_block_padding',
												'button_text',
												'button_font',
												'button_font_size',
												'button_text_color',
												'button_color'																												
											)
										),
										'close_block'=>array(
											'text'=>$this->_dictionary[design_block_name][close_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_bar],
											'img'=>plugins_url('i/ico/setting_close.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#close_block',
											'elements'=>array(
												'close_icon_type', 
												'close_text',
												'close_text_font',
												'close_icon_color',
												'close_icon_animation'																											
											)
										),
										'pro_block'=>array(
											'text'=>$this->_dictionary[design_block_name][pro_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_bar],
											'img'=>plugins_url('i/ico/setting_pro.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#pro_block',
											'elements'=>array(
												'header_image_src',
												'header_img_type',												
												'showup_animation'
											)
										)
									), 'emailListBuilderBar', $this->_options[emailListBuilderBar]);								
							?>
							
						</div>
						<!-- MOBILE -->
						<div id="PQEmailListBuilderBar_form_main_mobile" style="display:none;">
							<?php
								echo $this->getFormCodeForTool(
									array(
											'mobile_general'=>array(											
												'text'=>$this->_dictionary[design_block_name][general],
												'toolName'=>$this->_dictionary[toolName][email_list_builder_bar],
												'img'=>plugins_url('i/ico/setting_general.png', __FILE__),
												'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#m_general_block',
												'elements'=>array(											
													'mobile_position'
												)
											),
											'mobile_text'=>array(											
												'text'=>$this->_dictionary[design_block_name][text_block],
												'toolName'=>$this->_dictionary[toolName][email_list_builder_bar],
												'img'=>plugins_url('i/ico/setting_subtitle.png', __FILE__),
												'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#m_text_block',
												'elements'=>array(											
													'mobile_title',
													'mblock_text_font',
													'mblock_text_font_color',
													'mblock_text_font_size'
												)
											)
										)
									, 'emailListBuilderBar', $this->_options[emailListBuilderBar],'','', 1);
							?>
						</div>
					</div>
					</div>
					<div class="pq_design_buttons">
						<input type="button" value="<?php echo $this->_dictionary[navigation][next];?>" onclick="selectStep('PQEmailListBuilderBar', 3);">
						<a onclick="clearLastChanges('emailListBuilderBar');" <?php if(trim($this->_options[emailListBuilderBar][position])=='') echo 'style="display:none;"';?>><?php echo $this->_dictionary[navigation][clear_changes];?></a>
					</div>
					<?php
						echo $this->generateThankHTML('emailListBuilderBar', 'PQEmailListBuilderBar');
					?>
					
				</div>
				<div id="PQEmailListBuilderBar_step_3" style="display:none;" class="pq_rules">
					<div class="pq_navigation">
						
						<p><?php echo $this->_dictionary[displayRules][description];?></p>
						<input type="button" value="<?php echo $this->_dictionary[navigation][next_step];?>" onclick="selectStep('PQEmailListBuilderBar', 4);">
					</div>
					<h1><?php echo $this->_dictionary[displayRules][title];?> <a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#display_rules" target="_settings_info">?</a></h1>
					<p></p>
					<?php
						echo $this->getEventHandlerBlock('emailListBuilderBar', array('delay', 'scrolling'),  $this->_options[emailListBuilderBar]);
					?>
					<?php
						echo $this->getPageOptions('emailListBuilderBar', $this->_options[emailListBuilderBar]);
					?>
					<?php
						echo $this->getGadgetRules('emailListBuilderBar', $this->_options[emailListBuilderBar]);
					?>
					<div style="visibility:hidden">
					<input type="button" value="<?php echo $this->_dictionary[navigation][next_step];?>" onclick="selectStep('PQEmailListBuilderBar', 4);">
					<input type="submit" name="" value="<?php echo $this->_dictionary[navigation][save_button];?>">
					</div>
				</div>
				<div id="PQEmailListBuilderBar_step_4" style="display:none;" class="provider_settings">
					<div class="pq_navigation">
						
					</div>
					<h1><?php echo $this->_dictionary[providerSettings][title];?></h1>
					<p><?php echo $this->_dictionary[providerSettings][description];?></p>
					<?php
						echo $this->getProviderBlock('emailListBuilderBar', $this->_options[emailListBuilderBar]);
					?>
					<?php
						echo $this->getLockBlock('emailListBuilderBar', $this->_options[emailListBuilderBar]);
					?>
					<div style="visibility:hidden">
					<input type="submit" value="<?php echo $this->_dictionary[navigation][save_button];?>">
					</div>
				</div>
			</form>
			</div>
	<!-- ********* End Email List Builder Bar Div -->
				<script>
					<?php
						if((int)$_GET[step]){
							echo '
								disableAllDialog();	
								startTool(\'PQEmailListBuilderBar\');
								selectStep(\'PQEmailListBuilderBar\', '.(int)$_GET[step].');
							';
						}else{
							echo '
								emailListBuilderBarSettings(\''.stripslashes(urldecode($_GET[pos])).'\');
							';
						}
					?>
					checkTheme('emailListBuilderBar', 'PQEmailListBuilderBar');
					document.getElementById('PQLoader').style.display='none';
				</script>
				<?php
			}

			if($_GET[s_t] == 'emailListBuilderFloating' || trim($providerRedirectByError)){
				?>
				<!-- ********* Email List Builder Floating Div -->
			<div id="PQEmailListBuilderFloating" style="display:none;" class="f_wrapper pq_li3">
			<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
			<input type="hidden" name="action" value="emailListBuilderFloatingSave">
			<input type="hidden" id="PQEmailListBuilderFloating_position" name="emailListBuilderFloating[position]" value="<?php echo stripslashes($this->_options[emailListBuilderFloating][position]);?>">
			<input type="hidden" id="emailListBuilderFloating_whitelabel" value="">
				<div class="pq_nav">						
						<a class="pq_name_status" id="PQEmailListBuilderFloating_pro_status" href="javascript:void(0)" onclick="openChooseProPopup('PQEmailListBuilderFloating')">							
							<img src="<?php echo plugins_url('i/ico_contact_form_floating.png', __FILE__);?>"> 
							<span><?php echo $this->_dictionary[toolName][email_list_builder_floating];?></span>
							<span id="PQEmailListBuilderFloating_pro_question">?</span>
							<p >&nbsp;</p>
							<p id="PQEmailListBuilderFloating_pro_text" class=""></p>							
						</a>
						<div class="pq_status_menu">
							<span onclick="location.href='<?php echo $this->getSettingsPageUrl();?>';"><?php echo $this->_dictionary[navigation][index];?></span>
							
							<span  onclick="emailListBuilderFloatingSettings('<?php echo stripslashes($this->_options[emailListBuilderFloating][position]);?>');"><?php echo $this->_dictionary[navigation][location];?></span>
							<span id="PQEmailListBuilderFloating_nav_step_1" onclick="selectStep('PQEmailListBuilderFloating', 1);"><?php echo $this->_dictionary[navigation][themes];?></span>
							<span id="PQEmailListBuilderFloating_nav_step_2" onclick="selectStep('PQEmailListBuilderFloating', 2);enablePreviewIframe('PQEmailListBuilderFloating');selectForm('PQEmailListBuilderFloating', 'main');document.getElementById('PQThemeSelectPreviewPopup').style.display = 'none';"><?php echo $this->_dictionary[navigation][design];?></span>
							<span id="PQEmailListBuilderFloating_nav_step_3" onclick="selectStep('PQEmailListBuilderFloating', 3);document.getElementById('PQThemeSelectPreviewPopup').style.display = 'none';"><?php echo $this->_dictionary[navigation][display];?></span>
							<span id="PQEmailListBuilderFloating_nav_step_4" onclick="selectStep('PQEmailListBuilderFloating', 4);document.getElementById('PQThemeSelectPreviewPopup').style.display = 'none';"><?php echo $this->_dictionary[navigation][settings];?></span>
							<input type="button" id="PQEmailListBuilderFloating_next_button" style="display:none" name="" value="<?php echo $this->_dictionary[navigation][next_step];?>">
							<input type="submit" id="PQEmailListBuilderFloating_submit_button" style="display:none" name="" value="<?php echo $this->_dictionary[navigation][save_button];?>">
						</div>
				</div>
				<div id="PQEmailListBuilderFloating_step_1" style="display:none;" class="pq_themes">
				<?php
					if(!$this->_options[emailListBuilderFloating][theme]) $this->_options[emailListBuilderFloating][theme] = $this->_default_themes[emailListBuilderFloating][theme];
				?>
				<input type="hidden" id="PQEmailListBuilderFloating_Current_Theme" name="emailListBuilderFloating[theme]" value="<?php echo stripslashes($this->_options[emailListBuilderFloating][theme]);?>">
				<input type="hidden" id="PQEmailListBuilderFloating_CurrentThemeForPreview" value="">
				<div class="pq_navigation">
					
					<input type="button" value="<?php echo $this->_dictionary[navigation][skip_step];?>" onclick="selectStep('PQEmailListBuilderFloating', 2);enablePreviewIframe('PQEmailListBuilderFloating');selectForm('PQEmailListBuilderFloating', 'main');document.getElementById('PQThemeSelectPreviewPopup').style.display = 'none';">
				</div>
						<h1><?php echo $this->_dictionary[choseTheme][title];?></h1>
						<p><?php echo $this->_dictionary[choseTheme][description];?> </p>
						<div class="pq_grid">
						<?php foreach((array)$this->_themes[emailListBuilderFloating] as $k => $v){
								$isActive = '';
								if($k == stripslashes($this->_options[emailListBuilderFloating][theme])){
									$isActive = 'pq_active';
								}
						?>
							<div <?php echo 'class="'.$isActive.'"';?> onclick="
								<?php
											echo "previewThemeSelect('PQEmailListBuilderFloating','emailListBuilderFloating','".$k."', 'current');";
									?>
							"/>
								<img src="<?php if((float)$v[price] > 0) echo plugins_url('i/pro.png', __FILE__); else echo plugins_url('i/free.png', __FILE__);?>" />
								<img  src="<?php echo $v[preview_image_small];?>" />
								<span class="pq_preview_button"><?php echo $this->_dictionary[choseTheme][preview_button];?></span>
									<span class="pq_customize_button"><?php echo $this->_dictionary[choseTheme][chosen_button];?></span>
							</div>
						<?php }?>
						</div>
					<div class="pq_close"></div>
				</div>
				<div id="PQEmailListBuilderFloating_step_2" style="display:block;" class="design_settings">
					<a id="PQEmailListBuilderFloating_youusepro" class="pq_pro_theme" href="javascript:void(0)" onclick="openChooseProPopup('PQEmailListBuilderFloating')"><?php echo $this->_dictionary[navigation][pro_theme_selected];?></a>
					<div class="pq_thank_select">
						<span id="PQEmailListBuilderFloating_DesignToolSwitch_main" onclick="selectForm('PQEmailListBuilderFloating', 'main', ['desktop', 'mobile']);addClassToSwitcher('PQEmailListBuilderFloating', 'main', 'pq_active',['main', 'thank']);setToolFirstDesignStep('emailListBuilderFloating','PQEmailListBuilderFloating','');emailListBuilderFloatingPreview();" class="pq_active"><?php echo $this->_dictionary[navigation][your_tool];?></span>
						<span id="PQEmailListBuilderFloating_DesignToolSwitch_thank" onclick="selectForm('PQEmailListBuilderFloating', 'thank', ['desktop', 'mobile']);addClassToSwitcher('PQEmailListBuilderFloating', 'thank', 'pq_active',['main', 'thank']);setToolFirstDesignStep('emailListBuilderFloating','PQEmailListBuilderFloating','thank');emailListBuilderFloatingthankPreview();"><?php echo $this->_dictionary[navigation][success_tool];?></span>
						<span class="pq_disabled"><?php echo $this->_dictionary[navigation][email];?></span>
					</div>
					<!-- Preview PQEmailListBuilderFloating Iframe -->
			
						<div class="frame" id="PQEmailListBuilderFloating_PQIframePreviewBlock" style="display:none">
							<iframe src="about:blank" id="PQEmailListBuilderFloating_PQPreviewID" width="100%" height="100%" class="pq_if"></iframe>
						</div>
						<div id="PQEmailListBuilderFloating_PQForMobilePaper" class="pq_paper"></div>
						<!-- End Preview Iframe -->
					<div id="PQEmailListBuilderFloating_form_main" style="display:block;" class="pq_design">
					<div class="pq_sett_all">
						<div class="pq_desktop_mobile">
							<div id="PQEmailListBuilderFloating_main_DesignToolSwitch_desktop" onclick="addClassToSwitcher('PQEmailListBuilderFloating_main', 'desktop', 'pq_active',['desktop', 'mobile']);emailListBuilderFloatingPreview();" class="pq_active" ><?php echo $this->_dictionary[navigation][desktop];?><img src="<?php echo plugins_url('i/ico/desktop.png', __FILE__);?>"></div>
							<div id="PQEmailListBuilderFloating_main_DesignToolSwitch_mobile" onclick="addClassToSwitcher('PQEmailListBuilderFloating_main', 'mobile', 'pq_active',['desktop', 'mobile']);emailListBuilderFloatingPreview();"><?php echo $this->_dictionary[navigation][mobile];?><img src="<?php echo plugins_url('i/ico/mobile.png', __FILE__);?>"></div>
						</div>
						<!--h3>EMAIL LIST BUILDER FLOATING</h3-->
						<!-- DESKTOP -->
						<div id="PQEmailListBuilderFloating_form_main_desktop" style="display:block;">
							<?php
							
								echo $this->getFormCodeForTool(
								array(
										'general'=>array(											
											'text'=>$this->_dictionary[design_block_name][general],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_floating],
											'img'=>plugins_url('i/ico/setting_general.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#general_block',
											'elements'=>array(											
												'size_window',
												'popup_form',
												'background_color',
												'background_opacity',
												'border_type',
												'border_depth', 
												'border_color',
												'animation'
											)
										),
										'heading'=>array(											
											'text'=>$this->_dictionary[design_block_name][heading],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_floating],
											'img'=>plugins_url('i/ico/setting_title.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#heading_block',
											'elements'=>array(
												'title',
												'head_font',
												'head_font_size',
												'head_color'												
											)
										),										
										'text_block'=>array(
											'text'=>$this->_dictionary[design_block_name][text_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_floating],
											'img'=>plugins_url('i/ico/setting_subtitle.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#text_block',
											'elements'=>array(
												'sub_title',
												'text_font',
												'font_size',
												'text_color'												
											)
										),
										'promo_block'=>array(
											'text'=>$this->_dictionary[design_block_name][promo_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_floating],
											'img'=>plugins_url('i/ico/setting_promo.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#promo_block',
											'elements'=>array(																																				
												'tblock_text',
												'background_text_block',
												'text_block_padding',
												'tblock_text_font',
												'tblock_text_font_size',
												'tblock_text_font_color'
											)
										),
										'form_block'=>array(
											'text'=>$this->_dictionary[design_block_name][form_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_floating],
											'img'=>plugins_url('i/ico/setting_form.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#form_block',
											'elements'=>array(
												'background_form_block',
												'form_block_padding',
												'input_type',
												'enter_email_text',  
												'enter_name_text'																											
											)
										),
										'button_block'=>array(
											'text'=>$this->_dictionary[design_block_name][button_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_floating],
											'img'=>plugins_url('i/ico/setting_button.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#button_block',
											'elements'=>array(
												'button_form',
												'button_type',
												'background_button_block',
												'button_block_padding',
												'button_text',
												'button_font',
												'button_font_size',
												'button_text_color',
												'button_color'																												
											)
										),
										'close_block'=>array(
											'text'=>$this->_dictionary[design_block_name][close_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_floating],
											'img'=>plugins_url('i/ico/setting_close.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#close_block',
											'elements'=>array(
												'close_icon_type', 
												'close_text',
												'close_text_font',
												'close_icon_color',
												'close_icon_animation'																											
											)
										),										
										'pro_block'=>array(
											'text'=>$this->_dictionary[design_block_name][pro_block],
											'toolName'=>$this->_dictionary[toolName][email_list_builder_floating],
											'img'=>plugins_url('i/ico/setting_pro.png', __FILE__),
											'read_more_link'=>'http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#pro_block',
											'elements'=>array(
												'header_image_src',
												'header_img_type',												
												'background_image_src',
												'showup_animation'
											)
										)
									), 'emailListBuilderFloating', $this->_options[emailListBuilderFloating]);
							?>
							
						</div>
						<!-- MOBILE -->
						<div id="PQEmailListBuilderFloating_form_main_mobile" style="display:none;">
						</div>
					</div>
					</div>
					<div class="pq_design_buttons">
						<input type="button" value="<?php echo $this->_dictionary[navigation][next];?>" onclick="selectStep('PQEmailListBuilderFloating', 3);">
						<a onclick="clearLastChanges('emailListBuilderFloating');" <?php if(trim($this->_options[emailListBuilderFloating][position])=='') echo 'style="display:none;"';?>><?php echo $this->_dictionary[navigation][clear_changes];?></a>
					</div>
					<?php
						echo $this->generateThankHTML('emailListBuilderFloating', 'PQEmailListBuilderFloating');
					?>
					
				</div>
				<div id="PQEmailListBuilderFloating_step_3" style="display:none;" class="pq_rules">
					<div class="pq_navigation">
						
						<p><?php echo $this->_dictionary[displayRules][description];?></p>
						<input type="button" value="<?php echo $this->_dictionary[navigation][next_step];?>" onclick="selectStep('PQEmailListBuilderFloating', 4);">
					</div>
					<h1><?php echo $this->_dictionary[displayRules][title];?> <a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#display_rules" target="_settings_info">?</a></h1>
					<p></p>
					<?php
						echo $this->getEventHandlerBlock('emailListBuilderFloating', array('delay', 'scrolling'),  $this->_options[emailListBuilderFloating]);
					?>
					<?php
						echo $this->getPageOptions('emailListBuilderFloating', $this->_options[emailListBuilderFloating]);
					?>
					<?php
						echo $this->getGadgetRules('emailListBuilderFloating', $this->_options[emailListBuilderFloating]);
					?>
					<div style="visibility:hidden">
					<input type="button" value="<?php echo $this->_dictionary[navigation][next_step];?>" onclick="selectStep('PQEmailListBuilderFloating', 4);">
					<input type="submit" name="" value="<?php echo $this->_dictionary[navigation][save_button];?>">
					</div>
				</div>
				<div id="PQEmailListBuilderFloating_step_4" style="display:none;" class="provider_settings">
					<div class="pq_navigation">
						
					</div>
					<h1><?php echo $this->_dictionary[providerSettings][title];?></h1>
					<p><?php echo $this->_dictionary[providerSettings][description];?></p>
					<?php
						echo $this->getProviderBlock('emailListBuilderFloating', $this->_options[emailListBuilderFloating]);
					?>
					<?php
						echo $this->getLockBlock('emailListBuilderFloating', $this->_options[emailListBuilderFloating]);
					?>
					<div style="visibility:hidden">
					<input type="submit" value="<?php echo $this->_dictionary[navigation][save_button];?>">
					</div>
				</div>
			</form>
			</div>
	<!-- ********* End Email List Builder Floating Div -->
				<script>
					<?php
						if((int)$_GET[step]){
							echo '
								disableAllDialog();	
								startTool(\'PQEmailListBuilderFloating\');
								selectStep(\'PQEmailListBuilderFloating\', '.(int)$_GET[step].');
							';
						}else{
							echo '
								emailListBuilderFloatingSettings(\''.stripslashes(urldecode($_GET[pos])).'\');
							';
						}
					?>
					checkTheme('emailListBuilderFloating', 'PQEmailListBuilderFloating');
					document.getElementById('PQLoader').style.display='none';
				</script>
				<?php
			}
	?>
	</div>
	<!-- ***********************************************************************END TOOLS*********************************************************************************** -->
					
			
			<!-- CLEAR LAST CHANGES -->
			<div id="PQClearLastChangesPopup" style="display:none;">
				<input type="hidden" id="PQCLearLastChangesTool" value="">
				<h2><?php echo $this->_dictionary[clearChangesPopup][title];?></h2>
				<p><?php echo $this->_dictionary[clearChangesPopup][description];?></p>
				<input type="button" onclick="clearLastChangesProceed();" value="<?php echo $this->_dictionary[clearChangesPopup][button_yes];?>">
				<input type="button" onclick="document.getElementById('PQClearLastChangesPopup').style.display = 'none';" value="<?php echo $this->_dictionary[clearChangesPopup][button_no];?>"><br>
			</div>
			
			
			
			
			<!-- CONTINUE EXTEND PAY POPUP -->
			<?php				
				//CHECKOUT
				if($_POST[action] == 'checkout')
				{
										
					$summ = 0;
					$collMonths = (int)$_POST[checkoutMonthPeriod];
					$optArray = array();
					$formFields = '';
					$fieldCnt = 0;
					
					if($_POST[type] == 'activate'){
						if($collMonths == 1) $summ = $this->_plugin_settings[price]['pro_1'];
						if($collMonths == 6) $summ = $this->_plugin_settings[price]['pro_6'];
						if($collMonths == 12) $summ = $this->_plugin_settings[price]['pro_12'];
						$optArray['tool_pro']=1;							
						
						$formFields .= '<input type="hidden" name="li_'.$fieldCnt.'_name" value="24 PRO Tools">';
						$formFields .= '<input type="hidden" name="li_'.$fieldCnt.'_price" value="'.($this->_plugin_settings[price]['pro_1']*$collMonths).'">';
						$fieldCnt++;
					}
					
					if($_POST[type] == 'upgrade'){
						if($_POST[tool][period] && $_POST[tool][name]){
							if($collMonths == 1) $summ = $this->_plugin_settings[price]['pro_1'];
							if($collMonths == 6) $summ = $this->_plugin_settings[price]['pro_6'];
							if($collMonths == 12) $summ = $this->_plugin_settings[price]['pro_12'];
							$optArray['tool_pro']=1;
							
							$formFields .= '<input type="hidden" name="li_'.$fieldCnt.'_name" value="24 PRO Tools">';
							$formFields .= '<input type="hidden" name="li_'.$fieldCnt.'_price" value="'.($this->_plugin_settings[price]['pro_1']*$collMonths).'">';
							$fieldCnt++;
						}
					}
					
					if($_POST[type] == 'extend'){						
						if($collMonths == 1) $summ = $this->_plugin_settings[price]['pro_1'];
						if($collMonths == 6) $summ = $this->_plugin_settings[price]['pro_6'];
						if($collMonths == 12) $summ = $this->_plugin_settings[price]['pro_12'];
						$optArray['tool_pro']=1;
						
						
						$formFields .= '<input type="hidden" name="li_'.$fieldCnt.'_name" value="24 PRO Tools">';
						$formFields .= '<input type="hidden" name="li_'.$fieldCnt.'_price" value="'.($this->_plugin_settings[price]['pro_1']*$collMonths).'">';
						$fieldCnt++;
					}					
					$save = 0;
					
					$allSumm = round($this->_plugin_settings[price]['pro_1']*$collMonths);					
					$save = $allSumm - $summ;				
					if($save > 0){	
						$formFields .= '<input type="hidden" name="li_'.$fieldCnt.'_name" value="Profitquery Discount">';
						$formFields .= '<input type="hidden" name="li_'.$fieldCnt.'_price" value="'.$save.'">';
						$formFields .= '<input type="hidden" name="li_'.$fieldCnt.'_type" value="coupon">';
					}
					if($summ > 0){
						?>
						<script>
							try{document.getElementById('PQActivatePopup').style.display = 'none';}catch(err){}
							try{document.getElementById('PQExtandPopup').style.display = 'none';}catch(err){}
						</script>
						<div id="PQContinueCheckoutForm">
						<form action="https://www.2checkout.com/checkout/purchase" target="_" method="post" >
						<input name="sid" type="hidden" value="102567109">
						<input name="mode" type="hidden" value="2CO">  
						<input type="hidden" name="pq_period" value="<?php echo (int)$collMonths;?>">  
						<?php echo $formFields;?>
						  <h2>
							<?php
								if($save > 0){
									echo 'Save '.$save.'$';
								}else{
									echo 'Current total '.$summ.'$';
								}
							?>
						  </h2>
						  <p>
							<?php
								if($save > 0){
									echo 'Current total '.$summ.'$';
								}
							?>
						  </p>
						  <label><?php echo $this->_dictionary[checkoutConfirmPopup][enter_email];?><input name="email" type="text" value="<?php echo stripslashes($this->_options[settings][email]);?>"></label>
						  <label><?php echo $this->_dictionary[checkoutConfirmPopup][enter_domain];?><input name="domain" type="text" value="<?php echo $this->getDomain();?>"></label>
						  <label>Referral Code<input name="code" type="text" value="<?php echo stripslashes($this->_options[settings][code]);?>"></label>
						
						<?php
							$cnt = 0;
							foreach((array)$optArray as $k => $v){
								echo '<input name="pq_'.$cnt.'_art" type="hidden" value="'.$k.'">';
								$cnt++;
							}
						?>
						<input type="submit" name="sbmt" value="<?php if($save > 0) echo 'Get PRO and Save '.$save.'$'; else echo 'Get PRO';?>">
						<a class="pq_close"  href="javascript:void(0)" onclick="document.getElementById('PQContinueCheckoutForm').style.display='none';"></a>
						<img src="<?php echo plugins_url('i/basics.png', __FILE__);?>" />
						</form>
						</div>
						<?php					
					}
				}
			?>
		
		<div class="clear"></div>
	<script>


	<?php
		if($_GET[act] == 'clearLastChanges' && $_GET[s_t]){
			echo 'returnToDesignStep("'.$_GET[s_t].'");';
		}
		if(trim($providerRedirectByError)){
			echo 'returnToProviderStep("'.$providerRedirectByError.'");';
		}
	?>
	</script>

	<!-- LANG -->
				<div id="PQ_lang">
				<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
				<input type="hidden" name="action" value="setLang">
					<h2><?php echo $this->_dictionary[choseLangDialog][title_select];?> <a href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#lang_settings_block" target="_settings_info">?</a></h2>
					<select name="lang">
						<?php						
							foreach((array)$this->_plugin_settings[lang] as $code => $data){							
								echo '<option value="'.$code.'" '.sprintf("%s", (($code == $this->_options[settings][lang] ) ? "selected" : "")).'>'.stripslashes($data[name]).'</option>';
							}
						?>
					</select>
					<input type="submit" value="<?php echo $this->_dictionary[choseLangDialog][button];?>">
					<p><?php printf($this->_dictionary[choseLangDialog][text_contact_us], 'href="javascript:void(0)" onclick="document.getElementById(\'PQNeedHelpPopup\').style.display=\'block\';"');?></p>
					<h3><?php echo $this->_dictionary[choseLangDialog][title_translate];?></h3>
					<p><?php echo $this->_dictionary[choseLangDialog][text_translate];?></p>
					<div class="pq_languages">
						<?php						
							foreach((array)$this->_plugin_settings[needTranslate] as $code => $data){
								echo '<label><input type="radio" name="lang"><div><img src="'.$data[icon].'">'.$data[name].'</div></label>';
							}
						?>
					</div>
					<a href="http://profitquery-a.akamaihd.net/lib/plugins/lang/profitquery_plugin_dictionary.v5.zip" target="_langDict"><?php echo $this->_dictionary[choseLangDialog][downloadZip];?></a>
					<a class="pq_close" href="javascript:void(0)" onclick="document.getElementById('PQ_lang').style.display='none';"></a>
					</form>
				</div>
				
				<!-- ACTIVATE POPUP  -->
			<div class="pq_upgrade" style="display:none;" id="PQHowGetPro">				
				<h2><?php echo $this->_dictionary[proOptionsInfo][activate_pro];?></h2>
				<p><?php echo $this->_dictionary[proOptionsInfo][activate_pro_description];?></p>
				<a class="pq_active_btn" onclick="document.getElementById('PQActivatePopup').style.display='block';"><?php echo $this->_dictionary[proOptionsInfo][upgrade_to_pro];?> $<?php echo (float)($this->_plugin_settings[price]['pro_12']/12);?>/mo</a>
				<a class="" href="<?php echo $this->getSettingsPageUrl();?>&act=freeTrial"><?php echo $this->_dictionary[proOptionsInfo][start_free_trial];?></a>
				<div class="features_table">
					<div class="pq_half pq_pro_mode">
						<h3><?php echo $this->_dictionary[proOptionsInfo][free_version];?></h4>
						<table>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_f_themes];?></td>
							</tr>							
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_ga];?></td>
							</tr>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_utraffic];?></td>
							</tr>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_eservice];?></td>
							</tr>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_mobile];?></td>
							</tr>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_support];?></td>
							</tr>
							<tr>
								<td>&mdash;</td>
							</tr>
							<tr>
								<td>&mdash;</td>
							</tr>
							<tr>
								<td>&mdash;</td>
							</tr>
							<tr>
								<td>&mdash;</td>
							</tr>
						</table>
					</div>
					<div class="pq_half pq_pro_mode">
						<h3><?php echo $this->_dictionary[proOptionsInfo][pro_version];?></h4>
						<table>							
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_pro_template];?></td>
							</tr>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_pro_traffic];?></td>
							</tr>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_pro_images];?></td>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_pro_icons];?></td>
							</tr>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_pro_anim];?></td>
							</tr>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_pro_faster];?></td>
							</tr>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_pro_tracking];?></td>
							</tr>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_pro_mail];?></td>
							</tr>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_pro_whitelabel];?></td>
							</tr>
							<tr>
								<td><?php echo $this->_dictionary[proOptionsInfo][opt_pro_support];?></td>
							</tr>
						</table>
					</div>
				</div>
				<p><?php echo $this->_dictionary[proOptionsInfo][pro_text];?></p>
				<div id="PQSelectedProOptions2" style="display: block">
					<div class="pq_unlock_pro_tools">
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_sharing_sidebar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][sharing_sidebar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_image_sharer_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][image_sharer];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_sharing_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][sharing_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_sharing_bar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][sharing_bar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_sharing_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][sharing_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_collect_email_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][email_list_builder_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_collect_email_bar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][email_list_builder_bar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_collect_email_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][email_list_builder_floating];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_contact_form_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][contact_form_bookmark];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_contact_form_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][contact_form_floating_popup];?></span>
						</div>						
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_contact_form_center_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][contact_form_center];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_promote_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][promotion_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_promote_bar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][promotion_bar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_promote_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][promotion_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_call_me_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][call_me_bookmark];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_call_me_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][call_me_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_follow_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][follow_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_follow_bar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][follow_bar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_follow_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][follow_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_iframe_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][iframe_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_iframe_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][iframe_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_youtube_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][youtube_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_youtube_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][youtube_floating_popup];?></span>
						</div>
					</div>
					<a class="pq_active_btn" onclick="document.getElementById('PQActivatePopup').style.display='block';"><?php echo $this->_dictionary[proOptionsInfo][upgrade_to_pro];?> $<?php echo (float)($this->_plugin_settings[price]['pro_12']/12);?>/mo</a>
					<a class="" href="<?php echo $this->getSettingsPageUrl();?>&act=freeTrial"><?php echo $this->_dictionary[proOptionsInfo][start_free_trial];?></a>
					
					<h3><?php echo $this->_dictionary[proOptionsInfo][faq_title];?></h3>
					<div class="pq_half pq_pro_mode">						
						<h4><?php echo $this->_dictionary[proOptionsInfo][start_earning];?></h4>
						<p><?php echo $this->_dictionary[proOptionsInfo][start_earning_desc];?>
						<br><a href="mailto:support@profitquery.com?subject=[PQ_ES_WP <?php echo $this->getDomain();?>] Partner program"><?php echo $this->_dictionary[proOptionsInfo][pl_email_us];?></a>
						</p>
					</div>
					<div class="pq_half pq_pro_mode">
						
						<h4><?php echo $this->_dictionary[proOptionsInfo][get_pro_for_free];?></h4>
						<p><?php echo $this->_dictionary[proOptionsInfo][get_pro_for_free_desc];?>
						<br><a href="mailto:support@profitquery.com?subject=[PQ_ES_WP <?php echo $this->getDomain();?>] Pro for free"><?php echo $this->_dictionary[proOptionsInfo][pl_email_us];?></a>
						</p>
					</div>
					<p><?php echo $this->_dictionary[proOptionsInfo][q_about_pro];?> <a href="mailto:support@profitquery.com?subject=[PQ_ES_WP <?php echo $this->getDomain();?>] About Pro"><?php echo $this->_dictionary[proOptionsInfo][pl_email_us];?></a></p>
				</div>
				<a class="pq_close" onclick="document.getElementById('PQHowGetPro').style.display='none';"><?php echo $this->_dictionary[action][close];?></a>
			</div>
			
			<div class="pq_upgrade" style="display:none;" id="PQChooseProPopup">
			<input type="hidden" id="PQProSelectCurrentToolID" value="">
				<h2><?php echo $this->_dictionary[navigation][about_pro];?></h2>				
				<p><?php echo $this->_dictionary[proOptionsInfo][about_pro_desc];?></p>
				<div class="features">
							<label>
								<input type="radio" name="feature_name" checked="checked">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_1.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_template];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_2.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_anim];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_3.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_faster];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_4.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_mail];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_5.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_support];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_6.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_copyright];?></p>
								</div>
							</label>
						</div>				
				<a class="pq_active_btn" onclick="document.getElementById('PQChooseProPopup').style.display='none';"><?php echo $this->_dictionary[action]['continue'];?></a>
				<a class="" href="javascript:void(0)" onclick="goToThemesStep()"><?php echo $this->_dictionary[action][use_for_free];?></a>			
				<a class="pq_close" onclick="document.getElementById('PQChooseProPopup').style.display='none';"><?php echo $this->_dictionary[action][close];?></a>
			</div>			
			<div class="pq_upgrade" style="display:none" id="PQActivatePopup">
			<form action="<?php echo $this->getSettingsPageUrl();?>" method="post" id="PQtoActivateForm">
			<input type="hidden" name="action" value="checkout">
			<input type="hidden" name="type" value="activate">
			<input type="hidden" id="PQActivatePopup_Period" value="12">
				<h2><?php echo $this->_dictionary[activatePopup][title];?></h2>
				<p><?php echo $this->_dictionary[activatePopup][description];?></p>
				<div class="pq_period__">
				<label>
					<input type="radio"  name="checkoutMonthPeriod" value="12" checked onclick="document.getElementById('PQActivatePopup_Period').value=this.value;changeActivateCheckoutPeriod();">
					<div>
						<p><strong><?php echo ($this->_plugin_settings[price]['pro_12']/12).'$/mo';?></strong> <img src="<?php echo plugins_url('i/disable_pro.png', __FILE__);?>"> <?php echo $this->_dictionary[activatePopup][tools_12];?><strong><?php echo $this->_dictionary[activatePopup][save];?> <?php echo (float)($this->_plugin_settings[price]['pro_1']*12-$this->_plugin_settings[price]['pro_12']);?>$</strong></p>
					</div>
				</label>
				<label>
					<input type="radio" name="checkoutMonthPeriod" value="6"  onclick="document.getElementById('PQActivatePopup_Period').value=this.value;changeActivateCheckoutPeriod();">
					<div>
						<p><strong><?php echo ($this->_plugin_settings[price]['pro_6']/6).'$/mo';?></strong> <img src="<?php echo plugins_url('i/disable_pro.png', __FILE__);?>"> <?php echo $this->_dictionary[activatePopup][tools_6];?> <strong><?php echo $this->_dictionary[activatePopup][save];?> <?php echo (float)($this->_plugin_settings[price]['pro_1']*6-$this->_plugin_settings[price]['pro_6']);?>$</strong></p>
					</div>
				</label>
				<label>
					<input type="radio" name="checkoutMonthPeriod" value="1" onclick="document.getElementById('PQActivatePopup_Period').value=this.value;changeActivateCheckoutPeriod();">
					<div>
						<p><strong><?php echo ($this->_plugin_settings[price]['pro_1']).'$/mo';?></strong> <img src="<?php echo plugins_url('i/disable_pro.png', __FILE__);?>"> <?php echo $this->_dictionary[activatePopup][tools_1];?> </p>
					</div>
				</label>
				</div>
				<div style="">
					<table width="100%" cellspacing="0" style="margin:10px auto 0px; padding:0;border-spacing: 0px 5px;">
						<tbody>
							<?php
								$ifForActivateToolsExist = 1;					
							?>
						</tbody>
					</table>
				</div>
				<div >
					<p id="PQActivatePopup_Price_Text"></p>					
				</div>				
				<input type="submit" class="add_new_big" id="PQActivateForm_Submit" value="<?php echo $this->_dictionary[action][pay];?>">
				<a onclick="disableCurrentOption('pro', '24 PRO Tools', '<?php echo plugins_url('i/disable_pro.png', __FILE__);?>', '');"><?php echo $this->_dictionary[activatePopup][use_free];?></a>
				<div class="pq_close" onclick="document.getElementById('PQActivatePopup').style.display='none';"></div>				
				<script>
					changeActivateCheckoutPeriod();
				</script>
				</form>
			</div>
			<?php
				if((int)$needOpenActivatePopup && (int)$ifForActivateToolsExist == 1){
					echo '<script>document.getElementById("PQActivatePopup").style.display="block";</script>';
				}
				if((int)$upgradeToolPopup == 1){
					echo '<script>document.getElementById("PQActivatePopup").style.display="block";</script>';
				}
			?>
			<!-- END ACTIVATE  -->
			
			<div class="pq_upgrade" style="<?php if((int)$disableNeedUpgradePopup == 0) echo 'display:block;'; else echo 'display:none;';?>" id="PQNeedUpgradeTool">
				<div style="display:none">
					<img src="<?php echo plugins_url('i/diamond_m.png', __FILE__);?>" />
					<h2><?php echo $this->_dictionary[upgrade_tool][title];?></h2>
					<p><?php echo $this->_dictionary[upgrade_tool][description];?></p>
					<a href="http://profitquery.com/?utm=pq_es_wp&section=upgrade_tool" target="aboutPro"><?php echo $this->_dictionary[notification][read_more];?></a>
					<div style="max-height:250px; overflow-y:auto; padding-right:5px">
					<table width="100%" cellspacing="0" style="margin:10px auto 0px; padding:0;border-spacing: 0px 5px;">
					<tbody>					
						<?php
											
						?>
									<tr>
										<td><img src="<?php echo plugins_url('i/ico/main_pro.png', __FILE__);?>"></td>		
										<td><?php echo '24 PRO Tools';?></td>
										<td><?php echo ($this->_plugin_settings[price][pro_12]/12);?>$/mo</td>
										<td><a href="<?php echo $this->getSettingsPageUrl();?>&act=upgrade&tool=pro" ><?php echo $this->_dictionary[action][upgrade];?></a></td>
									</tr>									
								<?php
						
							?>
					</tbody>
					</table>
					</div>
					
				</div>
				<img src="<?php echo plugins_url('i/pro/diamond.png', __FILE__);?>" />
				<h2><?php echo $this->_dictionary[upgrade_tool][title];?></h2>
				<p><?php echo $this->_dictionary[proOptionsInfo][thanks_for_using];?><?php echo $this->_dictionary[proOptionsInfo][activate_pro_description];?></p>
				<div>
						<div class="features">
							<label>
								<input type="radio" name="feature_name" onclick="showPROImgFeaturesNeed(1);" checked="checked">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_1.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_template];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name" onclick="showPROImgFeaturesNeed(2);">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_2.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_anim];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name" onclick="showPROImgFeaturesNeed(3);">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_3.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_faster];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name" onclick="showPROImgFeaturesNeed(4);">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_4.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_mail];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name" onclick="showPROImgFeaturesNeed(5);">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_5.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_support];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name" onclick="showPROImgFeaturesNeed(6);">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_6.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_copyright];?></p>
								</div>
							</label>
						</div>
						<div class="features_l feature1" id="PQProNeedPopupFeaturesImg_1" style="display:block;background-image:url(<?php echo plugins_url('i/pro/feature_1.png', __FILE__);?>);">
						</div>
						<div class="features_l feature2" id="PQProNeedPopupFeaturesImg_2" style="display:none;background-image:url(<?php echo plugins_url('i/pro/feature_2.gif', __FILE__);?>);">
						</div>
						<div class="features_l feature3" id="PQProNeedPopupFeaturesImg_3" style="display:none;background-image:url(<?php echo plugins_url('i/pro/feature_3.png', __FILE__);?>);">
						</div>
						<div class="features_l feature4" id="PQProNeedPopupFeaturesImg_4" style="display:none;background-image:url(<?php echo plugins_url('i/pro/feature_4.gif', __FILE__);?>);">
						</div>
						<div class="features_l feature5" id="PQProNeedPopupFeaturesImg_5" style="display:none;background-image:url(<?php echo plugins_url('i/pro/feature_5.png', __FILE__);?>);">
						</div>
						<div class="features_l feature6" id="PQProNeedPopupFeaturesImg_6" style="display:none;background-image:url(<?php echo plugins_url('i/pro/feature_6.gif', __FILE__);?>);">						
						</div>
						<script>
						function showPROImgFeaturesNeed(val){
							document.getElementById('PQProNeedPopupFeaturesImg_1').style.display="none";
							document.getElementById('PQProNeedPopupFeaturesImg_2').style.display="none";
							document.getElementById('PQProNeedPopupFeaturesImg_3').style.display="none";
							document.getElementById('PQProNeedPopupFeaturesImg_4').style.display="none";
							document.getElementById('PQProNeedPopupFeaturesImg_5').style.display="none";
							document.getElementById('PQProNeedPopupFeaturesImg_6').style.display="none";
							
							document.getElementById('PQProNeedPopupFeaturesImg_'+val).style.display="block";							
						}						
					</script>
					</div>
				<p><?php echo $this->_dictionary[proOptionsInfo][about_pro_desc];?></p>
				<div class="pq_unlock_pro_tools">
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_sharing_sidebar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][sharing_sidebar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_image_sharer_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][image_sharer];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_sharing_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][sharing_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_sharing_bar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][sharing_bar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_sharing_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][sharing_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_collect_email_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][email_list_builder_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_collect_email_bar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][email_list_builder_bar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_collect_email_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][email_list_builder_floating];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_contact_form_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][contact_form_bookmark];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_contact_form_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][contact_form_floating_popup];?></span>
						</div>						
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_contact_form_center_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][contact_form_center];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_promote_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][promotion_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_promote_bar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][promotion_bar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_promote_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][promotion_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_call_me_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][call_me_bookmark];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_call_me_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][call_me_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_follow_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][follow_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_follow_bar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][follow_bar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_follow_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][follow_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_iframe_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][iframe_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_iframe_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][iframe_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_youtube_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][youtube_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_youtube_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][youtube_floating_popup];?></span>
						</div>
					</div>
					<a class="pq_active_btn" href="<?php echo $this->getSettingsPageUrl();?>&act=upgrade&tool=pro"><?php echo $this->_dictionary[proOptionsInfo][upgrade_to_pro];?> $<?php echo (float)($this->_plugin_settings[price]['pro_12']/12);?>/mo</a>
					<h3><?php echo $this->_dictionary[proOptionsInfo][faq_title];?></h3>
					<div class="pq_half pq_pro_mode">						
						<h4><?php echo $this->_dictionary[proOptionsInfo][start_earning];?></h4>
						<p><?php echo $this->_dictionary[proOptionsInfo][start_earning_desc];?>
						<br><a href="mailto:support@profitquery.com?subject=[PQ_ES_WP <?php echo $this->getDomain();?>] Partner program"><?php echo $this->_dictionary[proOptionsInfo][pl_email_us];?></a>
						</p>
					</div>
					<div class="pq_half pq_pro_mode">
						
						<h4><?php echo $this->_dictionary[proOptionsInfo][get_pro_for_free];?></h4>
						<p><?php echo $this->_dictionary[proOptionsInfo][get_pro_for_free_desc];?>
						<br><a href="mailto:support@profitquery.com?subject=[PQ_ES_WP <?php echo $this->getDomain();?>] Pro for free"><?php echo $this->_dictionary[proOptionsInfo][pl_email_us];?></a>
						</p>
					</div>	
					<p><?php echo $this->_dictionary[proOptionsInfo][q_about_pro];?> <a href="mailto:support@profitquery.com?subject=[PQ_ES_WP <?php echo $this->getDomain();?>] About Pro"><?php echo $this->_dictionary[proOptionsInfo][pl_email_us];?></a></p>
				<a class="pq_close" href="<?php echo $this->getSettingsPageUrl();?>&act=later_upgrade"><?php echo $this->_dictionary[action][later];?></a>			
			</div>						
			
			<!-- EXTEND POPUP  -->
			<div class="pq_upgrade no_longer" style="display:none" id="PQExtendPopup">
			<form action="<?php echo $this->getSettingsPageUrl();?>" method="post" id="PQExtendForm">
			<input type="hidden" name="action" value="checkout">
			<input type="hidden" name="type" value="extend">
			<input type="hidden" id="PQExtendPopup_ExtendPeriod" value="12">
				<h2><?php echo $this->_dictionary[extendPopup][title];?></h2>
				<p><?php echo $this->_dictionary[extendPopup][description];?></p>
				<div class="pq_period__">
				<label>
					<input type="radio"  name="checkoutMonthPeriod" value="12" checked onclick="document.getElementById('PQExtendPopup_ExtendPeriod').value=this.value;changeExtendCheckoutPeriod();">
					<div>
						<p><strong><?php echo ($this->_plugin_settings[price]['pro_12']/12).'$/mo';?></strong> <img src="<?php echo plugins_url('i/disable_pro.png', __FILE__);?>"> <?php echo $this->_dictionary[activatePopup][tools_12];?> <strong><?php echo $this->_dictionary[activatePopup][save];?> <?php echo (float)($this->_plugin_settings[price]['pro_1']*12-$this->_plugin_settings[price]['pro_12']);?>$</strong></p>
					</div>
				</label>
				<label>
					<input type="radio" name="checkoutMonthPeriod" value="6"  onclick="document.getElementById('PQExtendPopup_ExtendPeriod').value=this.value;changeExtendCheckoutPeriod();">
					<div>
						<p><strong><?php echo ($this->_plugin_settings[price]['pro_6']/6).'$/mo';?></strong> <img src="<?php echo plugins_url('i/disable_pro.png', __FILE__);?>"> <?php echo $this->_dictionary[activatePopup][tools_6];?> <strong><?php echo $this->_dictionary[activatePopup][save];?> <?php echo (float)($this->_plugin_settings[price]['pro_1']*6-$this->_plugin_settings[price]['pro_6']);?>$</strong></p>
					</div>
				</label>
				<label>
					<input type="radio" name="checkoutMonthPeriod" value="1" onclick="document.getElementById('PQExtendPopup_ExtendPeriod').value=this.value;changeExtendCheckoutPeriod();">
					<div>
						<p><strong><?php echo ($this->_plugin_settings[price]['pro_1']).'$/mo';?></strong> <img src="<?php echo plugins_url('i/disable_pro.png', __FILE__);?>"> <?php echo $this->_dictionary[activatePopup][tools_1];?> </p>
					</div>
				</label>
				</div>
				<div style="width: auto;display: block;overflow-y: auto;    max-height: 45vh; margin: 10px auto 20px;">
				<table width="100%" cellspacing="0" style="margin:10px auto 0px; padding:0;border-spacing: 0px 5px;">
				<tbody>		
				</tbody>
				</table>
				</div>
				<div >
					<p id="PQExtendPopup_Price_Text"></p>					
				</div>				
				<input type="submit" class="add_new_big" id="PQExtendPopup_Submit" value="<?php echo $this->_dictionary[action][pay];?>">		
				<div class="pq_close" onclick="document.getElementById('PQExtendPopup').style.display='none';"></div>				
				<script>
					changeExtendCheckoutPeriod();
				</script>
				</form>
			</div>
			<!--  END EXTEND -->
			
			<!-- Disable Option Popup -->
				<div class="pq_popup_desable" id="PQ_Disable_Option_Popup" >
				<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
				<input type="hidden" id="PQ_Disable_Option_Popup_location" name="currentLocation" value="">
				<input type="hidden" name="action" value="disableOption">				
				<input type="hidden" id="PQ_Disable_Option_toolID" name="tool" value="">
					<img id="PQ_Disable_Option_Popup_src" src="">
					<h2 id="PQ_Disable_Option_Popup_title"></h2>
					<input type="submit" value="<?php echo $this->_dictionary[disableDialog][button_option_yes];?>">
					<input type="button" value="<?php echo $this->_dictionary[disableDialog][button_no];?>" href="javascript:void(0)" onclick="document.getElementById('PQ_Disable_Option_Popup').style.display='none';"><br>
				</form>
				</div>
				
				<script>
					function disableCurrentOption(id, name, src, location){
						document.getElementById('PQ_Disable_Option_Popup').style.display = 'block';
						name = PQPluginDict.toolsStatus.pro_mode+' '+name;
						var title = '<?php echo $this->_dictionary[disableDialog][title];?>';					
						document.getElementById('PQ_Disable_Option_toolID').value = id;						
						document.getElementById('PQ_Disable_Option_Popup_title').innerHTML = title.replace(new RegExp("(\%s)",'g'),name);					
						document.getElementById('PQ_Disable_Option_Popup_src').src = src;
						document.getElementById('PQ_Disable_Option_Popup_location').value = location;
					}
				</script>
				
				<!-- Disable Popup -->
				<div class="pq_popup_desable" id="PQ_Disable_Popup">
				<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
				<input type="hidden" name="action" value="disable">
				<input type="hidden" id="PQ_Disable_Popup_toolsID" name="toolsID" value="">
					<img id="PQ_Disable_Popup_src" src="">
					<h2 id="PQ_Disable_Popup_title"></h2>
					<input type="submit" value="<?php echo $this->_dictionary[disableDialog][button_yes];?>">
					<input type="button" id="PQ_Disable_Popup_cancel" value="<?php echo $this->_dictionary[disableDialog][button_no];?>" href="javascript:void(0)" onclick=""><br>
				</form>
				</div>
				
				<!-- Enbale Popup -->
				<div class="pq_popup_desable" id="PQ_Enable_Popup">
				<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
				<input type="hidden" name="action" value="enable">
				<input type="hidden" id="PQ_Enable_Popup_toolsID" name="toolsID" value="">
					<img id="PQ_Enable_Popup_src" src="">
					<h2 id="PQ_Enable_Popup_title"></h2>
					<input type="submit" value="<?php echo $this->_dictionary[disableDialog][button_enable_yes];?>">
					<input type="button" id="PQ_Enable_Popup_cancel" value="<?php echo $this->_dictionary[disableDialog][button_no];?>" href="javascript:void(0)" onclick="document.getElementById('PQ_Enable_Popup').style.display='none';"><br>
				</form>
				</div>
				
				<!-- Delete Popup -->
				<div class="pq_popup_desable" id="PQ_Delete_Popup">
				<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
				<input type="hidden" name="action" value="delete">
				<input type="hidden" id="PQ_Delete_Popup_toolsID" name="toolsID" value="">
					<img id="PQ_Delete_Popup_src" src="">
					<h2 id="PQ_Delete_Popup_title"></h2>
					<input type="submit" value="<?php echo $this->_dictionary[deleteDialog][button_yes];?>">
					<input type="button" id="PQ_Delete_Popup_cancel" value="<?php echo $this->_dictionary[deleteDialog][button_no];?>" href="javascript:void(0)" onclick=""><br>
				</form>
				</div>
			
			<!--  PRO OPTIONS POPUP -->
			<div class="pq_anim pq_upgrade" id="PQ_pro_info">				
				<h2><?php echo $this->_dictionary[proOptionsInfo][upgrade_to_pro];?></h2>
				<p><?php echo $this->_dictionary[proOptionsInfo][activate_pro_description];?></p>
				<div id="PQSelectedProOptions2" style="display: block">
					<div class="pq_unlock_pro_tools">
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_sharing_sidebar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][sharing_sidebar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_image_sharer_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][image_sharer];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_sharing_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][sharing_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_sharing_bar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][sharing_bar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_sharing_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][sharing_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_collect_email_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][email_list_builder_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_collect_email_bar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][email_list_builder_bar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_collect_email_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][email_list_builder_floating];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_contact_form_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][contact_form_bookmark];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_contact_form_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][contact_form_floating_popup];?></span>
						</div>						
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_contact_form_center_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][contact_form_center];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_promote_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][promotion_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_promote_bar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][promotion_bar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_promote_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][promotion_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_call_me_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][call_me_bookmark];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_call_me_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][call_me_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_follow_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][follow_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_follow_bar_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][follow_bar];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_follow_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][follow_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_iframe_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][iframe_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_iframe_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][iframe_floating_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_youtube_popup_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][youtube_popup];?></span>
						</div>
						<div>
							<img src="<?php echo plugins_url('i/pro/ico_youtube_floating_pro.png', __FILE__);?>" />
							<span><?php echo $this->_dictionary[toolName][youtube_floating_popup];?></span>
						</div>
					</div>
					<img src="<?php echo plugins_url('i/pro/diamond.png', __FILE__);?>" />
					<h3><?php echo $this->_dictionary[proOptionsInfo][about_pro_title];?></h3>
					<p><?php echo $this->_dictionary[proOptionsInfo][about_pro_desc];?></p>
					
					<div>
						<div class="features">
							<label>
								<input type="radio" name="feature_name" onclick="showPROImgFeatures(1);" checked="checked">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_1.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_template];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name" onclick="showPROImgFeatures(2);">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_2.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_anim];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name" onclick="showPROImgFeatures(3);">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_3.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_faster];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name" onclick="showPROImgFeatures(4);">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_4.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_mail];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name" onclick="showPROImgFeatures(5);">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_5.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_support];?></p>
								</div>
							</label>
							<label>
								<input type="radio" name="feature_name" onclick="showPROImgFeatures(6);">
								<div>
									<img src="<?php echo plugins_url('i/pro/ico_feature_6.png', __FILE__);?>" />
									<p><?php echo $this->_dictionary[proOptionsInfo][pro_bl_copyright];?></p>
								</div>
							</label>
						</div>
						<div class="features_l feature1" id="PQProPopupFeaturesImg_1" style="display:block;background-image:url(<?php echo plugins_url('i/pro/feature_1.png', __FILE__);?>);">
						</div>
						<div class="features_l feature2" id="PQProPopupFeaturesImg_2" style="display:none;background-image:url(<?php echo plugins_url('i/pro/feature_2.gif', __FILE__);?>);">
						</div>
						<div class="features_l feature3" id="PQProPopupFeaturesImg_3" style="display:none;background-image:url(<?php echo plugins_url('i/pro/feature_3.png', __FILE__);?>);">
						</div>
						<div class="features_l feature4" id="PQProPopupFeaturesImg_4" style="display:none;background-image:url(<?php echo plugins_url('i/pro/feature_4.gif', __FILE__);?>);">
						</div>
						<div class="features_l feature5" id="PQProPopupFeaturesImg_5" style="display:none;background-image:url(<?php echo plugins_url('i/pro/feature_5.png', __FILE__);?>);">
						</div>
						<div class="features_l feature6" id="PQProPopupFeaturesImg_6" style="display:none;background-image:url(<?php echo plugins_url('i/pro/feature_6.gif', __FILE__);?>);">
						</div>
						<script>
						function showPROImgFeatures(val){
							document.getElementById('PQProPopupFeaturesImg_1').style.display="none";
							document.getElementById('PQProPopupFeaturesImg_2').style.display="none";
							document.getElementById('PQProPopupFeaturesImg_3').style.display="none";
							document.getElementById('PQProPopupFeaturesImg_4').style.display="none";
							document.getElementById('PQProPopupFeaturesImg_5').style.display="none";
							document.getElementById('PQProPopupFeaturesImg_6').style.display="none";
							
							document.getElementById('PQProPopupFeaturesImg_'+val).style.display="block";							
						}						
					</script>
					</div>
					<a class="pq_active_btn" onclick="document.getElementById('PQActivatePopup').style.display='block';"><?php echo $this->_dictionary[proOptionsInfo][upgrade_to_pro];?> $<?php echo (float)($this->_plugin_settings[price]['pro_12']/12);?>/mo</a>
					<a class="" href="<?php echo $this->getSettingsPageUrl();?>&act=freeTrial"><?php echo $this->_dictionary[proOptionsInfo][start_free_trial];?></a>
					<h3><?php echo $this->_dictionary[proOptionsInfo][faq_title];?></h3>
					<div class="pq_half pq_pro_mode">						
						<h4><?php echo $this->_dictionary[proOptionsInfo][start_earning];?></h4>
						<p><?php echo $this->_dictionary[proOptionsInfo][start_earning_desc];?>
						<br><a href="mailto:support@profitquery.com?subject=[PQ_ES_WP <?php echo $this->getDomain();?>] Partner program"><?php echo $this->_dictionary[proOptionsInfo][pl_email_us];?></a>
						</p>
					</div>
					<div class="pq_half pq_pro_mode">
						
						<h4><?php echo $this->_dictionary[proOptionsInfo][get_pro_for_free];?></h4>
						<p><?php echo $this->_dictionary[proOptionsInfo][get_pro_for_free_desc];?>
						<br><a href="mailto:support@profitquery.com?subject=[PQ_ES_WP <?php echo $this->getDomain();?>] Pro for free"><?php echo $this->_dictionary[proOptionsInfo][pl_email_us];?></a>
						</p>
					</div>
					<p><?php echo $this->_dictionary[proOptionsInfo][q_about_pro];?> <a href="mailto:support@profitquery.com?subject=[PQ_ES_WP <?php echo $this->getDomain();?>] About Pro"><?php echo $this->_dictionary[proOptionsInfo][pl_email_us];?></a></p>
				</div>
					
				<a class="pq_close" href="javascript:void(0)" onclick="document.getElementById('PQ_pro_info').style.display='none';"></a>
			</div>			
			<!--  END PRO OPTIONS POPUP -->
			
			<!-- NEED HELP -->
			<div class="pq_info_popup" id="PQNeedHelpPopup">
				<div>
				<h2><?php echo $this->_dictionary[needHelp][title];?></h2>
				<p><?php echo $this->_dictionary[needHelp][description];?></p>				
				<div class="pq_clear"></div>
				<a href="<?php echo $this->_plugin_support_url;?>" target="askQuestion"><?php echo $this->_dictionary[action]['continue'];?></a>
				</div>
				<a class="pq_close" href="javascript:void(0)" onclick="document.getElementById('PQNeedHelpPopup').style.display='none';"></a>
				
			</form>
			</div>
			<!-- END NEED HELP -->			
			<!-- STARS -->
			<div class="" id="PQstars" style="<?php if((int)$disableReview == 1) echo 'display:none'; else echo 'display:block';?>">
				<div>
				<h2><?php echo $this->_dictionary[review][title];?></h2>
				<p><?php echo $this->_dictionary[review][description];?></p>
				<a href="<?php echo $this->getSettingsPageUrl();?>&act=review"><?php echo $this->_dictionary[action][leave_a_review];?></a>
				
				</div>
				<a href="<?php echo $this->getSettingsPageUrl();?>&act=later"><?php echo $this->_dictionary[action][later];?></a>				
			</form>
			</div>
			<!-- END STARS -->
			<div id="View_tools_map" style="display: none;">
				
				<div>
				<label onclick="document.getElementById('PQ_desctop').style.display='inline-block',document.getElementById('PQ_mobile').style.display='none';">
					<input type="radio" name="tools_map" checked="">
					<div><p><?php echo $this->_dictionary[navigation][desktop];?></p></div>
				</label>
				<label onclick="document.getElementById('PQ_desctop').style.display='none', document.getElementById('PQ_mobile').style.display='inline-block';">
					<input type="radio" name="tools_map">
					<div><p><?php echo $this->_dictionary[navigation][mobile];?></p></div>
				</label>
				</div>
				<span>tools map</span>
				<div id="PQ_desctop" style="display: inline-block; float: none; position: relative; max-width: 100%; margin: 10% auto 0; overflow: hidden;"  onclick="document.getElementById('View_tools_map_popup').style.display='block';"><img src="<?php echo plugins_url('i/map_descktop.png', __FILE__);?>">
					<label class="bar_top"><input type="checkbox" <?php if($positionArray[desktop][BAR_TOP]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('desktop', 'BAR_TOP');">BAR TOP<div></div></label>
					<label class="bar_bottom"><input type="checkbox" <?php if($positionArray[desktop][BAR_BOTTOM]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('desktop', 'BAR_BOTTOM');">BAR BOTTOM<div></div></label>
					<label class="side_left_top"><input type="checkbox" <?php if($positionArray[desktop][SIDE_LEFT_TOP]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('desktop', 'SIDE_LEFT_TOP');">SIDE_LEFT_TOP<div></div></label>
					<label class="side_left_middle"><input type="checkbox" <?php if($positionArray[desktop][SIDE_LEFT_MIDDLE]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('desktop', 'SIDE_LEFT_MIDDLE');">SIDE_LEFT_MIDDLE<div></div></label>
					<label class="side_left_bottom"><input type="checkbox" <?php if($positionArray[desktop][SIDE_LEFT_BOTTOM]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('desktop', 'SIDE_LEFT_BOTTOM');">SIDE_LEFT_BOTTOM<div></div></label>
					<label class="side_right_top"><input type="checkbox" <?php if($positionArray[desktop][SIDE_RIGHT_TOP]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('desktop', 'SIDE_RIGHT_TOP');">SIDE_RIGHT_TOP<div></div></label>
					<label class="side_right_middle"><input type="checkbox" <?php if($positionArray[desktop][SIDE_RIGHT_MIDDLE]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('desktop', 'SIDE_RIGHT_MIDDLE');">SIDE_RIGHT_MIDDLE<div></div></label>
					<label class="side_right_bottom"><input type="checkbox" <?php if($positionArray[desktop][SIDE_RIGHT_BOTTOM]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('desktop', 'SIDE_RIGHT_BOTTOM');">SIDE_RIGHT_BOTTOM<div></div></label>
					<label class="popup_center"><input type="checkbox" <?php if($positionArray[desktop][CENTER]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('desktop', 'CENTER');">CENTER<div></div></label>
					<label class="floating_left_top"><input type="checkbox" <?php if($positionArray[desktop][FLOATING_LEFT_TOP]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('desktop', 'FLOATING_LEFT_TOP');">FLOATING_LEFT_TOP<div></div></label>
					<label class="floating_left_bottom"><input type="checkbox" <?php if($positionArray[desktop][FLOATING_LEFT_BOTTOM]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('desktop', 'FLOATING_LEFT_BOTTOM');">FLOATING_LEFT_BOTTOM<div></div></label>
					<label class="floating_right_top"><input type="checkbox" <?php if($positionArray[desktop][FLOATING_RIGHT_TOP]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('desktop', 'FLOATING_RIGHT_TOP');">FLOATING_RIGHT_TOP<div></div></label>
					<label class="floating_right_bottom"><input type="checkbox" <?php if($positionArray[desktop][FLOATING_RIGHT_BOTTOM]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('desktop', 'FLOATING_RIGHT_BOTTOM');">FLOATING_RIGHT_BOTTOM<div></div></label>
				</div>
				<div id="PQ_mobile" style="float: none; position: relative; max-width: 100%; margin: 10% auto 0; overflow: hidden; display: none;"><img src="<?php echo plugins_url('i/map_mobile.png', __FILE__);?>">
					<label class="bar_top"><input type="checkbox" <?php if($positionArray[mobile][BAR_TOP]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('mobile', 'BAR_TOP');">BAR TOP<div></div></label>
					<label class="bar_bottom"><input type="checkbox" <?php if($positionArray[mobile][BAR_BOTTOM]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('mobile', 'BAR_BOTTOM');">BAR BOTTOM<div></div></label>
					<label class="side_left_top"><input type="checkbox" <?php if($positionArray[mobile][SIDE_LEFT_TOP]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('mobile', 'SIDE_LEFT_TOP');">SIDE_LEFT_TOP<div></div></label>
					<label class="side_left_middle"><input type="checkbox" <?php if($positionArray[mobile][SIDE_LEFT_MIDDLE]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('mobile', 'SIDE_LEFT_MIDDLE');">SIDE_LEFT_MIDDLE<div></div></label>
					<label class="side_left_bottom"><input type="checkbox" <?php if($positionArray[mobile][SIDE_LEFT_BOTTOM]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('mobile', 'SIDE_LEFT_BOTTOM');">SIDE_LEFT_BOTTOM<div></div></label>
					<label class="side_right_top"><input type="checkbox" <?php if($positionArray[mobile][SIDE_RIGHT_TOP]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('mobile', 'SIDE_RIGHT_TOP');">SIDE_RIGHT_TOP<div></div></label>
					<label class="side_right_middle"><input type="checkbox" <?php if($positionArray[mobile][SIDE_RIGHT_MIDDLE]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('mobile', 'SIDE_RIGHT_MIDDLE');">SIDE_RIGHT_MIDDLE<div></div></label>
					<label class="side_right_bottom"><input type="checkbox" <?php if($positionArray[mobile][SIDE_RIGHT_BOTTOM]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('mobile', 'SIDE_RIGHT_BOTTOM');">SIDE_RIGHT_BOTTOM<div></div></label>
					<label class="popup_center"><input type="checkbox" <?php if($positionArray[mobile][CENTER]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('mobile', 'CENTER');">CENTER<div></div></label>
					<label class="floating_right_top"><input type="checkbox" <?php if($positionArray[mobile][FLOATING_TOP]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('mobile', 'FLOATING_TOP');">FLOATING_RIGHT_TOP<div></div></label>
					<label class="floating_right_bottom"><input type="checkbox" <?php if($positionArray[mobile][FLOATING_BOTTOM]) echo 'class="pq_checked"';?>  onclick="viewPositionDetail('mobile', 'FLOATING_BOTTOM');">FLOATING_RIGHT_BOTTOM<div></div></label>
				</div>
				<a class="pq_close" href="javascript:void(0)" onclick="document.getElementById('View_tools_map').style.display='none';"><?php echo $this->_dictionary[action][close];?></a>
				
				<!-- VIEW TOOLS MAP POPUP -->
				<div id="View_tools_map_popup" style="display:none;" >
					<table width="100%" cellspacing="0" style="margin:10px auto 20px; padding:0;border-spacing: 0px 5px;">
					<tbody id="VIEW_TOOLS_MAP_CONTENT">
					</tbody>
				</table>
				<a class="pq_close" href="javascript:void(0)" onclick="document.getElementById('View_tools_map_popup').style.display='none';"></a>
				</div>
				<script>
					function viewPositionDetail(type, pos){					
						var ret = '';
						var flag = false;
						var content = '';
						var ipath = '<?php echo plugins_url('i/', __FILE__);?>';
						var displayRules = 'Display Rules';
						var settingImg = '<?php echo plugins_url('i/settings_tool.png', __FILE__);?>';
						var settingUrl = '<?php echo $this->getSettingsPageUrl();?>';
						var header = 
						'<tr>'+
							'<td> </td>'+
							'<td> </td>'+
							'<td> </td>'+
							'<td> </td>'+						
							'<td> </td>'+
						'</tr>';
						for(var i in PQToolsPosition[type][pos]){
							if(typeof PQToolsPosition[type][pos][i] == 'object'){
								flag = true;
								content += 
								'<tr>'+
									'<td><img src="'+ipath+PQToolsPosition[type][pos][i].info.icon+'"></td>'+
									'<td><p>'+PQToolsPosition[type][pos][i].info.name+' '+PQToolsPosition[type][pos][i].info.type+'</p></td>'+
									'<td><p>'+PQToolsPosition[type][pos][i].eventHandler.name+' '+PQToolsPosition[type][pos][i].eventHandler.value+'</p></td>'+								
									'<td><a href="'+settingUrl+'&s_t='+PQToolsPosition[type][pos][i].code+'&step=3">'+displayRules+'</td>'+
									'<td><a href="'+settingUrl+'&s_t='+PQToolsPosition[type][pos][i].code+'&pos='+PQToolsPosition[type][pos][i].position+'"><img src="'+settingImg+'"></a></td>'+
								'</tr>';							
							}						
						}
						if(flag){
							ret = header+content;
						}else{
							ret = header+'<tr><td colspan="5"><?php echo $this->_dictionary[proOptionsInfo][empty_list];?></td></tr>';
						}
						document.getElementById('VIEW_TOOLS_MAP_CONTENT').innerHTML = ret;					
						document.getElementById('View_tools_map_popup').style.display = 'block';
					}
				</script>
				
				</div>
			<!-- SUCCESS SEND MAIL -->
			<div class="pq_info_popup" id="PQSuccessSendMail">
				<h2><?php echo $this->_dictionary[notification][send_mail];?></h2>
				<a class="pq_close" href="javascript:void(0)" onclick="document.getElementById('PQSuccessSendMail').style.display='none';"></a>
			</form>
			</div>
			<!-- END SUCCESS SEND MAIL -->
			
			<div id="PQStartFreeTrial" style="display:none">
				<h3><?php echo $this->_dictionary[notification][request_sent];?></h3>
				<p><?php echo $this->_dictionary[notification][free_trial];?></p>
				<a href="http://profitquery.com/blog/2015/11/enable-free-trial-for-wordpress/" target="_blank"><?php echo $this->_dictionary[notification][read_more];?></a>
				<a href="javascript:void(0)" onclick="document.getElementById('PQStartFreeTrial').style.display='none';">Close</a>
			</div>
			
			<?php
				if((int)$successSendEmail == 1){
					echo '<script>document.getElementById("PQSuccessSendMail").style.display="block";setTimeout(function(){document.getElementById("PQSuccessSendMail").style.display="none";},3000);</script>';
				}
			?>
			
			<!--  SETTINGS POPUP -->
			<div class="pq_info_popup" id="PQSettingsPopup">
			<form action="<?php echo $this->getSettingsPageUrl();?>" method="post">
			<input type="hidden" name="action" value="settingsSave">
				<h2><?php echo $this->_dictionary[globalSettings][title];?> <a class="pq_question" href="http://profitquery.com/blog/2015/12/aio-wordpress-plugin-settings/#main_settings_block" target="_settings_info">?</a></h2>
				<p><?php echo $this->_dictionary[globalSettings][description];?></p>
				<label><p><?php echo $this->_dictionary[globalSettings][api_key];?> <a href="http://api.profitquery.com/cms-sign-in/getApiKey/?domain=<?php echo $this->getDomain();?>&cms=wp&ae=<?php echo get_settings('admin_email');?>&redirect=<?php echo str_replace(".", "%2E", urlencode($this->getSettingsPageUrl()));?>"><?php echo $this->_dictionary[globalSettings][get_key];?></a></p><input type="text" name="settings[apiKey]" value="<?php echo stripslashes($this->_options[settings][apiKey]);?>"></label>
				<label><p><?php echo $this->_dictionary[globalSettings][pro_loader];?> <a href="<?php echo $this->getSettingsPageUrl().'&act=set_pro_loader';?>"><?php echo $this->_dictionary[globalSettings][get];?></a></p><input type="text" name="settings[pro_loader_filename]" value="<?php echo stripslashes($this->_options[settings][pro_loader_filename]);?>"></label>
				<label><p><?php echo $this->_dictionary[globalSettings][main_page];?></p><input type="text" name="settings[mainPage]" value="<?php echo stripslashes($this->_options[settings][mainPage]);?>"></label>
				<label><p><?php echo $this->_dictionary[globalSettings][admin_email];?></p><input type="text" name="settings[email]" value="<?php echo stripslashes($this->_options[settings][email]);?>"></label>
				<label><p><?php echo $this->_dictionary[globalSettings][referral_code];?></p><input type="text" name="settings[code]" value="<?php echo stripslashes($this->_options[settings][code]);?>"></label>
				<div class="pq_clear"></div>
				<label style="background-color: #F4F5F7; padding: 10px 20px; margin: 15px 0; position: relative;">
					<p><?php echo $this->_dictionary[globalSettings][use_ga];?></p><input type="checkbox" id="1" class="pq_switch" name="settings[enableGA]" <?php if($this->_options[settings][enableGA] == 'on') echo 'checked';?> /><label for="1" class="pq_switch_label"></label>
				</label>
				<label style="background-color: #F4F5F7; padding: 10px 20px; margin: 15px 0; position: relative;">
					<p><?php echo $this->_dictionary[globalSettings][right_to_left];?></p><input type="checkbox" id="PQ_rtl" class="pq_switch" name="settings[from_right_to_left]" <?php if($this->_options[settings][from_right_to_left] == 'on') echo 'checked';?> /><label for="PQ_rtl" class="pq_switch_label"></label>
				</label>
				<input type="submit" name="sbmt" value="<?php echo $this->_dictionary[action][save];?>">
				<a class="pq_close" href="javascript:void(0)" onclick="document.getElementById('PQSettingsPopup').style.display='none';"></a>
			</form>
			</div>
			<!--  END SETTINGS POPUP -->
			
	</div>
	<?php 
		if($_GET[act] == 'freeTrial'){
			echo '		
			<script>
				
				document.getElementById("PQStartFreeTrial").style.display="block";
				setTimeout(function(){
					try{
						if(document.getElementById("PQStartFreeTrial")){
							document.getElementById("PQStartFreeTrial").style.display="none";
						}
					}catch(err){};
				}, 10000);
			</script>
			<iframe src="//api.profitquery.com/getTrial/aio_5/?domain='.$this->getDomain().'&email='.get_settings('admin_email').'" style="width: 0px; height: 0px; position: fixed; bottom: -2px;display:none;"></iframe>
			';
		}
		
		}else{
			?>
			<form action="http://api.profitquery.com/sign-in/" method="post" target="PQSignIn">
			<input type="hidden" name="cms" value="wp">
			<input type="hidden" name="domain" value="<?php echo $this->getDomain();?>">
			<input type="hidden" name="email" value="<?php echo get_settings('admin_email');?>">
			<input type="hidden" name="redirectUrl" value="<?php echo $this->getSettingsPageUrl();?>">
			<div id="pq_agree">
				<div>
				<img src="<?php echo plugins_url('i/themes.jpg', __FILE__);?>">
				<h1>Welcome to Profitquery</h1>
				
				<p>New tools, more themes, more animation effect, more design and setting options, subscribe providers, special features, new dashboard to make it easy to promote your Wordpress blog, to boost website traffic, follower, shares, feedbacks.</p>
				<img src="<?php echo plugins_url('i/tools.png', __FILE__);?>">
				<p>For start to use Profitquery tools you need to sign-in on Profitquery website and generate Api Key</p>
				</div>
			<input type="submit" name="i_agree" value="Sign-in">
			<p class="pq_policy">By clicking "Sign-in" you agree to Profitquery's <a href="http://profitquery.com/terms.html" target="_blank">Terms of Service</a> and <a href="http://profitquery.com/privacy.html" target="_blank">Privacy Policy.</a></p>
			</div>
			</form>
			<?php
		}
	}
	
	function getFullDomain()
    {
        $url     = get_option('siteurl');
        $urlobj  = parse_url($url);
        $domain  = $urlobj['host'];        
        return $domain;
    }
	/**
     * Get the wp domain
     * 
     * @return string
     */
    function getDomain()
    {
        $url     = get_option('siteurl');
        $urlobj  = parse_url($url);
        $domain  = $urlobj['host'];
        $domain  = str_replace('www.', '', $domain);
        return $domain;
    }
}