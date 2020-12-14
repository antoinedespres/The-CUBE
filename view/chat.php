<script type="text/javascript">

/*
**  There was an attempt at writting a chat without using NodeJS
**  Unsurprisingly, it didnt go well
**  Also even if it actually worked, in its current state all user would share the same channel l o l
*/


let chatBtn = null,
    chatContainer = null,
    chatHistory = null,
    chatInput = null,
    chatTextBox = null,
    chatSubmit = null,
    firstName = '',
    lastMsg = '';

(function load() {
   firstName = <?php echo json_encode($_SESSION['FirstName']); ?>;
    chatBtn = document.createElement('button');
    chatBtn.appendChild(document.createTextNode('chat'));
    chatBtn.setAttribute('style', 'position: fixed; right: 0; bottom: 0; height: 50px; width: 100px;');
    document.body.appendChild(chatBtn);
   chatBtn.addEventListener('click', () => open());
})();

function render() {
    chatContainer = document.createElement('div');
    chatHistory = document.createElement('div');
    chatInput = document.createElement('form');
    chatTextBox = document.createElement('input');
    chatSubmit = document.createElement('input');
    chatContainer.style.cssText = 'background-color: inherit; border: 1px solid black; height: 300px; width: 300px; position: fixed; right: 0; bottom: 0; text-align: left;';
    chatHistory.style.cssText = 'margin: 0; padding: 0; width: inherit';
    chatInput.style.cssText = 'display: inline; margin: 0; padding: 0; position: fixed; bottom: 0; width: inherit';
    chatTextBox.style.cssText = 'inherit; width: 80%';
    chatSubmit.style.cssText = 'width: 20%';
    chatInput.setAttribute('method', 'POST');
    chatTextBox.setAttribute('type', 'text');
    chatTextBox.setAttribute('name', 'msg');
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
    let newMsg = 'uWu xD LoL'; // , lastMsg = <?php // echo json_encode($_POST['msg']); ?>; this is the part that doesnt work but atleast it runs :)
    if (newMsg !== lastMsg) {
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

</script>