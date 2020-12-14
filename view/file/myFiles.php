<head>
	<link href="./style.css" rel="stylesheet" type="text/css">
    <script src="../../script/confirmDelete.js"></script>
</head>

<?php
include("view/menu.php");

echo '<body class="styleBody">
    <div id="myFiles">';

// data[0] contains the files owned by the current user
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
                    <td>Edit</td>
                    <td>Download</td>
                    <td>Delete</td>
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

        $fileList = $fileList .
                    '</td>
                    <td><a href="' . $dir . '/' . $file['FileName'] . '" download><img id="iconDownload" src="assets/download_icon.png"></img></a></td>
                    <td><a onclick="confirmDelete(\'' . $file['FileName'] . '\')"><img id="iconDelete" src="/assets/delete_icon"></img></a></td>
                </tr>';
    }
    $fileList = $fileList .
            '</tbody>
        </table>';
    echo $fileList;
}

// data[1] contains the files shared to the current user
if ($data[1] != null) {
    $dir = './Files/';
    $fileList =
        '<table>
            <thead>
                <tr class="titleHeader">
                    <th colspan="6">Files shared with you</th>
                </tr>
                <tr>
                    <td>File</td>
                    <td>Category</td>
                    <td>Upload time</td>
                    <td>Owner</td>
                    <td>Edit</td>
                    <td>Download</td>
                </tr>
            </thead>
            <tbody>';

    foreach ($data[1] as $file) {
        $fileList = $fileList .
                '<tr>
                    <td>' . $file['FileName'] . '</td>
                    <td>' . $file['Category'] . '</td> 
                    <td>' . $file['UploadDateTime'] . '</td> 
                    <td>' . $file['Email'] . '</td> 
                    <td>';
        if ($file['Category'] == 'Text')
            $fileList = $fileList . '<a href="/File_Editing?file_id=' . $file['FileID'] . '"><img id="iconEdit" src="/assets/pencil_icon.png"></img></a>';
        
        $fileList = $fileList . 
                    '</td>
                    <td><a id="file" href="' . $dir . '/' . $file['UserID'] . '/' . $file['FileName'] . '" download><img id="iconDownload" src="assets/download_icon.png"></img></a></td>
                </tr>';
    }
    $fileList = $fileList .
            '</tbody>
        </table>';
    echo $fileList;
}
if ($data[0] == null && $data[1] == null) {
    echo "<p>No file found.</p>";
}
echo '</div>
</body>';
?>