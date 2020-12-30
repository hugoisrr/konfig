$(document).ready(function() {
    $('#femaleFilesForm').submit(function (e){
        e.preventDefault();

        let forData = $(this).serialize();

        $.ajax({
            url: "{{ route('uploadFile') }}",
            type: "POST",
            data: forData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                console.log(response);
                $('#translationCourseFiles tbody').prepend(
                    '<tr>' +
                    '<td> Name </td>' +
                    '<td> Type </td>' +
                    '<td> Date </td>' +
                    '<td> Download </td>' +
                    '<td> Delete </td>' +
                    '</tr>'
                );
                $('#femaleFilesForm')[0].reset();
            },
            error: function () {
                alert('There was an error');
            }
        })
    });
});
