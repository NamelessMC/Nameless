<?php

abstract class CustomAuthEndpoint extends EndpointBase {

    final public function isAuthorised(Nameless2API $api): bool {
        return $this->authorise($api);
    }

    abstract public function authorise(Nameless2API $api): bool;
}
