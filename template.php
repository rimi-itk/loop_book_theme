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

  if (isset($node->type) && $node->type === 'loop_book') {
    $book = new Book();

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
        $variables['loop_book_navigation'] = $tree;
      }
    }
    else {
      $roots = $book->getRoots($node->nid);
      $nodes = node_load_multiple($roots);
      foreach ($nodes as $node) {
        $url = url('node/' . $node->nid, array('query' => array('book' => $node->nid)));
        $books[] = array(
          'nid' => $node->nid,
          'title' => $node->title,
          'url' => $url,
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
    'canonical_url' => url('node/' . $drupal_node->nid),
  ];
}

// function loop_book_theme_build_tree($node, $lang, $root = NULL) {
//   if (empty($root)) {
//     $root = $node;
//   }

//   $children = array();
//   if (!empty($node->field_loop_book_children)) {
//     foreach ($node->field_loop_book_children[$lang] as $info) {
//       $child = node_load($info['target_id']);
//       if ($child) {
//         $children[] = loop_book_theme_build_tree($child, $lang, $root);
//       }
//     }
//   }

//   $url = $root->nid != $node->nid
//        ? url('node/' . $node->nid, array('query' => array('book' => $root->nid)))
//        : url('node/' . $node->nid);

//   return array(
//     'nid' => $node->nid,
//     'title' => $node->title,
//     'url' => $url,
//     'canonical_url' => url('node/' . $node->nid),
//     'children' => $children,
//   );
// }

// function loop_book_theme_get_parent_ids($node) {
//   return db_select('field_data_field_loop_book_children')
//     ->condition('field_loop_book_children_target_id', $node->nid, '=')
//     ->fields(NULL, array('entity_id'))
//     ->execute()->fetchCol();
// }

// function loop_book_theme_has_parents($node) {
//   return count(loop_book_theme_get_parent_ids($node)) > 0;
// }
