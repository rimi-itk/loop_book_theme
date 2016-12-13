<?php
if (!function_exists('loop_book_theme_render_navigation_node')) {
	function loop_book_theme_render_navigation_node($node) {
		echo '<li>';
		echo '<a href="' . $node['url'] . '">' . $node['title'] . '</a>';
		if (!empty($node['children'])) {
			echo '<ol>';
			foreach ($node['children'] as $child) {
				loop_book_theme_render_navigation_node($child);
			}
			echo '</ol>';
		}
		echo '</li>';
	}
}

if (!function_exists('loop_book_theme_render_context_node')) {
	function loop_book_theme_render_context_node($node) {
		echo '<li>';
		echo '<a href="' . $node['url'] . '">' . $node['title'] . '</a>';
		echo '</li>';
	}
}
?>
<div class="loop-book--book">
	<div class="loop-book--book-navigation">
		<?php
		if (isset($loop_book_navigation)) {
			echo '<ol>';
			loop_book_theme_render_navigation_node($loop_book_navigation);
			echo '</ol>';
		}

		if (isset($loop_book_roots)) {
			echo '<ol>';
			foreach ($loop_book_roots as $node) {
				loop_book_theme_render_context_node($node);
			}
			echo '</ol>';
		}
		?>
	</div>

  <div class="loop-book--book-content">
		<?php include drupal_get_path('theme', 'bartik') . '/templates/node.tpl.php'; ?>
	</div>
</div>
