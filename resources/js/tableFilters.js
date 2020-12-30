$(document).ready(function(){

    // Filters German Files

    $("#searchFileDe").on("keyup", function() {
        let value = $(this).val().toLowerCase();
        $("#translationCourseFilesDe tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#filterTypeDe").change(function () {
        let value = $('#filterTypeDe').val();
        if (value != "all") $("#translationCourseFilesDe tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
        else $("#translationCourseFilesDe tr").show();
    })

    // Filters English Files

    $("#searchFileEn").on("keyup", function() {
        let value = $(this).val().toLowerCase();
        $("#translationCourseFilesEn tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#filterTypeEn").change(function () {
        let value = $('#filterTypeEn').val();
        if (value != "all") $("#translationCourseFilesEn tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
        else $("#translationCourseFilesEn tr").show();
    })

    // Filters French Files

    $("#searchFileFr").on("keyup", function() {
        let value = $(this).val().toLowerCase();
        $("#translationCourseFilesFr tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#filterTypeFr").change(function () {
        let value = $('#filterTypeFr').val();
        if (value != "all") $("#translationCourseFilesFr tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
        else $("#translationCourseFilesFr tr").show();
    })

    // Filters Russian Files

    $("#searchFileRu").on("keyup", function() {
        let value = $(this).val().toLowerCase();
        $("#translationCourseFilesRu tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#filterTypeRu").change(function () {
        let value = $('#filterTypeRu').val();
        if (value != "all") $("#translationCourseFilesRu tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
        else $("#translationCourseFilesRu tr").show();
    })

    // Filters Spanish Files

    $("#searchFileEs").on("keyup", function() {
        let value = $(this).val().toLowerCase();
        $("#translationCourseFilesEs tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#filterTypeEs").change(function () {
        let value = $('#filterTypeEs').val();
        if (value != "all") $("#translationCourseFilesEs tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
        else $("#translationCourseFilesEs tr").show();
    })

    // Filters Portuguese Files

    $("#searchFilePt").on("keyup", function() {
        let value = $(this).val().toLowerCase();
        $("#translationCourseFilesPt tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });

    $("#filterTypePt").change(function () {
        let value = $('#filterTypePt').val();
        if (value != "all") $("#translationCourseFilesPt tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
        else $("#translationCourseFilesPt tr").show();
    })
});

