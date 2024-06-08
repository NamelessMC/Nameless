<?php
/**
 * Represents a context in which reactions can be given and received.
 *
 * @package NamelessMC\Misc
 * @author Aberdeener
 * @version 2.2.0
 * @license MIT
 */
abstract class ReactionContext
{
    /**
     * @return string Name of the reaction context which should be used when calling the `/queries/reactions` endpoint as the `context` parameter.
     */
    abstract public function name(): string;

    /**
     * @param  Language $language Language to get the friendly name in.
     * @return string   Friendly name of the reaction context. Used when displaying scores in the Reactions profile widget
     */
    abstract public function friendlyName(Language $language): string;

    /**
     * @return bool Whether this reaction context is enabled. If false, the reaction context will not be available for use.
     */
    public function isEnabled(): bool
    {
        return true;
    }

    /**
     * Get all the reactions that the user has received in this context.
     * This should return data from the contexts table which contains the reactionable ID,
     * the reaction ID, and the user ID of the user who gave the reaction.
     *
     * @param  User  $user User to get the reactions for.
     * @return array Array of reactions that the user has received in this context.
     */
    abstract public function getUserReceived(User $user): array;

    /**
     * Get all the reactions that the user has given in this context.
     * This should return data from the contexts table which contains the reactionable ID,
     * the reaction ID, and the user ID of the user who gave the reaction.
     *
     * @param  User  $user User to get the reactions for.
     * @return array Array of reactions that the user has given in this context.
     */
    abstract public function getUserGiven(User $user): array;

    /**
     * Determine whether the user can react to the given reactable.
     * Reactable meaning some model that can be reacted to: post, profile post, resource, etc.
     *
     * @return false|object Returns false if the reactable does not exist or is not viewable to the user, otherwise returns the reactable object.
     */
    abstract public function validateReactable(int $reactable_id, User $user);

    /**
     * Determine whether the user has reacted to the given reactable.
     *
     * @return false|int Returns false if the user has not reacted, otherwise returns the reactable reaction ID.
     */
    abstract public function hasReacted(User $user, Reaction $reaction, int $reactable_id);

    /**
     * Record a reaction being given for a given reactable.
     *
     * @param User     $user         User who is giving the reaction.
     * @param User     $receiver     User who is receiving the reaction (generally the owner of the reactionable object).
     * @param Reaction $reaction     Reaction to give.
     * @param int      $reactable_id ID of the reactable that the user is reacting to.
     */
    abstract public function giveReaction(User $user, User $receiver, Reaction $reaction, int $reactable_id): void;

    /**
     * Delete a reaction given in this context.
     *
     * @param int $reactable_reaction_id ID of the row in the contexts reactions table to delete.
     */
    abstract public function deleteReaction(int $reactable_reaction_id): void;

    /**
     * Get all the reactions that have been given to the given reactionable.
     *
     * @param  int   $reactionable_id ID of the reactionable to get the reactions for.
     * @return array Array of reactions that have been given to the given reactionable.
     */
    abstract public function getAllReactions(int $reactionable_id): array;

    /**
     * Name of the column which contains the owner of the reactionable object in the reactionables reaction table.
     * For example, the `profile_wall_posts_reaction` table stores the ID of the user who added the reaction as the `user_id`,
     * whereas `forum_post_reactions` stores the ID of the user who added the reaction as the `user_given`.
     *
     * @return string Name of the column
     */
    abstract public function reactionUserIdColumn(): string;

    /**
     * Determine which User owns the given reactable.
     * Used to give them a reaction point.
     *
     * @param  object $reactable Reactable object to get the receiver for.
     * @return User   User who owns the given reactable.
     */
    abstract public function determineReceiver(object $reactable): User;
}
