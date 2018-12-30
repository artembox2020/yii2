<script>
(function() {

    /**
     * Toggles element
     * 
     * @param DOM Element element
     */ 
    function toggle(element)
    {
        var style = window.getComputedStyle(element);
        var display = style.getPropertyValue('display');
        if (display == 'none') {
            element.style.display = 'block';
        } else {
            element.style.display = 'none';
        }
    }

    /**
     * Toggles filter group on filter click
     * 
     * @param Event event
     */ 
    function filterTypeClickFunction(event)
    {
        var filterGroup = this.parentNode.querySelector(".filter-group");
        toggle(filterGroup);
        var glyphicon = filterGroup.parentNode.querySelector(".glyphicon");

        if (filterGroup.style.display == 'none') {
            glyphicon.classList.remove("rotate90");
            glyphicon.parentNode.classList.remove('border');
        } else {
            glyphicon.classList.add("rotate90");
            glyphicon.parentNode.classList.add('border');
        }
    }

    /**
     * Toggles filter general block on filter sign '+' click
     */ 
    function filterExpandClickFunction()
    {
        var filterMenus = document.querySelectorAll(".grid-view-filter .filter-menu");
        var filterMenu = this.parentNode.parentNode.querySelector(".filter-menu");
        for (var i = 0; i < filterMenus.length; ++i)
        {
            if (filterMenu !== filterMenus[i]) {
                filterMenus[i].style.display = 'none';
                var glyphicon = filterMenus[i].parentNode.querySelector(".glyphicon");
                glyphicon.classList.add("glyphicon-plus");
                glyphicon.classList.remove("glyphicon-minus");
            }
        }
        
        toggle(filterMenu);
        
        if (filterMenu.style.display == 'none') {
            this.classList.add("glyphicon-plus");
            this.classList.remove("glyphicon-minus");
        } else {
            this.classList.remove("glyphicon-plus");
            this.classList.add("glyphicon-minus");
        }
    }

    /**
     * Prevents default element behavior
     */
    function preventDefaultBehavior(event)
    {
        event.stopPropagation();
        event.preventDefault();
    }

    /**
     * Disables default behavior of DOM Elements
     * 
     * @param DOM Elements formElements
     */ 
    function disableDefaultBehaviorFormElements(formElements)
    {
        for (var i = 0; i < formElements.length; ++i) {
            formElements[i].addEventListener(
                "change",
                function(event)
                {
                    preventDefaultBehavior(event);
                },
                false
            );
        }
    }
    
    /**
     * Filter date select click processing function
     * 
     * @param DOM Element element
     * @param string value
     */ 
    function filterDateSelectClickFunction(element, value)
    {
        var dateSelect = document.createElement("select");
        var inputVar1 = element.closest(".filter-group").querySelector(".input-val1");
        var dateOptions = ['today', 'tomorrow', 'yesterday', 'lastweek', 'lastmonth', 'lastyear', 'certain'];
        var dateOptionsTranslations = [
            "<?= $today ?>",
            "<?= $tomorrow ?>",
            "<?= $yesterday ?>",
            "<?= $lastweek ?>",
            "<?= $lastmonth ?>",
            "<?= $lastyear ?>",
            "<?= $certain ?>",
        ];
        
        for (var i = 0; i < dateOptions.length; ++i)
        {
            var optionToday = document.createElement("option");
            optionToday.value = dateOptions[i];
            optionToday.innerHTML = dateOptionsTranslations[i];
            dateSelect.appendChild(optionToday);
        }

        dateSelect.value = value;

        dateSelect.onchange = function(event)
        {
            preventDefaultBehavior(event);
        };
        
        dateSelect.classList.add("input-val1");
        dateSelect.classList.add("form-control");
        dateSelect.name = inputVar1.name;
        
        inputVar1.parentNode.replaceChild(dateSelect, inputVar1);
        
    }
    
    /**
     * Erases input value
     * 
     * @param DOM Element element
     */ 
    function filterEraseInputValue(element)
    {
        var inputElement = element.closest(".form-group").querySelector("input.inputValue");
        inputElement.value = '';
    }

    /**
     * Filter sort arrow function
     * 
     * @param DOM Element element
     */
    function filterSortByArrowClick(element)
    {
        var filterMenu = element.closest(".filter-menu");
        var field = filterMenu.getAttribute('data-field');
        var a = filterMenu.closest(".journal-grid-view").querySelector("th a[data-sort=" + field + "]");
        
        if (typeof a == 'undefined' || a == null) {
            a = filterMenu.closest(".journal-grid-view").querySelector("th a[data-sort=-" + field + "]");
        }
        
        if (typeof a != 'undefined' && a != null) {
            a.click();
        }
    }

    var formElements = document.querySelectorAll(".grid-view-filter-form input, .grid-view-filter-form select");
    
    disableDefaultBehaviorFormElements(formElements);
        
    
    var filterTypes = document.querySelectorAll(".grid-view-filter-form .filter-type");
    var filterExpandIcons = document.querySelectorAll(".grid-view-filter-form .filter-prompt .glyphicon");
    var filterValueContainers =  document.querySelectorAll(".grid-view-filter-form .filter-value-container");
    var filterHyperlinks = document.querySelectorAll(".grid-view-filter-form .left-hyperlink a");
    var dateFilterSelects  = document.querySelectorAll(".grid-view-filter-form .filter-menu .filter-group select[name='filterCondition[date]']");
    var dateFilterInputs  = document.querySelectorAll(
        ".grid-view-filter-form .filter-menu input[name='inputValue[date]']," +
        ".grid-view-filter-form .filter-menu input[name='val2[date]']"
    );
    var cancelButtons = document.querySelectorAll(".grid-view-filter-form .btn-cancel");
    var orderArrows = document.querySelectorAll(".grid-view-filter-form .filter-menu .order");

    for (var i =0; i < filterTypes.length; ++i)
    {
        filterTypes[i].onclick = filterTypeClickFunction;
    }

    for (var i =0; i < filterExpandIcons.length; ++i)
    {
        filterExpandIcons[i].onclick = filterExpandClickFunction;
    }

    for (var i = 0; i < dateFilterSelects.length; ++i)
    {
        dateFilterSelects[i].onchange = function()
        {
            filterDateSelectClickFunction(this, "<?= $params['val1']['date']  ?>");
        };
        dateFilterSelects[i].onchange();
    }
    
    for (var i = 0; i < dateFilterInputs.length; ++i)
    {
        dateFilterInputs[i].onchange = function(event)
        {
            preventDefaultBehavior(event);
        };
    }

    for (var i = 0; i < filterHyperlinks.length; ++i)
    {
        filterHyperlinks[i].onclick = function()
        {
            filterEraseInputValue(this);
        }
    }

    for (var i = 0; i < cancelButtons.length; ++i)
    {
        cancelButtons[i].onclick = function()
        {
            this.closest(".grid-view-filter").querySelector(".glyphicon").click();
        }
    }

    for (var i = 0; i < orderArrows.length; ++i)
    {
        orderArrows[i].onclick = function()
        {
            filterSortByArrowClick(this);
        };
    }

    var summaryShapter = document.querySelectorAll(".journal-grid-view .summary-shapter-for-status");

    // update summary string for mashine state data

    if (summaryShapter.length > 0) {
        summaryShapter = summaryShapter[0];
        var ul = document.querySelector(".journal-grid-view ul.pagination");
        var activeA = ul.querySelector("li.active a");
        var numberOfRecords = document.querySelectorAll(".journal-grid-view table tbody > tr").length;
        var startIndex = parseInt(activeA.dataset.page) * <?= $pageSize ?> + 1;
        summaryShapter.querySelector('.start').innerHTML = startIndex;
        summaryShapter.querySelector('.end').innerHTML = (startIndex + numberOfRecords - 1);

        var numberOfLis = ul.querySelectorAll('li').length;
        var checkHasDisabled = ul.querySelectorAll('li.next.disabled').length;

        if (numberOfLis >= 2) {
            var lastLi = ul.querySelectorAll("li")[numberOfLis - 2];
            var lastA = lastLi.querySelector("a");

            if (lastLi.classList.contains("active")) {
                var total = parseInt(lastA.dataset.page)*<?= $pageSize ?> + numberOfRecords;
                var isApproximate = false;
            } else {
                var total = (parseInt(lastA.dataset.page) + 1)* <?= $pageSize ?>;
                var isApproximate = true;
            }

            var summaryShapterTotal = parseInt(summaryShapter.querySelector(".total").innerHTML);

            if (summaryShapterTotal < total || checkHasDisabled) {

                if (!isApproximate) {
                    summaryShapter.querySelector('.approximate').remove();
                }

                summaryShapter.querySelector('.total').innerHTML = total;
            }
        }
    }
    
    // popup window for log aggregated status data

    var aggregatedStatusCells = document.querySelectorAll(".jlog-index table.table .aggregated-status-info");
    for (var i = 0; i< aggregatedStatusCells.length; ++i)
    {
        var cell = aggregatedStatusCells[i];
        cell.onmouseover = function()
        {
            var popupBlock = this.querySelector('.popup-block .label');
            popupBlock.style.display = 'block';
        }

        cell.onmouseout = function()
        {
            var popupBlock = this.querySelector('.popup-block .label');
            popupBlock.style.display = 'none';
        }
    }
    
})();
</script>