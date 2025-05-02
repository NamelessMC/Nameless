<?php

abstract class ProfileWidgetBase extends AbstractWidget
{
    abstract public function initialise(User $user): void;

    final public function getPages(): array
    {
        return ['profile'];
    }
}
