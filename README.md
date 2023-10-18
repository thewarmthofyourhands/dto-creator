Dto creator
================

php ./bin/console app:dto-creator --sourcePath='config/packages/dto' --baseNamespace='App' --baseDir='src'

Created dto 

schema_example.yaml
```
- namespace: App\Dto
  class: MyDto
  properties:
    propertyInt: int
    propertyString: string
    propertyArray: array
```

baseNamespace option transform App to baseDir (src)
so target file path will be src/Dto/MyDto.php
