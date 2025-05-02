<?php

class ReactionsProfileWidget extends ProfileWidgetBase {

    private Language $_language;

    public function __construct(TemplateEngine $engine, Language $language) {
        $this->_name = 'Reactions';
        $this->_description = 'Displays a users received and given reactions on their profile.';
        $this->_module = 'Core';

        $this->_engine = $engine;
        $this->_language = $language;
    }

    public function initialise(User $user): void {
        $reactions = [];
        foreach (Reaction::find(true, 'enabled') as $reaction) {
            $reactions[$reaction->id] = [
                'name' => $reaction->name,
                'html' => $reaction->html,
                'type' => $reaction->type,
                'received' => 0,
                'given' => 0,
                'custom_score' => $reaction->custom_score ?? 1,
                'contexts' => [],
            ];
        }

        $this->calculateCounts('received', $user, $reactions);
        $this->calculateCounts('given', $user, $reactions);

        // Sort by most received
        usort($reactions, static function ($a, $b) {
            return $b['received'] > $a['received'];
        });

        $reaction_score_aggregate = 0;
        $context_reaction_scores = [];
        foreach ($reactions as $reaction) {
            foreach ($reaction['contexts'] as $context => $context_reactions) {
                if (!isset($context_reaction_scores[$context])) {
                    $context_reaction_scores[$context] = 0;
                }
                if ($reaction['type'] === Reaction::TYPE_POSITIVE) {
                    $context_reaction_scores[$context] += $context_reactions['received'];
                    $reaction_score_aggregate += $context_reactions['received'];
                } else if ($reaction['type'] === Reaction::TYPE_NEGATIVE) {
                    $context_reaction_scores[$context] -= $context_reactions['received'];
                    $reaction_score_aggregate -= $context_reactions['received'];
                } else if ($reaction['type'] === Reaction::TYPE_CUSTOM) {
                    $context_reaction_scores[$context] += $context_reactions['received'] * $reaction['custom_score'];
                    $reaction_score_aggregate += $context_reactions['received'] * $reaction['custom_score'];
                }
            }
        }

        // Format reaction scores
        if ($reaction_score_aggregate > 0) {
            $reaction_score_aggregate = '+' . $reaction_score_aggregate;
        }

        foreach (ReactionContextsManager::getInstance()->validContextFriendlyNames($this->_language) as $context) {
            if (!isset($context_reaction_scores[$context])) {
                $context_reaction_scores[$context] = 0;
            }
            $score = $context_reaction_scores[$context];
            if ($score > 0) {
                $context_reaction_scores[$context] = '+' . $score;
            }
        }

        $this->_engine->addVariables([
            'REACTIONS_TEXT' => $this->_language->get('user', 'reactions'),
            'GIVEN' => $this->_language->get('user', 'given'),
            'RECEIVED' => $this->_language->get('user', 'received'),
            'REACTION_SCORE' => $this->_language->get('user', 'reaction_score'),
            'ALL_REACTIONS' => $reactions,
            'REACTION_SCORE_AGGREGATE' => $reaction_score_aggregate,
            'CONTEXT_REACTION_SCORES' => $context_reaction_scores,
        ]);
        $this->_content = $this->_engine->fetch('widgets/reactions');
    }

    private function calculateCounts(string $type, User $user, array &$reactions): void {
        $method = $type === 'received' ? 'getUserReceived' : 'getUserGiven';
        foreach (ReactionContextsManager::getInstance()->getContexts() as $reactionContext) {
            $received = $reactionContext->{$method}($user);
            foreach ($received as $reaction) {
                $this->incrementReactionCount($reactionContext->friendlyName($this->_language), $reactions, $reaction->reaction_id, $type);
            }
        }
    }

    private function incrementReactionCount(string $context, array &$reactions, int $reaction_id, string $type): void {
        if (!isset($reactions[$reaction_id]['contexts'][$context])) {
            $reactions[$reaction_id]['contexts'][$context] = [
                'received' => 0,
                'given' => 0,
            ];
        }

        $reactions[$reaction_id][$type]++;
        $reactions[$reaction_id]['contexts'][$context][$type]++;
    }
}
