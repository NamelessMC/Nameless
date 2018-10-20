<<<<<<< HEAD
<?php 
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-dev
 *
 *  Translation by SimplyRin(@SimplyRin_, https://www.simplyrin.net)
=======
<?php
/*
 *	Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr5
 *
 *  Translation by SimplyRin( @SimplyRin_, https://www.simplyrin.net )
 *  Additional translation by Mari0914( @Mari0914_Main, https://mari0914.japanminigame.net )
>>>>>>> upstream/v2
 *
 *  License: MIT
 *
 *  Japanese Language - Moderator terms
 */

$language = array(
	'mod_cp' => 'ModCP',
<<<<<<< HEAD
	'overview' => '概要',
	
	// Spam
	'spam' => 'スパム',
	'mark_as_spam' => 'スパムとしてマーク',
	'confirm_spam' => '<p>このユーザーをスパムとしてマークしてもよろしいですか？</p><p>ユーザーはIP Banされ、すべてのコンテンツは削除されます。</p>',
	'user_marked_as_spam' => 'スパムとして正常にマークされたユーザー。',
	'cant_ban_admin' => '管理者を禁止することはできません！',
	
	// IP Lookup
	'ip_lookup' => 'IP 検索',
	'search_for_ip' => 'ユーザー名またはIPアドレスを検索する:',
	'no_ips_with_username' => 'そのユーザーのIPアドレスは見つかりませんでした。',
	'no_accounts_with_that_ip' => 'そのIPアドレスのアカウントは見つかりませんでした。',
	'1_account_with_ip' => 'Found 1 account with the IP {y}', // Don't replace "{y}"
	'count_accounts_with_ip' => '{x} 個 のアカウントがIP {y}', // Don't replace "{x}" or "{y}"
	'1_ip_with_name' => 'Found 1 IP address for user {y}', // Don't replace "{y}"
	'count_ips_with_name' => 'ユーザー {y} の {x} IPアドレスが見つかりました', // Don't replace "{x}" or "{y}"
	'no_users_or_ips_found' => 'ユーザーまたはIPアドレスが見つかりません。',
	
	// Reports
	'reports' => 'レポート',
	'report_alert' => '新しいレポートが送信されました',
	'user_reported' => 'ユーザー報告',
	'comments' => 'コメント',
	'updated_by' => '更新者',
	'actions' => 'アクション',
	'view_closed' => 'クローズドビュー',
	'view_open' => 'ビューを開く',
=======
	'staff_cp' => 'StaffCP',
	'overview' => '概要',

	// Spam
	'spam' => 'スパム',
	'mark_as_spam' => 'スパムとして処罰',
	'confirm_spam' => '<p>指定ユーザーをスパムとして処罰してもよろしいですか？</p><p>指定ユーザーはIP Banされ、すべてのコンテンツが削除されます。</p>',
	'user_marked_as_spam' => 'スパムとして指定されたユーザーを正常に処罰しました。',
	'cant_ban_admin' => '管理者を処罰することはできません。',

	// IP Lookup
	'ip_lookup' => 'IP 検索',
	'search_for_ip' => 'ユーザー名またはIPアドレスを検索する:',
	'no_ips_with_username' => '指定したユーザーのIPアドレスは見つかりませんでした。',
	'no_accounts_with_that_ip' => '指定したIPアドレスのアカウントは見つかりませんでした。',
	'1_account_with_ip' => '{y} のアカウントが 1 件見つかりました。', // Don't replace "{y}"
	'count_accounts_with_ip' => '{y} のアカウントが {x} 件見つかりました。', // Don't replace "{x}" or "{y}"
	'1_ip_with_name' => '{y}　のIPアドレスが 1 件見つかりました。', // Don't replace "{y}"
	'count_ips_with_name' => '{y}　のIPアドレスが {x} 件見つかりました。', // Don't replace "{x}" or "{y}"
	'no_users_or_ips_found' => 'ユーザーまたはIPアドレスが見つかりません。',

	// Reports
	'reports' => 'レポート',
	'report_alert' => '新しいレポートが送信されました。',
	'user_reported' => 'ユーザーレポート',
	'comments' => 'コメント',
	'updated_by' => '報告者',
	'actions' => 'アクション',
	'view_closed' => 'レポートを既読',
	'view_open' => 'レポートを未読',
>>>>>>> upstream/v2
	'viewing_report' => 'レポートの表示',
	'view_content' => '報告されたコンテンツを表示する',
	'no_comments' => 'コメントはありません',
	'new_comment' => '新しいコメント',
<<<<<<< HEAD
	'report_comment_invalid' => 'コメントコンテンツが無効です。 1~10000 文字のコメントを入力してください。',
	'close_report' => 'レポートを閉じる',
	'reopen_report' => 'レポートを再度開く',
	'1_open_report' => 'There is currently <strong>1</strong> open report.',
	'open_reports' => '現在 <strong>{x}</strong> 公開レポートがあります。', // Don't replace {x}
	'no_open_reports' => 'There are currently no open reports.',
	'no_closed_reports' => 'There are currently no closed reports.',
	
	// Punishments
	'punishments' => '処罰',
	'view_punishments' => '罰を見る',
	'banned' => 'Banned',
	'groups' => 'グループ',
	'punish' => '罰する',
	'ban' => 'Ban',
	'warn' => '警告',
	'ban_ip' => 'Ban IP',
	'viewing_user_x' => 'ユーザー {x} を表示しています', // Don't replace {x}
	'previous_punishments' => '以前の刑罰',
	'no_previous_punishments' => '以前の罰はない',
=======
	'report_comment_invalid' => 'コメント内容が無効です。 1~10000 文字のコメントを入力してください。',
	'close_report' => 'レポートを既読',
	'reopen_report' => 'レポートを未読',
	'1_open_report' => '現在 <strong>1</strong> 件の公開レポートがあります。',
	'open_reports' => '現在 <strong>{x}</strong> 件の公開レポートがあります。', // Don't replace {x}
	'no_open_reports' => '未読のレポートはありません。',
	'no_closed_reports' => '既読のレポートはありません。',
	'recent_reports' => 'Recent Reports',
	'reported_by' => 'Reported by:',
	'website' => 'Website',
	'ingame' => 'Ingame',

	// Punishments
	'punishments' => '処罰',
	'view_punishments' => '処罰確認',
	'banned' => 'Ban済み',
	'groups' => 'グループ',
	'punish' => '処罰執行',
	'ban' => 'Ban',
	'warn' => '警告',
	'ban_ip' => 'Ban IP',
	'viewing_user_x' => 'ユーザー {x} を表示しています。', // Don't replace {x}
	'previous_punishments' => '処罰履歴',
	'no_previous_punishments' => '処罰履歴はありません。',
>>>>>>> upstream/v2
	'warning' => '警告',
	'ip_ban' => 'IP Ban',
	'reason' => '理由',
	'warn_user' => 'ユーザーに警告する',
	'ban_user' => 'Ban ユーザー',
<<<<<<< HEAD
	'enter_valid_punishment_reason' => '罰則の正当な理由を、 5~5000 文字の間で入力してください。',
	'user_punished' => 'ユーザーが罰せられました。',
	'user_punished_alert' => '{x} はユーザー {y} を処罰しました', // Don't replace {x} (staff member) or {y} (user punished)
	'revoke' => '取り消し',
	'revoked' => '取り消された',
	'acknowledged' => '承認された',
	'confirm_revoke_warning' => 'この警告を取り消してもよろしいですか？',
	'confirm_revoke_ban' => 'この禁止を取り消してもよろしいですか？ より最近の禁止がある場合でも、ユーザーは禁止されます！',
	'punishment_revoked' => '罰は取り消された。',
	'punishment_revoked_alert' => '{x} はユーザー {y} の罰を取り消しました', // Don't replace {x} (staff member) or {y} (user with revoked punishment)
	'cant_punish_admin' => '管理者を罰することはできません！',
    'viewing_all_punishments' => 'すべての刑罰を見る',
    'no_punishments_found' => '罰は見つかりませんでした。',
    'view_user' => 'ユーザーを表示',
    'when' => 'いつ',
    'staff' => 'スタッフ',
    'type' => 'タイプ'

);
=======
	'enter_valid_punishment_reason' => '処罰の正当な理由を、 5~5000 文字の間で入力してください。',
	'user_punished' => 'ユーザーが処罰されました。',
	'user_punished_alert' => '{x} がユーザー {y} を処罰しました。', // Don't replace {x} (staff member) or {y} (user punished)
	'revoke' => '取り消し',
	'revoked' => '取り消されました',
	'acknowledged' => '承認されました',
	'confirm_revoke_warning' => 'この警告を取り消してもよろしいですか？',
	'confirm_revoke_ban' => 'この処罰を取り消してもよろしいですか？ 指定処罰より最近の処罰がある場合は、指定ユーザーの禁止は続行されます。',
	'punishment_revoked' => '処罰は取り消されました。',
	'punishment_revoked_alert' => '{x} がユーザー {y} の処罰を取り消しました。', // Don't replace {x} (staff member) or {y} (user with revoked punishment)
	'cant_punish_admin' => '管理者を処罰することはできません。',
	'viewing_all_punishments' => '処罰履歴を確認',
	'no_punishments_found' => '処罰は見つかりませんでした。',
	'view_user' => 'ユーザーを表示',
	'when' => 'いつ',
	'staff' => 'スタッフ',
	'type' => 'タイプ',
	'recent_punishments' => 'Recent Punishments',
	'created' => 'Created:',
	'staff:' => 'Staff:',
	'reason:' => 'Reason:',
	
	// Users
	'recent_registrations' => 'Recent Registrations'

);
>>>>>>> upstream/v2
