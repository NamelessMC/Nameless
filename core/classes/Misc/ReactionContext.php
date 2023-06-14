<?php

abstract class ReactionContext {

    abstract public function name(): string;

    abstract public function friendlyName(Language $language): string;

    public function isEnabled(): bool {
        return true;
    }

    abstract public function getUserReceived(User $user): array;

    abstract public function getUserGiven(User $user): array;

    /**
     * @return false|object Returns false if the reactable does not exist or is not viewable to the user, otherwise returns the reactable object.
     */
    abstract public function validateReactable(int $reactable_id, User $user);

    /**
     * @return false|int Returns false if the user has not reacted, otherwise returns the reactable reaction ID.
     */
    abstract public function hasReacted(User $user, Reaction $reaction, int $reactable_id);

    abstract public function giveReaction(User $user, User $receiver, Reaction $reaction, int $reactable_id): void;

    abstract public function deleteReaction(int $reactable_reaction_id): void;

    abstract public function getAllReactions(int $reactionable_id): array;

    abstract public function reactionUserIdColumn(): string;

    abstract public function determineReceiver(object $reactable): User;
}
