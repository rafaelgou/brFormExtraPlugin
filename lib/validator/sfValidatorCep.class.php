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

    // Se configurado para busca local, então realiza consulta
    if (sfConfig::get('app_br_cep_local_search'))
    {

      $cep_record = Doctrine::getTable('CEP_Brazil')
                      ->createQuery('c')
                      ->addWhere('cep = ?',$clean)
                      ->fetchOne();

      if ( ! $cep_record)
      {
        throw new sfValidatorError($this, 'invalid');
      }

    // A busca é remota, procurando configurações
    } else {

      $cep_search = (strlen($clean) == 8)
                    ? $cep_search = substr($clean, 0, 5) . '-' . substr($clean, 5, 3)
                    : $clean;
      // Realizando requisição
      $url = sfConfig::get('app_br_cep_remote_url') . '?' .
             sfConfig::get('app_br_cep_remote_query') .
             $cep_search;

      $content = file_get_contents($url);

      $remote_fields = array_flip(sfConfig::get('app_br_cep_remote_fields'));

      switch(sfConfig::get('app_br_cep_format'))
      {
        case 'republicavirtual':

          if (! strlen($content))
          {
            throw new sfValidatorError($this, 'invalid');
          }
          break;

        case 'ceplivre':

          $xml = new SimpleXMLElement($content);

          foreach ($xml->cep[0] as $key => $value)
          {
            if ($key == 'sucesso' )
            {
              $v = each($value[0]);
              if ($v['value'] == '0' ) throw new sfValidatorError($this, 'invalid');
            }
          }
          break;
      }

    } // if (sfConfig::get('app_br_cep_local_search'))

    if ($this->getOption('formated'))
    {
      return $this->formatCEP($clean);
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
