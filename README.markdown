# brFormExtraPlugin #

Widgets e validadores para comunidade brasileira do symfony.


## Instalação ##

Para instalar o plugin num projeto symfony, utilize a linha de comando usual do symfony:

    php symfony plugin:install brFormExtraPlugin

E está feito.

Alternativamente, se você não tem o PEAR instalado, pode baixar o último pacote anexado à pagina wiki deste plugin e extraí-lo no diretório de plugins do projeto.

Neste caso, ative o plugin em seu ProjectConfiguration.class.php.

    class ProjectConfiguration extends sfProjectConfiguration
    {
      public function setup()
      {
        $this->enablePlugins(..., 'brFormExtraPlugin', ...);

Limpe o cache para habilitar ao autoloading encontrar as classes:

    php symfony cc

E, por último, publique os assets.

    php symfony plugin:publish-assets

## Utilizando brFormExtraPlugin ##

### sfWidgetFormChoiceUFBR , sfValidatorChoiceStates ###

- **sfWidgetFormChoiceUFBR**:
  Renderiza um SELECT com lista de estados (UFs - Unidades da Federação) brasileiros
- **sfValidatorChoiceStates**:
  Valida lista de estados (UFs - Unidades da Federação) brasileiros

**Exemplo 1**

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

**Exemplo 2**

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

**Opções**

- add_empty:
  Whether to add a first empty value or not (false by default)
  If the option is not a Boolean, the value will be used as the text value
- multiple:
  true if the select tag must allow multiple selections
- expanded:
  true to display an expanded widget
  if expanded is false, then the widget will be a select
  if expanded is true and multiple is false, then the widget will be a list of radio
  if expanded is true and multiple is true, then the widget will be a list of checkbox
- renderer_class:
  The class to use instead of the default ones
- renderer_options:
  The options to pass to the renderer constructor
- renderer:
  A renderer widget (overrides the expanded and renderer_options options)
  The choices option must be: new sfCallable($thisWidgetInstance, "getChoices"")

###sfWidgetFormSelectCheckboxStates , sfValidatorChoiceStates###

- **sfWidgetFormSelectCheckboxStates**:
  Renderiza checkboxes com lista de estados (UFs - Unidades da Federação) brasileiros,
  agrupados por região e permitindo selecionar por região e todos

![Preview sfWidgetFormSelectCheckboxStates](http://github.com/rafaelgou/brFormExtraPlugin/raw/master/docs/sfWidgetFormSelectCheckboxStates.png)

**Exemplo**

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

**Opções**

- label_separator:
  The separator to use between the input checkbox and the label
- class:
  The class to use for the main <ul> tag
- separator:
  The separator to use between each input checkbox
- formatter:
  A callable to call to format the checkbox choices
  The formatter callable receives the widget and the array of inputs as arguments
- template:
  The template to use when grouping option in groups (%group% %options%)

Dica: o melhor é configurar no arquivo YAML.

**plugins/brFormExtraPlugin/config/state.yml**

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

**Usando em conjunto com ORM - Propel e Doctrine**

Para que o widget possa trabalhar adequadamente com o ORN, deve receber os valores em forma de array.

Da mesma forma, o armazenamento é em apenas um campo VARCHAR. Considerando que são
27 estados + DF, 2 caracteres cada mais o separador, o máximo do campo é de 80 caracteres

*Propel*

    # config/schema.yml
    cadastro:
      uf: { phpName: Uf, type: VARCHAR, size: '80' }


    // (...)
    // Seu model, como lib/model/Cadastro.php
    public function getUf()
    {
      return sfWidgetFormSelectCheckboxStates::getExplodedValue($this->uf);
    }

    public function setUf($v)
    {
      parent::setUf(sfWidgetFormSelectCheckboxStates::getImplodedValue($v));
    }
    // (...)


*Doctrine*

    # config/doctrine/schema.yml
    Cadastro:
      columns:
        uf: { type: string(100) }


    // (...)
    // Seu model, como lib/model/doctrine/Cadastro.php
    public function getUf()
    {
      return sfWidgetFormSelectCheckboxStates::getExplodedValue($this->_get('uf'));
    }

    public function setUf($v)
    {
      parent::_set('uf',sfWidgetFormSelectCheckboxStates::getImplodedValue($v));
    }
    // (...)


###sfValidatorCpfCnpj###

Valida uma entrada em formato de CPF ou CNPJ. Permite retorna o valor sem formatação (padrão)
ou formatado (formated=>true) para armazenamento em banco de dados.

**Exemplo CPF**

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

**Exemplo CNPJ**

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

**Exemplo CPF/CNPJ**

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

**Opções**

- type:
  Type of validation (cpfcnpj, cpf, cnpj - default: cpfcnpj)
- formated:
  If true "clean" method returns a formated value, i.e. 000.000.000-00 (default: false)
  Use to store formated value in DB

###sfValidatori18nDate###

Valida uma data enviada por um input text contra o padrão do idioma do usuário.
Utiliza o sfContext para recuperar a "culture" do "user".
Este validador foi copiado de: (http://trac.symfony-project.org/attachment/ticket/6256/sfValidatorL10nDate.class.php),
enviado por logistiker. Realizei apenas ajustes cosméticos, como mudança do nome.

É necessário definir o idioma (culture) do usuário para a desejada. A forma mais simples é editar o
arquivo **apps/SUA_APLICACAO/config/settings.yml** e acrescentar:

    all:
      .settings:
        i18n:                   true
        default_culture:        pt_BR

**Exemplo**

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

**Opções**

As opções são as mesmas da classe pai, sfValidatorDate, acrescidas do "context" - que
não precisa ser enviado, é recuperado automaticamente se não informado.

- date_format:
  A regular expression that dates must match
- with_time:
  true if the validator must return a time, false otherwise
- date_output:
  The format to use when returning a date (default to Y-m-d)
- datetime_output:
  The format to use when returning a date with time (default to Y-m-d H:i:s)
- date_format_error:
  The date format to use when displaying an error for a bad_format error
  (use date_format if not provided)
- max:
  The maximum date allowed (as a timestamp)
- min:
  The minimum date allowed (as a timestamp)
- date_format_range_error:
  The date format to use when displaying an error for min/max (default to d/m/Y H:i:s)
- context:
  The symfony application context
    *

**Códigos de erro**

- bad_format
- min
- max

## Demonstração ##

Habilite a demonstração editando o arquivo **apps/SUA_APLICACAO/config/settings.yml** e
acrescentando a **enabled_modules**

    all:
      .settings:
        enabled_modules: [default, br_form_extra_demo]