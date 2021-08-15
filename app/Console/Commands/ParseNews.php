<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\RequestLog;

class ParseNews extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse news rss and save to BD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function logRequest($method, $url, $response_code, $response_body) {
        $log = new RequestLog();
        $log->method = $method;
        $log->url = $url;
        $log->response_code = $response_code;
        $log->response_body = $response_body;
        $log->save();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $request_url = 'http://static.feed.rbc.ru/rbc/logical/footer/news.rss';
        $headers = array(
            'cache-control: max-age=0',
            'user-agent: Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/78.0.3904.97 Safari/537.36',
            'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3',
            'accept-encoding: deflate, br',
            'accept-language: ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7',
        );
        $ch = curl_init($request_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $data = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $this->logRequest('GET', $request_url, $http_code, $data);

        if ($http_code != 200) {
            return 0;
        }

        $rss = simplexml_load_string($data);
        foreach ($rss->channel->item as $item) {
            $image = null;
            foreach ($item->enclosure as $en) {
                if ($en['type'] == 'image/jpeg') {
                    $image = $en['url'];
                    break;
                }
            }
            $author = $item->author ?? null;
            DB::insert('INSERT INTO news (guid, title, description, pubdate, author, image)
                VALUES (?, ?, ?, ?, ?, ?) ON CONFLICT (guid) DO NOTHING',
                [   $item->guid,
                    $item->title,
                    $item->description,
                    date('Y-m-d H:i:s', strtotime($item->pubDate)),
                    $author,
                    $image]);
        }

        return 0;
    }
}

