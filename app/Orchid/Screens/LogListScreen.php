<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use App\Models\RequestLog;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;

class LogListScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'LogListScreen';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'logs' => RequestLog::paginate()
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::table('logs', [
                TD::make('method', 'Метод'),
                TD::make('url', 'Url')->width("15%"),
                TD::make('response_code', 'Response code'),
                TD::make('response_body', 'Response body')
                    ->width("60%")
                    ->render(function ($item) {
                        $data = mb_substr($item->response_body, 0, 200);
                        $data = htmlspecialchars($data) . '...';
                        return $data;
                    }),
                TD::make('created_at', 'Дата и время')
                    ->render(function ($item) {
                        return date('Y-m-d H:i:s', strtotime($item->created_at));
                }),
            ])
        ];
    }
}
