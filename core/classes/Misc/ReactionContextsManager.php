<?php

class ReactionContextsManager extends Instanceable {

    /** @var ReactionContext[]  */
    private array $_contexts = [];

    public function provideContext(ReactionContext $context): void {
        $this->_contexts[$context->name()] = $context;
    }

    public function getContext(string $name): ?ReactionContext {
        if (!isset($this->_contexts[$name])) {
            throw new InvalidArgumentException('Invalid reaction context name: ' . $name);
        }

        return $this->_contexts[$name];
    }

    public function getContexts(): array {
        return $this->_contexts;
    }

    public function validContextNames(): array {
        return array_map(static function (ReactionContext $context) {
            return $context->name();
        }, $this->_contexts);
    }

    public function validContextFriendlyNames(Language $language): array {
        return array_map(static function (ReactionContext $context) use ($language) {
            return $context->friendlyName($language);
        }, $this->_contexts);
    }
}
