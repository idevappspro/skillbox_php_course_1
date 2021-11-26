<nav class="navbar pt-0 pb-0">
    <div class="container navbar-dark bg-dark">
        <div class="navbar-text">&copy;&nbsp;<nobr><?= date('Y') ?></nobr>
            Project.
        </div>
    </div>
</nav>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script src="/vendor/fontawesome/js/all.min.js"></script>
<script src="/js/app.js"></script>
<?php if (isCurrentUrl('/gallery/')) : ?>
    <script src="/js/gallery.js"></script>
<?php endif; ?>
</body>

</html>
