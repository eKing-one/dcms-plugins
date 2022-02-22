function x () {return;}
function FocusText() {
    document.message.msg.focus();
    document.message.msg.select();
    return true; }
function DoSmilie(addSmilie) {
    var revisedmsgage;
    var currentmsgage = document.message.msg.value;
    revisedmsgage = currentmsgage+addSmilie;
    document.message.msg.value=revisedmsgage;
    document.message.msg.focus();
    return;
}
function DoPrompt(action) { var revisedmsgage; var currentmsgage = document.message.qmsgage.value; }