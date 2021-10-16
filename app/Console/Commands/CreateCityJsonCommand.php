<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CreateCityJsonCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:city_json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $resas_api_key = env('RESAS_API_KEY');
        if (is_null($resas_api_key) || $resas_api_key === '') {
            $this->error('RESAS_API_KEY is not set.');
            return Command::FAILURE;
        }

        $context = stream_context_create(array(
            'http' => [
                'header'  => "X-API-KEY: {$resas_api_key}"
            ]
        ));
        $api_base_url = 'https://opendata.resas-portal.go.jp/';

        // 都道府県一覧を取得
        $prefs = file_get_contents("{$api_base_url}api/v1/prefectures", false, $context);
        $prefs = json_decode($prefs, true);
        if (!isset($prefs['result']) || !is_array($prefs['result']) || count($prefs['result']) <= 0) {
            $this->error('pref get error.');
            return Command::FAILURE;
        }

        foreach ($prefs['result'] as $pref) {
            sleep(1);

            // 都道府県に紐づく市区町村一覧を取得
            $cities = file_get_contents("{$api_base_url}api/v1/cities?prefCode={$pref['prefCode']}", false, $context);
            $cities = json_decode($cities, true);
            if (!isset($cities['result']) || !is_array($cities['result']) || count($cities['result']) <= 0) {
                $this->error('city get error.');
                return Command::FAILURE;
            }

            // Git管理したいので直でpublic以下に保存
            Storage::disk('public_json')->put("{$pref['prefCode']}.json", collect($cities['result'])->pluck('cityName'));
        }

        return Command::SUCCESS;
    }
}
