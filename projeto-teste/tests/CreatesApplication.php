<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {

        putenv('APP_ENV=testing');
        putenv('APP_KEY=base64:SuscZSg2in5ay864CxftoIHR/lN+wxrjn1xzxFpEMWw=');  // Certifique-se de substituir 'YOUR_APP_KEY' pela chave do seu aplicativo.
        putenv('CACHE_DRIVER=array');
        putenv('SESSION_DRIVER=array');
        putenv('QUEUE_DRIVER=sync');
        putenv('DB_CONNECTION=pgsql'); // ou qualquer que seja sua conexão de teste

        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
