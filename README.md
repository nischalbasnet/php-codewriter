# php-codewriter

######Generate code for writing to file using different components

####  1 . Variable Component
```php
      $variable = VariableComponent::create("var")->setValue("Is Name");
      
OUTPUT: 
      $var = 'Is Name';
```     

######Constants:
```php
      $constant = VariableComponent::create("TEST")->setValue("VALUE 1")->makeConstant();
      
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
        ]);
        
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
        ->setParameters(['array $my_array', '$val'])
        ->addComponentToBody($array)
        ->setAccessIdentifier(BaseComponent::ACCESS_PUBLIC)
        ->addComponentToBody($variable);
        
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
        ->setExtends("Controller");
       
      $class->addComponents($variable)
        ->addComponents($constant)
        ->addComponents($array)
        ->addComponents($function);
        
OUTPUT:
      /**
       * CLASS TestController
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
