<head>
	<link href="./style.css" rel="stylesheet" type="text/css">
    <script src='../../script/confirmDelete.js'></script>
</head>
<?php include("view/menu.php");

echo '<body class="styleBody">
<div id="myFiles">';
if ($data[0] != null) {
    $dir = './Files/' . $_SESSION["UserID"];
    $fileList =
        '<table>
                <thead>
                    <tr class="titleHeader">
                        <th colspan="6">Your files</th>
                    </tr>
                    <tr>
                        <td>File</td>
                        <td>Category</td>
                        <td>Upload time</td>
                        <td>Edit link</td>
                        <td>Download link</td>
                        <td>Delete link</td>
                    </tr>
                </thead>
                <tbody>';

    foreach ($data[0] as $file) {
        $fileList = $fileList .
            '<tr>
                    <td>' . $file['FileName'] . '</td>
                    <td>' . $file['Category'] . '</td> 
                    <td>' . $file['UploadDateTime'] . '</td>
                    <td>';
        if ($file['Category'] == 'Text')
            $fileList = $fileList . '<a href="/File_Editing?file_id=' . $file['FileID'] . '"><img id="iconEdit" src="/assets/pencil_icon.png"></img></a>';
                    $fileList = $fileList . '<td><a href="' . $dir . '/' . $file['FileName'] . '" download><img id="iconDownload" src="assets/download_icon.png"></img></a></td>';
        $fileList = $fileList . '</td>
        <td><a onclick="confirmDelete(\'' . $file['FileName'] . '\')"><img id="iconDelete" src="/assets/delete_icon"></img></a></td></tr>';
    }
    $fileList = $fileList .
        '</tbody>
            </table>';
    echo $fileList;
}

if ($data[1] != null) {
    $dir = './Files/';
    $fileList =
        '<table>
                <thead>
                    <tr class="titleHeader">
                        <th colspan="6">Your shared files</th>
                    </tr>
                    <tr>
                        <td>File</td>
                        <td>Category</td>
                        <td>Upload time</td>
                        <td>Owner</td>
                        <td>Edit link</td>
                    </tr>
                </thead>
                <tbody>';

    foreach ($data[1] as $file) {
        $fileList = $fileList .
            '<tr>
                    <td><a id="file" href="' . $dir . '/' . $file['UserID'] . '/' . $file['FileName'] . '" download>' . $file['FileName'] . '</a></td>
                    <td>' . $file['Category'] . '</td> 
                    <td>' . $file['UploadDateTime'] . '</td> 
                    <td>' . $file['Email'] . '</td> 
                    <td>';
                    if ($file['Category'] == 'Text')
                        $fileList = $fileList . '<a href="/File_Editing?file_id=' . $file['FileID'] . '">Edit</a>';
                    $fileList = $fileList . '</td></tr>';
    }
    $fileList = $fileList .
        '</tbody>
            </table>';
    echo $fileList;
}
if ($data[0] == null && $data[1] == null) {
    echo '<p>No existing files.</p>';
}
echo '</div>
</body>';

?>

<script type="text/javascript">
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
    let newMsg = "*purrs uWu~*"; <?php //echo json_encode($_POST['msg']); ?>;
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
</script>