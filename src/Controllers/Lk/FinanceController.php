<?php

namespace Chernogolov\Fogcms\Controllers\Lk;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

use Chernogolov\Fogcms\Controllers\LkController;
use Chernogolov\Fogcms\Notifications\AddRecord;
use Chernogolov\Fogcms\Jobs\GenerateInvoice;


use Chernogolov\Fogcms\Records;
use Chernogolov\Fogcms\Attr;
use Chernogolov\Fogcms\Reg;
use Chernogolov\Fogcms\User;
use Chernogolov\Fogcms\RegsUsers;
use Chernogolov\Fogcms\Comments;


class FinanceController extends LkController
{
    public $title;

    public function __construct()
    {
        parent::__construct();
    }

    public function financeCenter(Request $request)
    {
        $this->getAccounts();

        $this->data['views'][] = $this->getCharges($request, true);
        $this->data['views'][] = $this->getPayments($request, true);

        $this->title = __('Finance');

        return $this->index();
    }

    public function getCharges(Request $request, $view = false)
    {
        $this->getAccounts();

        $this->title = __('Utilites');

        $post_data = $request->all();
        if ($post_data) {
            if (isset($post_data['generate-invoice'])) {
                $job = (new GenerateInvoice((object)$this->current_account, (int)$post_data['month']));
                dispatch($job);
            }
        }

        if($request->ajax() || $view)
            return view('fogcms::lk/pages/charges', $this->current_account);
        else
        {
            $this->data['views'][] = view('fogcms::lk/pages/charges', $this->current_account);
            return $this->index();
        }
    }

    public function getPayments(Request $request, $view = false)
    {
        $this->title = __('Payments');
        if($request->ajax() || $view)
            return view('fogcms::lk/pages/payments', []);
        else
        {
            $this->data['views'][] = view('fogcms::lk/pages/payments', []);
            return $this->index();
        }
    }
}