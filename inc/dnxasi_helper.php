<?php
/**
 * Helper functions for danixland-author-signature
 */


/**
 * Returns true if current post is paginated aka is split into pages using the  <!--nextpage--> tag
 */
function dnxasi_is_post_paginated() {
    global $multipage;

    if ( 0 !== $multipage)
        return true;
}

/**
 * Returns the number of pages in a paginated post
 */
function dnxasi_post_last_page() {
    global $pages;
    $countpages = count($pages);

    return $countpages;
}