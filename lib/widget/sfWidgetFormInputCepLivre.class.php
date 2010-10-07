<?php

include_once( sfConfig::get('sf_symfony_lib_dir') . '/helper/AssetHelper.php' );
/**
 * @author     Rafael Goulart <rafaelgou@rgou.net>
 * @version    1.0.0
 */
class sfWidgetFormInputCepLivre extends sfWidgetFormInputText
{
  /**
   * Configures the current widget.
   *
   * Available options:
   *
   * @param string fields                Fields to feed with the response
   * 
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {

    $this->addOption('fields', sfConfig::get('app_br_ceplivre_form_fields'));

    // Tamanho máximo do CEP, com hífen
    parent::configure($options, $attributes);

    $this->setAttribute('maxlength', '9');  
    $this->setOption('type', 'text');
  }
  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $id = $this->generateId($name);

    $status = '<button id="loading_' . $id . '" class="sfWidgetFormInputCep_find" type="button" ' .
              'onclick="br_cepFind_' . $id . '()">&nbsp;</button>';

    $javascript = '
<script language="javascript" type="text/javascript">
/* <![CDATA[ */
function br_cepFind_' . $id . '()
{
  var cep = $("#' . $id . '").val();
  if (cep.length == 8)
    cep = cep.substr(0,5) + "-" + cep.substr(5,3);
' . '
  $.ajax({
    type:"GET",
    url: "' . sfConfig::get('app_br_ceplivre_remote_url') . '",
    beforeSend: function(XMLHttpRequest) {
        $("#loading_' . $id . '").removeAttr("class");
        $("#loading_' . $id . '").addClass("sfWidgetFormInputCep_loading");
      },
    dataType: "xml", ' .  //"' . sfConfig::get('app_br_ceplivre_remote_format') . '",
'    data: "' . sfConfig::get('app_br_ceplivre_remote_query') . '"+cep,
    failure: function(XMLHttpRequest, textStatus) {
        $("#loading_' . $id . '").removeAttr("class");
        $("#loading_' . $id . '").addClass("sfWidgetFormInputCep_error");
      },
    complete: function(xml) {
        //alert(XMLHttpRequest.responseText);
        $(xml).find("ceplivre").each(function(){
          $(this).find("cep").each(function(){
           alert($(this).find("sucesso").text());
            if ( $(this).find("sucesso").text() != "0")
              {
';
              $remote_fields = sfConfig::get('app_br_ceplivre_remote_fields');
              foreach ($this->getOption('fields') as $key_field => $field) {
                if ( $key_field == 'logradouro' )
                {
                  $javascript .= '              $("#' . $field . '").val($(this).find("' . $remote_fields['tipo_logradouro'] .  
                                 '").text()+" "+$(this).find("' . $remote_fields['logradouro'] .  '").text());' . "\n";
                } elseif ( $key_field == 'tipo_logradouro' ) {
                  $javascript .= '';
                } else {
                  $javascript .= '              $("#' . $field . '").val($(this).find("' . 
                                 $remote_fields[$key_field] .  '").text());' . "\n";
                }
              }
                  $javascript .= '
              $("#loading_' . $id . '").removeAttr("class");
              $("#loading_' . $id . '").addClass("sfWidgetFormInputCep_ok");
            } else {
              $("#loading_' . $id . '").removeAttr("class");
              $("#loading_' . $id . '").addClass("sfWidgetFormInputCep_error");
            }
          });
        });
      }
    });
}
/* ]]> */
</script>';

    $input = $this->renderTag('input', array_merge(array('type' => $this->getOption('type'), 'name' => $name, 'value' => $value), $attributes));
    return $input . $status . $javascript;
  }

  /**
   * Gets the Stylesheets paths associated with the widget.
   *
   * @return array An array of Stylesheets paths
   */
  public function getStylesheets()
  {
    return array('../brFormExtraPlugin/css/main.css');
  }

}
