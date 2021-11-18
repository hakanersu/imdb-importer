<?php

namespace App\Commands;

use App\Watched\Importers\TimePassed;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\DB;
use LaravelZero\Framework\Commands\Command;

class Search extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'import:search';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Add full text search';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Starting adding full text search.');

        $now = now();

        DB::raw(DB::statement('ALTER TABLE titles ADD COLUMN tsv_title_text tsvector'));
        DB::raw(DB::statement('CREATE INDEX tsv_title_text_idx ON titles USING gin(tsv_title_text)'));
        DB::raw(DB::statement("UPDATE titles SET tsv_title_text = setweight(to_tsvector(coalesce(primary_title,'')), 'A') || setweight(to_tsvector(coalesce(original_title,'')), 'B')"));

        $this->output->writeln('<comment>Casting columns process finished in '.TimePassed::took($now).'.</comment>');
    }

    /**
     * Define the command's schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
