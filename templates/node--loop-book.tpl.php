<?php
if (!function_exists('loop_book_theme_render_tree')) {
	function loop_book_theme_render_tree($node, $current_page_id, $level = 0) {
    $is_root = $level === 0;

    if ($is_root) {
      echo '<h2>' . '<a href="' . $node['url'] . '">' . $node['title'] . '</a>' . '</h2>';
      echo '<div class="guide--nav-wrapper-inner">';
      echo '<ul class="guide--nav-list">';
    } else {
      echo '<li class="guide--nav-list-item has-sublist">';
      echo '<a class="' . ($node['id'] === $current_page_id ? 'active' : '') . '" href="' . $node['url'] . '">' . $node['title'] . '</a>';
    }

    if (!empty($node['children'])) {
      echo '<ul class="guide--nav-' . ($level > 0 ? 'sub' : '' ) . 'list">';
      foreach ($node['children'] as $child) {
        loop_book_theme_render_tree($child, $current_page_id, $level + 1);
      }
      echo '</ul>';
    }

    if ($is_root) {
      echo '</ul>';
      echo '</div>';
    } else {
      echo '</li>';
    }
	}
}
?>
<div class="loop-book--book">
	<div class="loop-book--book-navigation guide--nav-wrapper">
		<?php
		if (isset($loop_book_tree)) {
			loop_book_theme_render_tree($loop_book_tree, $page_id);
		}

		if (isset($loop_book_roots)) {
      echo '<h2>' . 'Books' . '</h2>';
      echo '<ul class="guide--nav-list">';
			foreach ($loop_book_roots as $root) {
        echo '<li class="guide--nav-list-item">';
        echo '<a href="' . $root['page_url'] . '">' . $root['title'] . '</a>';
        echo '</li>';
      }
			echo '</ul>';
		}
		?>
	</div>

  <div class="loop-book--book-content">
		<?php include drupal_get_path('theme', 'bartik') . '/templates/node.tpl.php'; ?>
	</div>
</div>
