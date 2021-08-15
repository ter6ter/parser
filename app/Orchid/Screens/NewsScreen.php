<?php

namespace App\Orchid\Screens;

use Orchid\Screen\Actions\Link;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\TD;
use App\Models\News;

class NewsScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'News';

    /**
     * Query data.
     *
     * @return array
     */
    public function query(): array
    {
        return [
            'news' => News::filters()->defaultSort('pubdate', 'desc')->paginate()
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
            Layout::table('news', [
                TD::make('title', 'Заголовок')->width("20%"),
                TD::make('description', 'Описание')->width("32%"),
                TD::make('pubdate', 'Дата публикации (UTC)')->width("8%")->sort(),
                TD::make('author', 'Автор')->width("14%"),
                TD::make('image', 'Картинка')->width("15%"),
                TD::make('', 'Редактировать')
                    ->render(function ($item) {
                        return Link::make('Редактировать')
                            ->route('platform.news.edit', $item);
                    })
            ])
        ];
    }

    /**
     * @param News $item
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function remove(News $item)
    {
        $item->delete();

        Alert::info('Новость успешно удалена');

        return redirect()->route('platform.news');
    }
}
