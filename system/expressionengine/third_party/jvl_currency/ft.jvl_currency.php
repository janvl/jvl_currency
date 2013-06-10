<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
* JVL Currency Class v1.2
*
* @package   JVL Currency
* @author    Jan Van Lysebettens <janvanlysebettens@gmail.com>
* @copyright Copyright (c) 2012 Jan Van Lysebettens
*/

class jvl_currency_ft extends EE_Fieldtype {

	/**
	* Fieldtype Info
	* @var array
	*/
	var $info = array(
  		'name'             => 'JVL Currency',
  		'version'          => '1.2.1'
	);

	var $addon_name = 'jvl_currency';
	
	// --------------------------------------------------------------------
	
	/**
	* Install Fieldtype
	*
	* @access	public
	* @return	default global settings
	*
	*/
	function install()
	{
		// can return an array of global variables
		return array(
			'currency'	=> 'euro'
		);
	}
	
	// --------------------------------------------------------------------
	
	/**
	* Uninstall Fieldtype
	* 
	*/
	function uninstall()
	{
		return TRUE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	* Display Fieldtype
	* 
	*/	
	function display_field($data)
	{		
		$this->EE->lang->loadfile('jvl_currency');
		
		$this->EE->cp->add_to_head('<script type="text/javascript" src="' . URL_THIRD_THEMES . '/jvl_currency/js/jvl_currency.js"></script>');
		$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="' . URL_THIRD_THEMES . '/jvl_currency/css/jvl_currency.css" />');
		
		$input = form_input(array(
			'name' => $this->field_name,
			'value' => $data,
			'type' => 'text',
			'size' => '10',
			'maxlength' => '10'
		));
		
		$currency = "&#36;";
		
		switch ($this->settings['currency']){
			case "euro":
		        $currency = "&euro;";
		        break;
		    case "dollar":
		        $currency = "&#36;";
		        break;
		    case "pound":
		        $currency = "&pound;";
		        break;	
		    case "yen":
		        $currency = "&yen;";
		        break;		
		}
				
		return "<div class='jvl_currency'>" . "<span>" . $currency . "</span>" . $input ."</div>";

	}

	
	// --------------------------------------------------------------------
	
	/**
	* Render Field
	* 
	*/	
	function replace_tag($data, $params = array(), $tagdata = FALSE)
	{
		$currency = "&#36;";
		
		switch ($this->settings['currency']){
			case "euro":
		        $currency = "&euro;";
		        break;
		    case "dollar":
		        $currency = "&#36;";
		        break;
		    case "pound":
		        $currency = "&pound;";
		        break;	
		    case "yen":
		        $currency = "&yen;";
		        break;		
		}
		
		if(count($params) > 0)
		{
			array_key_exists('decimals', $params)		? $decimals = $params['decimals']			: $decimals = 0;
			array_key_exists('dec_point', $params)		? $dec_point = $params['dec_point']			: $dec_point = '.';
			array_key_exists('thousands_sep', $params)	? $thousands_sep = $params['thousands_sep']	: $thousands_sep = '';
			return $currency . number_format ($data, $decimals, $dec_point, $thousands_sep);
		}
		else{
		    return $currency . $data;
		}
	}
	
	function replace_value($data, $params = array(), $tagdata = FALSE)
	{
	    return $data;
	}
	
	function replace_currency($data, $params = array(), $tagdata = FALSE)
	{
		$currency = "&#36;";
		
		switch ($this->settings['currency']){
			case "euro":
		        $currency = "&euro;";
		        break;
		    case "dollar":
		        $currency = "&#36;";
		        break;
		    case "pound":
		        $currency = "&pound;";
		        break;	
		    case "yen":
		        $currency = "&yen;";
		        break;		
		}
		
	    return $currency;
	}
	
	// --------------------------------------------------------------------
	
	/**
	* Display Settings
	* 
	*/	
	function display_settings($data)
	{
		$this->EE->lang->loadfile('jvl_currency');
		$currency = isset($data['currency']) ? $data['currency'] : $this->settings['currency'];
		
		$options = array(
          'euro'   => 'Euro - &euro;',
          'dollar' => 'Dollar - $',
          'pound'   => 'Pound - &pound;',
          'yen'    => 'Yen - &yen;',
        );
						
		$this->EE->table->add_row(
			lang('currency', 'currency'),
			form_dropdown('currency',$options, $currency)
		);
	}
	
	// --------------------------------------------------------------------
	
	/**
	* Save Settings
	* 
	*/	
	function save_settings($data)
	{
		return array(
			'currency'	=> $this->EE->input->post('currency')
		);
		
	}
}
/* End of file ft.jvl_currency.php */
/* Location: ./system/expressionengine/third_party/jvl_currency/ft.jvl_currency.php */