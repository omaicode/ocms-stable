<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Contracts\AdminPage;
use Illuminate\Routing\Controller;

class FormBuilderController extends Controller
{
    use AuthorizesRequests;

    protected array $breadcrumb = [];
    protected $title = '';

    protected function index()
    {

    }

    protected function show($id)
    {
        return app(AdminPage::class)
        ->title(__($this->title))
        ->breadcrumb($this->breadcrumb)
        ->body($this->form()->edit($id));
    }

    protected function edit($id)
    {
        $breadcrumb = $this->breadcrumb;
        $breadcrumb[] = [
            'title' => __('messages.edit'),
            'url'   => '#'
        ];
        $breadcrumb[] = [
            'title' => "#".$id,
            'url'   => request()->url()
        ];
        $this->form()->title(__('messages.edit').' #'.$id);

        return app(AdminPage::class)
        ->title(' #'.$id.' | '.__($this->title()))
        ->breadcrumb($breadcrumb)
        ->body($this->form()->edit($id));
    }

    protected function create()
    {
        $breadcrumb = $this->breadcrumb;
        $breadcrumb[] = [
            'title' => __('messages.create'),
            'url'   => request()->url()
        ];

        $this->form()->title(__('messages.create'));

        return app(AdminPage::class)
        ->title(__('messages.create').' | '.__($this->title()))
        ->breadcrumb($breadcrumb)
        ->body($this->form());
    }

    /**
     * Get content title.
     *
     * @return string
     */
    protected function title()
    {
        return $this->title;
    }

    /**
     * Form
     *
     * @return null | \App\Form\Form
     */
    protected function form()
    {
        return null;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->form()->store();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        return $this->form()->update($id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->form()->destroy($id);
    }
}
