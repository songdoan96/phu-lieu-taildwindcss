<?php
if (isset($_SESSION["user"]) && $_SESSION["user"]) { ?>
    <div class="fixed bottom-4 left-1/2 -translate-x-1/2 max-w-xs text-center z-40">
        <a href="nhap-kho.php" title="Thêm khách hàng" class="hover:translate-y-1 w-12 h-12 bg-blue-700 hover:bg-blue-800 p-2 rounded-full flex items-center justify-center">
            <svg viewBox="0 0 20 20" enable-background="new 0 0 20 20" class="w-6 h-6 inline-block">
                <path fill="#FFFFFF" d="M16,10c0,0.553-0.048,1-0.601,1H11v4.399C11,15.951,10.553,16,10,16c-0.553,0-1-0.049-1-0.601V11H4.601
            C4.049,11,4,10.553,4,10c0-0.553,0.049-1,0.601-1H9V4.601C9,4.048,9.447,4,10,4c0.553,0,1,0.048,1,0.601V9h4.399
            C15.952,9,16,9.447,16,10z" />
            </svg>
        </a>
    </div>
<?php } ?>
<script src="assets/js/main.js?v=<?= time() ?>"></script>
</body>

</html>