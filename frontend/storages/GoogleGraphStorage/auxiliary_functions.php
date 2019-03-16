<script>
    function makeDataForHistogram()
    {
        var dataArray = [];

        var titles = [];
        <?php foreach ($data['titles'] as $title): ?>
            titles.push("<?= $title ?>");
        <?php endforeach; ?>

        dataArray.push(titles);

        var lines = [];
        <?php foreach ($data['lines'] as $line): ?>
            var line = [];
            <?php foreach ($line as $item): ?>

                <?php if (is_numeric($item)): ?>
                    line.push(<?= $item ?>);
                <?php else: ?>
                    line.push('<?= $item ?>');
                <?php endif; ?>

            <?php endforeach; ?>

            dataArray.push(line);
        <?php endforeach; ?>

        return dataArray;
    }
</script>