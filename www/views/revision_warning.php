<div class="warning">
    <?php
    if ($view['revision'] < $view['revision_count']) {
        echo "Atenţie! Aceasta este o versiune veche a paginii";
    } else {
        echo "Atenţie! Aceasta este ultima versiune a paginii";
    }
    echo ", scrisă la " . html_escape($textblock['timestamp']) . ".<br />";
    if ($view['revision'] > 1) {
        echo format_link(url_textblock_revision($view['page_name'], $view['revision'] - 1), "Revizia anterioară");
    }
    else {
        echo "Revizia anterioară";
    }
    ?>
    &nbsp;
    <?php
    if ($view['revision'] < $view['revision_count']) {
        echo format_link(url_textblock_revision($view['page_name'], $view['revision'] + 1), "Revizia următoare");
    }
    else {
        echo "Revizia următoare";
    }
    ?>
    &nbsp;
    <?php
    if (identity_can('textblock-delete-revision', $view['textblock'])) {
        echo format_post_link(url_textblock_delete_revision($view['page_name'], $view['revision']), "Şterge");
    }
    ?>
</div>
