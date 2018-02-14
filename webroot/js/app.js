let _files = [];

$(function() {
    // I might not need jquery. Oh no wait, Foundation does.
    $(document).foundation();
    ready();
});

// shortcut to document.getelementid
id = (element) => {
    return document.getElementById(element);
}

ready = () => {
    let uploader = id('upload-files');
    uploader.addEventListener('dragover', dragOver);
    uploader.addEventListener('dragleave', dragOver);
    uploader.addEventListener('click', () => $('#uploadModal').foundation('open'));
    uploader.addEventListener('drop', handleSelect);
}

dragOver = (event) => {
    event.stopPropagation();
    event.preventDefault();

    event.target.className = (event.type === "dragover" ? "hover" : "");
    event.target.innerHTML = (event.type === "dragover" ? "HIERZOO" : "DROP BESTANDEN");
}
handleSelect = (event) => {
    dragOver(event);
    _files = [];

    let files = event.target.files || event.dataTransfer.files;

    // Sure, probeer maar te omzeilen, ze worden in de backend toch nog eens gecontroleerd xx.
    let _allowedFiles = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'video/mpeg',
        'video/mp4',
        'audio/mpeg',
        'audio/ogg',
        'video/ogg',
        'audio/webm',
        'audio/x-wav',
        'audio/wav',
        'video/webm',
        'audio/midi',
        'audio/mid',
    ]

    $('.preview-medias').html('');
    $('.denied-medias').html('');

    for(let i = 0; i < files.length; i++) {

        let file = files[i];

        if(_allowedFiles.indexOf(file.type) >= 0) {
            _files.push(file);
            $('.preview-medias').append("<li>"+ file.name +" ~ "+ bytify(file.size) +"</li>");
        }
        else
        {
            $('.denied-medias').append("<li style='color:tomato;'>"+ file.name +" ~ "+ bytify(file.size) +"</li>")
        }
    }

    $('#uploadModal').foundation('open');
}

emptyList = () => {
    $('.preview-medias').html('');
    $('.denied-medias').html('');
}

bytify = (bytes, precision) => {
    if (isNaN(parseFloat(bytes)) || !isFinite(bytes)) return '-';
    if (typeof precision === 'undefined') precision = 1;
    var units = ['bytes', 'kB', 'MB', 'GB', 'TB', 'PB'],
        number = Math.floor(Math.log(bytes) / Math.log(1024));
    return (bytes / Math.pow(1024, Math.floor(number))).toFixed(precision) + ' ' + units[number];
}

uploadFiles = (event) => {
    event.preventDefault();

    let request = $.ajax({
        'url': $('#upload-files-form').attr('action'),
        'data': {files: _files, data: $('#upload-files-form').serialize()},
        'type': 'POST',
        'dataType': 'JSON',
    });
}