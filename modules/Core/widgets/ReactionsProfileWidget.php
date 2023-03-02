<?php

class ReactionsProfileWidget extends ProfileWidgetBase {

    private static array $_collectors = [
        'recieved' => [],
        'given' => []
    ];

    public function __construct(Smarty $smarty) {
        parent::__construct($smarty);

        $widget_query = self::getData('Reactions');

        $this->_name = 'Reactions';
        $this->_order = $widget_query->order;
        $this->_description = "Displays a user\'s recieved reactions on their profile.";
        $this->_module = 'Core';
        $this->_location = $widget_query->location;
    }

    public function initialise(User $user): void {
        $reactions = [];
        foreach (Reaction::find(true, 'enabled') as $reaction) {
            $reactions[$reaction->id] = [
                'name' => $reaction->name,
                'html' => Text::renderEmojis($reaction->html),
                'recieved' => 0,
                'given' => 0,
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
        $this->_smarty->assign([
            'ALL_REACTIONS' => $reactions,
        ]);
        $this->_content = $this->_smarty->fetch('widgets/forum/reactions.tpl');
    }

    public static function addRecievedCollector(Closure $collector): void {
        self::$_collectors['recieved'][] = $collector;
    }

    public static function addGivenCollector(Closure $collector): void {
        self::$_collectors['given'][] = $collector;
    }
}
