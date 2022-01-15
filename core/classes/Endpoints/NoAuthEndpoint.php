<?php

class NoAuthEndpoint extends EndpointBase {

    final public function isAuthorised(Nameless2API $api): bool {
        return true;
    }
}
