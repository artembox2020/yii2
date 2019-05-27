<script>
    var maxValue = 100000; // max value

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

    // prepares data, ready for histogram by active item
    function makeDataForHistogramByActive(index, minValue)
    {
        var dataArray = [];

        var titles = [];
        var i = 0;
        var styleObj = {'type': 'string', 'role': 'style'};

        <?php foreach ($data['titles'] as $title): ?>
            titles.push("<?= $title ?>");
            if (i == index) {
                titles.push(styleObj);
            }
            ++i;
        <?php endforeach; ?>

        var addressString = titles[index];
        if (!addressString.includes(", ")) {
            addressString += ", ";
        }

        var start = <?= $start ?? 0 ?>;
        var end = <?= $end ?? 0 ?>;

        var initializationData = JSON.parse(graphBuilder.getInitializationData(addressString, start, end));

        dataArray.push(titles);

        var lines = [];
        start = 0;
        end = 0;

        <?php foreach ($data['lines'] as $line): ?>
            var line = [];
            var i = 0;

            <?php $i = 0; foreach ($line as $item): ?>

                <?php if (is_numeric($item)): ?>
                    if (i != index) {
                        line.push(minValue);
                    } else {
                        line.push(<?= $item ?>);
                        if (checkPointsBetweenInitializationData(initializationData, start, end)) {
                            line.push('point { size: 6; }');
                        } else {
                            line.push(null);
                        }
                    }
                <?php elseif ($i == 0): ?>
                    line.push('<?= $item ?>');
                    start = end;
                    end = getTimestampByDate('<?= $item ?>');
                <?php endif; ?>
                ++i;
            <?php ++$i; endforeach; ?>

            dataArray.push(line);
        <?php endforeach; ?>

        return dataArray;
    }

    // prepares data, ready for line 
    function makeDataForLine(minValue)
    {
        <?php if (count($data['titles']) == 2): ?>

            return makeDataForHistogramByActive(1, minValue);
        <?php endif; ?>

        return makeDataForHistogram();
    }

    // converts timestamp from string representation 
    function getTimestampByDate(date)
    {
        var mainParts = date.split(" ");
        var dateParts = mainParts[0].split(".");
        var timeParts = mainParts[1].split(":");
        var dateString = dateParts[1] + "/" + dateParts[0] + "/" + dateParts[2];

        var dateTimestamp = Date.parse(dateString) / 1000;
        var timeTimestamp = parseInt(timeParts[0])*3600 + parseInt(timeParts[1])*60;

        return dateTimestamp + timeTimestamp;
    }

    // check whether initialization point lies between [start, end]
    function checkPointsBetweenInitializationData(initializationData, start, end)
    {
        for (var i = 0; i < initializationData.length; ++i) {
            var item = initializationData[i];

            if (item.unix_time_offset >= start && item.unix_time_offset <= end) {

                return true;
            }

            if (item.unix_time_offset > end) {

                return false;
            }
        }

        return false;
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

    // get minimal value
    function getMinValue(defaultValue)
    {
        var minValue = maxValue;
        var isInitialized = false;
        <?php foreach ($data['lines'] as $line): ?>
            <?php foreach ($line as $item): ?>

                <?php if (is_numeric($item)): ?>
                    var value = <?= $item ?>;
                    isInitialized = true;
                    if (value < minValue) {
                        minValue = value; 
                    }
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endforeach; ?>

        if (isInitialized) {

            return minValue;
        }

        return defaultValue;
    }
</script>