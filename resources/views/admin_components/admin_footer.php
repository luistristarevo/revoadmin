<script src="<?php echo asset('../js/bootstrap.min.js'); ?>"></script>
<script src="<?php echo asset('../js/bootstrap-datepicker.min.js'); ?>"></script>
<script src="/js/app.js"></script>
<script>
    affixElement = $('#affix_cont');
    affixElement.affix({
        offset: {
            top: function () {return (this.top = $(affixElement).offset().top)}
        }
    });
</script>
</body>
</html>