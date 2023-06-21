<?php

class UserReactionAddedEvent extends AbstractEvent {

    public User $giver;
    public User $receiver;
    public Reaction $reaction;
    public string $context;

    public static function description(): string {
        return (new Language())->get('admin', 'reaction_added_event_info');
    }

    public function __construct(
        User $giver,
        User $receiver,
        Reaction $reaction,
        string $context
    ) {
        $this->giver = $giver;
        $this->receiver = $receiver;
        $this->reaction = $reaction;

        if (!in_array($context, ReactionContextsManager::getInstance()->validContextNames())) {
            throw new InvalidArgumentException("Invalid context provided: {$context}.");
        }

        $this->context = $context;
    }

}
