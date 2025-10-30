let urlParam = new URLSearchParams(window.location.search);
let sort = urlParam.get('sort');

fetch('songs.json').then(response => {
    if (!response.ok) {
        throw new Error('Network response was not ok. Unable to fetch songs list.');
    }
    else return response.json();
}).then(data => {
    let songs = data.songs;
    let table = document.getElementById('song-table');
    songs.forEach(song => {
        table.insertAdjacentHTML('beforeend', `
        <tr class="info">
              <td> <a href="info?q=${song.id}">${song.title}</a> </td>
              <td> ${song.genre} </td>
              <td> ${song.language} </td>
        </tr>
        `);
    });
}).catch(error => {
    console.error('There was a problem with the fetch operation:', error);
});