function onDeleteConfirmationChange(value){
    if(value == "confirm_delete")
        document.getElementById("object-delete-button").removeAttribute("disabled");
    else
        document.getElementById("object-delete-button").setAttribute("disabled", "");
}

function onEditValueTextarea(textArea){
    textArea.style.height = 'auto'; 
    textArea.style.height = `${Math.max(textArea.placeholder.split("\n").length * textArea.style.lineHeight, textArea.scrollHeight+5)}px`;
}

function saveConfirmation(){
    return confirm("Are you sure you want to save this data?");
}

function deleteConfirmation(){
    return confirm("Are you sure you want to delete this data?");
}

function initializeTextArea(){
    let textAreas = document.querySelectorAll(".editor-table-textarea");
    textAreas.forEach((textArea)=>{
        textArea.addEventListener("input", function(e){ onEditValueTextarea(e.target); });
        onEditValueTextarea(textArea);
    });
}