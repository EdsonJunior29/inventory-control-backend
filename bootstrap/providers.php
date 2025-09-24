<?php

use L5Swagger\L5SwaggerServiceProvider;

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\ModelObserverServiceProvider::class,
    L5SwaggerServiceProvider::class,
];