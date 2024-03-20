<?php

namespace App\Http\Controllers\Admin;

use ApiResponse;
use App\Facades\AdminAsset;
use Illuminate\Http\Request;
use App\Contracts\AdminPage;
use App\Http\Controllers\FormBuilderController;
use App\Models\Popup;
use App\Tables\PopupTable;
use App\Form\Form;
use App\Form\Tools;
use Illuminate\Support\Facades\Cache;

class PopupController extends FormBuilderController
{
    protected $request;
    protected array $breadcrumb = [];
    protected $title = 'Posts';

    public function __construct(Request $request)
    {
        $this->breadcrumb[] = [
            'title' => __('Popup'),
            'url'   => '#'
        ];
        $this->middleware('can:blog.popup.view', ['index']);
        $this->middleware('can:blog.popup.edit', ['edit', 'update']);
        $this->middleware('can:blog.popup.create', ['create', 'store']);
        $this->middleware('can:blog.popup.delete', ['deletes']);
        $this->middleware('is-not-demo', ['only' => ['store', 'update', 'deletes']]);

        $this->request = $request;
        $this->breadcrumb[] = [
            'title'  => __('messages.popup'),
            'url'    => route('admin.popup.index'),
        ];
    }

    protected function index()
    {
        return app(AdminPage::class)
            ->title(__('messages.popup'))
            ->breadcrumb($this->breadcrumb)
            ->body(new PopupTable);
    }

    private function getHtmlScript()
    {
        return <<<JS
            $(function() {
                const default_val =  $('input[name="content"]').val();
                const default_w = $('input[name="width"]').val();
                const default_h = $('input[name="height"]').val();

                if(default_val) {
                    $('#popup-review').html(default_val);
                }

                $('#popup-review').css('width', default_w);
                $('#popup-review').css('height', default_h);

                $('input[name="width"]').on('input', function(e) {
                    const val = e.target.value;
                    $('#popup-review').css('width', val);
                });

                $('input[name="height"]').on('input', function(e) {
                    const val = e.target.value;
                    $('#popup-review').css('height', val);
                });

                $('input[name="content"]').on('input', function() {
                   $('#popup-review').html($(this).val());
                })
            });
        JS;
    }

    protected function form()
    {
        AdminAsset::addCustomScript($this->getHtmlScript());

        $statuses   = [0 => __('messages.inactive'), 1 => __('messages.active')];
        $form = new Form(new Popup());

        $form->text('name', __('messages.name'))
            ->rules('required|max:255');

        $form->text('order', __('messages.position'))
            ->attribute('type', 'number')
            ->default(0)
            ->rules('required|integer|min:0|max:999999');

        $form->text('position_x', __('messages.position_x'))
            ->default('50%')
            ->rules('required|string|max:20');

        $form->text('position_y', __('messages.position_y'))
            ->default('50%')
            ->rules('required|string|max:20');

        $form->text('width', __('messages.width'))
            ->placeholder("Eg: 1920px")
            ->default('100%')
            ->rules('required|string|max:20');

        $form->text('height', __('messages.height'))
            ->placeholder("Eg: 1080px")
            ->default('350px')
            ->rules('required|string|max:20');

        $form->quillEditor('content', __('messages.content'))->rules('required');

        $form->getLayout()->setCustomContent(__('popup_custom_content')."<div id='popup-review'><h5>".__('Popup Preview')."</h5></div>");

        $form->tools(function(Tools $tool) use ($statuses) {
            $tool->select('active', __('messages.active'))->options($statuses)->default(0);
        });

        $form->saved(function() {
            Cache::forget('popups');
        });

        return $form;
    }


    public function deletes()
    {
        $rows = $this->request->rows;
        Popup::whereIn('id', $rows)->delete();

        return ApiResponse::success();
    }
}
