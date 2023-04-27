<?php

trait Cancellable {

    private bool $cancelled = false;
    private string $reason = '';

    public function setCancelled(bool $cancelled, string $reason = ''): void {
        $this->cancelled = $cancelled;
        $this->reason = $reason;
    }

    public function isCancelled(): bool {
        return $this->cancelled;
    }

    public function getCancelledReason(): string {
        return $this->reason;
    }
}