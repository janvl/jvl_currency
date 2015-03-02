<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
/**
* JVL Currency Class v1.4
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
  		'version'          => '1.4'
	);

	var $addon_name = 'jvl_currency';
	
	var $default_values = array(
		'currency'			=> 'euro',
		'numbers_only'		=> FALSE
	);
	
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		
		// Using __construct to handle the lack of native update method
		
		$query = $this->EE->db->select('fieldtype_id, version')
			                      ->where('name', $this->addon_name)
			                      ->get('fieldtypes');

		if ($query->num_rows())
		{
			// manually call update(), see notes in method
			$update = $this->update($query->row('version'));
			
			if ($update)
			{
				// update version number
				$this->EE->db->where('name', $this->addon_name)
					->update('exp_fieldtypes', array('version' => $this->info['version']));
			}
		}
	}
	
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
		return $this->default_values;
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
	function display_field($data, $cell = FALSE)
	{
		$this->EE->lang->loadfile('jvl_currency');
		
		$this->EE->cp->add_to_head('<script type="text/javascript" src="' . URL_THIRD_THEMES . '/jvl_currency/js/jvl_currency.js"></script>');
		$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="' . URL_THIRD_THEMES . '/jvl_currency/css/jvl_currency.css" />');
		
		if(!$cell){
			$input = form_input(array(
				'name' => $this->field_name,
				'value' => $data,
				'type' => 'text',
				'size' => '10',
				'maxlength' => '10'
			));
		}else{			
			$input = form_input(array(
				'name' => $this->cell_name,
				'value' => $data,
				'type' => 'text',
				'size' => '10',
				'maxlength' => '10'
			));
		}
		
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
	* Save
	* 
	*/	
	function save($data)
	{
		if ($this->settings['numbers_only'])
		{
			// strip all non numeric characters, leave periods
			$data = preg_replace('/[^0-9.]*/', '', $data);
		}
		
		return $data;
	}
	
	// --------------------------------------------------------------------
	
	/**
	* Display Settings
	* 
	*/	
	function display_settings($data, $cell = FALSE)
	{		
		$this->EE->lang->loadfile('jvl_currency');
		$currency		= isset($data['currency'])		? $data['currency']		: $this->settings['currency'];
		$numbers_only	= isset($data['numbers_only'])	? $data['numbers_only']	: $this->settings['numbers_only'];
		
		$options = array(
          'euro'	=> 'Euro - &euro;',
          'dollar'	=> 'Dollar - $',
          'pound'	=> 'Pound - &pound;',
          'yen'		=> 'Yen - &yen;',
        );
		
		if(!$cell){
			$this->EE->table->add_row(
				lang('currency'),
				form_dropdown('currency', $options, $currency)
			);
			$this->EE->table->add_row(
				$this->EE->lang->line('numbers_only'),
				form_checkbox('numbers_only', 'y', $numbers_only)
			);
		}else{
			return array(
				array(
					lang('currency', 'currency'),
					form_dropdown('currency', $options, $currency)
				)
			);
		}
	}
	
	// --------------------------------------------------------------------
	
	/**
	* Save Settings
	* 
	*/	
	function save_settings($data)
	{

		return array(
			'currency'		=> $this->EE->input->post('currency'),
			'numbers_only'	=> $this->EE->input->post('numbers_only')
		);
		
	}

	// --------------------------------------------------------------------
	
	/**
	* Update
	* 
	* https://twitter.com/elliotlewis/status/572424599578783744
	* If fieldtype doesn't have global settings, can't run an update!
	* Update only triggered by visiting settings in CP, EE 2.9.2 line 247
	* /system/expressionengine/controllers/cp/addons_fieldtypes.php
	*/

	function update($from)
	{
		if ($from)
		{

			if (version_compare($from, '1.4', '<'))
			{
				// as already installed will only have 'currency' setting
				// read in current field settings, add 'numbers_only', re-save
				
				// update global fieldtype settings
				$field = $this->EE->db->select('fieldtype_id, settings')
					->where('name', $this->addon_name)
					->get('fieldtypes');
						
				if ($field->num_rows() > 0)
				{
					$settings		= unserialize( base64_decode( $field->row('settings') ) );
					$field_settings	= array_merge($this->default_values, $settings);

					$data = array(
						'settings'	=> base64_encode( serialize( $field_settings ) )
					);

					$r = $this->EE->db->where('fieldtype_id', $field->row('fieldtype_id'))
						->update('fieldtypes', $data);
				}

				// update channel field settings
				$fields = $this->EE->db->select('field_id, field_settings')
					->where('field_type', $this->addon_name)
					->get('channel_fields');
						
				foreach ($fields->result() as $field)
				{
					$settings		= unserialize( base64_decode( $field->field_settings ) );
					$field_settings	= array_merge($this->default_values, $settings);

					$data = array(
						'field_settings'	=> base64_encode( serialize( $field_settings ) )
					);

					$r = $this->EE->db->where('field_id', $field->field_id)
						->update('channel_fields', $data);
				}
				
				return TRUE;
			}

		}

		return FALSE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	* Matrix Compatibility
	* 
	*/	
	
	function display_cell($cell_data){
		return $this->display_field($cell_data, TRUE);
	}
	
	function display_cell_settings( $cell_data )
    {
    	return $this->display_settings($cell_data, TRUE);
    }
	
	function save_cell_settings( $cell_data )
	{
		return $cell_data;
    }
    
}
/* End of file ft.jvl_currency.php */
/* Location: ./system/expressionengine/third_party/jvl_currency/ft.jvl_currency.php */
