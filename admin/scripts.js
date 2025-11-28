function onDeleteConfirmationChange(value){
    if(value == "confirm_delete")
        document.getElementById("object-delete-button").removeAttribute("disabled");
    else
        document.getElementById("object-delete-button").setAttribute("disabled", "");
}