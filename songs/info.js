const urlParams = new URLSearchParams(window.location.search);
    var songId = urlParams.get('q');
    if (songId) {
        fetch('songs.json').then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok. Unable to fetch songs list.');
            }
            return response.json(); // Parse JSON data
        }).then(data => {
            console.log(data.songs);
            const song = data.songs.find(s => s.id == songId);
            if (!song) {
                throw new Error('Song not found in the songs list.');//TODO: Shows error if song not found
            }
            document.getElementById('song-title').innerText = song.title;
            document.getElementById('song-artist').innerText = song.artist;
            document.getElementById('song-language').innerText = song.language;
            document.getElementById('song-genre').innerText = song.genre;
            document.getElementById('song-cover').setAttribute("src", song.coverImg); 
            document.getElementById('song-video').setAttribute("src", song.video); 
            return fetch(`/songs/${songId}/lyrics.txt`);
        }).then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok. Unable to fetch lyrics.');
            }
            return response.text(); // Parse text data
        }).then(data => {
            document.getElementById('song-lyrics').innerText = data;
        }).catch(error => {
            console.error('There was a problem with the fetch operation:', error);
        });
        fetch(`/songs/${songId}/details.json`).then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok. Unable to fetch song details.');
            }
            return response.json(); // Parse JSON data
        }).then(data => {

        }).catch(error => {
        console.error('There was a problem with the fetch operation:', error);
    });
}