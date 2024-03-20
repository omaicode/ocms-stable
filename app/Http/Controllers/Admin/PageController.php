<?php

namespace App\Http\Controllers\Admin;

use ApiResponse;
use Illuminate\Http\Request;
use App\Contracts\AdminPage;
use App\Facades\AdminAsset;
use App\Http\Controllers\FormBuilderController;
use App\Enums\PageStatusEnum;
use App\Enums\PageTemplateEnum;
use App\Models\Repositories\Interfaces\PageRepository;
use App\Tables\PageTable;
use App\Form\Form;
use App\Form\Tools;
use App\Enums\ContentTypeEnum;
use App\Models\Page;

class PageController extends FormBuilderController
{
    protected $request;
    protected $repository;
    protected array $breadcrumb = [];
    protected $title = 'Pages';

    public function __construct(Request $request, PageRepository $pageRepository)
    {
        $this->middleware('can:page.view', ['index']);
        $this->middleware('can:page.edit', ['edit', 'update']);
        $this->middleware('can:page.create', ['create', 'store']);
        $this->middleware('can:page.delete', ['deletes']);

        $this->request = $request;
        $this->repository = $pageRepository;
        $this->breadcrumb[] = [
            'title'  => __('messages.pages'),
            'url'    => route('admin.pages.index'),
        ];
    }

    protected function getScript()
    {
        return <<<SCRIPT
        var content_type = document.querySelector('select[name="content_type"]');
        var quillEditor = document.querySelector('[data-quill-editor="content"]');
        var codeEditor = document.querySelector('[data-codemirror="content"]');

        function triggerEditor(value) {
            if(value == 'default') {
                quillEditor.style.display = 'block';
                quillEditor.querySelector('input[name="content"]').disabled = false;

                codeEditor.style.display = 'none';
                codeEditor.querySelector('textarea[name="content"]').disabled = true;
            } else {
                quillEditor.style.display = 'none';
                quillEditor.querySelector('input[name="content"]').disabled = true;

                codeEditor.style.display = 'block';
                codeEditor.querySelector('textarea[name="content"]').disabled = false;
            }
        }

        content_type.addEventListener('change', function() {
            var value = content_type.value;
            triggerEditor(value);
        });

        triggerEditor(content_type.value);
        SCRIPT;
    }

    protected function index()
    {
        return app(AdminPage::class)
        ->title(__('messages.pages'))
        ->breadcrumb($this->breadcrumb)
        ->body(new PageTable);
    }

    protected function form()
    {
        $statuses = PageStatusEnum::asSelectArray();
        $templates = PageTemplateEnum::asSelectArray();
        $content_types = [
            'default' => __('messages.default'),
            'partial' => __('messages.partial'),
        ];


        $form = new Form(new Page());
        $form->preview(true);

        $form->slug('name', 'slug', __('messages.name'))
              ->creationRules('required|unique:pages,slug')
              ->updateRules('required|unique:pages,slug,{{id}},id');
        $form->select('content_type', __('messages.content_type'))->options($content_types)->default(ContentTypeEnum::DEFAULT);
        $form->codeMirror('content', __('messages.content'));
        $form->quillEditor('content', __('messages.content'));
        $form->text('seo_title', __('messages.seo_title'));
        $form->textarea('seo_description', __('messages.seo_description'))->rows(2);

        $form->tools(function(Tools $tool) use ($statuses, $templates) {
            $tool->select('status', __('messages.status'))->options($statuses)->default(PageStatusEnum::DRAFT);
            $tool->select('template', __('messages.template'))->options($templates)->default(PageTemplateEnum::DEFAULT);
            $tool->media('featured_image', __('messages.featured_image'), Page::class)->placeholder(__('messages.select_featured_image'));
        });

        AdminAsset::addCustomScript($this->getScript());
        return $form;
    }


    public function deletes()
    {
        $rows = $this->request->rows;
        $this->repository
        ->getModel()
        ->whereIn('id', $rows)
        ->delete();

        return ApiResponse::success();
    }
}
