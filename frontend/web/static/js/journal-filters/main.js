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
    var formName = formElement.name;
    var formCaretPos = getCaretPos(formElement);
    var formHiddenSelectionName = form.querySelector('input[name=selectionName]');
    var formHiddenSelectionCaretPos = form.querySelector('input[name=selectionCaretPos]');
    formHiddenSelectionName.value = formElement.name;
    formHiddenSelectionCaretPos.value = formCaretPos; 
}

/**
 * @param string event
 * @param string formSelector
 * @param DOM Element formElement
 */
function eventProcessFunction(event, formSelector, formElement)
{
    if (event == 'keyup') {
        var form = document.querySelector(formSelector);
        fillHiddenSelectionFields(form, formElement);
    }
    submitForm(formSelector); 
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
