<?php

require_once dirname(__FILE__).'/../lib/Basebr_cepActions.class.php';

/**
 * br_cep_cep actions.
 * 
 * @package    brFormExtraPlugin
 * @subpackage br_cep
 * @author     Rafael Goulart <rafaelgou@gmail.com>
 * @version    SVN: $Id: actions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class br_cepActions extends Basebr_cepActions
{

 /**
   * Através do cep busca as informações de localização
   * @param object $request Informações da requisição
   * @return json           Informações da localização
   */
  public function executeBuscar(sfWebRequest $request)
  {

    // Apenas retorna se for Requisição AJAX
    $this->forward404Unless($request->isXmlHttpRequest());

    // Verificando se cliente pode acessar a busca
    if (sfConfig::get('app_br_cep_client_ips'))
    {
      if (! in_array($_SERVER['REMOTE_ADDR'], sfConfig::get('app_br_cep_client_ips') ) )
      {
        $this->forward404('Acesso não permitido');
      }
    }

    $cep = $request->getParameter('cep', null);

    //Definindo retorno nulo como base
    $location = array(
      'resultado'       => '0',
      'resultado_txt'   => 'CEP não encontrado',
      'logradouro'      => null,
      'tipo_logradouro' => null,
      'bairro'          => null,
      'cidade'          => null,
      'uf'              => null,
      'cep'             => $cep
      );

    // Apenas retorna se for Requisição AJAX
    if ($cep)
    {
      // Retirando hífen
      $cep = str_replace('-', '', $cep );

      // Se configurado para busca local, então realiza consulta
      if (sfConfig::get('app_br_cep_local_search'))
      {
        $cep_record = Doctrine::getTable('CEP_Brazil')
                        ->createQuery('c')
                        ->addWhere('cep = ?',$cep)
                        ->fetchOne();

        if ($cep_record)
        {
          $location = array(
            'resultado'       => '1',
            'resultado_txt'   => 'sucesso - CEP encontrado',
            'logradouro'      => $cep_record->getLogNo(),
            'tipo_logradouro' => $cep_record->getLogTipoLogradouro(),
            'bairro'          => $cep_record->getBaiNo(),
            'cidade'          => $cep_record->getLocNosub(),
            'uf'              => $cep_record->getUfeSg(),
            'cep'             => $cep
            );
        }
        
      // A busca é remota, procurando configurações
      } else {

        // Realizando requisição
        $url = sfConfig::get('app_br_cep_remote_url') . '?' .
               sfConfig::get('app_br_cep_remote_query') . 
               $cep . '&formato=json';
        $content = file_get_contents($url);

        if (strlen($content))
        {
          $location = json_decode($content, true);
          foreach ($location as $key => $value)
          {
            $location[$key] = trim($value);
          }
        }

      } // if (sfConfig::get('app_br_cep_local_search'))
    } //if (! $cep = $request->getParameter('cep', false))
    
    return $this->renderText( json_encode($location) );
  }
}
