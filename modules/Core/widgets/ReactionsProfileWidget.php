<?php

class ReactionsProfileWidget extends ProfileWidgetBase {

    private static array $_collectors = [
        'recieved' => [],
        'given' => [],
    ];

    public function __construct(Smarty $smarty) {
        $this->_name = 'Reactions';
        $this->_description = 'Displays a users recieved and given reactions on their profile.';
        $this->_module = 'Core';
        $this->_smarty = $smarty;
    }

    public function initialise(User $user): void {
        $reactions = [];
        foreach (Reaction::find(true, 'enabled') as $reaction) {
            $reactions[$reaction->id] = [
                'name' => $reaction->name,
                'html' => $reaction->html,
                'recieved' => 0,
                'given' => 0,
                'type' => $reaction->type,
            ];
        }
        foreach (self::$_collectors['recieved'] as $collector) {
            $received = $collector($user);
            foreach ($received as $reaction) {
                $reactions[$reaction->reaction_id]['recieved']++;
            }
        }
        foreach (self::$_collectors['given'] as $collector) {
            $given = $collector($user);
            foreach ($given as $reaction) {
                $reactions[$reaction->reaction_id]['given']++;
            }
        }
        // Sort by most recieved
        usort($reactions, static function ($a, $b) {
            return $b['recieved'] <=> $a['recieved'];
        });

        $reaction_score = 0;
        foreach ($reactions as $reaction) {
            if ($reaction['type'] === Reaction::TYPE_POSITIVE) {
                $reaction_score += $reaction['recieved'];
            } else if ($reaction['type'] === Reaction::TYPE_NEGATIVE) {
                $reaction_score -= $reaction['recieved'];
            }
        }

        if ($reaction_score > 0) {
            $reaction_score = '+' . $reaction_score;
        }

        $this->_smarty->assign([
            'ALL_REACTIONS' => $reactions,
            'REACTION_SCORE' => $reaction_score,
        ]);
        $this->_content = $this->_smarty->fetch('widgets/reactions.tpl');
    }

    public static function addRecievedCollector(Closure $collector): void {
        self::$_collectors['recieved'][] = $collector;
    }

    public static function addGivenCollector(Closure $collector): void {
        self::$_collectors['given'][] = $collector;
    }
}
