<?php
namespace App\Tables;

use App\Supports\TableBuilder;
use App\Models\Post;
use OCMS\TableBuilder\Column;

class PostTable extends TableBuilder
{
    /**
     * Model namespace
     * @var string
     */
    protected $model = Post::class;

    /**
     * Set table header title
     * @var string
     */
    protected string $header_title = 'messages.posts';

    /**
     * Show actions
     * @var bool
     */
    protected bool $show_actions = true;

    public function __construct(array $options = [])
    {
        parent::__construct($options);

        // Set create URL
        $this->create_url = route('admin.blog.posts.create');

        // Set delete URL (method => POST)
        $this->delete_url = route('admin.blog.posts.destroy');

        // Set edit URL. See documentation for more details
        $this->edit_url = route('admin.blog.posts.edit', ['post' => ':id']);

        // Apply custom query
        $this->applyQuery(function($query, $request) {
            if($request->filled('search')) {
                $query->where('title', 'LIKE', '%'.$request->search.'%')
                ->orWhere('slug', 'LIKE',  '%'.$request->search.'%');
            }
        });
    }

    /**
     * Add columns to table
     *
     * @return Column[]
     * @throws BindingResolutionException
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    protected function columns()
    {
        return [
            new Column("id", __('ID')),
            new Column("title", __('title')),
            new Column("publish", __('Status'), fn($item) => $item->publish ? __('messages.publish') : __('messages.draft')),
            new Column("created_at", __('Created at'), fn($item) => $item->created_at->format('Y-m-d H:i:s')),
            new Column("updated_at", __('Updated at'), fn($item) => $item->updated_at->format('Y-m-d H:i:s')),
        ];
    }
}
