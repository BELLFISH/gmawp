  </main>

<?php if(!is_front_page()){ echo get_crumbs(); } ?>

  <footer><?php
    include('template-parts/nav-logo.php');
    include('template-parts/nav-menu.php'); ?>
  </footer>
</div><!-- wrapper -->

<?php wp_footer(); ?>
</body>
</html>
