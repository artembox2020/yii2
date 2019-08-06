// show more button click
function showmoreClickProcess(button, trSelector)
{
    var trs = netManager.querySelectorAll(trSelector);
    var pageInitSize = parseInt(netManager.querySelector('.page-size-initial').value);
    var pageSize = parseInt(netManager.querySelector('.page-size').value);
    var i = 0;

    for (; i < trs.length; ++i) {

        if (trs[i].classList && trs[i].classList.contains('hidden')) {
            trs[i].classList.remove('hidden');

            if (++pageSize % pageInitSize == 0) {
                break;
            }
        }
    }

    netManager.querySelector('.page-size').value = pageSize;
    makeButtonsVisibility(buttonLess, buttonMore, trs.length);
    updateStatusString(pageSize, null);
}

// show more button click
function showlessClickProcess(button, trSelector)
{
    var trs = netManager.querySelectorAll(trSelector);
    var pageInitSize = parseInt(netManager.querySelector('.page-size-initial').value);
    var pageSize = parseInt(netManager.querySelector('.page-size').value);
    var i = trs.length - 1;
    var count = trs.length;

    for (; i >= 0; --i) {

        if (trs[i].classList && trs[i].classList.contains('hidden-row')) {
            continue;
        }

        if (!trs[i].classList || !trs[i].classList.contains('hidden')) {
            trs[i].classList.add('hidden');

            if (--pageSize % pageInitSize == 0) {
                break;
            }
        }
    }

    netManager.querySelector('.page-size').value = pageSize;
    makeButtonsVisibility(buttonLess, buttonMore, count);
    updateStatusString(pageSize, null);
}

// makes buttons visible
function makeButtonsVisibility(buttonLess, buttonMore, count)
{
    var pageInitSize = parseInt(netManager.querySelector('.page-size-initial').value);
    var pageSize = parseInt(netManager.querySelector('.page-size').value);

    if (pageSize >= count) {
        buttonMore.classList.add('hidden');
        if (pageSize > pageInitSize) {
            buttonLess.classList.remove('hidden');
        } else {
            buttonLess.classList.add('hidden');
        }
        direction = 'down';
    } else {
        if (pageSize <= pageInitSize) {
            direction = 'up';
        }

        if (direction == 'up') {
            buttonMore.classList.remove('hidden');
            buttonLess.classList.add('hidden');
            if (pageSize >= count) {
                direction = 'down';
            }
        } else {
            buttonMore.classList.add('hidden');
            buttonLess.classList.remove('hidden');
        }
    }
}