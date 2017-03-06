# customer.guru PHP API v1

> Simple wrapper for [customer.guru](https://customer.guru) API

This library is based on the [first version (v1) of the API](https://customer.guru/api/documentation/v1) 

## Usage

```php
<?php

$guru = new CustomerGuru("api_key", "api_secret");
$guru->sendSurvey("test@example.com", new \DateTime('now'));
```

See PHP docblock of the sendSurvey() method for more parameters