<?php

class UserReactionDeletedEvent extends AbstractEvent {

    public User $user;
    public Reaction $reaction;
    public string $context;

    public static function description(): string {
        return (new Language())->get('admin', 'reaction_deleted_event_info');
    }

    public function __construct(
        User $user,
        Reaction $reaction,
        string $context
    ) {
        $this->user = $user;
        $this->reaction = $reaction;

        if (!in_array($context, ReactionContextsManager::getInstance()->validContextNames())) {
            throw new InvalidArgumentException("Invalid context provided: {$context}.");
        }

        $this->context = $context;
    }

}
