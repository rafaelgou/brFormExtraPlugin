<?php
  use_javascripts_for_form($form);
  use_stylesheets_for_form($form);
?>
<style type="text/css">
body {margin: 0 50px;}
.error_list {color:red;}
pre {background: #666;color: #fff; margin: 0 20px;}
h1 {border-bottom: 3px solid #555}
h2 {border-bottom: 1px solid #555; margin-top: 100px;}
h3 {border-bottom: 1px dashed #555; font-style: italic}
pre {margin: 0 20px;}
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

');?>
</pre>

<br/><br/>

<p><button type="submit">Enviar</button></p>

</form>

