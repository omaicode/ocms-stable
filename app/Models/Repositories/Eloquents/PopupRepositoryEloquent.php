<?php

namespace App\Models\Repositories\Eloquents;

use Illuminate\Support\Facades\Cache;
use OCMS\Repository\Eloquent\BaseRepository;
use OCMS\Repository\Criteria\RequestCriteria;
use App\Models\Repositories\Interfaces\PopupRepository;
use App\Models\Popup;
use App\Validators\PopupValidator;

/**
 * Class PopupRepositoryEloquent.
 *
 * @package namespace App\Models\Repositories\Eloquents;
 */
class PopupRepositoryEloquent extends BaseRepository implements PopupRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Popup::class;
    }



    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    /**
     * Render popup css style
     * @return string
     */
    public function style() : string
    {
        $style_path = adminAsset("vendors/ok-popup/ok-popup.css?v=1.0.1");

        return <<<HTML
        <link rel="stylesheet" href="{$style_path}" >
        HTML;
    }

    /**
     * Render popup js script
     * @return string
     */
    public function script() : string
    {
        $js_path = adminAsset("vendors/ok-popup/ok-popup.js?v=1.0.0");
        $showOnceText = __("Don't show it today");
        $closeText = __('Close');

        $popups = Cache::rememberForever('popups', function() {
            return $this->makeModel()->activated()->get();
        });

        if($popups->count() <= 0) return '';
        $popups = json_encode($popups->toArray());

        return <<<HTML
        <script src="{$js_path}"></script>
        <script>
            $(function() {
               const popups = {$popups};
               const okPopup = new OkPopup(popups, {
                   showOnceText: '{$showOnceText}',
                   closeText: '{$closeText}'
               });
            });
        </script>
        HTML;
    }
}
