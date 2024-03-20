<?php

namespace App\Http\Controllers\Application;

use App\Facades\PPurio;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Emails\ContactMail;
use App\Models\Repositories\Interfaces\ContactRepository;
use Throwable;

class ContactController extends Controller
{
    protected $request;
    protected $repository;

    public function __construct(Request $request, ContactRepository $repository)
    {
        $this->request = $request;
        $this->repository = $repository;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable|RedirectResponse
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:30',
            'email'   => 'required|email|max:100',
            'phone'   => 'required|digits_between:10,13',
            'company_name' => 'required|string|max:50',
            'content' => 'required|max:1000'
        ]);

        $this->repository->create($data);

        if(config('app.notification_method') === 'email' && boolval(config('mail.enable', false))) {
            try {
                $data['subject'] = __('New consultation request arrived');
                $recipient = config('theme.theme.email');
                Mail::to($recipient)->send(new ContactMail($data));
            } catch (Throwable $e) {
                Log::error($e);
            }
        }

        if(config('app.notification_method') === 'sms') {
            PPurio::sendSMS([
               'content' => config('ppurio.default_message', '컨설팅 요청이 도착했습니다.')
            ]);
        }

        $queryParams = [];
        $hashFragment = '#contact-session';
        $previousUrl = url()->previous();
        $parsedUrl = parse_url($previousUrl);
        
        if(isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $existingQueryParams);
            $queryParams = array_merge($existingQueryParams, $queryParams);
        }
        
        $queryString = http_build_query($queryParams);
        $redirectUrl = $parsedUrl['scheme'] . '://' . $parsedUrl['host'] . (isset($parsedUrl['path']) ? $parsedUrl['path'] : '') . '?' . $queryString . $hashFragment;

        return redirect($redirectUrl)->with('contact_submitted', true);
    }
}
