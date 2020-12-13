<head>
	<link href="./style.css" rel="stylesheet" type="text/css">
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
        <td><a><img id="iconDelete" src="/assets/delete_icon"></img></a></td></tr>';
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
                    <td><a href="' . $dir . '/' . $file['UserID'] . '/' . $file['FileName'] . '" download>' . $file['FileName'] . '</a></td>
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
