<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfWidgetFormChoice represents a choice widget.
 *
 * @package    symfony
 * @subpackage widget
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfWidgetFormChoice.class.php 23994 2009-11-15 22:55:24Z bschussek $
 */
class sfWidgetFormChoiceUFBR extends sfWidgetFormChoice
{
  /**
   * Constructor.
   *
   * Available options:
   *
   *  * add_empty:        Whether to add a first empty value or not (false by default)
   *                      If the option is not a Boolean, the value will be used as the text value
   *  * multiple:         true if the select tag must allow multiple selections
   *  * expanded:         true to display an expanded widget
   *                        if expanded is false, then the widget will be a select
   *                        if expanded is true and multiple is false, then the widget will be a list of radio
   *                        if expanded is true and multiple is true, then the widget will be a list of checkbox
   *  * renderer_class:   The class to use instead of the default ones
   *  * renderer_options: The options to pass to the renderer constructor
   *  * renderer:         A renderer widget (overrides the expanded and renderer_options options)
   *                      The choices option must be: new sfCallable($thisWidgetInstance, 'getChoices')
   * @param array $options     An array of options
   * @param array $attributes  An array of default HTML attributes
   *
   * @see sfWidgetFormChoiceBase
   */
  protected function configure($options = array(), $attributes = array())
  {

    parent::configure($options, $attributes);

    $this->addOption('multiple', false);
    $this->addOption('expanded', false);
    $this->addOption('renderer_class', false);
    $this->addOption('renderer_options', array());
    $this->addOption('renderer', false);
    $this->addOption('add_empty', false);
    $this->addOption('type','sigla');

    switch ($this->getOption('type'))
    {
      case 'nome':
        $this->addOption('choices', $this->getUFNome());
        break;

      case 'sigla+nome':
        $this->addOption('choices', $this->getUFNomeSigla());
        break;

      case 'sigla':
      default:
        $this->addOption('choices', $this->getUFSigla());
        break;
    }
    
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if (false !== $this->getOption('add_empty'))
    {
      $choices[''] = (true === $this->getOption('add_empty')) ? '' : $this->translate($this->getOption('add_empty'));
      $this->setOption('choices', array_merge($choices, $this->getOption('choices')));
    }

    return parent::render($name, $value, $attributes, $errors);
  }

  public function getUFCompleto ()
  {
  	return array(
      "AC"=>"Acre",
      "AL"=>"Alagoas",
      "AP"=>"Amapá",
      "AM"=>"Amazonas",
      "BA"=>"Bahia",
      "CE"=>"Ceará",
      "DF"=>"Distrito Federal",
      "ES"=>"Espírito Santo",
      "GO"=>"Goiás",
      "MA"=>"Maranhão",
      "MG"=>"Minas Gerais",
      "MS"=>"Mato Grosso do Sul",
      "MT"=>"Mato Grosso",
      "PA"=>"Pará",
      "PB"=>"Paraíba",
      "PR"=>"Paraná;",
      "PE"=>"Pernambuco",
      "PI"=>"Piauí",
      "RJ"=>"Rio de Janeiro",
      "RN"=>"Rio Grande do Norte",
      "RS"=>"Rio Grande do Sul",
      "RO"=>"Rondônia",
      "RR"=>"Roraima",
      "SP"=>"São Paulo",
      "SC"=>"Santa Catarina",
      "SE"=>"Sergipe",
      "TO"=>"Tocantins",
      );
  }

  public function getUFSigla()
  {
    foreach ($this->getUfCompleto() as $key => $value)
    {
      $ufs[$key] = $key;
    }
    return $ufs;
  }

  public function getUFNome()
  {
    return $this->getUFCompleto();
  }

  public function getUFNomeSigla()
  {
    foreach ($this->getUfCompleto() as $key => $value)
    {
      $ufs[$key] = "$key - $value";
    }
    return $ufs;
  }

}
