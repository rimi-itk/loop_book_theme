<?php

include_once drupal_get_path('module', 'loop_book') . '/lib/src/Book.php';
use Drupal\loop_book\Book;

function loop_book_theme_preprocess_node(&$variables) {
  if (isset($variables['elements']['#view_mode']) && $variables['elements']['#view_mode'] !== 'full') {
    return;
  }

  if (!isset($variables['node'])) {
    return;
  }

  $node = $variables['node'];

  $field = field_info_instance('node', 'field_loop_book_children', $node->type);
  if ($field) {
    $variables['page_id'] = $node->nid;

    $book = Book::getInstance();
    $book_id = 0;
    $tree = $book->getTree($node->nid);
    if ($tree) {
      // If we're on tree root page.
      $book_id = $node->nid;
    }
    else {
      $parameters = drupal_get_query_parameters();
      if (!empty($parameters['book'])) {
        $book_id = intval($parameters['book']);
      }
      if (!$book_id) {
        $roots = $book->getRoots($node->nid);
        if (count($roots) === 1) {
          $book_id = $roots[0];
        }
      }
    }

    if ($book_id) {
      $tree = $book->getTree($book_id);
      if ($tree) {
        loop_book_theme_enrich_node($tree);
        $variables['loop_book_tree'] = $tree;
      }
    }
    else {
      $roots = $book->getRoots($node->nid);
      $roots = node_load_multiple($roots);
      foreach ($roots as $root) {
        $books[] = array(
          'nid' => $root->nid,
          'title' => $root->title,
          'url' => url('node/' . $root->nid, array('query' => array('book' => $root->nid))),
          'page_url' => url('node/' . $node->nid, array('query' => array('book' => $root->nid))),
        );
      }
      if (!empty($books)) {
        $variables['loop_book_roots'] = $books;
      }
    }
  }
}

/**
 * Add title and url to tree nodes.
 */
function loop_book_theme_enrich_node(array &$node, $root = NULL) {
  $drupal_node = node_load($node['id']);
  if (!$drupal_node) {
    return;
  }

  if (empty($root)) {
    $root = $node['id'];
  }

  if (isset($node['children'])) {
    foreach ($node['children'] as &$child) {
      loop_book_theme_enrich_node($child, $root);
    }
  }

  $url = $root
       ? url('node/' . $drupal_node->nid, array('query' => array('book' => $root)))
       : url('node/' . $drupal_node->nid);

  $node += [
    'nid' => $drupal_node->nid,
    'title' => $drupal_node->title,
    'url' => $url,
    'canonical_url' => url('node/' . $drupal_node->nid, array('alias' => TRUE)),
  ];
}
