<?php
function fidelio_remove_cpt_slug($post_link, $post) {
    if ('proyectos' === $post->post_type && 'publish' === $post->post_status) {
        $post_link = str_replace('/proyectos/', '/', $post_link);
    }
    return $post_link;
}
add_filter('post_type_link', 'fidelio_remove_cpt_slug', 10, 2);
function fidelio_add_rewrite_rules() {
    add_rewrite_rule('^([^/]+)?$', 'index.php?proyectos=$matches[1]', 'top');
}

add_action('init', 'fidelio_add_rewrite_rules');


// functions.php

function mi_tema_scripts() {
    // Registrar y encolar CSS
    wp_enqueue_style('mi-tema-estilos', get_template_directory_uri() . 'assets/css/animation.css', array(), '1.0', 'all');
    
    // Registrar y encolar JavaScript
   // wp_enqueue_script('mi-tema-scripts', get_template_directory_uri() . '/js/archivo.js', array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'mi_tema_scripts');

// Personaliza el campo de adjuntos de DCO Comment Attachment sin modificar el plugin.
function fidelio_dco_attachment_label($markup, $required) {
    $required_markup = $required ? ' <span class="required">*</span>' : '';

    return '<label class="comment-form-attachment__label" for="attachment">Subí tu foto' . $required_markup . '</label>';
}
add_filter('dco_ca_form_element_label', 'fidelio_dco_attachment_label', 10, 2);

function fidelio_dco_attachment_upload_size($markup, $max_upload_size) {
    return '<span class="comment-form-attachment__file-size-notice">Tamaño máximo por archivo: ' . esc_html($max_upload_size) . '.</span>';
}
add_filter('dco_ca_form_element_upload_size', 'fidelio_dco_attachment_upload_size', 10, 2);

function fidelio_dco_attachment_file_types($markup, $types) {
    return '<span class="comment-form-attachment__file-types-notice">Podés subir imágenes.</span>';
}
add_filter('dco_ca_form_element_file_types', 'fidelio_dco_attachment_file_types', 10, 2);

function fidelio_dco_attachment_autoembed_links($markup, $autoembed_links) {
    if (!$autoembed_links) {
        return '';
    }

    return '<span class="comment-form-attachment__autoembed-links-notice">Los enlaces de YouTube, Facebook, Twitter y otros servicios se mostrarán automáticamente.</span>';
}
add_filter('dco_ca_form_element_autoembed_links', 'fidelio_dco_attachment_autoembed_links', 10, 2);

function fidelio_dco_attachment_drop_area($markup) {
    return '<span class="comment-form-attachment__drop-area"><span class="comment-form-attachment__drop-area-inner">También podés soltar tus fotos acá</span></span>';
}
add_filter('dco_ca_form_element_drop_area', 'fidelio_dco_attachment_drop_area');
