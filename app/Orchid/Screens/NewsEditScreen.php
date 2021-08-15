<?php

namespace App\Orchid\Screens;

use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Alert;
use Orchid\Screen\Actions\Button;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use App\Models\News;

class NewsEditScreen extends Screen
{
    /**
     * Display header name.
     *
     * @var string
     */
    public $name = 'NewsEditScreen';

    /**
     * Query data.
     *
     * @param News $item
     * @return array
     */
    public function query(News $item): array
    {
        return [
            'item' => $item
        ];
    }

    /**
     * Button commands.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): array
    {
        return [
            Button::make('Редактировать')
                ->icon('note')
                ->method('edit'),
            Button::make('Удалить')
                ->icon('trash')
                ->method('remove')
        ];
    }

    /**
     * Views.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): array
    {
        return [
            Layout::rows([
                Input::make('item.title')
                    ->title('Заголовок'),

                TextArea::make('item.description')
                    ->title('Описание')
                    ->rows(5),

                Input::make('item.author')
                    ->title('Автор'),

                Input::make('item.image')
                    ->title('Картинка'),

                Input::make('item.pubdate')
                    ->title('Дата публикации'),
            ])
        ];
    }

    /**
     * @param News    $item
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(News $item, Request $request)
    {
        $item->fill($request->get('item'))->save();

        Alert::info('Успешно отредактировано');

        return redirect()->route('platform.news');
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
