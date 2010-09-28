<?php
/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * rgValidatorChoiceStates validates a list of states.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Rafael Goulart <rafaelgou@rgou.net>
 * @version    GIT
 */
class sfValidatorChoiceStates extends sfValidatorChoice
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * choices:  An array of expected values (required)
   *  * multiple: true if the select tag must allow multiple selections
   *  * min:      The minimum number of values that need to be selected (this option is only active if multiple is true)
   *  * max:      The maximum number of values that need to be selected (this option is only active if multiple is true)
   *
   * @param array $options    An array of options
   * @param array $messages   An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addOption('choices', $this->getStatesForValidate());
    $this->addOption('multiple', true);
    $this->addOption('min');
    $this->addOption('max');
    //$this->addMessage('min', 'At least %min% values must be selected (%count% values selected).');
    $this->addMessage('min', 'Ao menos %min% valor(es) devem ser selecionado(s) (%count% valor(es) selecionado(s)).');
    //$this->addMessage('max', 'Up to %min% values must be selected (%count% values selected).');
    $this->addMessage('max', 'No máximo %max% valor(es) devem ser selecionado(s) (%count% valor(es) selecionado(s)).');
    $this->setMessage('invalid', 'Estado/UF Inválido');
    $this->setMessage('required', 'Estado/UF Obrigatório');

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
    return $config['allcheckbox'];
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