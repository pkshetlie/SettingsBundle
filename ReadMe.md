Installation
-----------------------
```composer require pkshetlie/setting-bundle ```

add to AppKernel.php 

 ``` php
 [ 
        ...
        new Pkshetlie\SettingBundle\SettingBundle(), 
        ... 
 ]
 ```
 
 Add to config.yml
 
  ``` yaml
imports:
    ...
    - { resource: "@SettingBundle/Resources/config/services.yml" }
  ```
  
  installation is done.
  
 Exemple Usage
 -------------------------- 
 