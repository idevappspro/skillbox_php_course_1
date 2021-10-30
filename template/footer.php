<div class="clearfix">
    <?php showMenu(arraySort($menu, 'title', SORT_DESC),'bottom');?>
</div>
<div class="footer">&copy;&nbsp;<nobr><?= date('Y') ?></nobr>
    Project.
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script src="/js/app.js"></script>
<?php if (isCurrentUrl('/gallery/')): ?>
    <script src="/js/gallery.js"></script>
<?php endif; ?>
</body>
</html>