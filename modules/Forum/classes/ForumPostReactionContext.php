<?php
/**
 * Provides support for giving and receiving reactions on forum posts.
 *
 * @package NamelessMC\Misc
 * @author Aberdeener
 * @version 2.2.0
 * @license MIT
 * @see ReactionContext, ReactionContextsManager
 */
class ForumPostReactionContext extends ReactionContext {

    private Language $_forum_language;

    public function __construct(Language $forum_language) {
        $this->_forum_language = $forum_language;
    }

    public function name(): string {
        return 'forum_post';
    }

    public function friendlyName(Language $language): string {
        return $this->_forum_language->get('forum', 'forum_score');
    }

    public function isEnabled(): bool {
        return Settings::get('forum_reactions') === '1';
    }

    public function getUserReceived(User $user): array {
        return DB::getInstance()->get('forums_reactions', ['user_received', $user->data()->id])->results();
    }

    public function getUserGiven(User $user): array {
        return DB::getInstance()->get('forums_reactions', ['user_given', $user->data()->id])->results();
    }

    public function validateReactable(int $reactable_id, User $user) {
        $result = DB::getInstance()->get('posts', $reactable_id);

        if (!$result->exists()) {
            return false;
        }

        $post = $result->first();

        if (!(new Forum())->forumExist($post->forum_id, $user->getAllGroupIds())) {
            return false;
        }

        return $post;
    }

    public function hasReacted(User $user, Reaction $reaction, int $reactable_id) {
        $result = DB::getInstance()->get('forums_reactions', [
            ['post_id', $reactable_id], ['user_given', $user->data()->id], ['reaction_id', $reaction->id],
        ]);

        if ($result->exists()) {
            return $result->first()->id;
        }

        return false;
    }

    public function giveReaction(User $user, User $receiver, Reaction $reaction, int $reactable_id): void {
        DB::getInstance()->insert('forums_reactions', [
            'post_id' => $reactable_id,
            'user_received' => $receiver->data()->id,
            'user_given' => $user->data()->id,
            'reaction_id' => $reaction->id,
            'time' => date('U'),
        ]);

        Log::getInstance()->log(Log::Action('forums/react'), $reaction->id);
    }

    public function deleteReaction(int $reactable_reaction_id): void {
        DB::getInstance()->delete('forums_reactions', $reactable_reaction_id);
    }

    public function getAllReactions(int $reactionable_id): array {
        return DB::getInstance()->get('forums_reactions', ['post_id', $reactionable_id])->results();
    }

    public function reactionUserIdColumn(): string {
        return 'user_given';
    }

    public function determineReceiver(object $reactable): User {
        return new User($reactable->post_creator);
    }
}
