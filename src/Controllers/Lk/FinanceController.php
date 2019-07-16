<?php

namespace Chernogolov\Fogcms\Controllers\Lk;

use Illuminate\Http\Request;
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

        if(!empty($this->current_account)) {

            if ($this->Charges($request)) {
                sleep(3);
                return redirect('finance');
            }
            $this->data['views'][] = $this->getCharges($request, true);
            $this->data['views'][] = $this->getPayments($request, true);

        }

        $this->title = __('Finance');
        return $this->index();
    }

    public function Charges(Request $request)
    {
        $post_data = $request->all();
        if ($post_data) {
            if (isset($post_data['generate-invoice'])) {
                $job = (new GenerateInvoice((object)$this->current_account, (int)$post_data['month']));
                dispatch($job);
                $request->session()->push('tmp_kvit', (int)$post_data['month']);
                return true;
            }
        }

    }

    public function getCharges(Request $request, $view = false)
    {
        $this->getAccounts();

        $this->title = __('Utilites');
        $tmp_kvit = session('tmp_kvit');

        if(!empty($this->current_account)) {
            if ($request->ajax() || $view)
                return view('fogcms::lk/pages/charges', ['account' => $this->current_account, 'tmp_kvit' => $tmp_kvit]);
            else {
                if ($this->Charges($request))
                    return redirect('charges');

                $this->data['views'][] = view('fogcms::lk/pages/charges', ['account' => $this->current_account, 'tmp_kvit' => $tmp_kvit]);
                return $this->index();
            }
        }
        else
            return $this->index();
    }

    public function getPayments(Request $request, $view = false)
    {
        $this->getAccounts();

        $this->title = __('Payments');

        if(!empty($this->current_account)) {
            if ($request->ajax() || $view)
                return view('fogcms::lk/pages/payments', $this->current_account);
            else {
                $this->data['views'][] = view('fogcms::lk/pages/payments', $this->current_account);
                return $this->index();
            }
        }
        else
            return $this->index();
    }
}