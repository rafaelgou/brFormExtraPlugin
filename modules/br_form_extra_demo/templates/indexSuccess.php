<?php
  use_javascripts_for_form($form);
  use_stylesheets_for_form($form);
?>
<style type="text/css">
body {margin: 0 50px;}
.error_list {color:red;}
pre {background: #666;color: #fff; margin: 0 20px;}
h1 {border-bottom: 3px solid #555}
h2 {border-bottom: 1px solid #555; margin-top: 50px;}
h3 {border-bottom: 1px dashed #555; font-style: italic;  margin-top: 20px;}
p  {margin: 10px 20px;}
</style>
<h1>brFormExtraPlugin Demonstração</h1>

<form action="" method="post">

<h2>sfWidgetFormChoiceUFBR , sfValidatorChoiceStates</h2>

<h3>Exemplos</h3>

<p>
  <?php echo $form["uf1"]->renderError() ?>
  <?php echo $form["uf1"]->renderLabel() ?>
  <?php echo $form["uf1"] ?>
</p>

<pre>
<?php echo htmlspecialchars('
  <?php
    $form->setWidget("uf1", new sfWidgetFormChoiceUFBR(array("add_empty"=>"Escolha UF")));
    $form->getWidgetSchema()->setLabel("uf1", "Estado (UF)");
    $form->setValidator("uf1", new sfValidatorChoiceStates());
  ?>
  <p>
    <?php echo $form["uf1"]->renderError() ?>
    <?php echo $form["uf1"]->renderLabel() ?>
    <?php echo $form["uf1"] ?>
  </p>
');?>
</pre>

<p>
  <?php echo $form["uf2"]->renderError() ?>
  <?php echo $form["uf2"]->renderLabel() ?>
  <?php echo $form["uf2"] ?>
</p>

<pre>
<?php echo htmlspecialchars('
  <?php
    $form->setWidget("uf2", new sfWidgetFormChoiceUFBR());
    $form->getWidgetSchema()->setLabel("uf2", "Estado (UF)");
    $form->setValidator("uf2", new sfValidatorChoiceStates());
  ?>
  <p>
    <?php echo $form["uf2"]->renderError() ?>
    <?php echo $form["uf2"]->renderLabel() ?>
    <?php echo $form["uf2"] ?>
  </p>
');?>
</pre>

<h3>Opções</h3>

<pre>
<?php echo htmlspecialchars('
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
   *                      The choices option must be: new sfCallable($thisWidgetInstance, "getChoices"")
');?>
</pre>

<h2>sfWidgetFormSelectCheckboxStates , sfValidatorChoiceStates</h2>

<h3>Exemplo</h3>

<p>
  <?php echo $form["uf_checkbox"]->renderError() ?>
  <?php echo $form["uf_checkbox"]->renderLabel() ?>
  <?php echo $form["uf_checkbox"] ?>
</p>

<pre>
<?php echo htmlspecialchars('
  <?php
    $form->setWidget("uf_checkbox", new sfWidgetFormSelectCheckboxStates());
    $form->getWidgetSchema()->setLabel("uf_checkbox", "Estados (UF)");
    $form->setValidator("uf_checkbox", new sfValidatorChoiceStates(array("min"=>5, "max"=>10)));
  ?>
  <p>
    <?php echo $form["uf_checkbox"]->renderError() ?>
    <?php echo $form["uf_checkbox"]->renderLabel() ?>
    <?php echo $form["uf_checkbox"] ?>
  </p>
');?>
</pre>

<h3>Opções</h3>

<pre>
<?php echo htmlspecialchars('
   * Available options:
   *
   *  * label_separator: The separator to use between the input checkbox and the label
   *  * class:           The class to use for the main <ul> tag
   *  * separator:       The separator to use between each input checkbox
   *  * formatter:       A callable to call to format the checkbox choices
   *                     The formatter callable receives the widget and the array of inputs as arguments
   *  * template:        The template to use when grouping option in groups (%group% %options%)
');?>
</pre>

<h3>plugins/brFormExtraPlugin/config/state.yml</h3>

<pre>
<?php echo htmlspecialchars('
# a label or false
all_checkbox: "Todo o Brasil"

separator: ";"

regions:

  norte:
    label: "Região Norte"
    states:
      AC: Acre
      AP: Amapá
      AM: Amazonas
      PA: Pará
      RO: Rondônia
      RR: Roraima
      TO: Tocantins

  nordeste:
    label: "Região Nordeste"
    states:
      AL: Alagoas
      BA: Bahia
      CE: Ceará
      MA: Maranhão
      PB: Paraíba
      PE: Pernambuco
      PI: Piauí
      RN: Rio Grande do Norte
      SE: Sergipe

  centrooeste:
    label: "Região Centro-Oeste"
    states:
      DF: Distrito Federal
      GO: Goiás
      MG: Mato Grosso
      MS: Mato Grosso do Sul

  sudeste:
    label: "Região Sudeste"
    states:
      ES: Espírito Santo
      MG: Minas Gerais
      RJ: Rio de Janeiro
      SP: São Paulo

  sul:
    label: "Região Sul"
    states:
      PR: Paraná
      RS: Rio Grande do Sul
      SC: Santa Catarina

');?>
</pre>

<h2>sfValidatorCpfCnpj</h2>

<h3>Exemplo</h3>

<p>
  <?php echo $form["cpf"]->renderError() ?>
  <?php echo $form["cpf"]->renderLabel() ?>
  <?php echo $form["cpf"] ?>
</p>

<pre>
<?php echo htmlspecialchars('
  <?php
    $form->setWidget("cpf", new sfWidgetFormInputText());
    $form->getWidgetSchema()->setLabel("cpf", "CPF");
    $form->setValidator("cpf", new sfValidatorCpfCnpj(array("type"=>"cpf")));
  ?>
  <p>
    <?php echo $form["cpf"]->renderError() ?>
    <?php echo $form["cpf"]->renderLabel() ?>
    <?php echo $form["cpf"] ?>
  </p>
');?>
  </pre>

<p>
  <?php echo $form["cnpj"]->renderError() ?>
  <?php echo $form["cnpj"]->renderLabel() ?>
  <?php echo $form["cnpj"] ?>
</p>

<pre>
<?php echo htmlspecialchars('
  <?php
    $form->setWidget("cnpj", new sfWidgetFormInputText());
    $form->getWidgetSchema()->setLabel("cnpj", "CNPJ");
    $form->setValidator("cnpj", new sfValidatorCpfCnpj(array("type"=>"cnpj")));
  ?>
  <p>
    <?php echo $form["cnpj"]->renderError() ?>
    <?php echo $form["cnpj"]->renderLabel() ?>
    <?php echo $form["cnpj"] ?>
  </p>
');?>
</pre>

<p>
  <?php echo $form["cpfcnpj"]->renderError() ?>
  <?php echo $form["cpfcnpj"]->renderLabel() ?>
  <?php echo $form["cpfcnpj"] ?>
</p>

<pre>
<?php echo htmlspecialchars('
  <?php
    $form->setWidget("cpfcnpj", new sfWidgetFormInputText());
    $form->getWidgetSchema()->setLabel("cpfcnpj", "CPF/CNPJ");
    $form->setValidator("cpfcnpj", new sfValidatorCpfCnpj(array("type"=>"cpfcnpj")));
  ?>
  <p>
    <?php echo $form["cpfcnpj"]->renderError() ?>
    <?php echo $form["cpfcnpj"]->renderLabel() ?>
    <?php echo $form["cpfcnpj"] ?>
  </p>
');?>
</pre>

<h3>Opções</h3>

<pre>
<?php echo htmlspecialchars('
   * Available options:
   *
   *  * type:     Type of validation (cpfcnpj, cpf, cnpj - default: cpfcnpj)
   *  * formated: If true "clean" method returns a formated value, i.e. 000.000.000-00 (default: false)
                  Use to store formated value in DB
   *  * use_cnpj_with_15_chars: Returns CNPJ with 15 characters
');?>
</pre>

<h2>sfValidatori18nDate</h2>
Informe uma data no padrão do idioma definido para o usuário.

<h3>Exemplo</h3>

<p>
  <?php echo $form["data_i18n"]->renderError() ?>
  <?php echo $form["data_i18n"]->renderLabel() ?>
  <?php echo $form["data_i18n"] ?>
</p>

<pre>
<?php echo htmlspecialchars('
  <?php
    $form->setWidget("data_i18n", new sfWidgetFormInputText());
    $form->getWidgetSchema()->setLabel("data_i18n", "Data i18n");
    $form->setValidator("data_i18n", new sfValidatori18nDate());
  ?>
  <p>
    <?php echo $form["data_i18n"]->renderError() ?>
    <?php echo $form["data_i18n"]->renderLabel() ?>
    <?php echo $form["data_i18n"] ?>
  </p>
');?>
  </pre>

<h3>Opções</h3>

<pre>
<?php echo htmlspecialchars('
   * Available options:
   *
    *  * date_format:             A regular expression that dates must match
    *  * with_time:               true if the validator must return a time, false otherwise
    *  * date_output:             The format to use when returning a date (default to Y-m-d)
    *  * datetime_output:         The format to use when returning a date with time (default to Y-m-d H:i:s)
    *  * date_format_error:       The date format to use when displaying an error for a bad_format error
    *                             (use date_format if not provided)
    *  * max:                     The maximum date allowed (as a timestamp)
    *  * min:                     The minimum date allowed (as a timestamp)
    *  * date_format_range_error: The date format to use when displaying an error for min/max (default to d/m/Y H:i:s)
    *  * context:                 The symfony application context
    *
    * Available error codes:
    *
    *  * bad_format
    *  * min
    *  * max
');?>
</pre>

<h2>sfWidgetFormInputCEP , sfValidatorCEP</h2>

<h3>Exemplos</h3>

<p>
  <?php echo $form["cep"]->renderError() ?>
  <?php echo $form["cep"]->renderLabel() ?>
  <?php echo $form["cep"] ?>
<br/>
  <?php echo $form["logradouro"]->renderError() ?>
  <?php echo $form["logradouro"]->renderLabel() ?>
  <?php echo $form["logradouro"] ?>
<br/>
  <?php echo $form["bairro"]->renderError() ?>
  <?php echo $form["bairro"]->renderLabel() ?>
  <?php echo $form["bairro"] ?>
<br/>
  <?php echo $form["cidade"]->renderError() ?>
  <?php echo $form["cidade"]->renderLabel() ?>
  <?php echo $form["cidade"] ?>
<br/>
  <?php echo $form["uf_cep"]->renderError() ?>
  <?php echo $form["uf_cep"]->renderLabel() ?>
  <?php echo $form["uf_cep"] ?>
<br/>
  <?php echo $form["codibge"]->renderError() ?>
  <?php echo $form["codibge"]->renderLabel() ?>
  <?php echo $form["codibge"] ?>
  </p>

<pre>
<?php echo htmlspecialchars('
  <?php

    $fields_for_cep = array(
      "logradouro" => "demo_logradouro",
      "bairro"     => "demo_bairro",
      "cidade"     => "demo_cidade",
      "uf"         => "demo_uf_cep",
      "cep"        => "demo_cep"
    );

    $this->form->setWidget("cep", new sfWidgetFormInputCep( array("fields" => $fields_for_cep) ));
    $this->form->getWidgetSchema()->setLabel("cep", "CEP");
    $form->setValidator("cep", new sfValidatorCep());

    $this->form->setWidget("logradouro", new sfWidgetFormInput());
    $this->form->getWidgetSchema()->setLabel("logradouro", "Logradouro");
    $form->setValidator("logradouro", new sfValidatorString());

    $this->form->setWidget("bairro", new sfWidgetFormInput());
    $this->form->getWidgetSchema()->setLabel("bairro", "Bairro");
    $form->setValidator("bairro", new sfValidatorString());

    $this->form->setWidget("cidade", new sfWidgetFormInput());
    $this->form->getWidgetSchema()->setLabel("cidade", "Cidade");
    $form->setValidator("cidade", new sfValidatorString());

    $this->form->setWidget("uf_cep", new sfWidgetFormChoiceUFBR());
    $this->form->getWidgetSchema()->setLabel("uf_cep", "UF");
    $form->setValidator("uf_cep", new sfValidatorString());

    <p>
      <?php echo $form["cep"]->renderError() ?>
      <?php echo $form["cep"]->renderLabel() ?>
      <?php echo $form["cep"] ?>
    <br/>
      <?php echo $form["logradouro"]->renderError() ?>
      <?php echo $form["logradouro"]->renderLabel() ?>
      <?php echo $form["logradouro"] ?>
    <br/>
      <?php echo $form["bairro"]->renderError() ?>
      <?php echo $form["bairro"]->renderLabel() ?>
      <?php echo $form["bairro"] ?>
    <br/>
      <?php echo $form["cidade"]->renderError() ?>
      <?php echo $form["cidade"]->renderLabel() ?>
      <?php echo $form["cidade"] ?>
    <br/>
      <?php echo $form["uf_cep"]->renderError() ?>
      <?php echo $form["uf_cep"]->renderLabel() ?>
      <?php echo $form["uf_cep"] ?>
    <br/>
      <?php echo $form["codibge"]->renderError() ?>
      <?php echo $form["codibge"]->renderLabel() ?>
      <?php echo $form["codibge"] ?>
    </p>
');?>
</pre>

<h3>plugins/brFormExtraPlugin/config/state.yml</h3>

<pre>
<?php echo htmlspecialchars("
all:
  br_cep:

    # Buscar na base local (Cep Brasil importado)
    # Utilizar format: republicavirtual
    local_search:  false

    # Array com lista de IPs que podem acessar remotamente
    # ou false para acesso público
    # Exemplo
    #client_ips: ['200.217.64.146', '200.217.64.147']
    client_ips: false

    # Fonte: http://ceplivre.pc2consultoria.com
    # -----------------------------------------
    format: ceplivre

    remote_url:    'http://ceplivre.pc2consultoria.com/index.php'
    remote_query:  'module=cep&formato=xml&cep='

    # Do not change remote_fields to http://rceplivre.pc2consultoria.com
    remote_fields:
      resultado:       sucesso
      tipo_logradouro: tipo_logradouro
      logradouro:      logradouro
      uf:              estado_sigla
      uf_descricao:    estado
      cidade:          cidade
      bairro:          bairro
      cep:             cep
      codigo_ibge:     codigo_ibge

    form_fields:
      logradouro:      logradouro
      estado_sigla:    uf
      cidade:          cidade
      bairro:          bairro
      cep:             cep
      codigo_ibge:     codibge

    # Fonte: http://republicavirtual.com.br
    # -------------------------------------
#    format: republicavirtual
#    remote_url:    'http://republicavirtual.com.br/web_cep.php'
#    remote_query:  'formato=json&cep='
#
#    # Do not change remote_fields to http://rceplivre.pc2consultoria.com
#    remote_fields:
#      resultado:       resultado
#      uf:              uf
#      cidade:          cidade
#      bairro:          bairro
#      tipo_logradouro: tipo_logradouro
#      logradouro:      logradouro
#      cep:             cep
#    form_fields:
#      uf:              uf
#      cidade:          cidade
#      bairro:          bairro
#      tipo_logradouro: tipo_logradouro
#      logradouro:      logradouro
#      cep:             cep
");?>
</pre>

<br/><br/>

<p><button type="submit">Enviar</button></p>

</form>

