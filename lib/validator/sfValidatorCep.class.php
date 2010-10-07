<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorCep validates a CEP (Brazilian ZIP Code)
 * Accepts and return values formated/non-formated
 *
 * @package    symfony
 * @subpackage validator
 * @author     Rafael Goulart <rafaelgou@rgou.net>
 */
class sfValidatorCep extends sfValidatorString
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * formated: If true "clean" method returns a formated value, i.e. 000.000.000-00 (default: false)
   *              Use to store formated value in DB
   *
   * @param array $options   An array of options
   * @param array $messages  An array of error messages
   *
   * @see sfValidatorBase
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->setMessage('invalid', 'CEP Inválido');
    $this->setMessage('required', 'CEP Obrigatório');
    $this->addOption('formated', false);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $clean = $this->valueClean( (string) $value );

    if (false)
    {
      throw new sfValidatorError($this, 'invalid');
    } else {
      return $clean;
    }
    
  }

  /**
   * valueClean
   * Retira caracteres especiais
   * @param $value string
   * @author Rafael Goulart <rafaelgou@rgou.net>
   */
  protected function valueClean ($value)
  {
    $value = str_replace ('-','',$value);
    return $value;
  }

  /**
   * formatCEP
   * Retorna CEP Formatado
   * @param $value string
   * @author Rafael Goulart <rafaelgou@rgou.net>
   */
  protected function formatCEP ($value)
  {
    return ($this->getOption('formated'))
           ? substr($value, 0, 5) . '-' . substr($value, 5, 2)
           : $value;
  }

}
