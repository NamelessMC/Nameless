<?php
/**
 * Provides support for giving and receiving reactions on profile posts.
 *
 * @package NamelessMC\Misc
 * @author Aberdeener
 * @version 2.2.0
 * @license MIT
 * @see ReactionContext, ReactionContextsManager
 */
class ProfilePostReactionContext extends ReactionContext
{
    public function name(): string
    {
        return 'profile_post';
    }

    public function friendlyName(Language $language): string
    {
        return $language->get('user', 'profile_posts_score');
    }

    public function getUserReceived(User $user): array
    {
        return DB::getInstance()->query('SELECT r.reaction_id FROM nl2_user_profile_wall_posts_reactions r JOIN nl2_user_profile_wall_posts w ON r.post_id = w.id WHERE w.author_id = ?', [
            $user->data()->id,
        ])->results();
    }

    public function getUserGiven(User $user): array
    {
        return DB::getInstance()->get('user_profile_wall_posts_reactions', ['user_id', $user->data()->id])->results();
    }

    public function validateReactable(int $reactable_id, User $user)
    {
        // TODO check blocked?
        $result = DB::getInstance()->get('user_profile_wall_posts', ['id', $reactable_id]);

        if ($result->exists()) {
            return $result->first();
        }

        return false;
    }

    public function hasReacted(User $user, Reaction $reaction, int $reactable_id)
    {
        $result = DB::getInstance()->get('user_profile_wall_posts_reactions', [
            ['post_id', $reactable_id], ['user_id', $user->data()->id], ['reaction_id', $reaction->id],
        ]);

        if ($result->exists()) {
            return $result->first()->id;
        }

        return false;
    }

    public function giveReaction(User $user, User $receiver, Reaction $reaction, int $reactable_id): void
    {
        DB::getInstance()->insert('user_profile_wall_posts_reactions', [
            'post_id' => $reactable_id,
            'user_id' => $user->data()->id,
            'reaction_id' => $reaction->id,
            'time' => date('U'),
        ]);
    }

    public function deleteReaction(int $reactable_reaction_id): void
    {
        DB::getInstance()->delete('user_profile_wall_posts_reactions', $reactable_reaction_id);
    }

    public function getAllReactions(int $reactionable_id): array
    {
        return DB::getInstance()->get('user_profile_wall_posts_reactions', ['post_id', $reactionable_id])->results();
    }

    public function reactionUserIdColumn(): string
    {
        return 'user_id';
    }

    public function determineReceiver(object $reactable): User
    {
        return new User(DB::getInstance()->get('user_profile_wall_posts', ['id', $reactable->id])->first()->author_id);
    }
}
