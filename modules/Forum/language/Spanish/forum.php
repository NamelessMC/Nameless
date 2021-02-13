<?php
/*
 *  Made by Samerton
 *  Translated by AllPlayed
 *
 *  https://github.com/NamelessMC/Nameless/
 *  NamelessMC version 2.0.0-pr8
 *
 *  License: MIT
 *
 *  Spanish Language for Forum module
 */

$language = array(
    /*
     *  Forums
     */
    'forum' => 'Foro',
    'forums' => 'Foros',
    'forum_index' => 'Inicio',
    'no_users_online' => 'No hay usuarios conectados.',
    'online_users' => 'Usuarios Conectados',
    'discussion' => 'Discusión',
    'topic' => 'Tema',
    'stats' => 'Estadísticas',
    'topics' => 'temas',
    'views' => 'vistas',
    'posts' => 'publicaciones',
    'x_posts' => '{x} publicaciones', // Don't replace {x}
    'x_topics' => '{x} temas', // Don't replace {x}
    'registered_x' => 'Registrado el: {x}', // Don't replace {x}
    'by' => 'por',
    'in' => 'en',
    'last_reply' => 'Última Respuesta',
    'latest_discussions' => 'Última Discusión',
    'users_registered' => '<strong>Usuarios Registrados:</strong> {x}', // Don't replace "{x}"
    'latest_member' => '<strong>Último Miembro:</strong> {x}', // Don't replace "{x}"
    'subforums' => 'Subforos',
    'subforum' => 'Subforo',
    'no_topics' => 'Aún no hay temas creados aquí.',
    'no_topics_short' => 'No hay Temas',
    'new_topic' => 'Nuevo Tema',
    'post_successful' => 'Publicación creada correctamente.',
    'post_edited_successfully' => 'Publicación editada correctamente.',
    'user_tag' => 'Has sido etiquetado en una publicación.',
    'user_tag_info' => 'Has sido etiquetado en una publicación por {x}.', // Don't replace "{x}"
    'creating_topic_in_x' => 'Creando un tema en {x}', // Don't replace "{x}"
    'topic_title' => 'Título del Tema',
    'new_reply' => 'Escribir una nueva Respuesta',
    're' => 'RE: ',
    'topic_locked' => 'Tema Bloqueado',
    'mod_actions' => 'Moderación',
    'lock_topic' => 'Bloquear Tema',
    'unlock_topic' => 'Desbloquear Tema',
    'merge_topic' => 'Juntar Temas',
    'merge_topics' => 'Juntar el Tema',
    'merge_instructions' => 'Para juntar los temas es <strong>necesario</strong> que estén en el mismo Foro/Subforo. Mover un Tema es innececesario.',
    'confirm_delete_short' => 'Confirmar borrado',
    'confirm_delete_topic' => '¿Estás seguro de que quieres borrar este Tema?',
    'confirm_delete_post' => '¿Estás seguro de que quieres borrar este Post?',
    'delete_topic' => 'Borrar Tema',
    'move_topic' => 'Mover Tema',
    'move_topic_to' => 'Mover tema a:',
    'stick_topic' => 'Colocar este tema primero:',
    'unstick_topic' => 'Quitar el primer tema (no es borrarlo)',
    'share' => 'Compartir',
    'share_twitter' => 'Compartir en Twitter',
    'share_facebook' => 'Compartir en Facebook',
    'edit' => 'Editar',
    'edit_post' => 'Editar Post',
    'last_edited' => 'Última edición: {x}', // Don't replace "{x}"
    'quote' => 'Citar',
    'topic_locked_notice' => 'El tema está bloqueado, pero tus permisos te permiten volver a abrirlo y escribir una nueva respuesta en el.',
    'title_required' => 'Porfavor, escribe el título del Tema',
    'content_required' => 'Porfavor, escribe o coloca algo de contenido en el Post/Tema',
    'title_min_2' => 'El título de tu tema debe tener <strong>mínimo</strong>  2 caracteres',
    'title_max_64' => 'El título de su tema no debe tener más de 64 caracteres',
    'content_min_2' => 'El contenido de tu publicación debe tener un mínimo de 2 caracteres',
    'content_max_50000' => 'El contenido de la publicación no debe superar los 50000 caracteres',
    'post_already_reported' => '¡Ya has reportado este post!',
    'quoted_post' => 'Publicación añadida a publicaciones citadas',
    'removed_quoted_post' => 'Publicación quitada junto a las citas.',
    'insert_quotes' => 'Insertar citas de texto',
    'quoting_posts' => 'Insertando citas...',
    'error_quoting_posts' => 'Lo sentimos, ha habido un error al citar esta publicación',
    'error_rating_post' => 'Lo sentimos, ha habido un problema al calificar esta publicación',
    'topic_stuck' => 'El tema se ha colocado en el primer puesto',
    'topic_unstuck' => 'El tema ha sido quitado del primer puesto.',
    'spam_wait' => 'Porfavor, espera {x} segundos antes de postear.',
    'overview' => 'Vista General',
    'no_label' => 'Sin Etiqueta',
    'forum_redirect_warning' => 'Alerta: Estás a punto de dejar este sitio. ¿Estás seguro de que deseas proceder ir a {x}?',
    'follow' => 'Seguir',
    'unfollow' => 'Dejar de Seguir',
    'now_following_topic' => 'Ahora está siguiendo este tema y se le notificará cualquier nueva respuesta.',
    'no_longer_following_topic' => 'Ya no está siguiendo este tema y no se le notificará ninguna nueva respuesta.',
    'new_reply_in_topic' => '{x} ha respondido al tema {y}', // Don't replace {x} (username) or {y} (topic title)
    'started_by_x' => 'Iniciado por {x}', // Don't replace {x}
    'sticky_topics' => 'Temas pegajosos',

    // Homepage
    'latest_announcements' => 'Últimas Noticias',
    'read_full_post' => 'Seguir leyendo...',

    // Admin tab
    'labels' => 'Etiquetas',
    'new_forum' => '<i class="fa fa-plus-circle"></i> Nuevo Foro',
    'new_label' => '<i class="fa fa-plus-circle"></i> Nueva Etiqueta',
    'new_label_type' => '<i class="fa fa-plus-circle"></i> Nuevo tipo de etiqueta',
    'label_types' => 'Tipos de Etiquetas',
    'creating_label' => 'Creando una nueva Etiqueta',
    'creating_label_type' => 'Creando un nuevo tipo de Etiqueta',
    'editing_label' => 'Editando la etiqueta',
    'editing_label_type' => 'Editando el tipo de etiqueta',
    'label_name' => 'Nombre de la Etiqueta',
    'label_type' => 'Tipo de Etiqueta',
    'label_type_name' => 'Nombre de el tipo de etiqueta',
    'label_type_html' => 'Tipo de etiqueta HTML',
    'label_type_html_help' => 'El HTML debe incluir {x} como marcador de posición para el nombre de la etiqueta',
    'label' => 'Etiqueta',
    'label_forums' => 'Foros de Etiquetas',
    'label_groups' => 'Grupos de Etiquetas',
    'no_forums' => 'No hay Foros',
    'no_labels_defined' => 'Aún no se han definido las etiquetas',
    'no_label_types_defined' => 'Aún no se han definido los tipos de etiquetas.',
    'label_edit_success' => 'Etiqueta modificada correctamente',
    'label_type_edit_success' => 'Tipo de etiqueta modificada correctamente.',
    'label_creation_success' => 'Etiqueta creada correctamente.',
    'label_type_creation_success' => 'Tipo de etiqueta creada correctamente.',
    'label_creation_error' => 'Error al crear una etiqueta. Asegúrese de que el nombre no tenga más de 32 caracteres y de que haya especificado un tipo.',
    'label_type_creation_error' => 'Error creando un tipo de etiqueta. Asegúrese de que el nombre no tenga más de 32 caracteres y que el HTML no tenga más de 1024 caracteres.',
    'creating_forum' => 'Creando nuevo Foro',
    'forum_name' => 'Nombre del Foro',
    'forum_description' => 'Descripción del Foro',
    'delete_forum' => 'Borrar Foro',
    'move_topics_and_posts_to' => 'Mover temas y publicaciones a',
    'delete_topics_and_posts' => 'Borrar temas y publicaciones',
    'forum_permissions' => 'Permisos del foro',
    'select_a_parent_forum' => 'Selecciona un foro principal',
    'parent_forum' => 'Foro Principal',
    'parent_forum_x' => 'Foro Principal: {x}', // Don't replace {x}
    'has_no_parent' => 'No tiene foro principal',
    'guests' => 'Invitados',
    'group' => 'Grupo',
    'can_view_forum' => '¿Puede ver el foro?',
    'can_view_other_topics' => '¿Puede mirar los temas de los otros usuarios?',
    'can_create_topic' => '¿Puede crear temas?',
    'can_edit_topic' => 'Can edit their topics?',
    'can_post_reply' => '¿Puede responder a los temas?',
    'can_moderate_forum' => '¿Puede moderar el foro?',
    'display_topics_as_news' => '¿Mostrar posts como Noticias en la página principal?',
    'forum_created_successfully' => 'Foro creado correctamente.',
    'forum_layout' => 'Layout del Foro',
    'table_view' => 'Vista de Tablas',
    'latest_discussions_view' => 'Ver las últimas discusiones',
    'input_forum_title' => 'Porfavor, escribe un nombre al Foro.',
    'input_forum_description' => 'Porfavor, escribe una descripción al Foro.',
    'forum_name_minimum' => 'El nombre del foro debe de tener un mínimo de 2 caracteres.',
    'forum_description_minimum' => 'La descripción del foro debe de tener un mínimo de 2 caracteres.',
    'forum_name_maximum' => 'El nombre del foro debe de tener un máximo de 150 caracteres.',
    'forum_description_maximum' => 'La descripción del foro debe de tener un máximo de 255 caracteres.',
    'forum_type' => 'Tipo de Foro',
    'forum_type_forum' => 'Foro',
    'forum_type_category' => 'Categoría',
    'invalid_action' => 'Acción inválida',
    'use_reactions' => 'Usar reacciones?',
    'redirect_forum' => 'Redirect forum?',
    'redirect_url' => 'Enlace de Redirección',
    'invalid_redirect_url' => 'Ha habilitado la redirección del foro, pero no ha ingresado una URL válida entre 1 y 512 caracteres.',
    'forum_icon' => 'Icono del Foro',
    'forum_icon_maximum' => 'El icono del foro debe tener un máximo de 256 caracteres.',
    'settings_updated_successfully' => 'Configuraciones actualizadas con éxito.',
    'forum_updated_successfully' => 'Foro actualizado con éxito.',
    'forum_deleted_successfully' => 'Foro eliminado con éxito.',
    'label_deleted_successfully' => 'Etiqueta eliminada con éxito.',
    'label_type_deleted_successfully' => 'Tipo de etiqueta eliminado correctamente.',
    'topic_placeholder' => 'Marcador de Tema',
    'default_labels' => 'Default Labels',
    'default_labels_info' => 'These will be assigned to a new topic in the forum unless overridden during topic creation. Ctrl+Click to select multiple',

    // Search
    'forum_search' => 'Busqueda en el Foro',
    'search_again_in_x_seconds' => 'Por favor, espera {x} segundos antes de volver a buscar.',
    'search_results' => 'Resúltados de tu busqueda',
    'new_search' => 'Hacer una nueva busqueda',
    'invalid_search_query' => 'Por favor, haz una busqueda entre 3 y 128 caracteres.',
    'no_results_found' => 'No se han encontrado resultados',

    // Profile tab
    'user_no_posts' => 'Este usuario, todavía no ha hecho ningun post/tema',
    'latest_posts' => 'Últimos Posts',

    // UserCP
    'last_7_days_posts' => 'Posts del Foro (últimos 7 días)',
    'your_posts' => 'Tus publicaciones',
    'average_posts' => 'Publicaciones Promedio',
    'total_posts' => 'Publicaciones Totales',
    'following_topics' => 'Followed Topics',
    'unfollow_all_topics' => 'Unfollow All Topics',
    'confirm_unfollow_all_topics' => 'Are you sure you want to unfollow all topics?',
    'all_topics_unfollowed' => 'All topics have been unfollowed.',
    'not_following_any_topics' => 'You are not following any topics.',

    // Hooks
    'new_topic_hook_info' => 'Nuevo Tema',
    'new_topic_text' => 'Tema creado en {x} por {y}', // Don't replace {x} (forum name), optional variable {y} (topic author)
    'include_in_hook' => 'Include new topics from this forum in webhooks?',
    'available_hooks' => 'Available Hooks',

    // Panel statistics
    'recent_topics' => 'Temas Recientes',
    'recent_topics_statistic_icon' => '<i class="fas fa-comment"></i>',
    'recent_posts' => 'Publicaciones Recientes',
    'recent_posts_statistic_icon' => '<i class="far fa-comments"></i>',
    'topics_title' => 'Temas',
    'posts_title' => 'Publicaciones',
);
