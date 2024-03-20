<?php

namespace App\Http\Controllers\Admin;

use ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use App\Contracts\AdminPage;
use App\Models\Repositories\Interfaces\AdminActivityRepository;
use App\Tables\ActivityTable;
use OCMS\LaravelLogViewer\LaravelLogViewer;

class SystemController extends Controller
{
    /**
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * @var LaravelLogViewer
     */
    private $log_viewer;

    public function __construct(Request $request)
    {
        $this->middleware('can:system.information.view', ['information']);
        $this->middleware('can:system.activity.view', ['activities']);
        $this->middleware('can:system.error_log.view', ['logs']);

        $this->log_viewer = new LaravelLogViewer();
        $this->request = $request;
    }

    public function information()
    {
        return view('admin.pages.system.information');
    }

    public function activities(AdminPage $page)
    {
        $table = new ActivityTable;
        $breadcrumb = [['title' => __('menu.system'), 'url' => '#'], ['title' => __('menu.system.activities'), 'url' => '#', 'active' => true]];

        return $page
        ->title(__('menu.system.activities'))
        ->breadcrumb($breadcrumb)
        ->body($table);
    }

    public function deleteActivity(Request $request, AdminActivityRepository $activity)
    {
        $rows = $request->get('rows', []);
        $activity->getModel()->whereIn('id', $rows)->delete();

        return ApiResponse::success();
    }

    public function logs(AdminPage $page)
    {
        $breadcrumb = [['title' => __('menu.system'), 'url' => '#'], ['title' => __('menu.system.logs'), 'url' => '#', 'active' => true]];
        $folderFiles = [];
        if ($this->request->input('f')) {
            $this->log_viewer->setFolder(Crypt::decrypt($this->request->input('f')));
            $folderFiles = $this->log_viewer->getFolderFiles(true);
        }
        if ($this->request->input('l')) {
            $this->log_viewer->setFile(Crypt::decrypt($this->request->input('l')));
        }

        if ($early_return = $this->earlyReturn()) {
            return $early_return;
        }

        $data = [
            'logs' => $this->log_viewer->all(),
            'folders' => $this->log_viewer->getFolders(),
            'current_folder' => $this->log_viewer->getFolderName(),
            'folder_files' => $folderFiles,
            'files' => $this->log_viewer->getFiles(true),
            'current_file' => $this->log_viewer->getFileName(),
            'standardFormat' => true,
            'structure' => $this->log_viewer->foldersAndFiles(),
            'storage_path' => $this->log_viewer->getStoragePath(),

        ];

        if ($this->request->wantsJson()) {
            return $data;
        }

        if (is_array($data['logs']) && count($data['logs']) > 0) {
            $firstLog = reset($data['logs']);
            if ($firstLog) {
                if (!$firstLog['context'] && !$firstLog['level']) {
                    $data['standardFormat'] = false;
                }
            }
        }        

        return $page
        ->title(__('menu.system.logs'))
        ->breadcrumb($breadcrumb)
        ->push('styles', 'admin.pages.system.log_styles')
        ->push('scripts', 'admin.pages.system.log_scripts')
        ->body('admin.pages.system.log', $data);
    }

    /**
     * @return bool|mixed
     * @throws \Exception
     */
    private function earlyReturn()
    {
        if ($this->request->input('f')) {
            $this->log_viewer->setFolder(Crypt::decrypt($this->request->input('f')));
        }

        if ($this->request->input('dl')) {
            return $this->download($this->pathFromInput('dl'));
        } elseif ($this->request->has('clean')) {
            app('files')->put($this->pathFromInput('clean'), '');
            return $this->redirect(url()->previous());
        } elseif ($this->request->has('del')) {
            app('files')->delete($this->pathFromInput('del'));
            return $this->redirect($this->request->url());
        } elseif ($this->request->has('delall')) {
            $files = ($this->log_viewer->getFolderName())
                        ? $this->log_viewer->getFolderFiles(true)
                        : $this->log_viewer->getFiles(true);
            foreach ($files as $file) {
                app('files')->delete($this->log_viewer->pathToLogFile($file));
            }
            return $this->redirect($this->request->url());
        }
        return false;
    }

    /**
     * @param string $input_string
     * @return string
     * @throws \Exception
     */
    private function pathFromInput($input_string)
    {
        return $this->log_viewer->pathToLogFile(Crypt::decrypt($this->request->input($input_string)));
    }

    /**
     * @param $to
     * @return mixed
     */
    private function redirect($to)
    {
        if (function_exists('redirect')) {
            return redirect($to);
        }

        return app('redirect')->to($to);
    }

    /**
     * @param string $data
     * @return mixed
     */
    private function download($data)
    {
        if (function_exists('response')) {
            return response()->download($data);
        }

        // For laravel 4.2
        return app('\Illuminate\Support\Facades\Response')->download($data);
    }    
}
