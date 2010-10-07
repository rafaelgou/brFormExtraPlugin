<?php

require_once dirname(__FILE__).'/../lib/Basebr_form_extra_demoActions.class.php';

/**
 * br_form_extra_demo actions.
 * 
 * @package    brFormExtraPlugin
 * @subpackage br_form_extra_demo
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class br_form_extra_demoActions extends Basebr_form_extra_demoActions
{
  public function executeIndex(sfWebRequest $request)
  {
    $this->form = new sfForm();

    $this->form->setWidget("uf1", new sfWidgetFormChoiceUFBR(array('add_empty'=>'Escolha a UF')));
    $this->form->getWidgetSchema()->setLabel("uf1", "Estado (UF)");

    $this->form->setWidget("uf2", new sfWidgetFormChoiceUFBR());
    $this->form->getWidgetSchema()->setLabel("uf2", "Estado (UF)");

    $this->form->setWidget("uf_checkbox", new sfWidgetFormSelectCheckboxStates());
    $this->form->getWidgetSchema()->setLabel("uf_checkbox", "Estados (UF)");

    $this->form->setWidget("cpf", new sfWidgetFormInputText());
    $this->form->getWidgetSchema()->setLabel("cpf", "CPF");

    $this->form->setWidget("cnpj", new sfWidgetFormInputText());
    $this->form->getWidgetSchema()->setLabel("cnpj", "CNPJ");

    $this->form->setWidget("cpfcnpj", new sfWidgetFormInputText());
    $this->form->getWidgetSchema()->setLabel("cpfcnpj", "CPF/CNPJ");

    $this->form->setWidget("data_i18n", new sfWidgetFormInputText());
    $this->form->getWidgetSchema()->setLabel("data_i18n", "Data i18n");

    $fields_for_cep = array(
      "logradouro" => "demo_logradouro",
      "bairro"     => "demo_bairro",
      "cidade"     => "demo_cidade",
      "uf"         => "demo_uf_cep",
      "cep"        => "demo_cep"
    );
    $this->form->setWidget("cep", new sfWidgetFormInputCep( array("fields" => $fields_for_cep) ));
    $this->form->getWidgetSchema()->setLabel("cep", "CEP");

    $this->form->setWidget("logradouro", new sfWidgetFormInput());
    $this->form->getWidgetSchema()->setLabel("logradouro", "Logradouro");

    $this->form->setWidget("bairro", new sfWidgetFormInput());
    $this->form->getWidgetSchema()->setLabel("bairro", "Bairro");

    $this->form->setWidget("cidade", new sfWidgetFormInput());
    $this->form->getWidgetSchema()->setLabel("cidade", "Cidade");

    $this->form->setWidget("uf_cep", new sfWidgetFormChoiceUFBR());
    $this->form->getWidgetSchema()->setLabel("uf_cep", "UF");

    $this->form->setValidators(array(
        "uf" =>          new sfValidatorChoiceStates(),
        "uf_checkbox" => new sfValidatorChoiceStates(array("min"=>5, "max"=>10)),
        "cpf" =>         new sfValidatorCpfCnpj(array("type"=>"cpf")),
        "cnpj" =>        new sfValidatorCpfCnpj(array("type"=>"cnpj")),
        "cpfcnpj" =>     new sfValidatorCpfCnpj(array("type"=>"cpfcnpj")),
        "data_i18n" =>   new sfValidatori18nDate(),
        "cep" =>         new sfValidatorCep(),
        "logradouro" =>  new sfValidatorString(array("required"=>false)),
        "bairro" =>      new sfValidatorString(array("required"=>false)),
        "cidade" =>      new sfValidatorString(array("required"=>false)),
        "uf_cep" =>      new sfValidatorString(array("required"=>false)),
      ));
    //$f = new sfForm;

    $this->form->getWidgetSchema()->setNameFormat('demo[%s]');

    if ($request->isMethod('post'))
      $this->form->bind($request->getParameter($this->form->getName()), $request->getFiles($this->form->getName()));

  }
}
