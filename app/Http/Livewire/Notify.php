<?php

namespace App\Http\Livewire;

trait Notify
{
    protected $noticeMessage = null;
    protected $noticeTitle = null;
    protected $noticeType = 'success';

    protected function notify()
    {
        $this->dispatchBrowserEvent('notify', [
            'type' => $this->noticeType,
            'title' => $this->noticeTitle,
            'message' => $this->noticeMessage,
        ]);

        $this->resetNotice();
    }

    protected function notification($type = 'success', $title = 'Success', $message = null)
    {
        $this->noticeType = $type;
        $this->noticeTitle = $title;
        $this->noticeMessage = $message;

        return $this;
    }

    protected function resetNotice()
    {
        $this->noticeType = 'success';
        $this->noticeTitle = null;
        $this->noticeMessage = null;
    }

    protected function notifyError($title = 'Error', $message = null)
    {
        $this->notification('error', $title, $message)->notify();
    }

    public function success($title = 'Success', $message = null)
    {
        $this->notification('success', $title, $message)->notify();
    }
}
