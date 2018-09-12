// global keyup event time interval handler
var hKeyUpInterval;

/**
 * @param string formSelector
 */ 
function submitForm(formSelector)
{
    var form = document.querySelector(formSelector);
    var formButton = form.querySelector('button[type=submit], input[type=submit]');
    formButton.click();
}

/**
 * @param DOM Element form
 * @param DOM Element formElement
 */
function fillHiddenSelectionFields(form, formElement)
{
    var formCaretPos = getCaretPos(formElement);
    var focused = document.activeElement;
    if (!focused || focused == document.body) {
        focused = formElement;
    } else if (document.querySelector) {
        focused = document.querySelector(":focus");
    }
    var formHiddenSelectionName = form.querySelector('input[name=selectionName]');
    var formHiddenSelectionCaretPos = form.querySelector('input[name=selectionCaretPos]');
    formHiddenSelectionName.value = focused.name;
    formHiddenSelectionCaretPos.value = formCaretPos; 
}

/**
 * @param Object obj
 */
function getCaretPos(obj)
{
    obj.focus();

    if (obj.selectionStart) {

        return obj.selectionStart;
    } else if (document.selection) {
        var sel = document.selection.createRange();
        var clone = sel.duplicate();
        sel.collapse(true);
        clone.moveToElementText(obj);
        clone.setEndPoint('EndToEnd', sel);

        return clone.text.length;
    }

    return 0;
}
