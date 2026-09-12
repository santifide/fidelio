<?php
// Si el post está protegido por contraseña y el visitante no ha ingresado la contraseña
if (post_password_required()) {
    return;
}
?>

<section class="section-comments" data-duration="30">


    <?php if (have_comments()): ?>

        <div class="wrapper-comments">
        <button id="prev" class="arrow"><</button>
        <button id="next" class="arrow">></button>
            <?php
            wp_list_comments(array(
                'style' => 'div',
                'short_ping' => true,
            ));
            ?>

        </div>
        <?php if (get_comment_pages_count() > 1 && get_option('page_comments')): ?>
            <nav class="comment-navigation">
                <?php
                paginate_comments_links(array(
                    'prev_text' => '&larr; Anterior',
                    'next_text' => 'Siguiente &rarr;',
                ));
                ?>
            </nav>
        <?php endif; ?>

    <?php endif; ?>
    <div class="wrapper-form" id="wrapper-form">
    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/boton-cerrar.png'); ?>" id="btnClose" alt="Cerrar">
        <?php
        // Si los comentarios están cerrados y hay comentarios
        if (!comments_open() && get_comments_number()):
            ?>
            <p class="no-comments">Los comentarios están cerrados.</p>
        <?php endif; ?>

        <?php
        // Personalizar el formulario de comentarios
        $comentarios_args = array(
            'fields' => array(
                'author' => '<p class="comment-form-author"><input id="author" name="author" type="text" value="" size="30" maxlength="245" required="required" placeholder="Nombre y Apellido" /></p>',
                // Eliminamos el resto de los campos predeterminados (email, URL)
            ),
            'comment_field' => '<textarea id="comment" name="comment" rows="8" maxlength="65525" required="required" placeholder="Escribí un saludo"></textarea></p>',
            'submit_field' => '<p class="form-submit">%1$s %2$s</p>', // Formato del botón de enviar
            'label_submit' => 'Enviar', // Texto del botón de enviar
            'title_reply' => 'Deja un saludo!', // Título del formulario
        );

        // Llamar al formulario con los parámetros personalizados
        comment_form($comentarios_args);
        ?>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const commentForm = document.getElementById('commentform');
            const attachmentInput = document.getElementById('attachment');
            const attachmentLabel = document.querySelector('.comment-form-attachment__label');

            if (commentForm) {
                commentForm.enctype = 'multipart/form-data';
            }

            if (!attachmentInput || !attachmentLabel) {
                return;
            }

            let fileNotice = document.querySelector('.fidelio-attachment-selection');
            if (!fileNotice) {
                fileNotice = document.createElement('span');
                fileNotice.className = 'fidelio-attachment-selection';
                attachmentLabel.insertAdjacentElement('afterend', fileNotice);
            }

            attachmentInput.addEventListener('change', function () {
                const count = attachmentInput.files.length;
                fileNotice.textContent = count
                    ? (count === 1 ? '1 foto seleccionada' : count + ' fotos seleccionadas')
                    : 'Sin archivos seleccionados';
            });
        });
    </script>

    <script>
        // Función para aplicar el fade-out
        function fadeOutEffect(el) {
            let opacity = 1;
            const fadeOutInterval = setInterval(function () {
                if (opacity <= 0) {
                    clearInterval(fadeOutInterval);
                    el.style.display = "none";
                } else {
                    opacity -= 0.05;
                    el.style.opacity = opacity;
                }
            }, 20); // Ajusta la velocidad aquí (20ms para suavidad)
        }

        // Función para aplicar el fade-in
        function fadeInEffect(el) {
            let opacity = 0;
            el.style.display = "flex"; // Asegura que el elemento esté visible
            const fadeInInterval = setInterval(function () {
                if (opacity >= 1) {
                    clearInterval(fadeInInterval);
                } else {
                    opacity += 0.05;
                    el.style.opacity = opacity;
                }
            }, 20); // Ajusta la velocidad aquí (20ms para suavidad)
        }

        // Al cargar la página, agrega los listeners de eventos
        document.addEventListener("DOMContentLoaded", function () {
            const respondDiv = document.getElementById('btnClose');
            const wrapperForm = document.getElementById('wrapper-form');
            const showCommentsButton = document.getElementById('show-comments');

            // Al hacer click en el div #respond
            respondDiv.addEventListener('click', function () {
                fadeOutEffect(wrapperForm); // Aplica el fade-out
            });

            // Al hacer click en el botón "Dejar comentarios"
            showCommentsButton.addEventListener('click', function () {
                fadeInEffect(wrapperForm); // Aplica el fade-in
            });
        });
    </script>
    <div class="wrapper-call-to-action-comments">
        <!--<img src="<?php the_cfc_field('imagqr', 'imageqr', $post_id = false, $key = 0, $do_echo = true); ?>" id="qr">-->
        <button id="show-comments">Dejá tu saludo</button>

    </div>

</section>

<script>
    let currentIndexComments = 0; // Índice del comentario actual
const comments = document.querySelectorAll('.comment'); // Obtener todos los comentarios
const totalComments = comments.length; // Cantidad total de comentarios
const intervalTime = 5000; // 5 segundos entre cada transición

// Función para cambiar el comentario activo
function showComment(index) {
    comments.forEach((comment, i) => {
        if (i === index) {
            comment.classList.remove('d-none'); // Quitar la clase d-none
            comment.classList.add('show');      // Aplicar el efecto de fade-in
        } else {
            comment.classList.remove('show');   // Remover el fade-in
            comment.classList.add('d-none');    // Aplicar fade-out
        }
    });
}

// Función para ir al siguiente comentario
function nextComment() {
    currentIndexComments = (currentIndexComments + 1) % totalComments; // Ir al siguiente (vuelve al primero si es el último)
    showComment(currentIndexComments);
}

// Función para ir al comentario anterior
function prevComment() {
    currentIndexComments = (currentIndexComments - 1 + totalComments) % totalComments; // Ir al anterior (vuelve al último si está en el primero)
    showComment(currentIndexComments);
}

// Iniciar el ciclo automático cada 5 segundos
let interval = setInterval(nextComment, intervalTime);

// Evento para el botón "Siguiente"
document.getElementById('next').addEventListener('click', () => {
    clearInterval(interval); // Detener el ciclo automático al hacer clic
    nextComment();           // Ir al siguiente comentario
    interval = setInterval(nextComment, intervalTime); // Reiniciar el ciclo automático
});

// Evento para el botón "Anterior"
document.getElementById('prev').addEventListener('click', () => {
    clearInterval(interval); // Detener el ciclo automático al hacer clic
    prevComment();           // Ir al comentario anterior
    interval = setInterval(nextComment, intervalTime); // Reiniciar el ciclo automático
});

// Mostrar el primer comentario al cargar la página
showComment(currentIndexComments);

</script>