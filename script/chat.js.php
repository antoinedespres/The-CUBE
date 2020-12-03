let chatBtn = null,
    chatContainer = null,
    chatHistory = null,
    chatInput = null,
    chatTextBox = null,
    chatSubmit = null,
    firstName = '';

(function load() {
    firstName = "<?= $_GET['FirstName'];?>";
    chatBtn = document.createElement('button');
    chatBtn.appendChild(document.createTextNode('chat'));
    chatBtn.setAttribute('style', 'position: fixed; right: 0; bottom: 0;');
    document.body.appendChild(chatBtn);
    chatBtn.addEventListener('click', () => open());
})();

function render() {
    chatContainer = document.createElement('div');
    chatHistory = document.createElement('div');
    chatInput = document.createElement('form');
    chatTextBox = document.createElement('input');
    chatSubmit = document.createElement('input');
    chatContainer.style.cssText = 'border: 1px solid black; height: 300px; width: 300px; position: fixed; right: 0; bottom: 0; text-align: left;';
    chatHistory.style.cssText = 'margin: 0; padding: 0; width: inherit';
    chatInput.style.cssText = 'display: inline; margin: 0; padding: 0; position: fixed; bottom: 0; width: inherit';
    chatTextBox.style.cssText = 'width: 80%';
    chatSubmit.style.cssText = 'width: 20%';
    chatInput.setAttribute('method', 'POST');
    chatTextBox.setAttribute('type', 'text');
    chatTextBox.setAttribute('name', 'text');
    chatSubmit.setAttribute('type', 'submit');
    chatSubmit.setAttribute('value', 'send');
    chatInput.appendChild(chatTextBox);
    chatInput.appendChild(chatSubmit);
    chatContainer.appendChild(chatHistory);
    chatContainer.appendChild(chatInput);
}

function open() {
    render();
    chatBtn.remove();
    document.body.appendChild(chatContainer);
    setInterval(listen, 100);
}

function listen() {
    let lastMsg = '',
        newMsg = "<?= $_POST['text'];?>";
    if(newMsg !== lastMsg) {
        update(newMsg);
        lastMsg = newMsg;
    }
}

function update(message) {
    msgArr = message.split(" ");
    let msg = document.createElement('span'),
        string = firstName + " : ";
    Array.prototype.forEach.call(msgArr, (str) => {
        string += str + " ";
    });
    msg.appendChild(document.createTextNode(string));
    chatHistory.appendChild(msg);
    chatHistory.appendChild(document.createElement('br'));
}