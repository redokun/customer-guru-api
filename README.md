# customer.guru PHP API

> Simple wrapper for [customer.guru](https://customer.guru) API

## Usage

```php
<?php

$guru = new CustomerGuru("api_key", "api_secret");
$guru->sendSurvey("test@example.com", new \DateTime('now'));
```

See PHP docblock of the sendSurvey() method for more parameters