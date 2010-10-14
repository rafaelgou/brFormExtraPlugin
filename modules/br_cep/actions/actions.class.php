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
    //$this->forward404Unless($request->isXmlHttpRequest());

    // Verificando se cliente pode acessar a busca
    if (sfConfig::get('app_br_cep_client_ips'))
    {
      if (! in_array($_SERVER['REMOTE_ADDR'], sfConfig::get('app_br_cep_client_ips') ) )
      {
        $this->forward404('Acesso não permitido');
      }
    }

    $cep_value = $request->getParameter('cep', null);

    $location = array(
      'resultado'       => '0',
      'logradouro'      => null,
      'tipo_logradouro' => null,
      'bairro'          => null,
      'cidade'          => null,
      'uf'              => null,
      'cep'             => $cep_value
      );


    // Apenas retorna se for Requisição AJAX
    if ($cep_value)
    {

      // Se configurado para busca local, então realiza consulta
      if (sfConfig::get('app_br_cep_local_search'))
      {
        // Retirando hífen
        $cep_value = str_replace('-', '', $cep_value );

        $cep_record = Doctrine::getTable('CEP_Brazil')
                        ->createQuery('c')
                        ->addWhere('cep = ?',$cep_value)
                        ->fetchOne();

        if ($cep_record)
        {
          $location = array(
            'resultado'       => '1',
            'logradouro'      => $cep_record->getLogNo(),
            'tipo_logradouro' => $cep_record->getLogTipoLogradouro(),
            'bairro'          => $cep_record->getBaiNo(),
            'cidade'          => $cep_record->getLocNosub(),
            'uf'              => $cep_record->getUfeSg(),
            'cep'             => $cep_value
            );
        }
        
      // A busca é remota, procurando configurações
      } else {

        $cep_search = (strlen($cep_value) == 8)
                      ? $cep_search = substr($cep_value, 0, 5) . '-' . substr($cep_value, 5, 3)
                      : $cep_value;
        // Realizando requisição
        $url = sfConfig::get('app_br_cep_remote_url') . '?' .
               sfConfig::get('app_br_cep_remote_query') . 
               $cep_search;
//echo $url;
        $content = file_get_contents($url);

        $remote_fields = array_flip(sfConfig::get('app_br_cep_remote_fields'));

        switch(sfConfig::get('app_br_cep_format'))
        {
          case 'republicavirtual':
            
            $cep_value = str_replace('-', '', $cep_value );

            if (strlen($content))
            {
              $data = json_decode($content, true);
              foreach ($data as $key => $value)
              {
                if (isset($remote_fields[$key]))
                  $location[$remote_fields[$key]] = trim($value);
              }
            }
            break;

          case 'ceplivre':

            $xml = new SimpleXMLElement($content);

            foreach ($xml->cep[0] as $key => $value)
            {

              if (isset($remote_fields[$key]))
              {
                $v = each($value[0]);
                $location[$remote_fields[$key]] = $v['value'];
              }
            }
            break;
        }

      } // if (sfConfig::get('app_br_cep_local_search'))
    } //if (! $cep = $request->getParameter('cep', false))

    $location['cep'] = $cep_value;

    return $this->renderText( json_encode($location) );
  }
}
