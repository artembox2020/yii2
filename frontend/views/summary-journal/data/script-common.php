<script>

        // appends tr to table
        function appendTr(element, value)
        {
            var tr = document.createElement('tr');
            tr.dataset.key = 1;
            var td = document.createElement('td');
            td.classList.add('cell-device');
            td.innerHTML = value;
            tr.appendChild(td);
            element.appendChild(tr);
        }

        // appends tr with attribute to table
        function appendTrWithAttrs(element, value, attrName, attrValue)
        {
            var tr = document.createElement('tr');
            tr.dataset.key = 1;
            var td = document.createElement('td');
            td.classList.add('cell-device');
            td.dataset[attrName] = attrValue;
            td.innerHTML = value;
            tr.appendChild(td);
            element.appendChild(tr);
        }

        // appends tr to table and sets rowspan
        function appendTr2(element, value, rowspan)
        {
            var tr = document.createElement('tr');
            tr.dataset.key = 1;
            tr.style.height = 39 * rowspan + 'px';
            tr.style.width = '100%';
            var td = document.createElement('td');
            td.classList.add('cell-device');
            td.innerHTML = value;
            tr.appendChild(td);
            element.appendChild(tr);
        }

        // makes float value from element
        function parse(element)
        {
            var element2 = element.querySelector('span.td-cell');

            if (element2 != null) {
                element = element2;
            }

            var number = parseFloat(element.innerHTML);
            if (isNaN(number)) {

                return 0;
            }

            return preciseNumber(number);
        }

        // makes float income value from element
        function parseIncomes(element)
        {
            var number = parseFloat(element.dataset.income);

            if (isNaN(number)) {

                return 0;
            }

            return preciseNumber(number);
        }

        // makes float idle hours value from element
        function parseIdles(element)
        {
            var number = parseFloat(element.dataset.idleHours);

            if (isNaN(number)) {

                return 0;
            }

            return preciseNumber(number);
        }

        // makes float value from element
        function makeNumberFromElement(element, divideBy)
        {
            var number = parseFloat(element.innerHTML), sum = 0;

            if (number != 0) {
                if (typeof divideBy != "undefined" && divideBy != null && divideBy.innerHTML != null) {
                    divideBy = parseFloat(divideBy.innerHTML);
                    sum = number / divideBy;
                }
            }

            return preciseNumber(sum);
        }

        // precises number
        function preciseNumber(number)
        {
            if (isNaN(number)) {

                return 0;
            }
            if (Math.round(number) != number) {
                number = parseFloat(number).toFixed(2);
            }

            return parseFloat(number);
        }

        // makes division and precision
        function divideBy(number, divide)
        {
            if (typeof divide != "undefined" && divide) {
                    number = number / parseFloat(divide);
            } else {
                number = 0;
            }

            if (Math.round(number) != number) {
                number = number.toFixed(2);
            }

            return parseFloat(number);
        }
</script>