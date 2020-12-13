function confirmDelete(name){
    if (confirm("Do you really want to delete this file?"))
        window.location.replace('/deleteFile?fileName=' + name)
}