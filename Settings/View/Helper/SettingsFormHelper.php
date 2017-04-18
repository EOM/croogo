<?php

App::uses('AppHelper', 'View/Helper');

/**
 * SettingForms Helper
 *
 * @category Helper
 * @package  Croogo.Settings.View.Helper
 * @version  1.0
 * @author   Rachman Chavik <rchavik@xintesa.com>
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.croogo.org
 */
class SettingsFormHelper extends AppHelper {

	public $helpers = array(
		'Form' => array(
			'className' => 'Croogo.CroogoForm',
		),
	);

/**
 * _inputCheckbox
 *
 * @see SettingsFormHelper::input()
 */
	protected function _inputCheckbox($setting, $label, $i) {
		$tooltip = array(
			'data-trigger' => 'hover',
			'data-placement' => 'right',
			'data-title' => $setting['Setting']['description'],
		);
		if ($setting['Setting']['value'] == 1) {
			$output = $this->Form->input("Setting.$i.value", array(
				'type' => $setting['Setting']['input_type'],
				'checked' => 'checked',
				'tooltip' => $tooltip,
				'label' => $label
			));
		} else {
			$output = $this->Form->input("Setting.$i.value", array(
				'type' => $setting['Setting']['input_type'],
				'tooltip' => $tooltip,
				'label' => $label
			));
		}
		return $output;
	}

/**
 * Renders input setting according to its type
 *
 * @param array $setting setting data
 * @param string $label Input label
 * @param integer $i index
 * @return string
 */
	public function input($setting, $label, $i) {
		$output = '';
		$inputType = ($setting['Setting']['input_type'] != null) ? $setting['Setting']['input_type'] : 'text';
		if ($setting['Setting']['input_type'] == 'multiple') {
			$multiple = true;
			if (isset($setting['Params']['multiple'])) {
				$multiple = $setting['Params']['multiple'];
			};
			$selected = json_decode($setting['Setting']['value']);
			$options = json_decode($setting['Params']['options'], true);
			$output = $this->Form->input("Setting.$i.values", array(
				'label' => $setting['Setting']['title'],
				'multiple' => $multiple,
				'options' => $options,
				'selected' => $selected,
			));
		} elseif ($setting['Setting']['input_type'] == 'checkbox') {
			$output = $this->_inputCheckbox($setting, $label, $i);
		} elseif ($setting['Setting']['input_type'] == 'radio') {
			$value = $setting['Setting']['value'];
			$options = json_decode($setting['Params']['options'], true);
			$output = $this->Form->input("Setting.$i.value", array(
				'legend' => $setting['Setting']['title'],
				'type' => 'radio',
				'options' => $options,
				'value' => $value,
			));
		} elseif (is_int(array_search('select-function', (array)explode(':', $inputType)[0])) === true) {
		    
		    /* function tzlist(){
		        $l = array();
		        foreach(timezone_abbreviations_list() as $timezone){
                         foreach($timezone as $val){
                                if(isset($val['timezone_id'])){
                                        $l[$val['timezone_id']]=$val['timezone_id'];
                                }
                            }
                        }
		        return $l;
		    }*/
	
            	    $func = explode(':', $inputType)[0];
            	    $func = str_ireplace($func.':','',$inputType);
		    if(function_exists($func)){
		        $options = $func();
		    }else{
		        $options = array();
		    }
		    $output = $this->Form->input("Setting.$i.value", array(
			'type' => 'select',
			'options' => $options,
			'value' => $setting['Setting']['value'],
			'help' => $setting['Setting']['description'],
			'label' => $label,
		    ));
		} else {
			$output = $this->Form->input("Setting.$i.value", array(
				'type' => $inputType,
				'value' => $setting['Setting']['value'],
				'help' => $setting['Setting']['description'],
				'label' => $label,
			));
		}
		return $output;
	}

}
