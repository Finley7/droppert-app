let _files = [];

$(function() {
    // I might not need jquery. Oh no wait, Foundation does.
    $(document).foundation();
    $('.loader').hide();
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
        'audio/webm',
        'audio/x-wav',
        'audio/wav',
        'video/webm',
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

    if(_files.length > 0) {

        $('#upload-button').attr('disabled', true);
        $('#upload-button').val('...');

        $('#upload-modal-body').hide();
        $('.loader').show();

        let form = $('#upload-files-form');
        let formData = new FormData();

        $.each($('#upload-files-form input'), (key, input) => {
            formData.append($(input).attr('name'), $(input).attr('value'));
        });

        $.each(_files, (key, file) => {
            formData.append('file_' + key, file);
        });

        let request = $.ajax({
            'url': $('#upload-files-form').attr('action'),
            'data': formData,
            'processData': false,
            'contentType': false,
            'type': 'POST',
            'dataType': 'JSON',
            'success': (response) => {
                $.each(response.uploaded, (key, uploadedFile) => {

                    $.each(_files, (key, file) => {

                        if (file.name == uploadedFile.name + '.' + uploadedFile.extension) {
                            $('.preview-medias').html('').append('<li style="color:#088A08"><i class="fa fa-check"></i> ' + file.name + '</li>');
                        }

                    });

                });

                $.each(response.denied, (key, deniedFile) => {

                    $.each(_files, (key, file) => {

                        if (file.name == deniedFile.name + '.' + deniedFile.extension) {
                            $('.denied-medias').html('').append('<li style="color:tomato"><i class="fa fa-times"></i> ' + file.name + ' ~ ' + deniedFile.reason +'</li>');

                            $('#upload-modal-body').show();
                            $('.loader').hide();

                            if(response.uploaded.length < 1) {

                                $('#upload-button').attr('disabled', false);
                                $('#upload-button').val('Upload');
                            }
                        }

                    });

                });

                if (response.uploaded.length > 0) {
                    $('#upload-modal-body').show();
                    $('.loader').hide();
                    $('#upload-input').remove();
                    $('#upload-button').remove();
                    $('#upload-button-group').append('<br><a href="' + response.action + '" class="success button expanded icon next">Volgende <i class="fa fa-arrow-right"></i></a>');
                }
            },
            'error': (error) => {
                console.log(error);

                alert(error.responseText);

                $('#upload-modal-body').show();
                $('.loader').hide();

                $('#upload-button').attr('disabled', false);
                $('#upload-button').val('Upload');
            }
        });
    }
}