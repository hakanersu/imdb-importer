<?php

namespace App\Commands;

use Illuminate\Support\Facades\Storage;
use LaravelZero\Framework\Commands\Command;
use App\Watched\Importers\ImporterInterface;
use App\Watched\Importers\TimePassed;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Import extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'import:database
        {--skip= : Which tables to skip process. Comma separated.}
        {--only= : Only given tables will process. Comma separated. }
        {--path= : Overwrite path for files. }';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Import imdb database.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Starting to import process.');

        $fields = $original = ['title', 'episode', 'principal', 'name', 'crew', 'aka', 'rating'];

        Storage::makeDirectory('/tmp/imdb');

        $skip = $this->option('skip');
        $only = $this->option('only');
        $path = $this->option('path');

        if ($skip) {
            $skip = explode(',', $skip);
            $fields = array_diff($fields, $skip);
        }

        if ($only) {
            $fields = array_intersect($fields, explode(',', $only));
        }

        $this->fields = collect($fields);

        if ($this->fields->count() <=0) {
            $this->info("Nothing to do, available fields: ". json_encode($original));
            return false;
        }

        $this->dropTables();
        $this->createTables();

        $now = now();
        $this->fields->each(fn($field) => $this->importer($field)->download($this->output)->start($path)->index());

        $this->warn('All process took '. TimePassed::took($now));
    }

    private function dropTables(): void
    {
        $this->fields->each(function ($field) {
            $table = Str::plural($field);
            Schema::dropIfExists($table);
            if (Schema::hasTable('migrations')) {
                $migration = "create_{$table}_table";
                $this->info("Dropping {$table}.");
                DB::table('migrations')->where('migration', 'like', "%{$migration}")->delete();
            }
        });

        if (Schema::hasTable('migrations')) {
            $this->callSilent('migrate:rollback', ['--path' => 'database/migrations','--force' => true, '--quiet' => true]);
        }
    }

    private function createTables(): void
    {
        $this->info('Migrating tables.');
        $this->callSilent('migrate', ['--path' => 'database/migrations', '--force' => true, '--quiet' => true]);
    }

    private function importer($field): ImporterInterface
    {
        $name =  "App\Watched\Importers\\". Str::studly("{$field}_importer");
        return new $name;
    }
}
