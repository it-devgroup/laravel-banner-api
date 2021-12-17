<?php

namespace ItDevgroup\LaravelBannerApi\Console\Command;

use Carbon\Carbon;
use Illuminate\Support\Str;
use ItDevgroup\LaravelBannerApi\Provider\BannerServiceProvider;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

/**
 * Class BannerPublishCommand
 * @package ItDevgroup\LaravelBannerApi\Console\Command
 */
class BannerPublishCommand extends Command
{
    /**
     * @var string
     */
    protected $signature = 'banner:api:publish {--tag=* : Tag for published}';
    /**
     * @var string
     */
    protected $description = 'Publish files for banner api package';
    /**
     * @var array
     */
    private array $files = [];
    /**
     * @var array
     */
    private array $fileTags = [
        'config',
    ];

    /**
     * @return void
     */
    public function handle()
    {
        $option = is_array($this->option('tag')) && !empty($this->option('tag')) ? $this->option('tag')[0] : '';

        $this->parsePublishedFiles();

        switch ($option) {
            case 'config':
                $this->copyConfig();
                break;
            case 'migration':
                $this->copyMigration();
                break;
            default:
                $this->error('Not selected tag');
                break;
        }
    }

    /**
     * @return void
     */
    private function parsePublishedFiles(): void
    {
        $index = 0;
        foreach (BannerServiceProvider::pathsToPublish(BannerServiceProvider::class) as $k => $v) {
            $this->files[$this->fileTags[$index]] = [
                'from' => $k,
                'to' => $v,
            ];
            $index++;
        }
    }

    /**
     * @return void
     */
    private function copyConfig(): void
    {
        $this->copyFiles($this->files['config']['from'], $this->files['config']['to']);
    }

    /**
     * @return void
     */
    private function copyMigration(): void
    {
        $this->createMigration(
            Config::get('banner_api.table.position'),
            'create_banner_positions.stub'
        );
        $this->createMigration(
            Config::get('banner_api.table.banner'),
            'create_banners.stub'
        );
        $this->createMigration(
            Config::get('banner_api.table.banner_position'),
            'create_banners_positions.stub'
        );
        $this->createMigration(
            Config::get('banner_api.table.image'),
            'create_banner_images.stub'
        );
        $this->createMigration(
            Config::get('banner_api.table.statistic_click'),
            'create_banner_statistic_clicks.stub'
        );
        $this->createMigration(
            Config::get('banner_api.table.attach_model'),
            'create_banner_attach_models.stub'
        );
    }

    /**
     * @param string $tableName
     * @param string $migrationFile
     * @return void
     */
    private function createMigration(
        string $tableName,
        string $migrationFile
    ): void {
        $newFileName = sprintf(
            '%s_create_%s.php',
            Carbon::now()->format('Y_m_d_His'),
            $tableName
        );

        copy(
            __DIR__ . '/../../../migration/' . $migrationFile,
            base_path('database/migrations/' . $newFileName)
        );

        $this->parseContent(base_path('database/migrations/' . $newFileName));

        $this->info(
            sprintf(
                'File "%s" created',
                '/database/migrations/' . $newFileName
            )
        );
    }

    /**
     * @param string $from
     * @param string $to
     */
    private function copyFiles(string $from, string $to): void
    {
        if (!file_exists($to)) {
            mkdir($to, 0755, true);
        }
        $from = rtrim($from, '/') . '/';
        $to = rtrim($to, '/') . '/';
        foreach (scandir($from) as $file) {
            if (!is_file($from . $file)) {
                continue;
            }

            $path = strtr(
                $to . $file,
                [
                    base_path() => ''
                ]
            );

            if (file_exists($to . $file)) {
                $this->info(
                    sprintf(
                        'File "%s" skipped',
                        $path
                    )
                );
                continue;
            }

            copy(
                $from . $file,
                $to . $file
            );

            $this->parseContent($to . $file);

            $this->info(
                sprintf(
                    'File "%s" copied',
                    $path
                )
            );
        }
    }

    /**
     * @param string $file
     */
    private function parseContent(string $file)
    {
        $content = file_get_contents($file);
        $content = strtr(
            $content,
            [
                '{{TABLE_NAME_POSITION}}' => Config::get('banner_api.table.position'),
                '{{MIGRATION_NAME_POSITION}}' => Str::ucfirst(
                    Str::camel(
                        Config::get('banner_api.table.position')
                    )
                ),
                '{{TABLE_NAME_BANNER}}' => Config::get('banner_api.table.banner'),
                '{{MIGRATION_NAME_BANNER}}' => Str::ucfirst(
                    Str::camel(
                        Config::get('banner_api.table.banner')
                    )
                ),
                '{{TABLE_NAME_BANNERS_POSITIONS}}' => Config::get('banner_api.table.banner_position'),
                '{{MIGRATION_NAME_BANNERS_POSITIONS}}' => Str::ucfirst(
                    Str::camel(
                        Config::get('banner_api.table.banner_position')
                    )
                ),
                '{{TABLE_NAME_BANNER_IMAGE}}' => Config::get('banner_api.table.image'),
                '{{MIGRATION_NAME_BANNER_IMAGE}}' => Str::ucfirst(
                    Str::camel(
                        Config::get('banner_api.table.image')
                    )
                ),
                '{{TABLE_NAME_BANNER_STATISTIC_CLICK}}' => Config::get('banner_api.table.statistic_click'),
                '{{MIGRATION_NAME_BANNER_STATISTIC_CLICK}}' => Str::ucfirst(
                    Str::camel(
                        Config::get('banner_api.table.statistic_click')
                    )
                ),
                '{{TABLE_NAME_BANNER_ATTACH_MODEL}}' => Config::get('banner_api.table.attach_model'),
                '{{MIGRATION_NAME_BANNER_ATTACH_MODEL}}' => Str::ucfirst(
                    Str::camel(
                        Config::get('banner_api.table.attach_model')
                    )
                )
            ]
        );
        file_put_contents($file, $content);
    }
}
