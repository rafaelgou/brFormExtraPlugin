<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfValidatorCpfCnpj validates a CPF (Brazilian individual taxpayer identification)
 * or CNPJ (Brazilian taxpayer identification)
 * Accepts and return values formated/non-formated
 *
 * @package    symfony
 * @subpackage validator
 * @author     Rafael Goulart <rafaelgou@rgou.net>
 */
class sfValidatorCpfCnpj extends sfValidatorString
{
  /**
   * Configures the current validator.
   *
   * Available options:
   *
   *  * type: Type of validation (cpfcnpj, cpf, cnpj - default: cpfcnpj)
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
    $this->setMessage('invalid', 'CPF/CNPJ Inválido');
    $this->setMessage('required', 'CPF/CNPJ Obrigatório');
    $this->addOption('type', 'cpfcnpj');
    $this->addOption('formated', false);
  }

  /**
   * @see sfValidatorBase
   */
  protected function doClean($value)
  {
    $clean = (string) $value;

    $length = function_exists('mb_strlen') ? mb_strlen($clean, $this->getCharset()) : strlen($clean);

    switch ($this->getOption('type')) {

      case 'cnpj':
        if (!$this->checkCNPJ($value)) throw new sfValidatorError($this, 'invalid');
        break;

      case 'cpf':
        if (!$this->checkCPF($value)) throw new sfValidatorError($this, 'invalid');
        break;

      case 'cpfcnpj':
      default:
        if (!($this->checkCPF($value) || $this->checkCNPJ($value))) throw new sfValidatorError($this, 'invalid');
        break;

    }
    if ($this->getOption('formated'))
    {
      return $this->formatCPFCNPJ($clean);
    } else {
      return $clean;
    }
    
  }

  /**
   * checkCPF
   * Baseado em http://www.vivaolinux.com.br/script/Validacao-de-CPF-e-CNPJ/
   * Algoritmo em http://www.geradorcpf.com/algoritmo_do_cpf.htm
   * @param $cpf string
   * @author Rafael Goulart <rafaelgou@rgou.net>
   */
  protected function checkCPF($cpf) {

    // Limpando caracteres especiais
    $cpf = $this->valueClean($cpf);

    // Quantidade mínima de caracteres ou erro
    if (strlen($cpf) <> 11) return false;

    // Primeiro dígito
    $soma = 0;
    for ($i = 0; $i < 9; $i++) {
       $soma += ((10-$i) * $cpf[$i]);
    }
    $d1 = 11 - ($soma % 11);
    if ($d1 >= 10) $d1 = 0;

    // Segundo Dígito
    $soma = 0;
    for ($i = 0; $i < 10; $i++) {
       $soma += ((11-$i) * $cpf[$i]);
    }
    $d2 = 11 - ($soma % 11);
    if ($d2 >= 10) $d2 = 0;

    if ($d1 == $cpf[9] && $d2 == $cpf[10]) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * checkCNPJ
   * Baseado em http://www.vivaolinux.com.br/script/Validacao-de-CPF-e-CNPJ/
   * Algoritmo em http://www.geradorcnpj.com/algoritmo_do_cnpj.htm
   * @param $cnpj string
   * @author Rafael Goulart <rafaelgou@rgou.net>
   */
  protected function checkCNPJ($cnpj) {
    $cnpj = $this->valueClean($cnpj);
    if (strlen($cnpj) <> 14) return false;

    // Primeiro dígito
    $multiplicadores = array(5,4,3,2,9,8,7,6,5,4,3,2);
    $soma = 0;
    for ($i = 0; $i <= 11; $i++) {
       $soma += $multiplicadores[$i] * $cnpj[$i];
    }
    $d1 = 11 - ($soma % 11);
    if ($d1 >= 10) $d1 = 0;

    // Segundo dígito
    $multiplicadores = array(6,5,4,3,2,9,8,7,6,5,4,3,2);
    $soma = 0;
    for ($i = 0; $i <= 12; $i++) {
       $soma += $multiplicadores[$i] * $cnpj[$i];
    }
    $d2 = 11 - ($soma % 11);
    if ($d2 >= 10) $d2 = 0;

    if ($cnpj[12] == $d1 && $cnpj[13] == $d2) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * valueClean
   * Retira caracteres especiais
   * @param $value string
   * @author Rafael Goulart <rafaelgou@rgou.net>
   */
  protected function valueClean ($value) {

    $value = str_replace (')','',$value);
    $value = str_replace ('(','',$value);
    $value = str_replace ('/','',$value);
    $value = str_replace ('.','',$value);
    $value = str_replace ('-','',$value);
    $value = str_replace (' ','',$value);
    return $value;

  }

  /**
   * formatCPFCNPJ
   * Retorna CPF/CNPJ Formatado
   * @param $value string
   * @author Rafael Goulart <rafaelgou@rgou.net>
   */
  protected function formatCPFCNPJ ($value) {

    if (strlen($value) == 11)
    {
      return substr($value, 0, 3) . '.' .
             substr($value, 3, 3) . '.' .
             substr($value, 6, 3) . '-' .
             substr($value, 9, 2);

    } else {
      return substr($value, 0, 2) . '.' .
             substr($value, 2, 3) . '.' .
             substr($value, 5, 3) . '/' .
             substr($value, 8, 4) . '-' .
             substr($value, 12, 2);
    }

  }

}
