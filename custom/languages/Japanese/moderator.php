<?php
/*
 *  Made by Samerton
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr11
 *
 *  Translator
 *  - SimplyRin( @SimplyRin_, https://www.simplyrin.net )
 *  - Mari0914( @Mari0914_Main, https://mari0914.japanminigame.net )
 *  - snake( @ViaSnake, https://github.com/ViaSnake )
 *
 *  License: MIT
 *
 *  Japanese Language - Moderator terms
 */

$language = array(
    'mod_cp' => 'ModCP',
    'staff_cp' => 'StaffCP',
    'overview' => '概要',

    // Spam
    'spam' => 'スパム',
    'mark_as_spam' => 'スパムとして処罰',
    'confirm_spam' => '<p>指定ユーザーをスパムとして処罰してもよろしいですか？</p><p>指定ユーザーはIP Banされ、すべてのコンテンツが削除されます。</p>',
    'user_marked_as_spam' => 'スパムとして指定されたユーザーを正常に処罰しました。',
    'cant_ban_admin' => '管理者を処罰することはできません。',

    // IP Lookup
    'ip_lookup' => 'IP検索',
    'search_for_ip' => 'ユーザー名またはIPアドレスを検索する:',
    'no_ips_with_username' => '指定したユーザーのIPアドレスは見つかりませんでした。',
    'no_accounts_with_that_ip' => '指定したIPアドレスのアカウントは見つかりませんでした。',
    '1_account_with_ip' => '{y}のアカウントが1件見つかりました。', // Don't replace "{y}"
    'count_accounts_with_ip' => '{y}のアカウントが{x}件見つかりました。', // Don't replace "{x}" or "{y}"
    '1_ip_with_name' => '{y}のIPアドレスが1件見つかりました。', // Don't replace "{y}"
    'count_ips_with_name' => '{y}のIPアドレスが{x}件見つかりました。', // Don't replace "{x}" or "{y}"
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
    'viewing_report' => 'レポートの表示',
    'view_content' => '報告されたコンテンツを表示する',
    'no_comments' => 'コメントはありません',
    'new_comment' => '新しいコメント',
    'report_comment_invalid' => 'コメント内容が無効です。1~10000文字のコメントを入力してください。',
    'close_report' => 'レポートを既読',
    'reopen_report' => 'レポートを未読',
    '1_open_report' => '現在<strong>1</strong>件の公開レポートがあります。',
    'open_reports' => '現在<strong>{x}</strong>件の公開レポートがあります。', // Don't replace {x}
    'no_open_reports' => '未読のレポートはありません。',
    'no_closed_reports' => '既読のレポートはありません。',
    'recent_reports' => '最新のレポート',
    'reported_by' => '報告者：',
    'website' => 'ウェブサイト',
    'ingame' => 'ゲーム内',
    'x_closed_report' => '{x}がレポートを閉鎖しました。',// {x}（username）
    'x_reopened_report' => '{x}がレポートを再開しました。',// {x}（ユーザー名）
    'report_reopened' => 'レポートが正常に再開されました。',
    'report_closed' => 'レポートが正常に閉鎖されました。',
    'comment_created' => 'コメントが正常に作成されました。',

    // Punishments
    'punishments' => '処罰',
    'view_punishments' => '処罰確認',
    'banned' => 'Ban済み',
    'groups' => 'グループ',
    'punish' => '処罰執行',
    'ban' => 'Ban',
    'warn' => '警告',
    'ban_ip' => 'Ban IP',
    'viewing_user_x' => 'ユーザー{x}を表示しています。', // Don't replace {x}
    'previous_punishments' => '処罰履歴',
    'no_previous_punishments' => '処罰履歴はありません。',
    'warning' => '警告',
    'ip_ban' => 'IP Ban',
    'reason' => '理由',
    'warn_user' => 'ユーザーに警告する',
    'ban_user' => 'Ban ユーザー',
    'enter_valid_punishment_reason' => '処罰の正当な理由を、5~5000文字の間で入力してください。',
    'user_punished' => 'ユーザーが処罰されました。',
    'user_punished_alert' => '{x}がユーザー{y}を処罰しました。', // Don't replace {x} (staff member) or {y} (user punished)
    'revoke' => '取り消し',
    'revoked' => '取り消されました',
    'acknowledged' => '承認されました',
    'confirm_revoke_warning' => 'この警告を取り消してもよろしいですか？',
    'confirm_revoke_ban' => 'この処罰を取り消してもよろしいですか？指定処罰より最近の処罰がある場合は、指定ユーザーの禁止は続行されます。',
    'punishment_revoked' => '処罰は取り消されました。',
    'punishment_revoked_alert' => '{x}がユーザー{y}の処罰を取り消しました。', // Don't replace {x} (staff member) or {y} (user with revoked punishment)
    'cant_punish_admin' => '管理者を処罰することはできません。',
    'viewing_all_punishments' => '処罰履歴を確認',
    'no_punishments_found' => '処罰は見つかりませんでした。',
    'view_user' => 'ユーザーを表示',
    'when' => 'いつ',
    'staff' => 'スタッフ',
    'type' => 'タイプ',
    'recent_punishments' => '最近の処罰',
    'created' => '作成者：',
    'staff：' => 'スタッフ：',
    'reason：' => '理由：',

    //ユーザー
    'recent_registrations' => '最近の登録',
    'reset_profile_banner' => 'プロフィールバナーをリセット'

);
