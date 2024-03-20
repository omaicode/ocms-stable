<?php

namespace App\Http\Controllers\Admin;

use ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Contracts\AdminPage;
use App\Http\Controllers\FormBuilderController;
use App\Models\Category;
use App\Tables\CategoryTable;
use App\Form\Form;

class CategoryController extends FormBuilderController
{
    protected $request;
    protected array $breadcrumb = [[
        'title' => 'Blog',
        'url'   => '#'
    ]];
    protected $title = 'Categories';

    public function __construct(Request $request)
    {
        $this->middleware('can:blog.categories.view', ['index']);
        $this->middleware('can:blog.categories.edit', ['edit', 'update']);
        $this->middleware('can:blog.categories.create', ['create', 'store']);
        $this->middleware('can:blog.categories.delete', ['deletes']);
        $this->middleware('is-not-demo', ['only' => ['store', 'update', 'deletes']]);

        $this->request = $request;
        $this->breadcrumb[] = [
            'title'  => __('messages.categories'),
            'url'    => route('admin.blog.categories.index'),
        ];
    }

    protected function index()
    {
        return app(AdminPage::class)
        ->title(__('messages.categories'))
        ->breadcrumb($this->breadcrumb)
        ->body(new CategoryTable);
    }

    protected function form()
    {
        $form = new Form(new Category());
        $form->overrides('name', 'slug');

        $form->text('name', __('messages.name'))
              ->creationRules('required|string|max:255')
              ->updateRules('required|string|max:255');
        $form->select('active', __('messages.status'))
        ->options([1 => __('messages.active'),  0 => __('messages.inactive')])
        ->default(1);

        $form->submitted(function(Form $form) {
            $form->ignore(['name', 'slug']);
        });

        $form->saving(function(Form $form) {
            $name_input  = $this->request->get('name');
            $form->input('slug', Str::slug($name_input));
            $form->input('name', $name_input);
        });

        return $form;
    }


    public function deletes()
    {
        $rows = $this->request->rows;
        Category::whereIn('id', $rows)->delete();

        return ApiResponse::success();
    }
}
