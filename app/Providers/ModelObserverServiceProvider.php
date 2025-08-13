<?php

namespace App\Providers;

use App\Infra\Observers\ModelActivityObserver;
use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use ReflectionClass;

class ModelObserverServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $modelsPath = app_path('Models');
        $modelFiles = File::allFiles($modelsPath);

        foreach ($modelFiles as $file) {
            $relativePath = $file->getRelativePathname();
            $class = $this->getClassFullNameFromFile($relativePath);

            if (!class_exists($class) || $class === ActivityLog::class) {
                continue;
            }

            $reflection = new ReflectionClass($class);

            if ($reflection->isSubclassOf(Model::class) && !$reflection->isAbstract()) {
                $class::observe(ModelActivityObserver::class);
            }
        }
    }

    /**
     * Get the fully qualified class name from a relative file path.
     *
     * @param string $relativePath
     * @return string
     */
    protected function getClassFullNameFromFile(string $relativePath): string
    {
        $class = str_replace(['/', '.php'], ['\\', ''], $relativePath);
        return 'App\\Models\\' . $class;
    }
}