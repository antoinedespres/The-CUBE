<?php include("view/menu.php");


if ($data[0] != null) {
    $dir = './Files/' . $_SESSION["UserID"];
    $fileList =
        '<table>
                <thead>
                    <tr>
                        <th colspan="4">Your files</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td>File</td>
                    <td>Category</td>
                    <td>Upload time</td>
                    <td>Edit link</td>
                </tr>';

    foreach ($data[0] as $file) {
        $fileList = $fileList .
            '<tr>
                    <td><a href="' . $dir . '/' . $file['FileName'] . '" download>' . $file['FileName'] . '</a></td>
                    <td>' . $file['Category'] . '</td> 
                    <td>' . $file['UploadDateTime'] . '</td>
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

if ($data[1] != null) {
    $dir = './Files/';
    $fileList =
        '<table>
                <thead>
                    <tr>
                        <th colspan="4">Your shared files</th>
                    </tr>
                </thead>
                <tbody>
                <tr>
                    <td>File</td>
                    <td>Category</td>
                    <td>Upload time</td>
                    <td>Owner</td>
                </tr>';

    foreach ($data[1] as $file) {
        $fileList = $fileList .
            '<tr>
                    <td><a href="' . $dir . '/' . $file['UserID'] . '/' . $file['FileName'] . '" download>' . $file['FileName'] . '</a></td>
                    <td>' . $file['Category'] . '</td> 
                    <td>' . $file['UploadDateTime'] . '</td> 
                    <td>' . $file['Email'] . '</td> 
                </tr>';
    }
    $fileList = $fileList .
        '</tbody>
            </table>';
    echo $fileList;
}

if ($data[0] == null && $data[1] == null) {
    echo '<p>No existing files</p>';
}
