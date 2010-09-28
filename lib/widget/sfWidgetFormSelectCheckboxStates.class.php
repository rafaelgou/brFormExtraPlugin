<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormSelectCheckboxStates represents an array of checkboxes of states.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Rafael Goulart <rafaelgou@rgou.net>
 * @version    GIT
 */

class  sfWidgetFormSelectCheckboxStates extends sfWidgetFormSelectCheckbox
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * label_separator: The separator to use between the input checkbox and the label
   *  * class:           The class to use for the main <ul> tag
   *  * separator:       The separator to use between each input checkbox
   *  * formatter:       A callable to call to format the checkbox choices
   *                     The formatter callable receives the widget and the array of inputs as arguments
   *  * template:        The template to use when grouping option in groups (%group% %options%)
   *
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormChoiceBase
   */
  protected function configure($options = array(), $attributes = array())
  {
    parent::configure($options, $attributes);

    $this->addOption('choices', self::getStatesForWidget());
    $this->addOption('class', '');
    $this->addOption('all_checkbox', self::getAllCheckBox());
    $this->addOption('label_separator', '&nbsp;');
    $this->addOption('separator', "\n");
    $this->addOption('formatter', array($this, 'formatter'));
    $this->addOption('template', '%group% %options%');
  }

  protected function formatChoices($name, $value, $choices, $attributes)
  {

    $regions = self::getRegions();

    $inputs = array();

    $inputs['formName'] = $this->generateId($name);

    foreach ($regions as $region => $regValues) {

      $inputs[$region]['label'] = $regValues['label'];
      $inputs[$region]['id']    = $this->generateId($name,$region);

      foreach ($regValues['states'] as $key => $option)
      {
        $baseAttributes = array(
          'name'  => $name,
          'type'  => 'checkbox',
          'value' => self::escapeOnce($key),
          'id'    => $id = $this->generateId($name, self::escapeOnce($key)),
        );

        $baseAttributes['onclick'] = "javascript: ChoiceStateStateSet(this,'".$inputs[$region]['id']."','".$inputs['formName']."_allStates')";

        if ((is_array($value) && in_array(strval($key), $value)) || strval($key) == strval($value))
        {
          $baseAttributes['checked'] = 'checked';
        }

        $inputs[$region]['inputs'][$id] = array(
          'input' => $this->renderTag('input', array_merge($baseAttributes, $attributes)),
          'label' => $this->renderContentTag('label', $option, array('for' => $id)),
        );
      }

    }

    return call_user_func($this->getOption('formatter'), $this, $inputs);
  }

  public function formatter($widget, $inputs)
  {
    $states = self::getRegionForStates();

    $fieldsets = '';

    foreach ($inputs as $region => $regValues) {

      if ($region == 'formName') continue;

      $rows = array();
      foreach ($regValues['inputs'] as $input)
      {
        $rows[] = $this->renderContentTag('li', $input['input'].$this->getOption('label_separator').$input['label']);
      }

      $inputLegend = $this->renderTag('input',
                                      array(
                                            'name'  => $regValues['id'],
                                            'type'  => 'checkbox',
                                            'value' => $region,
                                            'id'    => $regValues['id'],
                                            'onclick' => "javascript: ChoiceStateRegionSet(this)"
                                            )
                                    );

      $legend = $this->renderContentTag('legend', $inputLegend . $this->getOption('label_separator') . $regValues['label']);

      $inputRegions = $this->renderContentTag('ul', implode($this->getOption('separator'), $rows), array('class' => $this->getOption('class'))) . "\n";

      $fieldsets .= $this->renderContentTag('div',
                                            $this->renderContentTag('fieldset', $legend . $inputRegions),
                                            array(
                                                  'id' => 'fieldset_' . $regValues['id'],
                                                  'class' => 'sfWidgetFormChoiceState')
                                                  );

    }

    // div only for break float
    $fieldsets .= $this->renderContentTag('div','&nbsp;', array('style' => 'clear:both;'));

    // Check for All Check Box
    if($this->getOption('all_checkbox')) {
      $fieldsets =
      $this->renderTag('input', array("type"=>"checkbox",
                                      "name"=>"allStates",
                                      "id"=>$inputs['formName']."_allStates",
                                      "onclick"=>"javascript:ChoiceStateAllSet(this,'".'div_' . $inputs['formName']."')")) .
      $this->renderContentTag('label', $this->getOption('all_checkbox'), array('for' => $inputs['formName']."_allStates")) .
      $fieldsets;
    }

      $fieldsets = $this->renderContentTag('div',
                                           $fieldsets,
                                            array(
                                                  'id' => 'div_' . $inputs['formName'],
                                                  'class' => 'sfWidgetFormChoiceState')
                                                  );

    return ($fieldsets== '') ? '' : $fieldsets;

  }

  /**
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */
  public function getStylesheets()
  {
    return array('/brFormExtraPlugin/css/main.css' => 'all');
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */

  public function getJavascripts()
  {
    // Adapted from http://bonrouge.com/~check_all
    return array('/brFormExtraPlugin/js/main.js');
  }

  /**
   * Get States from config file
   * returns a array for validation
   */
  static function getStatesForValidate ()
  {
    $config = sfYaml::load(sfConfig::get('sf_plugins_dir').'/brFormExtraPlugin/config/states.yml');
    $config = $config['regions'];
    $states = array();
    foreach ($config as $region)
    {
      foreach ($region['states'] as $state => $label)
      {
        $states[$state] = $state;
      }
    }
    return $states;
  }

  /**
   * Get Separator from config file
   * returns string
   */
  static function getSeparator ()
  {
    $config = sfYaml::load(sfConfig::get('sf_plugins_dir').'/brFormExtraPlugin/config/states.yml');
    return $config['separator'];
  }

  /**
   * Get States from config file
   * returns a array for widget
   */
  static function getStatesForWidget ()
  {
    $config = sfYaml::load(sfConfig::get('sf_plugins_dir').'/brFormExtraPlugin/config/states.yml');
    $config = $config['regions'];
    $states = array();
    foreach ($config as $region) {
        foreach ($region['states'] as $state => $label) {
          $states[$state] = $label;
      }
    }
    asort($states);
    return $states;
  }

  /**
   * Get States from config file
   * returns a array for widget
   */
  static function getStatesAbbrForWidget ()
  {
    $config = sfYaml::load(sfConfig::get('sf_plugins_dir').'/brFormExtraPlugin/config/states.yml');
    $config = $config['regions'];
    $states = array();
    foreach ($config as $region) {
        foreach ($region['states'] as $state => $label) {
          $states[$state] = $state;
      }
    }
    asort($states);
    return $states;
  }

  /**
   * Get States from config file
   * returns a array with [state] = region
   */
  static function getRegionForStates ()
  {
    $config = sfYaml::load(sfConfig::get('sf_plugins_dir').'/brFormExtraPlugin/config/states.yml');
    $config = $config['regions'];
    $states = array();
    foreach ($config as $region) {
        foreach ($region['states'] as $state => $label) {
          $states[$state] = $region;
      }
    }
    return $states;
  }

  /**
   * Get Regions from config file
   * returns a array with regions
   */
  static function getRegions ()
  {
    $config = sfYaml::load(sfConfig::get('sf_plugins_dir').'/brFormExtraPlugin/config/states.yml');
    return $config['regions'];
  }

  /**
   * Return string imploded value from regions
   *
   * @param array $value    array with regions to be implode
   */
  static function getImplodedValue ($value)
  {
    return (is_array($value)) ? implode(self::getSeparator(),$value) : $value;
  }

  /**
   * Return array exploded value from regions
   *
   * @param string $value     string with regions to be explode by separator
   */
  static function getExplodedValue ($regions)
  {
    $arrayRegiao = explode (self::getSeparator(),$regions);
    foreach ($arrayRegiao as $reg) {
      $regiao[$reg] = $reg;
    }
    return $regiao;
  }

  /**
   * Get AllCheckBox from config file
   * returns boolean
   */
  static function getAllCheckBox ()
  {
    $config = sfYaml::load(sfConfig::get('sf_plugins_dir').'/brFormExtraPlugin/config/states.yml');
    return $config['all_checkbox'];
  }

  /**
   * Get AllCheckBoxLabel from config file
   * returns string
   */
  static function getAllCheckBoxLabel ()
  {
    $config = sfYaml::load(sfConfig::get('sf_plugins_dir').'/brFormExtraPlugin/config/states.yml');
    return $config['allcheckboxlabel'];
  }

}
