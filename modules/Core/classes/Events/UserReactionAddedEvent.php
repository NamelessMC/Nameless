<?php

class UserReactionAddedEvent extends AbstractEvent {

    public User $giver;
    public User $reciever;
    public Reaction $reaction;
    public string $context;

    private const VALID_CONTEXTS = [
        'forum_post',
        'profile_post',
    ];

    public static function description(): string {
        return (new Language())->get('admin', 'reacted_event_info');
    }

    public function __construct(
        User $giver,
        User $reciever,
        Reaction $reaction,
        string $context
    ) {
        $this->giver = $giver;
        $this->reciever = $reciever;
        $this->reaction = $reaction;

        if (!in_array($context, self::VALID_CONTEXTS)) {
            throw new InvalidArgumentException("Invalid context provided: {$context}.");
        }

        $this->context = $context;
    }

}
