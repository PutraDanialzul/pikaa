function setURLParameter(key, value){
    const params = new URLSearchParams(window.location.search);
    // Add or update a query parameter
    params.set(key, value);
    // Update the URL without refreshing
    const newUrl = `${window.location.pathname}?${params.toString()}`;
    window.history.replaceState(null, '', newUrl);
}

function viewFeedbackButton(){
    setURLParameter("q", "view-feedback");
}

function editSongsButton(){
    setURLParameter("q", "edit-songs");
}