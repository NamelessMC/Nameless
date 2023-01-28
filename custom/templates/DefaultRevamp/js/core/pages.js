// @license magnet:?xt=urn:btih:d3d9a9a6595521f9666a5e94cc830dab83b65699&dn=expat.txt Expat/MIT
if (page !== '') {

    if (page === 'status') {
        $(".server").each(function () {
            let serverID = $(this).data("id");
            let serverBungee = $(this).data("bungee");
            let serverBedrock = $(this).data("bedrock");
            let serverPlayerList = $(this).data("players");
            let serverElem = '#server' + serverID + '[data-id=' + serverID + ']';

            const paramChar = URLBuild('').includes('?') ? '&' : '?';

            setInterval(function () {
                $.getJSON(URLBuild('queries/server/' + paramChar + 'id=' + serverID), function (data) {
                    let content;
                    let players = '';
                    if (data.status_value === 1) {
                        $(serverElem).removeClass("red");
                        $(serverElem).addClass("green");
                        content = data.player_count + "/" + data.player_count_max;
                        if (serverBungee === 1) {
                            players = bungeeInstance;
                        } else if (serverBedrock === 1) {
                            players = '';
                        } else {
                            if (serverPlayerList === 1) {
                                if (data.player_count > 0 && data.player_list.length <= 0) {
                                    // Weird edge case where player list is empty but the player count is > 0
                                    if (data.player_count > 1) {
                                        players += xPlayersOnline.replace('{{count}}', data.player_count);
                                    } else {
                                        players += onePlayerOnline;
                                    }
                                } else if (data.player_list.length > 0) {
                                    for (const player of data.player_list) {
                                        players += '' +
                                            '<a href="' + URLBuild('profile/' + player.name) + '" data-tooltip="' + player.name + '" data-variation="mini" data-inverted="" data-position="bottom center">' +
                                            '<img class="ui mini circular image" src="' + avatarSource.replace('{identifier}', player.id).replace('{size}', 64) + '" alt="' + player.name + '">' +
                                            '</a>';
                                    }

                                    if (data.player_list.length < data.player_count) {
                                        players += '<span class="ui blue circular label">+' + (data.player_count - data.player_list.length) + '</span>';
                                    }
                                } else {
                                    players += noPlayersOnline;
                                }
                            }
                        }
                    } else {
                        $(serverElem).removeClass("green");
                        $(serverElem).addClass("red");
                        content = offline;
                        players = noPlayersOnline;
                    }

                    $(serverElem).find('#server-status').html(content);
                    $(serverElem).find('#server-players').html(players);
                });
            }, 5000);
        });
    } else if (page === 'profile') {
        function showBannerSelect() {
            $('#imageModal').modal({
                onVisible: function () {
                    $("select").imagepicker();
                }
            }).modal('show');
        }
        $(function () {
            let postElem = window.location.hash;
            if (postElem) {
                postElem = $(postElem.slice(0, -1));
                setTimeout(function () {
                    $('html, body').animate({ scrollTop: postElem.offset().top - 15 }, 800);
                }, 100);
                postElem.delay(600).effect('highlight', {}, 800);
            }
        });
    } else if (page === 'cc_messaging') {
        $('.ui.search').dropdown({
            minCharacters: 3
        });
    } else if (route.indexOf("/forum/topic/") != -1) {
        $(function () {
            const postId = window.location.hash.replace('#post-', '');
            const postElem = '#topic-post[post-id=\'' + postId + '\']';

            if (postId) {
                setTimeout(function () {
                    $('html, body').animate({ scrollTop: $(postElem).offset().top - 15 }, 800);
                    $('> .ui.segment', postElem).effect("highlight", {}, 800);
                }, 100);
            }
        });
    }
}
// @license-end
