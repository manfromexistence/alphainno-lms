<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ReplaceGreenColors extends Command
{
    protected $signature = 'colors:replace-green';
    protected $description = 'Replace all hardcoded green colors with primary color classes';

    public function handle()
    {
        $this->info('Starting to replace green colors...');

        $replacements = [
            'bg-[#006A4E]' => 'bg-primary',
            'text-[#006A4E]' => 'text-primary',
            'border-[#006A4E]' => 'border-primary',
            'hover:bg-[#005a42]' => 'hover:opacity-90',
            'hover:bg-[#004d38]' => 'hover:opacity-90',
            'hover:bg-[#004432]' => 'hover:opacity-90',
            'hover:text-[#006A4E]' => 'hover:text-primary',
            'from-[#006A4E]' => 'from-primary',
            'to-[#004d38]' => 'to-primary',
        ];

        $viewsPath = resource_path('views');
        $files = File::allFiles($viewsPath);

        $totalReplacements = 0;

        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $content = File::get($file->getPathname());
                $originalContent = $content;

                foreach ($replacements as $search => $replace) {
                    $content = str_replace($search, $replace, $content);
                }

                if ($content !== $originalContent) {
                    File::put($file->getPathname(), $content);
                    $this->line('Updated: ' . $file->getRelativePathname());
                    $totalReplacements++;
                }
            }
        }

        $this->info("Completed! Updated {$totalReplacements} files.");
        
        // Clear caches
        $this->call('view:clear');
        $this->call('cache:clear');
        
        return 0;
    }
}
