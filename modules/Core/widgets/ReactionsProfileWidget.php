<?php

class ReactionsProfileWidget extends ProfileWidgetBase {

    private static array $_collectors = [
        'recieved' => [],
        'given' => [],
    ];

    private Language $_language;

    public function __construct(Smarty $smarty, Language $language) {
        $this->_name = 'Reactions';
        $this->_description = 'Displays a users recieved and given reactions on their profile.';
        $this->_module = 'Core';

        $this->_smarty = $smarty;
        $this->_language = $language;
    }

    public function initialise(User $user): void {
        $reactions = [];
        foreach (Reaction::find(true, 'enabled') as $reaction) {
            $reactions[$reaction->id] = [
                'name' => $reaction->name,
                'html' => $reaction->html,
                'type' => $reaction->type,
                'recieved' => 0,
                'given' => 0,
                'custom_score' => $reaction->custom_score ?? 1,
                'contexts' => [],
            ];
        }

        $this->calculateCounts('recieved', $user, $reactions);
        $this->calculateCounts('given', $user, $reactions);

        // Sort by most recieved
        usort($reactions, static function ($a, $b) {
            return $b['recieved'] > $a['recieved'];
        });

        $reaction_score_aggregate = 0;
        $context_reaction_scores = [];
        foreach ($reactions as $reaction) {
            foreach ($reaction['contexts'] as $context => $context_reactions) {
                if (!isset($context_reaction_scores[$context])) {
                    $context_reaction_scores[$context] = 0;
                }
                if ($reaction['type'] === Reaction::TYPE_POSITIVE) {
                    $context_reaction_scores[$context] += $context_reactions['recieved'];
                    $reaction_score_aggregate += $context_reactions['recieved'];
                } else if ($reaction['type'] === Reaction::TYPE_NEGATIVE) {
                    $context_reaction_scores[$context] -= $context_reactions['recieved'];
                    $reaction_score_aggregate -= $context_reactions['recieved'];
                } else if ($reaction['type'] === Reaction::TYPE_CUSTOM) {
                    $context_reaction_scores[$context] += $context_reactions['recieved'] * $reaction['custom_score'];
                    $reaction_score_aggregate += $context_reactions['recieved'] * $reaction['custom_score'];
                }
            }
        }

        // Format reaction scores
        if ($reaction_score_aggregate > 0) {
            $reaction_score_aggregate = '+' . $reaction_score_aggregate;
        }

        foreach ($this->allContexts() as $context) {
            if (!isset($context_reaction_scores[$context])) {
                $context_reaction_scores[$context] = 0;
            }
            $score = $context_reaction_scores[$context];
            if ($score > 0) {
                $context_reaction_scores[$context] = '+' . $score;
            }
        }

        $this->_smarty->assign([
            'REACTIONS_TEXT' => $this->_language->get('user', 'reactions'),
            'GIVEN' => $this->_language->get('user', 'given'),
            'RECEIVED' => $this->_language->get('user', 'received'),
            'REACTION_SCORE' => $this->_language->get('user', 'reaction_score'),
            'ALL_REACTIONS' => $reactions,
            'REACTION_SCORE_AGGREGATE' => $reaction_score_aggregate,
            'CONTEXT_REACTION_SCORES' => $context_reaction_scores,
        ]);
        $this->_content = $this->_smarty->fetch('widgets/reactions.tpl');
    }

    private function calculateCounts(string $type, User $user, array &$reactions): void {
        foreach (self::$_collectors[$type] as $collector) {
            $context = $collector['context'];
            $collector = $collector['collector'];
            $received = $collector($user);
            foreach ($received as $reaction) {
                $this->incrementReactionCount($context, $reactions, $reaction->reaction_id, $type);
            }
        }
    }

    private function incrementReactionCount(string $context, array &$reactions, int $reaction_id, string $type): void {
        if (!isset($reactions[$reaction_id]['contexts'][$context])) {
            $reactions[$reaction_id]['contexts'][$context] = [
                'recieved' => 0,
                'given' => 0,
            ];
        }

        $reactions[$reaction_id][$type]++;
        $reactions[$reaction_id]['contexts'][$context][$type]++;
    }

    private function allContexts(): array {
        $contexts = [];
        foreach (self::$_collectors as $collectors) {
            foreach ($collectors as $collector) {
                $contexts[] = $collector['context'];
            }
        }
        return array_unique($contexts);
    }

    public static function addRecievedCollector(string $context, Closure $collector): void {
        self::$_collectors['recieved'][] = [
            'context' => $context,
            'collector' => $collector,
        ];
    }

    public static function addGivenCollector(string $context, Closure $collector): void {
        self::$_collectors['given'][] = [
            'context' => $context,
            'collector' => $collector,
        ];
    }
}
