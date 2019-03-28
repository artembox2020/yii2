<script>
    // prepares data, ready for histogram
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

    // prepares options, ready for histogram
    function makeOptionsForHistogram()
    {
        var options = {};
        var colors = [];

        <?php 
            foreach (array_keys($data['options']) as $key):
                switch ($key) {
                    case 'colors':
                        foreach ($data['options'][$key] as $color):
        ?>
                            colors.push("<?= $color ?>");
        <?php   
                        endforeach;
                        break;
                }
            endforeach;
        ?>
        
        if (colors.length > 0) {
            options.colors = colors;
        }

        return options;
    }
</script>