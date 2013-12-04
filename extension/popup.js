function add() {
    chrome.tabs.getSelected(null, function(tab) {
    var form = document.createElement("form");
    form.setAttribute("method", "POST");
    form.setAttribute("action", "http://ec2-67-202-55-42.compute-1.amazonaws.com/parser/parser/parser.php?url="+tab.url);
    form.submit();
});
}
window.onload = add;