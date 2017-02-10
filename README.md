# php-codewriter

######Generate code for writing to file using different components

##### Need to pass instance of ```CodeWriterSettings``` to the top most component using ```setSettings()``` [inherited from BaseComponent Class] before calling ```writeComponent()``` method. Exception ```SettingsNotSet``` is thrown if ```writeComponent()``` is called before settings is set.
```php
      CodeWriterSettings::create(ISyntaxGrammar::PHP, $indent = 0)
```

####  1 . Variable Component
```php
      $variable = VariableComponent::create("var")->setValue("Is Name")->writeComponent();
      
OUTPUT: 
      $var = 'Is Name';
```     

######Constants:
```php
      $constant = VariableComponent::create("TEST")->setValue("VALUE 1")->makeConstant()->writeComponent();
      
OUTPUT: 
      const TEST = 'VALUE 1';
```     
      
####  2 . Array Component   
```php  
      $array    = ArrayComponent::create("what_is_this", TRUE)
        ->setValue([
            "string" => "is game",
            "number" => 2,
            "bool"   => FALSE,
        ])
        ->writeComponent();
        
OUTPUT:   
      $what_is_this = [
            'string' => 'is game',
            'number' => 2,
            'bool' => false,
        ];
```     
        
####  3 . Function Component  
```php
      $function = FunctionComponent::create("myFunction")
        ->setAccessIdentifier(BaseComponent::ACCESS_PUBLIC)
        ->setParameters(['array $my_array', '$val'])
        ->appendComponent($array)
        ->appendComponent($variable)
        ->writeComponent();
        
OUTPUT:
      /**
       * @param array $my_array
       * @param $val
       */
      public function myFunction(array $my_array, $val)
      {
          $what_is_this = [
              'string' => 'is game',
              'number' => 2,
              'bool' => false,
          ];
          $nischal = 'Is Name';
      }
```
      
####  4 . Class Component 
```php
      $class = ClassComponent::create('TestController')
        ->setExtends("Controller")
        ->appendComponent($variable)
        ->appendComponent($constant)
        ->appendBlankLine()
        ->appendComponent($array)
        ->appendBlankLine()
        ->appendComponent($function)
        ->writeComponent();
        
OUTPUT:
      /**
       * Class TestController
       */
      class TestController extends Controller
      {
           $var = 'Is Name';
           const TEST = 'VALUE 1';

           $what_is_this = [
              'string' => 'is game',
              'number' => 2,
              'bool' => false,
          ];

          /**
           * @param array $my_array
           * @param $val
           */
          public function myFunction(array $my_array, $val)
          {
              $what_is_this = [
                  'string' => 'is game',
                  'number' => 2,
                  'bool' => false,
              ];
              $var = 'Is Name';
          }
      }
```     
