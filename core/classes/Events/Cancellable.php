<?php

trait Cancellable {

    private bool $_cancelled = false;
    private string $_reason = '';

    public function setCancelled(bool $cancelled, string $reason = ''): void {
        $this->_cancelled = $cancelled;
        $this->_reason = $reason;
    }

    public function isCancelled(): bool {
        return $this->_cancelled;
    }

    public function getCancelledReason(): string {
        return $this->_reason;
    }
}