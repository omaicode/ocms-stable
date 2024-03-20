<?php

namespace App\Http\Controllers\Admin;

use ApiResponse;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Contracts\AdminPage;
use App\Http\Controllers\FormBuilderController;
use App\Models\Post;
use App\Tables\PostTable;
use App\Form\Form;
use App\Form\Tools;

class PostController extends FormBuilderController
{
    protected $request;
    protected array $breadcrumb = [];
    protected $title = 'Posts';

    public function __construct(Request $request)
    {
        $this->breadcrumb[] = [
            'title' => __('Blog'),
            'url'   => '#'
        ];
        $this->middleware('can:blog.posts.view', ['index']);
        $this->middleware('can:blog.posts.edit', ['edit', 'update']);
        $this->middleware('can:blog.posts.create', ['create', 'store']);
        $this->middleware('can:blog.posts.delete', ['deletes']);
        $this->middleware('is-not-demo', ['only' => ['store', 'update', 'deletes']]);

        $this->request = $request;
        $this->breadcrumb[] = [
            'title'  => __('messages.posts'),
            'url'    => route('admin.blog.posts.index'),
        ];
    }

    protected function index()
    {
        return app(AdminPage::class)
        ->title(__('messages.posts'))
        ->breadcrumb($this->breadcrumb)
        ->body(new PostTable);
    }

    protected function form()
    {
        $statuses   = [0 => __('messages.draft'), 1 => __('messages.publish')];
        $categories = Category::where('active', true)->get()->pluck('name', 'id')->toArray();
        $form = new Form(new Post());
        $form->preview(true);
        $form->slug('title', 'slug', __('messages.title'))
              ->creationRules('required|unique:blog_posts,slug')
              ->updateRules('required|unique:blog_posts,slug,{{id}},id');
        $form->quillEditor('content', __('messages.content'));
        $form->text('seo_title', __('messages.seo_title'));
        $form->textarea('seo_description', __('messages.seo_description'))->rows(2);

        $form->tools(function(Tools $tool) use ($statuses, $categories) {
            $tool->select('publish', __('messages.status'))->options($statuses)->default(0);
            $tool->select('category_id', __('messages.category'))->options($categories);
            $tool->media('featured_image', __('messages.featured_image'), Post::class)->placeholder(__('messages.select_featured_image'));
        });

        return $form;
    }


    public function deletes()
    {
        $rows = $this->request->rows;
        Post::whereIn('id', $rows)->delete();

        return ApiResponse::success();
    }
}
