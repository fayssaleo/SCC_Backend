<?php return [

'paths' => ['api/*'],

'allowed_methods' => ['*'],

'allowed_origins' => ['*'], // Permet toutes les origines. Vous pouvez spécifier des URLs comme ['http://localhost:8080'] pour des cas spécifiques.

'allowed_origins_patterns' => [],

'allowed_headers' => ['*'],

'exposed_headers' => [],

'max_age' => 0,

'supports_credentials' => false,

];
