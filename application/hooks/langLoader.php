<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class LangLoader{
	
	function initialize() {
		$ci =& get_instance();
		$language = $ci->session->userdata('language');
		$language = (!empty($language))?$language:"english";
		
		$ci->config->set_item('language',$language);
        /*---------load language files--------------*/

        //$path = $ci->config->item('Document_Root_Path').'application/language/'.$language.'/';

        //if (file_exists($path.'form_validation_lang.php')) { $ci->lang->load('form_validation', $language);}
        //if (file_exists($path.'language_lang.php')) { $ci->lang->load('language', $language);}

        
        $path = $ci->config->item('Document_Root_Path').'application/language/'.$language.'/';
		if (file_exists($path.'breadcrumb_lang.php')) { $ci->lang->load('breadcrumb', $language); }
        if (file_exists($path.'title_lang.php')) { $ci->lang->load('title', $language); }
        if (file_exists($path.'leftsidebar_lang.php')) { $ci->lang->load('leftsidebar', $language);}
        if (file_exists($path.'other_lang.php')) { $ci->lang->load('other', $language);}
        if (file_exists($path.'tooltip_lang.php')) { $ci->lang->load('tooltip', $language);}
        if (file_exists($path.'language_lang.php')) { $ci->lang->load('language', $language);}

    }

}

?>